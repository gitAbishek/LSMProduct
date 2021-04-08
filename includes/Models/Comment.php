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
use ThemeGrill\Masteriyo\Repository\CommentRepository;
use ThemeGrill\Masteriyo\Helper\Utils;
use ThemeGrill\Masteriyo\Cache\CacheInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Comment Model.
 *
 * @since 0.1.0
 */
class Comment extends Model {

	/**
	 * This is the name of this object type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $object_type = 'comment';

	/**
	 * Post type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $post_type = 'comment';

	/**
	 * Cache group.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $cache_group = 'comments';

	
	/**
	 * Stores comment data.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $data = array(
		'comment_author'        => '',
		'comment_author_email'  => '',
		'comment_author_IP'     => '',
		'comment_date'          => null,
		'comment_date_gmt'      => null,
		'comment_content'       => '',
		'comment_karma'         => 0,
		'comment_approved'      => 1,
		'comment_agent'         => '',
		'comment_type'          => 'masteriyo_review',
		'comment_parent'        => 0,
		'user_id'               => 0,
	);

	/**
	 * Get the comment if ID.
	 *
	 * @since 0.1.0
	 *
	 * @param CommentRepository $comment_repository Comment Repository,
	 */
	public function __construct( CommentRepository $comment_repository ) {
		$this->repository = $comment_repository;
	}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get comment_author.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_comment_author( $context = 'view' ) {
		return $this->get_prop( 'comment_author', $context );
	}

	/**
	 * Get comment_author_email.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_comment_author_email( $context = 'view' ) {
		return $this->get_prop( 'comment_author_email', $context );
	}

	/**
	 * Get comment_author_IP.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_comment_author_IP( $context = 'view' ) {
		return $this->get_prop( 'comment_author_IP', $context );
	}

	/**
	 * Get comment_date.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_comment_date( $context = 'view' ) {
		return $this->get_prop( 'comment_date', $context );
	}

	/**
	 * Get comment_date_gmt.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_comment_date_gmt( $context = 'view' ) {
		return $this->get_prop( 'comment_date_gmt', $context );
	}

	/**
	 * Get comment_content.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_comment_content( $context = 'view' ) {
		return $this->get_prop( 'comment_content', $context );
	}

	/**
	 * Get comment_karma.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_comment_karma( $context = 'view' ) {
		return $this->get_prop( 'comment_karma', $context );
	}

	/**
	 * Get comment_approved.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_comment_approved( $context = 'view' ) {
		return $this->get_prop( 'comment_approved', $context );
	}

	/**
	 * Get comment_agent.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_comment_agent( $context = 'view' ) {
		return $this->get_prop( 'comment_agent', $context );
	}

	/**
	 * Get comment_type.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_comment_type( $context = 'view' ) {
		return $this->get_prop( 'comment_type', $context );
	}

	/**
	 * Get comment_parent.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_comment_parent( $context = 'view' ) {
		return $this->get_prop( 'comment_parent', $context );
	}

	/**
	 * Get user_id.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
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
	 * Set comment_author.
	 *
	 * @since 0.1.0
	 *
	 * @param string $comment_author Comment author.
	 */
	public function set_comment_author( $comment_author ) {
		$this->set_prop( 'comment_author', $comment_author );
	}

	/**
	 * Set comment_author_email.
	 *
	 * @since 0.1.0
	 *
	 * @param string $comment_author_email Comment author Email.
	 */
	public function set_comment_author_email( $comment_author_email ) {
		$this->set_prop( 'comment_author_email', $comment_author_email );
	}

	/**
	 * Set comment_author_url.
	 *
	 * @since 0.1.0
	 *
	 * @param string $comment_author_url Comment author URL.
	 */
	public function set_comment_author_url( $comment_author_url ) {
		$this->set_prop( 'comment_author_url', $comment_author_url );
	}

	/**
	 * Set comment_author_IP.
	 *
	 * @since 0.1.0
	 *
	 * @param string $comment_author_IP Comment author IP.
	 */
	public function set_comment_author_IP( $comment_author_IP ) {
		$this->set_prop( 'comment_author_IP', $comment_author_IP );
	}

	/**
	 * Set comment_date.
	 *
	 * @since 0.1.0
	 *
	 * @param string $comment_date Comment date.
	 */
	public function set_comment_date( $comment_date ) {
		$this->set_prop( 'comment_date', $comment_date );
	}

	/**
	 * Set comment_date_gmt.
	 *
	 * @since 0.1.0
	 *
	 * @param string $comment_date_gmt Comment date gmt.
	 */
	public function set_comment_date_gmt( $comment_date_gmt ) {
		$this->set_prop( 'comment_date_gmt', $comment_date_gmt );
	}

	/**
	 * Set comment_content.
	 *
	 * @since 0.1.0
	 *
	 * @param string $comment_content Comment date.
	 */
	public function set_comment_content( $comment_content ) {
		$this->set_prop( 'comment_content', $comment_content );
	}

	/**
	 * Set comment_karma.
	 *
	 * @since 0.1.0
	 *
	 * @param string $comment_karma Comment karma.
	 */
	public function set_comment_karma( $comment_karma ) {
		$this->set_prop( 'comment_karma', $comment_karma );
	}

	/**
	 * Set comment_approved.
	 *
	 * @since 0.1.0
	 *
	 * @param string $comment_approved Comment Approved.
	 */
	public function set_comment_approved( $comment_approved ) {
		$this->set_prop( 'comment_approved', $comment_approved );
	}

	/**
	 * Set comment_agent.
	 *
	 * @since 0.1.0
	 *
	 * @param string $comment_agent Comment Agent.
	 */
	public function set_comment_agent( $comment_agent ) {
		$this->set_prop( 'comment_agent', $comment_agent );
	}

	/**
	 * Set comment_type.
	 *
	 * @since 0.1.0
	 *
	 * @param string $comment_type Comment Type.
	 */
	public function set_comment_type( $comment_type ) {
		$this->set_prop( 'comment_type', $comment_type );
	}

	/**
	 * Set comment_parent.
	 *
	 * @since 0.1.0
	 *
	 * @param string $comment_parent Comment Parent.
	 */
	public function set_comment_parent( $comment_parent ) {
		$this->set_prop( 'comment_parent', $comment_parent );
	}

	/**
	 * Set user_id.
	 *
	 * @since 0.1.0
	 *
	 * @param string $user_id User ID.
	 */
	public function set_user_id( $user_id ) {
		$this->set_prop( 'user_id', $user_id );
	}
}