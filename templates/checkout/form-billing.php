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

<div class="checkout-main">
	<h2 class="checkout--title">
		<?php esc_html_e( 'Payment Details', 'masteriyo' ); ?>
	</h2>

	<!-- <div class="checkout-alert-msg alert success-msg show">Successfully form submited</div> -->

	<form action="" class="checkout--form">
		<div class="checkout---fname-lname-wrapper">
			<div class="checkout----fname col-2">
				<label for="first-name">
					<?php esc_html_e( 'First Name', 'masteriyo' ); ?>
				</label>
				<input
					type="text" id="billing-first-name" name="billing_first_name"
					value="" />
			<?php if ( masteriyo_notice_exists( 'billing_first_name', Notice::ERROR ) ) : ?>
				<div class="error danger-msg">
				<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_first_name', Notice::ERROR ) ); ?>
				</div>
			<?php endif; ?>
			</div>

			<div class="checkout----lname col-2">
				<label for="last-name">
					<?php esc_html_e( 'Last Name', 'masteriyo' ); ?>
				</label>
				<input
					type="text" id="billing-last-name" name="billing_last_name"
					value="" />

				<?php if ( masteriyo_notice_exists( 'billing_last_name', Notice::ERROR ) ) : ?>
					<div class="error danger-msg">
					<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_last_name', Notice::ERROR ) ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<div class="checkout---email-wrapper">
			<div class="checkout----email col-1">
				<label for="email">
					<?php esc_html_e( 'Email Address', 'masteriyo' ); ?>
				</label>
				<input
					type="text" id="billing-email" name="billing_email"
					value="" />
			</div>
		<?php if ( masteriyo_notice_exists( 'billing_email', Notice::ERROR ) ) : ?>
			<div class="error danger-msg">
			<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_email', Notice::ERROR ) ); ?>
			</div>
		<?php endif; ?>
		</div>

		<div class="checkout---phone-wrapper">
			<div class="checkout----phone col-1">
				<label for="phone">
					<?php esc_html_e( 'Phone', 'masteriyo' ); ?>
				</label>
				<input
					type="tel" id="billing-phone" name="billing_phone"
					value="" />
			</div>
		<?php if ( masteriyo_notice_exists( 'billing_phone', Notice::ERROR ) ) : ?>
			<div class="error danger-msg">
			<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_phone', Notice::ERROR ) ); ?>
			</div>
		<?php endif; ?>
		</div>

		<div class="checkout---company-name-wrapper">
			<div class="checkout----company-name col-1">
				<label for="company-name">
					<?php esc_html_e( 'Company Name', 'masteriyo' ); ?>
				</label>
				<input type="text" id="billing-company" name="billing_company" />
			</div>
		<?php if ( masteriyo_notice_exists( 'billing_company', Notice::ERROR ) ) : ?>
			<div class="error danger-msg">
			<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_company', Notice::ERROR ) ); ?>
			</div>
		<?php endif; ?>
		</div>

		<div class="checkout---country-wrapper">
			<div class="checkout----country col-1">
				<label for="country">Country/Region</label>
				<div class="dropdown">
					<select name="billing_country" id="billing-country">
					<?php masteriyo( 'countries' )->country_dropdown_options(); ?>
					</select>
				</div>
			</div>
		<?php if ( masteriyo_notice_exists( 'billing_country', Notice::ERROR ) ) : ?>
			<div class="error danger-msg">
				<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_country', Notice::ERROR ) ); ?>
			</div>
		<?php endif; ?>
		</div>

		<div class="checkout---street-wrapper">
			<div class="checkout----street col-1">
				<label for="street1">
					<?php esc_html_e( 'Street Address', 'masteriyo' ); ?>
				</label>
				<input type="text" id="billing-address-1" name="billing_address_1" />
				<input type="text" id="billing-address-2" name="billing_address_2" />
			</div>

		<?php if ( masteriyo_notice_exists( 'billing_address_1', Notice::ERROR ) ) : ?>
			<div class="error danger-msg">
			<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_address_1', Notice::ERROR ) ); ?>
			</div>
		<?php endif; ?>

		<?php if ( masteriyo_notice_exists( 'billing_address_2', Notice::ERROR ) ) : ?>
			<div class="error danger-msg">
			<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_address_2', Notice::ERROR ) ); ?>
			</div>
		<?php endif; ?>
		</div>

		<div class="checkout---postcode-wrapper">
			<div class="checkout----postcode col-1">
				<label for="postcode">
					<?php esc_html_e( 'State', 'masteriyo' ); ?>
				</label>
				<input
					class="danger-msg" type="text" id="billing-state"
					name="billing_state"
					value="" />
			</div>
		<?php if ( masteriyo_notice_exists( 'billing_state', Notice::ERROR ) ) : ?>
			<div class="error danger-msg">
			<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_state', Notice::ERROR ) ); ?>
			</div>
		<?php endif; ?>
		</div>

		<div class="checkout---postcode-wrapper">
			<div class="checkout----postcode col-1">
				<label for="postcode">
					<?php esc_html_e( 'City / Town', 'masteriyo' ); ?>
				</label>
				<input
					class="danger-msg" type="text" id="billing-city"
					name="billing_city"
					value="" />
			</div>
		<?php if ( masteriyo_notice_exists( 'billing_city', Notice::ERROR ) ) : ?>
			<div class="error danger-msg">
			<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_city', Notice::ERROR ) ); ?>
			</div>
		<?php endif; ?>
		</div>

		<div class="checkout---postcode-wrapper">
			<div class="checkout----postcode col-1">
				<label for="postcode">
					<?php esc_html_e( 'Postcode / ZIP code', 'masteriyo' ); ?>
				</label>
				<input
					class="danger-msg" type="text" id="billing-postcode"
					name="billing_postcode"
					value="" />
			</div>
		<?php if ( masteriyo_notice_exists( 'billing_postcode', Notice::ERROR ) ) : ?>
			<div class="error danger-msg">
			<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_postcode', Notice::ERROR ) ); ?>
			</div>
		<?php endif; ?>
		</div>
	</form>
</div>
<?php do_action( 'masteriyo_checkout_after_billing' ); ?>
