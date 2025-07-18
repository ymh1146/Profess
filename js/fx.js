if (typeof Flip !== "undefined" && !gsap.plugins.Flip) {
  gsap.registerPlugin(Flip);
  console.log("fx.js: Flip 已注册");
}

const fx = {
  fade: (current, next, onComplete) => {
    gsap.set(next, { opacity: 0 });
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    tl.to(current, { opacity: 0, duration: 1 }).to(
      next,
      { opacity: 1, duration: 1 },
      0
    );
  },

  slide: (current, next, onComplete) => {
    const direction = Math.random() > 0.5 ? 1 : -1;
    gsap.set(next, { x: direction * window.innerWidth, opacity: 1 });
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    tl.to(current, {
      x: -direction * window.innerWidth,
      duration: 1.2,
      ease: "power2.inOut",
    }).to(next, { x: 0, duration: 1.2, ease: "power2.inOut" }, 0);
  },

  scale: (current, next, onComplete) => {
    gsap.set(next, { scale: 0, opacity: 1 });
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    tl.to(current, { scale: 2, opacity: 0, duration: 1, ease: "power2.in" }).to(
      next,
      { scale: 1, duration: 1, ease: "power2.out" },
      0
    );
  },

  rotate: (current, next, onComplete) => {
    gsap.set(next, { rotation: 180, opacity: 0 });
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    tl.to(current, {
      rotation: -180,
      opacity: 0,
      duration: 1.2,
      ease: "power2.inOut",
    }).to(
      next,
      { rotation: 0, opacity: 1, duration: 1.2, ease: "power2.inOut" },
      0
    );
  },

  flip: (current, next, onComplete) => {
    gsap.set(next, { rotationY: 180, opacity: 1 });
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    tl.to(current, { rotationY: -90, duration: 0.6, ease: "power2.in" }).to(
      next,
      { rotationY: 0, duration: 0.6, delay: 0.6, ease: "power2.out" },
      0.6
    );
  },

  clip: (current, next, onComplete) => {
    gsap.set(next, { clipPath: "circle(0% at 50% 50%)", opacity: 1 });
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    tl.to(current, { opacity: 0, duration: 0.5 }).to(
      next,
      {
        clipPath: "circle(150% at 50% 50%)",
        duration: 1.5,
        ease: "power2.out",
      },
      0
    );
  },

  slideVertical: (current, next, onComplete) => {
    const direction = Math.random() > 0.5 ? 1 : -1;
    gsap.set(next, { y: direction * window.innerHeight, opacity: 1 });
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    tl.to(current, {
      y: -direction * window.innerHeight,
      duration: 1.2,
      ease: "power2.inOut",
    }).to(next, { y: 0, duration: 1.2, ease: "power2.inOut" }, 0);
  },

  slideDiagonal: (current, next, onComplete) => {
    const dirX = Math.random() > 0.5 ? 1 : -1;
    const dirY = Math.random() > 0.5 ? 1 : -1;
    gsap.set(next, {
      x: dirX * window.innerWidth,
      y: dirY * window.innerHeight,
      opacity: 1,
    });
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    tl.to(current, {
      x: -dirX * window.innerWidth,
      y: -dirY * window.innerHeight,
      duration: 1.3,
      ease: "power2.inOut",
    }).to(next, { x: 0, y: 0, duration: 1.3, ease: "power2.inOut" }, 0);
  },

  wave: (current, next, onComplete) => {
    gsap.set(next, { opacity: 0, scaleX: 0, transformOrigin: "center center" });
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    tl.to(current, {
      opacity: 0,
      scaleX: 0,
      duration: 0.8,
      ease: "power2.in",
    }).to(
      next,
      { opacity: 1, scaleX: 1, duration: 0.8, delay: 0.2, ease: "power2.out" },
      0.2
    );
  },

  blinds: (current, next, onComplete) => {
    gsap.set(next, {
      clipPath: "polygon(0% 0%, 0% 0%, 0% 100%, 0% 100%)",
      opacity: 1,
    });
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    tl.to(current, { opacity: 0, duration: 0.6 }).to(
      next,
      {
        clipPath: "polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%)",
        duration: 1.2,
        ease: "power2.out",
      },
      0
    );
  },

  spiral: (current, next, onComplete) => {
    gsap.set(next, { scale: 0, rotation: 360, opacity: 0 });
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    tl.to(current, {
      scale: 2,
      rotation: -360,
      opacity: 0,
      duration: 1,
      ease: "power2.in",
    }).to(
      next,
      {
        scale: 1,
        rotation: 0,
        opacity: 1,
        duration: 1,
        delay: 0.2,
        ease: "power2.out",
      },
      0.2
    );
  },

  bounce: (current, next, onComplete) => {
    gsap.set(next, { scale: 0, opacity: 1 });
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    tl.to(current, {
      scale: 0,
      opacity: 0,
      duration: 0.5,
      ease: "power2.in",
    }).to(
      next,
      { scale: 1, duration: 1, delay: 0.3, ease: "elastic.out(1, 0.5)" },
      0.3
    );
  },

  split: (current, next, onComplete) => {
    gsap.set(next, {
      clipPath: "polygon(50% 0%, 50% 0%, 50% 100%, 50% 100%)",
      opacity: 1,
    });
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    tl.to(current, { scaleX: 0, duration: 0.8, ease: "power2.in" }).to(
      next,
      {
        clipPath: "polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%)",
        duration: 1,
        delay: 0.2,
        ease: "power2.out",
      },
      0.2
    );
  },

  ripple: (current, next, onComplete) => {
    const centerX = Math.random() * 100;
    const centerY = Math.random() * 100;
    gsap.set(next, {
      clipPath: `circle(0% at ${centerX}% ${centerY}%)`,
      opacity: 1,
    });
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    tl.to(current, { opacity: 0, duration: 0.6 }).to(
      next,
      {
        clipPath: `circle(150% at ${centerX}% ${centerY}%)`,
        duration: 1.5,
        ease: "power2.out",
      },
      0
    );
  },

  cube3d: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { rotationY: 90, opacity: 1 });
    tl.to(current, { rotationY: -90, duration: 0.8, ease: "power2.inOut" }).to(
      next,
      { rotationY: 0, duration: 0.8, ease: "power2.inOut" },
      0.4
    );
  },

  mirror: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { scaleX: -1, opacity: 0 });
    tl.to(current, { scaleX: 0, duration: 0.6, ease: "power2.in" }).to(
      next,
      { scaleX: 1, opacity: 1, duration: 0.8, ease: "power2.out" },
      0.3
    );
  },

  elastic: (current, next, onComplete) => {
    gsap.set(next, { scale: 0, rotation: 180, opacity: 1 });
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    tl.to(current, {
      scale: 0,
      opacity: 0,
      duration: 0.6,
      ease: "power2.in",
    }).to(
      next,
      {
        scale: 1,
        rotation: 0,
        duration: 1.2,
        delay: 0.3,
        ease: "elastic.out(1, 0.3)",
      },
      0.3
    );
  },

  magnetic: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { scale: 0.1, opacity: 1, filter: "blur(10px)" });
    tl.to(current, {
      scale: 1.5,
      opacity: 0,
      filter: "blur(5px)",
      duration: 0.8,
      ease: "power2.in",
    }).to(
      next,
      { scale: 1, filter: "blur(0px)", duration: 1, ease: "back.out(1.7)" },
      0.4
    );
  },

  liquid: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { scaleY: 0, transformOrigin: "center bottom", opacity: 1 });
    tl.to(current, {
      scaleY: 0,
      transformOrigin: "center top",
      duration: 0.8,
      ease: "power2.in",
    }).to(next, { scaleY: 1, duration: 1, ease: "elastic.out(1, 0.5)" }, 0.4);
  },

  particle: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { opacity: 0, scale: 0.8, filter: "blur(3px)" });
    tl.to(current, {
      opacity: 0,
      scale: 1.2,
      filter: "blur(8px)",
      duration: 0.8,
      ease: "power2.in",
    }).to(
      next,
      {
        opacity: 1,
        scale: 1,
        filter: "blur(0px)",
        duration: 1,
        ease: "power2.out",
      },
      0.4
    );
  },

  glitch: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { opacity: 0, x: 0 });

    tl.to(current, { opacity: 0.8, x: -5, duration: 0.1 })
      .to(current, { opacity: 0.6, x: 5, duration: 0.1 })
      .to(current, { opacity: 0.4, x: -3, duration: 0.1 })
      .to(current, { opacity: 0, x: 0, duration: 0.2 })
      .to(next, { opacity: 0.4, x: 3, duration: 0.1 }, 0.4)
      .to(next, { opacity: 0.7, x: -2, duration: 0.1 })
      .to(next, { opacity: 1, x: 0, duration: 0.2 });
  },

  fold: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { scaleX: 0, transformOrigin: "left center", opacity: 1 });
    tl.to(current, {
      scaleX: 0,
      transformOrigin: "right center",
      duration: 0.8,
      ease: "power2.in",
    }).to(next, { scaleX: 1, duration: 0.8, ease: "power2.out" }, 0.4);
  },

  keyframes: (current, next, onComplete) => {
    gsap.set(next, { opacity: 0, scale: 0.5, rotation: -45 });
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    tl.to(current, {
      keyframes: [
        { scale: 1.1, rotation: 5, duration: 0.3 },
        { scale: 0.8, rotation: -5, duration: 0.3 },
        { scale: 0, opacity: 0, rotation: 45, duration: 0.4 },
      ],
      ease: "power2.inOut",
    });
    tl.to(
      next,
      {
        keyframes: [
          { opacity: 0.5, scale: 0.7, rotation: -30, duration: 0.4 },
          { opacity: 0.8, scale: 0.9, rotation: -10, duration: 0.3 },
          { opacity: 1, scale: 1, rotation: 0, duration: 0.3 },
        ],
        delay: 0.5,
        ease: "power2.out",
      },
      0.5
    );
  },

  spiralZoom: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { scale: 0, rotation: -360, opacity: 1 });
    tl.to(current, {
      scale: 3,
      rotation: 360,
      opacity: 0,
      duration: 1,
      ease: "power2.in",
    }).to(
      next,
      { scale: 1, rotation: 0, duration: 1.2, ease: "back.out(1.7)" },
      0.3
    );
  },

  breath: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { scale: 0.8, opacity: 0 });
    tl.to(current, {
      scale: 1.1,
      opacity: 0.7,
      duration: 0.5,
      ease: "power2.inOut",
      yoyo: true,
      repeat: 1,
    })
      .to(current, { scale: 0, opacity: 0, duration: 0.4, ease: "power2.in" })
      .to(
        next,
        {
          scale: 1,
          opacity: 1,
          duration: 0.8,
          ease: "elastic.out(1, 0.4)",
        },
        0.6
      );
  },
};

