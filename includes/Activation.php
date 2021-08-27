<?php
/**
 * Activation class.
 *
 * @since 0.1.0
 */

namespace Masteriyo;

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
		self::attach_placeholder_image();
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
				'learning'    => array(
					'name'         => _x( 'learning', 'Page slug', 'masteriyo' ),
					'title'        => _x( 'Learning', 'Page title', 'masteriyo' ),
					'content'      => '',
					'setting_name' => 'learning_page_id',
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

	/**
	 * Insert masteriyo placeholder image to WP Media library.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public static function attach_placeholder_image() {
		global $wpdb;

		$img_file = masteriyo_get_plugin_url() . '/assets/img/placeholder.jpeg';
		$filename = basename( $img_file );

		$count = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM {$wpdb->postmeta} 
				 WHERE meta_value 
				 LIKE %s",
				"%/{$filename}"
			)
		);

		// Return if image already exists.
		if ( $count > 0 ) {
			return;
		}

		// Get upload directory.
		$upload_dir = wp_upload_dir();
		// Making masteriyo directory on uploads folder.
		$upload_masteriyo_dir = $upload_dir['basedir'] . '/masteriyo';

		if ( ! file_exists( $upload_masteriyo_dir ) ) {
			mkdir( $upload_masteriyo_dir );
		}
		$attach_file = $upload_masteriyo_dir . '/' . sanitize_file_name( $filename );
		$upload      = copy( $img_file, $attach_file );

		if ( $upload ) {
			$wp_filetype = wp_check_filetype( $filename, null );

			$attachment    = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', sanitize_file_name( $filename ) ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			);
			$attachment_id = wp_insert_attachment( $attachment, $attach_file );

			// Update attachment ID.
			update_option( 'masteriyo_placeholder_image', $attachment_id );

			if ( ! is_wp_error( $attachment_id ) ) {
				require_once ABSPATH . 'wp-admin/includes/image.php';
				$attachment_data = wp_generate_attachment_metadata( $attachment_id, $attach_file );
				wp_update_attachment_metadata( $attachment_id, $attachment_data );
			}
		}
	}
}
