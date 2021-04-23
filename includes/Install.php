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
		self::init_db();
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
			key CHAR(32) UNIQUE NOT NULL,
			data LONGTEXT NOT NULL,
			expiry BIGINT UNSIGNED NOT NULL,
			PRIMARY KEY (id)
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
			id BIGINT UNSIGNED AUTO_INCREMENT,
			order_id BIGINT UNSIGNED NOT NULL,
			product_id BIGINT UNSIGNED NOT NULL,
			name text,
			type varchar(200),
			quantity BIGINT,
			tax BIGINT,
			total BIGINT,
			PRIMARY KEY (id)
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
			PRIMARY KEY (meta_id)
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
				'course-list'        => array(
					'name'    => _x( 'course-list', 'Page slug', 'masteriyo' ),
					'title'   => _x( 'Course List', 'Page title', 'masteriyo' ),
					'content' => '',
				),
				'myaccount'          => array(
					'name'    => _x( 'myaccount', 'Page slug', 'masteriyo' ),
					'title'   => _x( 'My Masteriyo Acoount', 'Page title', 'masteriyo' ),
					'content' => '<!-- wp:shortcode -->[' . apply_filters( 'masteriyo_myaccount_shortcode_tag', 'masteriyo_myaccount' ) . ']<!-- /wp:shortcode -->',
				),
				'masteriyo-checkout' => array(
					// Checkout slug is 'masteriyo-checkout' as 'checkout' slug might be used by other plugins like WooCommerce.
					'name'    => _x( 'masteriyo-checkout', 'Page slug', 'masteriyo' ),
					'title'   => _x( 'Masteriyo Checkout', 'Page title', 'masteriyo' ),
					'content' => '<!-- wp:shortcode -->[' . apply_filters( 'masteriyo_checkout_shortcode_tag', 'masteriyo_checkout' ) . ']<!-- /wp:shortcode -->',
				),
			)
		);

		foreach ( $pages as $key => $page ) {
			masteriyo_create_page( esc_sql( $page['name'] ), 'masteriyo_' . $key . '_page_id', $page['title'], $page['content'], ! empty( $page['parent'] ) ? masteriyo_get_page_id( $page['parent'] ) : '' );
		}
	}
}

