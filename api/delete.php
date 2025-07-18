<?php
require_once '../config/config.php';
require_once '../config/auth.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => 0, 'err' => 'method_not_allowed']);
    exit;
}

try {
    requireAuth();

    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (json_last_error() !== JSON_ERROR_NONE || !isset($data['url'])) {
        http_response_code(400);
        echo json_encode(['ok' => 0, 'err' => 'invalid_request']);
        exit;
    }

    $url = $data['url'];
    $fileDeleted = false;
    $dataUpdated = false;

    // 检查是否是本地上传的文件
    if (strpos($url, '/uploads/') !== false || strpos($url, 'uploads/') === 0) {
        $fileName = basename($url);
        $filePath = UPLOADS_PATH . '/' . $fileName;

        if (file_exists($filePath)) {
            $realUploadsPath = realpath(UPLOADS_PATH);
            $realFilePath = realpath($filePath);

            if ($realFilePath && $realUploadsPath && strpos($realFilePath, $realUploadsPath) === 0) {
                $fileDeleted = unlink($realFilePath);
            } else {
                $fileDeleted = false;
            }
        } else {
            $fileDeleted = true;
        }
    } else {
        $fileDeleted = true;
    }

    try {
        $currentData = readDataFile();

        if (isset($currentData['images'])) {
            $currentData['images'] = array_values(array_filter($currentData['images'], function ($item) use ($url) {
                $itemUrl = is_string($item) ? $item : $item['url'];
                return $itemUrl !== $url;
            }));
        }

        if (isset($currentData['uploadedAudios'])) {
            $currentData['uploadedAudios'] = array_values(array_filter($currentData['uploadedAudios'], function ($item) use ($url) {
                return $item['url'] !== $url && basename($item['url']) !== $url;
            }));
        }

        if (isset($currentData['localMusicUrl']) && $currentData['localMusicUrl'] === $url) {
            $currentData['localMusicUrl'] = '';
        }

        $dataUpdated = writeDataFile($currentData);
    } catch (Exception $e) {
        $dataUpdated = false;
    }

    if ($fileDeleted && $dataUpdated) {
        echo json_encode(['ok' => 1, 'message' => '资源已删除']);
    } else {
        http_response_code(500);
        echo json_encode(['ok' => 0, 'err' => 'delete_failed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['ok' => 0, 'err' => 'delete_error']);
}
?>