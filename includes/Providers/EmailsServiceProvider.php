<?php
/**
 * Emails service provider.
 */

namespace Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Masteriyo\Emails\Email;
use Masteriyo\Emails\NewOrderEmail;
use Masteriyo\Emails\OrderCancelledEmail;
use Masteriyo\Emails\OrderOnHoldEmail;
use Masteriyo\Emails\OrderCompletedEmail;
use Masteriyo\Emails\ResetPasswordEmail;
use Masteriyo\Emails\CustomerNewAccountEmail;

class EmailsServiceProvider extends AbstractServiceProvider {
	/**
	 * The provided array is a way to let the container
	 * know that a service is provided by this service
	 * provider. Every service that is registered via
	 * this service provider must have an alias added
	 * to this array or it will be ignored
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $provides = array(
		'email',
		'email.customer-new-account',
		'email.password-reset',
		'email.new-order',
		'email.order-cancelled',
		'email.order-onhold',
		'email.order-completed',
		'\Masteriyo\Emails\Email',
		'\Masteriyo\Emails\ResetPasswordEmail',
	);

	/**
	 * This is where the magic happens, within the method you can
	 * access the container and register or retrieve anything
	 * that you need to, but remember, every alias registered
	 * within this method must be declared in the `$provides` array.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		$this->getContainer()->add( 'email', Email::class );
		$this->getContainer()->add( 'email.customer-new-account', CustomerNewAccountEmail::class );
		$this->getContainer()->add( 'email.password-reset', ResetPasswordEmail::class );
		$this->getContainer()->add( 'email.new-order', NewOrderEmail::class );
		$this->getContainer()->add( 'email.order-cancelled', OrderCancelledEmail::class );
		$this->getContainer()->add( 'email.order-onhold', OrderOnHoldEmail::class );
		$this->getContainer()->add( 'email.order-completed', OrderCompletedEmail::class );

		$this->getContainer()->add( '\Masteriyo\Emails\Email' );
		$this->getContainer()->add( '\Masteriyo\Emails\ResetPasswordEmail' );
	}
}
