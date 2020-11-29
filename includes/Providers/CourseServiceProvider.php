<?php
/**
 * Course model service provider.
 */

namespace ThemeGrill\Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use ThemeGrill\Masteriyo\Models\Course;
use ThemeGrill\Masteriyo\Repository\CourseRepository;
use ThemeGrill\Masteriyo\RestApi\Controllers\Version1\CoursesController;

class CourseServiceProvider extends AbstractServiceProvider {
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
    protected $provides = [
		'course',
		'course.store',
		'course.rest',
		'ThemeGrill\Masteriyo\Models\Course',
		'ThemeGrill\Masteriyo\Repository\CourseRepository',
		'ThemeGrill\Masteriyo\RestApi\Controllers\Version1\CoursesController'
    ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to, but remember, every alias registered
     * within this method must be declared in the `$provides` array.
	 *
	 * @since 0.1.0
     */
    public function register() {
		$this->getContainer()->add( 'course.store', CourseRepository::class );
		$this->getContainer()->add( 'course.rest', CoursesController::class );
		$this->getContainer()->add( 'course', Course::class )
			->addArgument( RepositoryInterface::class, function() {
				return new CourseRepository();
		});

	}
}
