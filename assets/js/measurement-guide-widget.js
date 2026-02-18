jQuery(window).on('elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction('frontend/element_ready/mdb-measurement-guide.default', function($scope, $) {
        // Measurement Guide Widget JS
        console.log('MDB Measurement Guide Widget Loaded');

        // Optional: Handle video clicks if not using standard links
        // $scope.find('.mdb-mg-video-card').on('click', function(e) {
        //     // Custom click handler
        // });
    });
});
