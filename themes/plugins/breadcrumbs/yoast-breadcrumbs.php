<?php /*
Plugin Name:  Yoast Breadcrumbs
Plugin URI:   http://yoast.com/wordpress/breadcrumbs/
Description:  Outputs a fully customizable breadcrumb path.
Version:      0.8.5
Author:       Joost de Valk
Author URI:   http://yoast.com/

Copyright (C) 2008-2010, Joost de Valk
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
Neither the name of Joost de Valk or Yoast nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.
THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.*/

// Load some defaults
$opt 						= array();
$opt['home'] 				= "Home";
$opt['blog'] 				= "Blog";
$opt['sep'] 				= "&raquo;";
$opt['prefix']				= "You are here:";
$opt['boldlast'] 			= true;
$opt['nofollowhome'] 		= false;
$opt['singleparent'] 		= 0;
$opt['singlecatprefix']		= true;
$opt['archiveprefix'] 		= "Archives for";
$opt['searchprefix'] 		= "Search for";
add_option("yoast_breadcrumbs",$opt);

if ( ! class_exists( 'YoastBreadcrumbs_Admin' ) ) {

	require_once('yst_plugin_tools.php');

	class YoastBreadcrumbs_Admin extends Yoast_Plugin_Admin {

		var $hook 		= 'breadcrumbs';
		var $longname	= 'Yoast Breadcrumbs Configuration';
		var $shortname	= 'Breadcrumbs';
		var $filename	= 'breadcrumbs/yoast-breadcrumbs.php';
		var $ozhicon	= 'script_link.png';

		function config_page() {
			if ( isset($_POST['submit']) ) {
				if (!current_user_can('manage_options')) die(__('You cannot edit the Yoast Breadcrumbs options.'));
				check_admin_referer('yoast-breadcrumbs-updatesettings');
				
				foreach (array('home', 'blog', 'sep', 'singleparent', 'prefix', 'archiveprefix', 'searchprefix', 'breadcrumbprefix', 'breadcrumbsuffix') as $option_name) {
					if (isset($_POST[$option_name])) {
						$opt[$option_name] = htmlentities(html_entity_decode($_POST[$option_name]));
					}
				}

				foreach (array('boldlast', 'nofollowhome', 'singlecatprefix', 'trytheme') as $option_name) {
					if (isset($_POST[$option_name])) {
						$opt[$option_name] = true;
					} else {
						$opt[$option_name] = false;
					}
				}
				
				update_option('yoast_breadcrumbs', $opt);
			}
			
			$opt  = get_option('yoast_breadcrumbs');
			?>
			<div class="wrap">
				<a href="http://yoast.com/"><div id="yoast-icon" style="background: url(http://cdn.yoast.com/theme/yoast-32x32.png) no-repeat;" class="icon32"><br /></div></a>
				<h2>Yoast Breadcrumbs Configuration</h2>
				<div class="postbox-container" style="width:70%;">
					<div class="metabox-holder">	
						<div class="meta-box-sortables">
							<form action="" method="post" id="yoastbreadcrumbs-conf">
								<?php if (function_exists('wp_nonce_field')) 		
										wp_nonce_field('yoast-breadcrumbs-updatesettings');
										
								$rows = array();
								$rows[] = array(
									"id" => "sep",
									"label" => __('Separator between breadcrumbs'),
									"content" => '<input type="text" name="sep" id="sep" value="'.htmlentities($opt['sep']).'"/>',
								);
								$rows[] = array(
									"id" => "home",
									"label" => __('Anchor text for the Homepage'),
									"content" => '<input type="text" name="home" id="home" value="'.$opt['home'].'"/>',
								);
								$rows[] = array(
									"id" => "blog",
									"label" => __('Anchor text for the Blog'),
									"content" => '<input type="text" name="blog" id="blog" value="'.$opt['blog'].'"/>',
								);
								$rows[] = array(
									"id" => "prefix",
									"label" => __('Prefix for the breadcrumb path'),
									"content" => '<input type="text" name="prefix" id="prefix" value="'.$opt['prefix'].'"/>',
								);
								$rows[] = array(
									"id" => "archiveprefix",
									"label" => __('Prefix for Archive breadcrumbs'),
									"content" => '<input type="text" name="archiveprefix" id="archiveprefix" value="'.$opt['archiveprefix'].'"/>',
								);
								$rows[] = array(
									"id" => "searchprefix",
									"label" => __('Prefix for Search Page breadcrumbs'),
									"content" => '<input type="text" name="searchprefix" id="searchprefix" value="'.$opt['searchprefix'].'"/>',
								);
								$rows[] = array(
									"id" => "singlecatprefix",
									"label" => __('Show category in post breadcrumbs?'),
									"desc" => __('Shows the category inbetween Home and the blogpost'),
									"content" => '<input type="checkbox" name="singlecatprefix" id="singlecatprefix" '.checked($opt['singlecatprefix'],true,false).'/>',
								);
								$rows[] = array(
									"id" => "singleparent",
									"label" => __('Show Parent Page for Blog posts'),
									"desc" => __('Adds another page inbetween Home and the blogpost'),
									"content" => wp_dropdown_pages("echo=0&depth=0&name=singleparent&show_option_none=-- None --&selected=".$opt['singleparent']),
								);
								$rows[] = array(
									"id" => "boldlast",
									"label" => __('Bold the last page in the breadcrumb'),
									"content" => '<input type="checkbox" name="boldlast" id="boldlast" '.checked($opt['boldlast'],true,false).'/>',
								);
								$rows[] = array(
									"id" => "nofollowhome",
									"label" => __('Nofollow the link to the home page'),
									"content" => '<input type="checkbox" name="nofollowhome" id="nofollowhome" '.checked($opt['nofollowhome'],true,false).'/>',
								);
								$rows[] = array(
									"id" => "trytheme",
									"label" => __('Try to add automatically'),
									"desc" => __('If you\'re using Hybrid, Thesis or Thematic, check this box for some lovely simple action'),
									"content" => '<input type="checkbox" name="trytheme" id="trytheme" '.checked($opt['trytheme'],true,false).'/>',
								);
								
								$table = $this->form_table($rows);
								
								$this->postbox('breadcrumbssettings',__('Setting for Yoast Breadcrumbs'), $table.'<div class="submit"><input type="submit" class="button-primary" name="submit" value="Save Breadcrumbs Settings" /></div>')
								?>
							</form>
						</div>
					</div>
				</div>
				<div class="postbox-container" style="width:20%;">
					<div class="metabox-holder">	
						<div class="meta-box-sortables">
							<?php
								$this->plugin_like();
								$this->plugin_support();
								$this->news(); 
							?>
						</div>
						<br/><br/><br/>
					</div>
				</div>
			</div>
		
<?php		}
	}
	
	$ybc = new YoastBreadcrumbs_Admin();
}

