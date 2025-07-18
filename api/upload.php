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

    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('文件上传失败');
    }

    $file = $_FILES['file'];
    $fileName = $file['name'];
    $fileSize = $file['size'];
    $fileTmpName = $file['tmp_name'];

    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $allowedImageTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $allowedAudioTypes = ['mp3', 'wav', 'ogg', 'm4a', 'aac'];
    $allowedTypes = array_merge($allowedImageTypes, $allowedAudioTypes);

    if (!in_array($extension, $allowedTypes)) {
        throw new Exception('不支持的文件类型: ' . $extension);
    }

    $maxSize = 50 * 1024 * 1024;
    if ($fileSize > $maxSize) {
        throw new Exception('文件大小超过限制 (50MB)');
    }

    $uniqueName = time() . '_' . uniqid() . '.' . $extension;
    $uploadPath = UPLOADS_PATH . '/' . $uniqueName;

    if (!is_dir(UPLOADS_PATH)) {
        if (!mkdir(UPLOADS_PATH, 0755, true)) {
            throw new Exception('无法创建上传目录');
        }
    }

    if (!move_uploaded_file($fileTmpName, $uploadPath)) {
        throw new Exception('文件保存失败');
    }

    $fileType = in_array($extension, $allowedImageTypes) ? 'image' : 'audio';

    if ($fileType === 'audio') {
        $data = readDataFile();
        if (!isset($data['uploadedAudios'])) {
            $data['uploadedAudios'] = [];
        }

        // 获取相对于网站根目录的路径用于存储
        $baseUrl = isset($_SERVER['SCRIPT_NAME']) ? dirname(dirname($_SERVER['SCRIPT_NAME'])) : '';
        if ($baseUrl === '/') $baseUrl = '';
        
        $data['uploadedAudios'][] = [
            'url' => 'uploads/' . $uniqueName,
            'originalName' => $fileName
        ];

        writeDataFile($data);
    }

    // 获取相对于网站根目录的路径
    $baseUrl = isset($_SERVER['SCRIPT_NAME']) ? dirname(dirname($_SERVER['SCRIPT_NAME'])) : '';
    if ($baseUrl === '/') $baseUrl = '';
    
    echo json_encode([
        'ok' => 1,
        'url' => 'uploads/' . $uniqueName,
        'originalName' => $fileName,
        'type' => $fileType
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['ok' => 0, 'err' => 'server_error', 'message' => $e->getMessage()]);
}
?>