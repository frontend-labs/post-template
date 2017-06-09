<?php
/**
 * Add a custom meta box
 *
 * @param array $meta_box Meta box input data
 * @return void
 */
function stag_add_meta_box( $meta_box ) {
	if( !is_array( $meta_box) ) return false;

	$callback = create_function( '$post,$meta_box', 'stag_create_meta_box( $post, $meta_box["args"] );' );

	add_meta_box( $meta_box['id'], $meta_box['title'], $callback, $meta_box['page'], $meta_box['context'], $meta_box['priority'], $meta_box );
}

/**
 * Create content for the custom meta box
 *
 * @param array $meta_box Meta box input data
 * @return void
 */
function stag_create_meta_box( $post, $meta_box ) {
	if( !is_array( $meta_box) ) return false;

	if( isset($meta_box['description']) && $meta_box['description'] != '' ){
		echo '<p>'. $meta_box['description'] .'</p>';
	}

	wp_nonce_field( basename(__FILE__), 'stag_meta_box_nonce' );

	echo '<table class="form-table stag-metabox-table">';

	foreach( $meta_box['fields'] as $field ){

		$meta = stag_get_post_meta( 'settings', $post->ID, $field['id'] );

		echo '<tr><th><label for="'. $field['id'] .'"><strong>'. $field['name'] .'</strong>
			 <span>'. $field['desc'] .'</span></label></th>';

		switch( $field['type'] ){
			case 'text':
			case 'default':
				echo '<td><input type="text" name="stag_meta['. $field['id'] .']" id="'. $field['id'] .'" value="'. ($meta ? $meta : $field['std']) .'" size="30" /></td>';
			break;

			case 'number':
				$min  = ( isset( $field['min'] ) ) ? $field['min']: '1';
				$max  = ( isset( $field['max'] ) ) ? $field['max']: '100';
				$step = ( isset( $field['step'] ) ) ? $field['step']: '1';

				echo '<td><input type="number" name="stag_meta['. $field['id'] .']" id="'. $field['id'] .'" value="'. ($meta ? $meta : $field['std']) .'" size="30" min="'. $min .'" max="'. $max .'" step="'. $step .'" /></td>';
			break;

			case 'textarea':
				$rows = ( isset( $field['rows'] ) ) ? $field['rows'] : '8';
				echo '<td><textarea name="stag_meta['. $field['id'] .']" id="'. $field['id'] .'" rows="' . $rows . '" cols="5">'. ($meta ? $meta : $field['std']) .'</textarea></td>';
			break;

			case 'file':

				$multiple     = ( isset( $field['multiple'] ) ) ? true : false;
				$library_type = ( isset( $field['library'] ) ) ? $field['library'] : false;

				?>

				<script>
				jQuery(function($){
					var frame,
						isMultiple = "<?php echo $multiple; ?>";

					$('#<?php echo $field['id']; ?>_button').on('click', function(e) {
						e.preventDefault();

						var options = {
							state: 'insert',
							frame: 'post',
							multiple: isMultiple,
							library: {
								type: '<?php echo $library_type ?>'
							}
						};

						frame = wp.media(options).open();

						frame.menu.get('view').unset('gallery');
						frame.menu.get('view').unset('featured-image');
						frame.menu.get('view').unset('playlist');
						frame.menu.get('view').unset('video-playlist');

						frame.toolbar.get('view').set({
							insert: {
								style: 'primary',
								text: '<?php _e("Insert", "stag"); ?>',

								click: function() {
									var models = frame.state().get('selection'),
										url = models.first().attributes.url,
										files = [];

									if( isMultiple ) {
										models.map (function( attachment ) {
											attachment = attachment.toJSON();
											files.push(attachment.url);
											url = files;
										});
									}

									$('#<?php echo $field['id']; ?>').val( url );

									frame.close();
								}
							}
						});
					});
				});
				</script>

				<?php
				echo '<td><input type="text" name="stag_meta['. $field['id'] .']" id="'. $field['id'] .'" value="'. ($meta ? $meta : $field['std']) .'" size="30" class="file" /> <input type="button" class="button" name="'. $field['id'] .'_button" id="'. $field['id'] .'_button" value="Browse" /></td>';
			break;

			case 'images':
			    ?>
			    <script>
			    jQuery(function($){
			        var frame,
			            images = '<?php echo stag_get_post_meta( "settings", $post->ID, "stag_image_ids" ); ?>',
			            selection = loadImages(images);

			        $('#stag_images_upload').on('click', function(e) {
			            e.preventDefault();
			            var options = {
			                title: '<?php _e("Create Featured Gallery", "stag"); ?>',
			                state: 'gallery-edit',
			                frame: 'post',
			                selection: selection
			            };

			            if( frame || selection ) {
			                options['title'] = '<?php esc_attr_e("Edit Featured Gallery", "stag"); ?>';
			            }

			            frame = wp.media(options).open();

			            // Tweak Views
			            frame.menu.get('view').unset('cancel');
			            frame.menu.get('view').unset('separateCancel');
			            frame.menu.get('view').get('gallery-edit').el.innerHTML = '<?php esc_attr_e("Edit Featured Gallery", "stag"); ?>';
			            frame.content.get('view').sidebar.unset('gallery'); // Hide Gallery Settings in sidebar

			            // when editing a gallery
			            overrideGalleryInsert();
			            frame.on( 'toolbar:render:gallery-edit', function() {
			                overrideGalleryInsert();
			            });

			            frame.on( 'content:render:browse', function( browser ) {
			                if ( !browser ) return;
			                // Hide Gallery Settings in sidebar
			                browser.sidebar.on('ready', function(){
			                    browser.sidebar.unset('gallery');
			                });
			                // Hide filter/search as they don't work
			                browser.toolbar.on('ready', function(){
			                    if(browser.toolbar.controller._state == 'gallery-library'){
			                        browser.toolbar.$el.hide();
			                    }
			                });
			            });

			            // All images removed
			            frame.state().get('library').on( 'remove', function() {
			                var models = frame.state().get('library');
			                if(models.length == 0){
			                    selection = false;
			                    $.post(ajaxurl, { ids: '', action: 'stag_save_images', post_id: stag_ajax.post_id, nonce: stag_ajax.nonce });
			                }
			            });

			            function overrideGalleryInsert(){
			                frame.toolbar.get('view').set({
			                    insert: {
			                        style: 'primary',
			                        text: '<?php _e("Save Featured Gallery", "stag"); ?>',
			                        click: function(){
			                            var models = frame.state().get('library'),
			                                ids = '';

			                            models.each( function( attachment ) {
			                                ids += attachment.id + ','
			                            });

			                            this.el.innerHTML = '<?php esc_attr_e("Saving...", "stag"); ?>';

			                            $.ajax({
			                                type: 'POST',
			                                url: ajaxurl,
			                                data: {
			                                    ids: ids,
			                                    action: 'stag_save_images',
			                                    post_id: stag_ajax.post_id,
			                                    nonce: stag_ajax.nonce
			                                },
			                                success: function(){
			                                    selection = loadImages(ids);
			                                    $('#_stag_image_ids').val( ids );
			                                    frame.close();
			                                },
			                                dataType: 'html'
			                            }).done( function( data ) {
			                                $('.stag-gallery-thumbs').html( data );
			                                console.log(data);
			                            });
			                        }
			                    }
			                });
			            }

			        });

			        function loadImages(images){
			            if (images){
			                var shortcode = new wp.shortcode({
			                    tag:      'gallery',
			                    attrs:    { ids: images },
			                    type:     'single'
			                });

			                var attachments = wp.media.gallery.attachments( shortcode );

			                var selection = new wp.media.model.Selection( attachments.models, {
			                    props:    attachments.props.toJSON(),
			                    multiple: true
			                });

			                selection.gallery = attachments.gallery;

			                selection.more().done( function() {
			                    // Break ties with the query.
			                    selection.props.set({ query: false });
			                    selection.unmirror();
			                    selection.props.unset('orderby');
			                });

			                return selection;
			            }
			            return false;
			        }
			    });
			    </script>

			    <?php

			    $meta = stag_get_post_meta( 'settings', $post->ID, 'stag_image_ids' );
			    $thumbs_output = '';
			    $button_text = ($meta) ? __('Edit Gallery', 'stag') : $field['std'];
			    if( $meta ) {
			        $field['std'] = __('Edit Gallery', 'stag');
			        $thumbs = explode(',', $meta);
			        $thumbs_output = '';
			        foreach( $thumbs as $thumb ) {
			            $thumbs_output .= '<li>' . wp_get_attachment_image( $thumb, array(75,75) ) . '</li>';
			        }
			    }

			    echo '<td class="stag-box-'.$field['type'].'">
			            <input type="button" class="button" name="' . $field['id'] . '" id="stag_images_upload" value="' . $button_text .'" />
			            <input type="hidden" name="stag_meta[_stag_image_ids]" id="_stag_image_ids" value="' . ($meta ? $meta : 'false') . '" />
			            <ul class="stag-gallery-thumbs">' . $thumbs_output . '</ul>
			        </td>';
		    break;

			case 'select':
				echo'<td><select name="stag_meta['. $field['id'] .']" id="'. $field['id'] .'">';
				foreach( $field['options'] as $key => $option ){
					echo '<option value="' . $key . '"';
					if( $meta ){
						if( $meta == $key ) echo ' selected="selected"';
					} else {
						if( $field['std'] == $key ) echo ' selected="selected"';
					}
					echo'>'. $option .'</option>';
				}
				echo'</select></td>';
			break;

			case 'radio':
				echo '<td>';
				foreach( $field['options'] as $key => $option ){
					echo '<label class="radio-label"><input type="radio" name="stag_meta['. $field['id'] .']" value="'. $key .'" class="radio"';
					if( $meta ){
						if( $meta == $key ) echo ' checked="checked"';
					} else {
						if( $field['std'] == $key ) echo ' checked="checked"';
					}
					echo ' /> '. $option .'</label> ';
				}
				echo '</td>';
			break;

			case 'color':
				echo '<td class="stag-box-'.$field['type'].'"><input data-default-color="'.@$field['std'].'" type="text" id="'. $field['id'] .'" name="stag_meta[' . $field['id'] .']" value="'. ($meta ? $meta : $field['std']) .'" class="colorpicker"></td>';
				?>
				<script type="text/javascript">
					jQuery(document).ready(function($){
						jQuery("#<?php echo $field['id'] ?>").wpColorPicker();
					});
				</script>
				<?php
			break;

			case 'checkbox':
			    echo '<td>';
			    $val = '';
	            if( $meta ) {
	                if( $meta == 'on' ) $val = ' checked="checked"';
	            } else {
	                if( $field['std'] == 'on' ) $val = ' checked="checked"';
	            }

	            echo '<input type="hidden" name="stag_meta['. $field['id'] .']" value="off" />
	            <input type="checkbox" id="'. $field['id'] .'" name="stag_meta['. $field['id'] .']" value="on"'. $val .' /> ';
			    echo '</td>';
		    break;

		}

		echo '</tr>';

	}

	echo '</table>';
}

