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
		'price_type'         => 'free',
		'category_ids'       => array(),
		'tag_ids'            => array(),
		'difficulty_id'      => 0,
		'featured_image'     => '',
		'rating_counts'      => array(),
		'average_rating'     => 0,
		'review_count'       => 0,
		'enrollment_limit'   => 0,
		'duration'           => 0,
		'access_mode'        => 'open',
		'billing_cycle'      => '',
		'show_curriculum'    => true,
	);

	/**
	 * Constructor.
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
		if ( empty( $this->get_featured_image() ) ) {
			return '';
		}
		return wp_get_attachment_url( $this->get_featured_image() );
	}

	/**
	 * If the stock level comes from another product ID, this should be modified.
	 *
	 * @since  0.1.0
	 * @return int
	 */
	public function get_stock_managed_by_id() {
		return $this->get_id();
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
	 * Course Preview URL;
	 *
	 * @return string
	 */
	public function get_preview_course_link() {
		return get_preview_post_link( $this->get_id() );
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
	 * Get lecture hours in human readable format.
	 *
	 * @since  0.1.0
	 *
	 * @return string
	 */
	public function get_human_readable_lecture_hours() {
		return masteriyo_get_lecture_hours( $this );
	}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

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
	 * Returns course's price type.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_price_type( $context = 'view' ) {
		return $this->get_prop( 'price_type', $context );
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

	/**
	 * Get the difficulty object.
	 *
	 * @since 0.1.0
	 *
	 * @return Difficulty
	 */
	public function get_difficulty() {
		$difficulty = masteriyo( 'course_difficulty' );
		$store      = masteriyo( 'course_difficulty.store' );

		try {
			$difficulty->set_id( $this->get_difficulty_id() );
			$store->read( $difficulty );
		} catch ( \Exception $e ) {
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
		return $this->get_prop( 'featured', $context );
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
	 * Get the total amount (COUNT) of ratings, or just the count for one rating e.g. number of 5 star ratings.
	 *
	 * @since 0.1.0
	 *
	 * @param  int $value Optional. Rating value to get the count for. By default returns the count of all rating values.
	 *
	 * @return int
	 */
	public function get_rating_count( $value = null ) {
		$counts = $this->get_rating_counts();

		if ( is_null( $value ) ) {
			return array_sum( $counts );
		} elseif ( isset( $counts[ $value ] ) ) {
			return absint( $counts[ $value ] );
		}
		return 0;
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
	 * Get the enrollment limit (maximum number of students allowed to enroll).
	 *
	 * @since 0.1.0
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return int
	 */
	public function get_enrollment_limit( $context = 'view' ) {
		return $this->get_prop( 'enrollment_limit', $context );
	}

	/**
	 * Get course duration.
	 *
	 * @since 0.1.0
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return int
	 */
	public function get_duration( $context = 'view' ) {
		return $this->get_prop( 'duration', $context );
	}

	/**
	 * Get course access mode.
	 *
	 * @since 0.1.0
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return int
	 */
	public function get_access_mode( $context = 'view' ) {
		return $this->get_prop( 'access_mode', $context );
	}

	/**
	 * Get course billing cycle.
	 *
	 * @since 0.1.0
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return int
	 */
	public function get_billing_cycle( $context = 'view' ) {
		return $this->get_prop( 'billing_cycle', $context );
	}

	/**
	 * Get course curriculum.
	 *
	 * True = Visible to all.
	 * False = Visible to only enrollees.
	 *
	 * @since 0.1.0
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return int
	 */
	public function get_show_curriculum( $context = 'view' ) {
		return $this->get_prop( 'show_curriculum', $context );
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

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
	 * Set the course's price type (free or paid).
	 *
	 * @since 0.1.0
	 *
	 * @param string $type Course's price type (free or paid)
	 */
	public function set_price_type( $type ) {
		$this->set_prop( 'price_type', $type );
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
		$this->set_prop( 'featured_image', absint( $featured_image ) );
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
		$this->set_prop( 'average_rating', masteriyo_format_decimal( $average ) );
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
	 * Set the enrollment limit. (maximum number of students allowed )
	 *
	 * @since 0.1.0
	 * @param int $value Enrollment limit.
	 */
	public function set_enrollment_limit( $value ) {
		$this->set_prop( 'enrollment_limit', absint( $value ) );
	}

	/**
	 * Set the course duration (minutes).
	 *
	 * @since 0.1.0
	 * @param int $value Course duration (minutes).
	 */
	public function set_duration( $value ) {
		$this->set_prop( 'duration', absint( $value ) );
	}

	/**
	 * Set the course access mode.
	 *
	 * @since 0.1.0
	 * @param string $value Course access mode (open, need_registration, one_time, recurring ).
	 */
	public function set_access_mode( $value ) {
		$this->set_prop( 'access_mode', $value );
	}

	/**
	 * Set the course billing cycle.
	 *
	 * @since 0.1.0
	 * @param string $value Course billing cycle (1d, 2w, 3m, 4y)
	 */
	public function set_billing_cycle( $value ) {
		$this->set_prop( 'billing_cycle', masteriyo_strtolower( $value ) );
	}

	/**
	 * Set the course curriculum.
	 *
	 * True = Visible to all.
	 * False = Visible to only enrollees.
	 *
	 * @since 0.1.0
	 * @param string $value
	 */
	public function set_show_curriculum( $value ) {
		$this->set_prop( 'show_curriculum', masteriyo_string_to_bool( $value ) );
	}

	/*
	|--------------------------------------------------------------------------
	| Non-CRUD functions
	|--------------------------------------------------------------------------
	*/

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
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function exists() {
		return false !== $this->get_status();
	}

	/**
	 * Returns whether or not the course is visible in the catalog.
	 *
	 * @since 0.1.0
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

	/**
	 * Get course category list (CourseCategory objects).
	 *
	 * @since 0.1.0
	 *
	 * @return array[CourseCategory]
	 */
	public function get_categories() {
		$cat_ids    = $this->get_category_ids();
		$categories = array();
		$store      = masteriyo( 'course_cat.store' );

		$categories = array_map(
			function( $cat_id ) use ( $store ) {
				$cat_obj = masteriyo( 'course_cat' );
				$cat_obj->set_id( $cat_id );
				$store->read( $cat_obj );
				return $cat_obj;
			},
			$cat_ids
		);

		return apply_filters( 'masteriyo_course_categories_objects', $categories, $this );
	}

	/**
	 * Get course tag list (CourseTag objects).
	 *
	 * @since 0.1.0
	 *
	 * @return array[CourseTag]
	 */
	public function get_tags() {
		$tag_ids = $this->get_tags_ids();
		$tags    = array();
		$store   = masteriyo( 'course_tag.store' );

		$tags = array_map(
			function( $tag_id ) use ( $store ) {
				$tag_obj = masteriyo( 'course_tag' );
				$tag_obj->set_id( $tag_id );
				$store->read( $tag_obj );
				return $tag_obj;
			},
			$tag_ids
		);

		return apply_filters( 'masteriyo_course_categories_objects', $tags, $this );
	}

	/**
	 * Get course difficulties list (Coursedifficulties objects).
	 *
	 * @since 0.1.0
	 *
	 * @return array[Coursedifficulties]
	 */
	public function get_difficulties() {
		$difficulty_id = $this->get_difficulty_id();
		$store         = masteriyo( 'course_difficulty.store' );

		$difficulty_obj = masteriyo( 'course_difficulty' );
		$difficulty_obj->set_id( $difficulty_id );
		$store->read( $difficulty_obj );

		return apply_filters( 'masteriyo_course_categories_objects', $difficulty_obj, $this );
	}

	/**
	 * Get add_to_cart now button text for the single page.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function single_add_to_cart_text() {
		return apply_filters( 'masteriyo_single_course_add_to_cart_text', __( 'Add to cart', 'masteriyo' ), $this );
	}

	/**
	 * Get start course button text for the single page.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function single_course_start_text() {
		return apply_filters( 'masteriyo_single_course_start_text', __( 'Start Course', 'masteriyo' ), $this );
	}

	/**
	 * Get start course URL.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function start_course_url() {
		return home_url( '?masteriyo=interactive#/course/' . $this->get_id() );
	}

	/**
	 * Get add_to_cart url.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function add_to_cart_url() {
		$url = $this->get_permalink();

		if ( $this->is_purchasable() ) {
			$base_url = ( function_exists( 'is_feed' ) && is_feed() ) || ( function_exists( 'is_404' ) && is_404() ) ? $this->get_permalink() : '';

			$url = add_query_arg(
				array(
					'add_to_cart' => $this->get_id(),
				),
				$base_url
			);
		}

		return apply_filters( 'masteriyo_course_add_to_cart_url', $url, $this );
	}

	/**
	 * Get add_to_cart text.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function add_to_cart_text() {
		$text = __( 'Read more', 'masteriyo' );

		if ( $this->is_purchasable() ) {
			$text = __( 'Add_to_cart Now', 'masteriyo' );
		}

		return apply_filters( 'masteriyo_course_add_to_cart_text', $text, $this );
	}


	/**
	 * Get add_to_cart  button text description - used in aria tags.
	 *
	 * @since 0.1.0
	 * @return string
	 */
	public function add_to_cart_description() {
		/* translators: %s: Course title */
		$text = __( 'Read more about &ldquo;%s&rdquo;', 'masteriyo' );

		if ( $this->is_purchasable() ) {
			/* translators: %s: Course title */
			$text = __( 'Enroll &ldquo;%s&rdquo; course', 'masteriyo' );
		}

		return apply_filters( 'masteriyo_course_add_to_cart_description', sprintf( $text, $this->get_name() ), $this );
	}
}
