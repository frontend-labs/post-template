<?php
/*
Plugin Name: Tweet, Like, Google +1 and Share
Plugin URI: http://techxt.com/tweet-like-google-1-and-share-plugin-wordpress/
Author: Sudipto Pratap Mahato
Version: 1.7.6
Description: Most simple social share icons. 99% of your blog post will be shared by these Social share icons.
Requires at least: 3.0
Tested up to: 3.9.2
*/

global $s4pexcerpt;
global $btnarrver;
$s4mainloop =FALSE;

function disp_social($content) {
	global $post,$s4pexcerpt;
	global $btnarrver;
	global $s4mainloop;
	global $wp_query;
	if (get_option('s4dmob', false )==true && social4i_check_mobile())return $content;
	if($s4mainloop==FALSE)return $content;
	
	$metapl=s4_get_custom_field( 's4_url2s' );
	$metattl=s4_get_custom_field( 's4_ctitle' );
	if($metapl=='')$plink = get_permalink($post->ID);else $plink=$metapl;
	if($metattl=='')$ptitle = get_the_title($post->ID);else $ptitle = $metattl;
	
	$eplink = urlencode($plink);
	$disps4=0;
	$abvcnt=0;
	$belcnt=0;
	$expostid=str_replace(' ','',get_option('s4excludeid',''));
	$expostcat=str_replace(' ','',get_option('s4excludecat',''));
	$clang=get_option( 's4fblikelang', 'en_US' );
	if($expostid!=''){
		$pids=explode(",",$expostid);
		if (in_array($post->ID, $pids)) {
	    		return $content;
		}
		$psttype=get_post_type($post->ID);
		if (in_array($psttype, $pids)&&$psttype!==flase) {
	    		return $content;
		}
		$pstfrmt=get_post_format($post->ID);
		if (in_array($pstfrmt, $pids)&&$pstfrmt!==false) {
	    		return $content;
		}
	}
	if($expostcat!=''){
		$pcat=explode(",",$expostcat);
		if (in_category($pcat)) {
	    		return $content;
		}
	}
	$twsc='<script type="text/javascript" src="https://platform.twitter.com/widgets.js"></script>';
	$flsc='<script type="text/javascript" src="//connect.facebook.net/'.$clang.'/all.js#xfbml=1"></script>';
	$gpsc='<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>';
	$fssc='';
	if (get_option( 's4optimize', true ) == true){
	$twsc='';
	$flsc='';
	$gpsc='';
	$fssc='';
	}
	if(is_single()&&get_option( 's4onpost', true ) == true){
		$disps4=1;
		if (get_option( 's4pabovepost', true ) == true)$abvcnt=1;
		if (get_option( 's4pbelowpost', false ) == true)$belcnt=1;
	}
	if(is_page()&&get_option( 's4onpage', true ) == true){
		$disps4=1;
		if (get_option( 's4pgabovepost', true ) == true)$abvcnt=1;
		if (get_option( 's4pgbelowpost', false ) == true)$belcnt=1;
	}
	if(is_home()&&get_option( 's4onhome', false ) == true){
		$disps4=1;
		if (get_option( 's4habovepost', true ) == true)$abvcnt=1;
		if (get_option( 's4hbelowpost', false ) == true)$belcnt=1;
	}
	if((is_archive()||is_search())&&get_option( 's4onarchi', false ) == true){
		$disps4=1;
		if (get_option( 's4aabovepost', true ) == true)$abvcnt=1;
		if (get_option( 's4abelowpost', false ) == true)$belcnt=1;
	}
	
	if ($disps4==1){
		$size=get_option( 's4iconsize', 'large' );
		$align=get_option( 's4iconalign', 'left' );
		if($align=="left")$align="align-left";
		if($align=="right")$align="align-right";
		if($align=="floatl")$align="float-left";
		if($align=="floatr")$align="float-right";
		$sharelinks=display_social4i($size,$align);
		$sharelinks2='';
		if((is_single()||is_page())&&get_option( 's4dispvb', false ) == true)
		{
			if(get_option( 's4vremove', false ) == false)$sln='<div class="sharelink"><small><a href="http://techxt.com/?" target="_blank" style="color:#aaa;font: 10px arial;">share</a></small></div>';else $sln='';
			$sharelinks2='<div id="social4ivtop"> </div><div class="social4iv" id="social4iv"><div class="social4inv">'.s4_arrange_btns($btnarrver).'</div>'.$sln.'<div style="clear:both"></div></div>';
			$content=$sharelinks2.$content.'<div id="social4ivbottom"> </div>';
		}
		if ($abvcnt==1)	$content=$sharelinks.$content;
		if ($belcnt==1)$content=$content.$sharelinks;
	}
	return $content;
}
function s4_main_loop_test($query) {
  global $wp_the_query;
  global $s4mainloop;
  if ($query === $wp_the_query) {
    $s4mainloop=TRUE;
  }else{ $s4mainloop=FALSE;}
}
add_action('loop_start', 's4_main_loop_test');
function s4load_script()
{
	$clang=get_option( 's4fblikelang', 'en_US' );
	$flsc='';
	if(get_option('s4nofbjava',false)==false)$flsc='<script type="text/javascript" src="//connect.facebook.net/'.$clang.'/all.js#xfbml=1"></script>';
	$r='';
	if(get_option('s4allscripts',true)== true){
	$r='<script type="text/javascript" src="https://platform.twitter.com/widgets.js"></script>'.$flsc.'<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script><script type="text/javascript" src="https://platform.linkedin.com/in.js"></script>';
	}
	else
	{
			if(get_option('s4_twitter','1'))$r.='<script type="text/javascript" src="https://platform.twitter.com/widgets.js"></script>';
			if(get_option('s4_fblike','1')||get_option('s4_fbshare','1'))$r.=$flsc;
			if(get_option('s4_plusone','1'))$r.='<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>';
			if(get_option('s4_linkedin',false))$r.='<script type="text/javascript" src="https://platform.linkedin.com/in.js"></script>';
			
			
	}
	return $r;
}

