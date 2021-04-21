<?php
/**
 * Order summary.
 *
 * @package ThemeGrill\Masteriyo\Templates
 * @since 0.1.0
 * @version 0.1.0
 */

 defined( 'ABSPATH' ) || exit;
?>

<div class="checkout-summary-your-order">
	<h2 class="checkout-summary--title">
		<?php esc_html_e( 'Your Order', 'masteriyo' ); ?>
	</h2>
	<ul class="checkout-summary-order-details">
		<li class="h-border">
			<div>
				<strong><?php esc_html_e( 'Courses', 'masteriyo' ); ?></strong>
				<strong><?php esc_html_e( 'Subtotal', 'masteriyo' ); ?></strong>
			</div>
		</li>

	<?php foreach ( $courses as $course ) : ?>
		<li>
			<div>
				<span><?php echo esc_html( $course->get_name() ); ?></span>
				<span><?php echo masteriyo_price( $course->get_price() ); ?></span>
			</div>
		</li>
	<?php endforeach; ?>
		<li>
			<div>
				<strong><?php esc_html_e( 'Subtotal', 'masteriyo' ); ?></strong>
				<span><?php echo masteriyo_price( $cart->get_subtotal() ); ?></span>
			</div>
		</li>
		<li>
			<div>
				<strong><?php esc_html_e( 'Total', 'masteriyo' ); ?></strong>
				<strong><?php echo masteriyo_price( $cart->get_total() ); ?></strong>
			</div>
		</li>
	</ul>
	<div class="checkout-summary-couponcode">
		<label href="#" for="couponcode-input" class="checkout-summary-couponcode-link">
			<?php esc_html_e( 'Have a Coupon Code?', 'masteriyo' ); ?>
		</label>
		<input type="checkbox" class="checkout-summary-couponcode-input hide" id="couponcode-input" name="couponcode-input" />
		<form class="checkout-summary-couponcode-form hide" action="">
			<div class="checkout-summary-couponcode---wrapper">
				<div class="checkout-summary-couponcode----input full-w">
					<input type="text" />
				</div>
				<div class="checkout-summary-couponcode----button">
					<a href="#" class="btn-primary full-w">
						<?php esc_html_e( 'Apply', 'masteriyo' ); ?>
					</a>
				</div>
			</div>
		</form>
	</div>
</div>
<?php
