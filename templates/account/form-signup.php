<?php

/**
 * Sign up form template content.
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$session = masteriyo( 'session' );

/**
 * Fires before rendering user registration form.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_before_registration_form_content' );

?>

<section class="masteriyo-signup">
		<div class="masteriyo-signup--wrapper masteriyo-form-container">
			<h3 class="masteriyo-signup--title"><?php esc_html_e( 'Create Your Account', 'masteriyo' ); ?></h3>

			<?php masteriyo_display_all_notices(); ?>

			<form id="masteriyo-signup--form" class="masteriyo-signup-form" method="post">
				<input type="hidden" name="remember" value="true">
				<?php wp_nonce_field( 'masteriyo-register' ); ?>

				<div class="masteriyo-first-name">
					<label for="first-name" class="masteriyo-label">
						<?php esc_html_e( 'First Name', 'masteriyo' ); ?>
						<span class="masteriyo-text-red">*</span>
					</label>
					<input id="first-name" class="masteriyo-input" name="first-name" type="text" value="<?php echo esc_attr( $session->get( 'user-registration.first-name' ) ); ?>" >
				</div>

				<div class="masteriyo-last-name">
					<label for="last-name" class="masteriyo-label">
						<?php esc_html_e( 'Last Name', 'masteriyo' ); ?>
						<span class="masteriyo-text-red">*</span>
					</label>
					<input id="last-name" class="masteriyo-input" name="last-name" type="text" value="<?php echo esc_attr( $session->get( 'user-registration.last-name' ) ); ?>">
				</div>
				<div class="masteriyo-username">
					<label for="username" class="masteriyo-label">
						<?php esc_html_e( 'Username', 'masteriyo' ); ?>
						<span class="masteriyo-text-red">*</span>
					</label>
					<input id="username" class="masteriyo-input" name="username" type="text"  value="<?php echo esc_attr( $session->get( 'user-registration.username' ) ); ?>">
				</div>

				<div class="masteriyo-email">
					<label for="email" class="masteriyo-label">
						<?php esc_html_e( 'Email Address', 'masteriyo' ); ?>
						<span class="masteriyo-text-red">*</span>
					</label>
					<input id="email" class="masteriyo-input" name="email" type="text"  value="<?php echo esc_attr( $session->get( 'user-registration.email' ) ); ?>">
				</div>

				<div class="masteriyo-password">
					<label for="password" class="masteriyo-label">
						<?php esc_html_e( 'Password', 'masteriyo' ); ?>
						<span class="masteriyo-text-red">*</span>
					</label>
					<input id="password" class="masteriyo-input" name="password" type="password" autocomplete="current-password"  value="<?php echo esc_attr( $session->get( 'user-registration.password' ) ); ?>">
				</div>

				<div class="masteriyo-confirm-password">
					<label for="confirm-password" class="masteriyo-label">
						<?php esc_html_e( 'Confirm Password', 'masteriyo' ); ?>
						<span class="masteriyo-text-red">*</span>
					</label>
					<input id="confirm-password" class="masteriyo-input" name="confirm-password" type="password" autocomplete="current-password" >
				</div>

				<?php
				/**
				 * Fires before render of submit button in student registration form.
				 *
				 * @since x.x.x
				 */
				do_action( 'masteriyo_registration_form_before_submit_button' );
				?>

				<button type="submit" name="masteriyo-registration" value="yes" class="masteriyo-btn-signup masteriyo-btn masteriyo-btn-primary">
					<?php esc_html_e( 'Register', 'masteriyo' ); ?>
				</button>

				<div class="masteriyo-notify-message masteriyo-alert masteriyo-danger-msg masteriyo-hidden"></div>
				<div class="masteriyo-notify-message masteriyo-alert masteriyo-success-msg masteriyo-hidden">
					<span><?php esc_html_e( 'Registration complete.', 'masteriyo' ); ?></span>
				</div>
			</form>
		</div>
</section>

<?php

/**
 * Fires after rendering user registration form.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_after_registration_form_content' );
