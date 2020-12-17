<?php
/**
 * Handle data for the current customer's session.
 *
 * @since 0.1.0
 * @class Session
 * @package ThemeGrill\Masteriyo\Database
 */

namespace ThemeGrill\Masteriyo\Database;

defined( 'ABSPATH' ) || exit;

/**
 * Abstract Session Class.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Database
 */
abstract class Session {

	/**
	 * Customer ID.
	 *
	 * @since 0.1.0
	 *
	 * @var integer $_customer_id Customer ID.
	 */
	protected $_customer_id;

	/**
	 * Session Data.
	 *
	 * @since 0.1.0
	 *
	 * @var array $_data Data array.
	 */
	protected $_data = array();

	/**
	 * Dirty when the session needs saving.
	 *
	 * @since 0.1.0
	 *
	 * @var boolean $_dirty When something changes
	 */
	protected $_dirty = false;

	/**
	 * Init hooks and session data. Extended by child classes.
	 *
	 * @since 0.1.0
	 */
	public function init() {}

	/**
	 * Cleanup session data. Extended by child classes.
	 *
	 * @since 0.1.0
	 */
	public function cleanup_sessions() {}

	/**
	 * Magic get method.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $key Key to get.
	 *
	 * @return mixed
	 */
	public function __get( $key ) {
		return $this->get( $key );
	}

	/**
	 * Magic set method.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $key Key to set.
	 *
	 * @param mixed $value Value to set.
	 */
	public function __set( $key, $value ) {
		$this->set( $key, $value );
	}

	/**
	 * Magic isset method.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $key Key to check.
	 *
	 * @return boolean
	 */
	public function __isset( $key ) {
		return isset( $this->_data[ sanitize_title( $key ) ] );
	}

	/**
	 * Magic unset method.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $key Key to unset.
	 */
	public function __unset( $key ) {
		if ( isset( $this->_data[ $key ] ) ) {
			unset( $this->_data[ $key ] );
			$this->_dirty = true;
		}
	}

	/**
	 * Get a session variable.
	 *
	 * @since 0.1.0
	 *
	 * @param string $key Key to get.
	 * @param mixed  $default used if the session variable isn't set.
	 *
	 * @return array|string value of session variable
	 */
	public function get( $key, $default = null ) {
		$key = sanitize_key( $key );
		return isset( $this->_data[ $key ] ) ? maybe_unserialize( $this->_data[ $key ] ) : $default;
	}

	/**
	 * Set a session variable.
	 *
	 * @since 0.1.0
	 *
	 * @param string $key Key to set.
	 * @param mixed  $value Value to set.
	 */
	public function set( $key, $value ) {
		if ( $value !== $this->get( $key ) ) {
			$this->_data[ sanitize_key( $key ) ] = maybe_serialize( $value );
			$this->_dirty                        = true;
		}
	}

	/**
	 * Get customer ID.
	 *
	 * @since 0.1.0
	 *
	 * @return integer
	 */
	public function get_customer_id() {
		return $this->_customer_id;
	}
}
