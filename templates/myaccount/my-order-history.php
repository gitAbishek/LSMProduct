<?php

/**
 * My Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/myaccount/orders.php.
 *
 * HOWEVER, on occasion masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates
 * @since 0.1.0
 * @version 0.1.0
 */

defined('ABSPATH') || exit;

do_action('masteriyo_before_account_orders', $orders);

if (count($orders) > 0) :
?>

<table class="mto-account-orders-table">
	<thead>
		<tr>
			<?php foreach (masteriyo_get_account_orders_columns() as $column_id => $column_name) : ?>
				<th class="masteriyo-orders-table__header-<?php echo esc_attr($column_id); ?>"><span class="nobr"><?php echo esc_html($column_name); ?></span></th>
			<?php endforeach; ?>
		</tr>
	</thead>

	<tbody>
		<?php
		foreach ($orders as $order) {
		?>
			<tr class="status-<?php echo esc_attr($order->get_status()); ?>">
				<?php foreach (masteriyo_get_account_orders_columns() as $column_id => $column_name) : ?>
					<td class="masteriyo-orders-table__cell-<?php echo esc_attr($column_id); ?>" data-title="<?php echo esc_attr($column_name); ?>">
						<?php if (has_action('masteriyo_my_account_my_orders_column_' . $column_id)) : ?>
							<?php do_action('masteriyo_my_account_my_orders_column_' . $column_id, $order); ?>

						<?php elseif ('order-number' === $column_id) : ?>
							<a href="<?php echo esc_url($order->get_view_order_url()); ?>">
								<?php echo esc_html(_x('#', 'hash before order number', 'masteriyo') . $order->get_order_number()); ?>
							</a>

						<?php elseif ('order-date' === $column_id) : ?>
							<time datetime="<?php echo esc_attr($order->get_date_created()->date('c')); ?>"><?php echo esc_html(masteriyo_format_datetime($order->get_date_created())); ?></time>

						<?php elseif ('order-status' === $column_id) : ?>
							<?php echo esc_html(masteriyo_get_order_status_name($order->get_status())); ?>

						<?php elseif ('order-total' === $column_id) : ?>
							<?php
							/* translators: 1: formatted order total 2: total order items */
							echo wp_kses_post(sprintf(_n('%1$s for %2$s item', '%1$s for %2$s items', $order->get_item_count(), 'masteriyo'), $order->get_formatted_order_total(), $order->get_item_count()));
							?>

						<?php elseif ('order-actions' === $column_id) : ?>
							<?php
							$actions = masteriyo_get_account_orders_actions($order);

							if (!empty($actions)) {
								foreach ($actions as $key => $action) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
									echo '<a href="' . esc_url($action['url']) . '" class="masteriyo-button button ' . sanitize_html_class($key) . '">' . esc_html($action['name']) . '</a>';
								}
							}
							?>
						<?php endif; ?>
					</td>
				<?php endforeach; ?>
			</tr>
		<?php
		}
		?>
	</tbody>
</table>

<?php else : ?>
	<div class="masteriyo-message masteriyo-message--info masteriyo-Message masteriyo-Message--info masteriyo-info">
		<a class="masteriyo-Button button" href="<?php echo esc_url(apply_filters('masteriyo_return_to_shop_redirect', masteriyo_get_page_permalink('courses-list'))); ?>"><?php esc_html_e('Browse products', 'masteriyo'); ?></a>
		<?php esc_html_e('No order has been made yet.', 'masteriyo'); ?>
	</div>
<?php endif; ?>

<?php

do_action('masteriyo_after_account_orders', $orders);
