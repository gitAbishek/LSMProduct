<?php
/**
 * Install
 *
 * @since 1.0.0
 */

namespace Masteriyo;

class Install {

	/**
	 * Initialization.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init() {
		self::install();

		$default_role = get_option( 'default_role', 'student' );

		self::remove_roles();
		self::create_roles();
		self::create_difficulties();

		update_option( 'default_role', $default_role );
	}

	/**
	 * Update Masteriyo information.
	 *
	 * @since 1.0.0
	 */
	public static function install() {
		$masteriyo_version = get_option( 'masteriyo_plugin_version' );

		if ( empty( $masteriyo_version ) ) {
			/**
			 * Filters boolean value to enable/disable setup wizard. True for enable.
			 *
			 * @since 1.0.0
			 *
			 * @param boolean $enable True to enable setup wizard.
			 */
			$enable_setup_wizard = apply_filters( 'masteriyo_enable_setup_wizard', true );

			if ( empty( $masteriyo_version ) && $enable_setup_wizard ) {
				set_transient( '_masteriyo_activation_redirect', 1, 30 );
			}
		}
		update_option( 'masteriyo_plugin_version', MASTERIYO_VERSION );

		// Save the install date.
		if ( false === get_option( 'masteriyo_install_date' ) ) {
			update_option( 'masteriyo_install_date', current_time( 'mysql', true ) );
		}

		flush_rewrite_rules();
	}

	/**
	 * Remove previous roles.
	 *
	 * @since 1.3.0
	 */
	private static function remove_roles() {
		// Remove the masteriyo manager role for now.
		remove_role( 'masteriyo_manager' );

		foreach ( Roles::get_all() as $role_slug => $role ) {
			remove_role( $role_slug );
		}
	}

	/**
	 * Create roles.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private static function create_roles() {
		foreach ( Roles::get_all() as $role_slug => $role ) {
			add_role( $role_slug, $role['display_name'], $role['capabilities'] );
		}
	}

	/**
	 * Create default difficulties.
	 *
	 * @since 1.0.0
	 */
	public static function create_difficulties() {
		$activation = get_option( 'masteriyo_first_time_activation_flag', false );
		$activation = masteriyo_string_to_bool( $activation );

		if ( $activation ) {
			return;
		}

		$terms = array(
			'beginner'     => esc_html__( 'Beginner', 'masteriyo' ),
			'intermediate' => esc_html__( 'Intermediate', 'masteriyo' ),
			'expert'       => esc_html__( 'Expert', 'masteriyo' ),
		);

		foreach ( $terms as $slug => $name ) {
			$term = get_term_by( 'slug', $name, 'course_difficulty' );

			if ( false === $term ) {
				wp_insert_term( $name, 'course_difficulty' );
			}
		}
	}
}

