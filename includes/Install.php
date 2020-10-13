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
		// self::create_roles();
		self::init_db();
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
	}

	/**
	 * Initialize question table.
	 *
	 * @since 0.1.0
	 *
	 * @param string $charset_collate	Database charset collate.
	 * @param string $base_prefix		Table prefix.
	 *
	 * @return void
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
	 * Create roles.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private static function create_roles() {
		$editor_cap = get_role( 'editor' )->capabilities;
		$author_cap = get_role( 'author' )->capabilities;

		add_role( 'masteriyo_student', 'Masteriyo Student', $author_cap );
		add_role( 'masteriyo_instructor', 'Masteriyo Instructor', $editor_cap );
	}

	private function get_student_capabilities() {
		return apply_filters( 'masteriyo_get_student_capabilities', array(
			'delete_courses',
			
		) );
	}

	/**
	 * Retun roles.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function get_roles() {
		return array(
			'masteriyo_manager',
			'masteriyo_instructor',
			'masteriyo_student'
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
			"{$wpdb->prefix}masteriyo_answers"
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
			$wpdb->query( 'DROP TABLE IF EXISTS ' . esc_sql( $table) );
		}
	}
}

