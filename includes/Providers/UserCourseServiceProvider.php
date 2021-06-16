<?php
/**
 * Course progress service provider.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Providers
 */

namespace ThemeGrill\Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use ThemeGrill\Masteriyo\Models\UserCourse;
use ThemeGrill\Masteriyo\Repository\UserCourseRepository;
use ThemeGrill\Masteriyo\RestApi\Controllers\Version1\UserCourseController;

class UserCourseServiceProvider extends AbstractServiceProvider {
	/**
	 * The provided array is a way to let the container
	 * know that a service is provided by this service
	 * provider. Every service that is registered via
	 * this service provider must have an alias added
	 * to this array or it will be ignored
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $provides = array(
		'user-course',
		'user-course.store',
		'user-course.rest',
		'\ThemeGrill\Masteriyo\RestApi\Controllers\Version1\CourseProgressController',
	);

	/**
	 * This is where the magic happens, within the method you can
	 * access the container and register or retrieve anything
	 * that you need to, but remember, every alias registered
	 * within this method must be declared in the `$provides` array.
	 *
	 * @since 0.1.0
	 */
	public function register() {
		$this->getContainer()->add( 'user-course.store', CourseProgressRepository::class );

		$this->getContainer()
			->add( 'user-course.rest', CourseProgressController::class )
			->addArgument( 'permission' );

		$this->getContainer()
			->add( '\ThemeGrill\Masteriyo\RestApi\Controllers\Version1\CourseProgressController' )
			->addArgument( 'permission' );

		$this->getContainer()
			->add( 'user-course', CourseProgress::class )
			->addArgument( 'user-course.store' );
	}
}
