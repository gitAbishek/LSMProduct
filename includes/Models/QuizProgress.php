<?php
/**
 * Quiz progress model.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Models;
 */

namespace ThemeGrill\Masteriyo\Models;

use ThemeGrill\Masteriyo\MetaData;
use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Cache\CacheInterface;
use ThemeGrill\Masteriyo\Repository\RepositoryInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Quiz progress model (custom table).
 *
 * @since 0.1.0
 */
class QuizProgress extends UserActivity {

	/**
	 * This is the name of this object type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $object_type = 'quiz-progress';

	/**
	 * Cache group.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $cache_group = 'quiz-progress';

	/**
	 * Quiz progress items (lesson, quiz) etc.
	 *
	 * @var array
	 */
	protected $items = array();


	/**
	 * Store items which are changed.
	 *
	 * @var array
	 */
	protected $item_changes = array();

	/**
	 * Stores user activity data.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $data = array(
		'user_id'       => 0,
		'quiz_id'       => 0,
		'type'          => 'quiz',
		'status'        => 'begin',
		'date_start'    => null,
		'date_update'   => null,
		'date_complete' => null,
	);

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param RepositoryInterface $quiz_progress_repository Quiz progress Repository,
	 */
	public function __construct( RepositoryInterface $quiz_progress_repository ) {
		$this->repository = $quiz_progress_repository;
	}

	/*
	|--------------------------------------------------------------------------
	| Non-CRUD Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get user activity table.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_table_name() {
		global $wpdb;

		return "{$wpdb->base_prefix}masteriyo_user_activities";
	}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get user activity items.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_items( $context = 'view' ) {
		return $this->items;
	}

	/**
	 * Get quiz id.
	 *
	 * @param string $context
	 * @return integer
	 */
	public function get_quiz_id( $context = 'view' ) {
		return $this->get_prop( 'quiz_id', $context );
	}

	/*
	|--------------------------------------------------------------------------
	| Settters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set quiz ID.
	 *
	 * @since 0.1.0
	 *
	* @param int $quiz_id Quiz ID.
	 */
	public function set_quiz_id( $quiz_id ) {
		$this->set_prop( 'quiz_id', absint( $quiz_id ) );
	}

	/**
	 * Set quiz progress item.
	 *
	 * @since 0.1.0
	 *
	 * @param array $items Quiz progress items.
	 */
	public function set_items( $items ) {
		foreach ( $items as $item ) {
			if ( isset( $item['id'] ) && ! empty( $item['id'] ) ) {
				$this->items[] = $item;
			} else {
				$this->set_item_changes( $item );
			}
		}
	}

	/**
	 * Set quiz items which are changed.
	 *
	 * @since 0.1.0
	 *
	 * @param array $progress_item Progress item.
	 */
	protected function set_item_changes( $progress_item ) {
		$changed     = false;
		$changes_key = array( 'item_type', 'is_completed' );

		foreach ( $this->items as $item ) {
			if ( $item['item_id'] === $progress_item['item_id'] && count( array_intersect( $item, $progress_item ) ) > 1 ) {
				$this->item_changes[] = wp_parse_args( $progress_item, $item );
				$changed              = true;
				break;
			}
		}

		if ( ! $changed ) {
			$this->item_changes[] = $progress_item;
		}
	}

	/**
	 * Get quiz progress item changes.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_item_changes() {
		return $this->item_changes;
	}

	/**
	 * Set quiz progress item.
	 *
	 * @since 0.1.0
	 *
	 * @param array $item Quiz progress item
	 */
	public function add_item( $context = 'view' ) {
		$this->items[] = item;
	}
}
