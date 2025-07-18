<?php
require_once '../config/config.php';
require_once '../config/auth.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['ok' => 0, 'err' => 'method_not_allowed']);
    exit;
}

try {
    $authenticated = isAuthenticated();
    $sessionInfo = $authenticated ? getSessionInfo() : null;

    $response = [
        'ok' => 1,
        'authenticated' => $authenticated,
        'sessionId' => $authenticated ? getSessionId() : null
    ];

    if ($authenticated && $sessionInfo) {
        $response['session'] = [
            'expires_in' => $sessionInfo['expires_in'],
            'created' => date('Y-m-d H:i:s', $sessionInfo['created']),
            'last_activity' => date('Y-m-d H:i:s', $sessionInfo['last_activity'])
        ];
    }

    echo json_encode($response);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['ok' => 0, 'err' => 'server_error']);
}
?>