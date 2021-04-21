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
		self::create_roles();
		// self::init_db();
		self::create_pages();
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
		dbDelta( self::get_session_table_schema( $charset_collate, $base_prefix ) );
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
			`id` BIGINT UNSIGNED AUTO_INCREMENT,
			`key` CHAR(32) UNIQUE NOT NULL,
			`data` LONGTEXT NOT NULL,
			`expiry` BIGINT UNSIGNED NOT NULL,
			PRIMARY KEY (id)
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
		foreach ( self::get_roles() as $role_slug => $role ) {
			add_role( $role_slug, $role['display_name'], $role['capabilities'] );
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
					'capabilities' => get_role( 'editor' )->capabilities,
				),
				'masteriyo_instructor' => array(
					'display_name' => esc_html__( 'Masteriyo Instructor', 'masteriyo' ),
					'capabilities' => get_role( 'author' )->capabilities,
				),
				'masteriyo_student'    => array(
					'display_name' => esc_html__( 'Masteriyo Student', 'masteriyo' ),
					'capabilities' => get_role( 'contributor' )->capabilities,
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

	/**
	 * Create pages that the plugin relies on, storing page IDs in variables.
	 *
	 * @since 0.1.0 
	 */
	public static function create_pages() {
		include_once dirname( __FILE__ ) . '/Helper/Core.php';

		$pages = apply_filters(
			'masteriyo_create_pages',
			array(
				'course-list' => array(
					'name'    => _x( 'course-list', 'Page slug', 'masteriyo' ),
					'title'   => _x( 'Course List', 'Page title', 'masteriyo' ),
					'content' => '',
				),
			)
		);

		foreach ( $pages as $key => $page ) {
			masteriyo_create_page( esc_sql( $page['name'] ), 'masteriyo_' . $key . '_page_id', $page['title'], $page['content'], ! empty( $page['parent'] ) ? masteriyo_get_page_id( $page['parent'] ) : '' );
		}
	}
}

