<?php
/**
 * Email Order Items
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/emails/order-items.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates\Emails
 */

defined( 'ABSPATH' ) || exit;

$text_align  = is_rtl() ? 'right' : 'left';
$margin_side = is_rtl() ? 'left' : 'right';
$plain_text  = '';

foreach ( $items as $item_id => $item ) :
	$course        = $item->get_course();
	$purchase_note = '';
	$image         = '';

	/**
	 * Filters boolean: true if the given order item should be visible in orders table.
	 *
	 * @since 1.0.0
	 *
	 * @param boolean $bool true if the given order item should be visible in orders table.
	 * @param object $order_item The order item object.
	 */
	if ( ! apply_filters( 'masteriyo_order_item_visible', true, $item ) ) {
		continue;
	}

	if ( is_object( $course ) ) {
		$purchase_note = $course->get_purchase_note();
		$image         = $course->get_image( $image_size );
	}

	?>
	<tr class="
	<?php
	/**
	 * Filters order item table row class.
	 *
	 * @since 1.0.0
	 *
	 * @param string $class The order item table row class.
	 * @param object $order_item Order item object.
	 * @param Masteriyo\Models\Order\Order $order Order object.
	 */
	echo esc_attr( apply_filters( 'masteriyo_order_item_class', 'order_item', $item, $order ) );
	?>
	">
		<td style="text-align:<?php echo esc_attr( $text_align ); ?>;">
		<?php

		// Show title/image etc.
		if ( $show_image ) {
			/**
			 * Filters order item thumbnail image html.
			 *
			 * @since 1.0.0
			 *
			 * @param string $image The thumbnail image html.
			 * @param object $order_item The order item object.
			 */
			echo wp_kses_post( apply_filters( 'masteriyo_order_item_thumbnail', $image, $item ) );
		}

		/**
		 * Filters order item name to be displayed in order detail table.
		 *
		 * @since 1.0.0
		 *
		 * @param string $item_name The order item name.
		 * @param object $order_item Order item object.
		 */
		echo wp_kses_post( apply_filters( 'masteriyo_order_item_name', $item->get_name(), $item ) );

		// allow other plugins to add additional course information here.
		do_action( 'masteriyo_order_item_meta_start', $item_id, $item, $order, $plain_text );

		masteriyo_display_item_meta(
			$item,
			array(
				'label_before' => '<strong class="masteriyo-item-meta-label" style="float: ' . esc_attr( $text_align ) . '; margin-' . esc_attr( $margin_side ) . ': .25em; clear: both">',
			)
		);

		// allow other plugins to add additional course information here.
		do_action( 'masteriyo_order_item_meta_end', $item_id, $item, $order, $plain_text );

		?>
		</td>
		<td style="text-align:<?php echo esc_attr( $text_align ); ?>;">
			<?php
			$qty          = $item->get_quantity();
			$refunded_qty = $order->get_qty_refunded_for_item( $item_id );

			if ( $refunded_qty ) {
				$qty_display = '<del>' . esc_html( $qty ) . '</del> <ins>' . esc_html( $qty - ( $refunded_qty * -1 ) ) . '</ins>';
			} else {
				$qty_display = esc_html( $qty );
			}

			/**
			 * Filters order item quantity display html to show in email.
			 *
			 * @since 1.0.0
			 *
			 * @param string $qty_display The order item quantity display html to show in email.
			 */
			echo wp_kses_post( apply_filters( 'masteriyo_email_order_item_quantity', $qty_display, $item ) );
			?>
		</td>
		<td style="text-align:<?php echo esc_attr( $text_align ); ?>;">
			<?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?>
		</td>
	</tr>

	<?php if ( $show_purchase_note && $purchase_note ) : ?>
	<tr>
		<td colspan="3" style="text-align:<?php echo esc_attr( $text_align ); ?>;">
			<?php echo wp_kses_post( wpautop( do_shortcode( $purchase_note ) ) ); ?>
		</td>
	</tr>
	<?php endif; ?>

<?php endforeach; ?>
