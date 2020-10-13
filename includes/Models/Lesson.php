<?php
/**
 * Lesson model.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Models;
 */

namespace ThemeGrill\Masteriyo\Models;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Repository\LessonRepository;
use ThemeGrill\Masteriyo\Helper\Utils;

defined( 'ABSPATH' ) || exit;

/**
 * Lesson model (post type).
 *
 * @since 0.1.0
 */
class Lesson extends Model {

	/**
	 * This is the name of this object type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $object_type = 'lesson';

	/**
	 * Post type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $post_type = 'lesson';

	/**
	 * Cache group.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $cache_group = 'lessons';

	/**
	 * Stores lesson data.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $data = array(
		'name'               => '',
		'slug'               => '',
		'date_created'       => null,
		'date_modified'      => null,
		'status'             => false,
		'menu_order'         => 0,
		'catalog_visibility' => 'visibile',
		'description'        => '',
		'short_description'  => '',
		'post_password'      => '',
		'parent_id'          => 0,
		'reviews_allowed'    => true,
		'category_ids'       => array(),
		'tag_ids'            => array(),
		'featured_image'     => ''
	);

	/**
	 * Get the lesson if ID
	 *
	 * @since 0.1.0
	 *
	 * @param LessonRepository $lesson_repository Lesson Repository,
	 */
	public function __construct( LessonRepository $lesson_repository ) {
		$this->repository = $lesson_repository;
	}

	/*
	|--------------------------------------------------------------------------
	| Non-CRUD Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the product's title. For products this is the product name.
	 *
	 * @return string
	 */
	public function get_title() {
		return apply_filters( 'masteriyo_lesson_title', $this->get_name(), $this );
	}

	/**
	 * Product permalink.
	 *
	 * @return string
	 */
	public function get_permalink() {
		return get_permalink( $this->get_id() );
	}

	/**
	 * Returns the children IDs if applicable. Overridden by child classes.
	 *
	 * @return array of IDs
	 */
	public function get_children() {
		return array();
	}


	/*
	|--------------------------------------------------------------------------
	| CRUD Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get lesson name.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_name( $context = 'view' ) {
		return $this->get_prop( 'name', $context );
	}

	/**
	 * Get lesson slug.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_slug( $context = 'view' ) {
		return $this->get_prop( 'slug', $context );
	}

	/**
	 * Get lesson created date.
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
	 * Get lesson modified date.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string object if the date is set or null if there is no date.
	 */
	public function get_date_modified( $context = 'view' ) {
		return $this->get_prop( 'date_modified', $context );
	}

	/**
	 * Get lesson status.
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
	 * Get catalog visibility.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_catalog_visibility( $context = 'view' ) {
		return $this->get_prop( 'catalog_visibility', $context );
	}

	/**
	 * Get lesson description.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_description( $context = 'view' ) {
		return $this->get_prop( 'description', $context );
	}

	/**
	 * Get lesson short description.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_short_description( $context = 'view' ) {
		return $this->get_prop( 'short_description', $context );
	}

	/**
	 * Returns the lesson's password.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string price
	 */
	public function get_post_password( $context = 'view' ) {
		return $this->get_prop( 'post_password', $context );
	}

	/**
	 * Returns whether review is allowed or not..
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string price
	 *
	 */
	public function get_reviews_allowed( $context = 'view' ) {
		return $this->get_prop( 'reviews_allowed', $context );
	}

	/**
	 * Returns lesson parent id.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string price
	 */
	public function get_parent_id( $context = 'view' ) {
		return $this->get_prop( 'parent_id', $context );
	}

	/**
	 * Returns lesson menu order.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string price
	 */
	public function get_menu_order( $context = 'view' ) {
		return $this->get_prop( 'menu_order', $context );
	}

	/**
	 * Returns lesson category ids.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string price
	 */
	public function get_category_ids( $context = 'view' ) {
		return $this->get_prop( 'category_ids', $context );
	}

	/**
	 * Returns lesson tag ids.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 *
	 * @return string price
	 */
	public function get_tag_ids( $context = 'view' ) {
		return $this->get_prop( 'tag_ids', $context );
	}

	/**
	 * Returns lesson difficulty ids.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string price
	 */
	public function get_difficulty_ids( $context = 'view' ) {
		return $this->get_prop( 'difficulty_ids', $context );
	}

