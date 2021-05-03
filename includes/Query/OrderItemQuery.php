<?php
/**
 * Class for parameter-based order items querying
 *
 * @package  ThemeGrill\Masteriyo\Query
 * @version 0.1.0
 * @since   0.1.0
 */

namespace ThemeGrill\Masteriyo\Query;

use ThemeGrill\Masteriyo\Abstracts\ObjectQuery;

defined( 'ABSPATH' ) || exit;

/**
 * Order item query class.
 */
class OrderItemQuery extends ObjectQuery {

	/**
	 * Valid query vars for order items.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {
		return array_merge(
			parent::get_default_query_vars(),
			array(
				'order_id'   => '',
				'course_id'  => '',
				'name'       => '',
				'type'       => '',
				'quantity'   => '',
			)
		);
	}

	/**
	 * Get order items matching the current query vars.
	 *
	 * @since 0.1.0
	 *
	 * @return array|Model Order item objects
	 */
	public function get_order_items() {
		$args    = apply_filters( 'masteriyo_order_item_object_query_args', $this->get_query_vars() );
		$results = masteriyo('order.item.store' )->query( $args );
		return apply_filters( 'masteriyo_order_item_object_query', $results, $args );
	}
}