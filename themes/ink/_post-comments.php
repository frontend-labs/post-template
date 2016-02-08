<?php
/**
 * @package Stag_Customizer
 * @subpackage Ink
 */

// Exit, if current user has no access to the content
if ( stag_rcp_user_has_no_access() ) {
	return;
}

// If comments are open or we have at least one comment, load up the comment template.
if ( comments_open() || get_comments_number() ) :
	comments_template();
endif;
