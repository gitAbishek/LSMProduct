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
use ThemeGrill\Masteriyo\Helper\Utils;
use ThemeGrill\Masteriyo\Helper\Format;
use ThemeGrill\Masteriyo\Cache\CacheInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Course model (post type).
 *
 * @since 0.1.0
 */
class Course extends Model {

	/**
	 * This is the name of this object type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $object_type = 'course';

	/**
	 * Post type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $post_type = 'course';

	/**
	 * Cache group.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $cache_group = 'courses';

	/**
	 * Stores course data.
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
		'featured'           => false,
		'catalog_visibility' => 'visible',
		'description'        => '',
		'short_description'  => '',
		'post_password'      => '',
		'author_id'          => 0,
		'parent_id'          => 0,
		'reviews_allowed'    => true,
		'date_on_sale_from'  => null,
		'date_on_sale_to'    => null,
		'price'              => '',
		'regular_price'      => '',
		'sale_price'         => '',
		'category_ids'       => array(),
		'tag_ids'            => array(),
		'difficulty_id'      => 0,
		'featured_image'     => '',
		'rating_counts'      => array(),
		'average_rating'     => 0,
		'review_count'       => 0,
	);

	/**
	 * Get the course if ID
	 *
	 * @since 0.1.0
	 *
	 * @param RepositoryInterface $course_repository Course Repository,
	 */
	public function __construct( RepositoryInterface $course_repository ) {
		$this->repository = $course_repository;
	}

	/**
	 * Get featured image URL.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_featured_image_url() {
		return wp_get_attachment_url( $this->get_featured_image() );
	}

	/**
	 * Get category list (CourseCategory objects).
	 *
	 * @since 0.1.0
	 *
	 * @return array[CourseCategory]
	 */
	public function get_categories() {
		$cat_ids = $this->get_category_ids();
		$categories = array();
		$store = masteriyo( 'course_cat.store' );

		foreach( $cat_ids as $cat_id ) {
			$cat_obj = masteriyo( 'course_cat' );
			$cat_obj->set_id( $cat_id );
			$store->read( $cat_obj );
			$categories[] = apply_filters( 'masteriyo_get_course_cat', $cat_obj, $cat_id );
		}

		return $categories;
	}

	/*
	|--------------------------------------------------------------------------
	| Non-CRUD Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the course's title. For courses this is the course name.
	 *
	 * @return string
	 */
	public function get_title() {
		return apply_filters( 'masteriyo_course_title', $this->get_name(), $this );
	}

	/**
	 * Course permalink.
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

	/**
	 * Get course name.
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
	 * Get course slug.
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
	 * Get course created date.
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
	 * Get course modified date.
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
	 * Get course status.
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
	 * Get course description.
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
	 * Get course short description.
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
	 * Returns the course's password.
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
	 * Get date on sale from.
	 *
	 * @since  0.1.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string|NULL object if the date is set or null if there is no date.
	 */
	public function get_date_on_sale_from( $context = 'view' ) {
		return $this->get_prop( 'date_on_sale_from', $context );
	}

	/**
	 * Get date on sale to.
	 *
	 * @since  0.1.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return MASTERIYO_DateTime|NULL object if the date is set or null if there is no date.
	 */
	public function get_date_on_sale_to( $context = 'view' ) {
		return $this->get_prop( 'date_on_sale_to', $context );
	}

	/**
	 * Returns course author id.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string price
	 */
	public function get_author_id( $context = 'view' ) {
		return $this->get_prop( 'author_id', $context );
	}

	/**
	 * Returns course parent id.
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
	 * Returns course menu order.
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
	 * Returns course's active price.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string Course's active price
	 */
	public function get_price( $context = 'view' ) {
		return $this->get_prop( 'price', $context );
	}

	/**
	 * Returns course's regular price.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string Course's regular price
	 */
	public function get_regular_price( $context = 'view' ) {
		return $this->get_prop( 'regular_price', $context );
	}

	/**
	 * Returns course's sale price.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string Course's sale price
	 */
	public function get_sale_price( $context = 'view' ) {
		return $this->get_prop( 'sale_price', $context );
	}

	/**
	 * Returns course category ids.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return array[integer] Category IDs.
	 */
	public function get_category_ids( $context = 'view' ) {
		return $this->get_prop( 'category_ids', $context );
	}

	/**
	 * Returns course tag ids.
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
	 * Returns course difficulty id.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return integer
	 */
	public function get_difficulty_id( $context = 'view' ) {
		return $this->get_prop( 'difficulty_id', $context );
	}

