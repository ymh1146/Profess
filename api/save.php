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
    $newData = json_decode($input, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['ok' => 0, 'err' => 'invalid_json']);
        exit;
    }

    $existingData = readDataFile();
    $updatedData = $existingData;

    foreach ($newData as $key => $value) {
        if ($key === 'images') {
            $updatedData['images'] = [];
            foreach ($value as $newImg) {
                $existingImg = null;
                if (isset($existingData['images'])) {
                    foreach ($existingData['images'] as $img) {
                        $imgUrl = is_string($img) ? $img : ($img['url'] ?? '');
                        $newImgUrl = is_string($newImg) ? $newImg : ($newImg['url'] ?? '');

                        if ($imgUrl === $newImgUrl) {
                            $existingImg = $img;
                            break;
                        }
                    }
                }

                if (is_array($newImg)) {
                    if (empty($newImg['text']) && $existingImg && is_array($existingImg) && !empty($existingImg['text'])) {
                        $updatedData['images'][] = [
                            'url' => $newImg['url'],
                            'text' => $existingImg['text']
                        ];
                    } else {
                        $updatedData['images'][] = $newImg;
                    }
                } else {
                    $updatedData['images'][] = $newImg;
                }
            }
        } else {
            $updatedData[$key] = $value;
        }
    }

    if (writeDataFile($updatedData)) {
        echo json_encode(['ok' => 1]);
    } else {
        http_response_code(500);
        echo json_encode(['ok' => 0, 'err' => 'write_failed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['ok' => 0, 'err' => 'save_error']);
}
?>