const fx3d = {
  pageFlip: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, {
      rotationY: -90,
      opacity: 0,
      transformOrigin: "left center",
    });
    tl.to(current, {
      rotationY: 90,
      opacity: 0,
      transformOrigin: "right center",
      duration: 1,
      ease: "power2.inOut",
    }).to(
      next,
      { rotationY: 0, opacity: 1, duration: 1, ease: "power2.inOut" },
      0.5
    );
  },

  cubeRotate: (current, next, onComplete) => {
    const direction = Math.random() > 0.5 ? 1 : -1;
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { rotationY: direction * 90, opacity: 0 });
    tl.to(current, {
      rotationY: -direction * 90,
      opacity: 0,
      duration: 1.2,
      ease: "power2.inOut",
    }).to(
      next,
      { rotationY: 0, opacity: 1, duration: 1.2, ease: "power2.inOut" },
      0.6
    );
  },

  fold3D: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, {
      rotationX: -90,
      opacity: 0,
      transformOrigin: "center bottom",
    });
    tl.to(current, {
      rotationX: 90,
      opacity: 0,
      transformOrigin: "center top",
      duration: 1,
      ease: "power2.inOut",
    }).to(
      next,
      { rotationX: 0, opacity: 1, duration: 1, ease: "power2.inOut" },
      0.5
    );
  },

  doorOpen: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, {
      rotationY: -180,
      opacity: 0,
      transformOrigin: "left center",
    });
    tl.to(current, {
      rotationY: 180,
      opacity: 0,
      transformOrigin: "right center",
      duration: 1.5,
      ease: "power2.inOut",
    }).to(
      next,
      { rotationY: 0, opacity: 1, duration: 1.5, ease: "power2.inOut" },
      0.3
    );
  },

  spiral3D: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { rotation: 360, scale: 0, opacity: 0 });
    tl.to(current, {
      rotation: -360,
      scale: 0,
      opacity: 0,
      duration: 1.2,
      ease: "power2.in",
    }).to(
      next,
      { rotation: 0, scale: 1, opacity: 1, duration: 1.2, ease: "power2.out" },
      0.4
    );
  },

  flipCard: (current, next, onComplete) => {
    const axis = Math.random() > 0.5 ? "X" : "Y";
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { [`rotation${axis}`]: 180, opacity: 0 });
    tl.to(current, {
      [`rotation${axis}`]: -180,
      opacity: 0,
      duration: 0.8,
      ease: "power2.inOut",
    }).to(
      next,
      {
        [`rotation${axis}`]: 0,
        opacity: 1,
        duration: 0.8,
        ease: "power2.inOut",
      },
      0.4
    );
  },

  wave3D: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { rotationX: -45, y: 100, opacity: 0 });
    tl.to(current, {
      rotationX: 45,
      y: -100,
      opacity: 0,
      duration: 1,
      ease: "sine.inOut",
    }).to(
      next,
      { rotationX: 0, y: 0, opacity: 1, duration: 1, ease: "sine.inOut" },
      0.5
    );
  },

  twist: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { rotation: 180, scaleX: 0, opacity: 0 });
    tl.to(current, {
      rotation: -180,
      scaleX: 0,
      opacity: 0,
      duration: 1,
      ease: "power2.inOut",
    }).to(
      next,
      { rotation: 0, scaleX: 1, opacity: 1, duration: 1, ease: "power2.inOut" },
      0.5
    );
  },
};

