<?php
/**
 * Masteriyo checkout form payment.
 *
 * @package Masteriyo\Templates;
 * @since 0.1.0
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

?>

<li class="payment-method payment-method-<?php echo esc_attr( $gateway->get_name() ); ?>">
	<input
		id="payment-method-<?php echo esc_attr( $gateway->get_name() ); ?>"
		type="radio"
		class="input-radio"
		name="payment_method"
		value="<?php echo esc_attr( $gateway->get_name() ); ?>"
		<?php checked( $gateway->is_chosen(), true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->get_order_button_text() ); ?>" />

	<label for="payment_method_<?php echo esc_attr( $gateway->get_name() ); ?>" class="mto-label">
		<?php
			echo $gateway->get_title(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $gateway->get_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
	</label>

	<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
		<div class="payment-box payment-method-<?php echo esc_attr( $gateway->get_name() ); ?>" <?php if ( ! $gateway->is_chosen() ) : /* phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */ ?>style="display:block;"<?php endif; /* phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */ ?>>
			<?php $gateway->payment_fields(); ?>
		</div>
	<?php endif; ?>
</li>
<?php

