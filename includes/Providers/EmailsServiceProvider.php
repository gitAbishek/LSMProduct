<?php
/**
 * Emails service provider.
 */

namespace ThemeGrill\Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use ThemeGrill\Masteriyo\Emails\Email;
use ThemeGrill\Masteriyo\Emails\NewOrderEmail;
use ThemeGrill\Masteriyo\Emails\ResetPasswordEmail;

class EmailsServiceProvider extends AbstractServiceProvider {
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
		'email',
		'email.password-reset',
		'email.new-order',
		'\ThemeGrill\Masteriyo\Emails\Email',
		'\ThemeGrill\Masteriyo\Emails\ResetPasswordEmail',
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
		$this->getContainer()->add( 'email', Email::class );
		$this->getContainer()->add( 'email.password-reset', ResetPasswordEmail::class );
		$this->getContainer()->add( 'email.new-order', NewOrderEmail::class );

		$this->getContainer()->add( '\ThemeGrill\Masteriyo\Emails\Email' );
		$this->getContainer()->add( '\ThemeGrill\Masteriyo\Emails\ResetPasswordEmail' );
	}
}
