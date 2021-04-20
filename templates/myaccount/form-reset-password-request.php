<?php

/**
 * Password reset request form.
 *
 * @version 0.1.0
 */

defined('ABSPATH') || exit;

do_action('masteriyo_before_password_reset_request_form');

?>

<section class="reset-form mto-container mto-mx-auto">
	<div class="mto-flex mto-justify-center mto-items-center mto-h-screen">

		<div class="mto-form-wrapper">
			<h2 class="mto-font-semibold mto-text-2xl mto-mb-4"><?php echo esc_html__('Reset Password', 'masteriyo'); ?></h2>
			<p><?php echo esc_html__("Enter your user account's verified email address and we will send you a password reset link.", 'masteriyo'); ?></p>

			<form class="mto-mt-10" method="post">
				<div class="mto-rounded-md mtoshadow-sm">
					<div class="reset-username mto-mb-6">
						<label for="reset-username-email-address" class="mto-block mto-text-sm mto-font-semibold mto-mb-2"><?php echo esc_html__('Username or Email', 'masteriyo'); ?></label>
						<input id="reset-username-email-address" name="user_login" type="text" required class="mto-px-4 mto-rounded mto-block mto-w-full mto-py-2 mto-border mto-border-gray-300 focus:mto-outline-none focus:mto-shadow-outline focus:mto-border-primary" placeholder="">
					</div>
				</div>

				<div class="mto-block md:mto-flex mto-items-center md:mto-space-x-6">
					<button type="submit" name="masteriyo-password-reset-request" value="yes" class="btn mto-capitalize sign-in">
						<?php echo esc_html__('Send Reset Email', 'masteriyo'); ?>
					</button>

					<div class="go-back mto-text-sm mto-mt-6 md:mto-my-0 mto-text-center md:mto-text-left">
						<a href="<?php echo esc_url(masteriyo_get_page_permalink('myaccount')); ?>" class="mto-font-medium mto-text-primary-800 hover:mto-text-primary-700 hover:mto-underline">
							<?php echo esc_html__('Go back to sign-in', 'masteriyo'); ?>
						</a>
					</div>
				</div>

				<input class="mto-hidden" type="text" name="_wpnonce" value="<?php echo wp_create_nonce('masteriyo-password-reset-request'); ?>">

				<?php masteriyo_display_all_notices(); ?>
			</form>

		</div>
	</div>
</section>

<?php

do_action('masteriyo_after_password_reset_request_form');
