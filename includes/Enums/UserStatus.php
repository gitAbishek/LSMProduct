<?php
/**
 * User status enums.
 *
 * @since x.x.x
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * User status enum class.
 *
 * @since x.x.x
 */
class UserStatus {
	/**
	 * User HAM/Active status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const HAM = 0;

	/**
	 * User HAM/Active status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const ACTIVE = 0;

	/**
	 * User SPAM status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const SPAM = 1;

	/**
	 * User inactive status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const INACTIVE = 1000;

	/**
	 * Get all statuses.
	 *
	 * @since x.x.x
	 * @static
	 *
	 * @return array
	 */
	public static function all() {
		/**
		 * Filter user statuses.
		 *
		 * @since x.x.x
		 * @param string[] $statuses User statuses.
		 */
		$statuses = apply_filters(
			'masteriyo_user_statuses',
			array(
				self::ACTIVE,
				self::INACTIVE,
				self::SPAM,
			)
		);

		return array_unique( $statuses );
	}

	/**
	 * Get all statuses in string.
	 *
	 * @since x.x.x
	 * @static
	 *
	 * @return array
	 */
	public static function all_str() {
		$all = self::all();

		$all = array_map( 'self::to_string', $all );

		return array_unique( $all );
	}

	/**
	 * User statuses maps.
	 *
	 * @since x.x.x
	 * @static
	 *
	 * @return array
	 */
	public static function map_user_statuses() {
		/**
		 * Filter user statuses maps.
		 *
		 * @since x.x.x
		 * @param string[] $maps User statuses maps.
		 */
		$maps = array_filters(
			'masteriyo_user_statuses_maps',
			array(
				'active'   => 0,
				'ham'      => 0,
				'spam'     => 1,
				'inactive' => 1000,
			)
		);

		return array_unique( $maps );
	}


	/**
	 * Convert string status to numeric.
	 *
	 * @since x.x.x
	 * @static
	 *
	 * @param string $status User status.
	 *
	 * @return int|null
	 */
	public static function to_numeric( $status ) {
		$maps = self::map_user_statuses();

		if ( isset( $maps[ $status ] ) ) {
			return $maps[ $status ];
		}

		return null;
	}

	/**
	 * Convert numeric status to string
	 *
	 * @since x.x.x
	 * @static
	 *
	 * @param int $status User status.
	 *
	 * @return string|null
	 */
	public static function to_string( $status ) {
		$maps = array_flip( self::map_user_statuses() );

		if ( isset( $maps[ $status ] ) ) {
			return $maps[ $status ];
		}

		return null;
	}
}
