<?php
/**
 * Shortcodes initializer.
 *
 * @since 0.1.0
 * @class Shortcodes
 * @package Masteriyo\Shortcodes
 */

namespace Masteriyo\Shortcodes;

use Masteriyo\Traits\Singleton;

defined( 'ABSPATH' ) || exit;

/**
 * Shortcodes initializer.
 */
class Shortcodes {
	use Singleton;

	/**
	 * Register shortcodes.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function register_shortcodes() {
		foreach ( $this->get_shortcodes() as $shortcode ) {
			masteriyo( $shortcode )->register();
		}
	}

	/**
	 * Get shortcodes list.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	protected function get_shortcodes() {
		$namespace = '\\Masteriyo\\Shortcodes';

		return apply_filters(
			'masteriyo_shortcodes',
			array(
				'myaccount' => "{$namespace}\\MyAccountShortcode",
				'checkout'  => "{$namespace}\\CheckoutShortcode",
				'cart'      => "{$namespace}\\CartShortcode",
			)
		);
	}
}
