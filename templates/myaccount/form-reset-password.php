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

<section class="mto-reset">
		<div class="mto-reset--wrapper mto-form-container">
			<h3 class="mto-reset--title"><?php echo esc_html__('New password', 'masteriyo'); ?></h3>
			<p class="mto-reset--msg"><?php echo apply_filters('masteriyo_reset_password_message', esc_html__('Enter a new password below.', 'masteriyo')); ?></p>

			<form id="mto-reset--form" class="mto-reset--form" method="post">
				<input type="hidden" name="remember" value="true">
					<div class="mto-username">
						<label for="password" class="mto-label"><?php echo esc_html__( 'New password', 'masteriyo' ); ?><span class="mto-text-red">*</span></label>
						<input id="password" class="mto-input" name="password" type="password" required autocomplete="new-password" >
					</div>
					<div class="mto-password">
						<label for="confirm-password" class="mto-label"><?php echo esc_html__( 'Re-enter new password', 'masteriyo' ); ?><span class="mto-text-red">*</span></label>
						<input id="confirm-password" class="mto-input" name="confirm-password" type="password" required autocomplete="new-password" >
					</div>

				<div class="mto-btn-wrapper">
					<button type="submit" name="masteriyo-password-reset" value="yes" class="mto-reset-btn mto-btn mto-primary">
						<?php echo esc_html__('Reset', 'masteriyo'); ?>
					</button>

					<div class="mto-reset-signin">
						<a href="<?php echo esc_url( masteriyo_get_page_permalink( 'myaccount' ) ); ?>" class="mto-link-primary">
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
</section>

<?php

do_action('masteriyo_after_password_reset_form');

