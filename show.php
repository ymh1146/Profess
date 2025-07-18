<?php
ob_start();

error_reporting(0);
ini_set('display_errors', 0);

require_once 'config/config.php';

// 检查是否启用了前台登录
if (ENABLE_FRONT_LOGIN) {
    $token = $_GET['token'] ?? '';

    if (!verifyOneTimeToken($token)) {
        // Token无效或不存在，重定向到登录页面
        header('Location: index.php');
        exit;
    }
}

try {
    $data = readDataFile();

    if (!isset($data['title'])) {
        $data['title'] = '💕Profess - 为你而来';
    }
    if (!isset($data['subtitle'])) {
        $data['subtitle'] = '每一个瞬间，都是为了遇见你';
    }
    if (!isset($data['floatingStyle'])) {
        $data['floatingStyle'] = 'hearts';
    }
    if (!isset($data['images'])) {
        $data['images'] = [];
    }
} catch (Exception $e) {
    $data = [
        'title' => '💕Profess - 为你而来',
        'subtitle' => '每一个瞬间，都是为了遇见你',
        'musicUrl' => '',
        'localMusicUrl' => '',
        'images' => [],
        'floatingStyle' => 'hearts'
    ];
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>💕Profess ‌- 表白时刻 - github @ymh1146 Profess‌</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body, html {
            height: 100%;
            overflow: hidden;
            font-family: 'Microsoft YaHei', sans-serif;
            background: #000;
        }
        
        #slider {
            position: relative;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
        }
        
        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0;
        }
        
        .slide.active {
            opacity: 1;
        }

        .image-text-effect {
            position: absolute;
            color: white;
            font-size: 1.8rem;
            font-weight: 600;
            text-shadow:
                1px 1px 3px rgba(0, 0, 0, 0.4),
                0 0 5px rgba(0, 0, 0, 0.3);
            z-index: 15;
            pointer-events: none;
            white-space: nowrap;
            animation: textFloat 3s ease-in-out infinite;
            filter: drop-shadow(1px 1px 2px rgba(0, 0, 0, 0.3));
        }

        @keyframes textFloat {
            0%, 100% {
                transform: translateY(0) scale(1);
                opacity: 0.9;
            }
            50% {
                transform: translateY(-10px) scale(1.05);
                opacity: 1;
            }
        }

        .text-effect-1 {
            animation: textPulse 2s ease-in-out infinite;
        }

        .text-effect-2 {
            animation: textGlow 3s ease-in-out infinite;
        }

        .text-effect-3 {
            animation: textBounce 2.5s ease-in-out infinite;
        }

        .text-effect-4 {
            animation: textShake 1.5s ease-in-out infinite;
        }

        .text-effect-5 {
            animation: textSlide 3s ease-in-out infinite;
        }

        .text-effect-6 {
            animation: textZoom 2s ease-in-out infinite;
        }

        .text-effect-7 {
            animation: textFade 2.5s ease-in-out infinite;
        }

        .text-effect-8 {
            animation: textWave 3s ease-in-out infinite;
        }

        .text-effect-9 {
            animation: textFlip 2s ease-in-out infinite;
        }

        .text-effect-10 {
            animation: textBreath 3.5s ease-in-out infinite;
        }

        .text-effect-11 {
            animation: textSwing 2.5s ease-in-out infinite;
        }

        .text-effect-12 {
            animation: textRipple 3s ease-in-out infinite;
        }

        @keyframes textPulse {
            0%, 100% {
                transform: scale(1);
                opacity: 0.8;
            }
            50% {
                transform: scale(1.1);
                opacity: 1;
            }
        }

        @keyframes textGlow {
            0%, 100% {
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
                opacity: 0.9;
            }
            50% {
                text-shadow: 0 0 20px rgba(255, 255, 255, 0.8), 2px 2px 4px rgba(0, 0, 0, 0.8);
                opacity: 1;
            }
        }

        @keyframes textBounce {
            0%, 100% {
                transform: translateY(0);
            }
            25% {
                transform: translateY(-8px);
            }
            50% {
                transform: translateY(0);
            }
            75% {
                transform: translateY(-4px);
            }
        }

        @keyframes textShake {
            0%, 100% {
                transform: translateX(0);
            }
            10% {
                transform: translateX(-2px);
            }
            20% {
                transform: translateX(2px);
            }
            30% {
                transform: translateX(-2px);
            }
            40% {
                transform: translateX(2px);
            }
            50% {
                transform: translateX(-1px);
            }
            60% {
                transform: translateX(1px);
            }
            70% {
                transform: translateX(-1px);
            }
            80% {
                transform: translateX(1px);
            }
            90% {
                transform: translateX(0);
            }
        }

        @keyframes textSlide {
            0%, 100% {
                transform: translateX(0);
            }
            25% {
                transform: translateX(10px);
            }
            75% {
                transform: translateX(-10px);
            }
        }

        @keyframes textZoom {
            0%, 100% {
                transform: scale(1);
            }
            25% {
                transform: scale(1.1);
            }
            50% {
                transform: scale(0.9);
            }
            75% {
                transform: scale(1.05);
            }
        }

        @keyframes textFade {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.3;
            }
        }

        @keyframes textWave {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            25% {
                transform: translateY(-5px) rotate(2deg);
            }
            50% {
                transform: translateY(0) rotate(0deg);
            }
            75% {
                transform: translateY(-3px) rotate(-2deg);
            }
        }

        @keyframes textFlip {
            0%, 100% {
                transform: rotateY(0deg);
            }
            50% {
                transform: rotateY(180deg);
            }
        }

        @keyframes textBreath {
            0%, 100% {
                transform: scale(1);
                opacity: 0.9;
            }
            50% {
                transform: scale(1.08);
                opacity: 1;
            }
        }

        @keyframes textSwing {
            0%, 100% {
                transform: rotate(0deg);
                transform-origin: center top;
            }
            25% {
                transform: rotate(5deg);
            }
            75% {
                transform: rotate(-5deg);
            }
        }

        @keyframes textRipple {
            0%, 100% {
                transform: scale(1);
                text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4);
            }
            25% {
                transform: scale(1.05);
                text-shadow: 0 0 8px rgba(255, 255, 255, 0.6), 1px 1px 3px rgba(0, 0, 0, 0.4);
            }
            50% {
                transform: scale(1.1);
                text-shadow: 0 0 15px rgba(255, 255, 255, 0.8), 1px 1px 3px rgba(0, 0, 0, 0.4);
            }
            75% {
                transform: scale(1.05);
                text-shadow: 0 0 8px rgba(255, 255, 255, 0.6), 1px 1px 3px rgba(0, 0, 0, 0.4);
            }
        }
        
        .slide::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                45deg,
                rgba(255, 182, 193, 0.3) 0%,
                rgba(255, 192, 203, 0.2) 25%,
                rgba(255, 105, 180, 0.3) 50%,
                rgba(255, 20, 147, 0.2) 75%,
                rgba(199, 21, 133, 0.3) 100%
            );
            z-index: 1;
        }
        
        .love-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 10;
        }
        
        .floating-element {
            position: absolute;
            color: rgba(255, 255, 255, 0.8);
            font-size: 20px;
            pointer-events: none;
            z-index: 5;
        }

        /* 花瓣样式 */
        .petal {
            width: 12px;
            height: 20px;
            background: linear-gradient(45deg, #FFB6C1, #FF69B4);
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            box-shadow: 0 0 8px rgba(255, 182, 193, 0.6);
        }

        /* 蝴蝶样式 */
        .butterfly {
            width: 16px;
            height: 12px;
            position: relative;
        }
        .butterfly::before,
        .butterfly::after {
            content: '';
            position: absolute;
            width: 8px;
            height: 6px;
            background: linear-gradient(45deg, #FF69B4, #87CEEB, #FFD700);
            border-radius: 50%;
            box-shadow: 0 0 6px rgba(255, 105, 180, 0.5);
        }
        .butterfly::before {
            top: 0;
            left: 0;
        }
        .butterfly::after {
            top: 0;
            right: 0;
        }

        /* 星星样式 */
        .star {
            width: 16px;
            height: 16px;
            background: #FFD700;
            position: relative;
            transform: rotate(45deg);
            box-shadow: 0 0 15px #FFD700, 0 0 25px #FFD700;
            animation: starTwinkle 1.5s ease-in-out infinite alternate;
        }
        .star::before {
            content: '';
            position: absolute;
            top: -8px;
            left: 0;
            width: 16px;
            height: 16px;
            background: #FFD700;
            transform: rotate(90deg);
        }
        @keyframes starTwinkle {
            from {
                opacity: 0.7;
                transform: rotate(45deg) scale(0.8);
                box-shadow: 0 0 10px #FFD700, 0 0 20px #FFD700;
            }
            to {
                opacity: 1;
                transform: rotate(45deg) scale(1.2);
                box-shadow: 0 0 20px #FFD700, 0 0 35px #FFD700;
            }
        }

        /* 泡泡样式 */
        .bubble {
            width: 16px;
            height: 16px;
            background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.8), rgba(255,255,255,0.1));
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            box-shadow: 0 0 8px rgba(255,255,255,0.4);
        }

        /* 雪花样式 */
        .snowflake {
            width: 12px;
            height: 12px;
            background: #FFFFFF;
            position: relative;
            border-radius: 50%;
            box-shadow: 0 0 6px rgba(255,255,255,0.8);
        }
        .snowflake::before,
        .snowflake::after {
            content: '';
            position: absolute;
            width: 2px;
            height: 12px;
            background: #FFFFFF;
            left: 5px;
            top: 0;
        }
        .snowflake::before {
            transform: rotate(60deg);
        }
        .snowflake::after {
            transform: rotate(-60deg);
        }

        /* 萤火虫样式 */
        .firefly {
            width: 6px;
            height: 6px;
            background: #ADFF2F;
            border-radius: 50%;
            box-shadow: 0 0 12px #ADFF2F, 0 0 20px #9AFF9A;
            animation: glow 1s ease-in-out infinite alternate;
        }
        @keyframes glow {
            from { box-shadow: 0 0 8px #ADFF2F, 0 0 16px #9AFF9A; }
            to { box-shadow: 0 0 16px #ADFF2F, 0 0 24px #9AFF9A; }
        }

        /* 羽毛样式 */
        .feather {
            width: 4px;
            height: 16px;
            background: linear-gradient(to bottom, #FFFFFF, #F5F5F5);
            border-radius: 50% 50% 50% 0;
            position: relative;
            box-shadow: 0 0 4px rgba(255,255,255,0.6);
        }
        .feather::before {
            content: '';
            position: absolute;
            width: 2px;
            height: 16px;
            background: #E0E0E0;
            left: 1px;
            top: 0;
        }

        /* 彩虹飘带样式 */
        .rainbow {
            width: 20px;
            height: 4px;
            background: linear-gradient(to right, #FF0000, #FF7F00, #FFFF00, #00FF00, #0000FF, #4B0082, #9400D3);
            border-radius: 2px;
            box-shadow: 0 0 8px rgba(255,255,255,0.5);
        }
        
        .text-overlay {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            z-index: 20;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            transition: opacity 1s ease-out, transform 1s ease-out;
        }

        .text-overlay.hidden {
            opacity: 0;
            transform: translate(-50%, -50%) translateY(-20px);
            pointer-events: none;
        }
        
        .love-title {
            font-size: 3rem;
            font-weight: 300;
            margin-bottom: 20px;
            opacity: 0;
            animation: fadeInUp 2s ease-out 1s forwards;
        }
        
        .love-subtitle {
            font-size: 1.5rem;
            font-weight: 200;
            opacity: 0;
            animation: fadeInUp 2s ease-out 2s forwards;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .progress-bar {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
            z-index: 30;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #ff6b9d, #c44569);
            border-radius: 2px;
            width: 0%;
            transition: width 0.3s ease;
        }
        
        .controls {
            position: fixed;
            bottom: 50px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 30;
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .speed-control {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 25px;
            padding: 8px 15px;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .speed-label {
            color: white;
            font-size: 12px;
            white-space: nowrap;
        }

        .speed-slider {
            width: 80px;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
            outline: none;
            -webkit-appearance: none;
            cursor: pointer;
        }

        .speed-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            background: white;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .speed-slider::-moz-range-thumb {
            width: 16px;
            height: 16px;
            background: white;
            border-radius: 50%;
            cursor: pointer;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .control-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.5);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .control-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.8);
            transform: scale(1.1);
        }
        
        .start-guide {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            color: white;
            text-align: center;
            cursor: pointer;
            transition: opacity 0.5s ease;
        }

        .start-guide.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .guide-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }

        .guide-title {
            font-size: 2rem;
            margin-bottom: 15px;
            font-weight: 300;
        }

        .guide-subtitle {
            font-size: 1.2rem;
            opacity: 0.8;
            margin-bottom: 30px;
        }

        .guide-hint {
            font-size: 1rem;
            opacity: 0.6;
            animation: fadeInOut 3s infinite;
        }

        @keyframes fadeInOut {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }

        @media (max-width: 768px) {
            .love-title {
                font-size: 2rem;
            }
            .love-subtitle {
                font-size: 1.2rem;
            }
            .controls {
                bottom: 40px;
            }
            .control-btn {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
            .guide-icon {
                font-size: 3rem;
            }
            .guide-title {
                font-size: 1.5rem;
            }
            .guide-subtitle {
                font-size: 1rem;
            }
        }
    </style>
    
    <!-- 将后端数据注入到全局JS变量 -->
    <script>
        const galData = <?php echo json_encode($data, JSON_UNESCAPED_UNICODE); ?>;
    </script>
</head>
<body>
    <!-- 播放引导界面 -->
    <div class="start-guide" id="startGuide">
        <div class="guide-icon">💕</div>
        <h1 class="guide-title">准备好开始了吗？</h1>
        <p class="guide-subtitle">点击任意位置开始播放</p>
        <p class="guide-hint">
            使用方向键 ← → 或滚轮切换图片<br>
            空格键暂停/播放，M键控制音乐
        </p>
    </div>

    <div id="slider">
        <!-- 动态生成的图片幻灯片 -->
    </div>
    
    <div class="love-overlay" id="loveOverlay">
        <!-- 动态生成的爱心特效 -->
    </div>
    
    <div class="text-overlay" id="textOverlay">
        <h1 class="love-title"><?php echo htmlspecialchars($data['title'] ?? '💕Profess - 为你而来'); ?></h1>
        <p class="love-subtitle"><?php echo htmlspecialchars($data['subtitle'] ?? '每一个瞬间，都是为了遇见你'); ?></p>
    </div>
    
    <div class="controls">
        <button class="control-btn" id="playBtn" title="播放/暂停">⏸</button>
        <button class="control-btn" id="musicBtn" title="音乐开/关">🎵</button>
        <div class="speed-control">
            <span class="speed-label">速度</span>
            <input type="range" id="speedSlider" class="speed-slider" min="0" max="100" step="1" value="50">
            <span class="speed-label" id="speedValue">1.0x</span>
        </div>
    </div>
    
    <a href="https://github.com/ymh1146/Profess" target="_blank" class="github-link" title="查看源代码">
        <svg width="24" height="24" viewBox="0 0 16 16" fill="white">
            <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0 0 16 8c0-4.42-3.58-8-8-8z"/>
        </svg>
    </a>
    <style>
        .github-link {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 30;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }
        .github-link:hover {
            transform: scale(1.1);
            background: rgba(0, 0, 0, 0.5);
        }
    </style>
    
    <div class="progress-bar">
        <div class="progress-fill" id="progressFill"></div>
    </div>
    
    <audio id="player" loop preload="auto"></audio>
    
    <!-- GSAP 3.13.0 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/TextPlugin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/Observer.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/MotionPathPlugin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/Flip.min.js"></script>
    
    <!-- GSAP插件注册 -->
    <script>
        if (typeof TextPlugin !== 'undefined') {
            gsap.registerPlugin(TextPlugin);
        }

        if (typeof ScrollTrigger !== 'undefined') {
            gsap.registerPlugin(ScrollTrigger);
        }

        if (typeof Observer !== 'undefined') {
            gsap.registerPlugin(Observer);
        }

        if (typeof MotionPathPlugin !== 'undefined') {
            gsap.registerPlugin(MotionPathPlugin);
        }

        if (typeof Flip !== 'undefined') {
            gsap.registerPlugin(Flip);
        }

    </script>

    <?php
    $baseUrl = isset($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : '';
    if ($baseUrl === '/') $baseUrl = '';
    ?>
    <script src="js/orientation.js"></script>
    <script src="js/emoji.js"></script>
    <script src="js/fx.js"></script>
    <script src="js/float.js"></script>
    <script src="js/textFx.js"></script>
    <script src="js/core.js"></script>
    <script src="js/show.js"></script>
</body>
</html>