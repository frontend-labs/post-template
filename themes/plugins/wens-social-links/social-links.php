<?php
/*
 * Plugin Name: WEN's Social Links
 * Version: 3.0.1
 * Plugin URI: http://wordpress.org/plugins/wens-social-links/
 * Description: Plugin to link 22 social networking sites to your WordPress Site.
 * Author: Manesh Timilsina
 * Author URI: http://manesh.com.np
 * License: GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

define('PLUGIN_NAME', 'WEN Social Links');
define('PLUGIN_VERSION', '3.0.1');

add_action('wp_enqueue_scripts', 'wen_enque_scripts');

function wen_enque_scripts() {

    wp_enqueue_style('main-style', plugins_url('/css/main-style.css', __FILE__), array(), PLUGIN_VERSION, 'all');
 
    wp_enqueue_script('jquery');

    wp_enqueue_script('jquery-ui-tooltip');               

    wp_enqueue_script('SC_custom', plugins_url( '/js/custom.js' , __FILE__ ));

}

// Create custom settings menu
add_action('admin_menu', 'wen_create_menu');


function wen_create_menu() {

    add_options_page( __( ' WEN Plugin Options' ), __( 'WEN Social Links' ), 'manage_options', basename(__FILE__), 'wen_settings_page' );
}

// Register settings
add_action( 'admin_init', 'register_plugin_settings' );

function register_plugin_settings() {
   global $wen_options, $option_group, $option_name;
    //register our settings
    register_setting( $option_group, $option_name);
}


$dir = plugin_dir_path( __FILE__ );

require_once($dir.'options.php');

require_once($dir.'functions.php');


