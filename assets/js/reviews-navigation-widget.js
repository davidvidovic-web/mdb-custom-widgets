/**
 * MDB Reviews Navigation Widget JavaScript
 * 
 * Handles navigation functionality for MDB Reviews Slider Widget
 *
 * @package MDB Custom Widgets  
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * MDB Reviews Navigation Class
     */
    class MDBReviewsNavigation {
        
        /**
         * Constructor
         *
         * @param {jQuery} $element Widget element
         */
        constructor($element) {
            this.$element = $element;
            this.$widget = $element.closest('.elementor-widget');
            this.targetSlider = $element.data('target') || 'mdb-reviews-slider';
            
            this.init();
        }

        /**
         * Initialize the navigation
         */
        init() {
            this.bindEvents();
            this.setupCommunication();
            
            // Debug logging
            if (window.MDBWidgets && window.MDBWidgets.debug) {
                console.log('MDB Reviews Navigation initialized for target:', this.targetSlider);
            }
        }

        /**
         * Bind navigation events
         */
        bindEvents() {
            const self = this;

            // Previous button click
            this.$element.on('click', '.mdb-reviews-prev', function(e) {
                e.preventDefault();
                self.navigateSlider('prev');
            });

            // Next button click  
            this.$element.on('click', '.mdb-reviews-next', function(e) {
                e.preventDefault();
                self.navigateSlider('next');
            });

            // Keyboard navigation
            this.$element.on('keydown', '.mdb-reviews-nav-btn', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    $(this).click();
                }
            });
        }

        /**
         * Set up communication with slider
         */
        setupCommunication() {
            const self = this;

            // Listen for slider state changes
            $(document).on('mdb-reviews-slider-changed', function(e, data) {
                if (data.sliderId === self.targetSlider) {
                    self.updateButtonStates(data);
                }
            });

            // Listen for slider initialization
            $(document).on('mdb-reviews-slider-ready', function(e, data) {
                if (data.sliderId === self.targetSlider) {
                    self.updateButtonStates(data);
                }
            });
        }

        /**
         * Navigate the target slider
         *
         * @param {string} direction 'prev' or 'next'
         */
        navigateSlider(direction) {
            // Trigger navigation event for target slider
            $(document).trigger('mdb-reviews-navigate', {
                sliderId: this.targetSlider,
                direction: direction,
                source: 'navigation-widget'
            });

            // Add visual feedback
            this.addClickFeedback(direction);

            // Debug logging
            if (window.MDBWidgets && window.MDBWidgets.debug) {
                console.log(`Navigation ${direction} triggered for slider:`, this.targetSlider);
            }
        }

        /**
         * Update button states based on slider state
         *
         * @param {Object} data Slider state data
         */
        updateButtonStates(data) {
            const $prevBtn = this.$element.find('.mdb-reviews-prev');
            const $nextBtn = this.$element.find('.mdb-reviews-next');

            // Update previous button
            if (data.currentSlide <= 0) {
                $prevBtn.prop('disabled', true).attr('aria-disabled', 'true');
            } else {
                $prevBtn.prop('disabled', false).attr('aria-disabled', 'false');
            }

            // Update next button
            if (data.currentSlide >= data.totalSlides - 1) {
                $nextBtn.prop('disabled', true).attr('aria-disabled', 'true');
            } else {
                $nextBtn.prop('disabled', false).attr('aria-disabled', 'false');
            }

            // Update aria-labels with current position
            $prevBtn.attr('aria-label', `Previous Reviews (Current: ${data.currentSlide + 1} of ${data.totalSlides})`);
            $nextBtn.attr('aria-label', `Next Reviews (Current: ${data.currentSlide + 1} of ${data.totalSlides})`);
        }

        /**
         * Add visual click feedback
         *
         * @param {string} direction Button direction
         */
        addClickFeedback(direction) {
            const $button = this.$element.find(`.mdb-reviews-${direction}`);
            
            $button.addClass('mdb-nav-clicked');
            
            setTimeout(() => {
                $button.removeClass('mdb-nav-clicked');
            }, 150);
        }

        /**
         * Destroy the navigation instance
         */
        destroy() {
            this.$element.off('click keydown');
            $(document).off('mdb-reviews-slider-changed mdb-reviews-slider-ready');
            
            if (window.MDBWidgets && window.MDBWidgets.debug) {
                console.log('MDB Reviews Navigation destroyed for target:', this.targetSlider);
            }
        }
    }

    /**
     * Initialize navigation widgets
     */
    function initReviewsNavigation() {
        $('.mdb-reviews-navigation-widget').each(function() {
            const $widget = $(this);
            
            if (!$widget.data('mdb-reviews-navigation')) {
                const navigation = new MDBReviewsNavigation($widget);
                $widget.data('mdb-reviews-navigation', navigation);
            }
        });
    }

    /**
     * Document ready initialization
     */
    $(document).ready(function() {
        initReviewsNavigation();
    });

    /**
     * Elementor frontend initialization
     */
    $(window).on('elementor/frontend/init', function() {
        // Re-initialize after Elementor loads
        setTimeout(initReviewsNavigation, 100);

        // Handle Elementor editor updates
        if (window.elementorFrontend && window.elementorFrontend.hooks) {
            elementorFrontend.hooks.addAction('frontend/element_ready/mdb-reviews-navigation.default', function($scope) {
                const $widget = $scope.find('.mdb-reviews-navigation-widget');
                if ($widget.length && !$widget.data('mdb-reviews-navigation')) {
                    const navigation = new MDBReviewsNavigation($widget);
                    $widget.data('mdb-reviews-navigation', navigation);
                }
            });
        }
    });

    /**
     * Cleanup on page unload
     */
    $(window).on('beforeunload', function() {
        $('.mdb-reviews-navigation-widget').each(function() {
            const navigation = $(this).data('mdb-reviews-navigation');
            if (navigation && typeof navigation.destroy === 'function') {
                navigation.destroy();
            }
        });
    });

    // Expose class globally for advanced usage
    window.MDBReviewsNavigation = MDBReviewsNavigation;

})(jQuery);
