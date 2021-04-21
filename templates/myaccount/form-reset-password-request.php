<?php

/**
 * Password reset request form.
 *
 * @version 0.1.0
 */

defined('ABSPATH') || exit;

do_action('masteriyo_before_password_reset_request_form');

?>

<section class="mto-reset">
		<div class="mto-reset--wrapper mto-form-container">
			<h3 class="mto-reset--title"><?php echo esc_html__('Reset Password', 'masteriyo'); ?></h3>
			<p class="mto-reset--msg"><?php echo esc_html__("Enter your user account's verified email address and we will send you a password reset link.", 'masteriyo'); ?></p>

			<form id="mto-reset--form" class="mto-reset--form" method="post">
					<div class="mto-username">
						<label for="reset-username-email-address" class="mto-label"><?php echo esc_html__('Username or Email', 'masteriyo'); ?></label>
						<input id="reset-username-email-address" class="mto-input" name="user_login" type="text" required placeholder="">
					</div>

				<div class="mto-btn-wrapper">
					<button type="submit" name="masteriyo-password-reset-request" value="yes" class="mto-reset-btn mto-btn mto-primary">
						<?php echo esc_html__('Send Reset Email', 'masteriyo'); ?>
					</button>

					<div class="mto-reset-signin">
						<a href="<?php echo esc_url(masteriyo_get_page_permalink('myaccount')); ?>" class="mto-link-primary">
							<?php echo esc_html__('Go back to sign-in', 'masteriyo'); ?>
						</a>
					</div>
				</div>

				<?php wp_nonce_field( 'masteriyo-password-reset-request' ); ?>

				<?php masteriyo_display_all_notices(); ?>
			</form>

		</div>
</section>

<?php

do_action('masteriyo_after_password_reset_request_form');
