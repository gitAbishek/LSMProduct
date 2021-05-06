<?php
/**
 * Masteriyo checkout form payment.
 *
 * @package ThemeGrill\Masteriyo\Templates;
 * @since 0.1.0
 * @version 0.1.0
 */

 defined( 'ABSPATH' ) || exit;
?>
<div id="masteriyo-payments" class="checkout-summary-payment-method">
	<?php if ( masteriyo( 'cart' )->needs_payment() ) : ?>
		<ul class="masteriyo-payment-methods payment-methods methods">
			<?php
			if ( ! empty( $available_gateways ) ) {
				foreach ( $available_gateways as $gateway ) {
					masteriyo_get_template( 'checkout/pa
					yment-method.php', array( 'gateway' => $gateway ) );
				}
			} else {
				$message = esc_html__( 'Please fill in your details above to see available payment methods.', 'masteriyo' );
				if ( masteriyo_get_current_user()->get_billing_country() ) {
					$message = esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'masteriyo' );
				}
				printf(
					'<li class="masteriyo-notice masteriyo-notice--info masteriyo-info">%s</li>',
					$message // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			}
			?>
		</ul>
	<?php endif; ?>
	<input type="hidden" name="payment_method" value="cod" />
</div>

<?php do_action( 'masteriyo_checkout_summary_before_submit' ); ?>

<button
	type="submit"
	class="checkout-confirm-payment btn-primary full-w button alt"
	id="masteriyo-place-order"
	name="masteriyo_checkout_place_order">
	<?php
		printf(
			esc_html( 'Confirm Payment: %s', 'masteriyo' ),
			masteriyo_price( masteriyo( 'cart' )->get_total() )
		);
		?>
</button>

<?php
	wp_nonce_field( 'masteriyo-process_checkout', 'masteriyo-process-checkout-nonce' );
	do_action( 'masteriyo_checkout_summary_after_submit' );

