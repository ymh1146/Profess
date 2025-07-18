function createFloatingEffect(loveOverlay, floatingStyle = "hearts") {
  if (floatingStyle === "none") return;

  const effects = {
    hearts: {
      type: "emoji",
      items: [
        "💕",
        "💖",
        "💗",
        "💝",
        "💘",
        "💞",
        "💓",
        "💟",
        "❤️",
        "🧡",
        "💛",
        "💚",
        "💙",
        "💜",
        "🤍",
        "🖤",
      ],
      spawnInterval: 1000,
    },
    petals: {
      type: "mixed",
      items: ["🌸", "🌺", "🌻", "🌷", "🌹", "🌼"],
      cssClass: "petal",
      spawnInterval: 800,
    },
    butterflies: {
      type: "mixed",
      items: ["🦋"],
      cssClass: "butterfly",
      spawnInterval: 1500,
    },
    sparkles: {
      type: "css",
      cssClass: "star",
      spawnInterval: 600,
    },
    bubbles: {
      type: "css",
      cssClass: "bubble",
      spawnInterval: 1200,
    },
    snowflakes: {
      type: "css",
      cssClass: "snowflake",
      spawnInterval: 400,
    },
    fireflies: {
      type: "css",
      cssClass: "firefly",
      spawnInterval: 2000,
    },
    feathers: {
      type: "mixed",
      items: ["🪶"],
      cssClass: "feather",
      spawnInterval: 1800,
    },
    rainbow: {
      type: "css",
      cssClass: "rainbow",
      spawnInterval: 1400,
    },

    stars: {
      type: "mixed",
      items: ["⭐", "✨", "💫"],
      cssClass: "star",
      spawnInterval: 1000,
    },
    clouds: {
      type: "mixed",
      items: ["☁️", "⛅"],
      cssClass: "cloud",
      spawnInterval: 2500,
    },
    music: {
      type: "mixed",
      items: ["🎵", "🎶", "🎼", "🎸", "🎹"],
      cssClass: "music",
      spawnInterval: 1200,
    },
  };

  function createEffectElement(style) {
    const element = document.createElement("div");
    element.className = "floating-element";

    const config = effects[style] || effects.hearts;

    if (config.type === "emoji") {
      element.textContent =
        config.items[Math.floor(Math.random() * config.items.length)];
      element.style.fontSize = Math.random() * 20 + 15 + "px";
    } else if (config.type === "css") {
      element.classList.add(config.cssClass);
    } else if (config.type === "mixed") {
      if (Math.random() < 0.5 && config.items) {
        element.textContent =
          config.items[Math.floor(Math.random() * config.items.length)];
        element.style.fontSize = Math.random() * 20 + 15 + "px";
      } else {
        element.classList.add(config.cssClass);
      }
    }

    element.style.position = "absolute";
    element.style.pointerEvents = "none";
    element.style.zIndex = "5";

    return element;
  }

  function getSpawnInterval(style) {
    return effects[style]?.spawnInterval || 1000;
  }

  const intervalId = setInterval(() => {
    const element = createEffectElement(floatingStyle);
    createGSAPFloatingAnimation(element, floatingStyle);
    loveOverlay.appendChild(element);
  }, getSpawnInterval(floatingStyle));

  return () => clearInterval(intervalId);
}

