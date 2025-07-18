<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>💕Profess‌ - 后台登录</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/htmx/2.0.5/htmx.min.js"></script>
    <?php
    $baseUrl = isset($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : '';
    if ($baseUrl === '/') $baseUrl = '';
    ?>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-container">
        <h1 class="login-title">💕Profess‌ - 后台登录</h1>
        
        <?php

        $error = $_GET['error'] ?? $error ?? '';
        $errorMessages = [
            'invalid_password' => '密码错误，请重试',
            'missing_password' => '请输入密码',
            'too_many_attempts' => '登录尝试次数过多，请稍后再试',
            'method_not_allowed' => '请求方法不允许',
            'server_error' => '服务器错误，请稍后再试'
        ];

        if (!empty($error)):
            $errorMessage = $errorMessages[$error] ?? '登录失败';
            ?>
            <div class="error-message">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>
        
        <div id="login-form-container">
            <form class="login-form"
                  method="post"
                  action="api/login.php"
                  hx-post="api/login.php"
                  hx-target="#login-response"
                  hx-swap="innerHTML"
                  hx-on::after-request="handleLoginResponse(event)">
                
                <input type="password" 
                       name="password" 
                       class="form-input" 
                       placeholder="请输入管理密码" 
                       required 
                       autocomplete="current-password">
                
                <button type="submit" class="login-btn">
                    登录
                    <span class="htmx-indicator spinner"></span>
                </button>
            </form>
        </div>
        
        <div id="login-response"></div>
        
        <p class="password-hint">
            默认密码：admin123<br>
            可通过.env设置 ADMIN_PASSWORD 修改
        </p>

        <div style="text-align: center; margin: 20px 0;">
            <a href="https://github.com/ymh1146/Profess" target="_blank" style="display: inline-flex; align-items: center; padding: 8px 16px; background: #24292e; color: white; text-decoration: none; border-radius: 6px; font-size: 14px; transition: all 0.3s ease;" onmouseover="this.style.background='#1b1f23'; this.style.transform='translateY(-1px)'" onmouseout="this.style.background='#24292e'; this.style.transform='translateY(0)'">
                <span style="margin-right: 6px;">⭐</span>
                GitHub
            </a>
        </div>

        <a href="show.php" class="back-link">← 返回展示页面</a>
    </div>

    <script>
        // 处理登录响应
        function handleLoginResponse(event) {
            const xhr = event.detail.xhr;
            const response = JSON.parse(xhr.responseText);
            
            if (response.ok && response.sessionId) {
                localStorage.setItem('sessionId', response.sessionId);

                document.getElementById('login-response').innerHTML = 
                    '<div class="success-message">登录成功，正在跳转...</div>';

                setTimeout(() => {
                    window.location.href = 'admin.php';
                }, 1000);
            } else {
                
                let errorMsg = '登录失败';
                if (response.err === 'invalid_password') {
                    errorMsg = '密码错误，请重试';
                } else if (response.err === 'too_many_attempts') {
                    errorMsg = '登录尝试次数过多，请稍后再试';
                } else if (response.err === 'missing_password') {
                    errorMsg = '请输入密码';
                }
                
                document.getElementById('login-response').innerHTML = 
                    `<div class="error-message">${errorMsg}</div>`;
            }
        }
        
        // 检查是否已经登录
        function checkAuthStatus() {
            const sessionId = localStorage.getItem('sessionId');
            if (sessionId) {
                fetch('api/auth.php', {
                    headers: {
                        'X-Session-ID': sessionId
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.ok && data.authenticated) {
                        window.location.href = 'admin.php';
                    }
                })
                .catch(error => {
                    console.log('认证检查失败:', error);
                });
            }
        }
        
        // 页面加载时检查认证状态
        document.addEventListener('DOMContentLoaded', checkAuthStatus);

        document.addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                const form = document.querySelector('.login-form');
                const passwordInput = form.querySelector('input[name="password"]');
                if (passwordInput.value.trim()) {
                    htmx.trigger(form, 'submit');
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.querySelector('input[name="password"]');
            if (passwordInput) {
                passwordInput.focus();
            }
        });
    </script>
</body>
</html>
