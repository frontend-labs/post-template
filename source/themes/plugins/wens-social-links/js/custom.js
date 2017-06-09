jQuery(function() {
		jQuery( '.wen-side-socials ul.wen-social-links li a' ).tooltip({
			position: {
				my: "center bottom-5",
				at: "center top",
				using: function( position, feedback ) {
					jQuery( this ).css( position );
					jQuery( "<div class='arrow-wrap'>" )
						.addClass( "arrow" )
						.addClass( feedback.vertical )
						.addClass( feedback.horizontal )
						.appendTo( this );
				}
			}
		});
	});