<?php
/**
 * @package Stag_Customizer
 * @subpackage Ink
 */
global $wp_query;

$archive_type = array();

if ( is_category() ) {

	$archive_type['cat'] = get_query_var('cat');

} elseif ( is_tag() ) {

	$archive_type['tag_id'] = get_query_var('tag_id');

} elseif ( is_day() ) {

	$archive_type['year']     = get_the_time('Y');
	$archive_type['monthnum'] = get_the_time('n');
	$archive_type['day']      = get_the_time('j');

} elseif ( is_month() ) {

	$archive_type['year']     = get_the_time('Y');
	$archive_type['monthnum'] = get_the_time('n');

} elseif ( is_year() ) {

	$archive_type['year']     = get_the_time('Y');

} elseif ( is_author() ) {

	$archive_type['author'] = get_query_var('author');
	$archive_type['posts_per_page'] = 10;

} elseif ( is_search() ) {

	$archive_type['s'] = get_search_query();

}

?>

<?php if ( $wp_query->max_num_pages > 1 ) : ?>
<div id="infinite-handle">
	<a id="load-more-posts" class="load-more-posts" href="#" data-archive='<?php echo json_encode( $archive_type ) ?>'><?php echo apply_filters( 'stag_load_more_text', __( 'Cargar más', 'stag' ) ); ?></a>
</div>
<?php endif;
