<?php

/**
 * Sign up form template content.
 *
 * @version 0.1.0
 */

defined('ABSPATH') || exit;

/**
 * masteriyo_before_registration_form_content hook.
 */
do_action('masteriyo_before_registration_form_content');

?>

<section class="mto-signup mto-container mto-mx-auto">
	<div class="mto-flex mto-justify-center mto-items-center mto-h-screen">

		<div class="mto-form-wrapper">
			<h2 class="mto-font-semibold mto-text-2xl mto-mb-4"><?php echo esc_html__('Create Your Account', 'masteriyo'); ?></h2>
			<p><?php echo esc_html__('*Note: All fields are required.', 'masteriyo'); ?></p>

			<form id="mto-signup-form" class="mto-mt-10" method="post">
				<input type="hidden" name="remember" value="true">
				<div class="mto-rounded-md mtoshadow-sm mto-space-y-6">
					<div class="username">
						<label for="username" class="mto-block mto-text-sm mto-font-semibold mto-mb-2">
							<?php echo esc_html__('Username', 'masteriyo'); ?>
							<span class="mto-text-red-700">*</span>
						</label>
						<input id="username" name="username" type="text" required class="mto-px-4 mto-rounded mto-block mto-w-full mto-py-2 mto-border mto-border-gray-300 focus:mto-outline-none focus:mto-shadow-outline focus:mto-border-primary" placeholder="">
					</div>

					<div class="email">
						<label for="email" class="mto-block mto-text-sm mto-font-semibold mto-mb-2">
							<?php echo esc_html__('Email Address', 'masteriyo'); ?>
							<span class="mto-text-red-700">*</span>
						</label>
						<input id="email" name="email" type="text" required class="mto-px-4 mto-rounded mto-block mto-w-full mto-py-2 mto-border mto-border-gray-300 focus:mto-outline-none focus:mto-shadow-outline focus:mto-border-primary" placeholder="">
					</div>

					<div class="password">
						<label for="password" class="mto-block mto-text-sm mto-font-semibold mto-mb-2">
							<?php echo esc_html__('Password', 'masteriyo'); ?>
							<span class="mto-text-red-700">*</span>
						</label>
						<input id="password" name="password" type="password" autocomplete="current-password" required class="mto-px-4 mto-rounded mto-block mto-w-full mto-py-2 mto-border mto-border-gray-300 focus:mto-outline-none focus:mto-shadow-outline focus:mto-border-primary" placeholder="">
						<p>
							<?php echo esc_html__('Use 10 or more characters that are a mixture of letters, numbers, and symbols', 'masteriyo'); ?>
						</p>
					</div>

					<div class="confirm-password">
						<label for="confirm-password" class="mto-block mto-text-sm mto-font-semibold mto-mb-2">
							<?php echo esc_html__('Confirm Password', 'masteriyo'); ?>
						</label>
						<input id="confirm-password" name="confirm-password" type="password" autocomplete="current-password" required class="mto-px-4 mto-rounded mto-block mto-w-full mto-py-2 mto-border mto-border-gray-300 focus:mto-outline-none focus:mto-shadow-outline focus:mto-border-primary" placeholder="">
						<div class="mto-msg-different-passwords mto-hidden mto-text-red-700">
							<?php echo esc_html__( 'The passwords doesn\'t match', 'masteriyo' ); ?>
						</div>
					</div>
				</div>

				<div class="mto-my-6 md:mto-mb-10">
					<div class="remember-me mto-flex mto-items-center">
						<input id="accept-terms-and-conditions" name="accept-terms-and-conditions" value="yes" type="checkbox" class="mto-h-4 mto-w-4 mto-text-primary-600 focus:mto-primary-500 mto-border-gray-300 mto-rounded">
						<label for="accept-terms-and-conditions" class="mto-mb-0 mto-block mto-text-sm mto-text-gray-900">
							<?php /* translators: %s: Terms & Conditions Label */ ?>
							<span><?php printf(esc_html__('I accept the %s.', 'masteriyo'), '<a href="#" class="mto-underline">' . __('Terms & Conditions', 'masteriyo') . '</a>'); ?></span>
						</label>
					</div>
					<div class="mto-msg-must-accept-terms mto-hidden mto-text-red-700">
						<?php echo esc_html__( 'You must accept the Terms & Conditions to proceed', 'masteriyo' ); ?>
					</div>
				</div>

				<input class="mto-hidden" type="text" name="_wpnonce" value="<?php echo wp_create_nonce( 'masteriyo-register' ); ?>">
				<button type="submit" name="masteriyo-registration" value="yes" class="btn mto-capitalize btn-sign-up">
					<?php echo esc_html__('Get Started', 'masteriyo'); ?>
				</button>

				<div class="mto-notify-message mto-error-msg mto-text-red-700 mto-bg-red-100 mto-border-red-300 mto-hidden"></div>
				<div class="mto-notify-message mto-success-msg mto-hidden">
					<span><?php echo esc_html__('Registration complete.', 'masteriyo'); ?></span>
				</div>
			</form>

			<?php masteriyo_display_all_notices(); ?>
		</div>
	</div>
</section>

<?php

/**
 * masteriyo_after_registration_form_content hook.
 */
do_action('masteriyo_after_registration_form_content');
