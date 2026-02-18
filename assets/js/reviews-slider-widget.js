/**
 * MDB Reviews Slider Widget JavaScript
 * 
 * Handles reviews slider functionality with a two-column staggered layout and autoplay
 *
 * @package MDB Custom Widgets  
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * MDB Reviews Slider Class
     */
    class MDBReviewsSlider {
        
        /**
         * Constructor
         *
         * @param {jQuery} $element Widget element
         */
        constructor($element) {
            this.$element = $element;
            this.$widget = $element.closest('.elementor-widget');
            this.$container = $element.find('.mdb-reviews-slider-container');
            this.$slides = $element.find('.mdb-reviews-slide');
            
            this.sliderId = $element.attr('id');
            this.currentSlide = 0;
            this.totalSlides = this.$slides.length;
            this.autoplay = $element.data('autoplay') === 'true' || $element.data('autoplay') === true;
            this.autoplaySpeed = parseInt($element.data('autoplay-speed')) || 5000;
            this.pauseOnHover = $element.data('pause-on-hover') === 'true' || $element.data('pause-on-hover') === true;
            this.autoplayTimer = null;
            this.isPlaying = false;
            
            this.init();
        }

        /**
         * Initialize the slider
         */
        init() {
            this.setupSlider();
            this.bindEvents();
            this.startAutoplay();
            this.notifyReady();
            
            // Debug logging
            if (window.MDBWidgets && window.MDBWidgets.debug) {
                console.log('MDB Reviews Slider initialized:', this.sliderId, {
                    slides: this.totalSlides,
                    autoplay: this.autoplay,
                    speed: this.autoplaySpeed
                });
            }
        }

        /**
         * Set up slider initial state
         */
        setupSlider() {
            // Ensure first slide is active
            this.$slides.removeClass('active');
            if (this.$slides.length > 0) {
                this.$slides.eq(0).addClass('active');
            }

            // Initialize navigation button states
            this.updateNavigationButtons();

            // Sync responsive CSS variables
            this.handleResponsive();
        }

        /**
         * Bind slider events
         */
        bindEvents() {
            const self = this;

            // Internal navigation buttons
            this.$element.find('.mdb-reviews-prev').on('click', function(e) {
                e.preventDefault();
                self.prevSlide();
            });

            this.$element.find('.mdb-reviews-next').on('click', function(e) {
                e.preventDefault();
                self.nextSlide();
            });

            // Navigation events from external navigation widgets (legacy support)
            $(document).on('mdb-reviews-navigate', function(e, data) {
                if (data.sliderId === self.sliderId) {
                    if (data.direction === 'prev') {
                        self.prevSlide();
                    } else if (data.direction === 'next') {
                        self.nextSlide();
                    }
                }
            });

            // Pause on hover if enabled
            if (this.pauseOnHover) {
                this.$element.on('mouseenter', function() {
                    self.pauseAutoplay();
                });

                this.$element.on('mouseleave', function() {
                    self.resumeAutoplay();
                });
            }

            // Handle window resize (update column count variable)
            this._resizeHandler = () => {
                self.handleResponsive();
            };
            this.resizeNamespace = '.mdbReviewsSlider-' + this.sliderId;
            $(window).on('resize' + this.resizeNamespace, this._resizeHandler);

            // Keyboard navigation
            this.$element.on('keydown', function(e) {
                if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    self.prevSlide();
                } else if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    self.nextSlide();
                }
            });
        }

        /**
         * Go to next slide
         */
        nextSlide() {
            if (this.totalSlides <= 1) return;
            
            this.goToSlide((this.currentSlide + 1) % this.totalSlides);
        }

        /**
         * Go to previous slide
         */
        prevSlide() {
            if (this.totalSlides <= 1) return;
            
            this.goToSlide(this.currentSlide === 0 ? this.totalSlides - 1 : this.currentSlide - 1);
        }

        /**
         * Go to specific slide
         *
         * @param {number} slideIndex Target slide index
         */
        goToSlide(slideIndex) {
            if (slideIndex < 0 || slideIndex >= this.totalSlides || slideIndex === this.currentSlide) {
                return;
            }

            const $currentSlide = this.$slides.eq(this.currentSlide);
            const $nextSlide = this.$slides.eq(slideIndex);

            // Fade transition
            $currentSlide.removeClass('active').css('opacity', 0);
            $nextSlide.addClass('active').css('opacity', 1);

            this.currentSlide = slideIndex;
            this.updateNavigationButtons();
            this.notifyChange();

            // Debug logging
            if (window.MDBWidgets && window.MDBWidgets.debug) {
                console.log('Reviews slider changed to slide:', slideIndex);
            }
        }

        /**
         * Update navigation button states
         */
        updateNavigationButtons() {
            const $prevBtn = this.$element.find('.mdb-reviews-prev');
            const $nextBtn = this.$element.find('.mdb-reviews-next');

            // Enable/disable buttons based on current slide
            // Note: if looping is desired, remove the disable logic
            if ($prevBtn.length) {
                if (this.currentSlide === 0) {
                    $prevBtn.attr('aria-disabled', 'true').prop('disabled', true);
                } else {
                    $prevBtn.attr('aria-disabled', 'false').prop('disabled', false);
                }
            }

            if ($nextBtn.length) {
                if (this.currentSlide === this.totalSlides - 1) {
                    $nextBtn.attr('aria-disabled', 'true').prop('disabled', true);
                } else {
                    $nextBtn.attr('aria-disabled', 'false').prop('disabled', false);
                }
            }
        }

        /**
         * Start autoplay
         */
        startAutoplay() {
            if (!this.autoplay || this.totalSlides <= 1) return;

            this.isPlaying = true;
            this.autoplayTimer = setInterval(() => {
                this.nextSlide();
            }, this.autoplaySpeed);
        }

        /**
         * Pause autoplay
         */
        pauseAutoplay() {
            if (this.autoplayTimer) {
                clearInterval(this.autoplayTimer);
                this.autoplayTimer = null;
                this.isPlaying = false;
            }
        }

        /**
         * Resume autoplay
         */
        resumeAutoplay() {
            if (this.autoplay && !this.isPlaying && this.totalSlides > 1) {
                this.startAutoplay();
            }
        }

        /**
         * Handle responsive behavior
         */
        handleResponsive() {
            const columns = window.innerWidth <= 767 ? 1 : 2;
            const widgetEl = this.$element.get(0);
            if (widgetEl) {
                widgetEl.style.setProperty('--mdb-reviews-column-count', columns);
            }
        }

        /**
         * Notify navigation widgets of slider state
         */
        notifyChange() {
            $(document).trigger('mdb-reviews-slider-changed', {
                sliderId: this.sliderId,
                currentSlide: this.currentSlide,
                totalSlides: this.totalSlides
            });
        }

        /**
         * Notify navigation widgets that slider is ready
         */
        notifyReady() {
            $(document).trigger('mdb-reviews-slider-ready', {
                sliderId: this.sliderId,
                currentSlide: this.currentSlide,
                totalSlides: this.totalSlides
            });
        }

        /**
         * Destroy the slider instance
         */
        destroy() {
            this.pauseAutoplay();
            this.$element.off('mouseenter mouseleave keydown');
            $(document).off('mdb-reviews-navigate');
            if (this.resizeNamespace) {
                $(window).off('resize' + this.resizeNamespace, this._resizeHandler);
            }
            
            if (window.MDBWidgets && window.MDBWidgets.debug) {
                console.log('MDB Reviews Slider destroyed:', this.sliderId);
            }
        }
    }

    /**
     * Initialize reviews slider widgets
     */
    function initReviewsSliders() {
        $('.mdb-reviews-slider-widget').each(function() {
            const $widget = $(this);
            
            if (!$widget.data('mdb-reviews-slider')) {
                const slider = new MDBReviewsSlider($widget);
                $widget.data('mdb-reviews-slider', slider);
            }
        });
    }

    /**
     * Document ready initialization
     */
    $(document).ready(function() {
        initReviewsSliders();
    });

    /**
     * Elementor frontend initialization
     */
    $(window).on('elementor/frontend/init', function() {
        // Re-initialize after Elementor loads
        setTimeout(initReviewsSliders, 100);

        // Handle Elementor editor updates
        if (window.elementorFrontend && window.elementorFrontend.hooks) {
            elementorFrontend.hooks.addAction('frontend/element_ready/mdb-reviews-slider.default', function($scope) {
                const $widget = $scope.find('.mdb-reviews-slider-widget');
                if ($widget.length && !$widget.data('mdb-reviews-slider')) {
                    const slider = new MDBReviewsSlider($widget);
                    $widget.data('mdb-reviews-slider', slider);
                }
            });
        }
    });

    /**
     * Cleanup on page unload
     */
    $(window).on('beforeunload', function() {
        $('.mdb-reviews-slider-widget').each(function() {
            const slider = $(this).data('mdb-reviews-slider');
            if (slider && typeof slider.destroy === 'function') {
                slider.destroy();
            }
        });
    });

    // Expose class globally for advanced usage
    window.MDBReviewsSlider = MDBReviewsSlider;

})(jQuery);
