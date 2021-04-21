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
<li class="masteriyo-payment_method payment-method-<?php echo esc_attr( $gateway->id ); ?>">
	<input
		id="payment-method-<?php echo esc_attr( $gateway->id ); ?>"
		type="radio"
		class="input-radio"
		name="payment_method"
		value="<?php echo esc_attr( $gateway->id ); ?>"
		<?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />

	<label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">
		<?php
			echo esc_html( $gateway->get_title() );
			echo $gateway->get_icon();
		?>
	</label>
	<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
		<div class="payment-box payment-method-<?php echo esc_attr( $gateway->id ); ?>" <?php if ( ! $gateway->chosen ) : /* phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */ ?>style="display:none;"<?php endif; /* phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */ ?>>
			<?php $gateway->payment_fields(); ?>
		</div>
	<?php endif; ?>
</li>
<?php

