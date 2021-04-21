<?php
/**
 * Masteriyo form checkout.
 *
 * @package ThemeGrill\Masteriyo\Templates
 * @since 0.1.0
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'masteriyo_before_checkout_form' );

if ( ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'masteriyo_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'masteriyo' ) ) );
	return;
}

?>

<div class="checkout" id="masteriyo-checkout">
	<form
		name="checkout" method="post" class="checkout masteriyo-checkout"
		action="<?php echo esc_url( masteriyo_get_checkout_url() ); ?>"
		enctype="multipart/form-data" >

		<div class="checkout-wrapper">
			<?php do_action( 'masteriyo_checkout_form' ); ?>

			<div class="checkout-summary">
				<?php do_action( 'masteriyo_checkout_summary' ); ?>
			</div>
		</div>

	</form>
</div>
<?php
	do_action( 'masteriyo_after_checkout_form' );

