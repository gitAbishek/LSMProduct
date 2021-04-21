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
<div class="checkout-summary-payment-method">
	<div class="checkout-summary-paypal">
		<img src="./img/paypal.png" class="checkout-summary-paypal--image w-25" alt="">
		<a href="#" class="primary-link">What is paypal?</a>
	</div>
	<div class="checkout-summary-info"><box-icon type="solid" name="lock-alt"></box-icon>Your transaction is secured with SSL encryption</div>
</div>
<a href="#" class="checkout-confirm-payment btn-primary full-w">
	<?php
		printf(
			esc_html( 'Confirm Payment: %s', 'masteriyo' ),
			masteriyo_price( masteriyo( 'cart' )->get_total() )
		);
		?>
</a>
<?php