function social4i_css() {
	echo "<!-- This site is powered by Tweet, Like, Plusone and Share Plugin - http://techxt.com/tweet-like-google-1-and-share-plugin-wordpress/ -->\n";
	s4_fb_share_thumb();
	if (get_option('s4dmob', false )==true && social4i_check_mobile())return;
	wp_enqueue_script('jquery');
	echo '<style type="text/css">div.socialicons{float:left;display:block;margin-right: 10px;line-height: 1;}div.socialiconsv{line-height: 1;}div.socialicons p{margin-bottom: 0px !important;margin-top: 0px !important;padding-bottom: 0px !important;padding-top: 0px !important;}div.social4iv{background: none repeat scroll 0 0 #FFFFFF;border: 1px solid #aaa;border-radius: 3px 3px 3px 3px;box-shadow: 3px 3px 3px #DDDDDD;padding: 3px;position: fixed;text-align: center;top: 55px;width: 68px;display:none;}div.socialiconsv{padding-bottom: 5px;}</style>'."\n";
	if(get_option('s4optimize',true)==true&&get_option( 's4scripthead', 'head' ) == "head" )
	echo s4load_script();
	$ccss=get_option('s4ccss','');
	$cjscript=get_option('s4cjscript','');
	if(trim($cjscript!=''))echo $cjscript;
	if(trim($ccss!=''))echo '<style type="text/css">'.$ccss.'</style>';

}
function social4i_foot()
{
?>
	<div id="fb-root"></div>
	<?php
	if (get_option('s4dmob', false )==true && social4i_check_mobile())return;
		if(get_option('s4optimize',true)==true&&get_option( 's4scripthead', 'head' ) == "foot" )
			echo s4load_script();
	if((is_single()||is_page())&&get_option( 's4dispvb', false ) == true){
	?>
	<script type="text/javascript">
	 var offset = jQuery("#social4ivtop").offset();
            var offset1 = jQuery("#social4ivbottom").offset();
            var lmargin=<?php echo get_option('s4vbmargin',100); ?>;
            var tmargin=<?php echo get_option('s4vbmargintop',20); ?>;
            var vhide=<?php if(get_option('s4vbhide','vbhide')=="vbhide") echo 1;else echo 0; ?>;
	    var avis=<?php if(get_option('s4vbavis','true')=="true") echo 1;else echo 0; ?>;
            var is_vis=true;
            var is_dis=true;
            jQuery("#social4iv").appendTo("body");
	    if(avis)
	    {
            	if(jQuery(window).scrollTop()<offset.top )
           	 	{jQuery("#social4iv").css("top",offset.top-jQuery(window).scrollTop());}
           	 else
           	 	{jQuery("#social4iv").css("top",tmargin);}
           	 jQuery("#social4iv").fadeIn();
           }
            jQuery("#social4iv").css("left",offset.left-lmargin);
            jQuery(window).scroll(function() {
		 offset = jQuery("#social4ivtop").offset();
           	 offset1 = jQuery("#social4ivbottom").offset();
           	 if(jQuery(window).scrollTop()<offset.top-tmargin)
           	 	{jQuery("#social4iv").css("top",offset.top-jQuery(window).scrollTop());}
           	 else
           	 	{jQuery("#social4iv").css("top",tmargin);}
           	 if(jQuery(window).scrollTop()<offset1.top-tmargin || vhide==0)is_dis=true;else is_dis=false;
                if ((jQuery(window).scrollTop() > offset.top||avis) && is_dis && is_vis) 
                {
		    jQuery("#social4iv").fadeIn();
                } else {
                    jQuery("#social4iv").fadeOut();
                }
            });
            jQuery(window).resize(function() {
            	offset = jQuery("#social4ivtop").offset();
            	offset1 = jQuery("#social4ivbottom").offset();
            	var off=jQuery("#social4iv").offset();
            	jQuery("#social4iv").css("left",offset.left-lmargin);
		if(offset.left-lmargin<0)is_vis=false; else is_vis=true;
		 if(jQuery(window).scrollTop()<offset1.top || vhide==0)is_dis=true;else is_dis=false;
                if (jQuery(window).scrollTop() > offset.top && is_dis && is_vis) 
                {
		    jQuery("#social4iv").fadeIn();
                } else {
                    jQuery("#social4iv").fadeOut();
                }
	    });
	</script>
<?php
}
if(get_option('s4analytics',false)==false)return;		
?>
	<script src="//analytics-api-samples.googlecode.com/svn/trunk/src/tracking/javascript/v5/social/ga_social_tracking.js"></script>
	<script>
 		 _ga.trackSocial();
	</script>
	
	
<?php
}
function s4_get_first_image() {
	global $post, $posts;
	$first_img = '';
	ob_start();
	ob_end_clean();
	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	$first_img = $matches[1][0];
	return $first_img;
}
function s4_fb_share_thumb()
{
	global $post, $posts;
	if(get_option('s4nometa',false)==true)return;
	$metaimg=s4_get_custom_field( 's4_image2s' );
	if($metaimg=='')$thumb = false;else $thumb = $metaimg;
	if(function_exists('get_post_thumbnail_id')&&function_exists('wp_get_attachment_image_src')&&$thumb==false)
	{
		$image_id = get_post_thumbnail_id();
		$image_url = wp_get_attachment_image_src($image_id,'large');
		$thumb = $image_url[0];
	}
	if($thumb=='')$thumb=s4_get_first_image();
	$default_img = get_option('s4defthumb',''); 
	if ( $thumb == false || $thumb=='') 
		$thumb=$default_img; 
	
	if(is_single() || is_page()) { 
	$metades=s4_get_custom_field( 's4_cdes' );
	$metattl=s4_get_custom_field( 's4_ctitle' );
	$metapl=s4_get_custom_field( 's4_url2s' );
	if($metades=='')$desc = "";else $desc = $metades;
	if($metattl=='')$ptitle = get_the_title($post->ID);else $ptitle = $metattl;
	if($metapl=='')$plink = get_permalink($post->ID);else $plink=$metapl;
	if($desc==""){
		if (has_excerpt($post->ID)) {
			$desc = esc_attr(strip_tags(get_the_excerpt($post->ID)));
		}else{
			$desc = esc_attr(str_replace("\r\n",' ',substr(strip_tags(strip_shortcodes($post->post_content)), 0, 160)));
		}
	}
	if(trim($desc)=="")$desc=get_the_title('');
	?>
		<meta property="og:type" content="article" />
		<meta property="og:title" content="<?php echo $ptitle; ?>" />
		<meta property="og:url" content="<?php echo $plink; ?>"/>
		<meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
		<meta property="og:description" content="<?php echo $desc; ?>"/>
		<!--[if lt IE 9]>
	  <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
		<?php if(trim($thumb)!=''){ ?>
			<meta property="og:image" content="<?php echo $thumb; ?>" />
		<?php } ?>
	<?php  } else { ?>
		<meta property="og:type" content="article" />
	  	<meta property="og:title" content="<?php bloginfo('name'); ?>" />
		<meta property="og:url" content="<?php bloginfo('url'); ?>"/>
		<meta property="og:description" content="<?php bloginfo('description'); ?>" />
		<meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
		<!--[if lt IE 9]>
	  <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
		<?php if(trim($default_img)!=''){ ?>
			<meta property="og:image" content="<?php echo $default_img; ?>" />
		<?php } ?>
	<?php  } 

}

function disp_social_on_optionpage()
{
	$fblplink = "http://wordpress.org/extend/plugins/only-tweet-like-share-and-google-1/";
	$fblplink2 = "http://techxt.com/";
	$plink = "http://techxt.com/tweet-like-google-1-and-share-plugin-wordpress/";
	$eplink = urlencode($plink);
	$ptitle = "Check out this cool Social Share Plugin for Wordpress";
	$sharelinks='<div id="social4i" style="position: relative; display: block;">';
	$clang=get_option( 's4fblikelang', 'en_US' ); 
	if(get_option('s4_twitter','1')){
	if (get_option( 's4iconsize', 'large' ) == "large" )$tp="vertical"; else $tp="horizontal";
	$sharelinks.= '<script src="//connect.facebook.net/'.$clang.'/all.js#xfbml=1"></script><div class=socialicons style="float:left;margin-right: 10px;"><a href="https://twitter.com/share" data-url="'.$plink.'" data-counturl="'.$plink.'" data-text="'.$ptitle.'" class="twitter-share-button" data-count="'.$tp.'">Tweet</a><script type="text/javascript" src="https://platform.twitter.com/widgets.js"></script></div>';
	}
	if(get_option('s4_fblike','1')){
	if(get_option('s4_fbsend',false)==true)$snd="true"; else $snd="false";
	if (get_option( 's4iconsize', 'large' ) == "large" )
		$tp=' layout="box_count" width="55" height="62" ';
	else 
		$tp=' layout="button_count" width="100" height="21" ';
		
	$sharelinks.= '<div class=socialicons style="float:left;margin-right: 10px;"><fb:like href="'.$fblplink.'" send="'.$snd.'"'.$tp.'show_faces="false" font=""></fb:like></div>';
	}
	if(get_option('s4_plusone','1')){
	if (get_option( 's4iconsize', 'large' ) == "large" )$tp="tall"; else $tp="medium";
	$sharelinks.='<div class="socialicons" style="float:left;margin-right: 10px;"><script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script><div class="g-plusone" data-size="'.$tp.'" data-href="'.$fblplink2.'"></div></div>';
	}
	if(get_option( 's4_linkedin', false )){
	if (get_option( 's4iconsize', 'large' ) == "large" )$tp="top"; else $tp="right";
	$sharelinks.='<div class="socialicons" style="float:left;margin-right: 10px;"><script type="text/javascript" src="https://platform.linkedin.com/in.js"></script><script type="in/share" data-url="'.$eplink.'" data-counter="'.$tp.'"></script></div>';
	}
	if(get_option('s4_fbshare','1')){
	if (get_option( 's4iconsize', 'large' ) == "large" )
	{
		$tp="box_count";
		$cs1="height:60px;";
		$cs2='style="position: absolute; bottom: 0pt;"';
	} else $tp="button_count";
	$sharelinks.= '<div class=socialicons style="position: relative;'.$cs1.'float:left;margin-right: 10px;"><div '.$cs2.'><div class="fb-share-button" data-href="'.$plink.'" data-width="450" data-type="'.$tp.'"></div></div></div>';
	}
	$sharelinks.= '<div style="clear:both"></div></div>';
	echo $sharelinks;
}

