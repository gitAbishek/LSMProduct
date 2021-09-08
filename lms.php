<?php
/**
 * Plugin Name:     Masteriyo - LMS for WordPress
 * Plugin URI:      https://masteriyo.com/wordpress-lms/
 * Description:     A Complete WordPress LMS plugin to create and sell online courses in no time.
 * Author:          Masteriyo
 * Author URI:      https://masteriyo.com
 * Version:         0.1.0
 * Text Domain:     masteriyo
 * Domain Path:     /i18n/languages
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

use Masteriyo\Masteriyo;
use League\Container\Container;
use Masteriyo\Activation;

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

add_action(
	'init',
	function() {
		if ( isset( $_GET['test'] ) ) {
			// masteriyo( 'email.customer-new-account' )->trigger( 1 );
			masteriyo( 'email.password-reset' )->trigger( 1, 'abc' );
			// masteriyo('email.new-order')->trigger(order_id_here);
			// masteriyo('email.order-cancelled')->trigger(order_id_here);
			// masteriyo('email.order-onhold')->trigger(order_id_here);
			// masteriyo('email.order-completed')->trigger(order_id_here);
		}
	}
);

/**
 * Include the autoloader.
 */
require_once dirname( __FILE__ ) . '/vendor/autoload.php';

/**
 * Bootstrap the appplication.
 */
$GLOBALS['masteriyo'] = require_once dirname( __FILE__ ) . '/bootstrap/app.php';

// Initialize the application.
$masteriyo->get( 'app' );

/**
 * Return the service container.
 *
 * @since 0.1.0
 *
 * @param string $class Class name or alias.
 * @return Masteriyo\Masteriyo
 */
function masteriyo( $class = 'app' ) {
	global $masteriyo;

	return empty( $class ) ? $masteriyo : $masteriyo->get( $class );
}
