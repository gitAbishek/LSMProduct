<?php
/**
 * Quiz progress service provider.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Providers
 */

namespace ThemeGrill\Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use ThemeGrill\Masteriyo\Models\QuizProgress;
use ThemeGrill\Masteriyo\Repository\QuizProgressRepository;
use ThemeGrill\Masteriyo\RestApi\Controllers\Version1\QuizProgressController;

class QuizProgressServiceProvider extends AbstractServiceProvider {
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
		'quiz-progress',
		'quiz-progress.store',
		'quiz-progress.rest',
		'\ThemeGrill\Masteriyo\RestApi\Controllers\Version1\QuizProgressController',
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
		$this->getContainer()->add( 'quiz-progress.store', QuizProgressRepository::class );

		$this->getContainer()
			->add( 'quiz-progress.rest', QuizProgressController::class )
			->addArgument( 'permission' );

		$this->getContainer()
			->add( '\ThemeGrill\Masteriyo\RestApi\Controllers\Version1\QuizProgressController' )
			->addArgument( 'permission' );

		$this->getContainer()
			->add( 'quiz-progress', QuizProgress::class )
			->addArgument( 'quiz-progress.store' );
	}
}
