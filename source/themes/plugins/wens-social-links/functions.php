<?php


	add_shortcode('wen_social_links', 'wen_social_links');
	
		function wen_social_links($args=''){

			ob_start();

			global $options, $option_name;

			$options = get_option($option_name);		
			
			$title 		=	$options['widget_title'];

			$tooltip_option 	=	$options['tooltip'];

			if( 1 == $tooltip_option){
				
				$tooltip = $tooltip_option;

			} else {

				$tooltip = '';
			}			

			$rss 			=	esc_url($options['rsslink']);
			$facebook 		=	esc_url($options['facebooklink']);
			$twitter 		=	esc_url($options['twitterlink']);
			$gplus 			=	esc_url($options['gpluslink']);
			$linkedin 		=	esc_url($options['linkedinlink']);
			$pinterest 		=	esc_url($options['pinterestlink']);
			$instagram 		=	esc_url($options['instagramlink']);
			$digg 			=	esc_url($options['digglink']);
			$myspace 		=	esc_url($options['myspacelink']);
			$tumblr 		=	esc_url($options['tumblrlink']);
			$flickr 		=	esc_url($options['flickrlink']);
			$reddit 		=	esc_url($options['redditlink']);
			$dribbble 		=	esc_url($options['dribbblelink']);
			$blogger 		=	esc_url($options['bloggerlink']);	
			$stackoverflow 	=	esc_url($options['stackoverflowlink']);	
			$yahoo 			=	esc_url($options['yahoolink']);	
			$skype 			=	esc_url($options['skypelink']);
			$paypal 		=	esc_url($options['paypallink']);
			$youtube 		=	esc_url($options['youtubelink']);
			$vimeo 			=	esc_url($options['vimeolink']);
			$dailymotion 	=	esc_url($options['dailymotionlink']);
			$netflix		=	esc_url($options['netflixlink']);
			?>
	       
	        <div class="wen-side-socials">
	      
	   			<?php if( !empty($title) ) { ?>

	        		<h2><?php echo $title; ?></h2>

	        	<?php } ?>
	        	
	            <ul class="wen-social-links">
	            	
	            	<?php if(!empty($rss)){ ?> 
	            		<li><a class="wen-side-rss" href="<?php echo $rss; ?>" target="_blank" <?php if ( $tooltip ): echo 'title="rss"'; endif; ?>>RSS</a></li>
	                 <?php } ?>
	                 <?php if(!empty($facebook)){ ?> 
	            		<li><a class="wen-side-facebook" href="<?php echo $facebook; ?>" target="_blank" <?php if ( !empty($tooltip) ): echo 'title="facebook"'; endif; ?>>FACEBOOK</a></li>
	                 <?php } ?>
	                  <?php if(!empty($twitter)){ ?> 
	            		<li><a class="wen-side-twitter" href="<?php echo $twitter; ?>" target="_blank" <?php if ( !empty($tooltip) ): echo 'title="twitter"'; endif; ?>>TWITTER</a></li>
	                 <?php } ?>
	                  <?php if(!empty($gplus)){ ?> 
	            		<li><a class="wen-side-gplus" href="<?php echo $gplus; ?>" target="_blank" <?php if ( !empty($tooltip) ): echo 'title="google+"'; endif; ?>>GOOGLE</a></li>
	                 <?php } ?>
	                 <?php if(!empty($linkedin)){ ?> 
	            		<li><a class="wen-side-linkedin" href="<?php echo $linkedin; ?>" target="_blank" <?php if ( !empty($tooltip) ): echo 'title="linkedin"'; endif; ?>>LINKEDIN</a></li>
	                 <?php } ?>
	                 <?php if(!empty($pinterest)){ ?> 
	            		<li><a class="wen-side-pinterest" href="<?php echo $pinterest; ?>" target="_blank" <?php if ( !empty($tooltip) ): echo 'title="pinterest"'; endif; ?>>PINTEREST</a></li>
	                 <?php } ?>
                      <?php if(!empty($instagram)){ ?> 
                 		<li><a class="wen-side-instagram" href="<?php echo $instagram; ?>" target="_blank" <?php if ( !empty($tooltip) ): echo 'title="instagram"'; endif; ?>>INSTAGRAM</a></li>
                      <?php } ?>
	                 <?php if(!empty($digg)){ ?> 
	            		<li><a class="wen-side-digg" href="<?php echo $digg; ?>" target="_blank" <?php if ( !empty($tooltip) ): echo 'title="digg"'; endif; ?>>DIGG</a></li>
	                 <?php } ?>
	                 <?php if(!empty($myspace)){ ?> 
	            		<li><a class="wen-side-myspace" href="<?php echo $myspace; ?>" target="_blank" <?php if ( !empty($tooltip) ): echo 'title="myspace"'; endif; ?>>MYSPACE</a></li>
	                 <?php } ?>
	                  <?php if(!empty($tumblr)){ ?> 
	            		<li><a class="wen-side-tumblr" href="<?php echo $tumblr; ?>" target="_blank" <?php if ( !empty($tooltip) ): echo 'title="tumblr"'; endif; ?>>TUMBLR</a></li>
	                 <?php } ?>
	                 <?php if(!empty($flickr)){ ?> 
	            		<li><a class="wen-side-flickr" href="<?php echo $flickr; ?>" target="_blank" <?php if ( !empty($tooltip) ): echo 'title="flickr"'; endif; ?>>FLICKR</a></li>
	                 <?php } ?>
	                 <?php if(!empty($reddit)){ ?> 
	            		<li><a class="wen-side-reddit" href="<?php echo $reddit; ?>" target="_blank" <?php if ( !empty($tooltip) ): echo 'title="reddit"'; endif; ?>>REDDIT</a></li>
	                 <?php } ?>
	                 <?php if(!empty($dribbble)){ ?> 
	            		<li><a class="wen-side-dribbble" href="<?php echo $dribbble; ?>" target="_blank" <?php if ( !empty($tooltip) ): echo 'title="dribbble"'; endif; ?>>DRIBBBLE</a></li>
	                 <?php } ?>
	                 <?php if(!empty($blogger)){ ?> 
	            		<li><a class="wen-side-blogger" href="<?php echo $blogger; ?>" target="_blank" <?php if ( !empty($tooltip) ): echo 'title="blogger"'; endif; ?>>BLOGGER</a></li>
	                 <?php } ?>
	                 <?php if(!empty($stackoverflow)){ ?> 
	            		<li><a class="wen-side-stackoverflow" href="<?php echo $stackoverflow; ?>" target="_blank" <?php if ( !empty($tooltip) ): echo 'title="stackoverflow"'; endif; ?>>STACKOVERFLOW</a></li>
	                 <?php } ?>
	                 <?php if(!empty($yahoo)){ ?> 
	            		<li><a class="wen-side-yahoo" href="<?php echo $yahoo; ?>" target="_blank" <?php if ( !empty($tooltip) ): echo 'title="yahoo"'; endif; ?>>YAHOO</a></li>
	                 <?php } ?>
	                 <?php if(!empty($skype)){ ?> 
	            		<li><a class="wen-side-skype" href="<?php echo $skype; ?>" target="_blank" <?php if ( !empty($tooltip) ): echo 'title="skype"'; endif; ?>>SKYPE</a></li>
	                 <?php } ?>
	                 <?php if(!empty($paypal)){ ?> 
	            		<li><a class="wen-side-paypal" href="<?php echo $paypal; ?>" target="_blank" <?php if ( !empty($tooltip) ): echo 'title="paypal"'; endif; ?>>PAYPAL</a></li>
	                 <?php } ?>
	                 <?php if(!empty($youtube)){ ?> 
	            		<li><a class="wen-side-youtube" href="<?php echo $youtube; ?>" target="_blank" <?php if ( !empty($tooltip) ): echo 'title="youtube"'; endif; ?>>YOUTUBE</a></li>
	                 <?php } ?>
	                 <?php if(!empty($vimeo)){ ?> 
	            		<li><a class="wen-side-vimeo" href="<?php echo $vimeo; ?>" target="_blank" <?php if ( !empty($tooltip) ): echo 'title="vimeo"'; endif; ?>>VIMEO</a></li>
	                 <?php } ?>
	                 <?php if(!empty($dailymotion)){ ?> 
	            		<li><a class="wen-side-dailymotion" href="<?php echo $dailymotion; ?>" target="_blank" <?php if ( !empty($tooltip) ): echo 'title="dailymotion"'; endif; ?>>DAILYMOTION</a></li>
	                 <?php } ?>
	                 <?php if(!empty($netflix)){ ?> 
	            		<li><a class="wen-side-netflix" href="<?php echo $netflix; ?>" target="_blank" <?php if ( !empty($tooltip) ): echo 'title="netflix"'; endif; ?>>NETFLIX</a></li>
	                 <?php } ?>
	            </ul>
	        </div>
	        <?php

	        return ob_get_clean();
		}

	add_action( 'wp_head', 'wen_custom_style');

	function wen_custom_style() {

		global $options, $option_name;

		$options = get_option($option_name);
	    
		if ( isset ($options['customstyle']) &&  ($options['customstyle']!="") ) {

			$style = '<style type="text/css">'."\n";

			$style .= $options['customstyle'] . "\n";

			$style .= '</style>'."\n";

			echo $style;
		}
	    
	}

	add_filter('widget_text', 'do_shortcode');
