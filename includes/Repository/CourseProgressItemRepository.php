<?php
/**
 * Course progress item repository.
 *
 * @package ThemeGrill\Masteriyo\Repository;
 */

namespace ThemeGrill\Masteriyo\Repository;

use ThemeGrill\Masteriyo\MetaData;
use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\ModelException;
use ThemeGrill\Masteriyo\Models\UserActivity;
use ThemeGrill\Masteriyo\Repository\AbstractRepository;

/**
 * Course progress repository class.
 */
class CourseProgressItemRepository extends AbstractRepository implements RepositoryInterface {

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
	 * Create a course progress item in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $course_progress_item Course progress item object.
	 */
	public function create( Model &$course_progress_item ) {
		global $wpdb;

		// Bail early if the course progress is not valid.
		$course_progress = masteriyo_get_course_progress( $course_progress_item->get_progress_id() );
		if ( is_wp_error( $course_progress ) ) {
			return $course_progress;
		}

		// Bail early if the user id is invalid.
		$user = masteriyo_get_user( $course_progress_item->get_user_id() );
		if ( is_wp_error( $user ) ) {
			return $user;
		}

		if ( ! $course_progress_item->get_date_start( 'edit' ) ) {
			$course_progress_item->set_date_start( current_time( 'mysql', true ) );
		}

		if ( ! $course_progress_item->get_date_update( 'edit' ) ) {
			$course_progress_item->set_date_update( current_time( 'mysql', true ) );
		}

		$date_complete = $course_progress_item->get_date_complete( 'edit' );
		$date_complete = is_null( $date_complete ) ? '' : gmdate( 'Y-m-d H:i:s', $date_complete->getTimestamp() );

		$result = $wpdb->insert(
			$course_progress_item->get_table_name(),
			apply_filters(
				'masteriyo_new_course_progress_data',
				array(
					'user_id'         => $course_progress_item->get_user_id( 'edit' ),
					'item_id'         => $course_progress_item->get_item_id( 'edit' ),
					'parent_id'       => $course_progress_item->get_progress_id( 'edit' ),
					'activity_type'   => $course_progress_item->get_type( 'edit' ),
					'activity_status' => $course_progress_item->get_status( 'edit' ),
					'date_start'      => gmdate( 'Y-m-d H:i:s', $course_progress_item->get_date_start( 'edit' )->getTimestamp() ),
					'date_update'     => gmdate( 'Y-m-d H:i:s', $course_progress_item->get_date_update( 'edit' )->getTimestamp() ),
					'date_complete'   => $date_complete,
				),
				$course_progress_item
			),
			array( '%d', '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s' )
		);

		if ( $result && $wpdb->insert_id ) {
			$course_progress_item->set_id( $wpdb->insert_id );
			$this->update_custom_table_meta( $course_progress_item, true );
			$course_progress_item->save_meta_data();
			$course_progress_item->apply_changes();
			$this->clear_cache( $course_progress_item );

			do_action( 'masteriyo_new_course_progress_item', $course_progress_item->get_id(), $course_progress_item );
		}

	}

	/**
	 * Update a course progress item in the database.
	 *
	 * @since 0.1.0
	 * @param CourseProgressItem $course_progress_item Course progress item object.
	 */
	public function update( Model &$course_progress_item ) {
		global $wpdb;

		$changes = $course_progress_item->get_changes();

		$course_progress_item_data_keys = array(
			'user_id',
			'item_id',
			'progress_id',
			'status',
			'date_start',
			'date_update',
			'date_complete',
		);

		if ( array_intersect( $course_progress_item_data_keys, array_keys( $changes ) ) ) {
			// Set the complete date if the status is complete.
			$date_complete = '';
			if ( 'complete' === $course_progress_item->get_status( 'edit' ) ) {
				$date_complete = $course_progress_item->get_date_complete( 'edit' );
				$date_complete = is_null( $date_complete ) ? '' : gmdate( 'Y-m-d H:i:s', $date_complete->getTimestamp() );
			}

			// Set the update if there is any change and the user hasn't set the update value manually.
			$date_update = gmdate( 'Y-m-d H:i:s', $course_progress_item->get_date_update( 'edit' )->getTimestamp() );
			if ( ! in_array( 'date_update', array_keys( $changes ), true ) ) {
				$date_update = $course_progress_item->get_date_update( 'edit' );
				$date_update = is_null( $date_update ) ? '' : gmdate( 'Y-m-d H:i:s', $date_update->getTimestamp() );
			}

			$wpdb->update(
				$course_progress_item->get_table_name(),
				array(
					'user_id'         => $course_progress_item->get_user_id( 'edit' ),
					'item_id'         => $course_progress_item->get_item_id( 'edit' ),
					'parent_id'       => $course_progress_item->get_progress_id( 'edit' ),
					'activity_type'   => $course_progress_item->get_type( 'edit' ),
					'activity_status' => $course_progress_item->get_status( 'edit' ),
					'date_start'      => gmdate( 'Y-m-d H:i:s', $course_progress_item->get_date_start( 'edit' )->getTimestamp() ),
					'date_update'     => $date_update,
					'date_complete'   => $date_complete,
				),
				array( 'id' => $course_progress_item->get_id() )
			);
		}

		$this->update_custom_table_meta( $course_progress_item );
		$course_progress_item->save_meta_data();
		$course_progress_item->apply_changes();
		$this->clear_cache( $course_progress_item );

		do_action( 'masteriyo_update_course_progress_item', $course_progress_item->get_id(), $course_progress_item );
	}

