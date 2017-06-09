jQuery(document).ready(function($) {

	var previewDiv = $('#customize-preview');

	previewDiv.prepend('<div id="stag-loading"><i class="dashicons dashicons-update"></i></div>');
	var loadingDiv = $('#customize-preview #stag-loading');

	setInterval(function(){
		if( previewDiv.children('iframe').length > 1 ) {
			loadingDiv.fadeIn('fast');
		} else{
			loadingDiv.fadeOut('fast');
		}
	}, 100);
});
