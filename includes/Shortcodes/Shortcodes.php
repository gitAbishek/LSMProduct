<?php
/**
 * Shortcodes initializer.
 *
 * @since 0.1.0
 * @class Shortcodes
 * @package ThemeGrill\Masteriyo\Shortcodes
 */

namespace ThemeGrill\Masteriyo\Shortcodes;

use ThemeGrill\Masteriyo\Traits\Singleton;

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
		$namespace = '\\ThemeGrill\\Masteriyo\\Shortcodes';

		return apply_filters( 'masteriyo_shortcodes', array(
			'profile'      => "{$namespace}\\ProfileShortcode",
			'course-list' => "{$namespace}\\CoursesListShortcode",
			'checkout'     => "{$namespace}\\CheckoutShortcode",
			'cart'         => "{$namespace}\\CartShortcode",
		) );
	}
}
