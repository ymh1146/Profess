<?php
require_once 'config/config.php';
require_once 'config/auth.php';

if (!isAuthenticated()) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>💕Profess-‌表白后台</title>

    <style>
        html, body {
            font-family: 'Microsoft YaHei', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            height: auto;
            overflow-y: auto;
        }
        .admin-container {
            max-width: 800px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow-y: visible;
            height: auto;
            margin-bottom: 30px;
        }
        .admin-title {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 300;
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }
        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 16px;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }
        .form-input:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            color: #333;
        }
        .image-list {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        .image-item {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            flex-direction: column;
        }
        .image-item:hover {
            border-color: #667eea;
            transform: translateX(5px);
        }
        .drag-handle {
            display: flex;
            align-items: center;
            cursor: move;
            width: 100%;
            margin-bottom: 10px;
            position: relative;
        }
        .drag-handle:hover {
            background: rgba(102, 126, 234, 0.1);
            border-radius: 8px;
            padding: 5px;
            margin: -5px -5px 5px -5px;
        }
        .drag-handle::before {
            content: '⋮⋮';
            position: absolute;
            right: 5px;
            top: 5px;
            color: #999;
            font-size: 16px;
            font-weight: bold;
            z-index: 10;
            background: rgba(255, 255, 255, 0.8);
            padding: 2px 4px;
            border-radius: 3px;
            line-height: 1;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }
        .resource-id {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 35px;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }
        .image-preview {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
        }
        .image-title {
            flex: 1;
            font-size: 14px;
            color: #666;
            display: flex;
            flex-direction: column;
        }
        .url-text {
            color: #666;
            font-size: 14px;
            word-break: break-all;
        }
        .image-info {
            width: 100%;
            margin-top: 10px;
            cursor: default;
            pointer-events: auto;
        }
        .image-remove {
            background: #ff6b6b;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
        }
        .upload-area {
            border: 2px dashed #ccc;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            margin: 20px 0;
            transition: border-color 0.3s;
        }
        .upload-area:hover {
            border-color: #667eea;
        }
        .save-btn {
            width: 100%;
            padding: 15px;
            font-size: 18px;
            margin-top: 30px;
        }
        .audio-item {
            background: #e8f5e8;
            border-color: #4CAF50;
        }
        .audio-preview {
            width: 60px;
            height: 60px;
            background: #4CAF50;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            margin-right: 15px;
        }
        .set-music-btn {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            flex: 1;
            margin-right: 10px;
            transition: all 0.3s ease;
        }
        .set-music-btn.active-music {
            background: #ff9800 !important;
            box-shadow: 0 2px 8px rgba(255, 152, 0, 0.4);
        }
        .set-music-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(76, 175, 80, 0.4);
        }
        .set-music-btn.active-music:hover {
            box-shadow: 0 2px 8px rgba(255, 152, 0, 0.6);
        }
        .audio-controls {
            width: 100%;
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        .audio-item .image-remove {
            flex: 0 0 auto;
        }
        .image-text-description {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            margin-top: 8px;
            resize: none;
            min-height: 40px;
            cursor: text;
            pointer-events: auto;
        }
        .image-text-description:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
        }
        .input-with-emoji {
            margin-top: 8px;
            position: relative;
        }
        .input-with-emoji .image-text-description {
            width: 100%;
            margin-bottom: 8px;
        }
        .emoji-picker-container {
            position: relative;
            width: 100%;
        }
        .emoji-toggle-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            margin-bottom: 8px;
            display: inline-block;
        }
        .emoji-toggle-btn:hover {
            background: #5a67d8;
        }
        .emoji-picker {
            display: none;
            flex-wrap: wrap;
            max-width: 300px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            padding: 10px;
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 100;
            margin-top: 5px;
            max-height: 200px;
            overflow-y: auto;
        }
        .emoji-category-title {
            width: 100%;
            padding: 5px;
            margin-top: 5px;
            font-size: 12px;
            color: #666;
            background: #f5f5f5;
            border-radius: 4px;
            text-align: center;
        }
        .emoji-btn {
            width: 30px;
            height: 30px;
            font-size: 16px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            margin: 2px;
            border-radius: 4px;
            transition: all 0.2s;
        }
        .emoji-btn:hover {
            background-color: #f0f0f0;
        }
        .emoji-btn:active {
            background-color: #e0e0e0;
        }
        .floating-style-group {
            margin-bottom: 20px;
        }
        .floating-style-select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 16px;
            background: white;
        }
        .text-toggle-container {
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .text-toggle-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            flex-shrink: 0;
        }
        .text-toggle-btn:hover {
            background: #5a67d8;
        }
        .text-toggle-btn.collapsed {
            background: #ccc;
        }
        .text-input-collapsed {
            display: none;
        }
        .text-preview {
            font-size: 12px;
            color: #666;
            background: #f5f5f5;
            padding: 4px 8px;
            border-radius: 4px;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .sortable-ghost {
            opacity: 0.4;
        }
        .sortable-chosen {
            transform: scale(1.02);
        }
        .github-link {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            background: #24292e;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
            margin-right: 10px;
        }
        .github-link:hover {
            background: #1b1f23;
            transform: translateY(-1px);
        }
        .github-link::before {
            content: "⭐";
            margin-right: 6px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h1 class="admin-title" style="margin-bottom: 0;">💕Profess-表白后台</h1>
            <div style="display: flex; align-items: center;">
                <a href="https://github.com/ymh1146/Profess" target="_blank" class="github-link" title="查看源代码">GitHub</a>
                <button onclick="logout()" style="padding: 8px 16px; background: #ff6b6b; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">退出登录</button>
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">主标题</label>
            <input type="text" id="mainTitle" class="form-input" placeholder="请输入主标题">
        </div>

        <div class="form-group">
            <label class="form-label">副标题</label>
            <input type="text" id="subTitle" class="form-input" placeholder="请输入副标题">
        </div>

        <div class="form-group">
            <label class="form-label">背景音乐URL</label>
            <input type="text" id="mUrl" class="form-input" placeholder="请输入音乐文件的URL地址">
        </div>

        <div class="form-group floating-style-group">
            <label class="form-label">飘浮效果样式</label>
            <select id="floatingStyle" class="floating-style-select">
                <option value="none">🚫 无效果</option>
                <option value="hearts">💕 爱心飘浮</option>
                <option value="petals">🌸 花瓣飞舞</option>
                <option value="butterflies">🦋 蝴蝶翩翩</option>
                <option value="sparkles">✨ 星光点点</option>
                <option value="bubbles">💭 梦幻泡泡</option>
                <option value="snowflakes">❄️ 雪花纷飞</option>
                <option value="fireflies">🌟 萤火虫舞</option>
                <option value="feathers">🍃 羽毛轻舞</option>
                <option value="rainbow">🌈 彩虹飘带</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">添加远程图片</label>
            <div style="display: flex; gap: 10px;">
                <input type="text" id="rUrl" class="form-input" placeholder="请输入图片URL地址">
                <button id="addUrl" class="btn btn-secondary">添加</button>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">上传本地资源</label>
            <div class="upload-area">
                <input type="file" id="lFile" accept="image/*,audio/*" multiple style="display: none;">
                <button onclick="document.getElementById('lFile').click()" class="btn">选择文件</button>
                <p style="margin: 10px 0 0 0; color: #666;">支持图片（JPG、PNG、GIF）和音频（MP3、WAV、OGG）格式，可批量选择</p>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">资源列表 (拖拽排序)</label>
            <ul id="imgList" class="image-list">
                <!-- 动态生成的资源列表 -->
            </ul>
        </div>

        <button id="saveBtn" class="btn save-btn">💾 保存所有设置</button>
    </div>

    <button id="scrollTopBtn" style="
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        display: none;
        z-index: 1000;
        transition: all 0.3s;
    ">↑</button>

    <!-- 引入脚本 -->
    <script src="js/lib/sortable.js"></script>
    <script src="js/core.js"></script>
    <script src="js/emoji.js"></script>

    <script>
        const mainTitle = document.getElementById('mainTitle');
        const subTitle = document.getElementById('subTitle');
        const mUrl = document.getElementById('mUrl');
        const floatingStyle = document.getElementById('floatingStyle');
        const imgList = document.getElementById('imgList');
        
        // 获取基础URL路径
        const baseUrl = '';
        
        async function loadData() {
            try {
                const response = await fetch('/api/data.php');

                if (!response.ok) {
                    throw new Error(`HTTP错误: ${response.status}`);
                }

                const data = await response.json();

                if (mainTitle) mainTitle.value = data.title || '💕相遇💕相知💕相守💕';
                if (subTitle) subTitle.value = data.subtitle || '因为有你💕方可相依';

                if (mUrl) {
                    const localMusic = data.localMusicUrl && data.localMusicUrl !== 'undefined' ? data.localMusicUrl : '';
                    const remoteMusic = data.musicUrl && data.musicUrl !== 'undefined' ? data.musicUrl : '';
                    mUrl.value = localMusic || remoteMusic;
                }

                if (floatingStyle) floatingStyle.value = data.floatingStyle || 'hearts';

                if (imgList) {
                    imgList.innerHTML = '';
                    if (data.images && data.images.length > 0) {
                        data.images.forEach((img, index) => {
                            const imageUrl = typeof img === 'string' ? img : img.url;
                            const imageText = typeof img === 'string' ? '' : (img.text || '');
                            addImageToList(imageUrl, index, imageText);
                        });
                    }

                    if (data.uploadedAudios && data.uploadedAudios.length > 0) {
                        data.uploadedAudios.forEach((audio, index) => {
                            const currentIndex = imgList.children.length;
                            addAudioToList(audio.url, audio.originalName, currentIndex);
                        });
                    }
                }
                
                updateMusicButtonStates();
            } catch (error) {
                // 处理错误
            }
        }
        
        function addImageToList(url, index, text = '') {
            const li = document.createElement('li');
            li.className = 'image-item';
            li.innerHTML = `
                <div class="drag-handle">
                    <img src="${url}" alt="图片 ${index + 1}" class="image-preview" loading="lazy">
                    <div class="image-title">
                        <div class="resource-id">#${index + 1}</div>
                        <div class="url-text">${url}</div>
                    </div>
                    <button class="image-remove">删除</button>
                </div>
                <div class="image-info">
                    <div class="text-preview editable-text" style="cursor: pointer; padding: 5px; border-radius: 3px; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f0f0f0'" onmouseout="this.style.backgroundColor='transparent'">${text || '无文字'}</div>
                </div>
            `;
            imgList.appendChild(li);
        }
        
        function addAudioToList(url, originalName, index) {
            const li = document.createElement('li');
            li.className = 'image-item audio-item';
            li.innerHTML = `
                <div class="drag-handle">
                    <div class="audio-preview">🎵</div>
                    <div class="image-title">
                        <div class="resource-id">#${index + 1}</div>
                        <div class="url-text">${originalName || url}</div>
                    </div>
                    <button class="image-remove">删除</button>
                </div>
                <div class="audio-controls">
                    <button class="set-music-btn" data-url="${url}">设为背景音乐</button>
                    <audio controls style="max-width: 200px; height: 30px;">
                        <source src="${url}" type="audio/mpeg">
                        <source src="${url}" type="audio/wav">
                        <source src="${url}" type="audio/ogg">
                        您的浏览器不支持音频播放。
                    </audio>
                </div>
            `;
            imgList.appendChild(li);
        }
        
        function updateMusicButtonStates() {
            const musicUrlInput = document.getElementById('mUrl');
            const currentMusicUrl = musicUrlInput ? musicUrlInput.value.trim() : '';
            
            const musicButtons = document.querySelectorAll('.set-music-btn');

            musicButtons.forEach(button => {
                const buttonUrl = button.getAttribute('data-url');
                const isCurrentMusic = buttonUrl === currentMusicUrl && currentMusicUrl !== '';

                if (isCurrentMusic) {
                    button.textContent = '✅ 当前背景音乐';
                    button.style.cssText = 'background: #FF9800; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: default; margin-right: 5px; font-weight: bold;';
                    button.disabled = true;
                } else {
                    button.textContent = '设为背景音乐';
                    button.style.cssText = 'background: #4CAF50; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; margin-right: 5px;';
                    button.disabled = false;
                }
            });
        }
        
        function setBackgroundMusic(audioUrl) {
            if (!audioUrl || audioUrl === 'undefined') {
                showNotification('设置失败：音频URL无效', 'error');
                return;
            }

            const musicUrlInput = document.getElementById('mUrl');
            if (musicUrlInput) {
                musicUrlInput.value = audioUrl;
                showNotification('背景音乐已设置为: ' + audioUrl, 'success');
                
                updateMusicButtonStates();
            } else {
                showNotification('设置失败：找不到音乐URL输入框', 'error');
            }
        }
        
        function addRemoteImage() {
            const rUrl = document.getElementById('rUrl');
            const url = rUrl.value.trim();

            if (!url) {
                showNotification('请输入图片URL', 'error');
                return;
            }

            if (!url.startsWith('http://') && !url.startsWith('https://')) {
                showNotification('请输入有效的URL（以http://或https://开头）', 'error');
                return;
            }

            const currentIndex = imgList.children.length;
            addImageToList(url, currentIndex, '');

            rUrl.value = '';

            showNotification('图片添加成功', 'success');
        }

        async function removeResource(button) {
            if (!confirm('确定要删除这个资源吗？')) {
                return;
            }

            const item = button.closest('.image-item');
            const isAudio = item.classList.contains('audio-item');

            let urlText;
            if (isAudio) {
                const setMusicBtn = item.querySelector('.set-music-btn');
                urlText = setMusicBtn ? setMusicBtn.getAttribute('data-url') : '';
            } else {
                urlText = item.querySelector('.url-text').textContent;
            }

            try {
                if (urlText.startsWith('/uploads/') || urlText.includes('uploads/')) {
                    const response = await fetch('/api/delete.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ url: urlText })
                    });

                    const result = await response.json();
                        if (!result.ok) {
                        showNotification('删除文件失败: ' + result.err, 'error');
                        return;
                    }
                }

                // 清理音频元素，避免继续请求已删除的文件
                if (isAudio) {
                    const audioElement = item.querySelector('audio');
                    if (audioElement) {
                        audioElement.pause();
                        
                        const sources = audioElement.querySelectorAll('source');
                        sources.forEach(source => {
                            source.src = '';
                            source.removeAttribute('src');
                        });
                        
                        audioElement.load();
                    }

                    // 如果删除的是当前背景音乐，清空音乐URL输入框并立即保存
                    const musicUrlInput = document.getElementById('mUrl');
                    if (musicUrlInput && musicUrlInput.value === urlText) {
                        musicUrlInput.value = '';
                        
                        setTimeout(() => {
                            saveSettings().then(() => {
                                // 背景音乐设置已清除并保存
                            }).catch(error => {
                                // 处理错误
                            });
                        }, 100);
                    }
                }

                item.remove();

                updateResourceNumbers();

                showNotification('删除成功', 'success');
            } catch (error) {
                showNotification('删除错误: ' + error.message, 'error');
            }
        }

        function updateResourceNumbers() {
            const items = imgList.querySelectorAll('.image-item');
            items.forEach((item, index) => {
                const resourceId = item.querySelector('.resource-id');
                if (resourceId) {
                    resourceId.textContent = `#${index + 1}`;
                }
            });
        }

        function editImageText(textElement) {
            const currentText = textElement.textContent === '无文字' ? '' : textElement.textContent;

            const editContainer = document.createElement('div');
            editContainer.style.cssText = `
                display: flex;
                align-items: center;
                gap: 5px;
                width: 100%;
            `;
            const input = document.createElement('input');
            input.type = 'text';
            input.value = currentText;
            input.style.cssText = `
                flex: 1;
                padding: 5px;
                border: 1px solid #ddd;
                border-radius: 3px;
                font-size: 14px;
                background: white;
            `;

            const emojiBtn = document.createElement('button');
            emojiBtn.textContent = '😊';
            emojiBtn.style.cssText = `
                padding: 5px 8px;
                border: 1px solid #ddd;
                border-radius: 3px;
                background: white;
                cursor: pointer;
                font-size: 14px;
            `;
            emojiBtn.title = '添加表情';

            editContainer.appendChild(input);
            editContainer.appendChild(emojiBtn);

            textElement.style.display = 'none';
            textElement.parentNode.insertBefore(editContainer, textElement);
            input.focus();
            input.select();

            emojiBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                if (typeof showEmojiPicker === 'function') {
                    showEmojiPicker(input);
                } else {
                    const emojis = window.EMOJIS ? Object.values(window.EMOJIS).flat() : ['💕', '❤️', '💖', '💗', '💙', '💚', '💛', '🧡', '💜', '🖤', '🤍', '🤎', '💔', '❣️', '💟', '♥️', '💘', '💝', '💞', '💓', '💌', '💋', '💍', '💎', '🌹', '🌸', '🌺', '🌻', '🌷', '🌼', '🌿', '☀️', '🌙', '⭐', '✨', '🎆', '🎇', '🎉', '🎊', '🎈', '🎁', '🎀', '🎂', '🍰', '🧁', '🍭', '🍬', '🍫', '🍯', '🍓', '🍒', '🍑', '🍊', '🍋', '🍌', '🍍', '🥭', '🍎', '🍏', '🍐', '🍇', '🫐', '🍈', '🍉', '🍅', '🥝', '🥥', '🥑', '🍆', '🥒', '🥬', '🥦', '🧄', '🧅', '🌶️', '🫑', '🌽', '🥕', '🫒', '🥔', '🍠', '🥐', '🥖', '🍞', '🥨', '🥯', '🧀', '🥚', '🍳', '🧈', '🥞', '🧇', '🥓', '🥩', '🍗', '🍖', '🦴', '🌭', '🍔', '🍟', '🍕'];

                    const emojiContainer = document.createElement('div');
                    emojiContainer.style.cssText = `
                        position: fixed;
                        background: white;
                        border: 1px solid #ddd;
                        border-radius: 8px;
                        padding: 8px;
                        width: 280px;
                        max-width: calc(100vw - 20px);
                        max-height: 200px;
                        overflow-y: auto;
                        overflow-x: hidden;
                        z-index: 10000;
                        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
                        display: grid;
                        grid-template-columns: repeat(8, 1fr);
                        gap: 2px;
                        box-sizing: border-box;
                    `;

                    emojis.forEach(emoji => {
                        const emojiSpan = document.createElement('span');
                        emojiSpan.textContent = emoji;
                        emojiSpan.style.cssText = `
                            cursor: pointer;
                            padding: 4px 2px;
                            border-radius: 3px;
                            text-align: center;
                            font-size: 16px;
                            line-height: 1.2;
                            min-height: 24px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            box-sizing: border-box;
                            transition: background-color 0.2s;
                        `;
                        emojiSpan.onmouseover = () => emojiSpan.style.backgroundColor = '#f0f0f0';
                        emojiSpan.onmouseout = () => emojiSpan.style.backgroundColor = 'transparent';
                        emojiSpan.onclick = () => {
                            input.value += emoji;
                            input.focus();
                            emojiContainer.remove();
                        };
                        emojiContainer.appendChild(emojiSpan);
                    });

                    const rect = emojiBtn.getBoundingClientRect();
                    const containerWidth = Math.min(280, window.innerWidth - 20); 
                    const containerHeight = 200;
                    const margin = 10;

                    let top = rect.top;
                    let left = rect.left - containerWidth - 5;

                    if (left < margin) {
                        left = rect.right + 5;
                        
                        if (left + containerWidth > window.innerWidth - margin) {
                            left = window.innerWidth - containerWidth - margin;
                        }
                    }

                    if (left < margin) {
                        left = margin;
                    }

                    if (top + containerHeight > window.innerHeight - margin) {
                        top = window.innerHeight - containerHeight - margin;
                    }
                    if (top < margin) {
                        top = margin;
                    }

                    emojiContainer.style.width = containerWidth + 'px';
                    emojiContainer.style.top = top + 'px';
                    emojiContainer.style.left = left + 'px';

                    document.body.appendChild(emojiContainer);

                    setTimeout(() => {
                        document.addEventListener('click', function closeEmoji(e) {
                            if (!emojiContainer.contains(e.target) && e.target !== emojiBtn) {
                                emojiContainer.remove();
                                document.removeEventListener('click', closeEmoji);
                            }
                        });
                    }, 100);
                }
            });

            function saveText() {
                const newText = input.value.trim();
                textElement.textContent = newText || '无文字';
                textElement.style.display = 'block';
                editContainer.remove();

                if (newText !== currentText) {
                    showNotification('文字已更新', 'success');
                }
            }

            function cancelEdit() {
                textElement.style.display = 'block';
                editContainer.remove();
            }

            input.addEventListener('blur', function(e) {
                
                setTimeout(() => {
                    if (!editContainer.contains(document.activeElement)) {
                        saveText();
                    }
                }, 100);
            });

            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    saveText();
                } else if (e.key === 'Escape') {
                    cancelEdit();
                }
            });
        }

        async function handleFileUpload(files) {
            if (!files || files.length === 0) {
                return;
            }

            for (let file of files) {
                try {


                    const formData = new FormData();
                    formData.append('file', file);

                    const response = await fetch(baseUrl + '/api/upload.php', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    const result = await response.json();
                    

                    if (result.ok) {
                        const currentIndex = imgList.children.length;

                        if (file.type.startsWith('image/')) {
                            
                            addImageToList(result.url, currentIndex, '');
                            showNotification(`图片 ${file.name} 上传成功`, 'success');
                        } else if (file.type.startsWith('audio/')) {
                            
                            addAudioToList(result.url, file.name, currentIndex);
                            showNotification(`音频 ${file.name} 上传成功`, 'success');
                        }
                    } else {
                        
                        showNotification(`上传 ${file.name} 失败: ${result.err}`, 'error');
                    }
                } catch (error) {
                    
                    showNotification(`上传 ${file.name} 错误: ${error.message}`, 'error');
                }
            }
        }

        async function saveSettings() {
            try {
                

                const musicUrlValue = mUrl.value || '';
                const formData = {
                    title: mainTitle.value || '💕相遇💕相知💕相守💕',
                    subtitle: subTitle.value || '因为有你💕方可相依',
                    floatingStyle: floatingStyle.value || 'hearts',
                    images: []
                };

                if (musicUrlValue) {
                    if (musicUrlValue.startsWith('/uploads/') || musicUrlValue.includes('uploads/')) {
                        
                        formData.localMusicUrl = musicUrlValue;
                        formData.musicUrl = ''; // 清空远程URL
                    } else {
                        
                        formData.musicUrl = musicUrlValue;
                        formData.localMusicUrl = ''; 
                    }
                } else {
                    
                    formData.musicUrl = '';
                    formData.localMusicUrl = '';
                }

                

                let imageItems = imgList.querySelectorAll('.image-item:not(.audio-item)');
                if (imageItems.length === 0) {
                    
                    imageItems = imgList.querySelectorAll('li:not(.audio-item)');
                }
                if (imageItems.length === 0) {
                    
                    const allItems = imgList.querySelectorAll('li');
                    imageItems = Array.from(allItems).filter(item => !item.classList.contains('audio-item'));
                }

                

                imageItems.forEach((item, index) => {
                    
                    let urlText = '';
                    const urlElement = item.querySelector('.url-text');
                    if (urlElement) {
                        urlText = urlElement.textContent;
                    } else {
                        
                        const urlDiv = item.querySelector('div[class*="url"]') ||
                                      item.querySelector('div:nth-child(2)') ||
                                      item.querySelector('div div:last-child');
                        if (urlDiv) {
                            urlText = urlDiv.textContent;
                        }
                    }

                    let text = '';
                    const textPreview = item.querySelector('.text-preview');
                    if (textPreview) {
                        text = textPreview.textContent.replace('无文字', '');
                    } else {
                        
                        const textElement = item.querySelector('[class*="text"]') ||
                                          item.querySelector('div:last-child') ||
                                          item.querySelector('div[onclick*="editText"]');
                        if (textElement) {
                            text = textElement.textContent.replace('无文字', '');
                        }
                    }

                    

                    if (urlText) {
                        formData.images.push({
                            url: urlText,
                            text: text
                        });
                    }
                });

                

                const response = await fetch(baseUrl + '/api/save.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.ok) {
                    
                    
                    showNotification('保存成功！', 'success');
                } else {
                    
                    showNotification('保存失败: ' + result.err, 'error');
                }
            } catch (error) {
                
                showNotification('保存错误: ' + error.message, 'error');
            }
        }

        // 显示通知
        function showNotification(message, type = 'info') {
            
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                bottom: 20px;
                left: 20px;
                background: ${type === 'success' ? '#4CAF50' : type === 'error' ? '#f44336' : '#2196F3'};
                color: white;
                padding: 12px 20px;
                border-radius: 6px;
                z-index: 10000;
                font-size: 14px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            `;
            notification.textContent = message;

            document.body.appendChild(notification);

            
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 3000);
        }

        async function logout() {
            try {
                const response = await fetch('/api/logout.php', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    localStorage.removeItem('sessionId');
                    window.location.href = '/show.php';
                } else {
                    
                    localStorage.removeItem('sessionId');
                    window.location.href = '/show.php';
                }
            } catch (error) {
                
                
                window.location.href = '/show.php';
            }
        }

        function initSortable() {
            if (typeof Sortable !== 'undefined' && imgList) {
                new Sortable(imgList, {
                    handle: '.drag-handle', // 只允许通过拖拽手柄进行拖拽
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    onEnd: function(evt) {
                        
                        updateResourceNumbers();
                        showNotification('排序已更新', 'success');
                    }
                });
                
            } else {
                
            }
        }

        function initEventListeners() {
            
            const addUrlBtn = document.getElementById('addUrl');
            if (addUrlBtn) {
                addUrlBtn.addEventListener('click', addRemoteImage);
            }

            const saveBtn = document.getElementById('saveBtn');
            if (saveBtn) {
                saveBtn.addEventListener('click', saveSettings);
            }

            const lFile = document.getElementById('lFile');
            if (lFile) {
                lFile.addEventListener('change', function(e) {
                    handleFileUpload(e.target.files);
                });
            }

            const rUrl = document.getElementById('rUrl');
            if (rUrl) {
                rUrl.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        addRemoteImage();
                    }
                });
            }

            initSortable();

            document.addEventListener('click', function(e) {
                

                if (e.target.classList.contains('editable-text')) {
                    
                    e.preventDefault();
                    e.stopPropagation();
                    editImageText(e.target);
                }

                if (e.target.classList.contains('set-music-btn')) {
                    
                    e.preventDefault();
                    e.stopPropagation();
                    const audioUrl = e.target.getAttribute('data-url');
                    setBackgroundMusic(audioUrl);
                }

                if (e.target.classList.contains('image-remove')) {
                    
                    e.preventDefault();
                    e.stopPropagation();
                    removeResource(e.target);
                }
            });
            
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadData();
            initEventListeners();
        });

        
    </script>
</body>
</html>
