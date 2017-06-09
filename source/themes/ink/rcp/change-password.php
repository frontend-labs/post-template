<?php global $rcp_password_form_args; ?>

<?php rcp_show_error_messages( 'password' ); ?>

<?php if( isset( $_GET['password-reset']) && $_GET['password-reset'] == 'true') { ?>
	<div class="rcp_message success">
		<span><?php _e( 'Password changed successfully', 'rcp' ); ?></span>
	</div>
<?php } ?>
<form id="rcp_password_form"  class="rcp_form" method="POST" action="<?php echo esc_url( rcp_get_current_url() ); ?>">
	<fieldset class="grid rcp_change_password_fieldset">
		<p class="unit one-of-two">
			<input name="rcp_user_pass" id="rcp_user_pass" class="required" type="password"/>
			<label for="rcp_user_pass"><?php echo apply_filters( 'rcp_registration_new_password_label', __( 'New Password', 'rcp' ) ); ?></label>
		</p>
		<p class="unit one-of-two">
			<input name="rcp_user_pass_confirm" id="rcp_user_pass_confirm" class="required" type="password"/>
			<label for="rcp_user_pass_confirm"><?php echo apply_filters( 'rcp_registration_confirm_password_label', __( 'Password Confirm', 'rcp' ) ); ?></label>
		</p>
		<p class="unit span-grid">
			<input type="hidden" name="rcp_action" value="reset-password"/>
			<input type="hidden" name="rcp_redirect" value="<?php echo esc_url( $rcp_password_form_args['redirect'] ); ?>"/>
			<input type="hidden" name="rcp_password_nonce" value="<?php echo wp_create_nonce('rcp-password-nonce' ); ?>"/>
			<input id="rcp_password_submit" type="submit" class="full-width-button" value="<?php echo apply_filters( 'rcp_registration_change_password_button', __( 'Change Password', 'rcp' ) ); ?>"/>
		</p>
	</fieldset>
</form>
