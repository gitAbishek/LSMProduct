<?php
/**
 * Class for parameter-based notification query.
 *
 * @package  Masteriyo\Query
 * @since   x.x.x
 */

namespace Masteriyo\Query;

use Masteriyo\Abstracts\ObjectQuery;

defined( 'ABSPATH' ) || exit;

/**
 * Notification query class.
 */
class NotificationQuery extends ObjectQuery {

	/**
	 * Valid query vars for notification.
	 *
	 * @since x.x.x
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {
		return array_merge(
			parent::get_default_query_vars(),
			array(
				'user_id'     => 0,
				'created_by'  => 0,
				'code'        => '',
				'status'      => '',
				'type'        => '',
				'level'       => '',
				'created_at'  => null,
				'modified_at' => null,
				'expire_at'   => null,
				'orderby'     => 'id',
			)
		);
	}

	/**
	 * Get notification matching the current query vars.
	 *
	 * @since x.x.x
	 *
	 * @return array|Model Course objects
	 */
	public function get_notifications() {
		$args    = apply_filters( 'masteriyo_notification_object_query_args', $this->get_query_vars() );
		$results = masteriyo( 'notification.store' )->query( $args, $this );
		return apply_filters( 'masteriyo_notification_object_query', $results, $args );
	}
}
