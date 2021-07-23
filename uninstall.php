<?php
/**
 * Masteriyo Uninstall
 *
 * Uninstalling Masteriyo deletes user roles, pages, tables, and options.
 *
 * @package ThemeGrill\Masteriyo\Uninstaller
 * @version 0.1.0
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

global $wpdb, $wp_version;

if ( defined( 'MASTERIYO_REMOVE_ALL_DATA' ) && true === MASTERIYO_REMOVE_ALL_DATA ) {

}
