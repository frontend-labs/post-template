<?php
/**
 * Uninstall Stag Custom Sidebar
 *
 * @package Stag_Custom_Sidebars
 * @author Ram Ratan Maurya
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;

// Delete Plugin Options
delete_option( 'stag_custom_sidebars' );
