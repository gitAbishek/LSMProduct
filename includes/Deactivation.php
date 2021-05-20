<?php
/**
 * Deactivation class.
 *
 * @since 0.1.0
 */

namespace ThemeGrill\Masteriyo;

class Deactivation {

	/**
	 * Initialization.
	 *
	 * @since 0.1.0
	 */
	public static function init() {
		register_deactivation_hook( Constants::get('MASTERIYO_PLUGIN_FILE'), array( __CLASS__, 'on_deactivate' ) );
	}

	/**
	 * Callback for plugin deactivation hook.
	 *
	 * @since 0.1.0
	 */
	public static function on_deactivate() {
		self::remove_roles();
		self::remove_core_capabilities_from_admin();
	}

	/**
	 * Remove roles.
	 *
	 * @since 0.1.0
	 */
	public static function remove_roles() {
		foreach ( Roles::get_all() as $role_slug => $role ) {
			remove_role( $role_slug );
		}
	}

	/**
	 * Remove core capabilities from admin role.
	 *
	 * @since 0.1.0
	 */
	public static function remove_core_capabilities_from_admin() {
		if ( ! class_exists( 'WP_Roles' ) ) {
			return;
		}

		$capabilities = Capabilities::get_admin_capabilities();

		foreach ( $capabilities as $cap => $bool ) {
			wp_roles()->remove_cap( 'administrator', $cap );
		}
	}
}
