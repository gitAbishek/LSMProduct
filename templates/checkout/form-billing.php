<?php
/**
 * Masteriyo billing form.
 *
 * @package ThemeGrill\Masteriyo\Templates
 * @since 0.1.0
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

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
					type="text" id="first-name" name="first-name"
					value="<?php echo esc_attr( $user->get_first_name() ); ?>" />
			</div>

			<div class="checkout----lname col-2">
				<label for="last-name">
					<?php esc_html_e( 'Last Name', 'masteriyo' ); ?>
				</label>
				<input
					type="text" id="last-name" name="last-name"
					value="<?php echo esc_attr( $user->get_last_name() ); ?>" />
			</div>
		</div>

		<div class="checkout---email-wrapper">
			<div class="checkout----email col-1">
				<label for="email">
					<?php esc_html_e( 'Email Address', 'masteriyo' ); ?>
				</label>
				<input
					type="text" id="email" name="email"
					value="<?php echo esc_attr( $user->get_email() ); ?>" />
			</div>
		</div>

		<div class="checkout---phone-wrapper">
			<div class="checkout----phone col-1">
				<label for="phone">
					<?php esc_html_e( 'Phone', 'masteriyo' ); ?>
				</label>
				<input
					type="tel" id="phone" name="phone"
					value="<?php echo esc_attr( '' ); ?>" />
			</div>
		</div>

		<div class="checkout---company-name-wrapper">
			<div class="checkout----company-name col-1">
				<label for="company-name">
					<?php esc_html_e( 'Company Name', 'masteriyo' ); ?>
				</label>
				<input type="text" id="company-name" name="company-name" />
			</div>
		</div>

		<div class="checkout---country-wrapper">
			<div class="checkout----country col-1">
				<label for="country">Country/Region</label>
				<div class="dropdown">
					<select name="country" id="country">
					<?php Countries::instance()->country_dropdown_options( $user->get_country() ); ?>
					</select>
				</div>

			</div>
		</div>

		<div class="checkout---street-wrapper">
			<div class="checkout----street col-1">
				<label for="street1">
					<?php esc_html_e( 'Street Address', 'masteriyo' ); ?>
				</label>
				<input type="text" id="street1" name="street1" />
				<input type="text" id="street2" name="street2" />
			</div>
		</div>

		<div class="checkout---postcode-wrapper">
			<div class="checkout----postcode col-1">
				<label for="postcode">
					<?php esc_html_e( 'Postcode / ZIP code', 'masteriyo' ); ?>
				</label>
				<input
					class="danger-msg" type="text" id="postcode" name="postcode"
					value="<?php esc_attr( $user->get_zip_code() ); ?>" />
			</div>
			<div class="error danger-msg">This field is required</div>
		</div>

	</form>
</div>
<?php do_action( 'masteriyo_checkout_after_billing' ); ?>
