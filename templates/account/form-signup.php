<?php

/**
 * Sign up form template content.
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$session = masteriyo( 'session' );

/**
 * masteriyo_before_registration_form_content hook.
 */
do_action( 'masteriyo_before_registration_form_content' );

?>

<section class="masteriyo-signup">
		<div class="masteriyo-signup--wrapper masteriyo-form-container">
			<h3 class="masteriyo-signup--title"><?php echo esc_html__( 'Create Your Account', 'masteriyo' ); ?></h3>

			<?php masteriyo_display_all_notices(); ?>

			<form id="masteriyo-signup--form" class="masteriyo-signup-form" method="post">
				<input type="hidden" name="remember" value="true">
				<div class="">
					<div class="masteriyo-first-name">
						<label for="first-name" class="masteriyo-label">
							<?php echo esc_html__( 'First Name', 'masteriyo' ); ?>
							<span class="masteriyo-text-red">*</span>
						</label>
						<input id="first-name" class="masteriyo-input" name="first-name" type="text" value="<?php echo esc_attr( $session->get( 'user-registration.first-name' ) ); ?>" >
					</div>

					<div class="masteriyo-last-name">
						<label for="last-name" class="masteriyo-label">
							<?php echo esc_html__( 'Last Name', 'masteriyo' ); ?>
							<span class="masteriyo-text-red">*</span>
						</label>
						<input id="last-name" class="masteriyo-input" name="last-name" type="text" value="<?php echo esc_attr( $session->get( 'user-registration.last-name' ) ); ?>">
					</div>
					<div class="masteriyo-username">
						<label for="username" class="masteriyo-label">
							<?php echo esc_html__( 'Username', 'masteriyo' ); ?>
							<span class="masteriyo-text-red">*</span>
						</label>
						<input id="username" class="masteriyo-input" name="username" type="text"  value="<?php echo esc_attr( $session->get( 'user-registration.username' ) ); ?>">
					</div>

					<div class="masteriyo-email">
						<label for="email" class="masteriyo-label">
							<?php echo esc_html__( 'Email Address', 'masteriyo' ); ?>
							<span class="masteriyo-text-red">*</span>
						</label>
						<input id="email" class="masteriyo-input" name="email" type="text"  value="<?php echo esc_attr( $session->get( 'user-registration.email' ) ); ?>">
					</div>

					<div class="masteriyo-password">
						<label for="password" class="masteriyo-label">
							<?php echo esc_html__( 'Password', 'masteriyo' ); ?>
							<span class="masteriyo-text-red">*</span>
						</label>
						<input id="password" class="masteriyo-input" name="password" type="password" autocomplete="current-password"  value="<?php echo esc_attr( $session->get( 'user-registration.password' ) ); ?>">
					</div>

					<div class="masteriyo-confirm-password">
						<label for="confirm-password" class="masteriyo-label">
							<?php echo esc_html__( 'Confirm Password', 'masteriyo' ); ?>
							<span class="masteriyo-text-red">*</span>
						</label>
						<input id="confirm-password" class="masteriyo-input" name="confirm-password" type="password" autocomplete="current-password" >
					</div>
				</div>

					<div class="masteriyo-remember-me">
						<input id="accept-terms-and-conditions" name="accept-terms-and-conditions" value="yes" type="checkbox">
						<label for="accept-terms-and-conditions" class="masteriyo-mb-0 masteriyo-block masteriyo-text-sm masteriyo-text-gray-900">
							<span>
								<?php
									echo wp_kses_post(
										sprintf(
											/* translators: %s: Terms & Conditions Label */
											__( 'I accept the %s.', 'masteriyo' ),
											'<a href="' . esc_url( masteriyo_get_page_permalink( 'terms_conditions' ) ) . '" class="masteriyo-link-primary">' . __( 'Terms & Conditions', 'masteriyo' ) . '</a>'
										)
									);
									?>
							</span>
							<span class="mto-text-red">*</span>
						</label>
					</div>
					<div class="masteriyo-msg-must-accept-terms masteriyo-hidden masteriyo-text-red">
						<?php echo esc_html__( 'You must accept the Terms & Conditions to proceed', 'masteriyo' ); ?>
					</div>

				<?php wp_nonce_field( 'masteriyo-register' ); ?>
				<button type="submit" name="masteriyo-registration" value="yes" class="masteriyo-btn-signup masteriyo-btn masteriyo-btn-primary">
					<?php echo esc_html__( 'Register', 'masteriyo' ); ?>
				</button>

				<div class="masteriyo-notify-message masteriyo-alert masteriyo-danger-msg masteriyo-hidden"></div>
				<div class="masteriyo-notify-message masteriyo-alert masteriyo-success-msg masteriyo-hidden">
					<span><?php echo esc_html__( 'Registration complete.', 'masteriyo' ); ?></span>
				</div>
			</form>
		</div>
</section>

<?php

/**
 * masteriyo_after_registration_form_content hook.
 */
do_action( 'masteriyo_after_registration_form_content' );
