<?php
/**
 * Masteriyo Uninstall
 *
 * Uninstalling Masteriyo deletes user roles, pages, tables, and options.
 *
 * @package Masteriyo\Uninstaller
 * @version 1.0.0
 */

use Masteriyo\Enums\CommentType;
use Masteriyo\Install;
use Masteriyo\PostType\PostType;
use Masteriyo\Taxonomy\Taxonomy;

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

defined( 'MASTERIYO_SLUG' ) || define( 'MASTERIYO_SLUG', 'masteriyo' );
defined( 'MASTERIYO_VERSION' ) || define( 'MASTERIYO_VERSION', '1.5.20' );
defined( 'MASTERIYO_PLUGIN_FILE' ) || define( 'MASTERIYO_PLUGIN_FILE', __FILE__ );
defined( 'MASTERIYO_PLUGIN_BASENAME' ) || define( 'MASTERIYO_PLUGIN_BASENAME', plugin_basename( MASTERIYO_PLUGIN_FILE ) );
defined( 'MASTERIYO_PLUGIN_DIR' ) || define( 'MASTERIYO_PLUGIN_DIR', dirname( MASTERIYO_PLUGIN_FILE ) );
defined( 'MASTERIYO_ASSETS' ) || define( 'MASTERIYO_ASSETS', dirname( MASTERIYO_PLUGIN_FILE ) . '/assets' );
defined( 'MASTERIYO_TEMPLATES' ) || define( 'MASTERIYO_TEMPLATES', dirname( MASTERIYO_PLUGIN_FILE ) . '/templates' );
defined( 'MASTERIYO_LANGUAGES' ) || define( 'MASTERIYO_LANGUAGES', dirname( MASTERIYO_PLUGIN_FILE ) . '/i18n/languages' );

require_once dirname( __FILE__ ) . '/vendor/autoload.php';

if ( ! function_exists( 'masteriyo' ) ) {
	$GLOBALS['masteriyo'] = require_once dirname( __FILE__ ) . '/bootstrap/app.php';

	/**
	 * Return the service container.
	 *
	 * @since 1.0.0
	 *
	 * @param string $class Class name or alias.
	 *
	 * @return \Masteriyo\Masteriyo
	 */
	function masteriyo( $class = 'app' ) {
		global $masteriyo;
		return empty( $class ) ? $masteriyo : $masteriyo->get( $class );
	}
}

if ( masteriyo_string_to_bool( masteriyo_get_setting( 'advance.uninstall.remove_data' ) ) ) {
	global $wpdb;

	Install::remove_roles();

	// Pages.
	wp_trash_post( masteriyo_get_setting( 'general.pages.courses_page_id' ) );
	wp_trash_post( masteriyo_get_setting( 'general.pages.account_page_id' ) );
	wp_trash_post( masteriyo_get_setting( 'general.pages.checkout_page_id' ) );
	wp_trash_post( masteriyo_get_setting( 'general.pages.learn_page_id' ) );
	wp_trash_post( masteriyo_get_setting( 'general.pages.instructor_registration_page_id' ) );

	// Tables.
	$tables = Install::get_tables();

	foreach ( $tables as $table ) {
		$wpdb->query( "DROP TABLE IF EXISTS {$table}" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	}

	// Delete options.
	$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'masteriyo\_%';" );

	// Delete usermeta.
	$wpdb->query( "DELETE FROM $wpdb->usermeta WHERE meta_key LIKE 'masteriyo\_%';" );

	// Delete our data from the post and post meta tables, and remove any additional tables we created.
	$post_types = masteriyo_join_array( ( new PostType() )->all(), ', ', "'{value}'" );
	$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_type IN ( {$post_types} );" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	$wpdb->query( "DELETE meta FROM {$wpdb->postmeta} meta LEFT JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id WHERE posts.ID IS NULL;" );

	$comment_types = masteriyo_join_array( CommentType::all(), ', ', "'{value}'" );
	$wpdb->query( "DELETE FROM {$wpdb->comments} WHERE comment_type IN ( {$comment_types} );" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	$wpdb->query( "DELETE meta FROM {$wpdb->commentmeta} meta LEFT JOIN {$wpdb->comments} comments ON comments.comment_ID = meta.comment_id WHERE comments.comment_ID IS NULL;" );

	// Delete term taxonomies.
	foreach ( Taxonomy::all() as $_taxonomy ) {
		$wpdb->delete(
			$wpdb->term_taxonomy,
			array(
				'taxonomy' => $_taxonomy,
			)
		);
	}

	// Delete orphan relationships.
	$wpdb->query( "DELETE tr FROM {$wpdb->term_relationships} tr LEFT JOIN {$wpdb->posts} posts ON posts.ID = tr.object_id WHERE posts.ID IS NULL;" );

	// Delete orphan terms.
	$wpdb->query( "DELETE t FROM {$wpdb->terms} t LEFT JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id WHERE tt.term_id IS NULL;" );

	// Delete orphan term meta.
	if ( ! empty( $wpdb->termmeta ) ) {
		$wpdb->query( "DELETE tm FROM {$wpdb->termmeta} tm LEFT JOIN {$wpdb->term_taxonomy} tt ON tm.term_id = tt.term_id WHERE tt.term_id IS NULL;" );
	}

	// Clear any cached data that has been removed.
	wp_cache_flush();
}
