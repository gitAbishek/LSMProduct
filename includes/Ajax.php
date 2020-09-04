<?php
/**
 * Ajax.
 *
 * @package ThemeGrill\Masteriyo
 *
 * @since 0.1.0
 */

namespace ThemeGrill\Masteriyo;

defined( 'ABSPATH' ) || exit;

/**
 * Aajx class.
 *
 * @class ThemeGrill\Masteriyo\Ajax
 */

class Ajax {

	/**
	 * Actions.
	 *
	 * @static
	 * @since 0.1.0
	 *
	 * @var array
	 */
	private static $actions = array();

	/**
	 * Initialize
	 *
	 * @static
	 * @since 0.1.0
	 */
	public static function init() {
		self::init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @static
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private static function init_hooks() {
		self::$actions = apply_filters( 'masteriyo_ajax_actions', array(
			'test' => array(
				'priv'   => array( __CLASS__, 'test' ),
				'nopriv' => array( __CLASS__, 'test' )
			)
		) );

		foreach ( self::$actions as $key => $action ) {
			foreach ( $action as $type => $callback ) {
				$type = 'priv' === $type ? '' : '_nopriv';
				$slug = MASTERIYO_SLUG;
				add_action( "wp_ajax{$type}_{$slug}_{$key}", $callback );
			}
		}
	}

	/**
	 * Test ajax function.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private static function test() {
		wp_send_json_success( __FUNCTION__ );
	}
}

Ajax::init();
