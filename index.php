<?php
require_once 'config/config.php';

// 检查是否启用了前台登录
if (!ENABLE_FRONT_LOGIN) {
    header('Location: show.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';

    if ($password === FRONT_PASSWORD) {
        $token = generateOneTimeToken();
        if (saveOneTimeToken($token)) {
            cleanExpiredTokens();
            header('Location: show.php?token=' . urlencode($token));
            exit;
        } else {
            $error = '系统错误，请重试';
        }
    } else {
        $error = '密码不正确，请重试';
    }
}

// 获取数据以显示标题
try {
    $data = readDataFile();
    if (!isset($data['title'])) {
        $data['title'] = '💕Profess - 为你而来';
    }
    if (!isset($data['subtitle'])) {
        $data['subtitle'] = '每一个瞬间，都是为了遇见你';
    }
} catch (Exception $e) {
    $data = [
        'title' => '💕Profess - 为你而来',
        'subtitle' => '每一个瞬间，都是为了遇见你'
    ];
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($data['title']); ?>💕Profess - 登录</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            font-family: 'Microsoft YaHei', 'Noto Sans SC', sans-serif;
            background: #000;
            overflow: hidden;
        }

        .page-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }

        .login-container {
            position: relative;
            width: 100%;
            max-width: 420px;
            padding: 50px 40px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            z-index: 10;
            text-align: center;
            color: white;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                135deg,
                rgba(255, 182, 193, 0.15) 0%,
                rgba(255, 105, 180, 0.2) 30%,
                rgba(255, 20, 147, 0.15) 60%,
                rgba(199, 21, 133, 0.2) 100%
            );
            z-index: -1;
            border-radius: 24px;
        }

        .login-title {
            font-size: 2.2rem;
            font-weight: 300;
            margin-bottom: 12px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 0.8s ease-out forwards;
            line-height: 1.2;
        }

        .login-subtitle {
            font-size: 1rem;
            font-weight: 200;
            margin-bottom: 40px;
            opacity: 0;
            animation: fadeInUp 0.8s ease-out 0.2s forwards;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.4;
        }

        .form-group {
            margin-bottom: 30px;
            position: relative;
            opacity: 0;
            animation: fadeInUp 0.8s ease-out 0.4s forwards;
        }

        .form-input {
            width: 100%;
            padding: 18px 24px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            color: white;
            font-size: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            outline: none;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-input:focus {
            border-color: rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
            transform: translateY(-1px);
        }

        .login-btn {
            width: 100%;
            padding: 18px 24px;
            background: linear-gradient(135deg, #ff6b9d 0%, #c44569 100%);
            border: none;
            border-radius: 16px;
            color: white;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            animation: fadeInUp 0.8s ease-out 0.6s forwards;
            position: relative;
            overflow: hidden;
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .login-btn:hover::before {
            left: 100%;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 107, 157, 0.3);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .error-message {
            background: rgba(255, 107, 107, 0.15);
            border: 1px solid rgba(255, 107, 107, 0.3);
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 20px;
            color: #ff6b6b;
            font-size: 14px;
            opacity: 0;
            animation: fadeInUp 0.8s ease-out 0.2s forwards;
        }

        .password-hint {
            margin-top: 30px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.5);
            opacity: 0;
            animation: fadeInUp 0.8s ease-out 0.8s forwards;
            line-height: 1.5;
            padding: 16px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .github-link {
            display: inline-flex;
            align-items: center;
            padding: 10px 16px;
            background: rgba(36, 41, 46, 0.8);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-size: 12px;
            transition: all 0.3s ease;
            margin-top: 20px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            opacity: 0;
            animation: fadeInUp 0.8s ease-out 1s forwards;
        }

        .github-link:hover {
            background: rgba(27, 31, 35, 0.9);
            transform: translateY(-2px);
        }

        .github-link::before {
            content: "⭐";
            margin-right: 6px;
        }

        .github-link {
            display: inline-flex;
            align-items: center;
            padding: 10px 16px;
            background: rgba(36, 41, 46, 0.8);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-size: 12px;
            transition: all 0.3s ease;
            margin-top: 20px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            opacity: 0;
            animation: fadeInUp 0.8s ease-out 1s forwards;
        }

        .github-link:hover {
            background: rgba(27, 31, 35, 0.9);
            transform: translateY(-2px);
        }

        .github-link::before {
            content: "⭐";
            margin-right: 6px;
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

        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background: linear-gradient(135deg, #000000 0%, #1a1a2e 50%, #16213e 100%);
            overflow: hidden;
        }

        .floating-hearts {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .heart {
            position: absolute;
            opacity: 0;
            animation: floatHeart 20s linear infinite;
            filter: blur(0.5px);
        }

        @keyframes floatHeart {
            0% {
                opacity: 0;
                transform: translateY(100vh) scale(0.3) rotate(0deg);
            }
            5% {
                opacity: 0.6;
            }
            95% {
                opacity: 0.6;
            }
            100% {
                opacity: 0;
                transform: translateY(-10vh) scale(1) rotate(360deg);
            }
        }

        @media (max-width: 768px) {
            .page-container {
                padding: 15px;
            }

            .login-container {
                max-width: 100%;
                padding: 40px 30px;
            }

            .login-title {
                font-size: 1.8rem;
            }

            .login-subtitle {
                font-size: 0.9rem;
                margin-bottom: 35px;
            }

            .form-input {
                padding: 16px 20px;
                font-size: 15px;
            }

            .login-btn {
                padding: 16px 20px;
                font-size: 15px;
            }

            .password-hint {
                font-size: 12px;
                padding: 14px;
            }
        }

        @media (max-width: 480px) {
            .page-container {
                padding: 10px;
            }

            .login-container {
                padding: 35px 25px;
                border-radius: 20px;
            }

            .login-title {
                font-size: 1.6rem;
                margin-bottom: 10px;
            }

            .login-subtitle {
                font-size: 0.85rem;
                margin-bottom: 30px;
            }

            .form-group {
                margin-bottom: 25px;
            }

            .form-input {
                padding: 14px 18px;
                font-size: 14px;
                border-radius: 14px;
            }

            .login-btn {
                padding: 14px 18px;
                font-size: 14px;
                border-radius: 14px;
            }

            .password-hint {
                font-size: 11px;
                padding: 12px;
                margin-top: 25px;
            }

            .github-link {
                font-size: 10px;
                padding: 8px 12px;
            }
        }

        @media (max-width: 360px) {
            .login-container {
                padding: 30px 20px;
            }

            .login-title {
                font-size: 1.4rem;
            }

            .form-input, .login-btn {
                padding: 12px 16px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <div class="background">
        <div class="floating-hearts" id="floatingHearts"></div>
    </div>

    <div class="page-container">
        <div class="login-container">
            <h1 class="login-title"><?php echo htmlspecialchars($data['title']); ?></h1>
            <p class="login-subtitle"><?php echo htmlspecialchars($data['subtitle']); ?></p>

            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="post" action="">
                <div class="form-group">
                    <input type="password" name="password" class="form-input" placeholder="请输入访问密码" required autofocus>
                </div>

                <button type="submit" class="login-btn">进入表白空间</button>
            </form>

            <div class="password-hint">
                默认密码：love520<br>
                可通过.env设置 FRONT_PASSWORD 修改
            </div>

            <div style="text-align: center;">
                <a href="https://github.com/ymh1146/Profess" target="_blank" class="github-link" title="查看源代码">GitHub</a>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('floatingHearts');
            const colors = ['#ff6b9d', '#c44569', '#ff9ff3', '#f368e0', '#ff9a9e', '#ffa8cc'];
            const hearts = ['💕', '💖', '💗', '💝', '💘'];
            const isMobile = window.innerWidth <= 768;
            const maxHearts = isMobile ? 8 : 15;
            let heartCount = 0;

            function createHeart() {
                if (heartCount >= maxHearts) return;

                const heart = document.createElement('div');
                heart.className = 'heart';
                heart.innerHTML = hearts[Math.floor(Math.random() * hearts.length)];

                const size = isMobile ?
                    12 + Math.random() * 8 :
                    15 + Math.random() * 15;
                const color = colors[Math.floor(Math.random() * colors.length)];
                const left = 5 + Math.random() * 90;
                const animationDuration = 18 + Math.random() * 12;
                const delay = Math.random() * 3;

                heart.style.cssText = `
                    left: ${left}%;
                    font-size: ${size}px;
                    color: ${color};
                    animation-duration: ${animationDuration}s;
                    animation-delay: ${delay}s;
                `;

                container.appendChild(heart);
                heartCount++;

                setTimeout(() => {
                    if (heart.parentNode) {
                        heart.remove();
                        heartCount--;
                    }
                }, (animationDuration + delay) * 1000);
            }

            for (let i = 0; i < Math.min(maxHearts, 8); i++) {
                setTimeout(() => createHeart(), i * 200);
            }

            const interval = setInterval(() => {
                createHeart();
            }, isMobile ? 2000 : 1500);

            window.addEventListener('beforeunload', () => {
                clearInterval(interval);
            });

            const form = document.querySelector('form');
            const submitBtn = document.querySelector('.login-btn');
            const originalText = submitBtn.textContent;

            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.textContent = '登录中...';
                submitBtn.style.opacity = '0.7';

                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;
                        submitBtn.style.opacity = '1';
                    }
                }, 5000);
            });

            const passwordInput = document.querySelector('input[name="password"]');
            passwordInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    form.submit();
                }
            });
        });
    </script>
</body>
</html>