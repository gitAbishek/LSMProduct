<?php

/**
 * Password reset request form.
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'masteriyo_before_password_reset_request_form' );

?>

<section class="masteriyo-reset">
		<div class="masteriyo-reset--wrapper masteriyo-form-container">
			<h3 class="masteriyo-reset--title"><?php echo esc_html__( 'Reset Password', 'masteriyo' ); ?></h3>
			<p class="masteriyo-reset--msg"><?php echo esc_html__( "Enter your user account's verified email address and we will send you a password reset link.", 'masteriyo' ); ?></p>

			<form id="masteriyo-reset--form" class="masteriyo-reset--form" method="post">
					<div class="masteriyo-username">
						<label for="reset-username-email-address" class="masteriyo-label"><?php echo esc_html__( 'Username or Email', 'masteriyo' ); ?></label>
						<input id="reset-username-email-address" class="masteriyo-input" name="user_login" type="text" required placeholder="">
					</div>

				<div class="masteriyo-btn-wrapper">
					<button type="submit" name="masteriyo-password-reset-request" value="yes" class="masteriyo-reset-btn masteriyo-btn masteriyo-primary">
						<?php echo esc_html__( 'Send Reset Email', 'masteriyo' ); ?>
					</button>

					<div class="masteriyo-reset-signin">
						<a href="<?php echo esc_url( masteriyo_get_page_permalink( 'account' ) ); ?>" class="masteriyo-link-primary">
							<?php echo esc_html__( 'Go back to sign-in', 'masteriyo' ); ?>
						</a>
					</div>
				</div>

				<?php wp_nonce_field( 'masteriyo-password-reset-request' ); ?>

				<?php masteriyo_display_all_notices(); ?>
			</form>

		</div>
</section>

<?php

do_action( 'masteriyo_after_password_reset_request_form' );