function yoast_breadcrumb($prefix = '', $suffix = '', $display = true) {
	global $wp_query, $post;
	
	$opt = get_option("yoast_breadcrumbs");

	if (!function_exists('bold_or_not')) {
		function bold_or_not($input) {
			$opt = get_option("yoast_breadcrumbs");
			if ($opt['boldlast']) {
				//return '<strong>'.$input.'</strong>';
				return '';
			} else {
				return $input;
			}
		}		
	}

	if (!function_exists('yoast_get_category_parents')) {
		// Copied and adapted from WP source
		function yoast_get_category_parents($id, $link = FALSE, $separator = '/', $nicename = FALSE){
			$chain = '';
			$parent = &get_category($id);
			if ( is_wp_error( $parent ) )
			   return $parent;

			if ( $nicename )
			   $name = $parent->slug;
			else
			   $name = $parent->cat_name;

			if ( $parent->parent && ($parent->parent != $parent->term_id) )
			   $chain .= get_category_parents($parent->parent, true, $separator, $nicename);

			$chain .= bold_or_not($name);
			return $chain;
		}
	}
	
	$nofollow = ' ';
	if ($opt['nofollowhome']) {
		$nofollow = ' rel="nofollow" ';
	}
	
	$on_front = get_option('show_on_front');
	
	if ($on_front == "page") {
		$homelink = '<a'.$nofollow.'href="'.get_permalink(get_option('page_on_front')).'">'.$opt['home'].'</a>';
		$bloglink = $homelink.' '.$opt['sep'].' <a href="'.get_permalink(get_option('page_for_posts')).'">'.$opt['blog'].'</a>';
	} else {
		// breadcrumb home
		$homelink = '<span typeof="v:Breadcrumb"><a'.$nofollow.' rel="v:url" property="v:title" href="'.get_bloginfo('url').'">'.$opt['home'].'</a></span>';
		$bloglink = $homelink;
	}
		
	if ( ($on_front == "page" && is_front_page()) || ($on_front == "posts" && is_home()) ) {
		$output = bold_or_not($opt['home']);
	} elseif ( $on_front == "page" && is_home() ) {
		$output = $homelink.' '.$opt['sep'].' '.bold_or_not($opt['blog']);
	} elseif ( !is_page() ) {
		$output = $bloglink.' '.$opt['sep'].' ';
		if ( ( is_single() || is_category() || is_tag() || is_date() || is_author() ) && $opt['singleparent'] != false) {
			$output .= '<a href="'.get_permalink($opt['singleparent']).'">'.get_the_title($opt['singleparent']).'</a> '.$opt['sep'].' ';
		} 
		if (is_single() && $opt['singlecatprefix']) {
			$cats = get_the_category();
			$cat = $cats[0];
			if ( is_object($cat) ) {
				if ($cat->parent != 0) {
					$output .= get_category_parents($cat->term_id, true, " ".$opt['sep']." ");
				} else {
					$output .= '<span typeof="v:Breadcrumb"><a rel="v:url" property="v:title" href="'.get_category_link($cat->term_id).'">'.$cat->name.'</a></span>'; //.$opt['sep'].' '; 
				}
			}
		}
		if ( is_category() ) {
			$cat = intval( get_query_var('cat') );
			$output .= yoast_get_category_parents($cat, false, " ".$opt['sep']." ");
		} elseif ( is_tag() ) {
			$output .= bold_or_not($opt['archiveprefix']." ".single_cat_title('',false));
		} elseif ( is_date() ) { 
			$output .= bold_or_not($opt['archiveprefix']." ".single_month_title(' ',false));
		} elseif ( is_author() ) { 
			$user = get_userdatabylogin($wp_query->query_vars['author_name']);
			$output .= bold_or_not($opt['archiveprefix']." ".$user->display_name);
		} elseif ( is_search() ) {
			$output .= bold_or_not($opt['searchprefix'].' "'.stripslashes(strip_tags(get_search_query())).'"');
		} else if ( is_tax() ) {
			$taxonomy 	= get_taxonomy ( get_query_var('taxonomy') );
			$term 		= get_query_var('term');
			$output .= $taxonomy->label .': '.bold_or_not( $term );
		} else {
			$output .= bold_or_not(get_the_title());
		}
	} else {
		$post = $wp_query->get_queried_object();

		// If this is a top level Page, it's simple to output the breadcrumb
		if ( 0 == $post->post_parent ) {
			$output = $homelink." ".$opt['sep']." ".bold_or_not(get_the_title());
		} else {
			if (isset($post->ancestors)) {
				if (is_array($post->ancestors))
					$ancestors = array_values($post->ancestors);
				else 
					$ancestors = array($post->ancestors);				
			} else {
				$ancestors = array($post->post_parent);
			}

			// Reverse the order so it's oldest to newest
			$ancestors = array_reverse($ancestors);

			// Add the current Page to the ancestors list (as we need it's title too)
			$ancestors[] = $post->ID;

			$links = array();			
			foreach ( $ancestors as $ancestor ) {
				$tmp  = array();
				$tmp['title'] 	= strip_tags( get_the_title( $ancestor ) );
				$tmp['url'] 	= get_permalink($ancestor);
				$tmp['cur'] = false;
				if ($ancestor == $post->ID) {
					$tmp['cur'] = true;
				}
				$links[] = $tmp;
			}

			$output = $homelink;
			foreach ( $links as $link ) {
				$output .= ' '.$opt['sep'].' ';
				if (!$link['cur']) {
					$output .= '<a href="'.$link['url'].'">'.$link['title'].'</a>';
				} else {
					$output .= bold_or_not($link['title']);
				}
			}
		}
	}
	if ($opt['prefix'] != "") {
		$output = $opt['prefix']." ".$output;
	}
	if ($display) {
		echo $prefix.$output.$suffix;
	} else {
		return $prefix.$output.$suffix;
	}
}

function yoast_breadcrumb_output() {
	$opt = get_option('yoast_breadcrumbs');
	if ($opt['trytheme'])
		yoast_breadcrumb('<div id="yoastbreadcrumb">','</div>');
	return;
}
add_action('thesis_hook_before_content','yoast_breadcrumb_output',10,1);
add_action('hybrid_before_content','yoast_breadcrumb_output',10,1);
add_action('thematic_belowheader','yoast_breadcrumb_output',10,1);
add_action('framework_hook_content_open','yoast_breadcrumb_output',10,1);

?>