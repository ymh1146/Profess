<?php
require_once __DIR__ . '/env.php';
loadEnv(dirname(__DIR__) . '/.env');

// 定义调试模式
$debugMode = $_ENV['DEBUG_MODE'] ?? getenv('DEBUG_MODE') ?? 'false';
define('DEBUG_MODE', filter_var($debugMode, FILTER_VALIDATE_BOOLEAN));

// 定义路径常量
define('ROOT_PATH', dirname(__DIR__));
define('UPLOADS_PATH', ROOT_PATH . '/uploads');
define('DATA_FILE', ROOT_PATH . '/data/data.json');
define('ADMIN_PASSWORD', $_ENV['ADMIN_PASSWORD'] ?? getenv('ADMIN_PASSWORD') ?? 'admin123');
define('SESSION_TIMEOUT', 2 * 60 * 60);
define('ALLOWED_IMAGE_TYPES', ['jpeg', 'jpg', 'png', 'gif', 'webp']);
define('ALLOWED_AUDIO_TYPES', ['mp3', 'wav', 'ogg', 'm4a', 'aac']);
$enableFrontLogin = $_ENV['ENABLE_FRONT_LOGIN'] ?? getenv('ENABLE_FRONT_LOGIN') ?? 'true';
define('ENABLE_FRONT_LOGIN', filter_var($enableFrontLogin, FILTER_VALIDATE_BOOLEAN));
define('FRONT_PASSWORD', $_ENV['FRONT_PASSWORD'] ?? getenv('FRONT_PASSWORD') ?? 'love520');

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Shanghai');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!file_exists(UPLOADS_PATH)) {
    mkdir(UPLOADS_PATH, 0755, true);
}

function isAjaxRequest()
{
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' ||
        !empty($_SERVER['HTTP_HX_REQUEST']);
}



function generateSessionId()
{
    return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 16) . time();
}

function isImageFile($filename)
{
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($extension, ALLOWED_IMAGE_TYPES);
}

function isAudioFile($filename)
{
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($extension, ALLOWED_AUDIO_TYPES);
}

function readDataFile()
{
    if (!file_exists(DATA_FILE)) {
        return ['musicUrl' => '', 'images' => [], 'uploadedAudios' => []];
    }

    $content = file_get_contents(DATA_FILE);
    $data = json_decode($content, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['musicUrl' => '', 'images' => [], 'uploadedAudios' => []];
    }

    return $data;
}

function writeDataFile($data)
{
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents(DATA_FILE, $json) !== false;
}

function getUploadedAudios()
{
    $audios = [];
    if (is_dir(UPLOADS_PATH)) {
        $files = scandir(UPLOADS_PATH);
        
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..' && isAudioFile($file)) {
                $audios[] = [
                    'url' => 'uploads/' . $file,
                    'type' => 'audio',
                    'originalName' => $file
                ];
            }
        }
    }
    return $audios;
}

function generateOneTimeToken()
{
    return bin2hex(random_bytes(32)) . '_' . time();
}

function saveOneTimeToken($token)
{
    $tokenFile = ROOT_PATH . '/data/tokens/' . hash('sha256', $token) . '.token';
    $tokenDir = dirname($tokenFile);

    if (!is_dir($tokenDir)) {
        mkdir($tokenDir, 0755, true);
    }

    $data = [
        'token' => $token,
        'created' => time(),
        'expires' => time() + 300
    ];

    return file_put_contents($tokenFile, json_encode($data)) !== false;
}

function verifyOneTimeToken($token)
{
    if (empty($token)) {
        return false;
    }

    $tokenFile = ROOT_PATH . '/data/tokens/' . hash('sha256', $token) . '.token';

    if (!file_exists($tokenFile)) {
        return false;
    }

    $data = json_decode(file_get_contents($tokenFile), true);

    if (!$data || $data['token'] !== $token) {
        @unlink($tokenFile);
        return false;
    }

    if (time() > $data['expires']) {
        @unlink($tokenFile);
        return false;
    }
    
    @unlink($tokenFile);
    return true;
}

function cleanExpiredTokens()
{
    $tokenDir = ROOT_PATH . '/data/tokens/';
    if (!is_dir($tokenDir)) {
        return;
    }

    $files = glob($tokenDir . '*.token');
    $now = time();

    foreach ($files as $file) {
        $data = json_decode(file_get_contents($file), true);
        if (!$data || $now > $data['expires']) {
            @unlink($file);
        }
    }
}
?>
