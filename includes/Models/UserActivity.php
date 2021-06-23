<?php
/**
 * UserActivity model.
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
 * UserActivity model (custom table).
 *
 * @since 0.1.0
 */
class UserActivity extends Model {

	/**
	 * This is the name of this object type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $object_type = 'user-activity';

	/**
	 * Cache group.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $cache_group = 'user-activities';

	/**
	 * Stores user activity data.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $data = array(
		'user_id'       => 0,
		'item_id'       => 0,
		'type'          => '',
		'parent_id'     => 0,
		'status'        => 'begin',
		'date_start'    => null,
		'date_update'   => null,
		'date_complete' => null,
	);

	/**
	 * Get the user-activity if ID
	 *
	 * @since 0.1.0
	 *
	 * @param RepositoryInterface $user_activity_repository User-activity Repository,
	 */
	public function __construct( RepositoryInterface $user_activity_repository ) {
		$this->repository = $user_activity_repository;
	}

	/*
	|--------------------------------------------------------------------------
	| Non-CRUD Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get user activity table.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_table_name() {
		global $wpdb;

		return "{$wpdb->base_prefix}masteriyo_user_activities";
	}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get user id.
	 *
	 * @since 0.1.0
	 *
	* @param  string $context What the value is for. Valid values are view and edit.

	 * @return int
	 */
	public function get_user_id( $context = 'view' ) {
		return $this->get_prop( 'user_id', $context );
	}

	/**
	 * Get item id.
	 *
	 * @since 0.1.0
	 *
	* @param  string $context What the value is for. Valid values are view and edit.

	 * @return int
	 */
	public function get_item_id( $context = 'view' ) {
		return $this->get_prop( 'item_id', $context );
	}

	/**
	 * Get activity type.
	 *
	 * @since 0.1.0
	 *
	* @param  string $context What the value is for. valid values are view and edit.

	 * @return string
	 */
	public function get_type( $context = 'view' ) {
		return $this->get_prop( 'type', $context );
	}

	/**
	 * Get activity parent id.
	 *
	 * @since 0.1.0
	 *
	* @param  string $context What the value is for. valid values are view and edit.

	 * @return int
	 */
	public function get_parent_id( $context = 'view' ) {
		return $this->get_prop( 'parent_id', $context );
	}

	/**
	 * Get activity status.
	 *
	 * @since 0.1.0
	 *
	* @param  string $context What the value is for. valid values are view and edit.

	 * @return int
	 */
	public function get_status( $context = 'view' ) {
		return $this->get_prop( 'status', $context );
	}

	/**
	 * Get activity start.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. valid values are view and edit.
	 * @return DateTime|null
	 */
	public function get_date_start( $context = 'view' ) {
		return $this->get_prop( 'date_start', $context );
	}

	/**
	 * Get activity update.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. valid values are view and edit.
	 * @return DateTime|null
	 */
	public function get_date_update( $context = 'view' ) {
		return $this->get_prop( 'date_update', $context );
	}

	/**
	 * Get activity complete.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $context What the value is for. valid values are view and edit.
	 * @return DateTime|null
	 */
	public function get_date_complete( $context = 'view' ) {
		return $this->get_prop( 'date_complete', $context );
	}

	/**
	 * Get user activity items.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_items( $context = 'view' ) {
		return $this->items;
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set user id.
	 *
	 * @since 0.1.0
	 *
	* @param int $user_id User ID.
	 */
	public function set_user_id( $user_id ) {
		$this->set_prop( 'user_id', absint( $user_id ) );
	}

	/**
	 * Set item id.
	 *
	 * @since 0.1.0
	 *
	* @param int $item_id Item ID. (course, quiz, etc.)
	 */
	public function set_item_id( $item_id ) {
		$this->set_prop( 'item_id', absint( $item_id ) );
	}

	/**
	 * Set activity type.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $type Activity type.
	 */
	public function set_type( $type ) {
		$this->set_prop( 'type', $type );
	}

	/**
	 * Set activity parent ID.
	 *
	 * @since 0.1.0
	 *
	 * @param int $value Activity parent ID.
	 */
	public function set_parent_id( $value ) {
		$this->set_prop( 'parent_id', absint( $value ) );
	}

	/**
	 * Set activity status.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $status Activity status.
	 */
	public function set_status( $status ) {
		$this->set_prop( 'status', $status );
	}

	/**
	 * Set activity start.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $start Activity start.
	 */
	public function set_date_start( $date_start ) {
		$this->set_date_prop( 'date_start', $date_start );
	}

	/**
	 * Set activity update.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $update Activity update.
	 */
	public function set_date_update( $date_update ) {
		$this->set_date_prop( 'date_update', $date_update );
	}

	/**
	 * Set activity complete.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $complete Activity complete.
	 */
	public function set_date_complete( $date_complete ) {
		$this->set_date_prop( 'date_complete', $date_complete );
	}
}
