<?php
/**
 * Faq model (comment type).
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Models;
 */

namespace ThemeGrill\Masteriyo\Models;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Repository\FaqRepository;

defined( 'ABSPATH' ) || exit;

/**
 * Faq model (post type).
 *
 * @since 0.1.0
 */
class Faq extends Model {

	/**
	 * This is the title of this object type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $object_type = 'faq';

	/**
	 * Comment type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $comment_type = 'mto-faq';

	/**
	 * Cache group.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $cache_group = 'faq';

	/**
	 * Stores Faq data.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $data = array(
		'title'      => '',
		'content'    => '',
		'course_id'  => 0,
		'user_id'    => 0,
		'user_name'  => '',
		'user_email' => '',
		'user_url'   => '',
		'user_ip'    => '',
		'user_agent' => '',
		'sort_order' => 0,
		'status'     => 'approve',
		'created_at' => null,
	);

	/**
	 * Get the faq if ID
	 *
	 * @since 0.1.0
	 *
	 * @param FaqRepository $faq_repository Faq Repository.
	 */
	public function __construct( FaqRepository $faq_repository ) {
		$this->repository = $faq_repository;
	}

	/*
	|--------------------------------------------------------------------------
	| Non-CRUD Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get comment type.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_type() {
		return $this->comment_type;
	}


	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get Faq title.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_title( $context = 'view' ) {
		return apply_filters( 'masteriyo_faq_title', $this->get_prop( 'title', $context ), $this );
	}

	/**
	 * Get faq content.
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
	 * Returns faq parent id.
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
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int
	 */
	public function get_user_id( $context = 'view' ) {
		return $this->get_prop( 'user_id', $context );
	}

	/**
	 * Get user name.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_user_name( $context = 'view' ) {
		return $this->get_prop( 'user_name', $context );
	}

	/**
	 * Get user email.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_user_email( $context = 'view' ) {
		return $this->get_prop( 'user_email', $context );
	}

	/**
	 * Get user url.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_user_url( $context = 'view' ) {
		return $this->get_prop( 'user_url', $context );
	}

	/**
	 * Get user ip.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_user_ip( $context = 'view' ) {
		return $this->get_prop( 'user_ip', $context );
	}

	/**
	 * Get user agent.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_user_agent( $context = 'view' ) {
		return $this->get_prop( 'user_agent', $context );
	}

	/**
	 * Get faq status.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_status( $context = 'view' ) {
		return $this->get_prop( 'status', $context );
	}

	/**
	 * Get faq created date.
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
	 * Returns faq menu order.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_sort_order( $context = 'view' ) {
		return $this->get_prop( 'sort_order', $context );
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set faq title.
	 *
	 * @since 0.1.0
	 *
	 * @param string $title faq title.
	 */
	public function set_title( $title ) {
		$this->set_prop( 'title', $title );
	}

	/**
	 * Set faq content.
	 *
	 * @since 0.1.0
	 *
	 * @param string $content Faq content.
	 */
	public function set_content( $content ) {
		$this->set_prop( 'content', $content );
	}

	/**
	 * Set the faq course ID.
	 *
	 * @since 0.1.0
	 *
	 * @param int $value Course ID.
	 */
	public function set_course_id( $value ) {
		$this->set_prop( 'course_id', absint( $value ) );
	}

	/**
	 * Set the faq user ID.
	 *
	 * @since 0.1.0
	 *
	 * @param int $value User ID.
	 */
	public function set_user_id( $value ) {
		$this->set_prop( 'user_id', absint( $value ) );
	}

	/**
	 * Set user's email.
	 *
	 * @since 0.1.0
	 *
	 * @param string $value User's email.
	 */
	public function set_user_email( $value ) {
		$this->set_prop( 'user_email', $value );
	}

	/**
	 * Set user's url.
	 *
	 * @since 0.1.0
	 *
	 * @param string $value User's url.
	 */
	public function set_user_url( $value ) {
		$this->set_prop( 'user_url', $value );
	}

	/**
	 * Set user's ip.
	 *
	 * @since 0.1.0
	 *
	 * @param string $value User's ip.
	 */
	public function set_user_ip( $value ) {
		$this->set_prop( 'user_ip', $value );
	}

	/**
	 * Set user's agent.
	 *
	 * @since 0.1.0
	 *
	 * @param string $value User's agent.
	 */
	public function set_user_agent( $value ) {
		$this->set_prop( 'user_agent', $value );
	}

	/**
	 * Set the faq menu order.
	 *
	 * @since 0.1.0
	 *
	 * @param int $sort_order Menu order id.
	 */
	public function set_sort_order( $sort_order ) {
		$this->set_prop( 'sort_order', absint( $sort_order ) );
	}

	/**
	 * Set the faq status.
	 *
	 * @since 0.1.0
	 *
	 * @param int $status Faq status.
	 */
	public function set_status( $status ) {
		$this->set_prop( 'status', absint( $status ) );
	}

	/**
	 * Set faq created date.
	 *
	 * @since 0.1.0
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_created_at( $date = null ) {
		$this->set_prop( 'created_at', $date );
	}
}
