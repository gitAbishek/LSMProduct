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
	 * Assign core capabilities to admin role.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public static function assign_admin_capabilities() {
		if ( ! class_exists( 'WP_Roles' ) ) {
			return;
		}

		$capabilities = self::get_admin_capabilities();

		foreach ( $capabilities as $cap => $bool ) {
			wp_roles()->add_cap( 'administrator', $cap );
		}
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

		$capabilities = self::get_admin_capabilities();

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
					'capabilities' => self::get_manager_capabilities(),
				),
				'masteriyo_instructor' => array(
					'display_name' => esc_html__( 'Masteriyo Instructor', 'masteriyo' ),
					'capabilities' => self::get_instructor_capabilities(),
				),
				'masteriyo_student'    => array(
					'display_name' => esc_html__( 'Masteriyo Student', 'masteriyo' ),
					'capabilities' => self::get_student_capabilities(),
				),
			)
		);
	}

	/**
	 * Get masteriyo manager capabilites.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public static function get_manager_capabilities() {
		$core_capabilities = array(
			'read_users',

			'delete_course',
			'delete_courses',
			'delete_others_courses',
			'delete_published_courses',
			'read_courses',

			'delete_section',
			'delete_sections',
			'delete_others_sections',
			'delete_published_sections',
			'read_sections',

			'delete_lesson',
			'delete_lessons',
			'delete_others_lessons',
			'delete_published_lessons',
			'read_lessons',

			'delete_quiz',
			'delete_quizes',
			'delete_others_quizes',
			'delete_published_quizes',
			'read_quizes',

			'delete_question',
			'delete_questions',
			'delete_others_questions',
			'delete_published_questions',
			'read_questions',

			'manage_masteriyo_setting',

			'delete_order',
			'delete_orders',
			'delete_others_orders',
			'delete_published_orders',
			'read_orders',
			'read_others_orders',
			'edit_order',
			'edit_orders',
			'edit_others_orders',
			'edit_published_orders',

			'delete_faq',
			'delete_faqs',
			'delete_others_faqs',
			'delete_published_faqs',
			'read_faqs',

			'delete_course_review',
			'delete_course_reviews',
			'delete_others_course_reviews',
			'delete_published_course_reviews',
			'read_course_reviews',
			'edit_course_review',
			'edit_course_reviews',
			'edit_others_course_reviews',
			'edit_published_course_reviews',
		);

		return apply_filters(
			'masteriyo_get_manager_capabilities',
			array_merge(
				array_fill_keys( $core_capabilities, true ),
				get_role( 'editor' )->capabilities
			)
		);
	}

	/**
	 * Get masteriyo instructor capabilites.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public static function get_instructor_capabilities() {
		$core_capabilities = array(
			'read_users',

			'publish_courses',
			'delete_courses',
			'delete_published_courses',
			'read_courses',
			'edit_course',
			'edit_courses',
			'edit_published_courses',

			'publish_lessons',
			'delete_lessons',
			'delete_published_lessons',
			'delete_lesson',
			'read_lessons',
			'edit_lesson',
			'edit_lessons',
			'edit_published_lessons',

			'publish_sections',
			'delete_sections',
			'delete_published_sections',
			'delete_section',
			'read_sections',
			'edit_section',
			'edit_sections',
			'edit_published_sections',

			'publish_quizes',
			'delete_quizes',
			'delete_published_quizes',
			'delete_quiz',
			'read_quizes',
			'edit_quize',
			'edit_quizes',
			'edit_published_quizes',

			'publish_questions',
			'delete_questions',
			'delete_published_questions',
			'delete_question',
			'read_questions',
			'edit_question',
			'edit_questions',
			'edit_published_questions',

			'publish_faqs',
			'delete_faqs',
			'delete_published_faqs',
			'delete_faq',
			'read_faqs',
			'edit_faq',
			'edit_faqs',
			'edit_published_faqs',

			'read_course_reviews',
		);

		return apply_filters(
			'masteriyo_get_instructor_capabilities',
			array_merge(
				array_fill_keys( $core_capabilities, true ),
				get_role( 'author' )->capabilities
			)
		);
	}

	/**
	 * Get masteriyo student capabilites.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public static function get_student_capabilities() {
		$core_capabilities = array(
			'read_users',
			'read_courses',
			'read_lessons',
			'read_sections',
			'read_quizes',
			'read_questions',
			'read_faqs',

			'publish_orders',
			'delete_orders',
			'delete_published_orders',
			'delete_order',
			'read_orders',
			'edit_order',
			'edit_orders',
			'edit_published_orders',

			'publish_course_reviews',
			'delete_course_reviews',
			'delete_published_course_reviews',
			'delete_course_review',
			'read_course_reviews',
			'edit_course_review',
			'edit_course_reviews',
			'edit_published_course_reviews',
		);

		return apply_filters(
			'masteriyo_get_student_capabilities',
			array_merge(
				array_fill_keys( $core_capabilities, true ),
				get_role( 'contributor' )->capabilities
			)
		);
	}

	/**
	 * Get admin's core capabilites.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public static function get_admin_capabilities() {
		$core_capabilities = array(
			'delete_course',
			'delete_courses',
			'delete_others_courses',
			'delete_published_courses',
			'read_courses',

			'delete_lesson',
			'delete_lessons',
			'delete_others_lessons',
			'delete_published_lessons',
			'read_lessons',

			'delete_section',
			'delete_sections',
			'delete_others_sections',
			'delete_published_sections',
			'read_sections',

			'delete_quiz',
			'delete_quizes',
			'delete_others_quizes',
			'delete_published_quizes',
			'read_quizes',

			'delete_question',
			'delete_questions',
			'delete_others_questions',
			'delete_published_questions',
			'read_questions',

			'manage_masteriyo_setting',

			'delete_order',
			'delete_orders',
			'delete_others_orders',
			'delete_published_orders',
			'read_orders',
			'read_others_orders',
			'edit_order',
			'edit_orders',
			'edit_others_orders',
			'edit_published_orders',

			'delete_faq',
			'delete_faqs',
			'delete_others_faqs',
			'delete_published_faqs',
			'read_faqs',

			'delete_course_review',
			'delete_course_reviews',
			'delete_others_course_reviews',
			'delete_published_course_reviews',
			'read_course_reviews',
			'edit_course_review',
			'edit_course_reviews',
			'edit_others_course_reviews',
			'edit_published_course_reviews',
		);

		return apply_filters(
			'masteriyo_get_administrator_capabilities',
			array_fill_keys( $core_capabilities, true ),
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

