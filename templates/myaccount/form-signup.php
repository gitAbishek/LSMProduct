<?php

/**
 * Sign up form template content.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * masteriyo_before_registration_form_content hook.
 */
do_action( 'masteriyo_before_registration_form_content' );

?>

<section class="mto-signup">
		<div class="mto-signup--wrapper mto-form-container">
			<h3 class="mto-signup--title"><?php echo esc_html__( 'Create Your Account', 'masteriyo' ); ?></h3>

			<?php masteriyo_display_all_notices(); ?>

			<form id="mto-signup--form" class="mto-signup-form" method="post">
				<input type="hidden" name="remember" value="true">
				<div class="">
					<div class="mto-first-name">
						<label for="first-name" class="mto-label">
							<?php echo esc_html__( 'First Name', 'masteriyo' ); ?>
							<span class="mto-text-red">*</span>
						</label>
						<input id="first-name" class="mto-input" name="first-name" type="text" >
					</div>

					<div class="mto-last-name">
						<label for="last-name" class="mto-label">
							<?php echo esc_html__( 'Last Name', 'masteriyo' ); ?>
							<span class="mto-text-red">*</span>
						</label>
						<input id="last-name" class="mto-input" name="last-name" type="text" >
					</div>

					<div class="mto-username">
						<label for="username" class="mto-label">
							<?php echo esc_html__( 'Username', 'masteriyo' ); ?>
							<span class="mto-text-red">*</span>
						</label>
						<input id="username" class="mto-input" name="username" type="text"  placeholder="">
					</div>

					<div class="mto-email">
						<label for="email" class="mto-label">
							<?php echo esc_html__( 'Email Address', 'masteriyo' ); ?>
							<span class="mto-text-red">*</span>
						</label>
						<input id="email" class="mto-input" name="email" type="text"  placeholder="">
					</div>

					<div class="mto-password">
						<label for="password" class="mto-label">
							<?php echo esc_html__( 'Password', 'masteriyo' ); ?>
							<span class="mto-text-red">*</span>
						</label>
						<input id="password" class="mto-input" name="password" type="password" autocomplete="current-password"  placeholder="">
					</div>

					<div class="mto-confirm-password">
						<label for="confirm-password" class="mto-label">
							<?php echo esc_html__( 'Confirm Password', 'masteriyo' ); ?>
							<span class="mto-text-red">*</span>
						</label>
						<input id="confirm-password" class="mto-input" name="confirm-password" type="password" autocomplete="current-password"  placeholder="">
						<div class="mto-msg-different-passwords masteriyo-hidden mto-text-red">
							<?php echo esc_html__( 'The passwords doesn\'t match', 'masteriyo' ); ?>
						</div>
					</div>
				</div>

					<div class="mto-remember-me">
						<input id="accept-terms-and-conditions" name="accept-terms-and-conditions" value="yes" type="checkbox">
						<label for="accept-terms-and-conditions" class="mto-mb-0 mto-block mto-text-sm mto-text-gray-900">
							<span>
								<?php
									echo wp_kses_post(
										sprintf(
											/* translators: %s: Terms & Conditions Label */
											__( 'I accept the %s.', 'masteriyo' ),
											'<a href="' . esc_url( masteriyo_get_page_permalink( 'terms_conditions' ) ) . '" class="mto-link-primary">' . __( 'Terms & Conditions', 'masteriyo' ) . '</a>'
										)
									);
									?>
							</span>
							<span class="mto-text-red">*</span>
						</label>
					</div>
					<div class="mto-msg-must-accept-terms masteriyo-hidden mto-text-red">
						<?php echo esc_html__( 'You must accept the Terms & Conditions to proceed', 'masteriyo' ); ?>
					</div>

				<?php wp_nonce_field( 'masteriyo-register' ); ?>
				<button type="submit" name="masteriyo-registration" value="yes" class="mto-btn-signup mto-btn mto-btn-primary">
					<?php echo esc_html__( 'Register', 'masteriyo' ); ?>
				</button>

				<div class="mto-notify-message mto-alert mto-danger-msg masteriyo-hidden"></div>
				<div class="mto-notify-message mto-alert mto-success-msg masteriyo-hidden">
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
