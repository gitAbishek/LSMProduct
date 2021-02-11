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

use ThemeGrill\Masteriyo\Traits\Singleton;

/**
 * Aajx class.
 *
 * @class ThemeGrill\Masteriyo\Ajax
 */

class AdminMenu {
	use Singleton;

	/**
	 * Initialize.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function init() {
		$this->init_hooks();
	}

	/**
	 * Initialize admin menus.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function init_menus() {
		add_menu_page(
			esc_html__( 'Masteriyo', 'masteriyo' ),
			esc_html__( 'Masteriyo', 'masteriyo' ),
			'manage_options',
			'masteriyo',
			array( $this, 'display_main_page' )
		);

		add_submenu_page(
			'masteriyo',
			esc_html__( 'Masteriyo', 'masteriyo' ),
			esc_html__( 'Masteriyo', 'masteriyo' ),
			'manage_options',
			'masteriyo#/masteriyo',
			array( $this, 'display_main_page' )
		);

		add_submenu_page(
			'masteriyo',
			esc_html__( 'Dashboard', 'masteriyo' ),
			esc_html__( 'Dashboard', 'masteriyo' ),
			'manage_options',
			'masteriyo#/dashboard',
			array( $this, 'display_main_page' )
		);

		add_submenu_page(
			'masteriyo',
			esc_html__( 'Courses', 'masteriyo' ),
			esc_html__( 'Courses', 'masteriyo' ),
			'manage_options',
			'masteriyo#/courses',
			array( $this, 'display_main_page' )
		);

		add_submenu_page(
			'masteriyo',
			esc_html__( 'Categories', 'masteriyo' ),
			esc_html__( 'Categories', 'masteriyo' ),
			'manage_options',
			'masteriyo#/categories',
			array( $this, 'display_main_page' )
		);

		add_submenu_page(
			'masteriyo',
			esc_html__( 'Tags', 'masteriyo' ),
			esc_html__( 'Tags', 'masteriyo' ),
			'manage_options',
			'masteriyo#/tags',
			array( $this, 'display_main_page' )
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
	private function init_hooks() {
		add_action( 'admin_menu', array( $this, 'init_menus' ) );
	}

	/**
	 * Display main page.
	 *
	 * @return void
	 */
	public function display_main_page() {
		require_once Constants::get( 'MASTERIYO_PLUGIN_DIR' ). '/templates/masteriyo.php';
	}
}
