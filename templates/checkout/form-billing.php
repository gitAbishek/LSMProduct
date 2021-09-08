<?php

/**
 * Masteriyo billing form.
 *
 * @package Masteriyo\Templates
 * @since 0.1.0
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

use Masteriyo\Notice;
use Masteriyo\Countries;

?>

<?php do_action( 'masteriyo_checkout_before_billing' ); ?>

<div class="mto-checkout-main">
	<h3 class="mto-checkout--title">
		<?php esc_html_e( 'Payment Details', 'masteriyo' ); ?>
	</h3>

	<form action="" class="mto-checkout--form">
		<div class="mto-checkout---fname-lname-wrapper mto-col-2">
			<div class="mto-checkout----fname">
				<label for="billing-first-name" class="mto-label">
					<?php esc_html_e( 'First Name', 'masteriyo' ); ?>
					<span>*</span>
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
					<span>*</span>
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
					<span>*</span>
				</label>
				<input type="text" id="billing-email" class="mto-input" name="billing_email" value="" />
			</div>
			<?php if ( masteriyo_notice_exists( 'billing_email', Notice::ERROR ) ) : ?>
				<div class="mto-error mto-danger-msg">
					<?php echo wp_kses_post( masteriyo_notice_by_id( 'billing_email', Notice::ERROR ) ); ?>
				</div>
			<?php endif; ?>
		</div>
	</form>
</div>
<?php do_action( 'masteriyo_checkout_after_billing' ); ?>
