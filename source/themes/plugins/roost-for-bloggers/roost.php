<?php
/**
 * Plugin Name: Roost Web Push
 * Plugin URI: http://goroost.com/
 * Description: Drive traffic to your website with Safari Mavericks push notifications and Roost.
 * Version: 2.1.8
 * Author: Roost
 * Author URI: http://goroost.com
 * License: GPLv2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
	
define( 'ROOST_URL', plugin_dir_url( __FILE__ ) );

require_once( plugin_dir_path( __FILE__ ) . 'includes/class-roost-core.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-roost-api.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-roost-bbpress.php' );

Roost::init();

$bbPress_active = Roost_bbPress::bbPress_active();
if ( ! empty( $bbPress_active['present'] ) && ! empty( $bbPress_active['enabled'] ) ) {
    Roost_bbPress::init();
}
