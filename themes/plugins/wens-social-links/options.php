<?php

    $option_group = '_plugin_option_group';
    
    $option_name = '_plugin_options';

    // Create plugin options

    global $wen_options;

    $wen_options = array (

        array("type" => "left-open"),

        array("name" => __('RSS','WEN'),        
                "id" => "rsslink",
                "type" => "text",
                "std" => ""),

        array("name" => __('Facebook','WEN'),        
                "id" => "facebooklink",
                "type" => "text",
                "std" => ""),

        array("name" => __('Twitter','WEN'),        
                "id" => "twitterlink",
                "type" => "text",
                "std" => ""),

        array("name" => __('Google+','WEN'),        
                "id" => "gpluslink",
                "type" => "text",
                "std" => ""),

        array("name" => __('LinkedIn','WEN'),        
                "id" => "linkedinlink",
                "type" => "text",
                "std" => ""),

        array("name" => __('Pinterest','WEN'),        
                "id" => "pinterestlink",
                "type" => "text",
                "std" => ""),

        array("name" => __('Instagram','WEN'),        
                "id" => "instagramlink",
                "type" => "text",
                "std" => ""),

        array("name" => __('Digg','WEN'),        
                "id" => "digglink",
                "type" => "text",
                "std" => ""),

        array("name" => __('Myspace','WEN'),        
                "id" => "myspacelink",
                "type" => "text",
                "std" => ""),

        array("name" => __('Tumblr','WEN'),        
                "id" => "tumblrlink",
                "type" => "text",
                "std" => ""),


        array("name" => __('Flickr','WEN'),       
                "id" => "flickrlink",
                "type" => "text",
                "std" => ""),

        array("type" => "close"),

        array("type" => "right-open"),


        array("name" => __('Reddit','WEN'),        
                "id" => "redditlink",
                "type" => "text",
                "std" => ""),

        array("name" => __('Dribbble','WEN'),        
                "id" => "dribbblelink",
                "type" => "text",
                "std" => ""),

        array("name" => __('Blogger','WEN'),        
                "id" => "bloggerlink",
                "type" => "text",
                "std" => ""),

        array("name" => __('Stackoverflow','WEN'),        
                "id" => "stackoverflowlink",
                "type" => "text",
                "std" => ""),

        array("name" => __('Yahoo','WEN'),        
                "id" => "yahoolink",
                "type" => "text",
                "std" => ""),

        array("name" => __('Skype','WEN'),        
                "id" => "skypelink",
                "type" => "text",
                "std" => ""),

        array("name" => __('Paypal','WEN'),       
                "id" => "paypallink",
                "type" => "text",
                "std" => ""),

        array("name" => __('Youtube','WEN'),        
                "id" => "youtubelink",
                "type" => "text",
                "std" => ""),

        array("name" => __('Vimeo','WEN'),        
                "id" => "vimeolink",
                "type" => "text",
                "std" => ""),

        array("name" => __('Dailymotion','WEN'),        
                "id" => "dailymotionlink",
                "type" => "text",
                "std" => ""),

        array("name" => __('Netflix','WEN'),               
                "id" => "netflixlink",
                "type" => "text",
                "std" => ""),

        array("type" => "close"),

        array("type" => "full-col-open"),

        array("name" => __('Title','WEN'),        
                "id" => "widget_title",
                "type" => "text",
                "std" => ""),

        array("name" => __('Tooltip','WEN'),        
                "id" => "tooltip",
                "type" => "radio",
                "options" => array( 1 =>'Enable', 0 =>'Disable' ),
                "std" => ""),

        array("name" => __('Custom Style','WEN'),        
                "id" => "customstyle",
                "type" => "textarea",
                "std" => ""),   

        array("type" => "close")

    );


