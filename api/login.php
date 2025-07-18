<?php
require_once '../config/config.php';
require_once '../config/auth.php';

$isAjaxRequest = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' ||
    !empty($_SERVER['HTTP_HX_REQUEST']);

if ($isAjaxRequest) {
    header('Content-Type: application/json; charset=utf-8');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    $baseUrl = isset($_SERVER['SCRIPT_NAME']) ? dirname(dirname($_SERVER['SCRIPT_NAME'])) : '';
    if ($baseUrl === '/') $baseUrl = '';
    
    if ($isAjaxRequest) {
        echo json_encode(['ok' => 0, 'err' => 'method_not_allowed']);
    } else {
        header('Location: ../login.php?error=method_not_allowed');
    }
    exit;
}

try {
    $clientIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

    if (!checkLoginAttempts($clientIp)) {
        http_response_code(429);
        if ($isAjaxRequest) {
            echo json_encode(['ok' => 0, 'err' => 'too_many_attempts']);
        } else {
            header('Location: ../login.php?error=too_many_attempts');
        }
        exit;
    }

    $password = $_POST['password'] ?? '';

    if (empty($password)) {
        http_response_code(400);
        if ($isAjaxRequest) {
            echo json_encode(['ok' => 0, 'err' => 'missing_password']);
        } else {
            header('Location: ../login.php?error=missing_password');
        }
        exit;
    }

    if (login($password)) {
        $sessionId = getSessionId();
        clearLoginFailures($clientIp);

        if ($isAjaxRequest) {
            echo json_encode(['ok' => 1, 'sessionId' => $sessionId]);
        } else {
            header('Location: ../admin.php');
        }
    } else {
        recordLoginFailure($clientIp);
        http_response_code(401);

        if ($isAjaxRequest) {
            echo json_encode(['ok' => 0, 'err' => 'invalid_password']);
        } else {
            header('Location: ../login.php?error=invalid_password');
        }
    }
} catch (Exception $e) {
    http_response_code(500);

    if ($isAjaxRequest) {
        echo json_encode(['ok' => 0, 'err' => 'server_error']);
    } else {
        header('Location: ../login.php?error=server_error');
    }
}
?>