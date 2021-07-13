<?php
/**
 * Course progress model.
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
 * Course progress model (custom table).
 *
 * @since 0.1.0
 */
class CourseProgress extends Model {

	/**
	 * This is the name of this object type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $object_type = 'course-progress';

	/**
	 * Cache group.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $cache_group = 'course-progresses';

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
		'user_id'      => 0,
		'course_id'    => 0,
		'status'       => '',
		'started_at'   => null,
		'modified_at'  => null,
		'completed_at' => null,
	);

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param RepositoryInterface $course_progress_repository Course progress Repository,
	 */
	public function __construct( RepositoryInterface $course_progress_repository ) {
		$this->repository = $course_progress_repository;
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
		return 'course_progress';
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
	public function get_started_at( $context = 'view' ) {
		return $this->get_prop( 'started_at', $context );
	}

	/**
	 * Get course progress update.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. valid values are view and edit.
	 * @return DateTime|null
	 */
	public function get_modified_at( $context = 'view' ) {
		return $this->get_prop( 'modified_at', $context );
	}

	/**
	 * Get course progress complete.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. valid values are view and edit.
	 * @return DateTime|null
	 */
	public function get_completed_at( $context = 'view' ) {
		return $this->get_prop( 'completed_at', $context );
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
	 * Set course progress status.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $status Course progress status.
	 */
	public function set_status( $status ) {
		$this->set_prop( 'status', $status );
		do_action( "masteriyo_course_status_{$status}", $status, $this );
	}

	/**
	 * Set course progress start.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $start Course progress start.
	 */
	public function set_started_at( $started_at ) {
		$this->set_date_prop( 'started_at', $started_at );
	}

	/**
	 * Set course progress update.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $update Course progress update.
	 */
	public function set_modified_at( $modified_at ) {
		$this->set_date_prop( 'modified_at', $modified_at );
	}

	/**
	 * Set course progress complete.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $complete Course progress complete.
	 */
	public function set_completed_at( $completed_at ) {
		$this->set_date_prop( 'completed_at', $completed_at );
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
		if ( empty( $this->items ) ) {
			$this->items = $this->repository->get_course_progress_items( $this );
		}

		return $this->items;
	}

	/**
	 * Get user course progress summary
	 *
	 * @return void
	 */
	public function get_summary( $type = 'all' ) {
		return $this->repository->get_summary( $this, $type );
	}
}
