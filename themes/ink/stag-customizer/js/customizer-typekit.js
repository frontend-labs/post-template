(function($) {
	var StagTypekit = {
		cache: {},

		init: function() {
			// Cache Elements
			StagTypekit.cache.$textInput = $('input', '#customize-control-stag-typekit-id');
			StagTypekit.cache.$wrapper = $('#accordion-section-typography');
			StagTypekit.cache.$descriptionText = $('p', '#customize-control-stag-typekit-load-fonts');
			StagTypekit.cache.$reset = $('a:nth-child(1)', StagTypekit.cache.$descriptionText);
			StagTypekit.cache.$load = $('a:nth-child(2)', StagTypekit.cache.$descriptionText);

			StagTypekit.cache.$headerFontSelect = $('select', '#customize-control-header_font');
			StagTypekit.cache.$bodyFontSelect = $('select', '#customize-control-body_font');

			// Denote which items are Typekit fonts
			StagTypekit.markTypekitChoices();

			// Add classes to elements
			StagTypekit.cache.$reset.addClass('button reset-fonts');
			StagTypekit.cache.$load.addClass('button load-fonts');

			StagTypekit.cache.$wrapper.on('click', '.load-fonts', function(evt) {
				evt.preventDefault();

				// Add the loading status
				StagTypekit.showSpinner();

				// Remove errors
				StagTypekit.removeErrors();

				if ('' !== StagTypekit.cache.$textInput.val()) {
					StagTypekit.makeRequest(StagTypekit.cache.$textInput.val(), StagTypekitData.nonce);
				} else {
					StagTypekit.addError(StagTypekitData.noInputError);
					StagTypekit.hideSpinner();
				}
			});

			StagTypekit.cache.$wrapper.on('click', '.reset-fonts', function(evt) {
				evt.preventDefault();
				StagTypekit.reset();
				StagTypekit.removeErrors();
				StagTypekit.hideSpinner();
			});
		},

		markTypekitChoices: function() {
			_.each(StagTypekitData.typekitChoices, function(value){
				$('option[value="' + value +'"]', StagTypekit.cache.$headerFontSelect).addClass('stag-typekit-choice');
				$('option[value="' + value +'"]', StagTypekit.cache.$bodyFontSelect).addClass('stag-typekit-choice');
			});

			// Mark the header as a choice
			if (StagTypekitData.typekitChoices.length > 0) {
				$('.stag-typekit-choice', StagTypekit.cache.$headerFontSelect).first().prev().addClass('stag-typekit-choice');
				$('.stag-typekit-choice', StagTypekit.cache.$bodyFontSelect).first().prev().addClass('stag-typekit-choice');
			}
		},

		makeRequest: function(id, nonce) {
			wp.ajax.send(
				'stag_get_typekit_fonts', {
					success: StagTypekit.handleSuccess,
					error: StagTypekit.handleError,
					data: {
						nonce: nonce,
						id: id
					}
				}
			);
		},

		handleSuccess: function(data) {
			var optionsHTML = StagTypekit.buildOption(0, '&mdash; ' + StagTypekitData.headerLabel + ' &mdash;', true),
				headerVal = StagTypekit.cache.$headerFontSelect.val(),
				bodyVal = StagTypekit.cache.$bodyFontSelect.val();

			$.each(data, function(index, value){
				optionsHTML += StagTypekit.buildOption(index, value, false);
			});

			// Remove the previous fonts
			StagTypekit.removeFonts();

			// Prepend the new options
			StagTypekit.prependFonts(optionsHTML);

			StagTypekit.addSuccess(StagTypekitData.ajaxSuccess);

			// Set the correct current vals
			StagTypekit.cache.$headerFontSelect.val(headerVal);
			StagTypekit.cache.$bodyFontSelect.val(bodyVal);

			// Remove the loading indicator
			StagTypekit.hideSpinner();
		},

		buildOption: function(index, value, disabled) {
			disabled = (true === disabled) ? ' disabled="disabled"' : '';
			return '<option value="' + index + '" class="stag-typekit-choice"' + disabled + '>' + value + '</option>';
		},

		prependFonts: function(optionsHTML) {
			StagTypekit.cache.$headerFontSelect.prepend(optionsHTML);
			StagTypekit.cache.$bodyFontSelect.prepend(optionsHTML);
		},

		removeFonts: function() {
			$('.stag-typekit-choice', StagTypekit.cache.$headerFontSelect).remove();
			$('.stag-typekit-choice', StagTypekit.cache.$bodyFontSelect).remove();
		},

		handleError: function() {
			StagTypekit.addError(StagTypekitData.ajaxError);
			StagTypekit.hideSpinner();
		},

		showSpinner: function() {
			StagTypekit.cache.$descriptionText.append('<span class="spinner"></span>');
		},

		hideSpinner: function() {
			$('.spinner', StagTypekit.cache.$descriptionText).remove();
		},

		addError: function(message) {
			StagTypekit.removeErrors();
			StagTypekit.cache.$descriptionText.prepend('<span class="error">' + message + '<br /></span>');
		},

		addSuccess: function(message) {
			StagTypekit.cache.$descriptionText.prepend('<span class="success">' + message + '<br /></span>');
		},

		removeErrors: function() {
			$('.error', StagTypekit.cache.$descriptionText).remove();
		},

		removeSuccess: function() {
			$('.success', StagTypekit.cache.$descriptionText).remove();
		},

		reset: function() {
			// Remove the text input
			StagTypekit.cache.$textInput.attr('value', '').change();

			// Remove the Typekit fonts
			StagTypekit.removeFonts();

			// Set the default fonts
			StagTypekit.cache.$headerFontSelect.val(StagTypekitData.headerFont).change();
			StagTypekit.cache.$bodyFontSelect.val(StagTypekitData.bodyFont).change();
		}
	};

	StagTypekit.init();
})(jQuery);
