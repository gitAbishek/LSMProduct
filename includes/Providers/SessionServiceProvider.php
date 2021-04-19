<?php
/**
 * Session service provider.
 */

namespace ThemeGrill\Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use ThemeGrill\Masteriyo\Session\SessionHandler;
use ThemeGrill\Masteriyo\Repository\SessionRepository;


class SessionServiceProvider extends AbstractServiceProvider {
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
		'session',
		'session.store',
		'\ThemeGrill\Masteriyo\Session\SessionHandler',
		'\ThemeGrill\Masteriyo\Repository\SessionRepository',
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
		$this->getContainer()
			->add( 'session.store', SessionRepository::class );

		$this->getContainer()
			->add( 'session', SessionHandler::class, true )
			->addArgument( 'session.store');
	}
}
