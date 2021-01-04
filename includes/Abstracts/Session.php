<?php
/**
 * Abstract class to handle session.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo;
 * @subpackage Session;
 */

namespace ThemeGrill\Masteriyo\Abstracts;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Contracts\Session\Session as SessionInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Abstract session class.
 */
abstract class Session extends Model implements SessionInterface {

	/**
	 * Session expiry.
	 *
	 * @since 0.1.0
	 *
	 * @var string Session due to expire - Timestamp.
	 */
	protected $expiring;

	/**
	 * This is the name of this object type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $object_type = 'session';

	/**
	 * Cache group.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $cache_group = 'sessions';

	/**
	 * Stores course data.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $data = array(
		'data'   => array(),
		'expiry' => 0,
	);

	/**
	 * Generate a unique customer ID for guests, or return user ID if logged in.
	 *
	 * Uses Portable PHP password hashing framework to generate a unique cryptographically strong ID.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function generate_id() {
		$id = '';

		if ( is_user_logged_in() ) {
			$id = strval( get_current_user_id() );
		}

		if ( empty( $id ) ) {
			require_once ABSPATH . 'wp-includes/class-phpass.php';
			$hasher = new \PasswordHash( 8, false );
			$id     = md5( $hasher->get_random_bytes( 32 ) );
		}

		return $id;
	}

	/**
	 * Get the current session ID.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Set the session ID.
	 *
	 * @param string $id Set the session ID.
	 */
	public function set_id( $id ) {
		$this->id = (string) $id;
	}

	/**
	 * Get session expiry.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return int
	 */
	public function get_expiry( $context = 'view' ) {
		return $this->get_prop( 'expiry', $context );
	}

	/**
	 * Set session expiry.
	 *
	 * @since 0.1.0
	 *
	 * @param int $expiry Session expiry timestamp.
	 * @return ThemeGrill\Masteriyo\Session\Session
	 */
	public function set_expiry( $expiry ) {
		$this->set_prop( 'expiry', absint( $expiry ) );
		return $this;
	}

	/**
	 * Get an item from the session.
	 *
	 * @since 0.1.0
	 *
	 * @param string $key     Session item key.
	 * @param mixed $default  Session item default value.
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed
	 */
	public function get( $key, $default = null, $context = 'view' ) {
		$session_data = $this->get_prop( 'data', $context );
		$session_data = isset( $session_data[ $key ] ) ? $session_data[ $key ] : $default;
		return $session_data;
	}

	/**
	 * Put a key/value pair in the session.
	 *
	 * @param string $key	Session item key.
	 * @param mixed $value	Session item value.
	 * @return void
	 */
	public function put( $key, $value = null ) {
		$session_data         = $this->get_prop( 'data' );
		$session_data[ $key ] = $value;
		$this->set_prop( 'data', $session_data );
	}

	/**
	 * Get all the session data.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return array
	 */
	public function all( $context = 'view' ) {
		return $this->get_prop( 'data', $context );
	}

	/**
	 * Check if a key exists.
	 *
	 * @since 0.1.0
	 *
	 * @param string|array $key Session data key/keys.
	 * @return bool
	 */
	public function exists( $key ) {
		$keys         = (array) $key;
		$session_data = $this->get_prop( 'data' );
		$result       = array_reduce( $keys, function( $result, $key ) use ( $session_data ) {
			return $result && isset( $session_data[ $key ] );
		}, true );

		return $result;
	}

	/**
	 * Check if a key is present and not null.
	 *
	 * @since 0.1.0
	 *
	 * @param string $key Session data key.
	 * @return boolean
	 */
	public function has( $key ) {
		$keys         = (array) $key;
		$session_data = $this->get_prop( 'data' );
		$result       = array_reduce( $keys, function( $result, $key ) use ( $session_data ) {
			return $result && isset( $session_data[ $key ] ) && ! is_null( $session_data[ $key ] );
		}, true );

		return $result;
	}

	/**
	 * Remove an item from the session, returning its value.
	 *
	 * @since 0.1.0
	 *
	 * @param string $key Session data key.
	 * @return mixed
	 */
	public function remove( $key ) {
		$value        = null;
		$session_data = $this->get_prop( 'data' );

		if ( isset( $session_data[ $key ] ) ) {
			$value = $session_data[ $key ];
			unset( $session_data[ $key ] );
		}

		$this->set_prop( 'data', $session_data );
		return $value;
	}

	/**
	 * Remove one or many items from the session.
	 *
	 * @since 0.1.0
	 *
	 * @param string|array $keys Session or array of session data keys.
	 * @return void
	 */
	public function forget( $keys ) {
		$session_data = $this->get_prop( 'data' );
		$keys         = (array ) $keys;
		$keys         = is_array( $keys ) ? array_flip( $keys ) : $keys;

		$session_data = array_filter( $session_data, function( $session_key ) use ( $keys ) {
			return ! isset( $keys[ $session_key ] );
		}, ARRAY_FILTER_USE_KEY );

		$this->set_prop( 'data', $session_data );
	}

	/**
	 * Remvoe all of the items from the session.
	 *
	 * @since 0.1.0
	 */
	public function flush() {
		$this->set_prop( 'data', array() );
	}

	/**
	 * Check whether the session is changed or not
	 *
	 * @since 0.1.0
	 *
	 * @return boolean
	 */
	public function is_dirty() {
		return ! empty( $this->changes );
	}
}