const fxAdvanced = {
  flipTransition: (current, next, onComplete) => {
    if (typeof Flip !== "undefined") {
      const state = Flip.getState(current);

      current.style.opacity = "0";
      next.style.opacity = "1";

      Flip.from(state, {
        duration: 1,
        ease: "power2.inOut",
        onComplete: () => {
          if (onComplete) onComplete();
        },
      });
    } else {
      const tl = gsap.timeline({
        onComplete: () => {
          if (onComplete) onComplete();
        },
      });

      const state = current.getBoundingClientRect();
      gsap.set(next, {
        x: state.left - next.getBoundingClientRect().left,
        y: state.top - next.getBoundingClientRect().top,
        opacity: 0,
      });

      tl.to(current, { opacity: 0, duration: 0.5 }).to(
        next,
        { x: 0, y: 0, opacity: 1, duration: 1, ease: "power2.inOut" },
        0.2
      );
    }
  },

  flipState: (current, next, onComplete) => {
    if (typeof Flip !== "undefined") {
      const state = Flip.getState([current, next]);

      const currentRect = current.getBoundingClientRect();
      const nextRect = next.getBoundingClientRect();

      current.style.transform = `translate(${
        nextRect.left - currentRect.left
      }px, ${nextRect.top - currentRect.top}px)`;
      next.style.transform = `translate(${
        currentRect.left - nextRect.left
      }px, ${currentRect.top - nextRect.top}px)`;

      current.style.opacity = "0";
      next.style.opacity = "1";

      Flip.from(state, {
        duration: 1.2,
        ease: "power2.inOut",
        onComplete: () => {
          current.style.transform = "";
          next.style.transform = "";
          if (onComplete) onComplete();
        },
      });
    } else {
      const tl = gsap.timeline({
        onComplete: () => {
          if (onComplete) onComplete();
        },
      });
      tl.to(current, { opacity: 0, duration: 0.6 }).to(
        next,
        { opacity: 1, duration: 0.6 },
        0.3
      );
    }
  },

  elasticScale: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { scale: 0, opacity: 1 });
    tl.to(current, {
      scale: 2,
      opacity: 0,
      duration: 0.8,
      ease: "power2.in",
    }).to(next, { scale: 1, duration: 1.2, ease: "elastic.out(1, 0.3)" }, 0.4);
  },

  liquidMorph: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { scaleY: 0, transformOrigin: "center bottom", opacity: 1 });
    tl.to(current, {
      scaleY: 0,
      transformOrigin: "center top",
      opacity: 0,
      duration: 0.8,
      ease: "power2.inOut",
    }).to(next, { scaleY: 1, duration: 1, ease: "elastic.out(1, 0.5)" }, 0.3);
  },

  particleDissolve: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { opacity: 0, scale: 0.8 });
    tl.to(current, {
      opacity: 0,
      scale: 1.2,
      filter: "blur(10px)",
      duration: 1,
      ease: "power2.in",
    }).to(
      next,
      {
        opacity: 1,
        scale: 1,
        duration: 1,
        ease: "power2.out",
      },
      0.5
    );
  },

  kaleidoscope: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { rotation: 360, scale: 0, opacity: 0 });
    tl.to(current, {
      rotation: -360,
      scale: 0,
      opacity: 0,
      duration: 1.5,
      ease: "power2.inOut",
    }).to(
      next,
      {
        rotation: 0,
        scale: 1,
        opacity: 1,
        duration: 1.5,
        ease: "power2.inOut",
      },
      0.3
    );
  },

  magneticAdvanced: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    const direction = Math.random() > 0.5 ? 1 : -1;
    gsap.set(next, { x: direction * 200, skewX: 20, opacity: 0 });
    tl.to(current, {
      x: -direction * 200,
      skewX: -20,
      opacity: 0,
      duration: 1,
      ease: "power2.inOut",
    }).to(
      next,
      {
        x: 0,
        skewX: 0,
        opacity: 1,
        duration: 1,
        ease: "back.out(1.7)",
      },
      0.3
    );
  },

  timeWarp: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { scaleX: 0, skewY: 45, opacity: 0 });
    tl.to(current, {
      scaleX: 0,
      skewY: -45,
      opacity: 0,
      duration: 1,
      ease: "power2.inOut",
    }).to(
      next,
      {
        scaleX: 1,
        skewY: 0,
        opacity: 1,
        duration: 1,
        ease: "power2.inOut",
      },
      0.4
    );
  },

  rippleAdvanced: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { scale: 0, opacity: 0 });

    for (let i = 0; i < 3; i++) {
      tl.to(
        current,
        {
          scale: 1 + i * 0.3,
          opacity: 1 - i * 0.3,
          duration: 0.3,
          ease: "sine.out",
        },
        i * 0.1
      );
    }

    tl.to(current, { opacity: 0, duration: 0.3 }).to(
      next,
      {
        scale: 1,
        opacity: 1,
        duration: 0.8,
        ease: "elastic.out(1, 0.3)",
      },
      0.5
    );
  },
};

