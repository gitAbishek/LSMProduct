<?php
/**
 * Login form template content.
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Fires before rendering login form section in account page.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_before_login_form_content' );

?>
<!-- re -->
<section class="masteriyo-login">
		<div class="masteriyo-login--wrapper masteriyo-form-container">
			<h3 class="masteriyo-login--title"><?php echo esc_html__( 'Sign In', 'masteriyo' ); ?></h3>

			<form id="masteriyo-login--form" class="masteriyo-login--form" method="post">
				<input type="hidden" name="action" value="masteriyo_login">

				<?php wp_nonce_field( 'masteriyo_login_nonce' ); ?>

				<div class="masteriyo-username">
					<label for="username-email-address" class="masteriyo-label"><?php echo esc_html__( 'Username or Email', 'masteriyo' ); ?></label>
					<input id="username-email-address" name="username" type="text" required class="masteriyo-input" placeholder="">
				</div>

				<div class="masteriyo-password">
					<label for="password" class="masteriyo-label"><?php echo esc_html__( 'Password', 'masteriyo' ); ?></label>
					<input id="password" class="masteriyo-input" name="password" type="password" autocomplete="current-password" required placeholder="">
				</div>

				<div class="masteriyo-remember-forgot">
					<div class="masteriyo-remember-me">
						<input id="remember_me" name="remember_me" type="checkbox">
						<label for="remember_me">
							<?php echo esc_html__( 'Remember me', 'masteriyo' ); ?>
						</label>
					</div>

					<div class="masteriyo-forgot-password">
						<a href="<?php echo esc_url( masteriyo_get_account_endpoint_url( 'reset-password' ) ); ?>" class="masteriyo-link-primary">
							<?php echo esc_html__( 'Forgot your password?', 'masteriyo' ); ?>
						</a>
					</div>
				</div>


				<?php
				/**
				 * Fires before render of login button in login form.
				 *
				 * @since x.x.x
				 */
				do_action( 'masteriyo_login_form_before_submit_button' );
				?>

				<div class="masteriyo-btn-wrapper">
					<button type="submit" class="masteriyo-login-btn masteriyo-btn masteriyo-primary">
						<?php echo esc_html__( 'Sign in', 'masteriyo' ); ?>
					</button>

					<div class="masteriyo-login-signup">
						<span><?php echo esc_html__( 'Don\'t have an account?', 'masteriyo' ); ?></span>
						<a
							href="<?php echo esc_url( masteriyo_get_account_endpoint_url( 'signup' ) ); ?>"
							class="masteriyo-link-primary"
						>
							<?php echo esc_html__( 'Sign Up Now', 'masteriyo' ); ?>
						</a>
					</div>
				</div>

				<div id="masteriyo-login-error-msg" class="masteriyo-hidden masteriyo-notify-message masteriyo-alert masteriyo-danger-msg"></div>
			</form>
			<?php masteriyo_display_all_notices(); ?>

		</div>
</section>

<?php

/**
 * Fires after rendering login form section in account page.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_after_login_form_content' );
