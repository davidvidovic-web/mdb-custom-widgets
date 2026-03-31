jQuery(window).on('elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction('frontend/element_ready/mdb-measurement-guide.default', function($scope, $) {

        /**
         * Convert a public YouTube / Vimeo URL to an embeddable URL.
         * Returns null if the URL is not recognised.
         */
        function getEmbedUrl(url) {
            if (!url || url === '#') return null;

            // YouTube: watch?v= or youtu.be/
            var ytMatch = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
            if (ytMatch) {
                return 'https://www.youtube.com/embed/' + ytMatch[1] + '?autoplay=1&rel=0';
            }

            // Vimeo: vimeo.com/ID
            var vimeoMatch = url.match(/vimeo\.com\/(\d+)/);
            if (vimeoMatch) {
                return 'https://player.vimeo.com/video/' + vimeoMatch[1] + '?autoplay=1';
            }

            // Fallback: return url as-is (e.g. direct .mp4 link — won't iframe-embed nicely but won't break)
            return url;
        }

        function openLightbox(url) {
            var embedUrl = getEmbedUrl(url);
            if (!embedUrl) return;

            var $lightbox = $(
                '<div class="mdb-mg-lightbox" role="dialog" aria-modal="true" aria-label="Video">' +
                    '<div class="mdb-mg-lightbox-overlay"></div>' +
                    '<div class="mdb-mg-lightbox-content">' +
                        '<button class="mdb-mg-lightbox-close" aria-label="Close video">&times;</button>' +
                        '<div class="mdb-mg-lightbox-iframe-wrap">' +
                            '<iframe src="' + embedUrl + '" frameborder="0" allowfullscreen ' +
                                'allow="autoplay; encrypted-media; picture-in-picture"></iframe>' +
                        '</div>' +
                    '</div>' +
                '</div>'
            );

            $('body').append($lightbox).addClass('mdb-mg-lightbox-open');

            // Trigger transition on next frame
            requestAnimationFrame(function() {
                $lightbox.addClass('is-active');
            });

            function closeLightbox() {
                $lightbox.removeClass('is-active');
                $('body').removeClass('mdb-mg-lightbox-open');
                // Remove iframe src immediately to stop playback, then remove element
                $lightbox.find('iframe').attr('src', '');
                setTimeout(function() { $lightbox.remove(); }, 320);
                $(document).off('keydown.mdb-mg-lightbox');
            }

            $lightbox.find('.mdb-mg-lightbox-close, .mdb-mg-lightbox-overlay').on('click', closeLightbox);

            $(document).on('keydown.mdb-mg-lightbox', function(e) {
                if (e.key === 'Escape') { closeLightbox(); }
            });
        }

        $scope.find('.mdb-mg-video-card').on('click', function(e) {
            e.preventDefault();
            openLightbox($(this).data('video-url'));
        });
    });
});
