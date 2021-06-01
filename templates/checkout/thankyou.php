<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/checkout/thankyou.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package ThemeGrill\Masteriyo\Templates
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! $order ) {
	printf(
		'<p class = "masteriyo-notice masteriyo-notice--success masteriyo-thankyou-order-received">%s</p>',
		apply_filters( 'masteriyo_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'masteriyo' ), null ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	);

	return;
}

?>

<div class="masteriyo-order">
<?php do_action( 'masteriyo_before_thankyou', $order->get_id() ); ?>

	<?php if ( $order->has_status( 'failed' ) ) : ?>

		<p class="masteriyo-notice masteriyo-notice--error masteriyo-thankyou-order-failed">
			<?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'masteriyo' ); ?>
		</p>

		<p class="masteriyo-notice masteriyo-notice--error masteriyo-thankyou-order-failed-actions">
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay">
				<?php esc_html_e( 'Pay', 'masteriyo' ); ?>
			</a>
			<?php if ( is_user_logged_in() ) : ?>
				<a href="<?php echo esc_url( masteriyo_get_page_permalink( 'myaccount' ) ); ?>" class="button pay">
					<?php esc_html_e( 'My account', 'masteriyo' ); ?>
				</a>
			<?php endif; ?>
		</p>

	<?php else : ?>

		<p class="masteriyo-notice masteriyo-notice--success masteriyo-thankyou-order-received"><?php echo apply_filters( 'masteriyo_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'masteriyo' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

		<ul class="masteriyo-order-overview masteriyo-thankyou-order-details order_details">

			<li class="masteriyo-order-overview__order order">
				<?php esc_html_e( 'Order number:', 'masteriyo' ); ?>
				<strong><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
			</li>

			<li class="masteriyo-order-overview__date date">
				<?php esc_html_e( 'Date:', 'masteriyo' ); ?>
				<strong><?php echo masteriyo_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
			</li>

			<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
				<li class="masteriyo-order-overview__email email">
					<?php esc_html_e( 'Email:', 'masteriyo' ); ?>
					<strong><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</li>
			<?php endif; ?>

			<li class="masteriyo-order-overview__total total">
				<?php esc_html_e( 'Total:', 'masteriyo' ); ?>
				<strong><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
			</li>

			<?php if ( $order->get_payment_method_title() ) : ?>
				<li class="masteriyo-order-overview__payment-method method">
					<?php esc_html_e( 'Payment method:', 'masteriyo' ); ?>
					<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
				</li>
			<?php endif; ?>

		</ul>

	<?php endif; ?>

	<?php do_action( 'masteriyo_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
	<?php do_action( 'masteriyo_thankyou', $order->get_id() ); ?>

</div>
<?php

