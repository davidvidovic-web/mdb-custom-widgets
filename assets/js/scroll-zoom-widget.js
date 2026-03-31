/* global gsap, ScrollTrigger */

( function () {
    'use strict';

    /**
     * Initialise a single scroll-zoom widget instance.
     *
     * @param {HTMLElement} el  The root .mdb-scroll-zoom element.
     */
    function initScrollZoom( el ) {
        const image        = el.querySelector( '.mdb-scroll-zoom__image' );
        if ( ! image ) return;

        const initialScale = parseFloat( el.dataset.initialScale ) || 0.1;
        const scrub        = parseFloat( el.dataset.scrub )        || 2;
        // travelY is how many px above its resting point the image starts.
        // Negative y = above, so we negate the positive value from the control.
        const travelY      = -( parseFloat( el.dataset.travelY ) || 0 );

        // Register the ScrollTrigger plugin (safe to call multiple times)
        gsap.registerPlugin( ScrollTrigger );

        // Place image at its starting position: small and above its resting spot
        gsap.set( image, { scale: initialScale, y: travelY } );

        // Animate scale AND y together so the image follows a downward path
        // as it grows into its final position.
        gsap.to( image, {
            scale: 1,
            y: 0,
            ease: 'none',
            scrollTrigger: {
                trigger: el,
                start: 'top 90%',
                end: 'center center',
                scrub: scrub,
                invalidateOnRefresh: true,
            },
        } );
    }

    /**
     * Initialise all widgets on the page.
     */
    function initAll() {
        document.querySelectorAll( '.mdb-scroll-zoom' ).forEach( initScrollZoom );
    }

    // ── Elementor frontend integration ──────────────────────────────────────
    // elementorFrontend.hooks is not available until after the 'elementor/frontend/init'
    // jQuery event fires, so we must wait for it before calling addAction.

    function registerElementorHook() {
        window.elementorFrontend.hooks.addAction(
            'frontend/element_ready/mdb-scroll-zoom.default',
            function ( $scope ) {
                const el = $scope[0].querySelector( '.mdb-scroll-zoom' );
                if ( el ) initScrollZoom( el );
            }
        );
    }

    if ( typeof window.elementorFrontend !== 'undefined' ) {
        if ( window.elementorFrontend.isEditMode() || typeof window.elementorFrontend.hooks === 'undefined' ) {
            // Hooks not ready yet — wait for Elementor's init event
            window.addEventListener( 'elementor/frontend/init', registerElementorHook );
        } else {
            registerElementorHook();
        }
    }

    // ── Standard page load (non-editor) ─────────────────────────────────────
    if ( document.readyState === 'loading' ) {
        document.addEventListener( 'DOMContentLoaded', initAll );
    } else {
        initAll();
    }

} )();
