# 💕Profess - 表白时刻

一个自定义图片的表白网站系统，支持图片轮播、文字特效、背景音乐、多种飘浮效果。

## 特性

- 🖼️ 图片轮播展示，支持拖拽排序
- 💬 自定义文字描述
- 🎵 背景音乐播放
- ✨ 多种飘浮动画效果
- 🔐 密码保护访问
- 🛠️ 简单易用的后台管理

## 快速开始

### 环境要求
- PHP 7.4+
- Web服务器

### 安装
1. 下载项目到Web服务器目录
2. 确保 `data/` 和 `uploads/` 目录可写
3. 访问网站开始使用

### 配置
复制 `.env.example` 为 `.env`：
```env
ENABLE_FRONT_LOGIN=true # 前台密码是否开启
FRONT_PASSWORD=love520 # 前台密码
ADMIN_PASSWORD=admin123 # 后台密码
```

## 使用

### 前台
- 开启ENABLE_FRONT_LOGIN则需要登录
- 首页密码（默认：love520）


### 后台
- 访问 `/login.php` 登录管理后台
- 管理员密码（默认：admin123）



## 许可证

MIT License