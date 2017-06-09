<?php global $rcp_options, $rcp_level, $post; ?>

<?php if( ! is_user_logged_in() ) { ?>
	<h3 class="rcp_header">
		<?php echo apply_filters( 'rcp_registration_header_logged_in', __( 'Register New Account', 'rcp' ) ); ?>
	</h3>
<?php } else { ?>
	<h3 class="rcp_header">
		<?php echo apply_filters( 'rcp_registration_header_logged_out', __( 'Upgrade Your Subscription', 'rcp' ) ); ?>
	</h3>
<?php }

// show any error messages after form submission
rcp_show_error_messages( 'register' ); ?>

<form id="rcp_registration_form" class="rcp_form" method="POST" action="<?php echo esc_url( rcp_get_current_url() ); ?>">

	<div class="rcp_description"><?php echo wpautop( wptexturize( rcp_get_subscription_description( $rcp_level ) ) ); ?></div>

	<?php if( ! is_user_logged_in() ) { ?>

	<?php do_action( 'rcp_before_register_form_fields' ); ?>


	<fieldset class="grid rcp_user_fieldset">
		<p id="rcp_user_login_wrap" class="unit one-of-two">
			<label for="rcp_user_Login"><?php echo apply_filters ( 'rcp_registration_username_label', __( 'Username', 'rcp' ) ); ?></label>
			<input name="rcp_user_login" id="rcp_user_login" class="required" type="text" <?php if( isset( $_POST['rcp_user_login'] ) ) { echo 'value="' . esc_attr( $_POST['rcp_user_login'] ) . '"'; } ?>/>
		</p>
		<p id="rcp_user_email_wrap" class="unit one-of-two">
			<label for="rcp_user_email"><?php echo apply_filters ( 'rcp_registration_email_label', __( 'Email', 'rcp' ) ); ?></label>
			<input name="rcp_user_email" id="rcp_user_email" class="required" type="text" <?php if( isset( $_POST['rcp_user_email'] ) ) { echo 'value="' . esc_attr( $_POST['rcp_user_email'] ) . '"'; } ?>/>
		</p>
		<p id="rcp_user_first_wrap" class="unit one-of-two">
			<label for="rcp_user_first"><?php echo apply_filters ( 'rcp_registration_firstname_label', __( 'First Name', 'rcp' ) ); ?></label>
			<input name="rcp_user_first" id="rcp_user_first" type="text" <?php if( isset( $_POST['rcp_user_first'] ) ) { echo 'value="' . esc_attr( $_POST['rcp_user_first'] ) . '"'; } ?>/>
		</p>
		<p id="rcp_user_last_wrap" class="unit one-of-two">
			<label for="rcp_user_last"><?php echo apply_filters ( 'rcp_registration_lastname_label', __( 'Last Name', 'rcp' ) ); ?></label>
			<input name="rcp_user_last" id="rcp_user_last" type="text" <?php if( isset( $_POST['rcp_user_last'] ) ) { echo 'value="' . esc_attr( $_POST['rcp_user_last'] ) . '"'; } ?>/>
		</p>
		<p id="rcp_password_wrap" class="unit one-of-two">
			<label for="password"><?php echo apply_filters ( 'rcp_registration_password_label', __( 'Password', 'rcp' ) ); ?></label>
			<input name="rcp_user_pass" id="rcp_password" class="required" type="password"/>
		</p>
		<p id="rcp_password_again_wrap" class="unit one-of-two">
			<label for="password_again"><?php echo apply_filters ( 'rcp_registration_password_again_label', __( 'Password Again', 'rcp' ) ); ?></label>
			<input name="rcp_user_pass_confirm" id="rcp_password_again" class="required" type="password"/>
		</p>

		<?php do_action( 'rcp_after_password_registration_field' ); ?>

	</fieldset>
	<?php } ?>

	<?php if( rcp_has_discounts() ) : ?>
	<fieldset class="rcp_discounts_fieldset">
		<p id="rcp_discount_code_wrap">
			<label for="rcp_discount_code">
				<?php _e( 'Discount Code', 'rcp' ); ?>
				<span class="rcp_discount_valid" style="display: none;"> - <?php _e( 'Valid', 'rcp' ); ?></span>
				<span class="rcp_discount_invalid" style="display: none;"> - <?php _e( 'Invalid', 'rcp' ); ?></span>
			</label>
			<input type="text" id="rcp_discount_code" name="rcp_discount" class="rcp_discount_code" value=""/>
		</p>
	</fieldset>
	<?php endif; ?>

	<?php do_action( 'rcp_after_register_form_fields' ); ?>

	<?php
	$gateways = rcp_get_enabled_payment_gateways();

	if( count( $gateways ) > 1 ) : $display = rcp_has_paid_levels() ? '' : ' style="display: none;"'; ?>
		<fieldset class="rcp_gateways_fieldset">
			<p id="rcp_payment_gateways"<?php echo $display; ?>>
				<select name="rcp_gateway" id="rcp_gateway">
					<?php foreach( $gateways as $key => $gateway ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $gateway ); ?></option>
					<?php endforeach; ?>
				</select>
				<label for="rcp_gateway"><?php _e( 'Choose Your Payment Method', 'rcp' ); ?></label>
			</p>
		</fieldset>
	<?php else: ?>
		<?php foreach( $gateways as $key => $gateway ) : ?>
			<input type="hidden" name="rcp_gateway" value="<?php echo esc_attr( $key ); ?>"/>
		<?php endforeach; ?>
	<?php endif; ?>

	<fieldset class="rcp_level_details_fieldset">
		<?php $level = rcp_get_subscription_details( $rcp_level ); ?>
		<p id="rcp_level_details_wrap">
			<span class="rcp_price"><?php echo rcp_currency_filter( $level->price ); ?></span>
			<span class="rcp_sep">&nbsp;/&nbsp;</span>
			<span class="rcp_duration"><?php echo $level->duration . ' ' . rcp_filter_duration_unit( $level->duration_unit, $level->duration ); ?></span>
		</p>
	</fieldset>

	<?php do_action( 'rcp_before_registration_submit_field' ); ?>

	<p id="rcp_submit_wrap">
		<input type="hidden" name="rcp_level" value="<?php echo absint( $rcp_level ); ?>"/>
		<input type="hidden" name="rcp_register_nonce" value="<?php echo wp_create_nonce('rcp-register-nonce' ); ?>"/>
		<input type="submit" name="rcp_submit_registration" class="full-width-button" id="rcp_submit" value="<?php echo apply_filters ( 'rcp_registration_register_button', __( 'Register', 'rcp' ) ); ?>"/>
	</p>
</form>
