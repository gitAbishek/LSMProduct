<?php
/**
 * Course model.
 *
 * @since 0.1.0
 *
 * @package Masteriyo\Models;
 */

namespace Masteriyo\Models;

use Masteriyo\Database\Model;
use Masteriyo\Repository\RepositoryInterface;
use Masteriyo\Cache\CacheInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Course model (post type).
 *
 * @since 0.1.0
 */
class UserCourse extends Model {

	/**
	 * This is the name of this object type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $object_type = 'user-course';

	/**
	 * Post type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $post_type = 'user-course';

	/**
	 * Cache group.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $cache_group = 'user-courses';

	/**
	 * Stores data about status changes so relevant hooks can be fired.
	 *
	 * @since 0.1.0
	 *
	 * @var bool|array
	 */
	protected $status_transition = false;

	/**
	 * Stores user courses data.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $data = array(
		'course_id'     => 0,
		'user_id'       => 0,
		'status'        => '',
		'date_start'    => null,
		'date_modified' => null,
		'date_end'      => null,
		'order_id'      => 0,
		'price'         => '',
	);

	/**
	 * Get the use course if ID
	 *
	 * @since 0.1.0
	 *
	 * @param RepositoryInterface $course_repository Course Repository,
	 */
	public function __construct( RepositoryInterface $course_repository ) {
		$this->repository = $course_repository;
	}

	/*
	|--------------------------------------------------------------------------
	| Non-CRUD Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get table name.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_table_name() {
		global $wpdb;

		return "{$wpdb->base_prefix}masteriyo_user_items";
	}

	/**
	 * Get course object.
	 *
	 * @since 0.1.0
	 *
	 * @return Masteriyo\Models\Course|NULL
	 */
	public function get_course() {
		return masteriyo_get_course( $this->get_course_id() );
	}

	/**
	 * Get order associated with the course.
	 *
	 * @since 0.1.0
	 *
	 * @return Masteriyo\Models\Order|NULL
	 */
	public function get_order() {
		return masteriyo_get_order( $this->get_order_id() );
	}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get user's course ID.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int
	 */
	public function get_course_id( $context = 'view' ) {
		return $this->get_prop( 'course_id', $context );
	}

	/**
	 * Get user ID.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int
	 */
	public function get_user_id( $context = 'view' ) {
		return $this->get_prop( 'user_id', $context );
	}

	/**
	 * Get user's course status.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_type( $context = 'view' ) {
		return 'user_course';
	}

	/**
	 * Get user's course status.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_status( $context = 'view' ) {
		return $this->get_prop( 'status', $context );
	}

	/**
	 * Get user's course date start.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_date_start( $context = 'view' ) {
		return $this->get_prop( 'date_start', $context );
	}

	/**
	 * Get user's course date modified.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_date_modified( $context = 'view' ) {
		return $this->get_prop( 'date_modified', $context );
	}

	/**
	 * Get user's course date end.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_date_end( $context = 'view' ) {
		return $this->get_prop( 'date_end', $context );
	}

	/**
	 * Get user's course associated recent order ID.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_order_id( $context = 'view' ) {
		return $this->get_prop( 'order_id', $context );
	}

	/**
	 * Get user's course recent price.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_price( $context = 'view' ) {
		return $this->get_prop( 'price', $context );
	}


	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set user's course ID.
	 *
	 * @since 0.1.0
	 *
	 * @param int $value User course ID.
	 */
	public function set_course_id( $value ) {
		$this->set_prop( 'course_id', absint( $value ) );
	}

	/**
	 * Set user's ID.
	 *
	 * @since 0.1.0
	 *
	 * @param int $value User ID.
	 */
	public function set_user_id( $value ) {
		$this->set_prop( 'user_id', absint( $value ) );
	}

	/**
	 * Set user's course status (does nothing).
	 *
	 * @since  0.1.0
	 *
	 * @param string $value User item type.
	 */
	public function set_type( $value ) {
		// Don't updat the type.
	}