/**
 * Save custom meta box
 *
 * @param int $post_id The post ID
 * @return void
 */
function stag_save_meta_box( $post_id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

	if ( !isset($_POST['stag_meta']) || !isset($_POST['stag_meta_box_nonce']) || !wp_verify_nonce( $_POST['stag_meta_box_nonce'], basename( __FILE__ ) ) )
		return;

	if ( 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) ) return;
	} else {
		if ( !current_user_can( 'edit_post', $post_id ) ) return;
	}

	foreach ( $_POST['stag_meta'] as $key => $val ) {
		stag_update_post_meta( 'settings', $post_id, $key, stripslashes( htmlspecialchars( $val ) ) );
	}
}
add_action( 'save_post', 'stag_save_meta_box' );

/**
 * Save images via AJAX 'images' metabox type.
 *
 * @return void
 */
function stag_save_images(){
    if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
        return;
    }

    if ( !isset($_POST['ids']) || !isset($_POST['nonce']) || !wp_verify_nonce( $_POST['nonce'], 'stag-ajax' ) ){
        return;
    }

    if ( !current_user_can( 'edit_posts' ) ) return;

    $ids = strip_tags(rtrim($_POST['ids'], ','));
    stag_update_post_meta( 'settings', $_POST['post_id'], 'stag_image_ids', $ids );

    $thumbs = explode(',', $ids);
    $thumbs_output = '';
    foreach( $thumbs as $thumb ) {
        $thumbs_output .= '<li>' . wp_get_attachment_image( $thumb, array(75,75) ) . '</li>';
    }

    echo $thumbs_output;

    die();
}
add_action( 'wp_ajax_stag_save_images', 'stag_save_images' );

