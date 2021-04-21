<?php
/**
 * Masteriyo form checkout.
 *
 * @package ThemeGrill\Masteriyo\Templates
 * @since 0.1.0
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;
?>

<?php do_action( 'masteriyo_before_checkout_form' ); ?>

<div class="checkout" id="masteriyo-checkout">
	<div class="checkout-wrapper">
		<?php do_action( 'masteriyo_checkout_form' ); ?>

		<div class="checkout-summary">
			<?php do_action( 'masteriyo_checkout_summary' ); ?>
		</div>
	</div>
</div>
<?php
	do_action( 'masteriyo_after_checkout_form' );

