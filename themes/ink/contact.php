<?php
/**
 * Template Name: Contact Form
 *
 * Displays a contact form.
 *
 * @package Stag_Customizer
 * @subpackage Ink
 */

$nameError         = __( 'Please enter your name.', 'stag' );
$emailError        = __( 'Please enter your email address.', 'stag' );
$emailInvalidError = __( 'You entered an invalid email address.', 'stag' );
$commentError      = __( 'Please enter a message.', 'stag' );

$errorMessages = array();

if ( isset( $_POST['submitted'] ) ) {

	if ( trim( $_POST['contactName'] ) === '' ) {
	    $errorMessages['nameError'] = $nameError;
	    $hasError = true;
	} else {
	    $name = trim( $_POST['contactName'] );
	}

	if ( trim( $_POST['email'] ) === '' ) {
	    $errorMessages['emailError'] = $emailError;
	    $hasError = true;
	} elseif ( !is_email( trim( $_POST['email'] ) ) ) {
	    $errorMessages['emailInvalidError'] = $emailInvalidError;
	    $hasError = true;
	} else {
	    $email = trim($_POST['email']);
	}

	if( trim( $_POST['comments'] ) === '' ) {
	    $errorMessages['commentError'] = $commentError;
	    $hasError = true;
	} else {
        $comments = stripslashes( trim( $_POST['comments'] ) );
	}

	if ( !isset( $hasError ) ) {
	    $emailTo = stag_theme_mod( 'general_settings', 'contact_email' );

	    if ( !isset($emailTo) || ($emailTo == '') ) {
	        $emailTo = get_option('admin_email');
	    }

		$subject = '[Contact Form] From '.$name;
		$body    = "Name: $name \n\nEmail: $email \n\nMessage: $comments \n\n";
		$body    .= "--\n";
		$body    .= "This mail is sent via contact form on ".get_bloginfo('name')."\n";
		$body    .= home_url();
		$headers = 'From: '.$name.' <'.$email.'>' . "\r\n" . 'Reply-To: ' . $email;

	    wp_mail( $emailTo, $subject, $body, $headers );
	    $emailSent = true;
	}
}

get_header(); ?>

	<main id="main" class="site-main page-cover page-cover--<?php echo get_the_ID(); ?>">

		<div class="page-cover__background"></div>

		<?php stag_post_background_css( get_the_ID(), '.page-cover--', '.page-cover__background' ); ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'page' ); ?>

		<?php endwhile; // end of the loop. ?>

	</main><!-- #main -->

	<section class="contact-form">
		<?php if(isset($emailSent) && $emailSent == true) : ?>

		<div class="stag-alert stag-alert--green">
		    <p><?php _e('Thanks, your email was sent successfully.', 'stag') ?></p>
		</div>

		<?php else: ?>

		<form action="<?php the_permalink(); ?>" id="contactForm" method="post">
			<h2 class="contact-form__title"><?php _e('Send a Message', 'stag'); ?></h2>

			<div class="grid">
			    <p class="unit one-of-two">
			        <input type="text" name="contactName" id="contactName" value="<?php if(isset($_POST['contactName'])) echo $_POST['contactName'];?>" required>
			        <label for="contactName"><?php _e('Name', 'stag') ?></label>
			        <?php if(isset($errorMessages['nameError'])) { ?>
			            <span class="error"><?php echo $errorMessages['nameError']; ?></span>
			        <?php } ?>
			    </p>

			    <p class="unit one-of-two">
			        <input type="email" name="email" id="email" value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>" required>
			        <label for="email"><?php _e('Email', 'stag') ?></label>
			        <?php if(isset($errorMessages['emailError'])) { ?>
			            <span class="error"><?php echo $errorMessages['emailError']; ?></span>
			        <?php } ?>
			        <?php if(isset($errorMessages['emailInvalidError'])) { ?>
			            <span class="error"><?php echo $errorMessages['emailInvalidError']; ?></span>
			        <?php } ?>
			    </p>

			    <p class="unit span-grid">
			        <textarea rows="6" name="comments" id="commentsText" required><?php if(isset($_POST['comments'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['comments']); } else { echo $_POST['comments']; } } ?></textarea>
			        <label for="commentsText"><?php _e( 'Your Message', 'stag' ) ?></label>
			        <?php if(isset($errorMessages['commentError'])) { ?>
			            <span class="error"><?php echo $errorMessages['commentError']; ?></span>
			        <?php } ?>
			    </p>

			    <p class="unit span-grid buttons">
			        <input type="submit" id="submitted" class="contact-form-button" name="submitted" value="<?php esc_attr_e('Send Message', 'stag') ?>">
			    </p>
			</div>
		</form>

		<?php endif; ?>
	</section>

<?php get_footer();
