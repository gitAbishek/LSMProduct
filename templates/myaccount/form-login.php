<?php
/**
 * Login form template content.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * masteriyo_before_login_form_content hook.
 */
do_action( 'masteriyo_before_login_form_content' );

?>
<!-- re -->
<section class="mto-login">
		<div class="mto-login--wrapper mto-form-container">
			<h3 class="mto-login--title"><?php echo esc_html__( 'Sign In', 'masteriyo' ); ?></h3>

			<form id="mto-login--form" class="mto-login--form">
				<input type="hidden" name="remember" value="true">
				<div class="mto-username">
					<label for="username-email-address" class="mto-label"><?php echo esc_html__( 'Username or Email', 'masteriyo' ); ?></label>
					<input id="username-email-address" name="text" type="text" required class="mto-input" placeholder="">
				</div>

				<div class="mto-password">
					<label for="password" class="mto-label"><?php echo esc_html__( 'Password', 'masteriyo' ); ?></label>
					<input id="password" class="mto-input" name="password" type="password" autocomplete="current-password" required placeholder="">
				</div>

				<div class="mto-remember-forgot">
					<div class="mto-remember-me">
						<input id="remember_me" name="remember_me" type="checkbox">
						<label for="remember_me">
							<?php echo esc_html__( 'Remember me', 'masteriyo' ); ?>
						</label>
					</div>

					<div class="mto-forgot-password">
						<a href="<?php echo esc_url( masteriyo_get_account_endpoint_url( 'reset-password' ) ); ?>" class="mto-link-primary">
							<?php echo esc_html__( 'Forgot your password?', 'masteriyo' ); ?>
						</a>
					</div>
				</div>

				<div class="mto-btn-wrapper">
					<button type="submit" class="mto-login-btn mto-btn mto-primary">
						<?php echo esc_html__( 'Sign in', 'masteriyo' ); ?>
					</button>

					<div class="mto-login-signup">
						<span><?php echo esc_html__( 'Don\'t have an account?', 'masteriyo' ); ?></span>
						<a
							href="<?php echo esc_url( masteriyo_get_account_endpoint_url( 'signup' ) ); ?>"
							class="mto-link-primary"
						>
							<?php echo esc_html__( 'Sign Up Now', 'masteriyo' ); ?>
						</a>
					</div>
				</div>

				<div id="mto-login-error-msg" class="mto-hidden mto-notify-message mto-alert mto-danger-msg"></div>
			</form>
			<?php masteriyo_display_all_notices(); ?>

		</div>
</section>

<?php

/**
 * masteriyo_after_login_form_content hook.
 */
do_action( 'masteriyo_after_login_form_content' );
