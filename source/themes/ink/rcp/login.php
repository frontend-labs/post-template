<?php global $rcp_login_form_args; ?>
<?php if( ! is_user_logged_in() ) : ?>

	<?php rcp_show_error_messages( 'login' ); ?>

	<form id="rcp_login_form"  class="rcp_form" method="POST" action="<?php echo esc_url( rcp_get_current_url() ); ?>">
		<fieldset class="grid rcp_login_data">
			<p class="unit one-of-two">
				<input name="rcp_user_login" id="rcp_user_login" class="required" type="text"/>
				<label for="rcp_user_Login"><?php _e( 'Username', 'rcp' ); ?></label>
			</p>
			<p class="unit one-of-two">
				<input name="rcp_user_pass" id="rcp_user_pass" class="required" type="password"/>
				<label for="rcp_user_pass">
					<?php _e( 'Password', 'rcp' ); ?>
					<span class="rcp_lost_password"><a href="<?php echo esc_url( wp_lostpassword_url( rcp_get_current_url() ) ); ?>"><?php _e( 'I have forgotten', 'stag' ); ?></a></span>
				</label>
			</p>
			<p class="unit span-grid rcp_submit">
				<input type="hidden" name="rcp_action" value="login"/>
				<input type="hidden" name="rcp_redirect" value="<?php echo esc_url( $rcp_login_form_args['redirect'] ); ?>"/>
				<input type="hidden" name="rcp_login_nonce" value="<?php echo wp_create_nonce( 'rcp-login-nonce' ); ?>"/>
				<input id="rcp_login_submit" type="submit" value="Login"/>
				<input type="checkbox" name="rcp_user_remember" id="rcp_user_remember" value="1"/>
				<label for="rcp_user_remember"><?php _e( 'Remember', 'rcp' ); ?></label>
			</p>
		</fieldset>
	</form>
<?php else : ?>
	<div class="rcp_logged_in"><?php _e( 'You&rsquo;re already logged in!', 'stag' ); ?> <a href="<?php echo wp_logout_url( home_url() ); ?>"><?php _e( 'Logout', 'rcp' ); ?></a></div>
<?php endif;
