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
 * UserActivity model (post type).
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
	 * Post type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $post_type = 'user-activity';

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
		'user_id'   => 0,
		'item_id'   => 0,
		'item_type' => null,
		'type'      => null,
		'status'    => null,
		'start'     => '',
		'update'    => '',
		'complete'  => '',
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

	/**
	 * Getters
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
	 * Get item type.
	 *
	 * @since 0.1.0
	 *
	* @param  string $context What the value is for. valid values are view and edit.

	 * @return int
	 */
	public function get_item_type( $context = 'view' ) {
		return $this->get_prop( 'item_type', $context );
	}

	/**
	 * Get activity type.
	 *
	 * @since 0.1.0
	 *
	* @param  string $context What the value is for. valid values are view and edit.

	 * @return int
	 */
	public function get_type( $context = 'view' ) {
		return $this->get_prop( 'type', $context );
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

	 * @return int
	 */
	public function get_start( $context = 'view' ) {
		return $this->get_prop( 'start', $context );
	}

	/**
	 * Get activity update.
	 *
	 * @since 0.1.0
	 *
	* @param  string $context What the value is for. valid values are view and edit.

	 * @return int
	 */
	public function get_update( $context = 'view' ) {
		return $this->get_prop( 'update', $context );
	}

	/**
	 * Get activity complete.
	 *
	 * @since 0.1.0
	 *
	* @param  string $context What the value is for. valid values are view and edit.

	 * @return int
	 */
	public function get_complete( $context = 'view' ) {
		return $this->get_prop( 'complete', $context );
	}

	/**
	 * Setters
	 */

	/**
	 * Set user id.
	 *
	 * @since 0.1.0
	 *
	* @param int $user_id User ID.

	 * @return int
	 */
	public function set_user_id( $user_id ) {
		return $this->set_prop( 'user_id', $user_id );
	}

	/**
	 * Set item id.
	 *
	 * @since 0.1.0
	 *
	* @param int $item_id Item ID. (course, quiz, etc.)

	 * @return int
	 */
	public function set_item_id( $item_id ) {
		return $this->set_prop( 'item_id', $item_id );
	}

	/**
	 * Set item type.
	 *
	 * @since 0.1.0
	 *
	* @param  string $type Item type.

	 * @return int
	 */
	public function set_item_type( $type ) {
		return $this->set_prop( 'item_type', $type );
	}

	/**
	 * Set activity type.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $type Activity type.
	 *
	 * @return int
	 */
	public function set_type( $type ) {
		return $this->set_prop( 'type', $type );
	}

	/**
	 * Set activity status.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $status Activity status.
	 *
	 * @return int
	 */
	public function set_status( $status ) {
		return $this->set_prop( 'status', $status );
	}

	/**
	 * Set activity start.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $start Activity start.
	 *
	 * @return int
	 */
	public function set_start( $start ) {
		return $this->set_prop( 'start', $start );
	}

	/**
	 * Set activity update.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $update Activity update.
	 *
	 * @return int
	 */
	public function set_update( $update ) {
		return $this->set_prop( 'update', $update );
	}

	/**
	 * Set activity complete.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $complete Activity complete.
	 *
	 * @return int
	 */
	public function set_complete( $complete ) {
		return $this->set_prop( 'complete', $complete );
	}
}
