<?php
/**
 * Plugin Name:     Masteriyo LMS
 * Plugin URI:      https://example.com
 * Description:     WordPress Learing Mangement System(LMS) plugin.
 * Author:          wp-plugin
 * Author URI:      https://example.com
 * Version:         0.1.0
 * Text Domain:     masteriyo
 * Domain Path:     /languages
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

use ThemeGrill\Masteriyo\Masteriyo;
use ThemeGrill\Masteriyo\Repository\CourseRepository;
use ThemeGrill\Masteriyo\Repository\SectionRepository;
use ThemeGrill\Masteriyo\Repository\LessonRepository;
use ThemeGrill\Masteriyo\Repository\QuizRepository;
use ThemeGrill\Masteriyo\Repository\CourseCategoryRepository;
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
	new League\Container\ReflectionContainer()
);

$masteriyo_container->add(
	\ThemeGrill\Masteriyo\Cache\CacheInterface::class,
	function() {
		return apply_filters( 'masteriyo_cache', Cache::instance() );
	}
);

$masteriyo_container->add( 'course', \ThemeGrill\Masteriyo\Models\Course::class )
	->addArgument( \ThemeGrill\Masteriyo\Repository\CourseRepository::class );

$masteriyo_container->add(
	\ThemeGrill\Masteriyo\Repository\CourseRepository::class,
	function() {
		return apply_filters( 'masteriyo_course_repository', new CourseRepository() );
	}
);

$masteriyo_container->add( 'section', \ThemeGrill\Masteriyo\Models\Section::class )
	->addArgument( \ThemeGrill\Masteriyo\Repository\SectionRepository::class );

$masteriyo_container->add(
	\ThemeGrill\Masteriyo\Repository\SectionRepository::class,
	function() {
		return apply_filters( 'masteriyo_section_repository', new SectionRepository() );
	}
);

$masteriyo_container->add( 'lesson', \ThemeGrill\Masteriyo\Models\Lesson::class )
	->addArgument( \ThemeGrill\Masteriyo\Repository\LessonRepository::class );

$masteriyo_container->add(
	\ThemeGrill\Masteriyo\Repository\LessonRepository::class,
	function() {
		return apply_filters( 'masteriyo_lesson_repository', new LessonRepository() );
	}
);

$masteriyo_container->add( 'quiz', \ThemeGrill\Masteriyo\Models\Quiz::class )
	->addArgument( \ThemeGrill\Masteriyo\Repository\QuizRepository::class );

$masteriyo_container->add(
	\ThemeGrill\Masteriyo\Repository\QuizRepository::class,
	function() {
		return apply_filters( 'masteriyo_quiz_repository', new QuizRepository() );
	}
);

$masteriyo_container->add( 'course_cat', \ThemeGrill\Masteriyo\Models\CourseCategory::class )
	->addArgument( \ThemeGrill\Masteriyo\Repository\CourseCategoryRepository::class );

$masteriyo_container->add( \ThemeGrill\Masteriyo\Repository\CourseCategoryRepository::class, function() {
	return apply_filters( 'masteriyo_course_category_repository', new CourseCategoryRepository );
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
	return Masteriyo::instance();
}

MSYO();
