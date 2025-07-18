if (typeof TextPlugin !== "undefined" && !gsap.plugins.TextPlugin) {
  gsap.registerPlugin(TextPlugin);
  console.log("textFx.js: TextPlugin 已注册");
}

const TEXT_EFFECTS = {
  textPulse: "text-effect-1",
  textGlow: "text-effect-2",
  textBounce: "text-effect-3",
  textShake: "text-effect-4",
  textSlide: "text-effect-5",
  textZoom: "text-effect-6",
  textFade: "text-effect-7",
  textWave: "text-effect-8",
  textFlip: "text-effect-9",
  textBreath: "text-effect-10",
  textSwing: "text-effect-11",
  textRipple: "text-effect-12",

  textSplit: "text-effect-13",
  textGlitch: "text-effect-14",
  textReveal: "text-effect-15",
  textFloat3D: "text-effect-16",

  textScramble: "text-effect-17",
  textTypewriter: "text-effect-18",
  textMorph: "text-effect-19",
  textElastic: "text-effect-20",
  textMagnetic: "text-effect-21",
  textLiquid: "text-effect-22",
  textParticle: "text-effect-23",
  textNeon: "text-effect-24",
  textMatrix: "text-effect-25",
  textHologram: "text-effect-26",
  textCrystal: "text-effect-27",
  textFire: "text-effect-28",
  textIce: "text-effect-29",
  textThunder: "text-effect-30",
  textRainbow: "text-effect-31",
  textGalaxy: "text-effect-32",
  textQuantum: "text-effect-33",
  textDimension: "text-effect-34",
  textTime: "text-effect-35",
  textSpace: "text-effect-36",
};

