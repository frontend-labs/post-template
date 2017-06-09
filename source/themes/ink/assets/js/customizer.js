/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Accent color
	wp.customize( 'accent', function( value ) {
		value.bind( function( to ) {
			$( 'a' ).not('.post-content a, .site-header a').css( 'color', to );
		} );
	} );

	// Background Color
	wp.customize( 'background', function( value ) {
		value.bind( function( to ) {
			$( 'body, #page' ).css( 'background-color', to );
		} );
	} );

	// Footer copyright text
	wp.customize( 'copyright', function( value ) {
		value.bind( function( to ) {
			$( '.site-info' ).html( to );
		} );
	} );

	// Site layout option
	wp.customize( 'layout', function( value ) {
		value.bind( function( to ) {
			$('body').attr('data-layout', to);
			$(window).trigger('resize');
		} );
	} );

	// Toggle Author title visibility
	wp.customize( 'hide_author_title', function( value ) {
		value.bind( function( to ) {
			if( to === true ) {
				$('.byline').hide();
			}else if( to === false ) {
				$('.byline').show();
			}
		} );
	} );

	// Toggle Author title visibility
	wp.customize( 'share_buttons', function( value ) {
		value.bind( function( to ) {
			if( to === true ) {
				$('.post-share-buttons').hide();
			}else if( to === false ) {
				$('.post-share-buttons').show();
			}
		} );
	} );

} )( jQuery );
