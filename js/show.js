
document.addEventListener('DOMContentLoaded', () => {
    if (typeof LoveGallery === 'function' && typeof fx === 'object' && Object.keys(fx).length > 0) {
        const gallery = new LoveGallery();
        gallery.init();
        window.gallery = gallery;
    } else {
        document.body.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100vh;font-family:Arial,sans-serif;color:#666;">模块加载失败，请刷新页面重试</div>';
    }
});