	/**
	 * Returns lesson tag ids.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string price
	 */
	public function get_featured_image( $context = 'view' ) {
		return $this->get_prop( 'featured_image', $context );
	}

	/**
	 * Check whether the lesson is featured or not.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return bool
	 */
	public function get_featured( $context = 'view' ) {
		return  $this->get_prop( 'featured', $context );
	}

	/**
	 * Set lesson name.
	 *
	 * @since 0.1.0
	 *
	 * @param string $name lesson name.
	 */
	public function set_name( $name ) {
		$this->set_prop( 'name', $name );
	}

	/**
	 * Set lesson slug.
	 *
	 * @since 0.1.0
	 *
	 * @param string $slug lesson slug.
	 */
	public function set_slug( $slug ) {
		$this->set_prop( 'slug', $slug );
	}

	/**
	 * Set lesson created date.
	 *
	 * @since 0.1.0
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_date_created( $date = null ) {
		$this->set_prop( 'date_created', $date );
	}

	/**
	 * Set lesson modified date.
	 *
	 * @since 0.1.0
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_date_modified( $date = null ) {
		$this->set_prop( 'date_modified', $date );
	}

	/**
	 * Set lesson status.
	 *
	 * @since 0.1.0
	 *
	 * @param string $status lesson status.
	 */
	public function set_status( $status ) {
		$this->set_prop( 'status', $status );
	}

	/**
	 * Set lesson description.
	 *
	 * @since 0.1.0
	 *
	 * @param string $description Lesson description.
	 */
	public function set_description( $description ) {
		$this->set_prop( 'description', $description );
	}

	/**
	 * Set lesson short description.
	 *
	 * @since 0.1.0
	 *
	 * @param string $short_description Lesson short description.
	 */
	public function set_short_description( $short_description ) {
		$this->set_prop( 'short_description', $short_description );
	}

	/**
	 * Set the lesson's password.
	 *
	 * @since 0.1.0
	 *
	 * @param string $password Password.
	 */
	public function set_post_password( $password ) {
		$this->set_prop( 'post_password',  $password );
	}

	/**
	 * Set the lesson's review status.
	 *
	 * @since 0.1.0
	 *
	 * @param string $reviews_allowed Reviews allowed.( Value can be 'open' or 'closed')
	 */
	public function set_reviews_allowed( $reviews_allowed ) {
		$this->set_prop( 'reviews_allowed',  $reviews_allowed );
	}

	/**
	 * Set the lesson parent id.
	 *
	 * @since 0.1.0
	 *
	 * @param string $parent Parent id.
	 */
	public function set_parent_id( $parent ) {
		$this->set_prop( 'parent_id',  $parent );
	}

	/**
	 * Set the lesson menu order.
	 *
	 * @since 0.1.0
	 *
	 * @param string $menu_order Menu order id.
	 */
	public function set_menu_order( $menu_order ) {
		$this->set_prop( 'menu_order',  $menu_order );
	}

	/**
	 * Set the lesson category ids.
	 *
	 * @since 0.1.0
	 *
	 * @param array $category_ids Category ids.
	 */
	public function set_category_ids( $category_ids ) {
		$this->set_prop( 'category_ids', array_unique( array_map( 'intval', $category_ids ) ) );
	}

	/**
	 * Set the lesson tag ids.
	 *
	 * @since 0.1.0
	 *
	 * @param array $tag_ids Tag ids.
	 */
	public function set_tag_ids( $tag_ids ) {
		$this->set_prop( 'tag_ids', array_unique( array_map( 'intval',  $tag_ids ) ) );
	}

	/**
	 * Set the featured image, in other words thumbnail post id.
	 *
	 * @since 0.1.0
	 *
	 * @param int $featured_image Featured image id.
	 */
	public function set_featured_image( $featured_image ) {
		$this->set_prop( 'featured_image', absint( $featured_image ) ) ;
	}

	/**
	 * Set the featured.
	 *
	 * @since 0.1.0
	 *
	 * @param bool $featured Featured.
	 */
	public function set_featured( $featured ) {
		$this->set_prop( 'featured', Utils::string_to_bool( $featured ) );
	}
}
