<?php
/**
 * Install
 *
 * @since 0.1.0
 */

namespace ThemeGrill\Masteriyo;

class Install {

	/**
	 * Initialization.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public static function init() {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		self::init_db();
		self::create_roles();

		register_deactivation_hook( Constants::get('MASTERIYO_PLUGIN_FILE'), array( __CLASS__, 'remove_roles' ) );
		register_deactivation_hook( Constants::get('MASTERIYO_PLUGIN_FILE'), array( __CLASS__, 'remove_admin_capabilities' ) );
	}

	/**
	 * Initialize database.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private static function init_db() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$base_prefix     = $wpdb->base_prefix;

		dbDelta( self::get_question_table_schema( $charset_collate, $base_prefix ) );
		// dbDelta( self::get_session_table_schema( $charset_collate, $base_prefix ) );
		dbDelta( self::get_order_items_table_schema( $charset_collate, $base_prefix ) );
		dbDelta( self::get_order_itemmeta_table_schema( $charset_collate, $base_prefix ) );
	}

	/**
	 * Get question table schema.
	 *
	 * @since 0.1.0
	 *
	 * @param string $charset_collate   Database charset collate.
	 * @param string $base_prefix       Table prefix.
	 *
	 * @return string
	 */
	private static function get_question_table_schema( $charset_collate, $base_prefix ) {
		$sql = "CREATE TABLE `{$base_prefix}masteriyo_questions` (
			id BIGINT UNSIGNED AUTO_INCREMENT,
			quiz_id BIGINT UNSIGNED,
			title text,
			description LONGTEXT,
			sort SMALLINT UNSIGNED,
			points SMALLINT UNSIGNED,
			correct_msg TEXT,
			incorrect_msg TEXT,
			hint_msg TEXT,
			hint_enabled TINYINT(1),
			answer_type varchar(50),
			answer_required TINYINT(1),
			randomize TINYINT(1),
			PRIMARY KEY (id)
		) $charset_collate;";

		return $sql;
	}

	/**
	 * Get session table schema.
	 *
	 * @since 0.1.0
	 *
	 * @param string $charset_collate   Database charset collate.
	 * @param string $base_prefix       Table prefix.
	 *
	 * @return string
	 */
	private static function get_session_table_schema( $charset_collate, $base_prefix ) {
		$sql = "CREATE TABLE `{$base_prefix}masteriyo_sessions` (
			id BIGINT UNSIGNED AUTO_INCREMENT,
			`key` CHAR(32) UNIQUE NOT NULL,
			`data` LONGTEXT NOT NULL,
			`expiry` BIGINT UNSIGNED NOT NULL,
			PRIMARY KEY (id)
		) {$charset_collate};";

		return $sql;
	}

	/**
	 * Get order items table schema.
	 *
	 * @since 0.1.0
	 *
	 * @param string $charset_collate   Database charset collate.
	 * @param string $base_prefix       Table prefix.
	 *
	 * @return string
	 */
	private static function get_order_items_table_schema( $charset_collate, $base_prefix ) {
		$sql = "CREATE TABLE `{$base_prefix}masteriyo_order_items` (
			order_item_id BIGINT UNSIGNED AUTO_INCREMENT,
			order_item_name text NOT NULL,
			order_item_type  varchar(200) NOT NULL,
			order_id BIGINT UNSIGNED NOT NULL,
			PRIMARY KEY (order_item_id),
			KEY order_id (order_id),
			KEY order_item_type (order_item_type)
		) $charset_collate;";

		return $sql;
	}

	/**
	 * Get order item meta table schema.
	 *
	 * @since 0.1.0
	 *
	 * @param string $charset_collate   Database charset collate.
	 * @param string $base_prefix       Table prefix.
	 *
	 * @return string
	 */
	private static function get_order_itemmeta_table_schema( $charset_collate, $base_prefix ) {
		$sql = "CREATE TABLE `{$base_prefix}masteriyo_order_itemmeta` (
			meta_id BIGINT UNSIGNED AUTO_INCREMENT,
			order_item_id BIGINT UNSIGNED NOT NULL,
			meta_key varchar(200) NOT NULL,
			meta_value text,
			PRIMARY KEY (meta_id),
			KEY order_item_id (order_item_id),
			KEY meta_key (meta_key(32))
		) $charset_collate;";

		return $sql;
	}

	/**
	 * Remove core capabilities from admin role.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public static function remove_admin_capabilities() {
		if ( ! class_exists( 'WP_Roles' ) ) {
			return;
		}

		$capabilities = Capabilities::get_admin_capabilities();

		foreach ( $capabilities as $cap => $bool ) {
			wp_roles()->remove_cap( 'administrator', $cap );
		}
	}

	/**
	 * Create roles.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private static function create_roles() {
		foreach ( self::get_roles() as $role_slug => $role ) {
			add_role( $role_slug, $role['display_name'], $role['capabilities'] );
		}
	}

	/**
	 * Remove roles.
	 */
	public static function remove_roles() {
		foreach ( self::get_roles() as $role_slug => $role ) {
			remove_role( $role_slug );
		}
	}

	/**
	 * Retun roles.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public static function get_roles() {
		return apply_filters(
			'masteriyo_get_roles',
			array(
				'masteriyo_manager'    => array(
					'display_name' => esc_html__( 'Masteriyo Manager', 'masteriyo' ),
					'capabilities' => Capabilities::get_manager_capabilities(),
				),
				'masteriyo_instructor' => array(
					'display_name' => esc_html__( 'Masteriyo Instructor', 'masteriyo' ),
					'capabilities' => Capabilities::get_instructor_capabilities(),
				),
				'masteriyo_student'    => array(
					'display_name' => esc_html__( 'Masteriyo Student', 'masteriyo' ),
					'capabilities' => Capabilities::get_student_capabilities(),
				),
			)
		);
	}

	/**
	 * Return the list of tables.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public static function get_tables() {
		global $wpdb;

		return array(
			"{$wpdb->prefix}masteriyo_questions",
			"{$wpdb->prefix}masteriyo_answers",
		);
	}

	/**
	 * Drop tables.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public static function drop_tables() {
		global $wpdb;

		$tables = self::get_tables();

		foreach ( $tables as $table ) {
			$wpdb->query( 'DROP TABLE IF EXISTS ' . esc_sql( $table ) );
		}
	}
}

