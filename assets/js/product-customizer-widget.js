/* =============================================================================
   MDB Product Customizer Widget – Frontend JS
   ============================================================================= */

( function ( $ ) {
    'use strict';

    /**
     * Initialise a single Product Customizer widget instance.
     *
     * @param {HTMLElement} widgetEl  The .mdb-pc-widget root element.
     */
    function initProductCustomizer( widgetEl ) {
        var $widget  = $( widgetEl );
        var $cards   = $widget.find( '.mdb-pc-card' );
        var $btn     = $widget.find( '.mdb-pc-btn' );
        var baseUrl  = $btn.data( 'base-url' ) || '';

        if ( ! $cards.length ) {
            return;
        }

        // ------------------------------------------------------------------
        // Card selection
        // ------------------------------------------------------------------
        $cards.on( 'click keydown', function ( e ) {
            // Allow keyboard activation via Enter / Space
            if ( e.type === 'keydown' && e.which !== 13 && e.which !== 32 ) {
                return;
            }
            if ( e.type === 'keydown' ) {
                e.preventDefault();
            }

            var $card = $( this );

            // Update selected state
            $cards
                .removeClass( 'is-selected' )
                .attr( 'aria-pressed', 'false' );

            $card
                .addClass( 'is-selected' )
                .attr( 'aria-pressed', 'true' );

            // Activate button
            $btn.addClass( 'has-selection' );

            // Update button href if no explicit base URL was set
            var productUrl = $card.data( 'product-url' );
            if ( ! baseUrl && productUrl && productUrl !== '#' ) {
                $btn.attr( 'href', productUrl );
            } else if ( baseUrl ) {
                // If a base URL exists you may append a query param, e.g.:
                // ?product=<name>
                var productName = encodeURIComponent( $card.data( 'product-name' ) || '' );
                $btn.attr( 'href', baseUrl + ( baseUrl.indexOf( '?' ) !== -1 ? '&' : '?' ) + 'product=' + productName );
            }
        } );

        // ------------------------------------------------------------------
        // Ensure first card is pre-selected visually on page load
        // ------------------------------------------------------------------
        var $firstSelected = $cards.filter( '.is-selected' ).first();
        if ( $firstSelected.length ) {
            var firstUrl = $firstSelected.data( 'product-url' );
            if ( ! baseUrl && firstUrl && firstUrl !== '#' ) {
                $btn.attr( 'href', firstUrl );
            }
            $btn.addClass( 'has-selection' );
        }
    }

    // -----------------------------------------------------------------------
    // Elementor frontend hook – runs for both initial load and AJAX updates
    // -----------------------------------------------------------------------
    function onElementorWidgetReady( $scope ) {
        var $widget = $scope.find( '.mdb-pc-widget' );
        if ( $widget.length ) {
            initProductCustomizer( $widget[0] );
        }
    }

    // Elementor frontend events
    $( window ).on( 'elementor/frontend/init', function () {
        if ( typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks ) {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/mdb-product-customizer.default',
                onElementorWidgetReady
            );
        }
    } );

    // Vanilla DOM fallback (non-Elementor pages / shortcode usage)
    $( document ).ready( function () {
        if ( typeof elementorFrontend === 'undefined' ) {
            $( '.mdb-pc-widget' ).each( function () {
                initProductCustomizer( this );
            } );
        }
    } );

} )( jQuery );
