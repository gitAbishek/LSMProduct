<?php
/**
 * User Activity Repository.
 *
 * @package ThemeGrill\Masteriyo\Repository;
 */

namespace ThemeGrill\Masteriyo\Repository;

use ThemeGrill\Masteriyo\MetaData;
use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Models\UserActivity;
use ThemeGrill\Masteriyo\Repository\AbstractRepository;

/**
 * User Activity repository class.
 */
class UserActivityRepository extends AbstractRepository implements RepositoryInterface {

	/**
	 * Meta type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $meta_type = 'user_activity';

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 3.0.0
	 * @var array
	 */
	protected $internal_meta_keys = array();

	/**
	 * Create a useractivity in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $user_activity UserActivity object.
	 */
	public function create( Model &$user_activity ) {
		global $wpdb;

		if ( ! $user_activity->get_date_start( 'edit' ) ) {
			$user_activity->set_date_start( current_time( 'mysql', true ) );
		}

		if ( ! $user_activity->get_date_update( 'edit' ) ) {
			$user_activity->set_date_update( current_time( 'mysql', true ) );
		}

		if ( ! $user_activity->get_status( 'edit' ) ) {
			$user_activity->set_status( 'begin' );
		}

		$date_complete = $user_activity->get_date_complete( 'edit' );
		$date_complete = is_null( $date_complete ) ? null : $date_complete->getTimestamp();

		$result = $wpdb->insert(
			$user_activity->get_table_name(),
			apply_filters(
				'masteriyo_new_user_activity_data',
				array(
					'user_id'         => $user_activity->get_user_id( 'edit' ),
					'item_id'         => $user_activity->get_item_id( 'edit' ),
					'activity_type'   => $user_activity->get_type( 'edit' ),
					'activity_status' => $user_activity->get_status( 'edit' ),
					'date_start'      => gmdate( 'Y-m-d H:i:s', $user_activity->get_date_start( 'edit' )->getTimestamp() ),
					'date_update'     => gmdate( 'Y-m-d H:i:s', $user_activity->get_date_update( 'edit' )->getTimestamp() ),
					'date_complete'   => gmdate( 'Y-m-d H:i:s', $date_complete ),
				),
				$user_activity
			),
			array( '%d', '%d', '%s', '%s', '%s', '%s', '%s' )
		);

		if ( $result && $wpdb->insert_id ) {
			$user_activity->set_id( $wpdb->insert_id );
			$this->update_custom_table_meta( $user_activity, true );
			$user_activity->save_meta_data();
			$user_activity->apply_changes();
			$this->clear_cache( $user_activity );

			do_action( 'masteriyo_new_user_activity', $user_activity->get_id(), $user_activity );
		}

	}

	/**
	 * Update a user activity item in the database.
	 *
	 * @since 0.1.0
	 * @param UserActivity $user_activity User activity object.
	 */
	public function update( Model &$user_activity ) {
		global $wpdb;

		$changes = $user_activity->get_changes();

		$user_activity_data_keys = array(
			'user_id',
			'item_id',
			'type',
			'status',
			'date_start',
			'date_update',
			'date_complete',
		);

		if ( array_intersect( $user_activity_data_keys, array_keys( $changes ) ) ) {
			$wpdb->update(
				$wpdb->prefix . 'masteriyo_user_activities',
				array(
					'user_id'         => $user_activity->get_user_id( 'edit' ),
					'item_id'         => $user_activity->get_item_id( 'edit' ),
					'activity_type'   => $user_activity->get_type( 'edit' ),
					'activity_status' => $user_activity->get_status( 'edit' ),
					'date_start'      => gmdate( 'Y-m-d H:i:s', $user_activity->get_date_start( 'edit' )->getTimestamp() ),
					'date_update'     => gmdate( 'Y-m-d H:i:s', $user_activity->get_date_update( 'edit' )->getTimestamp() ),
					'date_complete'   => gmdate( 'Y-m-d H:i:s', $user_activity->get_date_complete( 'edit' )->getTimestamp() ),
				),
				array( 'activity_id' => $user_activity->get_id() )
			);
		}

		$this->update_custom_table_meta( $user_activity );
		$user_activity->save_meta_data();
		$user_activity->apply_changes();
		$this->clear_cache( $user_activity );

		do_action( 'masteriyo_update_user_activity', $user_activity->get_id(), $user_activity );
	}

