<?php
/**
 * Ajax service provider.
 *
 * @since x.x.x
 */

namespace Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Ajax;
use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * Ajax service provider.
 *
 * @since x.x.x
 */
class AjaxServiceProvider extends AbstractServiceProvider {
	/**
	 * The provided array is a way to let the container
	 * know that a service is provided by this service
	 * provider. Every service that is registered via
	 * this service provider must have an alias added
	 * to this array or it will be ignored
	 *
	 * @since x.x.x
	 *
	 * @var array
	 */
	protected $provides = array(
		'ajax',
		'\Masteriyo\Ajax',
	);

	/**
	 * This is where the magic happens, within the method you can
	 * access the container and register or retrieve anything
	 * that you need to, but remember, every alias registered
	 * within this method must be declared in the `$provides` array.
	 *
	 * @since x.x.x
	 */
	public function register() {
		$this->getContainer()->add( 'ajax', Ajax::class, true );
	}
}
