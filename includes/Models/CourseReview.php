<?php
/**
 * Comment model.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Models;
 */

namespace ThemeGrill\Masteriyo\Models;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Repository\CourseReviewRepository;
use ThemeGrill\Masteriyo\Helper\Utils;
use ThemeGrill\Masteriyo\Cache\CacheInterface;

defined( 'ABSPATH' ) || exit;

/**
 * CourseReview Model.
 *
 * @since 0.1.0
 */
class CourseReview extends Model {

	/**
	 * This is the name of this object type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $object_type = 'course_review';

	/**
	 * Cache group.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $cache_group = 'course_reviews';


	/**
	 * Stores course review data.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $data = array(
		'course_id'    => 0,
		'author_name'  => '',
		'author_email' => '',
		'author_url'   => '',
		'ip_address'   => '',
		'date_created' => null,
		'content'      => '',
		'karma'        => 0,
		'status'       => 'approve',
		'agent'        => '',
		'type'         => 'masteriyo_review',
		'parent'       => 0,
		'user_id'      => 0,
	);

	/**
	 * Get the course review if ID.
	 *
	 * @since 0.1.0
	 *
	 * @param CourseReviewRepository $course_review_repository Course Review Repository.
	 */
	public function __construct( CourseReviewRepository $course_review_repository ) {
		$this->repository = $course_review_repository;
	}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get course_id.
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
	 * Get author_name.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_author_name( $context = 'view' ) {
		return $this->get_prop( 'author_name', $context );
	}

	/**
	 * Get author_email.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_author_email( $context = 'view' ) {
		return $this->get_prop( 'author_email', $context );
	}

	/**
	 * Get author_url.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_author_url( $context = 'view' ) {
		return $this->get_prop( 'author_url', $context );
	}

	/**
	 * Get ip_address.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_ip_address( $context = 'view' ) {
		return $this->get_prop( 'ip_address', $context );
	}

	/**
	 * Get date_created.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string object if the date is set or null if there is no date.
	 */
	public function get_date_created( $context = 'view' ) {
		return $this->get_prop( 'date_created', $context );
	}

	/**
	 * Get content.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_content( $context = 'view' ) {
		return $this->get_prop( 'content', $context );
	}

	/**
	 * Get karma.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int
	 */
	public function get_karma( $context = 'view' ) {
		return $this->get_prop( 'karma', $context );
	}

	/**
	 * Get status.
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
	 * Get agent.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_agent( $context = 'view' ) {
		return $this->get_prop( 'agent', $context );
	}

	/**
	 * Get type.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_type( $context = 'view' ) {
		return $this->get_prop( 'type', $context );
	}

	/**
	 * Get parent.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int
	 */
	public function get_parent( $context = 'view' ) {
		return $this->get_prop( 'parent', $context );
	}

	/**
	 * Get user_id.
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

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set course_id.
	 *
	 * @since 0.1.0
	 *
	 * @param int $course_id course_id.
	 */
	public function set_course_id( $course_id ) {
		$this->set_prop( 'course_id', absint( $course_id ) );
	}

	/**
	 * Set author_name.
	 *
	 * @since 0.1.0
	 *
	 * @param string $author_name Comment author name.
	 */
	public function set_author_name( $author_name ) {
		$this->set_prop( 'author_name', $author_name );
	}

	/**
	 * Set author_email.
	 *
	 * @since 0.1.0
	 *
	 * @param string $author_email Comment author email.
	 */
	public function set_author_email( $author_email ) {
		$this->set_prop( 'author_email', $author_email );
	}

	/**
	 * Set author_url.
	 *
	 * @since 0.1.0
	 *
	 * @param string $author_url Comment author url.
	 */
	public function set_author_url( $author_url ) {
		$this->set_prop( 'author_url', $author_url );
	}

	/**
	 * Set ip_address.
	 *
	 * @since 0.1.0
	 *
	 * @param string $ip_address Comment author IP.
	 */
	public function set_ip_address( $ip_address ) {
		$this->set_prop( 'ip_address', $ip_address );
	}

	/**
	 * Set date_created.
	 *
	 * @since 0.1.0
	 *
	 * @param string $date_created Comment date_created.
	 */
	public function set_date_created( $date_created ) {
		$this->set_prop( 'date_created', $date_created );
	}

	/**
	 * Set content.
	 *
	 * @since 0.1.0
	 *
	 * @param string $content Comment content.
	 */
	public function set_content( $content ) {
		$this->set_prop( 'content', $content );
	}

	/**
	 * Set karma.
	 *
	 * @since 0.1.0
	 *
	 * @param int $karma Comment karma.
	 */
	public function set_karma( $karma ) {
		$this->set_prop( 'karma', absint( $karma ) );
	}

	/**
	 * Set status.
	 *
	 * @since 0.1.0
	 *
	 * @param string $status Comment status.
	 */
	public function set_status( $status ) {
		$this->set_prop( 'status', $status );
	}

	/**
	 * Set agent.
	 *
	 * @since 0.1.0
	 *
	 * @param string $agent Comment Agent.
	 */
	public function set_agent( $agent ) {
		$this->set_prop( 'agent', $agent );
	}

	/**
	 * Set type.
	 *
	 * @since 0.1.0
	 *
	 * @param string $type Comment Type.
	 */
	public function set_type( $type ) {
		$this->set_prop( 'type', $type );
	}

	/**
	 * Set parent.
	 *
	 * @since 0.1.0
	 *
	 * @param int $parent Comment Parent.
	 */
	public function set_parent( $parent ) {
		$this->set_prop( 'parent', absint( $parent ) );
	}

	/**
	 * Set user_id.
	 *
	 * @since 0.1.0
	 *
	 * @param int $user_id User ID.
	 */
	public function set_user_id( $user_id ) {
		$this->set_prop( 'user_id', absint( $user_id ) );
	}
}
