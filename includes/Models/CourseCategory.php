<?php
/**
 * Course_Category model.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Models;
 */

namespace ThemeGrill\Masteriyo\Models;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Repository\CourseCategoryRepository;
use ThemeGrill\Masteriyo\Helper\Utils;
use ThemeGrill\Masteriyo\Cache\CacheInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Course_Category model (post type).
 *
 * @since 0.1.0
 */
class CourseCategory extends Model {

	/**
	 * This is the name of this object type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $object_type = 'course_cat';

	/**
	 * Post type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $taxonomy = 'course_cat';

	/**
	 * Cache group.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $cache_group = 'course_cat';

	/**
	 * Stores course_category data.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $data = array(
		'name'             => '',
		'slug'             => '',
		'description'      => '',
		'term_group'       => 0,
		'term_taxonomy_id' => 0,
		'taxonomy'         => '',
		'parent_id'        => 0,
		'count'            => 0,
		'term_order'       => 0,
		'display'          => 'default',
	);

	/**
	 * Get the course category if ID.
	 *
	 * @since 0.1.0
	 *
	 * @param CourseCategoryRepository $course_category_repository Course Category Repository.
	 */
	public function __construct( CourseCategoryRepository $course_category_repository ) {
		$this->repository = $course_category_repository;
	}

	/*
	|--------------------------------------------------------------------------
	| Non-CRUD Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the course category's title. For course categorys this is the course category name.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_title() {
		return apply_filters( 'masteriyo_course_title', $this->get_name(), $this );
	}

	/**
	 * Course category permalink.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_permalink() {
		return get_term_link( $this->get_id() );
	}

	/**
	 * Returns the children IDs if applicable. Overridden by child classes.
	 *
	 * @since 0.1.0
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
	 * Get course category name.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_name( $context = 'view' ) {
		return $this->get_prop( 'name', $context );
	}

	/**
	 * Get course category slug.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_slug( $context = 'view' ) {
		return $this->get_prop( 'slug', $context );
	}

	/**
	 * Get course category description.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_description( $context = 'view' ) {
		return $this->get_prop( 'description', $context );
	}

	/**
	 * Get course category term group.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_term_group( $context = 'view' ) {
		return $this->get_prop( 'term_group', $context );
	}

	/**
	 * Get course category term taxonomy id.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_term_taxonomy_id( $context = 'view' ) {
		return $this->get_prop( 'term_taxonomy_id', $context );
	}

	/**
	 * Get course category taxonomy.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_taxonomy( $context = 'view' ) {
		return $this->get_prop( 'taxonomy', $context );
	}

	/**
	 * Get course category parent id.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int
	 */
	public function get_parent_id( $context = 'view' ) {
		return $this->get_prop( 'parent_id', $context );
	}

	/**
	 * Get number of course for the category.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_count( $context = 'view' ) {
		return $this->get_prop( 'count', $context );
	}

	/**
	 * Get term order.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_term_order( $context = 'view' ) {
		return $this->get_prop( 'term_order', $context );
	}

	/**
	 * Get course category archive display type.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_display( $context = 'view' ) {
		return $this->get_prop( 'display', $context );
	}

	/*
	|--------------------------------------------------------------------------
	| CRUD Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set course category name.
	 *
	 * @since 0.1.0
	 *
	 * @param string $name Course category name.
	 */
	public function set_name( $name ) {
		$this->set_prop( 'name', $name );
	}

	/**
	 * Set course category slug.
	 *
	 * @since 0.1.0
	 *
	 * @param string $slug Course cateogy slug.
	 */
	public function set_slug( $slug ) {
		$this->set_prop( 'slug', $slug );
	}

	/**
	 * Set course category description.
	 *
	 * @since 0.1.0
	 *
	 * @param string $description Course category description.
	 */
	public function set_description( $description ) {
		$this->set_prop( 'description', $description );
	}

	/**
	 * Set course category parent id.
	 *
	 * @since 0.1.0
	 *
	 * @param int $parent_id Course category parent id.
	 */
	public function set_parent_id( $parent_id ) {
		$this->set_prop( 'parent_id', absint( $parent_id ) );
	}

	/**
	 * Set course category term group.
	 *
	 * @since 0.1.0
	 *
	 * @param int $term_group Course category term group.
	 */
	public function set_term_group( $term_group ) {
		$this->set_prop( 'term_group', absint( $term_group ) );
	}

	/**
	 * Set course category term taxonomy id.
	 *
	 * @since 0.1.0
	 *
	 * @param int $term_taxonomy_id Course category term taxonomy id.
	 */
	public function set_term_taxonomy_id( $term_taxonomy_id ) {
		$this->set_prop( 'term_taxonomy_id', absint( $term_taxonomy_id ) );
	}

	/**
	 * Set course category taxonomy.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $taxonomy Course category taxonomy.
	 */
	public function set_taxonomy( $taxonomy ) {
		$this->set_prop( 'taxonomy', $taxonomy );
	}

	/**
	 * Set number of course for the category.
	 *
	 * @since 0.1.0
	 *
	 * @param int $count Number of posts for the course category term.
	 */
	public function set_count( $count ) {
		$this->set_prop( 'count', absint( $count ) );
	}

	/**
	 * Set term order.
	 *
	 * @since 0.1.0
	 *
	 * @param int $term_order Course category term order.
	 */
	public function set_term_order( $term_order ) {
		$this->set_prop( 'term_order', absint( $term_order ) );
	}

	/**
	 * Set category display type.
	 *
	 * @since 0.1.0
	 *
	 * @param string $display Category archive display type.
	 */
	public function set_display( $display ) {
		$this->set_prop( 'display', $display );
	}
}
