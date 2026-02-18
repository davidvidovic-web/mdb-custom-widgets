/**
 * MDB Unique Features Slider Widget JS
 */
jQuery(window).on('elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction('frontend/element_ready/mdb-unique-features-slider.default', function($scope, $) {
        var $wrapper = $scope.find('.mdb-unique-features-slider-wrapper');
        var $swiperContainer = $scope.find('.mdb-unique-features-swiper');
        var settings = $wrapper.data('settings');
        
        if (!settings) {
            console.warn('MDB Unique Features Slider: No settings found');
            return;
        }

        // Build Swiper options
        var swiperOptions = {
            slidesPerView: settings.slidesPerView,
            spaceBetween: settings.spaceBetween,
            loop: settings.loop,
            speed: 600,
            autoHeight: false,
            grabCursor: true,
            watchSlidesProgress: true,
            watchSlidesVisibility: true,
            navigation: {
                nextEl: $scope.find('.mdb-unique-features-button-next')[0],
                prevEl: $scope.find('.mdb-unique-features-button-prev')[0],
            },
            breakpoints: settings.breakpoints,
            on: {
                init: function() {
                    console.log('Swiper initialized with', this.slides.length, 'slides');
                }
            }
        };
        
        if (settings.autoplay) {
            swiperOptions.autoplay = {
                delay: settings.autoplaySpeed,
                disableOnInteraction: false,
                pauseOnMouseEnter: true
            };
        }
        
        // Initialize Swiper
        var swiper = new Swiper($swiperContainer[0], swiperOptions);
    });
});
