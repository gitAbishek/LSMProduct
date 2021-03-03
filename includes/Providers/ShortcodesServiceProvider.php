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
use ThemeGrill\Masteriyo\Shortcodes\ProfileShortcode;

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
		'profile-shortcode',
		'\ThemeGrill\Masteriyo\Shortcodes\ProfileShortcode',
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
		$this->getContainer()->add( 'profile-shortcode', ProfileShortcode::class );
		$this->getContainer()->add( 'courses-list-shortcode', CoursesListShortcode::class );
		$this->getContainer()->add( 'checkout-shortcode', CheckoutShortcode::class );
		$this->getContainer()->add( 'cart-shortcode', CartShortcode::class );

		$this->getContainer()->add( '\ThemeGrill\Masteriyo\Shortcodes\ProfileShortcode' );
		$this->getContainer()->add( '\ThemeGrill\Masteriyo\Shortcodes\CoursesListShortcode' );
		$this->getContainer()->add( '\ThemeGrill\Masteriyo\Shortcodes\CartShortcode' );
		$this->getContainer()->add( '\ThemeGrill\Masteriyo\Shortcodes\CheckoutShortcode' );
	}
}
