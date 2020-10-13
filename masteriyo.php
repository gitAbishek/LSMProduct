<?php
/**
 * Plugin Name:     Masteriyo LMS
 * Plugin URI:      https://example.com
 * Description:     WordPress Learing Mangement System(LMS) plugin.
 * Author:          wp-plugin
 * Author URI:      https://example.com
 * Version: 		0.1.0
 * Text Domain:     masteriyo
 * Domain Path:     /languages
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

use ThemeGrill\Masteriyo\Masteriyo;
use ThemeGrill\Masteriyo\Repository\CourseRepository;
use League\Container\Container;
use ThemeGrill\Masteriyo\Cache\Cache;

defined( 'ABSPATH' ) || exit;

define( 'MASTERIYO_SLUG', 'masteriyo' );
define( 'MASTERIYO_VERSION', '0.1.0' );
define( 'MASTERIYO_PLUGIN_FILE', __FILE__ );
define( 'MASTERIYO_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'MASTERIYO_ASSETS', dirname( __FILE__ ) . '/assets' );
define( 'MASTERIYO_TEMPLATES', dirname( __FILE__ ) . '/templates' );
define( 'MASTERIYO_LANGUAGES', dirname( __FILE__ ) . '/i18n/languages' );

require_once dirname( __FILE__ ) . '/vendor/autoload.php';

$masteriyo_container = new Container();

// register the reflection container as a delegate to enable auto wiring
$masteriyo_container->delegate(
    new League\Container\ReflectionContainer
);

$masteriyo_container->add( \ThemeGrill\Masteriyo\Cache\CacheInterface::class, function() {
	return apply_filters( 'masteriyo_cache', Cache::instance() );
} );

$masteriyo_container->add( 'course', \ThemeGrill\Masteriyo\Models\Course::class )
	->addArgument( \ThemeGrill\Masteriyo\Repository\CourseRepository::class );

$masteriyo_container->add( \ThemeGrill\Masteriyo\Repository\CourseRepository::class, function() {
	return apply_filters( 'masteriyo_course_repository', new CourseRepository );
} );

$_GLOBALS['masteriyo_container'] = $masteriyo_container;

/**
 * Returns the main instance of Masteriyo.
 *
 * @since 0.1.0
 *
 * @return ThemeGrill\Masteriyo\Masteriyo
 */
function MSYO() {
	return Masteriyo:: instance();
}

MSYO();
