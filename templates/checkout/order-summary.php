<?php
/**
 * Order summary.
 *
 * @package Masteriyo\Templates
 * @since 0.1.0
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="mto-checkout-summary-your-order">
	<h2 class="mto-checkout-summary--title">
		<?php esc_html_e( 'Your Order', 'masteriyo' ); ?>
	</h2>
	<ul class="mto-checkout-summary-order-details">
		<li class="h-border">
				<strong><?php esc_html_e( 'Courses', 'masteriyo' ); ?></strong>
				<strong><?php esc_html_e( 'Subtotal', 'masteriyo' ); ?></strong>
		</li>

	<?php foreach ( $courses as $course ) : ?>
		<li>
				<span><?php echo esc_html( $course->get_name() ); ?></span>
				<span><?php echo wp_kses_post( masteriyo_price( $course->get_price() ) ); ?></span>
		</li>
	<?php endforeach; ?>
		<li>
				<strong><?php esc_html_e( 'Subtotal', 'masteriyo' ); ?></strong>
				<span><?php echo wp_kses_post( masteriyo_price( $cart->get_subtotal() ) ); ?></span>
		</li>
		<li>
				<strong><?php esc_html_e( 'Total', 'masteriyo' ); ?></strong>
				<strong><?php echo wp_kses_post( masteriyo_price( $cart->get_total() ) ); ?></strong>
		</li>
	</ul>
</div>
<?php
