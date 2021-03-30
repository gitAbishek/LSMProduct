<?php
/**
 * Plugin Name:     Masteriyo LMS
 * Plugin URI:      https://example.com
 * Description:     WordPress Learing Mangement System(LMS) plugin.
 * Author:          ThemeGrill
 * Author URI:      https://themegrill.com
 * Version:         0.1.0
 * Text Domain:     masteriyo
 * Domain Path:     /i18n/languages
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

use ThemeGrill\Masteriyo\Masteriyo;
use League\Container\Container;

defined( 'ABSPATH' ) || exit;

define( 'MASTERIYO_SLUG', 'masteriyo' );
define( 'MASTERIYO_VERSION', '0.1.0' );
define( 'MASTERIYO_PLUGIN_FILE', __FILE__ );
define( 'MASTERIYO_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'MASTERIYO_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'MASTERIYO_ASSETS', dirname( __FILE__ ) . '/assets' );
define( 'MASTERIYO_TEMPLATES', dirname( __FILE__ ) . '/templates' );
define( 'MASTERIYO_LANGUAGES', dirname( __FILE__ ) . '/i18n/languages' );
define( 'MASTERIYO_PLUGIN_REL_LANGUAGES_PATH', 'i18n/languages' );

add_filter( 'masteriyo_get_myaccount_page_id', function() {
	return 69;
});

/**
 * Include the autoloader.
 */
require_once dirname( __FILE__ ) . '/vendor/autoload.php';

/**
 * Bootstrap the appplication.
 */
$masteriyo = require_once dirname( __FILE__ ) . '/bootstrap/app.php';

/**
 * Return the service container.
 *
 * @since 0.1.0
 *
 * @param string $class Class name or alias.
 * @return ThemeGrill\Masteriyo\Masteriyo
 */
function masteriyo( $class = '' ) {
	global $masteriyo;

	return empty( $class ) ? $masteriyo : $masteriyo->get( $class );
}
