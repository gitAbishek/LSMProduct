<?php
/**
 * Password reset form.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/myaccount/form-reset-password.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates\MyAccount
 * @version 0.1.0
 */

defined('ABSPATH') || exit;

do_action('masteriyo_before_password_reset_form');

?>

<section class="reset-form mto-container mto-mx-auto">
	<div class="mto-flex mto-justify-center mto-items-center mto-h-screen">

		<div class="mto-form-wrapper">
			<h2 class="mto-font-semibold mto-text-2xl mto-mb-4"><?php echo esc_html__('New password', 'masteriyo'); ?></h2>

			<p><?php echo apply_filters('masteriyo_reset_password_message', esc_html__('Enter a new password below.', 'masteriyo')); ?></p>

			<form class="mto-mt-10" method="post">
				<input type="hidden" name="remember" value="true">
				<div class="mto-rounded-md mtoshadow-sm">
					<div class="mto-mb-6">
						<label for="password" class="mto-block mto-text-sm mto-font-semibold mto-mb-2"><?php echo esc_html__( 'New password', 'masteriyo' ); ?><span class="mto-text-red-700">*</span></label>
						<input id="password" name="password" type="password" required autocomplete="new-password" class="mto-px-4 mto-rounded mto-block mto-w-full mto-py-2 mto-border mto-border-gray-300 focus:mto-outline-none focus:mto-shadow-outline focus:mto-border-primary">
					</div>
				</div>
				<div class="mto-rounded-md mtoshadow-sm">
					<div class="mto-mb-6">
						<label for="confirm-password" class="mto-block mto-text-sm mto-font-semibold mto-mb-2"><?php echo esc_html__( 'Re-enter new password', 'masteriyo' ); ?><span class="mto-text-red-700">*</span></label>
						<input id="confirm-password" name="confirm-password" type="password" required autocomplete="new-password" class="mto-px-4 mto-rounded mto-block mto-w-full mto-py-2 mto-border mto-border-gray-300 focus:mto-outline-none focus:mto-shadow-outline focus:mto-border-primary">
					</div>
				</div>

				<div class="mto-block md:mto-flex mto-items-center md:mto-space-x-6">
					<button type="submit" name="masteriyo-password-reset" value="yes" class="btn mto-capitalize sign-in">
						<?php echo esc_html__('Reset', 'masteriyo'); ?>
					</button>

					<div class="go-back mto-text-sm mto-mt-6 md:mto-my-0 mto-text-center md:mto-text-left">
						<a href="<?php echo esc_url( masteriyo_get_page_permalink( 'myaccount' ) ); ?>" class="mto-font-medium mto-text-primary-800 hover:mto-text-primary-700 hover:mto-underline">
							<?php echo esc_html__('Go back to sign-in', 'masteriyo'); ?>
						</a>
					</div>
				</div>

				<input type="hidden" name="reset_key" value="<?php echo esc_attr( $key ); ?>" />
				<input type="hidden" name="reset_login" value="<?php echo esc_attr( $login ); ?>" />

				<?php do_action('masteriyo_password_reset_form'); ?>

				<?php wp_nonce_field( 'masteriyo-password-reset' ); ?>
			</form>

			<?php masteriyo_display_all_notices(); ?>
		</div>
	</div>
</section>

<?php

do_action('masteriyo_after_password_reset_form');

