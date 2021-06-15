<?php
/**
 * Course model.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Models;
 */

namespace ThemeGrill\Masteriyo\Models;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Repository\RepositoryInterface;
use ThemeGrill\Masteriyo\Cache\CacheInterface;

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
	 * Get course object.
	 *
	 * @since 0.1.0
	 *
	 * @return ThemeGrill\Masteriyo\Models\Course|NULL
	 */
	public function get_course() {
		return masteriyo_get_course( $this->get_course_id() );
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
	 * Set user's course status.
	 *
	 * @since 0.1.0
	 *
	 * @param int $value User's course status.
	 */
	public function set_status( $value ) {
		$this->set_prop( 'status', $value );
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

}
