<?php
/**
 * Class for parameter-based order querying
 *
 * @package  ThemeGrill\Masteriyo\Query
 * @version 0.1.0
 * @since   0.1.0
 */

namespace ThemeGrill\Masteriyo\Query;

use ThemeGrill\Masteriyo\Abstracts\ObjectQuery;

defined( 'ABSPATH' ) || exit;

/**
 * Order query class.
 */
class OrderQuery extends ObjectQuery {

	/**
	 * Valid query vars for orders.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {
		return array_merge(
			parent::get_default_query_vars(),
			array(
				'type'                 => 'mto-order',
				'status'               => array_keys( masteriyo_get_order_statuses() ),
				'total'                => '',
				'currency'             => '',
				'customer_id'          => '',
				'payment_method'       => '',
				'transaction_id'       => '',
				'created_via'          => '',
				'customer_ip_address'  => '',
				'customer_user_agent'  => '',
				'date_created'         => '',
				'date_modified'        => '',
				'date_paid'            => '',
				'date_completed'       => '',
				'version'              => '',
				'order_key'            => '',
				'customer_note'        => '',
				'billing_first_name'   => '',
				'billing_last_name'    => '',
				'billing_company'      => '',
				'billing_address_1'    => '',
				'billing_address_2'    => '',
				'billing_city'         => '',
				'billing_state'        => '',
				'billing_postcode'     => '',
				'billing_country'      => '',
				'billing_email'        => '',
				'billing_phone'        => '',
				'payment_method_title' => '',
			)
		);
	}

	/**
	 * Get orders matching the current query vars.
	 *
	 * @since 0.1.0
	 *
	 * @return array|Model order objects
	 */
	public function get_orders() {
		$args    = apply_filters( 'masteriyo_order_object_query_args', $this->get_query_vars() );
		$results = masteriyo('order.store' )->query( $args );
		return apply_filters( 'masteriyo_order_object_query', $results, $args );
	}
}
