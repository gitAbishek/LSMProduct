<?php
/**
 * View Order
 *
 * Shows the details of a particular order on the account page.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/myaccount/view-order.php.
 *
 * HOWEVER, on occasion masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

?>
<p>
	<?php
	printf(
		/* translators: 1: order number 2: order date 3: order status */
		esc_html__( 'Order #%1$s was placed on %2$s and is currently %3$s.', 'masteriyo' ),
		'<mark class="order-number">' . $order->get_order_number() . '</mark>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		'<mark class="order-date">' . masteriyo_format_datetime( $order->get_date_created() ) . '</mark>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		'<mark class="order-status">' . masteriyo_get_order_status_name( $order->get_status() ) . '</mark>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	);
?>
</p>

<?php if ( $notes ) : ?>
	<h2><?php esc_html_e( 'Order updates', 'masteriyo' ); ?></h2>
	<ol class="commentlist notes">
		<?php foreach ( $notes as $note ) : ?>
		<li class="comment note">
			<div class="comment_container">
				<div class="comment-text">
					<p class="meta"><?php echo date_i18n( esc_html__( 'l jS \o\f F Y, h:ia', 'masteriyo' ), strtotime( $note->comment_date ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
					<div class="description">
						<?php echo wpautop( wptexturize( $note->comment_content ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
		</li>
		<?php endforeach; ?>
	</ol>
<?php endif;

?>
<section class="masteriyo-order-details">
	<?php do_action( 'masteriyo_order_details_before_order_table', $order ); ?>

	<h2><?php esc_html_e( 'Order details', 'masteriyo' ); ?></h2>

	<table>
		<thead>
			<tr>
				<th><?php esc_html_e( 'Product', 'masteriyo' ); ?></th>
				<th><?php esc_html_e( 'Total', 'masteriyo' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<?php
			do_action( 'masteriyo_order_details_before_order_table_items', $order );

			foreach ( $order_items as $item_id => $item ) {
				$product = $item->get_course();

				if ( is_null( $product ) ) {
					continue;
				}
				?>
				<tr class="<?php echo esc_attr( apply_filters( 'masteriyo_order_item_class', 'masteriyo-table__line-item order_item', $item, $order ) ); ?>">

					<td>
						<?php
						$product_permalink = apply_filters( 'masteriyo_order_item_permalink', $product->get_permalink( $item ), $item, $order );

						echo apply_filters( 'masteriyo_order_item_name', $product_permalink ? sprintf( '<a href="%s">%s</a>', $product_permalink, $item->get_name() ) : $item->get_name(), $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

						$qty          = $item->get_quantity();
						$refunded_qty = $order->get_qty_refunded_for_item( $item_id );

						if ( $refunded_qty ) {
							$qty_display = '<del>' . esc_html( $qty ) . '</del> <ins>' . esc_html( $qty - ( $refunded_qty * -1 ) ) . '</ins>';
						} else {
							$qty_display = esc_html( $qty );
						}

						echo apply_filters( 'masteriyo_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '&times;&nbsp;%s', $qty_display ) . '</strong>', $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

						do_action( 'masteriyo_order_item_meta_start', $item_id, $item, $order, false );

						masteriyo_display_item_meta( $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

						do_action( 'masteriyo_order_item_meta_end', $item_id, $item, $order, false );
						?>
					</td>

					<td class="product-total">
						<?php echo $order->get_formatted_line_subtotal( $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</td>
				</tr>
				<?php
			}
			do_action( 'masteriyo_order_details_after_order_table_items', $order );
			?>
		</tbody>

		<tfoot>
			<?php
			foreach ( $order->get_order_item_totals() as $key => $total ) {
				?>
					<tr>
						<th scope="row"><?php echo esc_html( $total['label'] ); ?></th>
						<td><?php echo ( 'payment_method' === $key ) ? esc_html( $total['value'] ) : wp_kses_post( $total['value'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
					</tr>
				<?php
			}
			?>
		</tfoot>
	</table>

	<?php do_action( 'masteriyo_order_details_after_order_table', $order ); ?>
</section>

<?php
/**
 * Action hook fired after the order details.
 *
 * @since 0.1.0
 * @param Order $order Order data.
 */
do_action( 'masteriyo_after_order_details', $order );

if ( $show_customer_details ) {
	?>
	<section class="masteriyo-customer-details">

		<h2><?php esc_html_e( 'Billing address', 'masteriyo' ); ?></h2>

		<address>
			<?php echo wp_kses_post( $order->get_formatted_billing_address( esc_html__( 'N/A', 'masteriyo' ) ) ); ?>

			<?php if ( $order->get_billing_phone() ) : ?>
				<p><?php echo esc_html( $order->get_billing_phone() ); ?></p>
			<?php endif; ?>

			<?php if ( $order->get_billing_email() ) : ?>
				<p><?php echo esc_html( $order->get_billing_email() ); ?></p>
			<?php endif; ?>
		</address>

		<?php do_action( 'masteriyo_order_details_after_customer_details', $order ); ?>

	</section>
	<?php
}