/**
 * Add scripts required on metabox page.
 *
 * @return void
 */
function stag_metabox_scripts(){
    global $post;
    if( isset($post) ) {
        wp_localize_script( 'jquery', 'stag_ajax', array(
            'post_id' => $post->ID,
            'nonce' => wp_create_nonce( 'stag-ajax' )
        ) );
    }
}
add_action( 'admin_enqueue_scripts', 'stag_metabox_scripts' );

if ( ! function_exists( 'stag_flush_single_post_meta_cache' ) ) :
/**
 * Clear the Ink-specific meta cache for a particular post.
 *
 * @since 1.0.
 *
 * @param int $post_id The post's id
 */
function stag_flush_single_post_meta_cache( $post_id ) {
	// Checks save status
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );

	// Exits script depending on save status
	if ( $is_autosave || $is_revision ) {
		return;
	}

	delete_post_meta( $post_id, '_stag-post-cache' );
}
endif;

add_action( 'save_post', 'stag_flush_single_post_meta_cache' );

if ( ! function_exists( 'stag_flush_all_post_meta_cache' ) ) :
/**
 * Clear all Ink-specific post meta caches.
 *
 * @since 1.0.
 *
 */
function stag_flush_all_post_meta_cache() {
	delete_post_meta_by_key( '_stag-post-cache' );
}
endif;
add_action( 'switch_theme', 'stag_flush_all_post_meta_cache' );
