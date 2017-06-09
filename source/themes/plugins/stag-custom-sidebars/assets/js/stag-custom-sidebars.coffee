$ = jQuery

class StagCustomSidebars
	constructor: ->
		@widget_wrap = $('.widget-liquid-right')
		@widget_area = $('#widgets-right')
		@widget_add = $('#tmpl-stag-add-widget')
		@create_form()
		@add_elements()
		@events()

	create_form: ->
		@widget_wrap.append(this.widget_add.html())
		@widget_name = @widget_wrap.find('input[name="stag-add-widget"]')
		@nonce = @widget_wrap.find('input[name="scs-delete-nonce"]').val()
		return

	add_elements: ->
		@widget_area.find('.sidebar-stag-custom').append('<span class="scs-area-delete">&#10006;</span>')
		@widget_area.find('.sidebar-stag-custom').each ->
			where_to_add = $(this).find('.widgets-sortables')
			id = where_to_add.attr('id').replace('sidebar-', '')
			if where_to_add.find('.sidebar-description').length > 0
				where_to_add.find(".sidebar-description").append("<p class='description'>#{objectL10n.shortcode}: <code>[stag_sidebar id='#{id}']</code></p>")
			else
				where_to_add.append("<div class='sidebar-description'><p class='description'>#{objectL10n.shortcode}: <code>[stag_sidebar id='#{id}']</code></p></div>")
			return
		return

	events: ->
		@widget_wrap.on('click', '.scs-area-delete', $.proxy( this.delete_sidebar, this) )
		return

	delete_sidebar: (e) ->
		widget = $(e.currentTarget).parents '.widgets-holder-wrap:eq(0)'
		title = widget.find '.sidebar-name h3'
		spinner = title.find '.spinner'
		widget_name = $.trim title.text()
		obj = this

		if confirm( objectL10n.delete_sidebar_area )
			$.ajax {
				type: "POST"
				url: window.ajaxurl
				data: {
					action: 'stag_ajax_delete_custom_sidebar'
					name: widget_name
					_wpnonce: obj.nonce
				}

				beforeSend: ->
					spinner.addClass 'activate'
					return

				success: (response) ->
					if response is "sidebar-deleted"
						widget.slideUp 200, ->
							$('.widget-control-remove', widget).trigger 'click'
							widget.remove()

							wpWidgets.saveOrder()
							return

					return
			}

		return

$ ->
	sidebar = new StagCustomSidebars()
