(function ( $ ) {
	$(function () {
		
		if ( $( '#efbl_enabe_if_login' ).is(":checked") ) {
			$('#efbl_enabe_if_not_login').removeAttr("checked"); 
			$('#efbl_enabe_if_not_login').attr("disabled", true);
		}else if ( $( '#efbl_enabe_if_login' ).is(":checked") ) {
			$('#efbl_enabe_if_login').removeAttr("checked"); 
			$('#efbl_enabe_if_login').attr("disabled", true);
		}
		
		$('#efbl_enabe_if_login').click(function (){
		 
			 if ( $( this ).is(":checked")) {
					$('#efbl_enabe_if_not_login').removeAttr("checked"); 
					$('#efbl_enabe_if_not_login').attr("disabled", true);
					
 			  } else {
 				   $('#efbl_enabe_if_not_login').removeAttr("disabled"); 
 					
			}
			 
		});
		
		$('#efbl_enabe_if_not_login').click(function (){
		 
			 if ( $( this ).is(":checked")) {
					$('#efbl_enabe_if_login').removeAttr("checked"); 
					$('#efbl_enabe_if_login').attr("disabled", true);
					
 			  } else {
 				   $('#efbl_enabe_if_login').removeAttr("disabled"); 
 					
			}
			 
		});
		

	});

}(jQuery));