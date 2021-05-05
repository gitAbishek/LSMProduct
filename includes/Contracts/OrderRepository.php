<?php
/**
 * Order repository interface.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Contracts
 */

namespace ThemeGrill\Masteriyo\Contracts;

defined( 'ABSPATH' ) || exit;

/**
 * Order repository interface
 *
 * Functions that must be defined by order repository classes.
 */
interface OrderRepository {

	/**
	 * Read order items of a specific type from the database for this order.
	 *
	 * @since 0.1.0
	 *
	 * @param Order $order Order object.
	 * @param string  $type Order item type.
	 * @return array
	 */
	public function read_items( $order, $type );

	/**
	 * Remove all line items from an order.
	 *
	 * @since 0.1.0
	 *
	 * @param Order $order Order object.
	 * @param string  $type Order item type. Default null.
	 */
	public function delete_items( $order, $type = null );

	/**
	 * Get token ids for an order.
	 *
	 * @since 0.1.0
	 *
	 * @param Order $order Order object.
	 * @return array
	 */
	public function get_payment_token_ids( $order );

	/**
	 * Update token ids for an order.
	 *
	 * @since 0.1.0
	 *
	 * @param Order $order Order object.
	 * @param array    $token_ids Token IDs.
	 */
	public function update_payment_token_ids( $order, $token_ids );
}
