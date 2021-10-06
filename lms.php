<?php
/**
 * Plugin Name: Masteriyo - LMS for WordPress
 * Plugin URI: https://masteriyo.com/wordpress-lms/
 * Description: A Complete WordPress LMS plugin to create and sell online courses in no time.
 * Author: Masteriyo
 * Author URI: https://masteriyo.com
 * Version: 1.0.6
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * Text Domain: masteriyo
 * Domain Path: /i18n/languages
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

use Masteriyo\Masteriyo;
use League\Container\Container;
use Masteriyo\Activation;

defined( 'ABSPATH' ) || exit;

define( 'MASTERIYO_SLUG', 'masteriyo' );
define( 'MASTERIYO_VERSION', '1.0.6' );
define( 'MASTERIYO_PLUGIN_FILE', __FILE__ );
define( 'MASTERIYO_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'MASTERIYO_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'MASTERIYO_ASSETS', dirname( __FILE__ ) . '/assets' );
define( 'MASTERIYO_TEMPLATES', dirname( __FILE__ ) . '/templates' );
define( 'MASTERIYO_LANGUAGES', dirname( __FILE__ ) . '/i18n/languages' );
define( 'MASTERIYO_PLUGIN_REL_LANGUAGES_PATH', 'i18n/languages' );

/**
 * Include the autoloader.
 */
require_once dirname( __FILE__ ) . '/vendor/autoload.php';

/**
 * Bootstrap the application.
 */
$GLOBALS['masteriyo'] = require_once dirname( __FILE__ ) . '/bootstrap/app.php';

// Initialize the application.
$masteriyo->get( 'app' );

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
