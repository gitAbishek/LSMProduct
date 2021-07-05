<?php
/**
 * Course progress rRepository.
 *
 * @package ThemeGrill\Masteriyo\Repository;
 */

namespace ThemeGrill\Masteriyo\Repository;

use ThemeGrill\Masteriyo\MetaData;
use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\ModelException;
use ThemeGrill\Masteriyo\Repository\AbstractRepository;

/**
 * Course progress repository class.
 */
class CourseProgressRepository extends AbstractRepository implements RepositoryInterface {

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
	 * Create a course progress in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $course_progress CourseProgress object.
	 */
	public function create( Model &$course_progress ) {
		global $wpdb;

		if ( ! $course_progress->get_started_at( 'edit' ) ) {
			$course_progress->set_started_at( current_time( 'mysql', true ) );
		}

		if ( ! $course_progress->get_modified_at( 'edit' ) ) {
			$course_progress->set_modified_at( current_time( 'mysql', true ) );
		}

		$completed_at = $course_progress->get_completed_at( 'edit' );
		$completed_at = is_null( $completed_at ) ? '' : gmdate( 'Y-m-d H:i:s', $completed_at->getTimestamp() );

		$result = $wpdb->insert(
			$course_progress->get_table_name(),
			apply_filters(
				'masteriyo_new_course_progress_data',
				array(
					'user_id'         => $course_progress->get_user_id( 'edit' ),
					'item_id'         => $course_progress->get_course_id( 'edit' ),
					'activity_type'   => $course_progress->get_type( 'edit' ),
					'activity_status' => $course_progress->get_status( 'edit' ),
					'created_at'      => gmdate( 'Y-m-d H:i:s', $course_progress->get_started_at( 'edit' )->getTimestamp() ),
					'modified_at'     => gmdate( 'Y-m-d H:i:s', $course_progress->get_modified_at( 'edit' )->getTimestamp() ),
					'completed_at'    => $completed_at,
				),
				$course_progress
			),
			array( '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s' )
		);

		if ( $result && $wpdb->insert_id ) {
			$course_progress->set_id( $wpdb->insert_id );
			$this->update_custom_table_meta( $course_progress, true );
			$this->update_course_progress_items( $course_progress, true );
			$course_progress->save_meta_data();
			$course_progress->apply_changes();
			$this->clear_cache( $course_progress );

			do_action( 'masteriyo_new_course_progress', $course_progress->get_id(), $course_progress );
		}

	}

	/**
	 * Update a course progress item in the database.
	 *
	 * @since 0.1.0
	 * @param CourseProgress $course_progress Course progress object.
	 */
	public function update( Model &$course_progress ) {
		global $wpdb;

		$changes = $course_progress->get_changes();

		$course_progress_data_keys = array(
			'user_id',
			'item_id',
			'status',
			'created_at',
			'modified_at',
			'completed_at',
		);

		if ( array_intersect( $course_progress_data_keys, array_keys( $changes ) ) ) {
			$completed_at = $course_progress->get_completed_at( 'edit' );
			$completed_at = is_null( $completed_at ) ? '' : gmdate( 'Y-m-d H:i:s', $completed_at->getTimestamp() );

			$wpdb->update(
				$course_progress->get_table_name(),
				array(
					'user_id'         => $course_progress->get_user_id( 'edit' ),
					'item_id'         => $course_progress->get_course_id( 'edit' ),
					'activity_type'   => $course_progress->get_type( 'edit' ),
					'activity_status' => $course_progress->get_status( 'edit' ),
					'created_at'      => gmdate( 'Y-m-d H:i:s', $course_progress->get_started_at( 'edit' )->getTimestamp() ),
					'modified_at'     => gmdate( 'Y-m-d H:i:s', $course_progress->get_modified_at( 'edit' )->getTimestamp() ),
					'completed_at'    => $completed_at,
				),
				array( 'id' => $course_progress->get_id() )
			);
		}

		$this->update_custom_table_meta( $course_progress );
		$this->update_course_progress_items( $course_progress, true );
		$course_progress->save_meta_data();
		$course_progress->apply_changes();
		$this->clear_cache( $course_progress );

		do_action( 'masteriyo_update_course_progress', $course_progress->get_id(), $course_progress );
	}

	/**
	 * Remove an course progress from the database.
	 *
	 * @since 0.1.0
	 * @param CourseProgress $course_progress Course progress object.
	 * @param array         $args Array of args to pass to the delete method.
	 */
	public function delete( &$course_progress, $args = array() ) {
		global $wpdb;

		if ( $course_progress->get_id() ) {
			do_action( 'masteriyo_before_delete_course_progress', $course_progress->get_id() );

			$wpdb->delete( $wpdb->base_prefix . 'masteriyo_user_activities', array( 'id' => $course_progress->get_id() ) );
			$wpdb->delete( $wpdb->base_prefix . 'masteriyo_user_activitymeta', array( 'user_activity_id' => $course_progress->get_id() ) );

			do_action( 'masteriyo_delete_course_progress', $course_progress->get_id() );

			$course_progress->set_status( 'trash' );

			$this->clear_cache( $course_progress );
		}
	}

