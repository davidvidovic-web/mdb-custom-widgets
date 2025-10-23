/**
 * MDB Slider Widget JavaScript
 *
 * @package MDB Custom Widgets
 * @since 1.0.0
 */

(function ($) {
  "use strict";

  /**
   * MDB Slider Class
   */
  class MDBSlider {
    constructor(element) {
      this.element = element;
      this.$element = $(element);
      // Count only .mdb-slide-content elements to avoid counting duplicates
      // (both content and images have data-slide attributes)
      this.totalSlides = this.$element.find(".mdb-slide-content[data-slide]").length;
      this.currentSlide = 0;
      this.isInitialized = false;
      this.config = this.getConfig();

      // Validate element
      if (!this.$element.length || this.totalSlides === 0) {
        console.warn("MDB Slider: No valid slider element or slides found");
        return;
      }

      this.init();
    }

    /**
     * Initialize slider
     */
    init() {
      this.bindEvents();
      this.setupAccessibility();
      this.isInitialized = true;

      // Set up auto-advance if enabled
      if (this.config.autoplay) {
        this.startAutoplay();
      }

      console.log("MDB Slider: Initialized", this.config);
    }

    /**
     * Start autoplay functionality
     */
    startAutoplay() {
      if (this.autoplayInterval) {
        clearInterval(this.autoplayInterval);
      }

      this.autoplayInterval = setInterval(() => {
        if (!this.$element.is(":hover") || !this.config.pauseOnHover) {
          this.nextSlide();
        }
      }, this.config.autoplaySpeed);

      // Pause on hover if enabled
      if (this.config.pauseOnHover) {
        this.$element
          .on("mouseenter.mdb-autoplay", () => {
            if (this.autoplayInterval) {
              clearInterval(this.autoplayInterval);
            }
          })
          .on("mouseleave.mdb-autoplay", () => {
            this.startAutoplay();
          });
      }
    }

    /**
     * Get slider configuration
     */
    getConfig() {
      // Try to get config from JSON script tag first
      const $configScript = this.$element.find(".mdb-slider-config");
      let config = {};

      if ($configScript.length) {
        try {
          config = JSON.parse($configScript.text());
        } catch (e) {
          console.warn("MDB Slider: Invalid JSON config, using defaults");
        }
      }

      // Fallback to data attributes
      const dataConfig = this.$element.data("settings") || {};

      return {
        autoplay:
          config.autoplay !== undefined
            ? config.autoplay
            : dataConfig.autoplay !== false,
        autoplaySpeed:
          config.autoplaySpeed || parseInt(dataConfig.autoplay_speed) || 3000,
        transitionSpeed:
          config.transitionSpeed ||
          parseInt(dataConfig.transition_speed) ||
          500,
        pauseOnHover:
          config.pauseOnHover !== undefined
            ? config.pauseOnHover
            : dataConfig.pause_on_hover !== false,
        loop:
          config.loop !== undefined ? config.loop : dataConfig.loop !== false,
        totalSlides: config.totalSlides || this.totalSlides,
        counterFormat:
          config.counterFormat || dataConfig.counter_format || "x_of_y",
      };
    }

    /**
     * Bind event handlers
     */
    bindEvents() {
      // Progress bar navigation
      this.$element.on("click.mdb-slider", ".mdb-progress-bar", (e) => {
        const slideIndex =
          parseInt($(e.currentTarget).data("slide")) ||
          $(e.currentTarget).index();
        this.goToSlide(slideIndex);
      });

      // Keyboard navigation
      this.setupKeyboardNavigation();

      // Touch/swipe navigation
      this.setupSwipeGestures();
    }

    /**
     * Setup keyboard navigation
     */
    setupKeyboardNavigation() {
      this.$element.on("keydown.mdb-slider", (e) => {
        if (this.$element.is(":focus") || this.$element.find(":focus").length) {
          switch (e.keyCode) {
            case 37: // Left arrow
              e.preventDefault();
              this.previousSlide();
              break;
            case 39: // Right arrow
              e.preventDefault();
              this.nextSlide();
              break;
            case 36: // Home
              e.preventDefault();
              this.goToSlide(0);
              break;
            case 35: // End
              e.preventDefault();
              this.goToSlide(this.totalSlides - 1);
              break;
          }
        }
      });
    }

    /**
     * Setup swipe gestures for mobile
     */
    setupSwipeGestures() {
      let touchStartX = 0;
      let touchStartY = 0;

      this.$element.on("touchstart.mdb-slider", (e) => {
        touchStartX = e.originalEvent.touches[0].clientX;
        touchStartY = e.originalEvent.touches[0].clientY;
      });

      this.$element.on("touchend.mdb-slider", (e) => {
        if (!touchStartX || !touchStartY) return;

        const touchEndX = e.originalEvent.changedTouches[0].clientX;
        const touchEndY = e.originalEvent.changedTouches[0].clientY;
        const diffX = touchStartX - touchEndX;
        const diffY = touchStartY - touchEndY;

        // Check if horizontal swipe is more significant than vertical
        if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
          if (diffX > 0) {
            this.nextSlide();
          } else {
            this.previousSlide();
          }
        }
      });
    }

    /**
     * Setup accessibility attributes
     */
    setupAccessibility() {
      // Main container
      this.$element.attr({
        role: "region",
        "aria-label": "Image slider",
        tabindex: "0",
      });

      // Progress bars
      this.$element.find(".mdb-progress-bar").each((index, el) => {
        $(el).attr({
          role: "button",
          tabindex: "0",
          "aria-label": `Go to slide ${index + 1}`,
          "aria-pressed": index === 0 ? "true" : "false",
        });
      });

      // Slides
      this.$element
        .find(".mdb-slide-content, .mdb-slide-images")
        .each((index, el) => {
          $(el).attr({
            role: "tabpanel",
            "aria-hidden": index === 0 ? "false" : "true",
            "aria-label": `Slide ${index + 1} of ${this.totalSlides}`,
          });
        });
    }

    /**
     * Go to specific slide
     */
    goToSlide(slideIndex, direction = "next") {
      if (
        slideIndex < 0 ||
        slideIndex >= this.totalSlides ||
        slideIndex === this.currentSlide
      ) {
        return;
      }

      const $currentContent = this.$element
        .find(".mdb-slide-content")
        .eq(this.currentSlide);
      const $nextContent = this.$element
        .find(".mdb-slide-content")
        .eq(slideIndex);
      const $currentImages = this.$element
        .find(".mdb-slide-images")
        .eq(this.currentSlide);
      const $nextImages = this.$element
        .find(".mdb-slide-images")
        .eq(slideIndex);

      // Add transition classes based on direction
      const outClass =
        direction === "next" ? "slide-out-left" : "slide-out-right";
      const inClass = direction === "next" ? "slide-in-right" : "slide-in-left";

      // Animate out current slides
      $currentContent.addClass(outClass);
      $currentImages.addClass(outClass);

      // Animate in new slides
      setTimeout(() => {
        $currentContent.removeClass("active " + outClass);
        $currentImages.removeClass("active " + outClass);

        $nextContent.addClass(inClass + " active");
        $nextImages.addClass(inClass + " active");

        // Clean up animation classes
        setTimeout(() => {
          $nextContent.removeClass(inClass);
          $nextImages.removeClass(inClass);
        }, this.config.transitionSpeed);
      }, 50);

      this.currentSlide = slideIndex;
      this.updateActiveStates();
    }

    /**
     * Go to next slide
     */
    nextSlide() {
      const nextIndex =
        this.config.loop && this.currentSlide === this.totalSlides - 1
          ? 0
          : this.currentSlide + 1;
      if (nextIndex < this.totalSlides) {
        this.goToSlide(nextIndex, "next");
      }
    }

    /**
     * Go to previous slide
     */
    previousSlide() {
      const prevIndex =
        this.config.loop && this.currentSlide === 0
          ? this.totalSlides - 1
          : this.currentSlide - 1;
      if (prevIndex >= 0) {
        this.goToSlide(prevIndex, "prev");
      }
    }

    /**
     * Update active states for all elements
     */
    updateActiveStates() {
      // Update progress bars
      this.$element
        .find(".mdb-progress-bar")
        .removeClass("active")
        .eq(this.currentSlide)
        .addClass("active");

      // Update counter
      this.updateCounter();

      // Update accessibility
      this.updateAccessibility();
    }

    /**
     * Update slide counter
     */
    updateCounter() {
      const $counter = this.$element.find(".mdb-slide-counter");
      if ($counter.length) {
        const counterText = this.getCounterText(
          this.currentSlide + 1,
          this.totalSlides,
          this.config.counterFormat
        );
        $counter.text(counterText);
      }
    }

    /**
     * Get counter text based on format
     */
    getCounterText(current, total, format) {
      switch (format) {
        case "x_slash_y":
          return `${current} / ${total}`;
        case "x_pipe_y":
          return `${current} | ${total}`;
        case "x_of_y":
        default:
          return `${current} of ${total}`;
      }
    }

    /**
     * Update accessibility attributes
     */
    updateAccessibility() {
      // Update progress bars
      this.$element.find(".mdb-progress-bar").each((index, el) => {
        $(el).attr(
          "aria-pressed",
          index === this.currentSlide ? "true" : "false"
        );
      });

      // Update slides
      this.$element
        .find(".mdb-slide-content, .mdb-slide-images")
        .each((index, el) => {
          $(el).attr(
            "aria-hidden",
            index === this.currentSlide ? "false" : "true"
          );
        });
    }

    /**
     * Destroy slider and clean up
     */
    destroy() {
      if (this.autoplayInterval) {
        clearInterval(this.autoplayInterval);
      }

      this.$element.off(".mdb-slider").off(".mdb-autoplay");
      this.isInitialized = false;
    }

    /**
     * Refresh/reinitialize slider
     */
    refresh() {
      if (this.isInitialized) {
        this.destroy();
      }
      this.init();
    }
  }

  /**
   * jQuery Plugin
   */
  $.fn.mdbSlider = function (options) {
    return this.each(function () {
      const $element = $(this);

      // Prevent double initialization
      if ($element.data("mdb-slider")) {
        return;
      }

      const slider = new MDBSlider(this);
      $element.data("mdb-slider", slider);
    });
  };

  /**
   * Initialize sliders when MDBWidgets is available
   */
  function initSliders() {
    $(".mdb-slider-widget").each(function () {
      if (!$(this).data("mdb-slider")) {
        $(this).mdbSlider();
      }
    });
  }

  /**
   * MDB Slider widget handler for MDBWidgets system
   */
  if (typeof window.MDBWidgets !== "undefined") {
    window.MDBWidgets.slider = initSliders;
  }

  /**
   * Elementor frontend integration
   */
  function initElementorIntegration() {
    // Initialize when Elementor frontend is ready
    if (window.elementorFrontend && window.elementorFrontend.hooks) {
      // Hook into Elementor's widget initialization
      window.elementorFrontend.hooks.addAction(
        "frontend/element_ready/mdb-slider.default",
        function ($scope) {
          $scope.find(".mdb-slider-widget").mdbSlider();
        }
      );
    }

    // Fallback initialization
    initSliders();
  }

  /**
   * Multiple initialization approaches for maximum compatibility
   */

  // When document is ready
  $(document).ready(function () {
    initSliders();
  });

  // When Elementor frontend is initialized
  $(window).on("elementor/frontend/init", function () {
    setTimeout(initElementorIntegration, 100);
  });

  // When all content is loaded
  $(window).on("load", function () {
    setTimeout(initSliders, 500);
  });

  // After AJAX content changes
  $(document).ajaxComplete(function () {
    setTimeout(initSliders, 300);
  });

  // Export for global access
  window.MDBSlider = MDBSlider;
})(jQuery);
