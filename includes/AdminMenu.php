<?php
/**
 * Ajax.
 *
 * @package ThemeGrill\Masteriyo
 *
 * @since 0.1.0
 */

namespace ThemeGrill\Masteriyo;

use ThemeGrill\Masteriyo\Constants;

defined( 'ABSPATH' ) || exit;

/**
 * Aajx class.
 *
 * @class ThemeGrill\Masteriyo\Ajax
 */

class AdminMenu {

	/**
	 * Initialize.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public static function init() {
		self::init_hooks();
	}

	/**
	 * Initialize admin menus.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public static function init_menus() {
		add_menu_page(
			esc_html__( 'Masteriyo', 'masteriyo' ),
			esc_html__( 'Masteriyo', 'masteriyo' ),
			'manage_options',
			'masteriyo',
			array( __CLASS__, 'display_main_page' ),
			'dashicons-align-full-width',
			3
		);

		add_submenu_page(
			'masteriyo',
			esc_html__( 'Courses', 'masteriyo' ),
			esc_html__( 'Courses', 'masteriyo' ),
			'manage_options',
			'masteriyo#/courses',
			array( __CLASS__, 'display_main_page' )
		);

		add_submenu_page(
			'masteriyo',
			esc_html__( 'Categories', 'masteriyo' ),
			esc_html__( 'Categories', 'masteriyo' ),
			'manage_options',
			'masteriyo#/courses/categories',
			array( __CLASS__, 'display_main_page' )
		);

		add_submenu_page(
			'masteriyo',
			esc_html__( 'Orders', 'masteriyo' ),
			esc_html__( 'Orders', 'masteriyo' ),
			'manage_options',
			'masteriyo#/orders',
			array( __CLASS__, 'display_main_page' )
		);

		add_submenu_page(
			'masteriyo',
			esc_html__( 'Settings', 'masteriyo' ),
			esc_html__( 'Settings', 'masteriyo' ),
			'manage_options',
			'masteriyo#/settings',
			array( __CLASS__, 'display_main_page' )
		);

		remove_submenu_page( 'masteriyo', 'masteriyo' );
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private static function init_hooks() {
		add_action( 'admin_menu', array( __CLASS__, 'init_menus' ) );
	}

	/**
	 * Display main page.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public static function display_main_page() {
		require_once Constants::get( 'MASTERIYO_PLUGIN_DIR' ) . '/templates/masteriyo.php';
	}
}