	/**
	 * Remove an course progress item from the database.
	 *
	 * @since 0.1.0
	 * @param CourseProgressItem $course_progress_item Course progress item object.
	 * @param array         $args Array of args to pass to the delete method.
	 */
	public function delete( &$course_progress_item, $args = array() ) {
		global $wpdb;

		if ( $course_progress_item->get_id() ) {
			do_action( 'masteriyo_before_delete_course_progress_item', $course_progress_item->get_id() );

			$wpdb->delete( $wpdb->base_prefix . 'masteriyo_user_activities', array( 'id' => $course_progress_item->get_id() ) );
			$wpdb->delete( $wpdb->base_prefix . 'masteriyo_user_activitymeta', array( 'user_activity_id' => $course_progress_item->get_id() ) );

			do_action( 'masteriyo_delete_course_progress', $course_progress_item->get_id() );

			$course_progress_item->set_status( 'trash' );

			$this->clear_cache( $course_progress_item );
		}
	}

	/**
	 * Read a course progress from the database.
	 *
	 * @since 0.1.0
	 *
	 * @param CourseProgress $course_progress_item Course progress object.
	 *
	 * @throws ModelException If invalid course progress object object.
	 */
	public function read( &$course_progress_item ) {
		global $wpdb;

		$result = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}masteriyo_user_activities WHERE id = %d;",
				$course_progress_item->get_id()
			)
		);

		if ( ! $result ) {
			throw new ModelException(
				'masteriyo_invalid_course_progress_item',
				__( 'Invalid course progress item ID.', 'masteriyo' ),
				400
			);
		}

		$course_progress_item->set_props(
			array(
				'user_id'       => $result->user_id,
				'item_id'       => $result->item_id,
				'progress_id'   => $result->parent_id,
				'type'          => $result->activity_type,
				'status'        => $result->activity_status,
				'date_start'    => $this->string_to_timestamp( $result->date_start ),
				'date_update'   => $this->string_to_timestamp( $result->date_update ),
				'date_complete' => $this->string_to_timestamp( $result->date_complete ),
			)
		);

		$course_progress_item->read_meta_data();
		$course_progress_item->set_object_read( true );

		do_action( 'masteriyo_course_progress_item_read', $course_progress_item->get_id(), $course_progress_item );
	}

	/**
	 * Clear meta cache.
	 *
	 * @since 0.1.0
	 *
	 * @param CourseProgressItem $course_progress_item Course progress item object.
	 */
	public function clear_cache( &$course_progress_item ) {
		wp_cache_delete( 'item' . $course_progress_item->get_id(), 'masteriyo-course-progress-item' );
		wp_cache_delete( 'items-' . $course_progress_item->get_id(), 'masteriyo-course-progress-item' );
		wp_cache_delete( $course_progress_item->get_id(), $this->meta_type . '_meta' );
	}

	/**
	 * Fetch course progress items.
	 *
	 * @since 0.1.0
	 *
	 * @param array $query_vars Query vars.
	 * @return CourseProgressItem[]
	 */
	public function query( $query_vars ) {
		global $wpdb;

		$search_criteria = array();
		$sql[]           = "SELECT * FROM {$wpdb->base_prefix}masteriyo_user_activities";

		// Construct where clause part.
		if ( ! empty( $query_vars['user_id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'user_id = %d', $query_vars['user_id'] );
		}

		if ( ! empty( $query_vars['item_id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'item_id = %d', $query_vars['item_id'] );
		}

		if ( ! empty( $query_vars['progress_id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'parent_id = %d', $query_vars['progress_id'] );
		}

		if ( ! empty( $query_vars['item_type'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'activity_type = %s', $query_vars['item_type'] );
		}

		if ( ! empty( $query_vars['status'] ) && 'any' !== $query_vars['status'] ) {
			$search_criteria[] = $wpdb->prepare( 'activity_status = %s', $query_vars['status'] );
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
		$course_progress_item = $wpdb->get_results( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		$ids = wp_list_pluck( $course_progress_item, 'id' );

		return array_filter( array_map( 'masteriyo_get_course_progress_item', $ids ) );
	}
}