const fxExtra = {
  carousel: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { rotationY: 90, z: -500, opacity: 0 });
    tl.to(current, {
      rotationY: -90,
      z: -500,
      opacity: 0,
      duration: 1,
      ease: "power2.inOut",
    }).to(
      next,
      { rotationY: 0, z: 0, opacity: 1, duration: 1, ease: "power2.inOut" },
      0.5
    );
  },

  fold3d: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, {
      rotationX: 90,
      transformOrigin: "center top",
      opacity: 0,
    });
    tl.to(current, {
      rotationX: -90,
      transformOrigin: "center bottom",
      opacity: 0,
      duration: 0.8,
      ease: "power3.in",
    }).to(
      next,
      { rotationX: 0, opacity: 1, duration: 0.8, ease: "power3.out" },
      0.4
    );
  },

  sphereUnfold: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { scale: 0, opacity: 0, transformOrigin: "center center" });
    tl.to(current, {
      scale: 0,
      opacity: 0,
      rotationX: 180,
      rotationY: 180,
      duration: 1,
      ease: "power2.in",
    }).to(
      next,
      {
        scale: 1,
        opacity: 1,
        rotationX: 0,
        rotationY: 0,
        duration: 1,
        ease: "elastic.out(1, 0.5)",
      },
      0.5
    );
  },
};