	/**
	 * Remove an user activity from the database.
	 *
	 * @since 0.1.0
	 * @param UserActivity $user_activity User activity object.
	 * @param array         $args Array of args to pass to the delete method.
	 */
	public function delete( &$user_activity, $args = array() ) {
		global $wpdb;

		if ( $user_activity->get_id() ) {
			do_action( 'masteriyo_before_delete_user_activities', $user_activity->get_id() );

			$wpdb->delete( $wpdb->base_prefix . 'masteriyo_user_activities', array( 'activity_id' => $user_activity->get_id() ) );
			$wpdb->delete( $wpdb->base_prefix . 'masteriyo_user_activitymeta', array( 'user_activity_id' => $user_activity->get_id() ) );

			do_action( 'masteriyo_delete_user_activity', $user_activity->get_id() );

			$this->clear_cache( $user_activity );
		}
	}

	/**
	 * Read a user activity from the database.
	 *
	 * @since 0.1.0
	 *
	 * @param UserActivity $user_activity User activity object.
	 *
	 * @throws Exception If invalid user activity object.
	 */
	public function read( &$user_activity ) {
		global $wpdb;

		$result = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}masteriyo_user_activities WHERE activity_id = %d;",
				$user_activity->get_id()
			)
		);

		if ( ! $result ) {
			throw new \Exception( __( 'Invalid user activity.', 'masteriyo' ) );
		}

		$user_activity->set_props(
			array(
				'user_id'       => $result->user_id,
				'item_id'       => $result->item_id,
				'type'          => $result->activity_type,
				'status'        => $result->activity_status,
				'date_start'    => $this->string_to_timestamp( $result->date_start ),
				'date_update'   => $this->string_to_timestamp( $result->date_update ),
				'date_complete' => $this->string_to_timestamp( $result->date_complete ),
			)
		);

		$user_activity->read_meta_data();
		$user_activity->set_object_read( true );

		do_action( 'masteriyo_user_activity_read', $user_activity->get_id(), $user_activity );
	}

	/**
	 * Clear meta cache.
	 *
	 * @since 0.1.0
	 *
	 * @param UserActivity $user_activity User activity object.
	 */
	public function clear_cache( &$user_activity ) {
		wp_cache_delete( 'item' . $user_activity->get_id(), 'masteriyo-user-activities' );
		wp_cache_delete( 'items-' . $user_activity->get_id(), 'masteriyo-user-activities' );
		wp_cache_delete( $user_activity->get_id(), $this->meta_type . '_meta' );
	}

	/**
	 * Fetch user activities.
	 *
	 * @since 0.1.0
	 *
	 * @param array $query_vars Query vars.
	 * @return UserActivity[]
	 */
	public function query( $query_vars ) {
		global $wpdb;

		$search_criteria = array();
		$sql[]           = "SELECT * FROM {$wpdb->base_prefix}masteriyo_user_activities";

		if ( ! empty( $query_vars['user_id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'user_id = %d', $query_vars['user_id'] );
		}

		if ( ! empty( $query_vars['course_id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'item_id = %d', $query_vars['course_id'] );
		}

		if ( ! empty( $query_vars['activity_type'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'type = %s', $query_vars['activity_type'] );
		}

		if ( ! empty( $query_vars['status'] ) && 'any' !== $query_vars['status'] ) {
			$search_criteria[] = $wpdb->prepare( 'type = %s', $query_vars['status'] );
		}

		if ( 1 <= count( $search_criteria ) ) {
			$criteria = implode( ' AND ', $search_criteria );
			$sql[]    = 'WHERE ' . $criteria;
		}

		// Construct limit part.
		$per_page = $query_vars['per_page'];

		if ( $query_vars['paged'] > 0 ) {
			$offset = ( $query_vars['paged'] - 1 ) * $per_page;
		}

		$sql[] = $wpdb->prepare( 'LIMIT %d, %d', $offset, $per_page );

		$sql = implode( ' ', $sql ) . ';';

		// Fetch the results.
		$user_activities = $wpdb->get_results( $wpdb->prepare( $sql ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		$ids = wp_list_pluck( $user_activities, 'activity_id' );

		return array_filter( array_map( 'masteriyo_get_user_activity', $ids ) );
	}

	/**
	 * Get user activity meta.
	 *
	 * @since 0.1.0
	 *
	 * @param UserActivity $user_activity User activity object.
	 * @return
	 */
	public function get_user_activity_meta( $user_activity ) {
		$meta_values = $this->read_meta( $user_activity );

		foreach ( $meta_values  as $meta_value ) {
			$function = "set_{$meta_value->key}";

			if ( is_callable( array( $user_activity, $function ) ) ) {
				$user_activity->$function( maybe_unserialize( $meta_value->value ) );
			}
		}

		return $user_activity;
	}
}
