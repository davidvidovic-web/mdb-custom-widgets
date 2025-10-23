/**
 * MDB Custom Widgets Scripts
 * 
 * @package MDB Custom Widgets
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * MDB Widgets Handler
     */
    var MDBWidgets = {
        
        /**
         * Initialize widgets
         */
        init: function() {
            this.bindEvents();
            this.initializeWidgets();
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            // Re-initialize widgets when Elementor finishes loading
            if (typeof window.elementorFrontend !== 'undefined') {
                $(window).on('elementor/frontend/init', function() {
                    MDBWidgets.initializeWidgets();
                });
            }

            // Handle AJAX loading states
            $(document).ajaxStart(function() {
                $('.mdb-widget').addClass('mdb-loading-state');
            }).ajaxStop(function() {
                $('.mdb-widget').removeClass('mdb-loading-state');
            });
        },

        /**
         * Initialize all MDB widgets
         */
        initializeWidgets: function() {
            $('.mdb-widget').each(function() {
                var $widget = $(this);
                var widgetType = $widget.data('widget-type');
                
                if (typeof MDBWidgets[widgetType] === 'function') {
                    MDBWidgets[widgetType]($widget);
                }
            });
        },

        /**
         * Utility function to handle responsive behavior
         */
        handleResponsive: function($element) {
            var $window = $(window);
            
            function checkViewport() {
                var windowWidth = $window.width();
                
                if (windowWidth <= 767) {
                    $element.addClass('mdb-mobile-view').removeClass('mdb-tablet-view mdb-desktop-view');
                } else if (windowWidth <= 1024) {
                    $element.addClass('mdb-tablet-view').removeClass('mdb-mobile-view mdb-desktop-view');
                } else {
                    $element.addClass('mdb-desktop-view').removeClass('mdb-mobile-view mdb-tablet-view');
                }
            }
            
            checkViewport();
            $window.on('resize', checkViewport);
        },

        /**
         * Utility function for smooth scrolling
         */
        smoothScroll: function(target, offset) {
            offset = offset || 0;
            
            $('html, body').animate({
                scrollTop: $(target).offset().top - offset
            }, 800);
        },

        /**
         * Utility function to handle loading states
         */
        showLoading: function($element, message) {
            message = message || 'Loading...';
            $element.html('<div class="mdb-loading">' + message + '</div>');
        },

        /**
         * Utility function to show errors
         */
        showError: function($element, message) {
            message = message || 'An error occurred. Please try again.';
            $element.html('<div class="mdb-error">' + message + '</div>');
        },

        /**
         * Debounce function
         */
        debounce: function(func, wait, immediate) {
            var timeout;
            return function() {
                var context = this;
                var args = arguments;
                var later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                var callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        }
    };

    // Initialize when DOM is ready
    $(document).ready(function() {
        MDBWidgets.init();
    });

    // Make MDBWidgets globally accessible
    window.MDBWidgets = MDBWidgets;

})(jQuery);