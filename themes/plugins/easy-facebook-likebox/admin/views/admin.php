<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Plugin_Name
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */
?>   
<div class="wrap efbl" id="dashboard-widgets">
<h2 class="nav-tab-wrapper">
<?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';?>

                <a href="<?php echo admin_url('admin.php')?>?page=easy-facebook-likebox&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _e('General', 'easy-facebook-likebox'); ?></a>
                <a href="<?php echo admin_url('admin.php')?>?page=easy-facebook-likebox&tab=autopopup" class="nav-tab <?php echo $active_tab == 'autopopup' ? 'nav-tab-active' : ''; ?>"><?php _e('Auto PopUp', 'easy-facebook-likebox'); ?></a>
                <a href="<?php echo admin_url('admin.php')?>?page=easy-facebook-likebox&tab=supportupdates" class="nav-tab <?php echo $active_tab == 'supportupdates' ? 'nav-tab-active' : ''; ?>"><?php _e('Support and Updates', 'easy-facebook-likebox'); ?></a>
                 
            </h2><br /><br />
            
             
            
<form method="post" action="<?php echo admin_url('options.php')?>">
 
 <?php if( $active_tab == 'general' ) {?>
   
    <div id="normal-sortables" class="meta-box-sortables ui-sortable">
    
    	<?php do_meta_boxes($this->plugin_screen_hook_suffix, 'normal', $data); ?>
       
       
    </div>
    
  <?php }//End general tab?> 
  
    <?php if( $active_tab == 'autopopup' ) { //Start Post Layout tab ?> 
 
 
  
  <div id="normal-sortables" class="meta-box-sortables ui-sortable">
  
   <?php do_meta_boxes($this->plugin_screen_hook_suffix, 'additional', $data); ?>
   </div>
   <div class="clearfix"></div>
   <?php } //End ?>
   
   <?php if( $active_tab == 'supportupdates' ) { //Start Post Layout tab ?> 
   <div id="normal-sortables" class="meta-box-sortables ui-sortable">
    <?php do_meta_boxes($this->plugin_screen_hook_suffix, 'side', $data); ?>
        
    </div>
    <?php }?>
   
</form>  
</div>

<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
			// close postboxes that should be closed
			$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
			// postboxes setup
			postboxes.add_postbox_toggles('<?php echo $this->plugin_screen_hook_suffix; ?>');
		});
		//]]>
	</script>
    
<style type="text/css">
#dashboard_right_now li{
	width:100%;
}
.hndle{
	padding: 10px;
	margin:0px;
}
</style>
