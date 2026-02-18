/**
 * MDB Simple Reviews Widget JS
 */
jQuery(window).on('elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction('frontend/element_ready/mdb-simple-reviews.default', function($scope, $) {
        var $wrapper = $scope.find('.mdb-simple-reviews-slider-wrapper');
        var $swiperContainer = $scope.find('.mdb-simple-reviews-swiper');
        var settings = $wrapper.data('settings');
        
        if (!settings) {
            return;
        }
        
        var swiperOptions = {
            slidesPerView: settings.slidesPerView,
            spaceBetween: settings.spaceBetween,
            loop: settings.loop,
            speed: 600,
            autoHeight: false, // Ensure equal height flex cards work
            grabCursor: true,
            pagination: settings.pagination ? {
                el: $scope.find('.swiper-pagination')[0],
                clickable: true,
            } : false,
            navigation: settings.navigation ? {
                nextEl: $scope.find('.mdb-swiper-button-next')[0],
                prevEl: $scope.find('.mdb-swiper-button-prev')[0],
            } : false,
            breakpoints: settings.breakpoints
        };
        
        if (settings.autoplay) {
            swiperOptions.autoplay = {
                delay: settings.autoplaySpeed,
                disableOnInteraction: false,
                pauseOnMouseEnter: true
            };
        }
        
        // Initialize Swiper
        const swiper = new Swiper($swiperContainer[0], swiperOptions);
    });
});
