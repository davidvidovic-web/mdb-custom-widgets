/**
 * MDB Feature Grid Widget JS
 * Hover effects are CSS-driven; this file provides the Elementor frontend hook.
 */
( function ( $ ) {
	'use strict';

	var MDBFeatureGridHandler = function ( $scope ) {
		// No JS required — all transitions are handled via CSS.
		// Extend this function for any future JS-driven enhancements.
	};

	$( window ).on( 'elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/mdb-feature-grid.default',
			MDBFeatureGridHandler
		);
	} );
} )( jQuery );
