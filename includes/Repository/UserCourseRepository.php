<?php
/**
 * User course repository.
 *
 * @package ThemeGrill\Masteriyo\Repository;
 */

namespace ThemeGrill\Masteriyo\Repository;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Repository\AbstractRepository;

/**
 * user course repository class.
 */
class UserCourseRepository extends AbstractRepository implements RepositoryInterface {

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 0.1.0
	 * @var array
	 */
	protected $internal_meta_keys = array();

	/**
	 * Create a user course in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $user_course UserCourse object.
	 */
	public function create( Model &$user_course ) {
		global $wpdb;

		if ( ! $user_course->get_date_start( 'edit' ) ) {
			$user_course->set_date_start( current_time( 'mysql', true ) );
		}

		if ( ! $user_course->get_date_modified( 'edit' ) ) {
			$user_course->set_date_modified( current_time( 'mysql', true ) );
		}

		$date_end = $user_course->get_date_end( 'edit' );
		$date_end = is_null( $date_end ) ? '' : gmdate( 'Y-m-d H:i:s', $date_end->getTimestamp() );

		$result = $wpdb->insert(
			$user_course->get_table_name(),
			apply_filters(
				'masteriyo_new_user_course_data',
				array(
					'user_id'       => $user_course->get_user_id( 'edit' ),
					'item_id'       => $user_course->get_course_id( 'edit' ),
					'item_type'     => $user_course->get_type( 'edit' ),
					'status'        => $user_course->get_status( 'edit' ),
					'date_start'    => gmdate( 'Y-m-d H:i:s', $user_course->get_date_start( 'edit' )->getTimestamp() ),
					'date_modified' => gmdate( 'Y-m-d H:i:s', $user_course->get_date_modified( 'edit' )->getTimestamp() ),
					'date_end'      => $date_complete,
				),
				$user_course
			),
			array( '%d', '%d', '%s', '%s', '%s', '%s', '%s' )
		);

		if ( $result && $wpdb->insert_id ) {
			$user_course->set_id( $wpdb->insert_id );
			$this->update_custom_table_meta( $user_course, true );
			$user_course->save_meta_data();
			$user_course->apply_changes();
			$this->clear_cache( $user_course );

			do_action( 'masteriyo_new_user_course', $user_course->get_id(), $user_course );
		}

	}

	/**
	 * Update a user course item in the database.
	 *
	 * @since 0.1.0
	 * @param UserCourse $user_course user course object.
	 */
	public function update( Model &$user_course ) {
		global $wpdb;

		$changes = $user_course->get_changes();

		$user_course_data_keys = array(
			'user_id',
			'item_id',
			'status',
			'date_start',
			'date_modified',
			'date_end',
		);

		if ( array_intersect( $user_course_data_keys, array_keys( $changes ) ) ) {
			$wpdb->update(
				$user_course->get_table_name(),
				array(
					'user_id'       => $user_course->get_user_id( 'edit' ),
					'item_id'       => $user_course->get_item_id( 'edit' ),
					'item_type'     => $user_course->get_type( 'edit' ),
					'status'        => $user_course->get_status( 'edit' ),
					'date_start'    => gmdate( 'Y-m-d H:i:s', $user_course->get_date_start( 'edit' )->getTimestamp() ),
					'date_modified' => gmdate( 'Y-m-d H:i:s', $user_course->get_date_modified( 'edit' )->getTimestamp() ),
					'date_end'      => gmdate( 'Y-m-d H:i:s', $user_course->get_date_end( 'edit' )->getTimestamp() ),
				),
				array( 'user_item_id' => $user_course->get_id() )
			);
		}

		$this->update_custom_table_meta( $user_course );
		$user_course->save_meta_data();
		$user_course->apply_changes();
		$this->clear_cache( $user_course );

		do_action( 'masteriyo_update_user_course', $user_course->get_id(), $user_course );
	}