	/**
	 * Set user's course status.
	 *
	 * @since 0.1.0
	 *
	 * @param string $new_status    Status to change the user_course to.
	 * @param string $note          Optional note to add.
	 * @param bool   $manual_update Is this a manual user_course status change?.
	 * @return array
	 */
	public function set_status( $new_status, $note = '', $manual_update = false ) {
		$old_status = $this->get_status();

		// If setting the status, ensure it's set to a valid status.
		if ( true === $this->object_read ) {
			// Only allow valid new status.
			if ( ! in_array( $new_status, $this->get_valid_statuses(), true ) ) {
				$new_status = 'active';
			}

			// If the old status is set but unknown (e.g. active) assume its active for action usage.
			if ( $old_status && ! in_array( $old_status, $this->get_valid_statuses(), true ) ) {
				$old_status = 'active';
			}
		}

		$this->set_prop( 'status', $new_status );

		$result = array(
			'from' => $old_status,
			'to'   => $new_status,
		);

		if ( true === $this->object_read && ! empty( $result['from'] ) && $result['from'] !== $result['to'] ) {
			$this->status_transition = array(
				'from'   => ! empty( $this->status_transition['from'] ) ? $this->status_transition['from'] : $result['from'],
				'to'     => $result['to'],
				'manual' => (bool) $manual_update,
			);

			if ( $manual_update ) {
				do_action( 'masteriyo_user_course_edit_status', $this->get_id(), $result['to'] );
			}
		}

		return $result;
	}

	/**
	 * Set user's course start date
	 *
	 * @since 0.1.0
	 *
	 * @param int $value User's course start date.
	 */
	public function set_date_start( $value ) {
		$this->set_date_prop( 'date_start', $value );
	}

	/**
	 * Set user's course modified date
	 *
	 * @since 0.1.0
	 *
	 * @param int $value User's course modified date.
	 */
	public function set_date_modified( $value ) {
		$this->set_date_prop( 'date_modified', $value );
	}

	/**
	 * Set user's course end date
	 *
	 * @since 0.1.0
	 *
	 * @param int $value User's course end date.
	 */
	public function set_date_end( $value ) {
		$this->set_date_prop( 'date_end', $value );
	}

	/**
	 * Set user's course associated recent order ID.
	 *
	 * @since 0.1.0
	 *
	 * @param int $value User's course end date.
	 */
	public function set_order_id( $value ) {
		$this->set_prop( 'order_id', absint( $value ) );
	}

	/**
	 * Set user's course price.
	 *
	 * @since 0.1.0
	 *
	 * @param int $value User's course end date.
	 */
	public function set_price( $value ) {
		$this->set_prop( 'price', $value );
	}

	/*
	|--------------------------------------------------------------------------
	| CRUD methods
	|--------------------------------------------------------------------------
	|
	*/
	/**
	 * Save data to the database.
	 *
	 * @since 0.1.0
	 * @return int user_course ID
	 */
	public function save() {
		parent::save();
		$this->status_transition();

		return $this->get_id();
	}

	/*
	|--------------------------------------------------------------------------
	| Non-CRUD methods
	|--------------------------------------------------------------------------
	|
	*/

	/**
	 * Handle the status transition.
	 *
	 * @since 0.1.0
	 */
	protected function status_transition() {
		$status_transition = $this->status_transition;

		// Reset status transition variable.
		$this->status_transition = false;

		if ( ! $status_transition ) {
			return;
		}

		try {
			do_action( 'masteriyo_user_course_status_' . $status_transition['to'], $this->get_id(), $this );

			if ( ! empty( $status_transition['from'] ) ) {
				do_action( 'masteriyo_user_course_status_' . $status_transition['from'] . '_to_' . $status_transition['to'], $this->get_id(), $this );
				do_action( 'masteriyo_user_course_status_changed', $this->get_id(), $status_transition['from'], $status_transition['to'], $this );
			}
		} catch ( \Exception $e ) { // phpcs:ignore
			// TODO Log the message.
		}
	}

	/**
	 * Get all valid statuses for this user course
	 *
	 * @since 0.1.0
	 * @return array Internal status keys e.g. ('active', 'enrolled )
	 */
	protected function get_valid_statuses() {
		return array_keys( masteriyo_get_user_course_statuses() );
	}
}
