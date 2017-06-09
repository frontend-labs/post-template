<?php 
/** 
 * Represents the view for the public-facing feed of the plugin.
 *
 * This typically includes any information, if any, that is rendered to the
 * frontend of the theme when the plugin is activated.
 * 
 * @package   Easy Facebook like box
 * @author    Your Name <sjaved_87@yahoo.com>
 * @link      http://jwebsol.com
 * @copyright 2015 jwebsol
 */
 
extract($instance);
error_reporting(0); 
//Switch to test mode to disable cache 
$test_mode = false; 

$page_id = ($fanpage_url) ? $fanpage_url : 'jwebsol';
$access_token = ($access_token) ? $access_token : '395202813876688|73e8ede72008b231a0322e40f0072fe6';
//$access_token = ($access_token) ? $access_token : '1489500477999288|KFys5ppNi3sreihdreqPkU2ChIE';

$post_limit = ($post_limit) ? $post_limit : '10';
$number_of_posts = ($post_number) ? $post_number : '10';
if($layout == 'half'){ $layout = 'halfwidth'; }elseif($layout == 'full'){$layout = 'fullwidth';}else{$layout = 'thumbnail';}
$image_size = ($image_size) ? $image_size : 'normal';

if( !empty($show_logo) ) $show_logo = $show_logo; else $show_logo = 1;
if( !empty($show_image) ) $show_image = $show_image; else $show_image = 1;

//Calculate the cache time in seconds
if($cache_unit == 'minutes') $cache_unit = 60;
if($cache_unit == 'hours') $cache_unit = 60*60;
if($cache_unit == 'days') $cache_unit = 60*60*24;
$cache_seconds = $cache_duration * $cache_unit;

//setting query for "Show Posts By"
$query = 'posts';
$others_only = false;

if($post_by == 'me') $query = 'posts';
else
if($post_by == 'others') $query = 'feed';
else
if($post_by == 'onlyothers'){
	$query = 'feed';
	$others_only = true;
}

$trasneint_name = 'efbl_'.$query.'_'.$page_id;

//delete_transient($trasneint_name);

$posts_json = get_transient( $trasneint_name );

if($posts_json == false || $posts_json == ''){
	//build query
	$jws_api_url = 'https://graph.facebook.com/' .$page_id. '/'.$query.'?access_token='. $access_token . '&limit=' . $post_limit . '&locale=en_us';
	
	//set json data
	$posts_json = jws_fetchUrl($jws_api_url);
	
	//store in databse if not in test mode
	if(!$test_mode)
 		set_transient( $trasneint_name, $posts_json, $cache_seconds );
	
	
	//exit('nocahe found');
}else{
	//echo 'usign cached object ... ';
	//echo 'Number of seconds '.$cache_seconds.'<br>';

}

//Interpret data with JSON
$fbData = json_decode($posts_json);

