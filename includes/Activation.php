<?php
/**
 * Activation class.
 *
 * @since 0.1.0
 */

namespace ThemeGrill\Masteriyo;

class Activation {

	/**
	 * Initialization.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public static function init() {
		register_activation_hook( Constants::get( 'MASTERIYO_PLUGIN_FILE' ), array( __CLASS__, 'on_activate' ) );
	}

	/**
	 * Callback for plugin activation hook.
	 *
	 * @since 0.1.0
	 */
	public static function on_activate() {
		self::create_pages();
		self::assign_core_capabilities_to_admin();
	}

	/**
	 * Create pages that the plugin relies on, storing page IDs in variables.
	 *
	 * @since 0.1.0
	 */
	public static function create_pages() {
		$pages = apply_filters(
			'masteriyo_create_pages',
			array(
				'course-list' => array(
					'name'         => _x( 'courses', 'Page slug', 'masteriyo' ),
					'title'        => _x( 'Course List', 'Page title', 'masteriyo' ),
					'content'      => '',
					'setting_name' => 'course_list_page_id',
				),
				'myaccount'   => array(
					'name'         => _x( 'my-account', 'Page slug', 'masteriyo' ),
					'title'        => _x( 'My Account', 'Page title', 'masteriyo' ),
					'content'      => '<!-- wp:shortcode -->[' . apply_filters( 'masteriyo_myaccount_shortcode_tag', 'masteriyo_myaccount' ) . ']<!-- /wp:shortcode -->',
					'setting_name' => 'myaccount_page_id',
				),
				'checkout'    => array(
					'name'         => _x( 'checkout', 'Page slug', 'masteriyo' ),
					'title'        => _x( 'Checkout', 'Page title', 'masteriyo' ),
					'content'      => '<!-- wp:shortcode -->[' . apply_filters( 'masteriyo_checkout_shortcode_tag', 'masteriyo_checkout' ) . ']<!-- /wp:shortcode -->',
					'setting_name' => 'checkout_page_id',
				),
			)
		);

		foreach ( $pages as $key => $page ) {
			$setting_name = $page['setting_name'];
			$page_id      = masteriyo_create_page( esc_sql( $page['name'] ), $setting_name, $page['title'], $page['content'], ! empty( $page['parent'] ) ? masteriyo_get_page_id( $page['parent'] ) : '' );
			masteriyo_set_setting( "advance.pages.{$setting_name}", $page_id );
		}
	}


	/**
	 * Assign core capabilities to admin role.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public static function assign_core_capabilities_to_admin() {
		if ( ! class_exists( 'WP_Roles' ) ) {
			return;
		}

		$capabilities = Capabilities::get_admin_capabilities();

		foreach ( $capabilities as $cap => $bool ) {
			wp_roles()->add_cap( 'administrator', $cap );
		}
	}
}