function s4_order_check($ord)
{
	$ord=str_replace(' ','',$ord);
	$orddefa= array(1,2,3,4,5,6);
	$ordarr=array_unique(explode(",",$ord));
	if(!is_array($ordarr))$ordarr=$orddefa;
	foreach($ordarr as $key=>&$value)
	{
            if($value!=1&&$value!=2&&$value!=3&&$value!=4&&$value!=5&&$value!=6)
            {
              unset($ordarr[$key]);
            }
        }
        $ordarr=array_unique(array_merge($ordarr, $orddefa));
	$ord=implode(",",$ordarr);
return $ord;
}

function social4ioptions(){
?>
	<h2>Tweet, Like, Share and Google +1 Option Page</h2>
	Like this Plugin then why not hit the like button. Your like will motivate me to enhance the features of the Plugin :)<br />
	<iframe style="overflow: hidden; width: 450px; height: 35px;" src="http://www.facebook.com/plugins/like.php?app_id=199883273397074&amp;href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FTech-XT%2F223482634358279&amp;send=false&amp;layout=standard&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=35" width="320" height="35"></iframe><br />And if you are too generous then you can always <b>DONATE</b> by clicking the donation button.<br/>If you like the plugin then <b>write a review</b> of it pointing out the plus and minus points.<br /><a href="http://techxt.com/tweet-like-google-1-and-share-plugin-wordpress/" TARGET='_blank'>Click here</a> for <b>Reference on using shortcode/Function</b> or if you want to <b>report a bug</b>. 
	<table class="form-ta">	
	<tr valign="top">
	<td width="73%">
	<form method="post" action="options.php">
	<h3>Test Buttons</h3>
	<?php disp_social_on_optionpage(); ?>
	
	<h3 style="color: #cc0000;">Increase Page Load Speed</h3>
	<p>Note: After using this option if the buttons do not get displayed properly then uncheck it</p>
	<p><input type="checkbox" name="s4optimize" id="s4optimize" value="true"<?php if (get_option( 's4optimize', true ) == true) echo ' checked'; ?>>Optimize the script for faster loading</p>
	
	&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="s4scripthead" value="head" id="s4scripthead1"<?php if (get_option( 's4scripthead', 'head' ) == "head" ) echo ' checked'; ?>></input><label for="s4scripthead">Place Script in the Header&nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input type="radio" name="s4scripthead" value="foot" id="s4scripthead2"<?php if (get_option( 's4scripthead', 'head' ) == "foot" ) echo ' checked'; ?>></input><label for="s4scripthead">Place Script in the Footer</label>
	
	<p>Keep this option checked if you are using Shortcode or PHP function to display the buttons<br/><input type="checkbox" name="s4allscripts" id="s4allscripts" value="true"<?php if (get_option( 's4allscripts', true ) == true) echo ' checked'; ?>>Load all scripts</p>
	
	<h3 style="color: #cc0000;">Select Icons to display</h3>
	<p><b>1 </b><input type="checkbox" name="s4_twitter" id="s4-twitter" value="true"<?php if (get_option( 's4_twitter', true ) == true) echo ' checked'; ?>> Display Twitter&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;via @<input type="text" name="s4twtvia" style="width: 150px;" value="<?php echo get_option('s4twtvia',''); ?>" /></p>
	<p><b>2 </b><input type="checkbox" name="s4_fblike" id="s4-fblike" value="true"<?php if (get_option( 's4_fblike', true ) == true) echo ' checked'; ?>> Display Facebook Like&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="s4_fbsend" id="s4-fbsend" value="true"<?php if (get_option( 's4_fbsend', false ) == true) echo ' checked'; ?>> Display Facebook Send </p>
	<p>&nbsp;&nbsp;&nbsp;&nbsp;Select Facebook Like Language <?php s4_lang_disp(); ?> </p>
	<p><b>3 </b><input type="checkbox" name="s4_plusone" id="s4-plusone" value="true"<?php if (get_option( 's4_plusone', true ) == true) echo ' checked'; ?>> Display Google +1 </p>
	<p><b>4 </b><input type="checkbox" name="s4_fbshare" id="s4-fbshare" value="true"<?php if (get_option( 's4_fbshare', true ) == true) echo ' checked'; ?>> Display Facebook Share </p>
	<p><b>5 </b><input type="checkbox" name="s4_linkedin" id="s4_linkedin" value="true"<?php if (get_option( 's4_linkedin', false ) == true) echo ' checked'; ?>> Display Linkedin </p>
	<p><b>6 </b><input type="checkbox" name="s4_cbtn" id="s4_cbtn" value="true"<?php if (get_option( 's4_cbtn', false ) == true) echo ' checked'; ?>> Display Custom Buttons </p>
	<p><b>Display order</b> <input type="text" name="s4dispord" style="width: 300px;" value="<?php echo s4_order_check(get_option('s4dispord','1,2,3,4,5,6')); ?>" /> <br/>To arrange icons reorder the ID associated with the buttons here separated with comma</p>
	<p><b>Default Thumbnail URL</b> <input type="text" name="s4defthumb" style="width: 300px;" value="<?php echo get_option('s4defthumb',''); ?>" /></p>
	
	<h3 style="color: #cc0000;">Size of Icons</h3>
	<input type="radio" name="s4iconsize" value="large" id="s4iconsize1"<?php if (get_option( 's4iconsize', 'large' ) == "large" ) echo ' checked'; ?>></input><label for="s4iconsize">Large&nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input type="radio" name="s4iconsize" value="small" id="s4iconsize2"<?php if (get_option( 's4iconsize', 'large' ) == "small" ) echo ' checked'; ?>></input><label for="s4iconsize">Small</label>
	
	<h3 style="color: #cc0000;">Alignment</h3>
	<input type="radio" name="s4iconalign" value="left" id="s4iconalign1"<?php if (get_option( 's4iconalign', 'left' ) == "left" ) echo ' checked'; ?>></input><label for="s4iconsize">Left Aligned&nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input type="radio" name="s4iconalign" value="right" id="s4iconalign2"<?php if (get_option( 's4iconalign', 'left' ) == "right" ) echo ' checked'; ?>></input><label for="s4iconsize">Right Aligned&nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input type="radio" name="s4iconalign" value="floatl" id="s4iconalign3"<?php if (get_option( 's4iconalign', 'left' ) == "floatl" ) echo ' checked'; ?>></input><label for="s4iconsize">Float Left&nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input type="radio" name="s4iconalign" value="floatr" id="s4iconalign3"<?php if (get_option( 's4iconalign', 'left' ) == "floatr" ) echo ' checked'; ?>></input><label for="s4iconsize">Float Right&nbsp;&nbsp;&nbsp;&nbsp;</label>
	
	<div style="padding-right: 10px; padding-bottom: 10px; padding-left: 10px; background: none repeat scroll 0% 0% rgb(219, 234, 249);">	
	<h3 style="color: #cc0000;">Vertical Scrolling sidebar</h3>
	<p><input type="checkbox" name="s4dispvb" id="s4dispvb" value="true"<?php if (get_option( 's4dispvb', false ) == true) echo ' checked'; ?>> <b>Display Vertical sidebar</b> </p>
	<p><b>Margin Left </b><input type="text" name="s4vbmargin" style="width: 50px;" value="<?php echo get_option('s4vbmargin',100); ?>" /> (Default 100)</p>
	<p><b>Margin Top </b><input type="text" name="s4vbmargintop" style="width: 50px;" value="<?php echo get_option('s4vbmargintop',20); ?>" /> (Default 20)</p>
	<p><input type="radio" name="s4vbavis" value="true" <?php if (get_option( 's4vbavis', 'true' ) == "true" ) echo ' checked'; ?>></input><label for="s4vbavis">Always Visible&nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input type="radio" name="s4vbavis" value="false" <?php if (get_option( 's4vbavis', 'true' ) == "false" ) echo ' checked'; ?>></input><label for="s4vbavis">Visible on scroll</label></p>
	<p><input type="radio" name="s4vbhide" value="vbhide" <?php if (get_option( 's4vbhide', 'vbhide' ) == "vbhide" ) echo ' checked'; ?>></input><label for="s4vbhide">Hide before comments block&nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input type="radio" name="s4vbhide" value="vbnohide" <?php if (get_option( 's4vbhide', 'vbhide' ) == "vbnohide" ) echo ' checked'; ?>></input><label for="s4vbhide">Display until end of the page</label></p><p>Note: Other customization such as <i>background-color, border-color, etc.</i> can be done using Custom CSS. Need help then post it in the <a target="_blank" href="http://techxt.com/plugin-support-forum/tweet-like-plusone-and-share-plugin/">Forum</a></p>
	</div>
	
	<h3 style="color: #cc0000;">Where to Display</h3>
	<p><input type="checkbox" name="s4onpost" id="s4onpost" value="true"<?php if (get_option( 's4onpost', true ) == true) echo ' checked'; ?>> <b>Display on Posts</b> </p>
	<div style="margin-left: 30px;">
	<p><input type="checkbox" name="s4pabovepost" id="s4abovepost" value="true"<?php if (get_option( 's4pabovepost', true ) == true) echo ' checked'; ?>> Display Above Content </p>
	<p><input type="checkbox" name="s4pbelowpost" id="s4belowpost" value="true"<?php if (get_option( 's4pbelowpost', false ) == true) echo ' checked'; ?>>Display Below Content</p>
	</div>
	<p><input type="checkbox" name="s4onpage" id="s4onpage" value="true"<?php if (get_option( 's4onpage', true ) == true) echo ' checked'; ?>><b>Display on Pages</b></p>
	<div style="margin-left: 30px;">
	<p><input type="checkbox" name="s4pgabovepost" id="s4abovepost" value="true"<?php if (get_option( 's4pgabovepost', true ) == true) echo ' checked'; ?>> Display Above Content </p>
	<p><input type="checkbox" name="s4pgbelowpost" id="s4belowpost" value="true"<?php if (get_option( 's4pgbelowpost', false ) == true) echo ' checked'; ?>>Display Below Content</p>
	</div>
	<p><input type="checkbox" name="s4onhome" id="s4onhome" value="true"<?php if (get_option( 's4onhome', false ) == true) echo ' checked'; ?>><b>Display on Home Page</b> </p>
	<div style="margin-left: 30px;">
	<p><input type="checkbox" name="s4habovepost" id="s4abovepost" value="true"<?php if (get_option( 's4habovepost', true ) == true) echo ' checked'; ?>> Display Above Content </p>
	<p><input type="checkbox" name="s4hbelowpost" id="s4belowpost" value="true"<?php if (get_option( 's4hbelowpost', false ) == true) echo ' checked'; ?>>Display Below Content</p>
	</div>
	<p><input type="checkbox" name="s4onarchi" id="s4onarchi" value="true"<?php if (get_option( 's4onarchi', false ) == true) echo ' checked'; ?>><b>Display on Archive Pages(Categories, Tages, Author etc.)</b></p>
	<div style="margin-left: 30px;">
	<p><input type="checkbox" name="s4aabovepost" id="s4abovepost" value="true"<?php if (get_option( 's4aabovepost', true ) == true) echo ' checked'; ?>> Display Above Content </p>
	<p><input type="checkbox" name="s4abelowpost" id="s4belowpost" value="true"<?php if (get_option( 's4abelowpost', false ) == true) echo ' checked'; ?>>Display Below Content</p>
	</div>
	<p><input type="checkbox" name="s4onexcer" id="s4onexcer" value="true"<?php if (get_option( 's4onexcer', true ) == true) echo ' checked'; ?>><b>Display on Excerpts</b></p>
	<p><input type="checkbox" name="s4onexcererr" id="s4onexcererr" value="true"<?php if (get_option( 's4onexcererr', false ) == true) echo ' checked'; ?>><b>Rectify display error on Excerpts</b> (check this only if the buttons are not getting displayed properly on excerpts)</p>
	
	<h3 style="color: #cc0000;">Mobile browsers</h3>
	<p><input type="checkbox" name="s4dmob" id="s4dmob" value="true"<?php if (get_option( 's4dmob', false ) == true) echo ' checked'; ?>><b>Disable on Mobile Browser</b><br /> Check this option if you have installed a mobile theme plugin like Wptouch, WordPress Mobile Pack etc.</p>
	
	<h3 style="color: #cc0000;">Don't display on Posts/Pages</h3>
	<p>Enter the <b>ID's</b> of those Pages/Posts separated by comma. e.g 13,5,87<br/>You can also include a <b>custom post types</b> or <b>custom post format</b> (all separated by comma)<br /> 
	<input type="text" name="s4excludeid" style="width: 300px;" value="<?php echo get_option('s4excludeid',''); ?>" /></p>
	
	<h3 style="color: #cc0000;">Don't display on Category</h3>
	<p>Enter the ID's of those Categories separated by comma. e.g 131,45,817<br/>
	<input type="text" name="s4excludecat" style="width: 300px;" value="<?php echo get_option('s4excludecat',''); ?>" /></p>
	
	<h3 style="color: #cc0000;">Insert Custom CSS</h3>
	<small>Your theme should have Call to wp_head() function</small><br />
	<p><textarea name="s4ccss" rows="10" cols="50" style="width:600px;"><?php echo stripslashes(htmlspecialchars(get_option('s4ccss',''))); ?></textarea></p>
	
	<h3 style="color: #cc0000;">Insert Custom JavaScript</h3>
	<small>Your theme should have Call to wp_head() function</small><br />
	<small>Add opening and closing script tags (&lt;script>...&lt;/script>)</small><br />
	<p><textarea name="s4cjscript" rows="10" cols="50" style="width:600px;"><?php echo stripslashes(htmlspecialchars(get_option('s4cjscript',''))); ?></textarea></p>
	
	<h3 style="color: #cc0000;">Add your own Custom Buttons</h3>
	<table>
	<tr><td>
	<p>
	To add more than one custom button, separate the buttons codes with the word <b>[BUTTON]</b><br />
	e.g {code of first button} [BUTTON] {code of second button}
	</p>
	<p>
	Following <b>Tags</b> that will be replace by actual codes when the buttons are displayed<br/>
	<b>%%URL%%</b> - The URL of the Post/Page<br/>
	<b>%%EURL%%</b> - The HTML encoded URL of the Post/Page<br/>
	<b>%%TITLE%%</b> - The Title of the Post/Page<br/>
	<b>%%ETITLE%%</b> - URL encoded Title of the Post/Page<br/>
	<b>%%DESC%%</b> - Description or Post Excerpts<br/>
	<b>%%EDESC%%</b> - URL encoded Description or Post Excerpts<br/>
	<b>%%PIMAGE%%</b> - Link to the Featured Image of the post or the first image if featured image not set.<br/>
	<b>%%COMMENTCOUNT%%</b> - Number of comments for the Post.<br/>
	</p>
	</td></tr>
	<tr><td>
	Place <b>Large button Code</b> in this box
	</td><td>
	Place <b>Small button Code</b> in this box
	</td></tr>
	<tr><td>
	<textarea name="s4cblarge" rows="10" cols="50" style="width:350px;"><?php echo stripslashes(htmlspecialchars(get_option('s4cblarge',''))); ?></textarea>
	</td><td>
	<textarea name="s4cbsmall" rows="10" cols="50" style="width:350px;"><?php echo stripslashes(htmlspecialchars(get_option('s4cbsmall',''))); ?></textarea>
	</td></tr>
	<tr><td>
	If you have successfully added a custom button to your site then please help others by posting the custom code in the <a target="_blank" href="http://techxt.com/plugin-support-forum/tweet-like-plusone-and-share-plugin/">Plugin Forum</a>.
	</td></tr>
	<tr><td>
	<h3 style="color: #cc0000;">Other options</h3>
	<p><input type="checkbox" name="s4vremove" id="s4vremove" value="true"<?php if (get_option( 's4vremove', false ) == true) echo ' checked'; ?>><b>Remove Share link</b><br/>Removes share link credit from Vertical sharebar</p>
	<p><input type="checkbox" name="s4nometa" id="s4nometa" value="true"<?php if (get_option( 's4nometa', false ) == true) echo ' checked'; ?>><b>Do not add Facebook OG META tags</b><br/>If some other plugin is adding the Facebook Meta tags</p>
	<p><input type="checkbox" name="s4nofbjava" id="s4nofbjava" value="true"<?php if (get_option( 's4nofbjava', false ) == true) echo ' checked'; ?>><b>Do not add Facebook Javascript</b><br/>If some other plugin is adding the javascript</p>
	<p><input type="checkbox" name="s4analytics" id="s4analytics" value="true"<?php if (get_option( 's4analytics', false ) == true) echo ' checked'; ?>><b>Add Google analytics button Tracking code</b><br/>Adds tracking code to track Facebook Like and Linkedin button clicks (under beta testing)</p>
	</td></tr>
	</table>
	
	
	<div style="clear:both"></div>
	<div id="btnsubmit">
	<input type="submit" class="button-primary" value="Save Changes"/>
	</div>
	<style>
	div#btnsubmit {
	     background: none repeat scroll 0 0 #444444;
	    border-radius: 10px 10px 0 0;
	    bottom: 0;
	    left: 800px;
	    padding: 7px 30px;
	    position: fixed;
	    z-index: 9999;
	}
	</style>
	<?php wp_nonce_field('update-options'); ?>
	<input type="hidden" name="page_options" value="s4pabovepost,s4pbelowpost,s4pgabovepost,s4pgbelowpost,s4habovepost,s4hbelowpost,s4aabovepost,s4abelowpost,s4_twitter,s4_fblike,s4_plusone,s4_fbshare,s4onpost,s4onpage,s4onhome,s4onarchi,s4iconsize,s4iconalign,s4excludeid,s4_fbsend,s4optimize,s4twtvia,s4excludecat,s4defthumb,s4onexcer,s4fblikelang,s4ccss,s4_linkedin,s4scripthead,s4allscripts,s4dmob,s4cblarge,s4cbsmall,s4_cbtn,s4dispord,s4onexcererr,s4nofbjava,s4nometa,s4analytics,s4vbhide,s4dispvb,s4vbmargin,s4vremove,s4vbavis,s4vbmargintop,s4cjscript">
	<input type="hidden" name="action" value="update" />
	</form>
	</td><td width="2%">&nbsp;</td><td width="25%"><a href="http://techxt.com/tweetlike_ad" target="_blank"><img src="http://techxt.com/tweetlike_ad.png" /></a><br/><b>Follow us on</b><br/><a href="http://twitter.com/techxt" target="_blank"><img src="http://a0.twimg.com/a/1303316982/images/twitter_logo_header.png" /></a><br/><a href="http://facebook.com/techxt" target="_blank"><img src="https://secure-media-sf2p.facebook.com/ads3/creative/pressroom/jpg/b_1234209334_facebook_logo.jpg" height="38px" width="118px"/></a><p></p><b>Feeds and News</b><br /><?php get_feeds_s4() ?>
	<p></p>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
	<input type="hidden" name="cmd" value="_donations">
	<input type="hidden" name="business" value="isudipto@gmail.com">
	<input type="hidden" name="lc" value="US">
	<input type="hidden" name="item_name" value="Tweet Like Share Plusone Plugin">
	<input type="hidden" name="no_note" value="0">
	<input type="hidden" name="currency_code" value="USD">
	<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
	<input type="image" src="https://www.paypalobjects.com/WEBSCR-640-20110401-1/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/WEBSCR-640-20110401-1/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>
	<br />Consider a Donation and remember $X is always better than $0
	</td></tr></table>
	<?php
}

