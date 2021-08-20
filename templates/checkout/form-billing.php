<?php

/**
 * Masteriyo billing form.
 *
 * @package ThemeGrill\Masteriyo\Templates
 * @since 0.1.0
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

use ThemeGrill\Masteriyo\Notice;
use ThemeGrill\Masteriyo\Countries;

?>

<?php do_action( 'masteriyo_checkout_before_billing' ); ?>

<div class="mto-checkout-main">
	<h3 class="mto-checkout--title">
		<?php esc_html_e( 'Payment Details', 'masteriyo' ); ?>
	</h3>

	<!-- <div class="mto-checkout--alert-msg mto-alert mto-success-msg mto-show">Successfully form submited</div> -->

	<form action="" class="mto-checkout--form">
		<div class="mto-checkout---fname-lname-wrapper mto-col-2">
			<div class="mto-checkout----fname">
				<label for="billing-first-name" class="mto-label">
					<?php esc_html_e( 'First Name', 'masteriyo' ); ?>
				</label>

				<input type="text" id="billing-first-name" class="mto-input" name="billing_first_name" value="" />

				<?php if ( masteriyo_notice_exists( 'billing_first_name', Notice::ERROR ) ) : ?>
					<div class="mto-error mto-danger-msg">
						<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_first_name', Notice::ERROR ) ); ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="mto-checkout----lname">
				<label for="billing-last-name" class="mto-label">
					<?php esc_html_e( 'Last Name', 'masteriyo' ); ?>
				</label>

				<input type="text" id="billing-last-name" class="mto-input" name="billing_last_name" value="" />

				<?php if ( masteriyo_notice_exists( 'billing_last_name', Notice::ERROR ) ) : ?>
					<div class="mto-error mto-danger-msg">
						<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_last_name', Notice::ERROR ) ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<div class="mto-checkout---email-wrapper">
			<div class="mto-checkout----email">
				<label for="billing-email" class="mto-label">
					<?php esc_html_e( 'Email Address', 'masteriyo' ); ?>
				</label>
				<input type="text" id="billing-email" class="mto-input" name="billing_email" value="" />
			</div>
			<?php if ( masteriyo_notice_exists( 'billing_email', Notice::ERROR ) ) : ?>
				<div class="mto-error mto-danger-msg">
					<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_email', Notice::ERROR ) ); ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="mto-checkout---phone-wrapper">
			<div class="mto-checkout----phone">
				<label for="billing-phone" class="mto-label">
					<?php esc_html_e( 'Phone', 'masteriyo' ); ?>
				</label>
				<input type="tel" id="billing-phone" class="mto-input" name="billing_phone" value="" />
			</div>
			<?php if ( masteriyo_notice_exists( 'billing_phone', Notice::ERROR ) ) : ?>
				<div class="mto-error mto-danger-msg">
					<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_phone', Notice::ERROR ) ); ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="mto-checkout---company-name-wrapper">
			<div class="mto-checkout----company-name">
				<label for="billing-company" class="mto-label">
					<?php esc_html_e( 'Company Name', 'masteriyo' ); ?>
				</label>

				<input type="text" id="billing-company" class="mto-input" name="billing_company" />
			</div>

			<?php if ( masteriyo_notice_exists( 'billing_company', Notice::ERROR ) ) : ?>
				<div class="mto-error mto-danger-msg">
					<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_company', Notice::ERROR ) ); ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="mto-checkout---country-wrapper">
			<div class="mto-checkout----country">
				<label for="billing-country" class="mto-label">Country/Region</label>
				<div class="dropdown mto-country-dropdown">
					<select name="billing_country" id="billing-country" class="mto-input">
						<?php masteriyo( 'countries' )->country_dropdown_options(); ?>
					</select>
				</div>
			</div>
			<?php if ( masteriyo_notice_exists( 'billing_country', Notice::ERROR ) ) : ?>
				<div class="mto-error mto-danger-msg">
					<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_country', Notice::ERROR ) ); ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="mto-checkout---street-wrapper">
			<div class="mto-checkout----street">
				<label for="billing-address-1" class="mto-label">
					<?php esc_html_e( 'Street Address', 'masteriyo' ); ?>
				</label>

				<input type="text" id="billing-address-1" class="mto-input" name="billing_address_1" />
				<input type="text" id="billing-address-2" class="mto-input" name="billing_address_2" />
			</div>

			<?php if ( masteriyo_notice_exists( 'billing_address_1', Notice::ERROR ) ) : ?>
				<div class="mto-error mto-danger-msg">
					<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_address_1', Notice::ERROR ) ); ?>
				</div>
			<?php endif; ?>

			<?php if ( masteriyo_notice_exists( 'billing_address_2', Notice::ERROR ) ) : ?>
				<div class="mto-error mto-danger-msg">
					<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_address_2', Notice::ERROR ) ); ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="mto-checkout---state-wrapper">
			<div class="mto-checkout----state">
				<label for="billing-state" class="mto-label">
					<?php esc_html_e( 'State', 'masteriyo' ); ?>
				</label>

				<input class="mto-input" type="text" id="billing-state" name="billing_state" value="" />
			</div>
			<?php if ( masteriyo_notice_exists( 'billing_state', Notice::ERROR ) ) : ?>
				<div class="mto-error mto-danger-msg">
					<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_state', Notice::ERROR ) ); ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="mto-checkout---city-wrapper">
			<div class="mto-checkout----city">
				<label for="billing-city" class="mto-label">
					<?php esc_html_e( 'City / Town', 'masteriyo' ); ?>
				</label>
				<input class="mto-input" type="text" id="billing-city" name="billing_city" value="" />
			</div>
			<?php if ( masteriyo_notice_exists( 'billing_city', Notice::ERROR ) ) : ?>
				<div class="mto-error mto-danger-msg">
					<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_city', Notice::ERROR ) ); ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="mto-checkout---postcode-wrapper">
			<div class="mto-checkout----postcode">
				<label for="billing-postcode" class="mto-label">
					<?php esc_html_e( 'Postcode / ZIP code', 'masteriyo' ); ?>
				</label>

				<input class="mto-input" type="text" id="billing-postcode" name="billing_postcode" value="" />
			</div>

			<?php if ( masteriyo_notice_exists( 'billing_postcode', Notice::ERROR ) ) : ?>
				<div class="mto-error mto-danger-msg">
					<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_postcode', Notice::ERROR ) ); ?>
				</div>
			<?php endif; ?>
		</div>
	</form>
</div>
<?php do_action( 'masteriyo_checkout_after_billing' ); ?>
