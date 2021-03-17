<?php
/**
 * FAQ model.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Models;
 */

namespace ThemeGrill\Masteriyo\Models;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Repository\FAQRepository;

defined( 'ABSPATH' ) || exit;

/**
 * FAQ model (post type).
 *
 * @since 0.1.0
 */
class FAQ extends Model {

	/**
	 * This is the name of this object type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $object_type = 'faq';

	/**
	 * Post type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $post_type = 'faq';

	/**
	 * Cache group.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $cache_group = 'faq';

	/**
	 * Stores FAQ data.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $data = array(
		'name'          => '',
		'description'   => '',
		'menu_order'    => 0,
		'course_id'     => 0,
		'date_created'  => null,
		'date_modified' => null,
		'children'      => array(),
		'status'        => false,
	);

	/**
	 * Get the faq if ID
	 *
	 * @since 0.1.0
	 *
	 * @param FAQRepository $faq_repository FAQ Repository.
	 */
	public function __construct( FAQRepository $faq_repository ) {
		$this->repository = $faq_repository;
	}

	/*
	|--------------------------------------------------------------------------
	| Non-CRUD Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Returns the children IDs if applicable. Overridden by child classes.
	 *
	 * @return array of IDs
	 */
	public function get_children() {}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get FAQ name.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_name( $context = 'view' ) {
		return apply_filters( 'masteriyo_faq_name', $this->get_prop( 'name', $context ), $this );
	}

	/**
	 * Get FAQ title. Alias for get_name().
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_title( $context = 'view' ) {
		return $this->get_name( $context );
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
	public function get_date_created( $context = 'view' ) {
		return $this->get_prop( 'date_created', $context );
	}

	/**
	 * Get faq modified date.
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
	 * Get faq description.
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
	 * Returns faq parent id.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_course_id( $context = 'view' ) {
		return $this->get_prop( 'course_id', $context );
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
	public function get_menu_order( $context = 'view' ) {
		return $this->get_prop( 'menu_order', $context );
	}

	/**
	 * Get faq status.
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

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set faq name.
	 *
	 * @since 0.1.0
	 *
	 * @param string $name faq name.
	 */
	public function set_name( $name ) {
		$this->set_prop( 'name', $name );
	}


	/**
	 * Set faq title. Alias for set_name().
	 *
	 * @since 0.1.0
	 *
	 * @param string $title faq title.
	 */
	public function set_title( $title ) {
		$this->set_prop( 'name', $title );
	}

	/**
	 * Set faq created date.
	 *
	 * @since 0.1.0
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_date_created( $date = null ) {
		$this->set_prop( 'date_created', $date );
	}

	/**
	 * Set faq modified date.
	 *
	 * @since 0.1.0
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_date_modified( $date = null ) {
		$this->set_prop( 'date_modified', $date );
	}

	/**
	 * Set faq description.
	 *
	 * @since 0.1.0
	 *
	 * @param string $description FAQ description.
	 */
	public function set_description( $description ) {
		$this->set_prop( 'description', $description );
	}

	/**
	 * Set the faq parent id.
	 *
	 * @since 0.1.0
	 *
	 * @param int $parent Parent id.
	 */
	public function set_course_id( $parent ) {
		$this->set_prop( 'course_id', absint( $parent ) );
	}

	/**
	 * Set the faq menu order.
	 *
	 * @since 0.1.0
	 *
	 * @param int $menu_order Menu order id.
	 */
	public function set_menu_order( $menu_order ) {
		$this->set_prop( 'menu_order', absint( $menu_order ) );
	}

	/**
	 * Set faq status.
	 *
	 * @since 0.1.0
	 *
	 * @param string $status FAQ status.
	 */
	public function set_status( $status ) {
		$this->set_prop( 'status', $status );
	}
}
