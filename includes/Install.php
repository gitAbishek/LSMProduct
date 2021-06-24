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
		self::install();
		self::init_db();
		self::create_roles();
	}

	/**
	 * Update Masteriyo information.
	 *
	 * @since 0.1.0
	 */
	public static function install() {
		$masteriyo_version = get_option( 'masteriyo_plugin_version' );

		if ( empty( $masteriyo_version ) ) {
			if ( empty( $masteriyo_version ) && apply_filters( 'masteriyo_enable_setup_wizard', true ) ) {
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

		// dbDelta( self::get_question_table_schema( $charset_collate, $base_prefix ) );
		dbDelta( self::get_session_table_schema( $charset_collate, $base_prefix ) );
		dbDelta( self::get_order_items_table_schema( $charset_collate, $base_prefix ) );
		dbDelta( self::get_order_itemmeta_table_schema( $charset_collate, $base_prefix ) );
		dbDelta( self::get_user_activity_table_schema( $charset_collate, $base_prefix ) );
		dbDelta( self::get_user_activitymeta_table_schema( $charset_collate, $base_prefix ) );
		dbDelta( self::get_user_items_table_schema( $charset_collate, $base_prefix ) );
		dbDelta( self::get_user_itemmeta_table_schema( $charset_collate, $base_prefix ) );
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
			`session_id` BIGINT UNSIGNED AUTO_INCREMENT,
			`session_key` CHAR(32) NOT NULL,
			`session_data` LONGTEXT NOT NULL,
			`session_expiry` BIGINT UNSIGNED NOT NULL,
			`user_id` BIGINT UNSIGNED NOT NULL DEFAULT '0',
			`user_agent` LONGTEXT,
			PRIMARY KEY (`session_id`),
			KEY `user_id` (`user_id`),
			UNIQUE KEY `session_key` (`session_key`)
		) $charset_collate;";

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
			order_item_name TEXT NOT NULL,
			order_item_type  VARCHAR(200) NOT NULL,
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
			`meta_id` BIGINT UNSIGNED AUTO_INCREMENT,
			`order_item_id` BIGINT UNSIGNED NOT NULL,
			`meta_key` VARCHAR(255) NOT NULL,
			`meta_value` LONGTEXT,
			PRIMARY KEY (`meta_id`),
			KEY `order_item_id` (`order_item_id`),
			KEY `meta_key` (`meta_key`(191))
		) $charset_collate;";

		return $sql;
	}

	/**
	 * Get user activity table schema.
	 *
	 * @since 0.1.0
	 *
	 * @param string $charset_collate   Database charset collate.
	 * @param string $base_prefix       Table prefix.
	 *
	 * @return string
	 */
	public static function get_user_activity_table_schema( $charset_collate, $base_prefix ) {
		$sql = "CREATE TABLE `{$base_prefix}masteriyo_user_activities` (
			`id` BIGINT UNSIGNED AUTO_INCREMENT,
			`user_id` BIGINT UNSIGNED NOT NULL DEFAULT '0',
			`item_id` BIGINT UNSIGNED NOT NULL DEFAULT '0',
			`activity_type` VARCHAR(20) DEFAULT NULL,
			`activity_status` VARCHAR(20) DEFAULT NULL,
			`parent_id` BIGINT UNSIGNED NOT NULL DEFAULT '0',
			`date_start` datetime DEFAULT '0000-00-00 00:00:00',
			`date_update` datetime DEFAULT '0000-00-00 00:00:00',
			`date_complete` datetime DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY (`id`),
			KEY `user_id` (`user_id`),
			KEY `item_id` (`item_id`),
			KEY `parent_id` (`parent_id`),
			KEY `activity_type` (`activity_type`),
			KEY `activity_status` (`activity_status`),
			KEY `date_start` (`date_start`),
			KEY `date_update` (`date_update`),
			KEY `date_complete` (`date_complete`)
		) $charset_collate;";

		return $sql;
	}


	/**
	 * Get user acitivty item meta table schema.
	 *
	 * @since 0.1.0
	 *
	 * @param string $charset_collate   Database charset collate.
	 * @param string $base_prefix       Table prefix.
	 *
	 * @return string
	 */
	private static function get_user_activitymeta_table_schema( $charset_collate, $base_prefix ) {
		$sql = "CREATE TABLE `{$base_prefix}masteriyo_user_activitymeta` (
			`meta_id` BIGINT UNSIGNED AUTO_INCREMENT,
			`user_activity_id` BIGINT UNSIGNED NOT NULL,
			`meta_key` VARCHAR(255) NOT NULL,
			`meta_value` LONGTEXT,
			PRIMARY KEY (`meta_id`),
			KEY `user_activity_id` (`user_activity_id`),
			KEY `meta_key` (`meta_key`(191))
		) $charset_collate;";

		return $sql;
	}

	/**
	 * Get user items table schema.
	 *
	 * @since 0.1.0
	 *
	 * @param string $charset_collate   Database charset collate.
	 * @param string $base_prefix       Table prefix.
	 *
	 * @return string
	 */
	private static function get_user_items_table_schema( $charset_collate, $base_prefix ) {
		$sql = "CREATE TABLE `{$base_prefix}masteriyo_user_items` (
			`id` BIGINT UNSIGNED AUTO_INCREMENT,
			`user_id` BIGINT UNSIGNED NOT NULL,
			`item_id` BIGINT UNSIGNED NOT NULL,
			`item_type` VARCHAR(255) NOT NULL DEFAULT '',
			`status` VARCHAR(255) NOT NULL DEFAULT '',
			`parent_id` BIGINT UNSIGNED NOT NULL DEFAULT 0,
			`date_start` datetime DEFAULT '0000-00-00 00:00:00',
			`date_modified` datetime DEFAULT '0000-00-00 00:00:00',
			`date_end` datetime DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY (`id`),
			KEY `user_id` (`user_id`),
			KEY `item_id` (`item_id`),
			KEY `parent_id` (`parent_id`),
			KEY `status` (`status`(191)),
			KEY `item_type` (`item_type`(191)),
			KEY `date_start` (`date_start`),
			KEY `date_modified` (`date_modified`),
			KEY `date_end` (`date_end`)
		) $charset_collate;";

		return $sql;
	}

	/**
	 * Get user items meta table schema.
	 *
	 * @since 0.1.0
	 *
	 * @param string $charset_collate   Database charset collate.
	 * @param string $base_prefix       Table prefix.
	 *
	 * @return string
	 */
	private static function get_user_itemmeta_table_schema( $charset_collate, $base_prefix ) {
		$sql = "CREATE TABLE `{$base_prefix}masteriyo_user_itemmeta` (
			`meta_id` BIGINT UNSIGNED AUTO_INCREMENT,
			`user_item_id` BIGINT UNSIGNED NOT NULL,
			`meta_key` VARCHAR(255) NOT NULL,
			`meta_value` LONGTEXT,
			PRIMARY KEY (`meta_id`),
			KEY `user_item_id` (`user_item_id`),
			KEY `meta_key` (`meta_key`(191))
		) $charset_collate;";

		return $sql;
	}

	/**
	 * Create roles.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private static function create_roles() {
		foreach ( Roles::get_all() as $role_slug => $role ) {
			add_role( $role_slug, $role['display_name'], $role['capabilities'] );
		}
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

