<?php
/**
 * Comment model service provider.
 */

namespace ThemeGrill\Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use ThemeGrill\Masteriyo\Models\Comment;
use ThemeGrill\Masteriyo\Repository\CommentRepository;
use ThemeGrill\Masteriyo\RestApi\Controllers\Version1\CommentsController;

class CommentServiceProvider extends AbstractServiceProvider {
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
		'comment',
		'comment.store',
		'comment.rest',
		'\ThemeGrill\Masteriyo\RestApi\Controllers\Version1\CommentsController'
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
		 $this->getContainer()->add( 'comment.store', CommentRepository::class );

		 $this->getContainer()->add( 'comment.rest', CommentsController::class )
			->addArgument( 'permission');

		  $this->getContainer()->add( '\ThemeGrill\Masteriyo\RestApi\Controllers\Version1\CommentsController' )
			->addArgument( 'permission');

		 $this->getContainer()->add( 'comment', Comment::class )
			->addArgument( 'comment.store');
	 }
}
