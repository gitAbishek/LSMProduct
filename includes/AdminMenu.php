<?php
/**
 * Ajax.
 *
 * @package Masteriyo
 *
 * @since 1.0.0
 */

namespace Masteriyo;

use Masteriyo\Constants;

defined( 'ABSPATH' ) || exit;

/**
 * Ajax class.
 *
 * @class Masteriyo\Ajax
 */

class AdminMenu {

	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init() {
		self::init_hooks();
	}

	/**
	 * Initialize admin menus.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init_menus() {
		// Bail early if the admin menus is not visible.
		if ( ! masteriyo_is_admin_menus_visible() ) {
			return true;
		}

		// phpcs:disable
		if ( isset( $_GET['page'] ) && 'masteriyo' === $_GET['page'] ) {
			$dashicon = 'data:image/svg+xml;base64,' . base64_encode( masteriyo_get_svg( 'dashicon-white' ) );
		} else {
			$dashicon = 'data:image/svg+xml;base64,' . base64_encode( masteriyo_get_svg( 'dashicon-grey' ) );
		}
		// phpcs:enable

		add_menu_page(
			esc_html__( 'Masteriyo', 'masteriyo' ),
			esc_html__( 'Masteriyo', 'masteriyo' ),
			'edit_courses',
			'masteriyo',
			array( __CLASS__, 'display_main_page' ),
			$dashicon,
			3
		);

		add_submenu_page(
			'masteriyo',
			esc_html__( 'Courses', 'masteriyo' ),
			esc_html__( 'Courses', 'masteriyo' ),
			'edit_courses',
			'masteriyo#/courses',
			array( __CLASS__, 'display_main_page' )
		);

		add_submenu_page(
			'masteriyo',
			esc_html__( 'Categories', 'masteriyo' ),
			esc_html__( 'Categories', 'masteriyo' ),
			'manage_course_categories',
			'masteriyo#/courses/categories',
			array( __CLASS__, 'display_main_page' )
		);

		add_submenu_page(
			'masteriyo',
			esc_html__( 'Orders', 'masteriyo' ),
			esc_html__( 'Orders', 'masteriyo' ),
			'manage_masteriyo_settings',
			'masteriyo#/orders',
			array( __CLASS__, 'display_main_page' )
		);

		add_submenu_page(
			'masteriyo',
			esc_html__( 'Quiz Attempts', 'masteriyo' ),
			esc_html__( 'Quiz Attempts', 'masteriyo' ),
			'manage_masteriyo_settings',
			'masteriyo#/quiz-attempts',
			array( __CLASS__, 'display_main_page' )
		);

		add_submenu_page(
			'masteriyo',
			esc_html__( 'Users', 'masteriyo' ),
			esc_html__( 'Users', 'masteriyo' ),
			'manage_masteriyo_settings',
			'masteriyo#/users/students',
			array( __CLASS__, 'display_main_page' )
		);

		add_submenu_page(
			'masteriyo',
			esc_html__( 'Settings', 'masteriyo' ),
			esc_html__( 'Settings', 'masteriyo' ),
			'manage_masteriyo_settings',
			'masteriyo#/settings',
			array( __CLASS__, 'display_main_page' )
		);

		remove_submenu_page( 'masteriyo', 'masteriyo' );
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private static function init_hooks() {
		add_action( 'admin_menu', array( __CLASS__, 'init_menus' ) );
	}

	/**
	 * Display main page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function display_main_page() {
		require_once Constants::get( 'MASTERIYO_PLUGIN_DIR' ) . '/templates/masteriyo.php';
	}
}