const fxObserver = {
  verticalScroll: (current, next, onComplete) => {
    const direction = Math.random() > 0.5 ? 1 : -1;
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { y: direction * window.innerHeight, opacity: 1 });
    tl.to(current, {
      y: -direction * window.innerHeight,
      duration: 1.2,
      ease: "power1.inOut",
    }).to(
      next,
      {
        y: 0,
        duration: 1.2,
        ease: "power1.inOut",
      },
      0
    );
  },

  horizontalScroll: (current, next, onComplete) => {
    const direction = Math.random() > 0.5 ? 1 : -1;
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { x: direction * window.innerWidth, opacity: 1 });
    tl.to(current, {
      x: -direction * window.innerWidth,
      duration: 1.2,
      ease: "power1.inOut",
    }).to(
      next,
      {
        x: 0,
        duration: 1.2,
        ease: "power1.inOut",
      },
      0
    );
  },

  diagonalScroll: (current, next, onComplete) => {
    const directionX = Math.random() > 0.5 ? 1 : -1;
    const directionY = Math.random() > 0.5 ? 1 : -1;
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, {
      x: directionX * window.innerWidth,
      y: directionY * window.innerHeight,
      opacity: 1,
    });
    tl.to(current, {
      x: -directionX * window.innerWidth,
      y: -directionY * window.innerHeight,
      duration: 1.5,
      ease: "power2.inOut",
    }).to(
      next,
      {
        x: 0,
        y: 0,
        duration: 1.5,
        ease: "power2.inOut",
      },
      0.2
    );
  },

  elasticEntry: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { scale: 0, opacity: 1 });
    tl.to(current, {
      scale: 2,
      opacity: 0,
      duration: 0.8,
      ease: "power2.in",
    }).to(next, { scale: 1, duration: 1.2, ease: "elastic.out(1, 0.3)" }, 0.4);
  },

  liquidMorphObserver: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { scaleY: 0, transformOrigin: "center bottom", opacity: 1 });
    tl.to(current, {
      scaleY: 0,
      transformOrigin: "center top",
      opacity: 0,
      duration: 0.8,
      ease: "power2.inOut",
    }).to(next, { scaleY: 1, duration: 1, ease: "elastic.out(1, 0.5)" }, 0.3);
  },

  magneticObserver: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    const direction = Math.random() > 0.5 ? 1 : -1;
    gsap.set(next, { x: direction * 200, skewX: 20, opacity: 0 });
    tl.to(current, {
      x: -direction * 200,
      skewX: -20,
      opacity: 0,
      duration: 1,
      ease: "power2.inOut",
    }).to(
      next,
      {
        x: 0,
        skewX: 0,
        opacity: 1,
        duration: 1,
        ease: "back.out(1.7)",
      },
      0.3
    );
  },

  timeWarpObserver: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { scaleX: 0, skewY: 45, opacity: 0 });
    tl.to(current, {
      scaleX: 0,
      skewY: -45,
      opacity: 0,
      duration: 1,
      ease: "power2.inOut",
    }).to(
      next,
      {
        scaleX: 1,
        skewY: 0,
        opacity: 1,
        duration: 1,
        ease: "power2.inOut",
      },
      0.4
    );
  },

  rippleObserver: (current, next, onComplete) => {
    const tl = gsap.timeline({
      onComplete: () => {
        if (onComplete) onComplete();
      },
    });
    gsap.set(next, { scale: 0, opacity: 0 });

    for (let i = 0; i < 3; i++) {
      tl.to(
        current,
        {
          scale: 1 + i * 0.3,
          opacity: 1 - i * 0.3,
          duration: 0.3,
          ease: "sine.out",
        },
        i * 0.1
      );
    }

    tl.to(current, { opacity: 0, duration: 0.3 }).to(
      next,
      {
        scale: 1,
        opacity: 1,
        duration: 0.8,
        ease: "elastic.out(1, 0.3)",
      },
      0.5
    );
  },
};

Object.assign(fx, fx3d, fxAdvanced, fxExtra, fxObserver);

window.fx = fx;

console.log("🎬 fx.js 模块加载完成");
console.log("📊 可用效果数量:", Object.keys(fx).length);
console.log(
  "📋 效果列表:",
  Object.keys(fx).slice(0, 10),
  "...(共" + Object.keys(fx).length + "个)"
);

const keyEffects = ["fade", "slide", "scale", "rotate", "flip"];
keyEffects.forEach((effect) => {
  if (fx[effect]) {
    console.log(`✅ ${effect} 效果可用`);
  } else {
    console.error(`❌ ${effect} 效果缺失`);
  }
});
