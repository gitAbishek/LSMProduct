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
use ThemeGrill\Masteriyo\Repository\CourseQuestionAnswerRepository;

defined( 'ABSPATH' ) || exit;

/**
 * CourseQuestionAnswer Model.
 *
 * @since 0.1.0
 */
class CourseQuestionAnswer extends Model {

	/**
	 * This is the name of this object type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $object_type = 'mto_course_qa';

	/**
	 * Cache group.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $cache_group = 'mto_course_qas';


	/**
	 * Stores course question answer data.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $data = array(
		'course_id'  => 0,
		'user_name'  => '',
		'user_email' => '',
		'user_url'   => '',
		'ip_address' => '',
		'created_at' => null,
		'title'      => '',
		'content'    => '',
		'status'     => 'approve',
		'agent'      => '',
		'parent'     => 0,
		'user_id'    => 0,
	);

	/**
	 * Get the course question-answer if ID.
	 *
	 * @since 0.1.0
	 *
	 * @param CourseQuestionAnswerRepository $mto_course_qa_repository Course question answer Repository.
	 */
	public function __construct( CourseQuestionAnswerRepository $mto_course_qa_repository ) {
		$this->repository = $mto_course_qa_repository;
	}

	/**
	 * Get user.
	 *
	 * @since  0.1.0
	 *
	 * @return User
	 */
	public function get_user() {
		return masteriyo_get_user( $this->get_user_id() );
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
	 * Get user_name.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_user_name( $context = 'view' ) {
		return $this->get_prop( 'user_name', $context );
	}

	/**
	 * Get user_email.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_user_email( $context = 'view' ) {
		return $this->get_prop( 'user_email', $context );
	}

	/**
	 * Get user_url.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_user_url( $context = 'view' ) {
		return $this->get_prop( 'user_url', $context );
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
	 * Get created_at.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string object if the date is set or null if there is no date.
	 */
	public function get_created_at( $context = 'view' ) {
		return $this->get_prop( 'created_at', $context );
	}

	/**
	 * Get title.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_title( $context = 'view' ) {
		return $this->get_prop( 'title', $context );
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
		return 'mto_course_qa';
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
	 * Check if this is an answer.
	 *
	 * @since 0.1.0
	 *
	 * @return boolean
	 */
	public function is_answer() {
		return absint( $this->get_parent( 'edit' ) ) > 0;
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
	 * Set user_name.
	 *
	 * @since 0.1.0
	 *
	 * @param string $user_name Comment user name.
	 */
	public function set_user_name( $user_name ) {
		$this->set_prop( 'user_name', $user_name );
	}

	/**
	 * Set user_email.
	 *
	 * @since 0.1.0
	 *
	 * @param string $user_email Comment user email.
	 */
	public function set_user_email( $user_email ) {
		$this->set_prop( 'user_email', $user_email );
	}

	/**
	 * Set user_url.
	 *
	 * @since 0.1.0
	 *
	 * @param string $user_url Comment user url.
	 */
	public function set_user_url( $user_url ) {
		$this->set_prop( 'user_url', $user_url );
	}

	/**
	 * Set ip_address.
	 *
	 * @since 0.1.0
	 *
	 * @param string $ip_address Comment user IP.
	 */
	public function set_ip_address( $ip_address ) {
		$this->set_prop( 'ip_address', $ip_address );
	}

	/**
	 * Set created_at.
	 *
	 * @since 0.1.0
	 *
	 * @param string $created_at Comment created_at.
	 */
	public function set_created_at( $created_at ) {
		$this->set_prop( 'created_at', $created_at );
	}

	/**
	 * Set title.
	 *
	 * @since 0.1.0
	 *
	 * @param string $title Comment title.
	 */
	public function set_title( $title ) {
		$this->set_prop( 'title', $title );
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
