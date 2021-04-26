<?php
/**
 * Queries service provider.
 */

namespace ThemeGrill\Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use ThemeGrill\Masteriyo\Query\FaqQuery;
use ThemeGrill\Masteriyo\Query\LessonQuery;
use ThemeGrill\Masteriyo\Query\OrderItemQuery;
use ThemeGrill\Masteriyo\Query\OrderQuery;
use ThemeGrill\Masteriyo\Query\QuizQuery;
use ThemeGrill\Masteriyo\Query\QuestionQuery;
use ThemeGrill\Masteriyo\Query\SectionQuery;

class QueriesServiceProvider extends AbstractServiceProvider {
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
		'query.sections',
		'query.lessons',
		'query.quizes',
		'query.questions',
		'query.faqs',
		'query.orders',
		'query.orders.items',

		'\ThemeGrill\Masteriyo\Query\SectionQuery',
		'\ThemeGrill\Masteriyo\Query\LessonQuery',
		'\ThemeGrill\Masteriyo\Query\QuizQuery',
		'\ThemeGrill\Masteriyo\Query\QuestionQuery',
		'\ThemeGrill\Masteriyo\Query\FaqQuery',
		'\ThemeGrill\Masteriyo\Query\OrderQuery',
		'\ThemeGrill\Masteriyo\Query\OrderItemQuery',
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
		$this->getContainer()->add( 'query.sections', SectionQuery::class );
		$this->getContainer()->add( 'query.lessons', LessonQuery::class );
		$this->getContainer()->add( 'query.quizes', QuizQuery::class );
		$this->getContainer()->add( 'query.questions', QuestionQuery::class );
		$this->getContainer()->add( 'query.faqs', FaqQuery::class );
		$this->getContainer()->add( 'query.orders', OrderQuery::class );
		$this->getContainer()->add( 'query.orders.items', OrderItemQuery::class );

		$this->getContainer()->add( '\ThemeGrill\Masteriyo\Query\SectionQuery' );
		$this->getContainer()->add( '\ThemeGrill\Masteriyo\Query\LessonQuery' );
		$this->getContainer()->add( '\ThemeGrill\Masteriyo\Query\QuizQuery' );
		$this->getContainer()->add( '\ThemeGrill\Masteriyo\Query\QuestionQuery' );
		$this->getContainer()->add( '\ThemeGrill\Masteriyo\Query\FaqQuery' );
		$this->getContainer()->add( '\ThemeGrill\Masteriyo\Query\OrderQuery' );
		$this->getContainer()->add( '\ThemeGrill\Masteriyo\Query\OrderItemQuery' );
	}
}