	public function get_difficulty() {
		$difficulty = masteriyo( 'course_difficulty' );
		$store = masteriyo( 'course_difficulty.store' );

		try {
			$difficulty->set_id( $this->get_difficulty_id() );
			$store->read( $difficulty );
		} catch ( \Exception $e) {
			return null;
		}

		return apply_filters( 'masteriyo_get_course_difficulty_object', $difficulty, $this->get_difficulty_id(), $this );
	}

	/**
	 * Returns course tag ids.
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
	 * Check whether the course is featured or not.
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
	 * Returns whether or not the course is featured.
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	public function is_featured() {
		return true === $this->get_featured();
	}

	/**
	 * Get rating count.
	 *
	 * @since  0.1.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return array of counts
	 */
	public function get_rating_counts( $context = 'view' ) {
		return $this->get_prop( 'rating_counts', $context );
	}

	/**
	 * Get average rating.
	 *
	 * @since  0.1.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return float
	 */
	public function get_average_rating( $context = 'view' ) {
		return $this->get_prop( 'average_rating', $context );
	}

	/**
	 * Get review count.
	 *
	 * @since  0.1.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return int
	 */
	public function get_review_count( $context = 'view' ) {
		return $this->get_prop( 'review_count', $context );
	}

	/**
	 * Set course name.
	 *
	 * @since 0.1.0
	 *
	 * @param string $name course name.
	 */
	public function set_name( $name ) {
		$this->set_prop( 'name', $name );
	}

	/**
	 * Set course slug.
	 *
	 * @since 0.1.0
	 *
	 * @param string $slug course slug.
	 */
	public function set_slug( $slug ) {
		$this->set_prop( 'slug', $slug );
	}

	/**
	 * Set course created date.
	 *
	 * @since 0.1.0
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_date_created( $date = null ) {
		$this->set_prop( 'date_created', $date );
	}

	/**
	 * Set course modified date.
	 *
	 * @since 0.1.0
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_date_modified( $date = null ) {
		$this->set_prop( 'date_modified', $date );
	}

	/**
	 * Set course status.
	 *
	 * @since 0.1.0
	 *
	 * @param string $status course status.
	 */
	public function set_status( $status ) {
		$this->set_prop( 'status', $status );
	}

	/**
	 * Set course description.
	 *
	 * @since 0.1.0
	 *
	 * @param string $description Course description.
	 */
	public function set_description( $description ) {
		$this->set_prop( 'description', $description );
	}

	/**
	 * Set course short description.
	 *
	 * @since 0.1.0
	 *
	 * @param string $short_description Course short description.
	 */
	public function set_short_description( $short_description ) {
		$this->set_prop( 'short_description', $short_description );
	}

	/**
	 * Set the course's password.
	 *
	 * @since 0.1.0
	 *
	 * @param string $password Password.
	 */
	public function set_post_password( $password ) {
		$this->set_prop( 'post_password', $password );
	}

	/**
	 * Set the course's review status.
	 *
	 * @since 0.1.0
	 *
	 * @param string $reviews_allowed Reviews allowed.( Value can be 'open' or 'closed')
	 */
	public function set_reviews_allowed( $reviews_allowed ) {
		$this->set_prop( 'reviews_allowed', $reviews_allowed );
	}

	/**
	 * Set the course author id.
	 *
	 * @since 0.1.0
	 *
	 * @param string $author_id Author id.
	 */
	public function set_author_id( $author_id ) {
		$this->set_prop( 'author_id', absint( $author_id ) );
	}

	/**
	 * Set the course parent id.
	 *
	 * @since 0.1.0
	 *
	 * @param string $parent Parent id.
	 */
	public function set_parent_id( $parent ) {
		$this->set_prop( 'parent_id', absint( $parent ) );
	}

	/**
	 * Set the course menu order.
	 *
	 * @since 0.1.0
	 *
	 * @param string $menu_order Menu order id.
	 */
	public function set_menu_order( $menu_order ) {
		$this->set_prop( 'menu_order', absint( $menu_order ) );
	}

	/**
	 * Set the course's active price.
	 *
	 * @since 0.1.0
	 *
	 * @param string $price Price.
	 */
	public function set_price( $price ) {
		$this->set_prop( 'price', $price );
	}

	/**
	 * Set the course's regular price.
	 *
	 * @since 0.1.0
	 *
	 * @param string $price Regular price.
	 */
	public function set_regular_price( $price ) {
		$this->set_prop( 'regular_price', $price );
	}

