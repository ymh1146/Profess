<?php
require_once '../config/config.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['ok' => 0, 'err' => 'method_not_allowed']);
    exit;
}

try {
    $data = readDataFile();
    $data['uploadedAudios'] = getUploadedAudios();

    if (!isset($data['musicUrl']))
        $data['musicUrl'] = '';
    if (!isset($data['images']))
        $data['images'] = [];
    if (!isset($data['title']))
        $data['title'] = '💕Profess - 为你而来';
    if (!isset($data['subtitle']))
        $data['subtitle'] = '每一个瞬间，都是为了遇见你';
    if (!isset($data['floatingStyle']))
        $data['floatingStyle'] = 'hearts';

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['ok' => 0, 'err' => 'data_read_error']);
}
?>