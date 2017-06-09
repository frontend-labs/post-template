<?php
/**
 * SyntaxHighlighter Evolved - Spacegray
 *
 * A nice Color Scheme for "SyntaxHighlighter Evolved"
 * based on "Spacegray", the lovely Theme  for Sublime Text 2/3.
 *
 * Plugin Name:  SyntaxHighlighter Evolved - Spacegray
 * Plugin URI:   http://wordpress.org/plugins/syntaxhighlighter-evolved-spacegray/
 * Description:  A nice Color Scheme for "SyntaxHighlighter Evolved" based on "Spacegray", the lovely Theme  for Sublime Text 2/3.
 * Version:      1.0.2
 * Author:       Manoz
 * Author URI:   http://k-legrand.fr/
 * License:      GPL-3.0+
 * License URI:  http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'WPINC' ) ) die;

// Add Spacegray themes to the "SyntaxHighlighter Evolved" plugin
syntaxhighlighter_spacegray_regtheme();

add_filter( 'syntaxhighlighter_themes', 'add_spacegray_theme' );
add_filter( 'syntaxhighlighter_themes', 'add_spacegray_light_theme' );

function add_spacegray_theme( $themes ) {
    $themes['spacegray'] = 'Spacegray';
    return $themes;
}

function add_spacegray_light_theme( $themes ) {
    $themes['spacegray-light'] = 'Spacegray Light';
    return $themes;
}

// Register styles
function syntaxhighlighter_spacegray_regtheme() {

    wp_register_style( 'syntaxhighlighter-theme-spacegray',
        plugins_url( 'shThemeSpacegray.css', __FILE__ ),
        array( 'syntaxhighlighter-core' ),
        '1.0.2'
    );

    wp_register_style( 'syntaxhighlighter-theme-spacegray-light',
        plugins_url( 'shThemeSpacegrayLight.css', __FILE__ ),
        array( 'syntaxhighlighter-core' ),
        '1.0.2'
    );

}
