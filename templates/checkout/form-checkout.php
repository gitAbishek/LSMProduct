<?php
/**
 * Masteriyo form checkout.
 *
 * @package Masteriyo\Templates
 * @since 0.1.0
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'masteriyo_before_checkout_form' );

if ( ! is_user_logged_in() ) {
	$message = sprintf(
		apply_filters(
			'masteriyo_checkout_must_be_logged_in_message',
			// translators: %s: My account page link
			__( 'You must be %1$slogged in%2$s to checkout.', 'masteriyo' )
		),
		'<a href="' . esc_url( masteriyo_get_myaccount_url() ) . '">',
		'</a>'
	);

	echo wp_kses(
		$message,
		array(
			'a' => array(
				'href'  => array(),
				'title' => array(),
			),
		)
	);

	return;
}

?>

<div class="mto-checkout" id="masteriyo-checkout">
	<form
		name="checkout" method="post" class="mto-checkout--form masteriyo-checkout"
		action="<?php echo esc_url( masteriyo_get_checkout_url() ); ?>"
		enctype="multipart/form-data"
		class="checkout masteriyo-checkout"
		validate="novalidate">

		<div class="mto-checkout-wrapper">
			<?php do_action( 'masteriyo_checkout_form' ); ?>

			<div class="mto-checkout-summary">
				<?php do_action( 'masteriyo_checkout_summary' ); ?>
			</div>
		</div>

	</form>
</div>
<?php
	do_action( 'masteriyo_after_checkout_form' );

