<?php
/**
 * The template for displaying search form.
 *
 * @package Stag_Customizer
 * @subpackage Ink
 */

$search_query = get_search_query();

?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php _ex( 'Search for:', 'label', 'stag' ); ?></span>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Buscar &hellip;', 'placeholder', 'stag' ); ?>" value="<?php the_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Buscar por:', 'label', 'stag' ); ?>">
	</label>
	<button type="submit" class="search-submit"><i class="fa fa-search"></i></button>
</form>
