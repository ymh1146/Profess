<?php
require_once __DIR__ . '/config.php';

function isAuthenticated($refreshSession = true)
{
    if (!isset($_SESSION['admin_session'])) {
        return false;
    }

    $session = $_SESSION['admin_session'];
    $now = time();

    // 检查session是否过期
    if ($now - $session['created'] > SESSION_TIMEOUT) {
        unset($_SESSION['admin_session']);
        return false;
    }

    if (isset($session['last_activity']) && $now - $session['last_activity'] > 1800) {
        unset($_SESSION['admin_session']);
        return false;
    }

    // 刷新最后活跃时间
    if ($refreshSession) {
        $_SESSION['admin_session']['last_activity'] = $now;

        if ($now - $session['created'] > 3600 && !isset($session['regenerated'])) {
            session_regenerate_id(true);
            $_SESSION['admin_session']['regenerated'] = true;
        }
    }

    return true;
}

function login($password)
{
    if ($password === ADMIN_PASSWORD) {
        $now = time();
        $_SESSION['admin_session'] = [
            'id' => generateSessionId(),
            'created' => $now,
            'last_activity' => $now,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'regenerated' => false
        ];

        session_regenerate_id(true);

        return true;
    }
    return false;
}

function logout()
{
    unset($_SESSION['admin_session']);
    session_destroy();
}

function getSessionId()
{
    return $_SESSION['admin_session']['id'] ?? null;
}

function validateSessionSecurity()
{
    if (!isset($_SESSION['admin_session'])) {
        return false;
    }

    $session = $_SESSION['admin_session'];

    $currentIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    if (isset($session['ip']) && $session['ip'] !== $currentIp) {
        error_log("Session IP changed: {$session['ip']} -> {$currentIp}");
    }

    $currentUserAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    if (isset($session['user_agent']) && $session['user_agent'] !== $currentUserAgent) {
        unset($_SESSION['admin_session']);
        return false;
    }

    return true;
}

function getSessionInfo()
{
    if (!isset($_SESSION['admin_session'])) {
        return null;
    }

    $session = $_SESSION['admin_session'];
    return [
        'id' => $session['id'],
        'created' => $session['created'],
        'last_activity' => $session['last_activity'] ?? $session['created'],
        'expires_in' => SESSION_TIMEOUT - (time() - $session['created']),
        'ip' => $session['ip'] ?? 'unknown'
    ];
}

function requireAuth()
{
    $sessionId = $_SERVER['HTTP_X_SESSION_ID'] ?? $_GET['session'] ?? $_POST['session'] ?? null;

    if (!validateSessionSecurity()) {
        redirectToLogin('security_violation');
        return false;
    }

    if (!$sessionId && isAuthenticated()) {
        return true;
    }

    if ($sessionId) {
        $currentSessionId = getSessionId();
        if ($currentSessionId && $currentSessionId === $sessionId && isAuthenticated()) {
            return true;
        }
    }

    redirectToLogin('unauthorized');
    return false;
}

function redirectToLogin($reason = 'unauthorized')
{
    $baseUrl = isset($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : '';
    if ($baseUrl === '/') $baseUrl = '';

    if (isAjaxRequest()) {
        http_response_code(401);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => 0, 'err' => $reason]);
        exit;
    } else {
        $scriptPath = $_SERVER['SCRIPT_NAME'] ?? '';
        $dirDepth = substr_count(dirname($scriptPath), '/');
        $relativePath = str_repeat('../', $dirDepth) . 'login.php';

        if ($reason !== 'unauthorized') {
            $relativePath .= '?error=' . $reason;
        }

        header('Location: ' . $relativePath);
        exit;
    }
}

function logLoginAttempt($success, $ip = null)
{
    $ip = $ip ?: ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    $timestamp = date('Y-m-d H:i:s');
    $status = $success ? 'SUCCESS' : 'FAILED';

    $logEntry = "[$timestamp] LOGIN $status from $ip\n";

    $logFile = ROOT_PATH . '/logs/auth.log';
    if (!file_exists(dirname($logFile))) {
        mkdir(dirname($logFile), 0755, true);
    }

    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

function checkLoginAttempts($ip = null)
{
    $ip = $ip ?: ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    $key = 'login_attempts_' . md5($ip);

    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = ['count' => 0, 'last_attempt' => 0, 'first_attempt' => 0];
    }

    $attempts = $_SESSION[$key];
    $now = time();

    if ($attempts['last_attempt'] > 0 && ($now - $attempts['last_attempt']) >= 3600) {
        $_SESSION[$key] = ['count' => 0, 'last_attempt' => 0, 'first_attempt' => 0];
        return true;
    }

    if ($attempts['count'] >= 8 && ($now - $attempts['last_attempt']) < 3600) {
        return false;
    } elseif ($attempts['count'] >= 5 && ($now - $attempts['last_attempt']) < 900) {
        return false;
    } elseif ($attempts['count'] >= 3 && ($now - $attempts['last_attempt']) < 300) {
        return false;
    }

    return true;
}

function recordLoginFailure($ip = null)
{
    $ip = $ip ?: ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    $key = 'login_attempts_' . md5($ip);
    $now = time();

    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = ['count' => 0, 'last_attempt' => 0, 'first_attempt' => $now];
    }

    $_SESSION[$key]['count']++;
    $_SESSION[$key]['last_attempt'] = $now;

    if ($_SESSION[$key]['first_attempt'] == 0) {
        $_SESSION[$key]['first_attempt'] = $now;
    }

    logLoginAttempt(false, $ip);
}

function clearLoginFailures($ip = null)
{
    $ip = $ip ?: ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    $key = 'login_attempts_' . md5($ip);

    unset($_SESSION[$key]);
    logLoginAttempt(true, $ip);
}
?>
