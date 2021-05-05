<?php
/**
 * Order helper functions.
 *
 * @since 0.1.0
 * @package ThemeGrill\Masteriyo\Helper
 */

/**
 * Get order.
 *
 * @since 0.1.0
 *
 * @param int|Order|WP_Post $order Order id or Order Model or Post.
 * @return Order|null
 */
function masteriyo_get_order( $order ) {
	$order_obj   = masteriyo( 'order' );
	$order_store = masteriyo( 'order.store' );

	if ( is_a( $order, 'ThemeGrill\Masteriyo\Models\Order\Order' ) ) {
		$id = $order->get_id();
	} elseif ( is_a( $order, 'WP_Post' ) ) {
		$id = $order->ID;
	} else {
		$id = $order;
	}

	try {
		$id = absint( $id );
		$order_obj->set_id( $id );
		$order_store->read( $order_obj );
	} catch ( \Exception $e ) {
		return null;
	}
	return apply_filters( 'masteriyo_get_order', $order_obj, $order );
}

/**
 * Get order item.
 *
 * @since 0.1.0
 *
 * @param int|OrderItem $order Order id or Order Model or Post.
 *
 * @return OrderItem|null
 */
function masteriyo_get_order_item( $order_item ) {
	$order_item_obj   = masteriyo( 'order-item' );
	$order_item_store = masteriyo( 'order-item.store' );

	try {
		if ( is_a( $order_item, 'ThemeGrill\Masteriyo\Models\Order\OrderItem' ) ) {
			$id = $order_item->get_id();
		} elseif ( is_a( $order_item, \stdClass::class ) ) {
			$id = $order_item->id;
		} else {
			$id = $order_item;
		}

		$id = absint( $id );
		$order_item_obj->set_id( $id );
		$order_item_store->read( $order_item_obj );
	} catch ( \Exception $e ) {
		return null;
	}
	return apply_filters( 'masteriyo_get_order_item', $order_item_obj, $order_item );
}

/**
 * Get list of status for order.
 *
 * @since 0.1.0
 *
 * @return array
 */
function masteriyo_get_order_statuses() {
	$order_statuses = array(
		'mto-pending'    => array(
			'label'                     => _x( 'Pending payment', 'Order status', 'masteriyo' ),
			'public'                    => false,
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			// translators: %s: number of orders
			'label_count'               => _n_noop( 'Pending payment <span class="count">(%s)</span>', 'Pending payment <span class="count">(%s)</span>', 'masteriyo' ),
		),
		'mto-processing' => array(
			'label'                     => _x( 'Processing', 'Order status', 'masteriyo' ),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			/* translators: %s: number of orders */
			'label_count'               => _n_noop( 'Processing <span class="count">(%s)</span>', 'Processing <span class="count">(%s)</span>', 'masteriyo' ),
		),
		'mto-on-hold'    => array(
			'label'                     => _x( 'On hold', 'Order status', 'masteriyo' ),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			/* translators: %s: number of orders */
			'label_count'               => _n_noop( 'On hold <span class="count">(%s)</span>', 'On hold <span class="count">(%s)</span>', 'masteriyo' ),
		),
		'mto-completed'  => array(
			'label'                     => _x( 'Completed', 'Order status', 'masteriyo' ),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			/* translators: %s: number of orders */
			'label_count'               => _n_noop( 'Completed <span class="count">(%s)</span>', 'Completed <span class="count">(%s)</span>', 'masteriyo' ),
		),
		'mto-cancelled'  => array(
			'label'                     => _x( 'Cancelled', 'Order status', 'masteriyo' ),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			/* translators: %s: number of orders */
			'label_count'               => _n_noop( 'Cancelled <span class="count">(%s)</span>', 'Cancelled <span class="count">(%s)</span>', 'masteriyo' ),
		),
		'mto-refunded'   => array(
			'label'                     => _x( 'Refunded', 'Order status', 'masteriyo' ),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			/* translators: %s: number of orders */
			'label_count'               => _n_noop( 'Refunded <span class="count">(%s)</span>', 'Refunded <span class="count">(%s)</span>', 'masteriyo' ),
		),
		'mto-failed'     => array(
			'label'                     => _x( 'Failed', 'Order status', 'masteriyo' ),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			/* translators: %s: number of orders */
			'label_count'               => _n_noop( 'Failed <span class="count">(%s)</span>', 'Failed <span class="count">(%s)</span>', 'masteriyo' ),
		),
	);

	return apply_filters( 'masteriyo_order_statuses', $order_statuses );
}

/**
 * See if a string is an order status.
 *
 * @since 0.1.0
 *
 * @param  string $maybe_status Status, including any masteriyo- prefix.
 * @return bool
 */
function masteriyo_is_order_status( $maybe_status ) {
	$order_statuses = masteriyo_get_order_statuses();
	return isset( $order_statuses[ $maybe_status ] );
}

/**
 * Get list of statuses which are consider 'paid'.
 *
 * @since  0.1.0
 * @return array
 */
function masteriyo_get_is_paid_statuses() {
	return apply_filters( 'masteriyo_order_is_paid_statuses', array( 'mto-processing', 'mto-completed' ) );
}

/**
 * Get list of statuses which are consider 'pending payment'.
 *
 * @since  0.1.0
 * @return array
 */
function masteriyo_get_is_pending_statuses() {
	return apply_filters( 'masteriyo_order_is_pending_statuses', array( 'mto-pending' ) );
}

/**
 * Get the nice name for an order status.
 *
 * @since  0.1.0
 * @param  string $status Status.
 * @return string
 */
function masteriyo_get_order_status_name( $status ) {
	$statuses = masteriyo_get_order_statuses();
	if ( isset( $statuses[ $status ] ) ) {
		return $statuses[ $status ]['label'];
	}

	return '';
}

/**
 * Get orders.
 *
 * @since 0.1.0
 *
 * @param array $args Query arguments.
 *
 * @return object|array[Order]
 */
function masteriyo_get_orders( $args = array() ) {
	$orders = masteriyo( 'query.orders' )->set_args( $args )->get_orders();

	return apply_filters( 'masteriyo_get_orders', $orders, $args );
}

/**
 * Get order items.
 *
 * @since 0.1.0
 *
 * @param array $args Query arguments.
 *
 * @return object|array[OrderItem]
 */
function masteriyo_get_order_items( $args = array() ) {
	$order_items = masteriyo( 'query.orders.items' )->set_args( $args )->get_order_items();

	return apply_filters( 'masteriyo_get_order_items', $order_items, $args );
}