	/**
	 * Remove an user course from the database.
	 *
	 * @since 0.1.0
	 * @param UserCourse $user_course user course object.
	 * @param array         $args Array of args to pass to the delete method.
	 */
	public function delete( &$user_course, $args = array() ) {
		global $wpdb;

		if ( $user_course->get_id() ) {
			do_action( 'masteriyo_before_delete_user_course', $user_course->get_id() );

			$wpdb->delete( $wpdb->base_prefix . 'masteriyo_user_items', array( 'user_item_id' => $user_course->get_id() ) );
			$wpdb->delete( $wpdb->base_prefix . 'masteriyo_user_itemmeta', array( 'user_item_id' => $user_course->get_id() ) );

			do_action( 'masteriyo_delete_user_course', $user_course->get_id() );

			$user_course->set_status( 'trash' );

			$this->clear_cache( $user_course );
		}
	}

	/**
	 * Read a user course from the database.
	 *
	 * @since 0.1.0
	 *
	 * @param UserCourse $user_course user course object.
	 *
	 * @throws Exception If invalid user course object object.
	 */
	public function read( &$user_course ) {
		global $wpdb;

		$result = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}masteriyo_user_items WHERE user_item_id = %d;",
				$user_course->get_id()
			)
		);

		if ( ! $result ) {
			throw new \Exception( __( 'Invalid user course.', 'masteriyo' ) );
		}

		$user_course->set_props(
			array(
				'user_id'       => $result->user_id,
				'course_id'     => $result->item_id,
				'type'          => $result->item_type,
				'status'        => $result->status,
				'date_start'    => $this->string_to_timestamp( $result->date_start ),
				'date_modified' => $this->string_to_timestamp( $result->date_modified ),
				'date_end'      => $this->string_to_timestamp( $result->date_end ),
			)
		);

		$user_course->read_meta_data();
		$user_course->set_object_read( true );

		do_action( 'masteriyo_user_course_read', $user_course->get_id(), $user_course );
	}

	/**
	 * Clear meta cache.
	 *
	 * @since 0.1.0
	 *
	 * @param ThemeGrill\Masteriyo\Models\UserCourse $user_course User course object.
	 */
	public function clear_cache( &$user_course ) {
		wp_cache_delete( 'item' . $user_course->get_id(), 'masteriyo-user-course' );
		wp_cache_delete( 'items-' . $user_course->get_id(), 'masteriyo-user-course' );
		wp_cache_delete( $user_course->get_id(), $this->meta_type . '_meta' );
	}

	/**
	 * Fetch user course items.
	 *
	 * @since 0.1.0
	 *
	 * @param array $query_vars Query vars.
	 * @return UserCourse[]
	 */
	public function query( $query_vars ) {
		global $wpdb;

		$search_criteria = array();
		$sql[]           = "SELECT * FROM {$wpdb->base_prefix}masteriyo_user_items";

		// Construct where clause part.
		if ( ! empty( $query_vars['user_id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'user_id = %d', $query_vars['user_id'] );
		}

		if ( ! empty( $query_vars['course_id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'item_id = %d', $query_vars['course_id'] );
		}

		if ( ! empty( $query_vars['type'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'item_type = %s', $query_vars['type'] );
		}

		if ( ! empty( $query_vars['status'] ) && 'any' !== $query_vars['status'] ) {
			$search_criteria[] = $wpdb->prepare( 'status = %s', $query_vars['status'] );
		}

		if ( 1 <= count( $search_criteria ) ) {
			$criteria = implode( ' AND ', $search_criteria );
			$sql[]    = 'WHERE ' . $criteria;
		}

		// Construct order and order by part.
		$sql[] = 'ORDER BY ' . sanitize_sql_orderby( $query_vars['orderby'] . ' ' . $query_vars['order'] );

		// Construct limit part.
		$per_page = $query_vars['per_page'];

		if ( $query_vars['paged'] > 0 ) {
			$offset = ( $query_vars['paged'] - 1 ) * $per_page;
		}

		$sql[] = $wpdb->prepare( 'LIMIT %d, %d', $offset, $per_page );

		// Generate SQL from the SQL parts.
		$sql = implode( ' ', $sql ) . ';';

		// Fetch the results.
		$user_course = $wpdb->get_results( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		$ids = wp_list_pluck( $user_course, 'user_item_id' );

		return array_filter( array_map( 'masteriyo_get_user_course', $ids ) );
	}
}