add_action('wp_footer', 'social4i_foot',99);
add_action('wp_head', 'social4i_css');
add_filter('the_content', 'disp_social',1);
if (get_option( 's4onexcer', true ) == true)
	add_filter('the_excerpt', 'disp_social');
if (get_option( 's4onexcererr', false ) == true)
	add_filter('wp_trim_excerpt', 's4_wp_trim_excerpt');
add_action('admin_menu', 'socialicons_addmenu');
add_filter( 'wp_dashboard_widgets', 's4_widget_order');
function social4i_admin_widget(){
	get_feeds_s4_dash();
}
function social4i_add_admin_widget(){
    wp_add_dashboard_widget('social4i_admin_widget', 'News and Updates', 'social4i_admin_widget'); 
}
add_action('wp_dashboard_setup','social4i_add_admin_widget',5);

function s4_wp_trim_excerpt($content, $text = '')
{


    $raw_excerpt = $text;
    if ( '' == $text ) {

        $text = get_the_content('');
        $text = strip_shortcodes( $text );

        remove_filter('the_content', 'disp_social', 1); 
       
        $text = apply_filters('the_content', $text);

        add_filter('the_content', 'disp_social', 1);

        $text = str_replace(']]>', ']]&gt;', $text);
        $text = strip_tags($text);
        $excerpt_length = apply_filters('excerpt_length', 55); 
        $excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
        $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
        if ( count($words) > $excerpt_length ) {
            array_pop($words);
            $text = implode(' ', $words);
            $text = $text . $excerpt_more;
        } else {
            $text = implode(' ', $words);
        }
    	 return $text;
    }
    else
    {
        return $content;
    }
}
//original code by Yoast.com
function s4_widget_order( $arr ) {
	global $wp_meta_boxes;
	
	if ( is_admin() ) {
		if ( isset($wp_meta_boxes['dashboard']['normal']['core']['social4i_admin_widget']) ) {
			$social4i_admin_widget = $wp_meta_boxes['dashboard']['normal']['core']['social4i_admin_widget'];
			unset($wp_meta_boxes['dashboard']['normal']['core']['social4i_admin_widget']);
			if ( isset($wp_meta_boxes['dashboard']['side']['core']) ) {
				$begin = array_slice($wp_meta_boxes['dashboard']['side']['core'], 0, 1);
				$end = array_slice($wp_meta_boxes['dashboard']['side']['core'], 1, 5);
				$wp_meta_boxes['dashboard']['side']['core'] = $begin;
				$wp_meta_boxes['dashboard']['side']['core'][] = $social4i_admin_widget;
				$wp_meta_boxes['dashboard']['side']['core'] += $end;
			} else {
				$wp_meta_boxes['dashboard']['side']['core'] = array();
				$wp_meta_boxes['dashboard']['side']['core'][] = $social4i_admin_widget;
			}
		} 
	}
return $arr;
}
function get_feeds_s4() {
	include_once(ABSPATH . WPINC . '/feed.php');
	$rss = fetch_feed('http://feeds.feedburner.com/techxt');
	if (!is_wp_error( $rss ) ){
		$rss5 = $rss->get_item_quantity(5); 
		$rss1 = $rss->get_items(0, $rss5); 
	}
?>
<ul>
<?php if (!$rss5 == 0)foreach ( $rss1 as $item ){?>
<li style="list-style-type:circle">
<a target="_blank" href='<?php echo $item->get_permalink(); ?>'><?php echo $item->get_title(); ?></a>
</li>
<?php } ?>
</ul>
<?php
}
function get_feeds_s4_dash() {
	include_once(ABSPATH . WPINC . '/feed.php');
	$rss = fetch_feed('http://feeds.feedburner.com/techxtrack');
	if (!is_wp_error( $rss ) ){
		$rss5 = $rss->get_item_quantity(5); 
		$rss1 = $rss->get_items(0, $rss5); 
	}
	$rsss = fetch_feed('http://feeds.feedburner.com/uQuoted');
	if (!is_wp_error( $rsss ) ){
		$rsss5 = $rsss->get_item_quantity(1); 
		$rsss1 = $rsss->get_items(0, $rsss5); 
	}
	if(!$rsss5 == 0)foreach ( $rsss1 as $items ){?>
		Quote of the day<br/>
		<i>"<a target="_blank" href='<?php echo $items->get_permalink(); ?>'><?php echo $items->get_title(); ?></a>"</i>
		<p>Must read</p>
	<?php
	break;
	}
?>
<ol>
<?php if (!$rss5 == 0)foreach ( $rss1 as $item ){?>
<li>
<a target="_blank" href='<?php echo $item->get_permalink(); ?>'><?php echo $item->get_title(); ?></a>
</li>
<?php } ?>
</ol>
<?php
}
function socialicons_addmenu(){
	add_options_page("Tweet Like Share Plusone", "Tweet Like Plusone", "administrator", "social4i", "social4ioptions");
}
//===================================================================================//
function display_social4i($size,$align, $type = FALSE)
{
	global $post;
	global $btnarrver;
	$btnarr=array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'');
	$btnarrver=array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'');
	if($size=='')$size="large";
	if($align=='')$align="align-left";

	$metapl=s4_get_custom_field( 's4_url2s' );
	$metattl=s4_get_custom_field( 's4_ctitle' );
	if($metapl=='')$plink = get_permalink($post->ID);else $plink=$metapl;
	if($metattl=='')$ptitle = get_the_title($post->ID);else $ptitle = $metattl;

	$eplink = urlencode($plink);
	$eptitle=str_replace(array(">","<"),"",$ptitle);
	$via=get_option('s4twtvia','');
	$clang=get_option( 's4fblikelang', 'en_US' );
	$twsc='<script type="text/javascript" src="https://platform.twitter.com/widgets.js"></script>';
	$flsc='';
	if(get_option('s4nofbjava',false)==false)$flsc='<script type="text/javascript" src="//connect.facebook.net/'.$clang.'/all.js#xfbml=1"></script>';
	$gpsc='<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>';
	$fssc='';
	$lnsc='<script type="text/javascript" src="https://platform.linkedin.com/in.js"></script>';
	if (get_option( 's4optimize', true ) == true){
	$twsc='';
	$flsc='';
	$gpsc='';
	$fssc='';
	$lnsc='';
	}
	
	if ($size == "large" ){
		if(get_option('s4_fbsend',false)==true)
			$css1="height:82px;"; 
		else 
			$css1="height:69px;";
	}
	else $css1="height:29px;";
	$css2=$css1;
	if ($align == "float-right" ){$css2.="float: right;";$css1.="float: right;";}
	if ($align == "float-left" ){$css2.="float: left;";$css1.="float: left;";}
	if ($align == "align-left" )$css1.="float: left;";
	if ($align == "align-right" )$css1.="float: right;";
	$sharelinks='<div class="social4i" style="'.$css2.'"><div class="social4in" style="'.$css1.'">';
	if(get_option('s4_twitter','1') && $type === FALSE || $type == "s4_twitter"){
	if ($size == "large" )$tp="vertical"; else $tp="horizontal";
	$s4link= '<div class="socialicons s4twitter" style="float:left;margin-right: 10px;"><a href="https://twitter.com/share" data-url="'.$plink.'" data-counturl="'.$plink.'" data-text="'.$eptitle.'" class="twitter-share-button" data-count="'.$tp.'" data-via="'.$via.'"></a>'.$twsc.'</div>';
	$sharelinks.=$s4link;
	$btnarr[1]=$s4link;
	
	$btnarrver[1]='<div class="socialiconsv s4twitterv" ><a href="https://twitter.com/share" data-url="'.$plink.'" data-counturl="'.$plink.'" data-text="'.$eptitle.'" class="twitter-share-button" data-count="vertical" data-via="'.$via.'"></a>'.$twsc.'</div>';
	}
	if(get_option('s4_fblike','1') && $type === FALSE || $type == "s4_fblike" || $type == "s4_fbsend"){
	if(get_option('s4_fbsend',false)==true || $type == "s4_fbsend")$snd="true"; else $snd="false";
	if ($size == "large" )
		$tp=' data-layout="box_count" data-width="55" data-height="62" ';
	else 
		$tp=' data-layout="button_count" data-width="100" data-height="21" ';
		
	$s4link= '<div class="socialicons s4fblike" style="float:left;margin-right: 10px;">'.$flsc.'<div class="fb-like" data-href="'.$plink.'" data-send="'.$snd.'" '.$tp.' data-show-faces="false"></div></div>';
	
	$sharelinks.=$s4link;
	$btnarr[2]=$s4link;
	
	$btnarrver[2]='<div class="socialiconsv s4fblikev" ><iframe src="//www.facebook.com/plugins/like.php?href='.$plink.'&amp;send=false&amp;layout=box_count&amp;width=49&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=65" style="border:none; overflow:hidden; width:49px; height:65px;"></iframe></div>';
	}
	if(get_option('s4_plusone','1') && $type === FALSE || $type == "s4_plusone"){
	if ($size == "large" )$tp="tall"; else $tp="medium";
	$s4link='<div class="socialicons s4plusone" style="float:left;margin-right: 10px;">'.$gpsc.'<div class="g-plusone" data-size="'.$tp.'" data-href="'.$plink.'"></div></div>';
	$sharelinks.=$s4link;
	$btnarr[3]=$s4link;
	
	$btnarrver[3]='<div class="socialiconsv s4plusonev" >'.$gpsc.'<div class="g-plusone" data-size="tall" data-href="'.$plink.'" ></div></div>';
	}
	if(get_option( 's4_linkedin', false )&& $type === FALSE || $type == "s4_linkedin"){
	if ($size == "large" )$tp="top"; else $tp="right";
	$s4link='<div class="socialicons s4linkedin" style="float:left;margin-right: 10px;">'.$lnsc.'<script type="in/share" data-url="'.$plink.'" data-counter="'.$tp.'"></script></div>';
	$sharelinks.=$s4link;
	$btnarr[4]=$s4link;
	
	$btnarrver[4]='<div class="socialiconsv s4linkedinv" >'.$lnsc.'<script type="in/share" data-url="'.$plink.'" data-counter="top"></script></div>';
	}
	if(get_option('s4_fbshare','1') && $type === FALSE || $type == "s4_fbshare"){
	if ($size == "large" )
	{
		$tp="box_count";
		$cs1="height: 61px;width:61px;";
		$cs2='style="position: absolute; bottom: 0pt;"';
	} else $tp="button_count";
	$s4link= '<div class="socialicons s4fbshare" style="position: relative;'.$cs1.'float:left;margin-right: 10px;"><div class="s4ifbshare" '.$cs2.'><div class="fb-share-button" data-href="'.$plink.'" data-width="450" data-type="'.$tp.'"></div>'.$flsc.'</div></div>';
	$sharelinks.=$s4link;
	$btnarr[5]=$s4link;
	
	$btnarrver[5]='<div class="socialiconsv s4fbsharev" ><div class="fb-share-button" data-href="'.$plink.'" data-width="450" data-type="box_count"></div>'.$flsc.'</div>';
	}
	if(get_option('s4_cbtn', false )==true && $type === FALSE)
	{
		$s4link=s4_get_custom_button($size);
		$s4link1=s4_get_custom_button("large");
		$sharelinks.=$s4link;
		$btnarr[6]=$s4link;
		
		
		$s4link1=str_replace("socialicons", "socialiconsv", $s4link1);
		$s4link1=str_replace("s4custombtn", "s4custombtnv", $s4link1);
		$s4link1=str_replace('style="float:left;margin-right: 10px;"', "", $s4link1);
		$btnarrver[6]=$s4link1;
	}
	if($type === FALSE)
	{
		$sharelinks='<div class="social4i" style="'.$css2.'"><div class="social4in" style="'.$css1.'">'.s4_arrange_btns($btnarr).'</div><div style="clear:both"></div></div>';
	}
	else $sharelinks.= '</div><div style="clear:both"></div></div>';
	
	return $sharelinks;
}

