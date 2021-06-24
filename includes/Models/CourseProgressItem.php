<?php
/**
 * Course progress item model.
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
 * Course progress item model (custom table).
 *
 * @since 0.1.0
 */
class CourseProgressItem extends Model {

	/**
	 * This is the name of this object type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $object_type = 'course-progress-item';

	/**
	 * Cache group.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $cache_group = 'course-progress-items';

	/**
	 * Course progress items (lesson, quiz) etc.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $items = array();

	/**
	 * Store items which are changed.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $item_changes = array();

	/**
	 * Stores user course progress data.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $data = array(
		'user_id'       => 0,
		'item_id'       => 0,
		'course_id'     => 0,
		'type'          => '',
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
	 * @param RepositoryInterface $course_progress_item_item_repository Course progress Repository,
	 */
	public function __construct( RepositoryInterface $course_progress_item_repository ) {
		$this->repository = $course_progress_item_repository;
	}

	/*
	|--------------------------------------------------------------------------
	| Non-CRUD Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get user course progress table.
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
	 * Get user ID.
	 *
	 * @since 0.1.0
	 *
	* @param  string $context What the value is for. Valid values are view and edit.

	 * @return int
	 */
	public function get_user_id( $context = 'view' ) {
		return $this->get_prop( 'user_id', $context );
	}

	/**
	 * Get course progress(quiz, lesson) item ID.
	 *
	 * @since 0.1.0
	 *
	* @param  string $context What the value is for. Valid values are view and edit.

	 * @return int
	 */
	public function get_item_id( $context = 'view' ) {
		return $this->get_prop( 'item_id', $context );
	}

	/**
	 * Get course id.
	 *
	 * @since 0.1.0
	 *
	* @param  string $context What the value is for. Valid values are view and edit.

	 * @return int
	 */
	public function get_course_id( $context = 'view' ) {
		return $this->get_prop( 'course_id', $context );
	}

	/**
	 * Get course progress type.
	 *
	 * @since 0.1.0
	 *
	* @param  string $context What the value is for. valid values are view and edit.

	 * @return string
	 */
	public function get_type( $context = 'view' ) {
		return $this->get_prop( 'type', $context );
	}

	/**
	 * Get course progress status.
	 *
	 * @since 0.1.0
	 *
	* @param  string $context What the value is for. valid values are view and edit.

	 * @return int
	 */
	public function get_status( $context = 'view' ) {
		return $this->get_prop( 'status', $context );
	}

	/**
	 * Get course progress start.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. valid values are view and edit.
	 * @return DateTime|null
	 */
	public function get_date_start( $context = 'view' ) {
		return $this->get_prop( 'date_start', $context );
	}

	/**
	 * Get course progress update.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. valid values are view and edit.
	 * @return DateTime|null
	 */
	public function get_date_update( $context = 'view' ) {
		return $this->get_prop( 'date_update', $context );
	}

	/**
	 * Get course progress complete.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. valid values are view and edit.
	 * @return DateTime|null
	 */
	public function get_date_complete( $context = 'view' ) {
		return $this->get_prop( 'date_complete', $context );
	}

	/*
	|--------------------------------------------------------------------------
	| Settters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set user ID.
	 *
	 * @since 0.1.0
	 *
	* @param int $user_id User ID.
	 */
	public function set_user_id( $user_id ) {
		$this->set_prop( 'user_id', absint( $user_id ) );
	}

	/**
	 * Set course progress item (course, quiz) ID.
	 *
	 * @since 0.1.0
	 *
	* @param int $item_id Course progress item (course, quiz) ID.
	 */
	public function set_item_id( $item_id ) {
		$this->set_prop( 'item_id', absint( $item_id ) );
	}

	/**
	 * Set course ID.
	 *
	 * @since 0.1.0
	 *
	* @param int $course_id Course ID.
	 */
	public function set_course_id( $course_id ) {
		$this->set_prop( 'course_id', absint( $course_id ) );
	}

	/**
	 * Set course progress type.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $type Course progress type.
	 */
	public function set_type( $type ) {
		$this->set_prop( 'type', $type );
	}

	/**
	 * Set course progress status.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $status Course progress status.
	 */
	public function set_status( $status ) {
		$this->set_prop( 'status', $status );
	}

	/**
	 * Set course progress start.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $start Course progress start.
	 */
	public function set_date_start( $date_start ) {
		$this->set_date_prop( 'date_start', $date_start );
	}

	/**
	 * Set course progress update.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $update Course progress update.
	 */
	public function set_date_update( $date_update ) {
		$this->set_date_prop( 'date_update', $date_update );
	}

	/**
	 * Set course progress complete.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $complete Course progress complete.
	 */
	public function set_date_complete( $date_complete ) {
		$this->set_date_prop( 'date_complete', $date_complete );
	}

	/*
	|--------------------------------------------------------------------------
	| Activit items related functions.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get user course progress items.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_items( $context = 'view' ) {
		return $this->items;
	}

	/**
	 * Set course progress item.
	 *
	 * @since 0.1.0
	 *
	 * @param array $items Course progress items.
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
	 * Set course items which are changed.
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
	 * Get course progress item changes.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_item_changes() {
		return $this->item_changes;
	}

	/**
	 * Set course progress item.
	 *
	 * @since 0.1.0
	 *
	 * @param array $item Course progress item
	 */
	public function add_item( $context = 'view' ) {
		$this->items[] = item;
	}
}
