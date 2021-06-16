<?php
/**
 * Quiz progress rRepository.
 *
 * @package ThemeGrill\Masteriyo\Repository;
 */

namespace ThemeGrill\Masteriyo\Repository;

use ThemeGrill\Masteriyo\MetaData;
use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Models\UserActivity;
use ThemeGrill\Masteriyo\Repository\AbstractRepository;

/**
 * Quiz progress repository class.
 */
class QuizProgressRepository extends AbstractRepository implements RepositoryInterface {

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
	 * Create a quiz progress in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $quiz_progress QuizProgress object.
	 */
	public function create( Model &$quiz_progress ) {
		global $wpdb;

		if ( ! $quiz_progress->get_date_start( 'edit' ) ) {
			$quiz_progress->set_date_start( current_time( 'mysql', true ) );
		}

		if ( ! $quiz_progress->get_date_update( 'edit' ) ) {
			$quiz_progress->set_date_update( current_time( 'mysql', true ) );
		}

		$date_complete = $quiz_progress->get_date_complete( 'edit' );
		$date_complete = is_null( $date_complete ) ? '' : gmdate( 'Y-m-d H:i:s', $date_complete->getTimestamp() );

		$result = $wpdb->insert(
			$quiz_progress->get_table_name(),
			apply_filters(
				'masteriyo_new_quiz_progress_data',
				array(
					'user_id'         => $quiz_progress->get_user_id( 'edit' ),
					'item_id'         => $quiz_progress->get_quiz_id( 'edit' ),
					'activity_type'   => $quiz_progress->get_type( 'edit' ),
					'activity_status' => $quiz_progress->get_status( 'edit' ),
					'date_start'      => gmdate( 'Y-m-d H:i:s', $quiz_progress->get_date_start( 'edit' )->getTimestamp() ),
					'date_update'     => gmdate( 'Y-m-d H:i:s', $quiz_progress->get_date_update( 'edit' )->getTimestamp() ),
					'date_complete'   => $date_complete,
				),
				$quiz_progress
			),
			array( '%d', '%d', '%s', '%s', '%s', '%s', '%s' )
		);

		if ( $result && $wpdb->insert_id ) {
			$quiz_progress->set_id( $wpdb->insert_id );
			$this->update_custom_table_meta( $quiz_progress, true );
			$this->update_quiz_progress_items( $quiz_progress, true );
			$quiz_progress->save_meta_data();
			$quiz_progress->apply_changes();
			$this->clear_cache( $quiz_progress );

			do_action( 'masteriyo_new_quiz_progress', $quiz_progress->get_id(), $quiz_progress );
		}

	}

	/**
	 * Update a quiz progress item in the database.
	 *
	 * @since 0.1.0
	 * @param QuizProgress $quiz_progress Quiz progress object.
	 */
	public function update( Model &$quiz_progress ) {
		global $wpdb;

		$changes = $quiz_progress->get_changes();

		$quiz_progress_data_keys = array(
			'user_id',
			'item_id',
			'status',
			'date_start',
			'date_update',
			'date_complete',
		);

		if ( array_intersect( $quiz_progress_data_keys, array_keys( $changes ) ) ) {
			$wpdb->update(
				$wpdb->prefix . 'masteriyo_quiz_progress',
				array(
					'user_id'         => $quiz_progress->get_user_id( 'edit' ),
					'item_id'         => $quiz_progress->get_item_id( 'edit' ),
					'activity_type'   => $quiz_progress->get_type( 'edit' ),
					'activity_status' => $quiz_progress->get_status( 'edit' ),
					'date_start'      => gmdate( 'Y-m-d H:i:s', $quiz_progress->get_date_start( 'edit' )->getTimestamp() ),
					'date_update'     => gmdate( 'Y-m-d H:i:s', $quiz_progress->get_date_update( 'edit' )->getTimestamp() ),
					'date_complete'   => gmdate( 'Y-m-d H:i:s', $quiz_progress->get_date_complete( 'edit' )->getTimestamp() ),
				),
				array( 'activity_id' => $quiz_progress->get_id() )
			);
		}

		$this->update_custom_table_meta( $quiz_progress );
		$this->update_quiz_progress_items( $quiz_progress, true );
		$quiz_progress->save_meta_data();
		$quiz_progress->apply_changes();
		$this->clear_cache( $quiz_progress );

		do_action( 'masteriyo_update_quiz_progress', $quiz_progress->get_id(), $quiz_progress );
	}

	/**
	 * Remove an quiz progress from the database.
	 *
	 * @since 0.1.0
	 * @param QuizProgress $quiz_progress Quiz progress object.
	 * @param array         $args Array of args to pass to the delete method.
	 */
	public function delete( &$quiz_progress, $args = array() ) {
		global $wpdb;

		if ( $quiz_progress->get_id() ) {
			do_action( 'masteriyo_before_delete_quiz_progress', $quiz_progress->get_id() );

			$wpdb->delete( $wpdb->base_prefix . 'masteriyo_user_activities', array( 'activity_id' => $quiz_progress->get_id() ) );
			$wpdb->delete( $wpdb->base_prefix . 'masteriyo_user_activitymeta', array( 'user_activity_id' => $quiz_progress->get_id() ) );

			do_action( 'masteriyo_delete_quiz_progress', $quiz_progress->get_id() );

			$quiz_progress->set_status( 'trash' );

			$this->clear_cache( $quiz_progress );
		}
	}