function s4_arrange_btns($btnarr)
{
	if(!is_array($btnarr))return '';
	$ord=s4_order_check(get_option('s4dispord','1,2,3,4,5,6'));
	$btnord=explode(",",$ord);
	$btnarr2=array();
	for($i=0;$i<=5;$i++)
	{
		$btnarr2[]=$btnarr[$btnord[$i]];
	}
	return implode('',$btnarr2);
}
function s4_post_img_link()
{
$thumb = false;
if(function_exists('get_post_thumbnail_id')&&function_exists('wp_get_attachment_image_src'))
{
	$image_id = get_post_thumbnail_id();
	$image_url = wp_get_attachment_image_src($image_id,'large');
	$thumb = $image_url[0];
}
if($thumb=='')$thumb=s4_get_first_image();
$default_img = get_option('s4defthumb',''); 
if ( $thumb == false || $thumb=='') 
	$thumb=$default_img; 
return $thumb;
}
function s4_get_custom_button($size)
{
	global $post;
	
	$metaimg=s4_get_custom_field( 's4_image2s' );
	$metades=s4_get_custom_field( 's4_cdes' );
	$metapl=s4_get_custom_field( 's4_url2s' );
	$metattl=s4_get_custom_field( 's4_ctitle' );
	
	if($metapl=='')$plink = get_permalink($post->ID);else $plink=$metapl;
	if($metattl=='')$ptitle = get_the_title($post->ID);else $ptitle = $metattl;
	if($metades=='')$desc = "";else $desc = $metades;
	if($metaimg=='')$pimg = s4_post_img_link();else $pimg = $metaimg;
	
	$eplink = urlencode($plink);

	$comments_count = wp_count_comments($post->ID);
	$cc=$comments_count->approved;
	if($desc=='')
	{
		if (has_excerpt($post->ID)) {
			$desc = esc_attr(strip_tags(get_the_excerpt($post->ID)));
		}else{
			$desc = esc_attr(str_replace("\r\n",' ',substr(strip_tags(strip_shortcodes($post->post_content)), 0, 160)));
		}	
	}
	if($size=='large')$cbtn=get_option('s4cblarge','');
	else $cbtn=get_option('s4cbsmall','');
	if(trim($cbtn==''))return '';
	
	$eptitle=urlencode($ptitle);
	$edesc=urlencode($desc);
	
	$cbtn=str_replace("%%URL%%", $plink, $cbtn);
	$cbtn=str_replace("%%EURL%%", $eplink, $cbtn);
	$cbtn=str_replace("%%TITLE%%", $ptitle, $cbtn);
	$cbtn=str_replace("%%PIMAGE%%", $pimg, $cbtn);
	$cbtn=str_replace("%%DESC%%", $desc, $cbtn);
	$cbtn=str_replace("%%ETITLE%%", $eptitle, $cbtn);
	$cbtn=str_replace("%%EDESC%%", $edesc, $cbtn);
	$cbtn=str_replace("%%COMMENTCOUNT%%", $cc, $cbtn);
		
	$allbtns=explode("[BUTTON]",$cbtn);
	$cnt=1;
	$buttoncode='';
	foreach($allbtns as $btn)
	{
		if(trim($btn==''))continue;
		$buttoncode.='<div class="socialicons s4custombtn-'.$cnt.'" style="float:left;margin-right: 10px;">'.$btn.'</div>';
		$cnt=$cnt+1;
	} 
	return $buttoncode;

} 
function s4_get_custom_field( $value ) {
	global $post;

    $custom_field = get_post_meta( $post->ID, $value, true );
    if ( !empty( $custom_field ) )
	    return is_array( $custom_field ) ? stripslashes_deep( $custom_field ) : stripslashes( wp_kses_decode_entities( $custom_field ) );

    return '';
}

