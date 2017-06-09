<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Ghost
 * @author    Ghost Foundation
 * @license   GPL-2.0+
 * @link      http://ghost.org
 * @copyright 2014 Ghost Foundation
 */

// If uninstall, not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