	/**
	 * Read a course progress from the database.
	 *
	 * @since 0.1.0
	 *
	 * @param CourseProgress $course_progress Course progress object.
	 *
	 * @throws Exception If invalid course progress object object.
	 */
	public function read( &$course_progress ) {
		global $wpdb;

		$progress_obj = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}masteriyo_user_activities WHERE id = %d;",
				$course_progress->get_id()
			)
		);

		if ( ! $progress_obj || 'course_progress' !== $progress_obj->activity_type ) {
			throw new ModelException(
				'masteriyo_invalid_course_progress_id',
				__( 'Invalid course progress ID.', 'masteriyo' ),
				400
			);
		}

		$course_progress->set_props(
			array(
				'user_id'      => $progress_obj->user_id,
				'course_id'    => $progress_obj->item_id,
				'type'         => $progress_obj->activity_type,
				'status'       => $progress_obj->activity_status,
				'started_at'   => $this->string_to_timestamp( $progress_obj->created_at ),
				'modified_at'  => $this->string_to_timestamp( $progress_obj->modified_at ),
				'completed_at' => $this->string_to_timestamp( $progress_obj->completed_at ),
			)
		);

		$course_progress->read_meta_data();
		$this->read_course_progress_items( $course_progress );
		$course_progress->set_object_read( true );

		do_action( 'masteriyo_course_progress_read', $course_progress->get_id(), $course_progress );
	}

	/**
	 * Clear meta cache.
	 *
	 * @since 0.1.0
	 *
	 * @param CourseProgress $course_progress Course progress object.
	 */
	public function clear_cache( &$course_progress ) {
		wp_cache_delete( 'item' . $course_progress->get_id(), 'masteriyo-course-progress' );
		wp_cache_delete( 'items-' . $course_progress->get_id(), 'masteriyo-course-progress' );
		wp_cache_delete( $course_progress->get_id(), $this->meta_type . '_meta' );
	}

	/**
	 * Fetch course progress items.
	 *
	 * @since 0.1.0
	 *
	 * @param array $query_vars Query vars.
	 * @return CourseProgress[]
	 */
	public function query( $query_vars ) {
		global $wpdb;

		$search_criteria = array();
		$sql[]           = "SELECT * FROM {$wpdb->base_prefix}masteriyo_user_activities";

		// Construct where clause part.
		if ( ! empty( $query_vars['user_id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'user_id = %d', $query_vars['user_id'] );
		}

		if ( ! empty( $query_vars['course_id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'item_id = %d', $query_vars['course_id'] );
		}

		if ( ! empty( $query_vars['activity_type'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'activity_type = %s', 'course_progress' );
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

		if ( $query_vars['page'] > 0 ) {
			$offset = ( $query_vars['page'] - 1 ) * $per_page;
		}

		$sql[] = $wpdb->prepare( 'LIMIT %d, %d', $offset, $per_page );

		// Generate SQL from the SQL parts.
		$sql = implode( ' ', $sql ) . ';';

		// Fetch the results.
		$course_progress = $wpdb->get_results( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		$ids = wp_list_pluck( $course_progress, 'id' );

		return array_filter( array_map( 'masteriyo_get_course_progress', $ids ) );
	}

	/**
	 * Update course progress items.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $model model object.
	 * @param bool  $force Force update. Used during create.
	 */
	protected function update_course_progress_items( $course_progress, $force = false ) {
		foreach ( $course_progress->get_items() as $item ) {
			$this->update_single_course_progress_item( $course_progress, $item );
		}
	}

	/**
	 * Update a single course progress item.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $course_progress Course progress object.
	 */
	protected function update_single_course_progress_item( $course_progress, $item ) {
		global $wpdb;

		foreach ( $course_progress->get_item_changes() as $item ) {
			if ( isset( $item['id'] ) && ! empty( $item['id'] ) ) {
				$wpdb->update(
					"{$wpdb->base_prefix}masteriyo_user_activitymeta",
					array(
						'user_activity_id' => $course_progress->get_id(),
						'meta_key'         => $item['item_id'],
						'meta_value'       => masteriyo_bool_to_string( $item['is_completed'] ),
					),
					array( 'meta_id' => $item['id'] ),
					array(
						'%d',
						'%s',
						'%s',
					),
					array( '%d' )
				);
			} else {
				$wpdb->insert(
					"{$wpdb->base_prefix}masteriyo_user_activitymeta",
					array(
						'user_activity_id' => $course_progress->get_id(),
						'meta_key'         => $item['item_id'],
						'meta_value'       => masteriyo_bool_to_string( $item['is_completed'] ),
					),
					array(
						'%d',
						'%s',
						'%s',
					)
				);
			}
		}
	}

	/**
	 * Read all course progress items.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $course_progress Course progress object.
	 */
	protected function read_course_progress_items( $course_progress ) {
		global $wpdb;

		$items = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->base_prefix}masteriyo_user_activitymeta WHERE user_activity_id = %d",
				$course_progress->get_id()
			)
		);

		$items = array_map(
			function( $item ) {
				return array(
					'id'           => absint( $item->meta_id ),
					'item_id'      => absint( $item->meta_key ),
					'item_type'    => $item->meta_type,
					'is_completed' => masteriyo_string_to_bool( $item->meta_value ),
				);
			},
			$items
		);

		$course_progress->set_items( $items );
	}

}
