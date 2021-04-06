<?php
/**
 * Shortcodes service provider.
 */

namespace ThemeGrill\Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use ThemeGrill\Masteriyo\Shortcodes\CartShortcode;
use ThemeGrill\Masteriyo\Shortcodes\CheckoutShortcode;
use ThemeGrill\Masteriyo\Shortcodes\CoursesListShortcode;
use ThemeGrill\Masteriyo\Shortcodes\MyAccountShortcode;

class ShortcodesServiceProvider extends AbstractServiceProvider {
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
		'shortcode.myaccount',
		'shortcode.courses-list',
		'shortcode.checkout',
		'shortcode.cart',
		'\ThemeGrill\Masteriyo\Shortcodes\MyAccountShortcode',
		'\ThemeGrill\Masteriyo\Shortcodes\CoursesListShortcode',
		'\ThemeGrill\Masteriyo\Shortcodes\CartShortcode',
		'\ThemeGrill\Masteriyo\Shortcodes\CheckoutShortcode',
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
		$this->getContainer()->add( 'shortcode.myaccount', MyAccountShortcode::class );
		$this->getContainer()->add( 'shortcode.courses-list', CoursesListShortcode::class );
		$this->getContainer()->add( 'shortcode.checkout', CheckoutShortcode::class );
		$this->getContainer()->add( 'shortcode.cart', CartShortcode::class );

		$this->getContainer()->add( '\ThemeGrill\Masteriyo\Shortcodes\MyAccountShortcode' );
		$this->getContainer()->add( '\ThemeGrill\Masteriyo\Shortcodes\CoursesListShortcode' );
		$this->getContainer()->add( '\ThemeGrill\Masteriyo\Shortcodes\CartShortcode' );
		$this->getContainer()->add( '\ThemeGrill\Masteriyo\Shortcodes\CheckoutShortcode' );
	}
}