function wen_settings_page() {

  global $wen_options, $option_group, $option_name;
?>

<div class="wrap">
    <div class="options_wrap">

        <h2><?php _e('WEN Social Links Options','WEN'); ?></h2>

        <p class="top-notice"><?php _e('Customize your options with these settings. ','WEN'); ?></p>

    <form method="post" action="options.php">

        <?php settings_fields( $option_group ); ?>

        <?php $options = get_option( $option_name ); ?>        

        <?php foreach ($wen_options as $value) {
     
            if ( isset($value['id']) ) { $valueid = $value['id'];}
            switch ( $value['type'] ) {

                case 'text':
                ?>                
                    <div class="options_input options_text">      
                        <span class="labels">
                            <label for="<?php echo $option_name.'['.$valueid.']'; ?>">
                                <?php echo $value['name']; ?>
                            </label>
                        </span>
                        <input name="<?php echo $option_name.'['.$valueid.']'; ?>" id="<?php echo $option_name.'['.$valueid.']'; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( isset( $options[$valueid]) ){ esc_attr_e($options[$valueid]); } else { esc_attr_e($value['std']); } ?>" />
                    </div>
                <?php
                break;

                case 'textarea':
                ?>
                    <div class="options_input options_textarea">        
                        <span class="labels"><label for="<?php echo $option_name.'['.$valueid.']'; ?>"><?php echo $value['name']; ?></label></span>
                        <textarea name="<?php echo $option_name.'['.$valueid.']'; ?>" type="<?php echo $option_name.'['.$valueid.']'; ?>" cols="" rows=""><?php if ( isset( $options[$valueid]) ){ esc_attr_e($options[$valueid]); } else { esc_attr_e($value['std']); } ?></textarea>
                    </div>
                <?php 
                break;

                case "radio":
                ?>
                    <div class="options_input options_select">        
                        <span class="labels"><label for="<?php echo $option_name.'['.$valueid.']'; ?>"><?php echo $value['name']; ?></label></span>
                          <?php foreach ($value['options'] as $key=>$option) { 
                            $radio_setting = $options[$valueid];
                            if($radio_setting != ''){
                                if ($key == $options[$valueid] ) {
                                    $checked = "checked=\"checked\"";
                                } else {
                                    $checked = "";
                                }
                            }else{
                                if($key == $value['std']){
                                    $checked = "checked=\"checked\"";
                                }else{
                                    $checked = "";
                                }
                            }?>
                            <input type="radio" id="<?php echo $option_name.'['.$valueid.']'; ?>" name="<?php echo $option_name.'['.$valueid.']'; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?> /><?php echo $option; ?>
                            <?php } ?>
                    </div>
                <?php
                break;

                case "left-open":
                ?>
                    <div class="options_wrap_left">
                <?php 
                break;

                case "right-open":
                ?>
                    <div class="options_wrap_right">
                <?php 
                break;

                case "full-col-open":
                ?>
                    <div class="options_wrap_full">
                    <h2>General Settings</h2>
                <?php 
                break;

                case "close":
                ?>
                    </div><!--div close-->
                <?php 
                break;

            }
        }
        ?>
        <div class="clearfix"></div>
        <span class="submit">
            <input class="button button-primary" type="submit" name="save" value="<?php _e('Save All Changes', 'WEN') ?>" />
        </span>
    </form>
    </div>

    <div class="sidebox first-sidebox"> 

        <h3>Instruction to use Plugin</h3>

        <hr />

        <h3>Using Shortcode</h3>

        <p>Use following shortcode in your post, page or widget <br /><br />[wen_social_links]</p>

        <hr />

        <h3>Using PHP Code</h3>

        <p>
            Use following function in your template <br /><br />
            <?php echo htmlspecialchars("<?php echo wen_social_links(); ?>"); ?>
        </p>

    </div>

    <div class="sidebox"> 

        <h3>Do you find this Plugin useful?</h3>

        <p>If yes, give 5 star rating to respect our work. Click <a href="http://wordpress.org/support/view/plugin-reviews/wens-social-links" target="_blank">here</a> to submit your ratings.</p>

        <hr />

        <p>If you have any queries or want to suggest imporvement, Please feel free to write us today. Click <a href="mailto://smanesh2004@gmail.com">here</a></p>
    </div>

    <div class="sidebox similar-plugin">
        
        <h3>WEN's Social Media Followers Counter</h3>

        <a href="http://wordpress.org/plugins/social-media-followers-counter/" target="_blank">
            <img src="<?php echo plugins_url( '/images/social-media-follower.png' , __FILE__ ); ?>" />
        </a>

        <p>Plugin to displays Facebook page likes , Twitter followers , Google's Plus and YouTube subscribers.</p> 

        <hr />
        
        <a href="http://wordpress.org/plugins/social-media-followers-counter/" target="_blank">Download</a>
        
    </div>

</div>
<?php }

if ( is_admin() ) {
    add_action( 'admin_head', 'hook_admin_head' );
}


function hook_admin_head() {

 wp_enqueue_style('admin-style', plugins_url('/css/admin-style.css', __FILE__), array(), PLUGIN_VERSION, 'all');            

}