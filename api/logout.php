<?php
require_once '../config/config.php';
require_once '../config/auth.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    logout();

    if ($method === 'GET') {
        header('Location: ../login.php');
        exit;
    } else if ($method === 'POST') {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => 1, 'message' => 'logged_out']);
    } else {
        http_response_code(405);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => 0, 'err' => 'method_not_allowed']);
    }
} catch (Exception $e) {
    if ($method === 'GET') {
        header('Location: ../login.php?error=logout_failed');
        exit;
    } else {
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => 0, 'err' => 'server_error']);
    }
}
?>