function s4_add_custom_meta_box() {
	add_meta_box( 's4-meta-box', 'Tweet Like Plusone (extra options)', 's4_meta_box_output', 'post', 'normal', 'high' );
	add_meta_box( 's4-meta-box', 'Tweet Like Plusone (extra options)', 's4_meta_box_output', 'page', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 's4_add_custom_meta_box' );

function s4_meta_box_output( $post ) {
	wp_nonce_field( 'my_s4_meta_box_nonce', 's4_meta_box_nonce' ); ?>
	
	<p>
		<b><label for="s4_url2s"><?php echo "URL to share"; ?>:</label></b>
		<input type="text" name="s4_url2s" id="s4_url2s" value="<?php echo s4_get_custom_field( 's4_url2s' ); ?>" size="50" /><br/>
		<i>If you want to share a URL that is not your Post URL. (Leave blank to share default URL)</i>
    </p>
    
	<p>
		<b><label for="s4_image2s"><?php echo "Image to share"; ?>:</label></b>
		<input type="text" name="s4_image2s" id="s4_image2s" value="<?php echo s4_get_custom_field( 's4_image2s' ); ?>" size="50" /><br/>
		<i>If you want to share a custom image. (Leave blank to share default image)</i>
    </p>
    <p>
		<b><label for="s4_ctitle"><?php echo "Custom Title"; ?>:</label></b>
		<input type="text" name="s4_ctitle" id="s4_ctitle" value="<?php echo s4_get_custom_field( 's4_ctitle' ); ?>" size="50" /><br/>
		<i>Custom Title that you want to share. (Leave blank to share default Title)</i>
    </p>
    <p>
		<b><label for="s4_cdes"><?php echo "Custom Description"; ?>:</label></b>
		<input type="text" name="s4_cdes" id="s4_cdes" value="<?php echo s4_get_custom_field( 's4_cdes' ); ?>" size="50" /><br/>
		<i>Custom description that you want to share. (Leave blank to share default Description)</i>
    </p>
	<?php
}

function s4_meta_box_save( $post_id ) {
	
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if( !isset( $_POST['s4_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['s4_meta_box_nonce'], 'my_s4_meta_box_nonce' ) ) return;
	if( !current_user_can( 'edit_post' ) ) return;
	if( isset( $_POST['s4_url2s'] ) )
		update_post_meta( $post_id, 's4_url2s', esc_attr( $_POST['s4_url2s'] ) );
	if( isset( $_POST['s4_image2s'] ) )
		update_post_meta( $post_id, 's4_image2s', esc_attr( $_POST['s4_image2s'] ) );
	if( isset( $_POST['s4_cdes'] ) )
		update_post_meta( $post_id, 's4_ctitle', esc_attr( $_POST['s4_ctitle'] ) );
	if( isset( $_POST['s4_cdes'] ) )
		update_post_meta( $post_id, 's4_cdes', esc_attr( $_POST['s4_cdes'] ) );
}
add_action( 'save_post', 's4_meta_box_save' );

//Geilt - Alexander Conroy geilt@esotech.org http://www.esotech.org and http://www.geilt.com 
//Added: $type: 
//s4_plusone s4_fbshare,s4_fblike, s4_twitter, s4_fbsend
function social4i_shortcode($atts){
	extract(shortcode_atts( array('size' => 'large','align'=>'align-left', 'type' => FALSE), $atts ));
	$ss=display_social4i($size,$align, $type);
	return $ss;
}
add_shortcode( 'social4i', 'social4i_shortcode' );
function s4_lang_disp()
{
$alllang=array("Catalan|ca_ES","Czech|cs_CZ","Welsh|cy_GB","Danish|da_DK","German|de_DE","Basque|eu_ES","English (Pirate)|en_PI","English (Upside Down)|en_UD","Cherokee|ck_US","English (US)|en_US","Spanish|es_LA","Spanish (Chile)|es_CL","Spanish (Colombia)|es_CO","Spanish (Spain)|es_ES","Spanish (Mexico)|es_MX","Spanish (Venezuela)|es_VE","Finnish (test)|fb_FI","Finnish|fi_FI","French (France)|fr_FR","Galician|gl_ES","Hungarian|hu_HU","Italian|it_IT","Japanese|ja_JP","Korean|ko_KR","Norwegian (bokmal)|nb_NO","Norwegian (nynorsk)|nn_NO","Dutch|nl_NL","Polish|pl_PL","Portuguese (Brazil)|pt_BR","Portuguese (Portugal)|pt_PT","Romanian|ro_RO","Russian|ru_RU","Slovak|sk_SK","Slovenian|sl_SI","Swedish|sv_SE","Thai|th_TH","Turkish|tr_TR","Kurdish|ku_TR","Simplified Chinese (China)|zh_CN","Traditional Chinese (Hong Kong)|zh_HK","Traditional Chinese (Taiwan)|zh_TW","Leet Speak|fb_LT","Afrikaans|af_ZA","Albanian|sq_AL","Armenian|hy_AM","Azeri|az_AZ","Belarusian|be_BY","Bengali|bn_IN","Bosnian|bs_BA","Bulgarian|bg_BG","Croatian|hr_HR","Dutch (Belgie)|nl_BE","English (UK)|en_GB","Esperanto|eo_EO","Estonian|et_EE","Faroese|fo_FO","French (Canada)|fr_CA","Georgian|ka_GE","Greek|el_GR","Gujarati|gu_IN","Hindi|hi_IN","Icelandic|is_IS","Indonesian|id_ID","Irish|ga_IE","Javanese|jv_ID","Kannada|kn_IN","Kazakh|kk_KZ","Latin|la_VA","Latvian|lv_LV","Limburgish|li_NL","Lithuanian|lt_LT","Macedonian|mk_MK","Malagasy|mg_MG","Malay|ms_MY","Maltese|mt_MT","Marathi|mr_IN","Mongolian|mn_MN","Nepali|ne_NP","Punjabi|pa_IN","Romansh|rm_CH","Sanskrit|sa_IN","Serbian|sr_RS","Somali|so_SO","Swahili|sw_KE","Filipino|tl_PH","Tamil|ta_IN","Tatar|tt_RU","Telugu|te_IN","Malayalam|ml_IN","Ukrainian|uk_UA","Uzbek|uz_UZ","Vietnamese|vi_VN","Xhosa|xh_ZA","Zulu|zu_ZA","Khmer|km_KH","Tajik|tg_TJ","Arabic|ar_AR","Hebrew|he_IL","Urdu|ur_PK","Persian|fa_IR","Syriac|sy_SY","Yiddish|yi_DE","Guarani|gn_PY","Quechua|qu_PE","Aymara|ay_BO","Northern Sami|se_NO","Pashto|ps_AF","Klingon|tl_ST");
echo '<select name="s4fblikelang">';
$clang=get_option( 's4fblikelang', 'en_US' );
foreach($alllang as $lang)
{
	$l1=explode("|",$lang);
	if($l1[1]==$clang)$l2=' selected="selected"';else $l2='';
	echo '<option value="'.$l1[1].'"'.$l2.'>'.$l1[0].'</option>';
}
echo '</select>';
}
function social4i_check_mobile()
{
//This mobile browser check code is taken from Mobilepress plugin
$ismob=false;
switch(TRUE)
{	
	case (preg_match('/(iphone|ipod)/i', $_SERVER['HTTP_USER_AGENT']) && preg_match('/mobile/i', $_SERVER['HTTP_USER_AGENT'])):
		$ismob="true";
		break; 
	case (preg_match('/ipad/i', $_SERVER['HTTP_USER_AGENT']) && preg_match('/mobile/i', $_SERVER['HTTP_USER_AGENT'])):
		$ismob=false;
		break;	
	case (preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', $_SERVER['HTTP_USER_AGENT'])):
		$ismob=true;
		break; 
	case (((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'text/vnd.wap.wml') > 0) || (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml')>0)) || ((isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])))):
		$ismob=true;
		break; 
	case (in_array(strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,3)),array('lg '=>'lg ','lg-'=>'lg-','lg_'=>'lg_','lge'=>'lge'))):
		$ismob=true;
		break; 
	case (in_array(strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4)),array('acs-'=>'acs-','amoi'=>'amoi','doco'=>'doco','eric'=>'eric','huaw'=>'huaw','lct_'=>'lct_','leno'=>'leno','mobi'=>'mobi','mot-'=>'mot-','moto'=>'moto','nec-'=>'nec-','phil'=>'phil','sams'=>'sams','sch-'=>'sch-','shar'=>'shar','sie-'=>'sie-','wap_'=>'wap_','zte-'=>'zte-'))):
		$ismob=true;
		break;
	case (preg_match('/Googlebot-Mobile/i', $_SERVER['HTTP_USER_AGENT']) || preg_match('/YahooSeeker\/M1A1-R2D2/i', $_SERVER['HTTP_USER_AGENT'])):
		$ismob=true;
		break;
}
return $ismob;
}
?>