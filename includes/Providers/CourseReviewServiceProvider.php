<?php
/**
 * Course review model service provider.
 */

namespace ThemeGrill\Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use ThemeGrill\Masteriyo\Models\CourseReview;
use ThemeGrill\Masteriyo\Repository\CourseReviewRepository;
use ThemeGrill\Masteriyo\RestApi\Controllers\Version1\CourseReviewController;

class CourseReviewServiceProvider extends AbstractServiceProvider {
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
		'course_review',
		'course_review.store',
		'course_review.rest',
		'\ThemeGrill\Masteriyo\RestApi\Controllers\Version1\CourseReviewController',
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
		$this->getContainer()->add( 'course_review.store', CourseReviewRepository::class );

		$this->getContainer()->add( 'course_review.rest', CourseReviewController::class )
		->addArgument( 'permission' );

		$this->getContainer()->add( '\ThemeGrill\Masteriyo\RestApi\Controllers\Version1\CourseReviewController' )
		->addArgument( 'permission' );

		$this->getContainer()->add( 'course_review', CourseReview::class )
		->addArgument( 'course_review.store' );
	}
}
