// 移动端横屏检测和提示模块
class OrientationManager {
  constructor() {
    this.rotationOverlay = null;
    this.isInitialized = false;
  }

  init() {
    if (this.isInitialized) return;

    if (!this.isMobileDevice()) return;

    this.createRotationOverlay();
    this.checkOrientation();
    this.addOrientationListener();
    this.isInitialized = true;
  }

  isMobileDevice() {
    return (
      /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
        navigator.userAgent
      ) ||
      (window.innerWidth <= 768 && "ontouchstart" in window)
    );
  }

  isPortrait() {
    return window.innerHeight > window.innerWidth;
  }

  createRotationOverlay() {
    this.rotationOverlay = document.createElement("div");
    this.rotationOverlay.className = "rotation-overlay";
    this.rotationOverlay.innerHTML = `
            <div class="rotation-content">
                <div class="rotation-icon">📱</div>
                <h2 class="rotation-title">请旋转屏幕</h2>
                <p class="rotation-subtitle">为了更好的浏览体验<br>请将设备旋转至横屏模式</p>
            </div>
        `;

    this.rotationOverlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            color: white;
            text-align: center;
            font-family: 'Microsoft YaHei', sans-serif;
        `;

    const content = this.rotationOverlay.querySelector(".rotation-content");
    content.style.cssText = `
            padding: 40px 20px;
            max-width: 300px;
        `;

    const icon = this.rotationOverlay.querySelector(".rotation-icon");
    icon.style.cssText = `
            font-size: 4rem;
            margin-bottom: 20px;
            animation: rotatePhone 2s ease-in-out infinite;
        `;

    const title = this.rotationOverlay.querySelector(".rotation-title");
    title.style.cssText = `
            font-size: 1.8rem;
            margin-bottom: 15px;
            font-weight: 300;
        `;

    const subtitle = this.rotationOverlay.querySelector(".rotation-subtitle");
    subtitle.style.cssText = `
            font-size: 1rem;
            opacity: 0.8;
            line-height: 1.5;
        `;

    const style = document.createElement("style");
    style.textContent = `
            @keyframes rotatePhone {
                0%, 100% { transform: rotate(0deg); }
                50% { transform: rotate(90deg); }
            }
        `;
    document.head.appendChild(style);

    document.body.appendChild(this.rotationOverlay);
  }

  checkOrientation() {
    if (!this.rotationOverlay) return;

    if (this.isPortrait()) {
      this.showRotationPrompt();
    } else {
      this.hideRotationPrompt();
    }
  }

  showRotationPrompt() {
    if (this.rotationOverlay) {
      this.rotationOverlay.style.display = "flex";
    }
  }

  hideRotationPrompt() {
    if (this.rotationOverlay) {
      this.rotationOverlay.style.display = "none";
    }
  }

  addOrientationListener() {
    window.addEventListener("resize", () => {
      setTimeout(() => {
        this.checkOrientation();
      }, 100);
    });

    if ("onorientationchange" in window) {
      window.addEventListener("orientationchange", () => {
        setTimeout(() => {
          this.checkOrientation();
        }, 300);
      });
    }
  }

  cleanup() {
    if (this.rotationOverlay && this.rotationOverlay.parentNode) {
      this.rotationOverlay.parentNode.removeChild(this.rotationOverlay);
    }
    this.isInitialized = false;
  }
}

// 全局实例
window.OrientationManager = OrientationManager;

document.addEventListener("DOMContentLoaded", () => {
  const orientationManager = new OrientationManager();
  orientationManager.init();

  window.orientationManager = orientationManager;
});