	/**
	 * Set the course's sale price.
	 *
	 * @since 0.1.0
	 *
	 * @param string $price Sale price.
	 */
	public function set_sale_price( $price ) {
		$this->set_prop( 'sale_price', $price );
	}

	/**
	 * Set date on sale from.
	 *
	 * @since 0.1.0
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_date_on_sale_from( $date = null ) {
		$this->set_date_prop( 'date_on_sale_from', $date );
	}

	/**
	 * Set date on sale to.
	 *
	 * @since 0.1.0
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_date_on_sale_to( $date = null ) {
		$this->set_date_prop( 'date_on_sale_to', $date );
	}

	/**
	 * Set the course category ids.
	 *
	 * @since 0.1.0
	 *
	 * @param array $category_ids Category ids.
	 */
	public function set_category_ids( $category_ids ) {
		$this->set_prop( 'category_ids', array_unique( array_map( 'intval', $category_ids ) ) );
	}

	/**
	 * Set the course tag ids.
	 *
	 * @since 0.1.0
	 *
	 * @param array $tag_ids Tag ids.
	 */
	public function set_tag_ids( $tag_ids ) {
		$this->set_prop( 'tag_ids', array_unique( array_map( 'intval', $tag_ids ) ) );
	}

	/**
	 * Set the course difficulty id.
	 *
	 * @since 0.1.0
	 *
	 * @param array $difficulty_id Difficulty id.
	 */
	public function set_difficulty_id( $difficulty_id ) {
		$this->set_prop( 'difficulty_id', absint( $difficulty_id ) );
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


	/**
	 * Set rating counts. Read only.
	 *
	 * @since 0.1.0
	 * @param array $counts Course rating counts.
	 */
	public function set_rating_counts( $counts ) {
		$this->set_prop( 'rating_counts', array_filter( array_map( 'absint', (array) $counts ) ) );
	}

	/**
	 * Set average rating. Read only.
	 *
	 * @since 0.1.0
	 * @param float $average Course average rating.
	 */
	public function set_average_rating( $average ) {
		$this->set_prop( 'average_rating', Format::decimal( $average ) );
	}

	/**
	 * Set review count. Read only.
	 *
	 * @since 0.1.0
	 * @param int $count Course review count.
	 */
	public function set_review_count( $count ) {
		$this->set_prop( 'review_count', absint( $count ) );
	}

	 /**
	 * Returns whether or not the course is on sale.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return bool
	 */
	public function is_on_sale( $context = 'view' ) {
		if ( '' !== (string) $this->get_sale_price( $context ) && $this->get_regular_price( $context ) > $this->get_sale_price( $context ) ) {
			$on_sale = true;

			if ( $this->get_date_on_sale_from( $context ) && $this->get_date_on_sale_from( $context )->getTimestamp() > time() ) {
				$on_sale = false;
			}

			if ( $this->get_date_on_sale_to( $context ) && $this->get_date_on_sale_to( $context )->getTimestamp() < time() ) {
				$on_sale = false;
			}
		} else {
			$on_sale = false;
		}
		return 'view' === $context ? apply_filters( 'masteriyo_course_is_on_sale', $on_sale, $this ) : $on_sale;
	}

	/**
	 * Returns false if the course cannot be bought.
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	public function is_purchasable() {
		return apply_filters(
			'masteriyo_is_purchasable',
			( 'publish' === $this->get_status() || current_user_can( 'edit_post', $this->get_id() ) ) && '' !== $this->get_price(),
			$this
		);
	}

	/**
	 * Check whether the course exists in the database or not.
	 *
	 * @return void
	 */
	public function exists() {
		return false !== $this->get_status();
	}

		/**
	 * Returns whether or not the course is visible in the catalog.
	 *
	 * @return bool
	 */
	public function is_visible() {
		$visible = $this->is_visible_core();
		return apply_filters( 'masteriyo_course_is_visible', $visible, $this->get_id() );
	}

	/**
	 * Returns whether or not the course is visible in the catalog (doesn't trigger filters).
	 *
	 * @return bool
	 */
	protected function is_visible_core() {
		$visible = 'visible' === $this->get_catalog_visibility() || ( is_search() && 'search' === $this->get_catalog_visibility() ) || ( ! is_search() && 'catalog' === $this->get_catalog_visibility() );

		if ( 'trash' === $this->get_status() ) {
			$visible = false;
		} elseif ( 'publish' !== $this->get_status() && ! current_user_can( 'edit_post', $this->get_id() ) ) {
			$visible = false;
		}

		if ( 'yes' === get_option( 'masteriyo_hide_out_of_stock_items' ) ) {
			$visible = false;
		}

		return $visible;
	}
}
