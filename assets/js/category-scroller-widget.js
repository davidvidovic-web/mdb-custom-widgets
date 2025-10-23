/**
 * MDB Category Scroller Widget JavaScript
 * 
 * @package MDB Custom Widgets
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * MDB Category Scroller Class
     */
    class MDBCategoryScroller {
        constructor(element, options = {}) {
            this.element = element;
            this.$element = $(element);
            this.options = {
                itemsPerView: 3,
                itemsPerViewTablet: 3,
                itemsPerViewMobile: 2,
                spaceBetween: 20,
                autoplay: false,
                autoplaySpeed: 3000,
                loop: true,
                ...options
            };

            this.currentIndex = 0;
            this.totalItems = 0;
            this.itemsPerView = this.options.itemsPerView;
            this.autoplayInterval = null;
            this.isInitialized = false;

            this.init();
        }

        /**
         * Initialize the category scroller
         */
        init() {
            try {
                this.setupElements();
                this.calculateDimensions();
                this.bindEvents();
                this.updateButtons();
                this.startAutoplay();
                this.isInitialized = true;

                // Force layout recalculation after initialization
                setTimeout(() => {
                    this.calculateDimensions();
                    this.updatePosition();
                }, 100);

                // Trigger custom event
                this.$element.trigger('mdb:categoryScrollerInit', {
                    scroller: this,
                    element: this.element
                });
            } catch (error) {
                console.error('MDB Category Scroller: Initialization error:', error);
            }
        }

        /**
         * Setup DOM elements
         */
        setupElements() {
            this.$wrapper = this.$element.find('.mdb-category-scroller-wrapper');
            this.$slides = this.$element.find('.mdb-category-slide');
            this.$prevBtn = this.$element.find('.mdb-category-prev');
            this.$nextBtn = this.$element.find('.mdb-category-next');

            this.totalItems = this.$slides.length;

            if (this.totalItems === 0) {
                console.warn('MDB Category Scroller: No slides found');
                return;
            }

            // Ensure wrapper has flex display
            this.$wrapper.css({
                'display': 'flex',
                'flex-wrap': 'nowrap',
                'align-items': 'stretch'
            });

            // Add data attribute for CSS targeting
            this.$element.attr('data-total-items', this.totalItems);
        }

        /**
         * Calculate responsive dimensions
         */
        calculateDimensions() {
            const windowWidth = $(window).width();
            
            if (windowWidth <= 480) {
                this.itemsPerView = 1;
            } else if (windowWidth <= 767) {
                this.itemsPerView = 2; // Force 2 items on mobile
            } else {
                this.itemsPerView = 3; // Force exactly 3 items on tablet and desktop
            }

            // Ensure we don't exceed total items
            this.itemsPerView = Math.min(this.itemsPerView, this.totalItems);

            // Update CSS custom properties for responsive behavior
            this.updateSlideWidths();
        }

        /**
         * Update slide widths based on items per view
         */
        updateSlideWidths() {
            if (this.totalItems === 0) return;

            const slideWidth = (100 / this.itemsPerView);
            
            // Get gap from CSS custom property, fallback to 30px
            const computedStyle = getComputedStyle(document.documentElement);
            const gap = parseInt(computedStyle.getPropertyValue('--mdb-category-gap')) || 30;

            this.$slides.each((index, slide) => {
                $(slide).css({
                    'width': `calc(${slideWidth}% - ${gap * (this.itemsPerView - 1) / this.itemsPerView}px)`,
                    'flex': '0 0 auto',
                    'box-sizing': 'border-box'
                });
            });

            // Ensure wrapper maintains flex layout with consistent gap
            this.$wrapper.css({
                'display': 'flex',
                'gap': gap + 'px',
                'padding': '0 var(--mdb-category-padding, 15px)'
            });
        }

        /**
         * Bind event listeners
         */
        bindEvents() {
            // Navigation buttons
            this.$prevBtn.on('click.mdb-category-scroller', () => this.prev());
            this.$nextBtn.on('click.mdb-category-scroller', () => this.next());

            // Window resize
            $(window).on('resize.mdb-category-scroller', this.debounce(() => {
                this.calculateDimensions();
                this.updatePosition();
                this.updateButtons();
            }, 250));

            // Touch events for mobile swipe
            if ('ontouchstart' in window) {
                this.bindTouchEvents();
            }

            // Pause autoplay on hover
            this.$element.on('mouseenter.mdb-category-scroller', () => {
                if (this.autoplayInterval) {
                    this.pauseAutoplay();
                }
            });

            this.$element.on('mouseleave.mdb-category-scroller', () => {
                if (this.options.autoplay) {
                    this.startAutoplay();
                }
            });

            // Keyboard navigation
            this.$element.on('keydown.mdb-category-scroller', (e) => {
                if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    this.prev();
                } else if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    this.next();
                }
            });
        }

        /**
         * Bind touch events for mobile swipe
         */
        bindTouchEvents() {
            let startX = 0;
            let currentX = 0;
            let isDragging = false;

            this.$wrapper.on('touchstart.mdb-category-scroller', (e) => {
                startX = e.touches[0].clientX;
                isDragging = true;
                this.$wrapper.css('transition', 'none');
            });

            this.$wrapper.on('touchmove.mdb-category-scroller', (e) => {
                if (!isDragging) return;
                
                currentX = e.touches[0].clientX;
                const diffX = startX - currentX;
                const currentTransform = this.getCurrentTransform();
                
                this.$wrapper.css('transform', `translateX(${currentTransform - diffX}px)`);
            });

            this.$wrapper.on('touchend.mdb-category-scroller', (e) => {
                if (!isDragging) return;
                
                isDragging = false;
                this.$wrapper.css('transition', 'transform 0.3s ease');
                
                const endX = e.changedTouches[0].clientX;
                const diffX = startX - endX;
                
                if (Math.abs(diffX) > 50) { // Minimum swipe distance
                    if (diffX > 0) {
                        this.next();
                    } else {
                        this.prev();
                    }
                } else {
                    this.updatePosition(); // Snap back to current position
                }
            });
        }

        /**
         * Get current transform value
         */
        getCurrentTransform() {
            const transform = this.$wrapper.css('transform');
            if (transform === 'none') return 0;
            
            const matrix = transform.match(/matrix\((.+)\)/);
            if (matrix) {
                const values = matrix[1].split(', ');
                return parseFloat(values[4]) || 0;
            }
            return 0;
        }

        /**
         * Navigate to previous items
         */
        prev() {
            if (this.currentIndex > 0) {
                this.currentIndex--;
            } else if (this.options.loop) {
                this.currentIndex = this.getMaxIndex();
            }
            
            this.updatePosition();
            this.updateButtons();
            this.restartAutoplay();

            // Trigger custom event
            this.$element.trigger('mdb:categoryScrollerChange', {
                currentIndex: this.currentIndex,
                direction: 'prev',
                scroller: this
            });
        }

        /**
         * Navigate to next items
         */
        next() {
            const maxIndex = this.getMaxIndex();
            
            if (this.currentIndex < maxIndex) {
                this.currentIndex++;
            } else if (this.options.loop) {
                this.currentIndex = 0;
            }
            
            this.updatePosition();
            this.updateButtons();
            this.restartAutoplay();

            // Trigger custom event
            this.$element.trigger('mdb:categoryScrollerChange', {
                currentIndex: this.currentIndex,
                direction: 'next',
                scroller: this
            });
        }

        /**
         * Get maximum index based on items per view
         */
        getMaxIndex() {
            return Math.max(0, this.totalItems - this.itemsPerView);
        }

        /**
         * Update wrapper position
         */
        updatePosition() {
            if (this.totalItems === 0) return;

            const slideWidth = this.$slides.first().outerWidth(true);
            const translateX = -(this.currentIndex * slideWidth);
            
            this.$wrapper.css('transform', `translateX(${translateX}px)`);
        }

        /**
         * Update navigation buttons state
         */
        updateButtons() {
            const maxIndex = this.getMaxIndex();
            
            // Previous button
            if (this.options.loop || this.currentIndex > 0) {
                this.$prevBtn.prop('disabled', false).attr('aria-disabled', 'false');
            } else {
                this.$prevBtn.prop('disabled', true).attr('aria-disabled', 'true');
            }
            
            // Next button
            if (this.options.loop || this.currentIndex < maxIndex) {
                this.$nextBtn.prop('disabled', false).attr('aria-disabled', 'false');
            } else {
                this.$nextBtn.prop('disabled', true).attr('aria-disabled', 'true');
            }
        }

        /**
         * Start autoplay
         */
        startAutoplay() {
            if (!this.options.autoplay || this.totalItems <= this.itemsPerView) return;

            this.pauseAutoplay(); // Clear any existing interval
            
            this.autoplayInterval = setInterval(() => {
                if (this.currentIndex < this.getMaxIndex()) {
                    this.next();
                } else if (this.options.loop) {
                    this.currentIndex = 0;
                    this.updatePosition();
                    this.updateButtons();
                } else {
                    this.pauseAutoplay();
                }
            }, this.options.autoplaySpeed);
        }

        /**
         * Pause autoplay
         */
        pauseAutoplay() {
            if (this.autoplayInterval) {
                clearInterval(this.autoplayInterval);
                this.autoplayInterval = null;
            }
        }

        /**
         * Restart autoplay
         */
        restartAutoplay() {
            if (this.options.autoplay) {
                this.pauseAutoplay();
                this.startAutoplay();
            }
        }

        /**
         * Debounce utility function
         */
        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        /**
         * Destroy the scroller and clean up
         */
        destroy() {
            this.pauseAutoplay();
            
            // Remove event listeners
            this.$element.off('.mdb-category-scroller');
            this.$prevBtn.off('.mdb-category-scroller');
            this.$nextBtn.off('.mdb-category-scroller');
            $(window).off('.mdb-category-scroller');
            
            // Reset transforms
            this.$wrapper.css('transform', '');
            
            this.isInitialized = false;
        }

        /**
         * Refresh/reinitialize scroller
         */
        refresh() {
            if (this.isInitialized) {
                this.destroy();
            }
            this.init();
        }
    }

    /**
     * Initialize category scrollers when document is ready
     */
    $(document).ready(function() {
        // Initialize existing category scrollers
        $('.mdb-category-scroller-widget').each(function() {
            const $widget = $(this);
            const settings = $widget.data('settings') || {};
            
            if (!$widget.data('mdb-category-scroller')) {
                const scroller = new MDBCategoryScroller(this, settings);
                $widget.data('mdb-category-scroller', scroller);
                
                // Force a layout update after initialization
                setTimeout(() => {
                    if (scroller.isInitialized) {
                        scroller.calculateDimensions();
                        scroller.updatePosition();
                    }
                }, 100);
            }
        });
    });

    /**
     * Initialize category scrollers for Elementor editor
     */
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/mdb-category-scroller.default', function($scope) {
            const $widget = $scope.find('.mdb-category-scroller-widget');
            if ($widget.length) {
                const settings = $widget.data('settings') || {};
                
                // Destroy existing instance if it exists
                const existingScroller = $widget.data('mdb-category-scroller');
                if (existingScroller) {
                    existingScroller.destroy();
                }
                
                // Create new instance
                const scroller = new MDBCategoryScroller($widget[0], settings);
                $widget.data('mdb-category-scroller', scroller);
            }
        });
    });

    /**
     * Expose to global scope for external access
     */
    window.MDBCategoryScroller = MDBCategoryScroller;

})(jQuery);