	/**
	 * Read a quiz progress from the database.
	 *
	 * @since 0.1.0
	 *
	 * @param QuizProgress $quiz_progress Quiz progress object.
	 *
	 * @throws Exception If invalid quiz progress object object.
	 */
	public function read( &$quiz_progress ) {
		global $wpdb;

		$result = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}masteriyo_user_activities WHERE activity_id = %d;",
				$quiz_progress->get_id()
			)
		);

		if ( ! $result ) {
			throw new \Exception( __( 'Invalid quiz progress.', 'masteriyo' ) );
		}

		$quiz_progress->set_props(
			array(
				'user_id'       => $result->user_id,
				'quiz_id'       => $result->item_id,
				'type'          => $result->activity_type,
				'status'        => $result->activity_status,
				'date_start'    => $this->string_to_timestamp( $result->date_start ),
				'date_update'   => $this->string_to_timestamp( $result->date_update ),
				'date_complete' => $this->string_to_timestamp( $result->date_complete ),
			)
		);

		$quiz_progress->read_meta_data();
		$this->read_quiz_progress_items( $quiz_progress );
		$quiz_progress->set_object_read( true );

		do_action( 'masteriyo_quiz_progress_read', $quiz_progress->get_id(), $quiz_progress );
	}

	/**
	 * Clear meta cache.
	 *
	 * @since 0.1.0
	 *
	 * @param UserActivity $quiz_progress Quiz progress object.
	 */
	public function clear_cache( &$quiz_progress ) {
		wp_cache_delete( 'item' . $quiz_progress->get_id(), 'masteriyo-quiz-progress' );
		wp_cache_delete( 'items-' . $quiz_progress->get_id(), 'masteriyo-quiz-progress' );
		wp_cache_delete( $quiz_progress->get_id(), $this->meta_type . '_meta' );
	}

	/**
	 * Fetch quiz progress items.
	 *
	 * @since 0.1.0
	 *
	 * @param array $query_vars Query vars.
	 * @return QuizProgress[]
	 */
	public function query( $query_vars ) {
		global $wpdb;

		$search_criteria = array();
		$sql[]           = "SELECT * FROM {$wpdb->base_prefix}masteriyo_user_activities";

		// Construct where clause part.
		if ( ! empty( $query_vars['user_id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'user_id = %d', $query_vars['user_id'] );
		}

		if ( ! empty( $query_vars['quiz_id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'item_id = %d', $query_vars['quiz_id'] );
		}

		if ( ! empty( $query_vars['activity_type'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'activity_type = %s', $query_vars['activity_type'] );
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
		$quiz_progress = $wpdb->get_results( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		$ids = wp_list_pluck( $quiz_progress, 'activity_id' );

		return array_filter( array_map( 'masteriyo_get_quiz_progress', $ids ) );
	}

	/**
	 * Update quiz progress items.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $model model object.
	 * @param bool  $force Force update. Used during create.
	 */
	protected function update_quiz_progress_items( $quiz_progress, $force = false ) {
		foreach ( $quiz_progress->get_items() as $item ) {
			$this->update_single_quiz_progress_item( $quiz_progress, $item );
		}
	}

	/**
	 * Update a single quiz progress item.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $quiz_progress Quiz progress object.
	 */
	protected function update_single_quiz_progress_item( $quiz_progress, $item ) {
		global $wpdb;

		foreach ( $quiz_progress->get_item_changes() as $item ) {
			if ( isset( $item['id'] ) && ! empty( $item['id'] ) ) {
				$wpdb->update(
					"{$wpdb->base_prefix}masteriyo_user_activitymeta",
					array(
						'user_activity_id' => $quiz_progress->get_id(),
						'meta_key'         => $item['item_id'],
						'meta_type'        => $item['item_type'],
						'meta_value'       => masteriyo_bool_to_string( $item['is_completed'] ),
					),
					array( 'meta_id' => $item['id'] ),
					array(
						'%d',
						'%s',
						'%s',
						'%s',
					),
					array( '%d' )
				);
			} else {
				$wpdb->insert(
					"{$wpdb->base_prefix}masteriyo_user_activitymeta",
					array(
						'user_activity_id' => $quiz_progress->get_id(),
						'meta_key'         => $item['item_id'],
						'meta_type'        => $item['item_type'],
						'meta_value'       => masteriyo_bool_to_string( $item['is_completed'] ),
					),
					array(
						'%d',
						'%s',
						'%s',
						'%s',
					)
				);
			}
		}
	}

	/**
	 * Read all quiz progress items.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $quiz_progress Quiz progress object.
	 */
	protected function read_quiz_progress_items( $quiz_progress ) {
		global $wpdb;

		$items = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->base_prefix}masteriyo_user_activitymeta WHERE user_activity_id = %d",
				$quiz_progress->get_id()
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

		$quiz_progress->set_items( $items );
	}

}