function createGSAPFloatingAnimation(element, style) {
  const startX = Math.random() * window.innerWidth;
  const startY = window.innerHeight + 50;

  gsap.set(element, {
    x: startX,
    y: startY,
    opacity: 0,
    scale: 0.5,
  });

  let tl = gsap.timeline({
    onComplete: () => {
      if (element.parentNode) {
        element.parentNode.removeChild(element);
      }
    },
  });

  switch (style) {
    case "hearts":
      tl.to(element, {
        opacity: 1,
        scale: 1,
        duration: 0.5,
        ease: "back.out(1.7)",
      })
        .to(
          element,
          {
            y: -100,
            duration: 4,
            ease: "sine.inOut",
          },
          0.5
        )
        .to(element, { opacity: 0, scale: 0.3, duration: 1 }, "-=1");

      gsap.to(element, {
        x: `+=${Math.sin(Date.now() * 0.001) * 50}`,
        duration: 2,
        repeat: -1,
        yoyo: true,
        ease: "sine.inOut",
      });
      break;

    case "petals":
      gsap.set(element, { y: -50, x: startX });
      tl.to(element, {
        opacity: 1,
        scale: 1,
        duration: 0.5,
        ease: "back.out(1.7)",
      })
        .to(
          element,
          {
            y: window.innerHeight * 0.3,
            rotation: 360,
            duration: 2,
            ease: "sine.inOut",
          },
          0.5
        )
        .to(element, {
          y: -100,
          rotation: 720,
          duration: 3,
          ease: "sine.out",
        })
        .to(element, { opacity: 0, scale: 0.5, duration: 1 }, "-=1");

      gsap.to(element, {
        x: `+=${gsap.utils.random(-80, 80)}`,
        duration: gsap.utils.random(1.5, 2.5),
        repeat: -1,
        yoyo: true,
        ease: "sine.inOut",
      });
      break;

    case "butterflies":
      tl.to(element, {
        opacity: 1,
        scale: 1,
        duration: 0.3,
        ease: "back.out(1.7)",
      })
        .to(
          element,
          {
            y: -100,
            duration: 5,
            ease: "none",
          },
          0.3
        )
        .to(element, { opacity: 0, scale: 0.8, duration: 0.8 }, "-=0.8");

      gsap.to(element, {
        motionPath: {
          path: `M0,0 Q50,-50 0,-100 Q-50,-150 0,-200 Q50,-250 0,-300`,
          autoRotate: false,
        },
        duration: 5,
        ease: "none",
      });

      gsap.to(element, {
        scaleX: 0.8,
        duration: 0.2,
        repeat: -1,
        yoyo: true,
        ease: "power2.inOut",
      });
      break;

    case "sparkles":
      tl.to(element, {
        opacity: 1,
        scale: gsap.utils.random(0.5, 1.5),
        duration: 0.1,
        ease: "power2.out",
      })
        .to(
          element,
          {
            y: -100,
            duration: 6,
            ease: "none",
          },
          0.1
        )
        .to(element, { opacity: 0, duration: 1 }, "-=1");

      gsap.to(element, {
        opacity: gsap.utils.random(0.3, 1),
        duration: gsap.utils.random(0.1, 0.3),
        repeat: -1,
        yoyo: true,
        ease: "power2.inOut",
      });

      gsap.to(element, {
        x: `+=${gsap.utils.random(-20, 20)}`,
        duration: gsap.utils.random(2, 4),
        repeat: -1,
        yoyo: true,
        ease: "sine.inOut",
      });
      break;

    case "bubbles":
      tl.to(element, {
        opacity: 0.7,
        scale: gsap.utils.random(0.5, 1.2),
        duration: 0.5,
      })
        .to(
          element,
          {
            y: -100,
            duration: gsap.utils.random(4, 7),
            ease: "none",
          },
          0.5
        )
        .to(element, { opacity: 0, scale: 0.3, duration: 1 }, "-=1");

      gsap.to(element, {
        x: `+=${gsap.utils.random(-30, 30)}`,
        duration: gsap.utils.random(1, 2),
        repeat: -1,
        yoyo: true,
        ease: "sine.inOut",
      });

      gsap.to(element, {
        scale: `*=${gsap.utils.random(0.8, 1.2)}`,
        duration: gsap.utils.random(1, 3),
        repeat: -1,
        yoyo: true,
        ease: "sine.inOut",
      });
      break;

    case "snowflakes":
      gsap.set(element, { y: -50, x: startX });
      tl.to(element, {
        opacity: 1,
        scale: gsap.utils.random(0.5, 1.2),
        duration: 0.5,
      })
        .to(
          element,
          {
            y: window.innerHeight + 50,
            rotation: gsap.utils.random(360, 720),
            duration: gsap.utils.random(4, 7),
            ease: "none",
          },
          0.5
        )
        .to(element, { opacity: 0, duration: 1 }, "-=1");

      gsap.to(element, {
        x: `+=${gsap.utils.random(-60, 60)}`,
        duration: gsap.utils.random(1.5, 3),
        repeat: -1,
        yoyo: true,
        ease: "sine.inOut",
      });
      break;

    case "fireflies":
      tl.to(element, { opacity: 1, scale: 0.8, duration: 0.3 })
        .to(
          element,
          {
            y: gsap.utils.random(-50, -150),
            x: `+=${gsap.utils.random(-200, 200)}`,
            duration: gsap.utils.random(3, 6),
            ease: "none",
          },
          0.3
        )
        .to(element, { opacity: 0, duration: 0.5 }, "-=0.5");

      gsap.to(element, {
        opacity: gsap.utils.random(0.2, 1),
        duration: gsap.utils.random(0.3, 0.8),
        repeat: -1,
        yoyo: true,
        ease: "power2.inOut",
      });

      gsap.to(element, {
        x: `+=${gsap.utils.random(-100, 100)}`,
        y: `+=${gsap.utils.random(-50, 50)}`,
        duration: gsap.utils.random(1, 2),
        repeat: -1,
        yoyo: true,
        ease: "sine.inOut",
      });
      break;

    case "feathers":
      gsap.set(element, { y: -30, x: startX });
      tl.to(element, {
        opacity: 1,
        scale: 1,
        duration: 0.8,
        ease: "power1.out",
      })
        .to(
          element,
          {
            y: window.innerHeight + 50,
            rotation: gsap.utils.random(-180, 180),
            duration: gsap.utils.random(6, 10),
            ease: "none",
          },
          0.8
        )
        .to(element, { opacity: 0, duration: 1.5 }, "-=1.5");

      gsap.to(element, {
        x: `+=${gsap.utils.random(-40, 40)}`,
        duration: gsap.utils.random(2, 4),
        repeat: -1,
        yoyo: true,
        ease: "sine.inOut",
      });

      gsap.to(element, {
        rotation: `+=${gsap.utils.random(-30, 30)}`,
        duration: gsap.utils.random(3, 5),
        repeat: -1,
        yoyo: true,
        ease: "sine.inOut",
      });
      break;

    case "rainbow":
      tl.to(element, {
        opacity: 1,
        scale: 1,
        duration: 0.5,
        ease: "back.out(1.7)",
      })
        .to(
          element,
          {
            y: -100,
            duration: 4,
            ease: "sine.inOut",
          },
          0.5
        )
        .to(element, { opacity: 0, scale: 0.5, duration: 1 }, "-=1");

      gsap.to(element, {
        motionPath: {
          path: `M0,0 Q${gsap.utils.random(-100, 100)},${
            -window.innerHeight * 0.3
          } ${gsap.utils.random(-50, 50)},${-window.innerHeight * 0.8}`,
          autoRotate: false,
        },
        duration: 4,
        ease: "sine.inOut",
      });

      gsap.to(element, {
        opacity: 0.8,
        duration: 0.5,
        repeat: -1,
        yoyo: true,
        ease: "sine.inOut",
      });
      break;

    case "stars":
      tl.to(element, {
        opacity: 1,
        scale: gsap.utils.random(0.7, 1.3),
        duration: 0.3,
      })
        .to(
          element,
          {
            y: -100,
            duration: 5,
            ease: "power1.out",
          },
          0.3
        )
        .to(element, { opacity: 0, duration: 1 }, "-=1");

      gsap.to(element, {
        scale: gsap.utils.random(0.8, 1.2),
        opacity: gsap.utils.random(0.6, 1),
        duration: gsap.utils.random(0.5, 1.5),
        repeat: -1,
        yoyo: true,
        ease: "power1.inOut",
      });
      break;

    case "clouds":
      gsap.set(element, {
        y: gsap.utils.random(-50, window.innerHeight * 0.5),
        x: -100,
      });
      tl.to(element, {
        opacity: 0.8,
        scale: gsap.utils.random(0.8, 1.5),
        duration: 0.8,
      })
        .to(
          element,
          {
            x: window.innerWidth + 100,
            duration: gsap.utils.random(15, 25),
            ease: "none",
          },
          0.8
        )
        .to(element, { opacity: 0, duration: 1 }, "-=1");

      gsap.to(element, {
        y: `+=${gsap.utils.random(-20, 20)}`,
        duration: gsap.utils.random(3, 6),
        repeat: -1,
        yoyo: true,
        ease: "sine.inOut",
      });
      break;

    case "music":
      tl.to(element, {
        opacity: 1,
        scale: 1,
        duration: 0.3,
        ease: "back.out(1.7)",
      })
        .to(
          element,
          {
            y: -100,
            duration: 3,
            ease: "power2.out",
          },
          0.3
        )
        .to(element, { opacity: 0, scale: 0.5, duration: 0.8 }, "-=0.8");

      gsap.to(element, {
        motionPath: {
          path: `M0,0 C${gsap.utils.random(-30, 30)},${gsap.utils.random(
            -50,
            -20
          )} ${gsap.utils.random(-20, 20)},${gsap.utils.random(
            -100,
            -70
          )} ${gsap.utils.random(-40, 40)},${gsap.utils.random(-150, -100)}`,
          autoRotate: true,
        },
        duration: 3,
        ease: "power1.out",
      });

      gsap.to(element, {
        rotation: gsap.utils.random(-30, 30),
        duration: 1,
        repeat: -1,
        yoyo: true,
        ease: "sine.inOut",
      });
      break;

    default:
      tl.to(element, {
        opacity: 1,
        scale: 1,
        duration: 0.5,
        ease: "back.out(1.7)",
      })
        .to(element, { y: -100, duration: 4, ease: "sine.inOut" }, 0.5)
        .to(element, { opacity: 0, duration: 1 }, "-=1");
  }
}

window.FloatingEffect = {
  create: createFloatingEffect,
  animate: createGSAPFloatingAnimation,
};