function getTextPosition() {
  const isFirstPage = window.gallery && window.gallery.currentIndex === 0;
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

function createTextEffect(slide, text, options = {}) {
  if (!text || !text.trim()) return null;

  const defaultOptions = {
    randomPosition: true,
    randomEffect: true,
    effectType: null,
    useGSAP: false,
    fontSize: null,
    color: null,
    delay: null,
  };

  const settings = {
    ...defaultOptions,
    ...options,
  };

  const textElement = document.createElement("div");

  textElement.className = "image-text-effect";

  if (settings.randomEffect) {
    const effectTypes = Object.values(TEXT_EFFECTS);
    const randomEffect =
      effectTypes[Math.floor(Math.random() * effectTypes.length)];
    textElement.classList.add(randomEffect);
  } else if (settings.effectType && TEXT_EFFECTS[settings.effectType]) {
    textElement.classList.add(TEXT_EFFECTS[settings.effectType]);
  } else {
    textElement.classList.add(TEXT_EFFECTS.textPulse);
  }
  textElement.textContent = text;

  if (settings.randomPosition) {
    const position = getTextPosition();
    textElement.style.left = position.x + "%";
    textElement.style.top = position.y + "%";
  } else if (options.position) {
    if (options.position.x !== undefined)
      textElement.style.left = options.position.x + "%";

    if (options.position.y !== undefined)
      textElement.style.top = options.position.y + "%";
  }

  if (settings.fontSize) {
    textElement.style.fontSize =
      typeof settings.fontSize === "number"
        ? `${settings.fontSize}rem`
        : settings.fontSize;
  }

  if (settings.color) {
    textElement.style.color = settings.color;
  }

  if (settings.delay !== null) {
    textElement.style.animationDelay = `${settings.delay}s`;
  } else {
    textElement.style.animationDelay = Math.random() * 2 + "s";
  }

  slide.appendChild(textElement);

  if (settings.useGSAP) {
    applyGSAPTextEffect(textElement, settings.effectType || "textReveal");
  }

  return textElement;
}

function applyGSAPTextEffect(element, effectType) {
  element.style.animation = "none";

  switch (effectType) {
    case "textReveal":
      const text = element.textContent;
      element.textContent = "";

      const wrapper = document.createElement("div");
      wrapper.style.position = "relative";
      wrapper.style.display = "inline-block";
      element.appendChild(wrapper);

      const characters = [...text];
      for (let i = 0; i < characters.length; i++) {
        const charSpan = document.createElement("span");
        charSpan.textContent = characters[i];
        charSpan.style.display = "inline-block";
        charSpan.style.opacity = "0";
        charSpan.style.transform = "translateY(20px)";
        wrapper.appendChild(charSpan);

        gsap.to(charSpan, {
          opacity: 1,
          y: 0,
          duration: 0.5,
          delay: 0.05 * i,
          ease: "back.out(1.7)",
          repeat: -1,
          repeatDelay: 5,
          yoyo: true,
        });
      }
      break;

    case "textGlitch":
      const timeline = gsap.timeline({ repeat: -1, repeatDelay: 3 });

      timeline
        .to(element, {
          skewX: 20,
          duration: 0.1,
          ease: "power4.inOut",
        })
        .to(element, {
          skewX: 0,
          duration: 0.1,
          ease: "power4.inOut",
        })
        .to(element, {
          opacity: 0.3,
          duration: 0.1,
        })
        .to(element, {
          opacity: 1,
          duration: 0.1,
        })
        .to(element, {
          x: -10,
          duration: 0.1,
        })
        .to(element, {
          x: 0,
          duration: 0.1,
        })
        .to(element, {
          x: 10,
          duration: 0.1,
          delay: 0.2,
        })
        .to(element, {
          x: 0,
          duration: 0.1,
        });
      break;

    case "textFloat3D":
      gsap.set(element, {
        transformPerspective: 1000,
        transformStyle: "preserve-3d",
      });

      gsap.to(element, {
        rotationY: 15,
        rotationX: 15,
        y: -20,
        duration: 2,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });

      gsap.to(element, {
        textShadow: "0 30px 30px rgba(0,0,0,0.6)",
        duration: 2,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });
      break;

    case "textSplit":
      const originalText = element.textContent;
      element.textContent = "";

      const text1 = document.createElement("div");
      text1.textContent = originalText;
      text1.style.position = "absolute";
      text1.style.color = "rgba(255, 105, 180, 0.7)";
      text1.style.top = "0";
      text1.style.left = "0";

      const text2 = document.createElement("div");
      text2.textContent = originalText;
      text2.style.position = "absolute";
      text2.style.color = "rgba(0, 191, 255, 0.7)";
      text2.style.top = "0";
      text2.style.left = "0";

      element.appendChild(text1);
      element.appendChild(text2);

      gsap.to(text1, {
        x: -5,
        duration: 2,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });

      gsap.to(text2, {
        x: 5,
        duration: 2,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });
      break;

    case "textScramble":
      const scrambleOriginalText = element.textContent;
      const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
      let scrambleCount = 0;

      const scrambleInterval = setInterval(() => {
        let scrambledText = "";
        for (let i = 0; i < scrambleOriginalText.length; i++) {
          if (scrambleOriginalText[i] === " ") {
            scrambledText += " ";
          } else if (Math.random() < 0.7) {
            scrambledText += chars[Math.floor(Math.random() * chars.length)];
          } else {
            scrambledText += scrambleOriginalText[i];
          }
        }
        element.textContent = scrambledText;

        scrambleCount++;
        if (scrambleCount > 20) {
          clearInterval(scrambleInterval);
          element.textContent = scrambleOriginalText;
        }
      }, 100);

      gsap.to(element, {
        opacity: 1,
        duration: 2,
        ease: "power2.out",
      });
      break;

    case "textTypewriter":
      const typeText = element.textContent;
      element.textContent = "";
      element.style.borderRight = "2px solid currentColor";

      let typeIndex = 0;
      const typeInterval = setInterval(() => {
        element.textContent = typeText.slice(0, typeIndex + 1);
        typeIndex++;

        if (typeIndex >= typeText.length) {
          clearInterval(typeInterval);

          const cursorBlink = gsap.to(element, {
            borderRightColor: "transparent",
            duration: 0.5,
            repeat: 5,
            yoyo: true,
            onComplete: () => {
              element.style.borderRight = "none";
            },
          });
        }
      }, 100);
      break;

    case "textMorph":
      gsap.to(element, {
        scaleX: 0,
        duration: 0.5,
        ease: "power2.in",
        onComplete: () => {
          gsap.to(element, {
            scaleX: 1,
            duration: 0.5,
            ease: "elastic.out(1, 0.3)",
          });
        },
      });

      gsap.to(element, {
        skewX: 15,
        duration: 1,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });
      break;

    case "textElastic":
      gsap.fromTo(
        element,
        {
          scale: 0,
          rotation: -180,
        },
        {
          scale: 1,
          rotation: 0,
          duration: 1.5,
          ease: "elastic.out(1, 0.3)",
          repeat: -1,
          repeatDelay: 3,
        }
      );
      break;

    case "textMagnetic":
      gsap.to(element, {
        x: "random(-20, 20)",
        y: "random(-10, 10)",
        rotation: "random(-5, 5)",
        duration: 2,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });

      gsap.to(element, {
        textShadow: "0 0 20px currentColor",
        duration: 1.5,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });
      break;

    case "textLiquid":
      gsap.to(element, {
        scaleY: 1.3,
        scaleX: 0.8,
        duration: 1,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });

      gsap.to(element, {
        skewX: 5,
        duration: 1.5,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });
      break;

    case "textParticle":
      gsap.to(element, {
        filter: "blur(2px)",
        duration: 0.5,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });

      gsap.to(element, {
        textShadow:
          "0 0 10px currentColor, 0 0 20px currentColor, 0 0 30px currentColor",
        duration: 2,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });
      break;

    case "textNeon":
      gsap.to(element, {
        textShadow:
          "0 0 5px #ff00ff, 0 0 10px #ff00ff, 0 0 15px #ff00ff, 0 0 20px #ff00ff",
        duration: 1,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });

      gsap.to(element, {
        color: "#ff00ff",
        duration: 2,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });
      break;

    case "textMatrix":
      const matrixChars = "01";
      const matrixText = element.textContent;

      setTimeout(() => {
        const matrixInterval = setInterval(() => {
          let newText = "";
          for (let i = 0; i < matrixText.length; i++) {
            if (Math.random() < 0.15) {
              newText +=
                matrixChars[Math.floor(Math.random() * matrixChars.length)];
            } else {
              newText += matrixText[i];
            }
          }
          element.textContent = newText;
        }, 200);

        gsap.to(element, {
          color: "#00ff00",
          textShadow: "0 0 10px #00ff00",
          duration: 0.5,
          ease: "sine.inOut",
          repeat: 10,
          yoyo: true,
        });

        setTimeout(() => {
          clearInterval(matrixInterval);
          element.textContent = matrixText;

          gsap.to(element, {
            color: "white",
            textShadow: "none",
            duration: 1,
          });
        }, 5000);
      }, 3000);
      break;

    case "textHologram":
      gsap.to(element, {
        opacity: 0.7,
        duration: 0.5,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });

      gsap.to(element, {
        textShadow: "0 0 5px cyan, 0 0 10px cyan, 0 0 15px cyan",
        filter: "hue-rotate(180deg)",
        duration: 3,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });
      break;

    case "textCrystal":
      gsap.to(element, {
        textShadow:
          "0 0 10px rgba(255,255,255,0.8), 0 0 20px rgba(255,255,255,0.6), 0 0 30px rgba(255,255,255,0.4)",
        filter: "brightness(1.5) contrast(1.2)",
        duration: 2,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });

      gsap.to(element, {
        scale: 1.05,
        duration: 1.5,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });
      break;

    case "textFire":
      gsap.to(element, {
        textShadow:
          "0 0 5px #ff4500, 0 0 10px #ff4500, 0 0 15px #ff4500, 0 0 20px #ff4500",
        color: "#ff6600",
        duration: 0.5,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });

      gsap.to(element, {
        y: -5,
        duration: 0.3,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });
      break;

    case "textIce":
      gsap.to(element, {
        textShadow: "0 0 5px #87ceeb, 0 0 10px #87ceeb, 0 0 15px #87ceeb",
        color: "#b0e0e6",
        filter: "brightness(1.2)",
        duration: 2,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });

      gsap.to(element, {
        scale: 1.02,
        duration: 3,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });
      break;

    case "textThunder":
      let thunderCount = 0;
      const maxThunderFlashes = 5;

      const thunderFlash = () => {
        if (thunderCount >= maxThunderFlashes) return;

        gsap.to(element, {
          textShadow:
            "0 0 5px #ffff00, 0 0 10px #ffff00, 0 0 15px #ffff00, 0 0 20px #ffff00",
          color: "#ffff00",
          duration: 0.1,
          ease: "power2.out",
          onComplete: () => {
            gsap.to(element, {
              textShadow: "none",
              color: "white",
              duration: 0.1,
              onComplete: () => {
                thunderCount++;
                if (thunderCount < maxThunderFlashes) {
                  setTimeout(thunderFlash, Math.random() * 2000 + 500);
                }
              },
            });
          },
        });
      };

      setTimeout(thunderFlash, Math.random() * 1000 + 500);
      break;

    case "textRainbow":
      gsap.to(element, {
        filter: "hue-rotate(360deg)",
        duration: 3,
        ease: "none",
        repeat: -1,
      });

      gsap.to(element, {
        textShadow: "0 0 10px currentColor",
        duration: 1,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });
      break;

    case "textGalaxy":
      gsap.to(element, {
        textShadow:
          "0 0 5px #4b0082, 0 0 10px #4b0082, 0 0 15px #4b0082, 0 0 20px #4b0082",
        filter: "hue-rotate(45deg)",
        duration: 4,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });

      gsap.to(element, {
        rotation: 360,
        duration: 10,
        ease: "none",
        repeat: -1,
      });
      break;

    case "textQuantum":
      gsap.to(element, {
        opacity: 0.3,
        duration: 0.1,
        ease: "none",
        repeat: -1,
        yoyo: true,
        repeatDelay: Math.random() * 2,
      });

      gsap.to(element, {
        x: "random(-5, 5)",
        y: "random(-5, 5)",
        duration: 0.1,
        ease: "none",
        repeat: -1,
      });
      break;

    case "textDimension":
      gsap.to(element, {
        rotationY: 360,
        duration: 3,
        ease: "sine.inOut",
        repeat: -1,
      });

      gsap.to(element, {
        z: 100,
        duration: 2,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });
      break;

    case "textTime":
      gsap.to(element, {
        filter: "blur(2px)",
        duration: 1,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });

      gsap.to(element, {
        scaleX: 0.8,
        duration: 2,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });
      break;

    case "textSpace":
      gsap.to(element, {
        textShadow: "0 0 10px #ffffff, 0 0 20px #ffffff, 0 0 30px #ffffff",
        filter: "brightness(1.5)",
        duration: 2,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });

      gsap.to(element, {
        y: -10,
        duration: 3,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });
      break;

    default:
      gsap.to(element, {
        scale: 1.1,
        duration: 1,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });
  }
}

function applyAdvancedGSAPEffect(element, effectType) {
  element.style.animation = "none";

  switch (effectType) {
    case "splitTextChars":
      if (typeof SplitText !== "undefined") {
        const split = new SplitText(element, { type: "chars" });
        gsap.from(split.chars, {
          opacity: 0,
          y: 100,
          duration: 1,
          stagger: 0.05,
          ease: "back.out(1.7)",
          repeat: -1,
          repeatDelay: 3,
          yoyo: true,
        });
      } else {
        const text = element.textContent;
        element.innerHTML = "";
        [...text].forEach((char, i) => {
          const span = document.createElement("span");
          span.textContent = char === " " ? "\u00A0" : char;
          span.style.display = "inline-block";
          element.appendChild(span);

          gsap.from(span, {
            opacity: 0,
            y: 50,
            duration: 0.5,
            delay: i * 0.05,
            ease: "back.out(1.7)",
            repeat: -1,
            repeatDelay: 5,
            yoyo: true,
          });
        });
      }
      break;

    case "splitTextWords":
      if (typeof SplitText !== "undefined") {
        const split = new SplitText(element, { type: "words" });
        gsap.from(split.words, {
          opacity: 0,
          y: -100,
          rotation: "random(-80, 80)",
          duration: 0.7,
          stagger: 0.15,
          ease: "back.out(1.7)",
          repeat: -1,
          repeatDelay: 4,
        });
      } else {
        const words = element.textContent.split(" ");
        element.innerHTML = "";
        words.forEach((word, i) => {
          const span = document.createElement("span");
          span.textContent = word;
          span.style.display = "inline-block";
          span.style.marginRight = "0.3em";
          element.appendChild(span);

          gsap.from(span, {
            opacity: 0,
            y: -50,
            rotation: Math.random() * 40 - 20,
            duration: 0.7,
            delay: i * 0.15,
            ease: "back.out(1.7)",
            repeat: -1,
            repeatDelay: 6,
          });
        });
      }
      break;

    case "splitTextLines":
      if (typeof SplitText !== "undefined") {
        const split = new SplitText(element, { type: "lines" });
        gsap.from(split.lines, {
          rotationX: -100,
          transformOrigin: "50% 50% -160px",
          opacity: 0,
          duration: 0.8,
          stagger: 0.25,
          ease: "power3.out",
          repeat: -1,
          repeatDelay: 5,
        });
      } else {
        const lines = element.textContent.split("\n");
        if (lines.length === 1) {
          const text = element.textContent;
          const midPoint = Math.floor(text.length / 2);
          lines[0] = text.substring(0, midPoint);
          lines[1] = text.substring(midPoint);
        }

        element.innerHTML = "";
        lines.forEach((line, i) => {
          const lineDiv = document.createElement("div");
          lineDiv.textContent = line;
          lineDiv.style.display = "block";
          element.appendChild(lineDiv);

          gsap.from(lineDiv, {
            rotationX: -90,
            opacity: 0,
            duration: 0.8,
            delay: i * 0.25,
            ease: "power3.out",
            repeat: -1,
            repeatDelay: 6,
          });
        });
      }
      break;

    case "textPluginReplace":
      if (typeof TextPlugin !== "undefined") {
        const pluginOriginalText = element.textContent;
        const alternateTexts = ["✨ 爱你 ✨", "💖 Forever 💖", "🌟 Always 🌟"];

        const randomText =
          alternateTexts[Math.floor(Math.random() * alternateTexts.length)];

        const tl = gsap.timeline();

        tl.set(element, { text: pluginOriginalText })
          .to({}, { duration: 3 })
          .to(element, {
            duration: 1,
            text: randomText,
            ease: "none",
          })
          .to({}, { duration: 2 })
          .to(element, {
            duration: 1,
            text: pluginOriginalText,
            ease: "none",
          });
      } else {
        const pluginOriginalText = element.textContent;
        const alternateTexts = ["✨ 爱你 ✨", "💖 Forever 💖", "🌟 Always 🌟"];
        const randomText =
          alternateTexts[Math.floor(Math.random() * alternateTexts.length)];

        gsap
          .timeline()
          .to({}, { duration: 3 })
          .to(element, {
            opacity: 0,
            duration: 0.5,
            onComplete: () => {
              element.textContent = randomText;
            },
          })
          .to(element, {
            opacity: 1,
            duration: 0.5,
          })
          .to({}, { duration: 2 })
          .to(element, {
            opacity: 0,
            duration: 0.5,
            onComplete: () => {
              element.textContent = pluginOriginalText;
            },
          })
          .to(element, {
            opacity: 1,
            duration: 0.5,
          });
      }
      break;

    case "morphingText":
      const morphStates = [
        {
          scaleX: 1,
          scaleY: 1,
          skewX: 0,
        },
        {
          scaleX: 1.2,
          scaleY: 0.8,
          skewX: 10,
        },
        {
          scaleX: 0.8,
          scaleY: 1.3,
          skewX: -5,
        },
        {
          scaleX: 1,
          scaleY: 1,
          skewX: 0,
        },
      ];

      let morphIndex = 0;
      const morphCycle = () => {
        gsap.to(element, {
          ...morphStates[morphIndex],
          duration: 1,
          ease: "elastic.out(1, 0.3)",
          onComplete: () => {
            morphIndex = (morphIndex + 1) % morphStates.length;
            setTimeout(morphCycle, 1500);
          },
        });
      };
      morphCycle();
      break;

    case "particleText":
      const text = element.textContent;
      element.innerHTML = "";

      [...text].forEach((char, i) => {
        if (char === " ") return;

        const particle = document.createElement("span");
        particle.textContent = char;
        particle.style.position = "absolute";
        particle.style.left = `${i * 1.2}em`;
        particle.style.display = "inline-block";
        element.appendChild(particle);

        gsap.to(particle, {
          x: `random(-50, 50)`,
          y: `random(-30, 30)`,
          rotation: `random(-180, 180)`,
          scale: `random(0.5, 1.5)`,
          opacity: `random(0.3, 1)`,
          duration: `random(2, 4)`,
          ease: "sine.inOut",
          repeat: -1,
          yoyo: true,
          delay: i * 0.1,
        });
      });
      break;

    case "liquidText":
      gsap.to(element, {
        scaleY: 1.5,
        scaleX: 0.7,
        skewX: 15,
        duration: 1,
        ease: "elastic.inOut(1, 0.3)",
        repeat: -1,
        yoyo: true,
      });

      gsap.to(element, {
        filter: "blur(1px)",
        duration: 2,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true,
      });
      break;

    case "splitTextAdvanced":
      if (typeof SplitText !== "undefined") {
        const split = new SplitText(element, { type: "chars,words" });

        gsap.from(split.chars, {
          opacity: 0,
          y: 100,
          rotation: "random(-180, 180)",
          scale: "random(0.5, 2)",
          duration: 1,
          stagger: {
            amount: 1.5,
            from: "random",
          },
          ease: "back.out(1.7)",
          repeat: -1,
          repeatDelay: 4,
        });

        gsap.to(split.words, {
          color:
            "random(['#ff6b9d', '#4ecdc4', '#45b7d1', '#96ceb4', '#feca57'])",
          duration: 2,
          stagger: 0.3,
          ease: "sine.inOut",
          repeat: -1,
          yoyo: true,
        });
      }
      break;

    case "splitTextWave":
      if (typeof SplitText !== "undefined") {
        const split = new SplitText(element, { type: "chars" });

        split.chars.forEach((char, i) => {
          gsap.to(char, {
            y: Math.sin(i * 0.5) * 20,
            rotation: Math.sin(i * 0.3) * 10,
            duration: 2,
            delay: i * 0.1,
            ease: "sine.inOut",
            repeat: -1,
            yoyo: true,
          });
        });
      }
      break;

    default:
      applyGSAPTextEffect(element, "textReveal");
  }
}

window.TextEffect = {
  create: createTextEffect,
  applyGSAP: applyGSAPTextEffect,
  applyAdvanced: applyAdvancedGSAPEffect,
  TYPES: TEXT_EFFECTS,
};
window.TextEffect = {
  create: createTextEffect,
  applyAdvanced: applyAdvancedGSAPEffect,
};

console.log("📝 textFx.js 模块加载完成");
