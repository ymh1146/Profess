class LoveGallery {
  constructor(options = {}) {
    const defaults = {
      sliderSelector: "#slider",
      overlaySelector: "#loveOverlay",
      progressSelector: "#progressFill",
      playBtnSelector: "#playBtn",
      musicBtnSelector: "#musicBtn",
      speedSliderSelector: "#speedSlider",
      speedValueSelector: "#speedValue",
      startGuideSelector: "#startGuide",
      textOverlaySelector: "#textOverlay",
      playerSelector: "#player",
      transitionDuration: 1500,
      displayDuration: 5000,
    };

    this.config = { ...defaults, ...options };

    this.slider = document.querySelector(this.config.sliderSelector);
    this.loveOverlay = document.querySelector(this.config.overlaySelector);
    this.progressFill = document.querySelector(this.config.progressSelector);
    this.playBtn = document.querySelector(this.config.playBtnSelector);
    this.musicBtn = document.querySelector(this.config.musicBtnSelector);
    this.speedSlider = document.querySelector(this.config.speedSliderSelector);
    this.speedValue = document.querySelector(this.config.speedValueSelector);
    this.startGuide = document.querySelector(this.config.startGuideSelector);
    this.textOverlay = document.querySelector(this.config.textOverlaySelector);
    this.player = document.querySelector(this.config.playerSelector);

    this.originalImages = galData.images || [];
    this.images = [...this.originalImages];
    this.currentIndex = 0;
    this.isPlaying = false;
    this.autoPlayTimer = null;
    this.slides = [];
    this.playSpeed = 1.0;
    this.transitionDuration = this.config.transitionDuration;
    this.displayDuration = this.config.displayDuration;
    this.hasStarted = false;
    this.floatingStyle = galData.floatingStyle || "hearts";
    this.isAnimating = false;
    this.cleanupFunctions = [];
  }

  init() {
    this._setupMusic();

    if (this.originalImages.length === 0) {
      this.slider.innerHTML =
        '<div class="slide active" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>';
      this._hideStartGuide();
      return;
    }

    this._createSlides();
    this.showImage(0);
    this._createFloatingEffect();
    this._updatePlaySpeed(50);
    this._setupStartGuide();
    this._setupEventListeners();
  }

  showImage(index) {
    if (this.images.length === 0) return;

    if (this.isAnimating && this.currentIndex !== 0) return;

    const prevIndex = this.currentIndex;
    this.currentIndex = index;

    this._ensureEnoughImages();

    const progress =
      (((this.currentIndex % this.originalImages.length) + 1) /
        this.originalImages.length) *
      100;
    this.progressFill.style.width = progress + "%";

    if (prevIndex === this.currentIndex) {
      return;
    }

    if (!this.slides[prevIndex] || !this.slides[this.currentIndex]) {
      return;
    }

    this.isAnimating = true;
    this._resetAllSlides();
    this.slides.forEach((slide) => slide.classList.remove("active"));

    if (index === 0 && prevIndex === 0) {
      this.slides[0].classList.add("active");
      gsap.set(this.slides[0], { opacity: 1 });
      this.isAnimating = false;
      return;
    }

    const effectNames = Object.keys(fx);
    const randomEffect =
      effectNames[Math.floor(Math.random() * effectNames.length)];

    fx[randomEffect](
      this.slides[prevIndex],
      this.slides[this.currentIndex],
      () => {
        this.isAnimating = false;
        this.slides[this.currentIndex].classList.add("active");
        gsap.set(this.slides[this.currentIndex], { opacity: 1 });
      }
    );

    this.slides[this.currentIndex].classList.add("active");
  }

  nextImage() {
    if (this.images.length === 0 || this.isAnimating) return;
    const nextIndex = this.currentIndex + 1;

    if (this.currentIndex === this.images.length - 1) {
      this._ensureEnoughImages();
    }

    this.showImage(nextIndex);
  }

  prevImage() {
    if (this.images.length === 0 || this.isAnimating) return;

    if (this.currentIndex === 0) {
      const moreImages = [...this.originalImages];
      this.images = moreImages.concat(this.images);
      this.currentIndex = moreImages.length;
      this._createSlides();
    }

    const prevIndex = this.currentIndex - 1;
    this.showImage(prevIndex);
  }

  startAutoPlay() {
    if (this.images.length <= 1) return;

    this._stopAutoPlay();

    this._scheduleNextTransition();
  }

  _stopAutoPlay() {
    if (this.autoPlayTimer) {
      clearTimeout(this.autoPlayTimer);
      this.autoPlayTimer = null;
    }

    if (this.isAnimating) {
      const waitForAnimation = () => {
        if (!this.isAnimating) {
          return;
        } else {
          setTimeout(waitForAnimation, 100);
        }
      };
      waitForAnimation();
    }
  }

  togglePlay() {
    if (!this.hasStarted) {
      this.startExperience();
      return;
    }

    this.isPlaying = !this.isPlaying;
    this.playBtn.textContent = this.isPlaying ? "⏸" : "▶";
    this.playBtn.title = this.isPlaying ? "暂停" : "播放";

    if (this.isPlaying && this.images.length > 1) {
      this.startAutoPlay();
    } else {
      this._stopAutoPlay();
    }
  }

  toggleMusic() {
    const musicUrl = galData.localMusicUrl || galData.musicUrl;
    if (!musicUrl) {
      this._showMessage("没有设置音乐", "error");
      return;
    }

    if (this.player.paused) {
      this.player.muted = false;
      this.player
        .play()
        .then(() => {
          this.musicBtn.textContent = "🎵";
          this.musicBtn.title = "音乐播放中，点击暂停";
        })
        .catch((e) => {
          this.musicBtn.textContent = "🔇";
          this.musicBtn.title = "音乐播放失败";
          this._showMessage("音乐播放失败", "error");
        });
    } else {
      this.player.pause();
      this.musicBtn.textContent = "🔇";
      this.musicBtn.title = "音乐已暂停，点击播放";
    }
  }

  startExperience() {
    if (this.hasStarted) return;

    this.hasStarted = true;
    this._hideStartGuide();

    this.isPlaying = true;
    this.playBtn.textContent = "⏸";

    if (this.images.length > 1) {
      this.startAutoPlay();
    }

    this._tryPlayMusic();

    setTimeout(() => {
      this._hideMainTitle();
    }, 5000);
  }

  cleanup() {
    this._stopAutoPlay();

    if (this.player && !this.player.paused) {
      this.player.pause();
    }

    this.cleanupFunctions.forEach((fn) => fn());
    this._removeEventListeners();
  }

  _ensureEnoughImages() {
    if (this.currentIndex >= this.images.length - 3) {
      const moreImages = [...this.originalImages];
      this.images = this.images.concat(moreImages);
      this._createSlides();
    }
  }

  _resetSlideState(slide) {
    if (!slide) return;

    gsap.killTweensOf(slide);

    gsap.set(slide, {
      x: 0,
      y: 0,
      scale: 1,
      rotation: 0,
      opacity: slide.classList.contains("active") ? 1 : 0,
      transformOrigin: "center center",
      clearProps: "transform",
    });
  }

  _resetAllSlides() {
    this.slides.forEach((slide) => {
      this._resetSlideState(slide);
    });
  }

  _setupMusic() {
    const musicUrl = galData.localMusicUrl || galData.musicUrl;

    if (musicUrl) {
      this.player.src = musicUrl;
      this.player.volume = 0.7;
      this.player.preload = "auto";
      this.player.muted = false;

      this.player.addEventListener("canplay", () => {
        this.musicBtn.textContent = "🎵";
      });

      this.player.addEventListener("error", (e) => {
        this.musicBtn.textContent = "🔇";
      });
    } else {
      this.musicBtn.textContent = "🔇";
    }
  }

  _createSlides() {
    this.slider.innerHTML = "";
    this.slides = [];

    this.images.forEach((img) => {
      const slide = document.createElement("div");
      slide.className = "slide";

      const imageUrl = typeof img === "string" ? img : img.url;
      const imageText = typeof img === "string" ? "" : img.text || "";

      slide.style.backgroundImage = `url(${imageUrl})`;

      if (imageText) {
        if (window.TextEffect) {
          const useAdvanced = Math.random() > 0.6;
          const useGSAP = Math.random() > 0.5;

          if (useAdvanced && window.TextEffect.applyAdvanced) {
            const advancedEffects = [
              "splitTextChars",
              "splitTextWords",
              "splitTextLines",
              "textPluginReplace",
              "morphingText",
              "particleText",
              "liquidText",
            ];
            const randomAdvancedEffect =
              advancedEffects[
                Math.floor(Math.random() * advancedEffects.length)
              ];

            window.TextEffect.create(slide, imageText, {
              randomPosition: true,
              randomEffect: false,
              useGSAP: false,
            });

            setTimeout(() => {
              const textElement = slide.querySelector(".image-text-effect");
              if (textElement && window.TextEffect.applyAdvanced) {
                window.TextEffect.applyAdvanced(
                  textElement,
                  randomAdvancedEffect
                );
              }
            }, 100);
          } else {
            window.TextEffect.create(slide, imageText, {
              randomPosition: true,
              randomEffect: true,
              useGSAP: useGSAP,
            });
          }
        } else {
          this._createSimpleTextEffect(slide, imageText);
        }
      }

      this.slider.appendChild(slide);
      this.slides.push(slide);
    });

    if (this.slides.length > 0 && this.currentIndex === 0) {
      this.slides[0].classList.add("active");
      gsap.set(this.slides[0], { opacity: 1 });
    }
  }

  _createSimpleTextEffect(slide, text) {
    if (!text.trim()) return;

    const textElement = document.createElement("div");
    textElement.className = `image-text-effect text-effect-${
      Math.floor(Math.random() * 12) + 1
    }`;
    textElement.textContent = text;

    const position = this._getTextPosition();
    textElement.style.left = position.x + "%";
    textElement.style.top = position.y + "%";

    textElement.style.animationDelay = Math.random() * 2 + "s";

    slide.appendChild(textElement);
  }

  _getTextPosition() {
    const isFirstPage = this.currentIndex === 0;
    const randomX = Math.random() * 60 + 20;

    if (isFirstPage) {
      const useTopArea = Math.random() > 0.5;
      const randomY = useTopArea
        ? Math.random() * 25 + 5
        : Math.random() * 15 + 70;
      return { x: randomX, y: randomY };
    } else {
      const randomY = Math.random() * 65 + 5;
      return { x: randomX, y: randomY };
    }
  }

  _createFloatingEffect() {
    if (window.FloatingEffect) {
      const cleanup = window.FloatingEffect.create(
        this.loveOverlay,
        this.floatingStyle
      );
      if (cleanup) {
        this.cleanupFunctions.push(cleanup);
      }
    }
  }

  _scheduleNextTransition() {
    if (!this.isPlaying || this.images.length <= 1) return;

    const actualDisplayTime = this.displayDuration / this.playSpeed;

    this.autoPlayTimer = setTimeout(() => {
      if (this.isPlaying && !this.isAnimating) {
        this.nextImage();

        const checkAnimationComplete = () => {
          if (!this.isAnimating) {
            this._scheduleNextTransition();
          } else {
            setTimeout(checkAnimationComplete, 100);
          }
        };
        setTimeout(checkAnimationComplete, this.transitionDuration);
      } else if (this.isPlaying) {
        setTimeout(() => this._scheduleNextTransition(), 200);
      }
    }, actualDisplayTime);
  }

  _updatePlaySpeed(sliderValue) {
    let speed;
    const value = parseInt(sliderValue);

    if (value <= 50) {
      speed = 0.1 + (value / 50) * 0.9;
    } else {
      speed = 1.0 + ((value - 50) / 50) * 1.0;
    }

    this.playSpeed = speed;
    this.speedValue.textContent = speed.toFixed(1) + "x";

    if (this.isPlaying && this.images.length > 1) {
      this.startAutoPlay();
    }
  }

  _setupStartGuide() {
    this.startGuide.addEventListener("click", () => this.startExperience());

    const onFirstKey = (e) => {
      this.startExperience();
      document.removeEventListener("keydown", onFirstKey);
    };

    document.addEventListener("keydown", onFirstKey);
  }

  _hideMainTitle() {
    if (this.textOverlay) {
      this.textOverlay.classList.add("hidden");
      setTimeout(() => {
        this.textOverlay.style.display = "none";
      }, 1000);
    }
  }

  _hideStartGuide() {
    this.startGuide.classList.add("hidden");
    setTimeout(() => {
      this.startGuide.style.display = "none";
    }, 500);
  }

  _tryPlayMusic() {
    const musicUrl = galData.localMusicUrl || galData.musicUrl;
    if (musicUrl && this.player.src) {
      this.player.muted = false;

      if (this.player.readyState >= 2) {
        this.player
          .play()
          .then(() => {
            this.musicBtn.textContent = "🎵";
            this.musicBtn.title = "音乐播放中，点击暂停";
          })
          .catch((e) => {
            this.musicBtn.textContent = "🔇";
            this.musicBtn.title = "音乐播放失败";
          });
      } else {
        this.player.addEventListener(
          "canplay",
          () => {
            this.player.muted = false;
            this.player
              .play()
              .then(() => {
                this.musicBtn.textContent = "🎵";
                this.musicBtn.title = "音乐播放中，点击暂停";
              })
              .catch((e) => {
                this.musicBtn.textContent = "🔇";
                this.musicBtn.title = "音乐播放失败";
              });
          },
          { once: true }
        );
      }
    } else {
      this.musicBtn.textContent = "🔇";
      this.musicBtn.title = "没有设置音乐";
    }
  }

  _showMessage(message, type) {
    const msgEl = document.createElement("div");
    msgEl.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 15px;
            border-radius: 8px;
            color: white;
            font-size: 14px;
            z-index: 1000;
            transition: all 0.3s;
            ${
              type === "error" ? "background: #ff4757;" : "background: #2ed573;"
            }
        `;
    msgEl.textContent = message;

    document.body.appendChild(msgEl);

    setTimeout(() => {
      msgEl.style.opacity = "0";
      setTimeout(() => {
        if (msgEl.parentNode) {
          msgEl.parentNode.removeChild(msgEl);
        }
      }, 300);
    }, 2000);
  }

  _setupEventListeners() {
    this.playBtn.addEventListener("click", () => this.togglePlay());
    this.musicBtn.addEventListener("click", () => this.toggleMusic());

    this.speedSlider.addEventListener("input", (e) => {
      this._updatePlaySpeed(e.target.value);
    });

    const wheelHandler = (e) => {
      e.preventDefault();

      if (!this.hasStarted) {
        this.startExperience();
        return;
      }

      if (e.deltaY > 0) {
        this.nextImage();
      } else {
        this.prevImage();
      }
    };

    document.addEventListener("wheel", wheelHandler, { passive: false });

    const keydownHandler = (e) => {
      if (!this.hasStarted) {
        this.startExperience();
        return;
      }

      switch (e.key) {
        case "ArrowLeft":
          this.prevImage();
          break;
        case "ArrowRight":
          this.nextImage();
          break;
        case " ":
          e.preventDefault();
          this.togglePlay();
          break;
        case "m":
        case "M":
          this.toggleMusic();
          break;
      }
    };

    document.addEventListener("keydown", keydownHandler);

    this._removeEventListeners = () => {
      this.playBtn.removeEventListener("click", () => this.togglePlay());
      this.musicBtn.removeEventListener("click", () => this.toggleMusic());
      this.speedSlider.removeEventListener("input", (e) =>
        this._updatePlaySpeed(e.target.value)
      );
      document.removeEventListener("wheel", wheelHandler);
      document.removeEventListener("keydown", keydownHandler);
    };
  }
}

window.LoveGallery = LoveGallery;
