(function () {
  "use strict";

  function Sortable(el, options) {
    if (!(el && el.nodeType && el.nodeType === 1)) {
      throw new Error(
        "Sortable: `el` must be HTMLElement, not " + {}.toString.call(el)
      );
    }

    this.el = el;
    this.options = Object.assign(
      {
        animation: 150,
        ghostClass: "sortable-ghost",
        chosenClass: "sortable-chosen",
        dragClass: "sortable-drag",
      },
      options || {}
    );

    this._init();
  }

  Sortable.prototype = {
    _init: function () {
      var self = this;
      var el = this.el;

      el.addEventListener("mousedown", function (e) {
        self._onMouseDown(e);
      });

      el.addEventListener("touchstart", function (e) {
        self._onTouchStart(e);
      });
    },

    _onMouseDown: function (e) {
      if (e.button !== 0) return;
      this._prepareDrag(e, e);
    },

    _onTouchStart: function (e) {
      this._prepareDrag(e, e.touches[0]);
    },

    _prepareDrag: function (originalEvent, pointer) {
      var self = this;
      var target = originalEvent.target;

      if (this.options.handle) {
        var handleElement = target.closest(this.options.handle);
        if (!handleElement) {
          console.log("未点击拖拽句柄，取消拖拽");
          return;
        }
        console.log("点击了拖拽句柄:", handleElement);
      }

      var dragTarget = target;
      while (dragTarget && dragTarget !== this.el) {
        if (dragTarget.parentNode === this.el) {
          break;
        }
        dragTarget = dragTarget.parentNode;
      }

      if (!dragTarget || dragTarget === this.el) return;

      target = dragTarget;

      this.dragEl = target;
      this.startIndex = Array.from(this.el.children).indexOf(target);

      target.classList.add(this.options.chosenClass);

      var rect = target.getBoundingClientRect();
      this.offset = {
        x: pointer.clientX - rect.left,
        y: pointer.clientY - rect.top,
      };

      this.ghost = target.cloneNode(true);
      this.ghost.classList.add(this.options.ghostClass);
      this.ghost.style.position = "fixed";
      this.ghost.style.zIndex = "100000";
      this.ghost.style.pointerEvents = "none";
      this.ghost.style.left = pointer.clientX - this.offset.x + "px";
      this.ghost.style.top = pointer.clientY - this.offset.y + "px";
      document.body.appendChild(this.ghost);

      target.classList.add(this.options.dragClass);

      this._onMouseMove = function (e) {
        self._onMove(e, e);
      };
      this._onTouchMove = function (e) {
        self._onMove(e, e.touches[0]);
      };
      this._onMouseUp = function (e) {
        self._onDrop(e);
      };
      this._onTouchEnd = function (e) {
        self._onDrop(e);
      };

      document.addEventListener("mousemove", this._onMouseMove);
      document.addEventListener("touchmove", this._onTouchMove);
      document.addEventListener("mouseup", this._onMouseUp);
      document.addEventListener("touchend", this._onTouchEnd);

      originalEvent.preventDefault();
    },

    _onMove: function (originalEvent, pointer) {
      if (!this.dragEl) return;

      this.ghost.style.left = pointer.clientX - this.offset.x + "px";
      this.ghost.style.top = pointer.clientY - this.offset.y + "px";

      var target = document.elementFromPoint(pointer.clientX, pointer.clientY);
      while (target && target !== this.el && target.parentNode !== this.el) {
        target = target.parentNode;
      }

      if (target && target !== this.dragEl && target.parentNode === this.el) {
        var targetRect = target.getBoundingClientRect();
        var dragRect = this.dragEl.getBoundingClientRect();

        if (pointer.clientY < targetRect.top + targetRect.height / 2) {
          this.el.insertBefore(this.dragEl, target);
        } else {
          this.el.insertBefore(this.dragEl, target.nextSibling);
        }
      }

      originalEvent.preventDefault();
    },

    _onDrop: function (e) {
      if (!this.dragEl) return;

      document.removeEventListener("mousemove", this._onMouseMove);
      document.removeEventListener("touchmove", this._onTouchMove);
      document.removeEventListener("mouseup", this._onMouseUp);
      document.removeEventListener("touchend", this._onTouchEnd);

      this.dragEl.classList.remove(this.options.chosenClass);
      this.dragEl.classList.remove(this.options.dragClass);

      if (this.ghost && this.ghost.parentNode) {
        this.ghost.parentNode.removeChild(this.ghost);
      }

      var endIndex = Array.from(this.el.children).indexOf(this.dragEl);
      if (this.startIndex !== endIndex && this.options.onEnd) {
        this.options.onEnd({
          oldIndex: this.startIndex,
          newIndex: endIndex,
          item: this.dragEl,
        });
      }

      this.dragEl = null;
      this.ghost = null;
      this.startIndex = -1;

      e.preventDefault();
    },
  };

  if (typeof module !== "undefined" && module.exports) {
    module.exports = Sortable;
  } else if (typeof define === "function" && define.amd) {
    define(function () {
      return Sortable;
    });
  } else {
    window.Sortable = Sortable;
  }
})();
