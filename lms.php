<?php
/**
 * Plugin Name: Masteriyo - LMS for WordPress
 * Plugin URI: https://masteriyo.com/wordpress-lms/
 * Description: A Complete WordPress LMS plugin to create and sell online courses in no time.
 * Author: Masteriyo
 * Author URI: https://masteriyo.com
 * Version: 1.5.16
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * Text Domain: masteriyo
 * Domain Path: /i18n/languages
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

use Masteriyo\Masteriyo;

defined( 'ABSPATH' ) || exit;

/**
 * @since 1.4.4 Auto deactivation of free plugin.
 */
if ( in_array( 'learning-management-system-pro/lms.php', get_option( 'active_plugins', array() ), true ) ) {
	add_action(
		'admin_init',
		function() {
			deactivate_plugins( 'learning-management-system/lms.php', true );

			if ( isset( $_GET['activate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				unset( $_GET['activate'] );
			}
		},
		0
	);

	return;
}

if ( ! defined( 'MASTERIYO_SLUG' ) ) {
	define( 'MASTERIYO_SLUG', 'masteriyo' );
}

if ( ! defined( 'MASTERIYO_VERSION' ) ) {
	define( 'MASTERIYO_VERSION', '1.5.16' );
}

if ( ! defined( 'MASTERIYO_PLUGIN_FILE' ) ) {
	define( 'MASTERIYO_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'MASTERIYO_PLUGIN_BASENAME' ) ) {
	define( 'MASTERIYO_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'MASTERIYO_PLUGIN_DIR' ) ) {
	define( 'MASTERIYO_PLUGIN_DIR', dirname( __FILE__ ) );
}

if ( ! defined( 'MASTERIYO_ASSETS' ) ) {
	define( 'MASTERIYO_ASSETS', dirname( __FILE__ ) . '/assets' );
}

if ( ! defined( 'MASTERIYO_TEMPLATES' ) ) {
	define( 'MASTERIYO_TEMPLATES', dirname( __FILE__ ) . '/templates' );
}

if ( ! defined( 'MASTERIYO_LANGUAGES' ) ) {
	define( 'MASTERIYO_LANGUAGES', dirname( __FILE__ ) . '/i18n/languages' );
}

/**
 * Include the autoloader.
 */
require_once dirname( __FILE__ ) . '/vendor/autoload.php';

// Check whether assets are built or not.
if ( masteriyo_is_production() && ! file_exists( dirname( __FILE__ ) . '/assets/js/build/masteriyo-backend.' . MASTERIYO_VERSION . '.js' ) ) {
	add_action(
		'admin_notices',
		function() {
			printf(
				'<div class="notice notice-error is-dismissible"><p><strong>%s </strong>%s</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">%s</span></button></div>',
				esc_html( 'Masteriyo:' ),
				wp_kses_post( 'Assets are need to be built. Run <code>yarn && yarn build</code> from the wp-content/plugins/learning-management-system directory.', 'masteriyo' ),
				esc_html__( 'Dismiss this notice.', 'masteriyo' )
			);
		}
	);

	add_action(
		'admin_init',
		function() {
			deactivate_plugins( plugin_basename( MASTERIYO_PLUGIN_FILE ) );

			if ( isset( $_GET['activate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				unset( $_GET['activate'] );
			}
		},
		0
	);

	return;
}


// Check for the existence of autoloader file.
if ( ! file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	add_action(
		'admin_notices',
		function() {
			printf(
				'<div class="notice notice-error is-dismissible"><p><strong>%s </strong>%s</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">%s</span></button></div>',
				esc_html( 'Masteriyo:' ),
				wp_kses_post( 'Requires autoloader files to work properly. Run <code>composer update</code> from the wp-content/plugins/learning-management-system directory.', 'masteriyo' ),
				esc_html__( 'Dismiss this notice.', 'masteriyo' )
			);
		}
	);

	add_action(
		'admin_init',
		function() {
			deactivate_plugins( plugin_basename( MASTERIYO_PLUGIN_FILE ) );

			if ( isset( $_GET['activate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				unset( $_GET['activate'] );
			}
		}
	);

	return;
}

if ( ! function_exists( 'masteriyo' ) ) {
	/**
	 * Bootstrap the application.
	 */
	$GLOBALS['masteriyo'] = require_once dirname( __FILE__ ) . '/bootstrap/app.php';

	/**
	 * Return the service container.
	 *
	 * @since 1.0.0
	 *
	 * @param string $class Class name or alias.
	 * @return Masteriyo\Masteriyo
	 */
	function masteriyo( $class = 'app' ) {
		global $masteriyo;

		return empty( $class ) ? $masteriyo : $masteriyo->get( $class );
	}

	// Initialize the application.
	$GLOBALS['masteriyo']->get( 'app' );
}
