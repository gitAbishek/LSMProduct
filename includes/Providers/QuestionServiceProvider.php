<?php
/**
 * Question model service provider.
 */

namespace ThemeGrill\Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use ThemeGrill\Masteriyo\Models\Question\Question;
use ThemeGrill\Masteriyo\Models\Question\TrueFalse;
use ThemeGrill\Masteriyo\Models\Question\SingleChoice;
use ThemeGrill\Masteriyo\Models\Question\MultipleChoice;
use ThemeGrill\Masteriyo\Models\Question\ShortAnswer;
use ThemeGrill\Masteriyo\Models\Question\ImageMatching;
use ThemeGrill\Masteriyo\Models\Question\Sortable;


use ThemeGrill\Masteriyo\Repository\QuestionRepository;
use ThemeGrill\Masteriyo\RestApi\Controllers\Version1\QuestionsController;

class QuestionServiceProvider extends AbstractServiceProvider {
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
		'question',
		'question.store',
		'question.rest',
		'\ThemeGrill\Masteriyo\RestApi\Controllers\Version1\QuestionsController',
		'question.true-false',
		'question.single-choice',
		'question.multiple-choice',
		'question.short-answer',
		'question.image-matching',
		'question.sortable',
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
		$this->getContainer()->add( 'question.store', QuestionRepository::class );

		$this->getContainer()->add( 'question.rest', QuestionsController::class )
			->addArgument( 'permission' );

		$this->getContainer()->add( '\ThemeGrill\Masteriyo\RestApi\Controllers\Version1\QuestionsController' )
			->addArgument( 'permission' );

		$this->getContainer()->add( 'question', Question::class )
			->addArgument( 'question.store' );

		$this->getContainer()->add( 'question.true-false', TrueFalse::class )
			->addArgument( 'question.store' );

		$this->getContainer()->add( 'question.single-choice', SingleChoice::class )
			->addArgument( 'question.store' );

		$this->getContainer()->add( 'question.multiple-choice', MultipleChoice::class )
			->addArgument( 'question.store' );

		$this->getContainer()->add( 'question.short-answer', ShortAnswer::class )
			->addArgument( 'question.store' );

		$this->getContainer()->add( 'question.image-matching', ImageMatching::class )
			->addArgument( 'question.store' );

		$this->getContainer()->add( 'question.sortable', Sortable::class )
			->addArgument( 'question.store' );
	}
}