if( !empty($fbData->data) ) {
	
	//Start wraper of feed
	echo '<div class="efbl_feed_wraper">';

		$i = 1;
		foreach($fbData->data as $story){
			
			//reset variables
			$post_text = '';
			
			//to check for the number of posts specified
			if($i == $number_of_posts) break;
			
			//Explode News and Page ID's into 2 values
			$full_story_id = $story->id;
			$PostID = explode("_", $story->id);
			$page_id = $PostID[0];
			$story_id = $PostID[1];
			
			//Check the post type*/
			
			//get the feed type
			$feed_type = $story->type;
			//getting number of likes 
			if(count($story->likes->data) > 24){
				$like_url = "https://graph.facebook.com/" . $full_story_id . "/likes?summary=true&access_token=" . $access_token;
				$likes_data = jws_fetchUrl( $like_url );
 				$efbl_likes_count = json_decode($likes_data)->summary->total_count;
				
			}else{
				$efbl_likes_count = count($story->likes->data);	
			}
			
			//getting number of comments 
			if(count($story->comments->data) >= 25){
				$comments_data = jws_fetchUrl("https://graph.facebook.com/" . $full_story_id . "/comments?summary=true&access_token=" . $access_token);
 				$efbl_comments_count = json_decode($comments_data)->summary->total_count;
				
			}else{
				$efbl_comments_count = count($story->comments->data);	
			}
			
			//getting number of shares
			$shares = $story->shares;
					
			//get the time of story
			$time = $story->created_time;
			//convert time into minutes/days ago.
			$time = efbl_time_ago($time);
			
			if($feed_type == 'photo'){
				$story_link = $story->link;
			}else{
				$story_link = 'https://www.facebook.com/'.$story->id;
			}
			
			( isset($story->message_tags) )? $text_tags = $story->message_tags : $text_tags = $story->story_tags;
					
			//Get the original story
			if(!empty($story->story))
				$post_text = htmlspecialchars($story->story);
			
			//get mesasge
			if(!empty($story->message))	
				$post_text = htmlspecialchars($story->message);
				
			$post_plain_text = $post_text;
			
			$html_check_array = array('&lt;', '’', '“', '&quot;', '&amp;','#','http');
			
			//Convert links url to html links
			$post_text = ecff_makeClickableLinks($post_text);
			
			//convert hastags into links
			$post_text = ecff_hastags_to_link($post_text);
	 
			//always use the text replace method
			if( ecff_stripos_arr($post_text, $html_check_array) !== false ) {
				//Loop through the tags
				if($text_tags) { foreach($text_tags as $message_tag ) {
					$tag_name = $message_tag[0]->name;
					$tag_link = '<a href="https://facebook.com/' . $message_tag[0]->id . '" target="_blank">' . $tag_name . '</a>';
					$post_text = str_replace($tag_name, $tag_link, $post_text);
				} }
		
			}else{
				//not html found now use manaul loop
				$message_tags_arr = array();
				
				$j = 0;
				if($text_tags){
					foreach($text_tags as $message_tag ) {
						$j++;
						
						$tag_name = $message_tag[0]->name;
						$tag_link = '<a href="https://facebook.com/' . $message_tag[0]->id . '" target="_blank">' . $message_tag[0]->name . '</a>';				
						$post_text = str_replace($tag_name, $tag_link, $post_text);
					}
				}
		  
			}
	 
			//Get the image suource of author
			$auth_img_src = 'https://graph.facebook.com/' . $page_id . '/picture?type=large';
			
			//get author image src
			$author_image ='<a href="https://facebook.com/'.$page_id.'" ><img src="'.$auth_img_src.'" title="'. $story->from->name .'" width="40" height="40" /></a>';
			if($story->object_id and $show_image){
				//Get story image
				$pic = 'https://graph.facebook.com/'.$story->object_id.'/picture?type='.$image_size.'&width=9999&height=9999&access_token=' . $access_token . '';
				$full_img_url = 'https://graph.facebook.com/'.$story->object_id.'/picture?type=normal&width=9999&height=9999&access_token=' . $access_token . '';
				$pic_class = 'efbl_has_message';
			}else{
				$pic_class = 'efbl_no_image';
			}
			if($story->message){
				$message_class = 'efbl_has_message';
			}else{
				$message_class = 'efbl_no_message';
			}
	 		
			//Divert to full width layout if no image or no video
			if( $feed_type != 'video' and !isset($story->object_id))
					$layout = 'fullwidth';
				else{
					if($instance['layout'] == 'half' || $instance['layout'] == 'halfwidth'){ $layout = 'halfwidth'; }elseif($instance['layout']  == 'full' || $instance['layout']  == 'fullwidth' ){$instance['layout']  = 'fullwidth';}else{$layout = 'thumbnail';}
				}
			//Start generating html
				echo '<div id="efblcf" class="efbl_fb_story '.$layout.' '.$feed_type.' '.$pic_class.' '.$message_class.' ">';
						if($story->object_id and $show_image and $feed_type != 'video' and !isset($story->source) ){
							
							//if image attached
							echo '<div class="efbl_story_photo">';
 									echo '<img src="' .$pic. '" width="'.$img_width.'" height="'.$img_height.'" />';
 									echo '<a href="#efblcf_holder" data-imagelink="' .$full_img_url. '" data-storylink="'.$story_link.'"  data-linktext="'.__('Read full story', 'easy-facebook-likebox').'" data-caption="'.$post_plain_text.'" class="efbl_feed_popup"><span class="efbl_hover"></span></a>';	
							echo '</div>';
							
						}elseif( $feed_type == 'video' and $story->source){
							
							echo '<div class="efbl_story_photo">';
									
									if (strpos($story->source, 'youtube') > 0){
										
										$video_url = preg_replace('/\?.*/', '', $story->source);
										
											echo '<iframe src="'.$video_url.'" class="efbl_youtube_video"></iframe>';
										echo '<a href="#efblcf_holder" data-videolink="' .$story->source. '" data-storylink="'.$story_link.'" data-linktext="'.__('Read full story', 'easy-facebook-likebox').'"  data-caption="'.$post_plain_text.'" class="efbl_iframe_popup_video efbl_feed_popup"><span class="efbl_hover"></span></a>';	
										
										
									}elseif (strpos($story->source, 'vimeo') > 0){
										
										$video_url = preg_replace('/\?.*/', '', $story->source);
										 
											echo '<iframe src="'.$video_url.'" class="efbl_vimeo_video"></iframe>';
											
											echo '<a href="#efblcf_holder" data-videolink="' .$story->source. '" data-storylink="'.$story_link.'"  data-linktext="'.__('Read full story', 'easy-facebook-likebox').'" data-caption="'.$post_plain_text.'" class="efbl_iframe_popup_video efbl_feed_popup"><span class="efbl_hover"></span></a>';
									}else{
										
										echo '<video src="'.$story->source.'" controls>
											  Your browser does not support HTML5 video.
											</video>';
											echo '<a href="#efblcf_holder" data-video="' .$story->source. '" data-storylink="'.$story_link.'"  data-linktext="'.__('Read full story', 'easy-facebook-likebox').'" data-caption="'.$post_plain_text.'" class="efbl_iframe_popup_video efbl_feed_popup"><span class="efbl_hover"></span></a>';
									}
								 
									
							echo '</div>';		
						}
						
						echo '<div class="efbl_post_content">';
							
							//Author information
							echo '<div class="efbl_author_info">';
							
							if($show_logo == 1){
								echo '<div class="efbl_auth_logo">'
											.$author_image.
										'</div>';	
							}
							
							echo 	'<div class="efbl_name_date">
											
											<p class="efbl_author_name"> <a href="https://facebook.com/'.$page_id.'" >'	
												.$story->from->name.
											'</a></p>
											
											<p class="efbl_story_time">'
												.$time.
											'</p>
											
									</div>
									 
								</div>';
							 
							//Story content
							$story_content = '<p class="efbl_story_text">'.($post_text).'</a>';
							
							if ( ( !empty($story->description) and $feed_type != 'link') ) {
								 
								$story_description = $story->description;
								$story_content .= '<p class="story_description">'.$story_description.'</p>';
								
							}
							
							if( $feed_type == 'link'){
								
								if( $story->picture){
									$link_image = 'efbl_has_link_image';
								}else{
									$link_image = 'efbl_no_link_image';	
								}
								
								$story_content .= '<div class="efbl_shared_story '.$link_image.' ">';
									
									if($story->picture)
										$story_content .= '<a href="'.$story->link.'" class="efbl_link_image" re="nofollow" target="_blank"><img src="'.$story->picture.'" /></a>';
									
									$story_content .= '<div class="efbl_link_text">';
										$story_content .= '<p class="efbl_title_link"><a href="'.$story->link.'" targe="_blank">'.$story->name.'</a></p>';
										
										$story_content .= '<p class="efbl_link_caption">'.$story->caption.'</p>';
										
										$story_content .= '<p class="efbl_link_description">'.$story->description.'</p>';
									$story_content .= '</div>';
									
								
								$story_content .= '</div>';
				
							}
					 
							echo '<div class="efbl_content_wraper">'.nl2br($story_content).'</div>';
							
								
						//end post content	
						echo '</div>';
						
						/*echo "<pre>";
						print_r($story);
						exit;*/
						//Story meta
						
							echo '<div class="efbl_story_meta">';
								//do not show whole container if none of these available
								if($efbl_likes_count > 0 || $story->shares->count > 0 || $efbl_comments_count > 0) {
									echo	'<div class="efbl_info">';
										 
										if($efbl_likes_count > 0){
											echo	'<span class="efbl_likes">
														<span class="efbl_like_text"><i class="fa fa-thumbs-o-up"></i></span>
														<span class="efbl_likes_counter"> '.$efbl_likes_count.' </span>												
													</span>';
										}
												
										if($story->shares->count > 0){		
											echo	'<span class="efbl_shares">
														<span class="efbl_shares_text"><i class="fa fa-share"></i></span> 
														<span class="efbl_shares_counter"> '.($story->shares->count).' </span>
													</span>';
										}
										
										if($efbl_comments_count > 0){	
											echo	'<span class="efbl_comments">
														<span class="efbl_comments_text"><i class="fa fa-comment-o"></i></span>
														<span class="efbl_comments_counter"> '.$efbl_comments_count.' </span>
													</span>';
										}
										
										echo	'</div>';
								}
								
								$read_more_text = __(apply_filters('efbl_read_more_text','Read full story'), 'easy-facebook-likebox');
								$share_this_text = __(apply_filters('efbl_share_text', 'Share'), 'easy-facebook-likebox');
								
								echo 	'<!--Readmore div started-->
										<div class="efbl_read_more_link">
											<a href="'.$story_link.'" target="_blank" class="efbl_read_full_story">'.$read_more_text.'</a> 									
											 
											<a href="javascript:void(0)" class="efbl_share_links">'.$share_this_text.'</a>
												
												<span class="efbl_links_container">
													<a class="efbl_facebook" href="https://www.facebook.com/sharer/sharer.php?u='.$story_link.'" target="_blank"><i class="fa fa-facebook"></i></a>
													
													<a class="efbl_twitter" href="https://twitter.com/intent/tweet?text='.$story_link.'" target="_blank"><i class="fa fa-twitter"></i></a>
													
													<a class="efbl_linked_in" href="https://www.linkedin.com/shareArticle?mini=true&url='.$story_link.'" target="_blank"><i class="fa fa-linkedin"></i></a>
													
													<a class="efbl_google_plus" href="https://plus.google.com/share?url='.$story_link.'" target="_blank"><i class="fa fa-google-plus"></i></a>
												</span>
												
										</div>
										<!--Readmore div end-->';
									
							
							 if(count($story->comments->data) > 0 || count($story->likes->data) > 0){
								 //Comments area started
								echo '<div class="efbl_comments_wraper">';
							 }
								
							
							if($efbl_likes_count > 0){
								
								$like_text = __(apply_filters('efbl_like_this_text','like this.'), 'easy-facebook-likebox');
								$and_text = __(apply_filters('efbl_and_text', 'and '), 'easy-facebook-likebox');
								$other_text = __(apply_filters('efbl_other_text', 'other '), 'easy-facebook-likebox');
								$others_text = __(apply_filters('efbl_others_text', 'others '), 'easy-facebook-likebox');
								
								echo '<div class="efbl_comments_header">';
										
								
										if( $efbl_likes_count == 1 ){
											
											echo '<a href="https://facebook.com/'.$story->likes->data[0]->id.'" target="_blank" rel="nofollow">' . $story->likes->data[0]->name . '</a> '.$like_text;																					
										}elseif( $efbl_likes_count == 2 ){
											echo '<a href="https://facebook.com/'.$story->likes->data[0]->id.'" target="_blank" rel="nofollow">' . $story->likes->data[0]->name . '</a> '.$and_text.'  <a href="https://facebook.com/'.$story->likes->data[1]->id.'" target="_blank" rel="nofollow">' . $story->likes->data[1]->name . '</a> '.$like_text;				
										}elseif( $efbl_likes_count == 3 ){
											
											echo '<a href="https://facebook.com/'.$story->likes->data[0]->id.'" target="_blank" rel="nofollow">' . $story->likes->data[0]->name . '</a>, 
											<a href="https://facebook.com/'.$story->likes->data[1]->id.'"  target="_blank" rel="nofollow">' . $story->likes->data[1]->name . '</a> 
											'.$and_text.' 1 '.$other_text.$like_text;
											
										}else{
											
											$efbl_others = $efbl_likes_count - 2;
											echo '<a href="https://facebook.com/'.$story->likes->data[0]->id.'" target="_blank" rel="nofollow">' . $story->likes->data[0]->name . '</a>, 
											<a href="https://facebook.com/'.$story->likes->data[1]->id.'"  target="_blank" rel="nofollow">' . $story->likes->data[1]->name . '</a> 
											'.$and_text.' '.$efbl_others.' '.$others_text.$like_text;
											
										}
										
								echo '</div>';
							}	
							
							 if( count($story->comments->data) > 0 ){
										
										$ci = 1;
										foreach ($story->comments->data as $comment){
											
											$comment_likes = $comment->like_count;
											$comment_message = htmlspecialchars($comment->message);
											$comment_time = efbl_time_ago($comment->created_time);
											
											//do not show more than 10 comments 
											if($ci == 5) break;
											
											echo '<div class="efbl_comments">';
											
													echo '<div class="efbl_commenter_image">';
													
															 echo '<a href="https://facebook.com/'. $comment->from->id .'" target="_blank" rel="nofollow"> 
																		<img src="https://graph.facebook.com/'.$comment->from->id.'/picture" width=32 height=32>
																	</a>';
													echo '</div>';
													
													echo '<div class="efbl_comment_text">';
															
															echo '<a class="efbl_comenter_name" href="https://facebook.com/'. $comment->from->id .'" target="_blank" rel="nofollow"> 
																		  '.$comment->from->name.'
																	</a>';
																	
															echo '<p class="efbl_comment_message">'.$comment_message.'</p>';
															
															
															echo '<p class="efbl_comment_time_n_likes">'; 
																
																if($comment_likes)
																	echo '<span class="efbl_comment_like"><i class="fa fa-thumbs-o-up"></i>&nbsp;'.$comment_likes.'</span> - ';
															
															echo '<span class="efbl_comment_time">'.$comment_time.'</spa> </p>';
															 
															
													echo '</div>'; //comments text
													
											echo '</div>';
										
										$ci++;
										}
										
									$comment_more_text = __(apply_filters('efbl_comment_on_text', 'comment on facebook'), 'easy-facebook-likebox');
									echo '<div class="efbl_comments_footer">
											<a href="'.$story_link.'" target="_blank" rel="nofollow"><i class="fa fa-comment-o"></i> '.$comment_more_text.' </a>
										</div>';	
										
								
								
								}
								
							if(count($story->comments->data) > 0 || count($story->likes->data) > 0){
							//Comments area ends here							
							echo '</div>';	
							}
									
							
							
							echo '</div>'; //Meta container ends here
								
						
				echo '</div>';
			//Incrrement the counter
			$i++;
		}
	
	//Display like box here if enabled
	if($show_like_box){
		
		echo '<div class="efbl_custom_likebox">'.do_shortcode('[efb_likebox fanpage_url="'.$page_id.'" box_width="250" box_height="" colorscheme="light" locale="en_US" responsive="0" show_faces="0" show_header="0" show_stream="0" show_border="0" ]').'</div>';
	}
	
	//prepare for inline popup
	echo '<div id="efblcf_holder" class="white-popup mfp-hide">
	
			<div class="efbl_popup_wraper">
			
				<div class="efbl_popup_left_container">	
				  <img src="" class="efbl_popup_image" />
				  <iframe src="" class="efbl_popup_if_video" ></iframe>
				  <video src="" class="efbl_popup_video" id="html_video" controls></video>
				</div>
				
				 <div class="efbl_popupp_footer">
				 </div>
				 
			</div>	 
				 
		</div>';
 	  	
	//End wraper
	echo '</div>';	
}
else
	_e(apply_filters('efbl_error_message', 'Error occured while retrieving the facebook feed'),  'easy-facebook-likebox');
