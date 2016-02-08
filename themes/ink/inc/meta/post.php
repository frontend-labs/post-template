<?php

add_action('add_meta_boxes', 'stag_metabox_post');

function stag_metabox_post(){
	$meta_box = array(
		'id'          => 'stag-metabox-portfolio',
		'title'       =>  __( 'Background Settings', 'stag' ),
		'description' => __( 'Here you can customize post background cover. The cover&rsquo;s image is selected at &ldquo;Featured Image&rdquo; panel on the right.', 'stag' ),
		'page'        => 'post',
		'context'     => 'normal',
		'priority'    => 'high',
		'fields'      => array(
			array(
				'name' => __( 'Background Color', 'stag' ),
				'desc' => __( 'Choose background color for this post.', 'stag' ),
				'id'   => 'post-background-color',
				'type' => 'color',
				'std'  => '#000000'
			),
			array(
                'name' => __( 'Cover Opacity', 'stag' ),
                'desc' => __( 'Choose the opacity for the post&lsquo;s cover.', 'stag' ),
                'id'   => 'post-background-opacity',
                'type' => 'number',
                'std'  => '40',
                'step'  => '5',
                'min'  => '0'
            ),
            array(
                'name'    => __( 'Cover Filter', 'stag' ),
                'desc'    => __( 'Applies CSS3 filter on cover image.', 'stag' ),
                'id'      => 'post-background-filter',
                'type'    => 'select',
                'std'     => 'none',
                'options' => array(
                    'none'       => __( 'None', 'stag' ),
                    'grayscale'  => __( 'Grayscale', 'stag' ),
                    'sepia'      => __( 'Sepia', 'stag' ),
                    'blur'       => __( 'Blur', 'stag' ),
                    'hue-rotate' => __( 'Hue Rotate', 'stag' ),
                    'contrast'   => __( 'Contrast', 'stag' ),
                    'brightness' => __( 'Brightness', 'stag' ),
                    'invert'     => __( 'Invert', 'stag' )
                ),
			),
		)
	);

    stag_add_meta_box( $meta_box );

    $meta_box = array(
    	'id'          => 'stag-metabox-portfolio',
    	'title'       =>  __( 'Background Settings', 'stag' ),
    	'description' => __( 'Here you can customize page background cover. The cover&rsquo;s image is selected at &ldquo;Featured Image&rdquo; panel on the right.', 'stag' ),
    	'page'        => 'page',
    	'context'     => 'normal',
    	'priority'    => 'high',
    	'fields'      => array(
    		array(
    			'name' => __( 'Background Color', 'stag' ),
    			'desc' => __( 'Choose background color for this page.', 'stag' ),
    			'id'   => 'post-background-color',
    			'type' => 'color',
    			'std'  => ''
    		),
    		array(
    			'name' => __( 'Featured Image Opacity', 'stag' ),
    			'desc' => __( 'Choose featured image opacity for this page.', 'stag' ),
    			'id'   => 'post-background-opacity',
    			'type' => 'number',
    			'std'  => '40',
    			'step'  => '5',
    			'min'  => '0'
    		),
            array(
                'name'    => __( 'Cover Filter', 'stag' ),
                'desc'    => __( 'Applies CSS3 filter on cover image.', 'stag' ),
                'id'      => 'post-background-filter',
                'type'    => 'select',
                'std'     => 'none',
                'options' => array(
                    'none'       => __( 'None', 'stag' ),
                    'grayscale'  => __( 'Grayscale', 'stag' ),
                    'sepia'      => __( 'Sepia', 'stag' ),
                    'blur'       => __( 'Blur', 'stag' ),
                    'hue-rotate' => __( 'Hue Rotate', 'stag' ),
                    'contrast'   => __( 'Contrast', 'stag' ),
                    'brightness' => __( 'Brightness', 'stag' ),
                    'invert'     => __( 'Invert', 'stag' )
                ),
            ),
    		array(
    			'name' => __( 'Text Color', 'stag' ),
    			'desc' => __( 'Choose text color for this page.', 'stag' ),
    			'id'   => 'post-text-color',
    			'type' => 'color',
    			'std'  => '#333333'
    		),
    		array(
    			'name' => __( 'Link Color', 'stag' ),
    			'desc' => __( 'Choose link color for this page.', 'stag' ),
    			'id'   => 'post-link-color',
    			'type' => 'color',
    			'std'  => stag_theme_mod( 'colors', 'accent' )
    		)
    	)
    );

	stag_add_meta_box( $meta_box );

    $meta_box = array(
        'id'          => 'stag-metabox-layout',
        'title'       =>  __( 'Sidebar Settings', 'stag' ),
        'description' => __( 'Select a sidebar here to display its widgets after the page content.', 'stag' ),
        'page'        => 'page',
        'context'     => 'side',
        'priority'    => 'high',
        'fields'      => array(
            array(
                'name'    => __( 'Choose Sidebar', 'stag' ),
                'desc'    => null,
                'id'      => 'page-sidebar',
                'type'    => 'select',
                'std'     => 'default',
                'options' => stag_registered_sidebars( array('' => __( 'No Sidebar', 'stag' ) ) )
            ),
            array(
                'name' => __( 'Hide Page Title?', 'stag' ),
                'desc' => null,
                'id'   => 'page-hide-title',
                'type' => 'checkbox',
                'std'  => 'off'
            )
        )
    );

    stag_add_meta_box( $meta_box );

    $meta_box = array(
        'id'          => 'stag-metabox-video',
        'title'       =>  __( 'Video Cover Settings', 'stag' ),
        'description' => __( 'Select a video to display at the post&lsquo;s single page. If these fields are left blank, the Featured Image will show. The background settings from the panel above (background color and opacity) also apply for the video cover. (Videos are disabled for viewports smaller than 650px of width.)', 'stag' ),
        'page'        => 'post',
        'context'     => 'normal',
        'priority'    => 'high',
        'fields'      => array(
            array(
                'name'    => __( 'MP4 File URL', 'stag' ),
                'desc'    => __( 'Enter URL to .mp4 video file.', 'stag' ),
                'id'      => 'post-video-mp4',
                'type'    => 'file',
                'std'     => '',
                'library' => 'video'
            ),
            array(
                'name'    => __( 'Webm File URL', 'stag' ),
                'desc'    => __( 'Enter URL to .webm video file.', 'stag' ),
                'id'      => 'post-video-webm',
                'type'    => 'file',
                'std'     => '',
                'library' => 'video'
            ),
            array(
                'name'    => __( 'OGV File URL', 'stag' ),
                'desc'    => __( 'Enter URL to .ogv video file.', 'stag' ),
                'id'      => 'post-video-ogv',
                'type'    => 'file',
                'std'     => '',
                'library' => 'video'
            ),
        )
    );

    stag_add_meta_box( $meta_box );
}
