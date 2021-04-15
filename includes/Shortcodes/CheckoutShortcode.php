<?php
/**
 * Checkout page shortcode.
 *
 * @since 0.1.0
 * @class CheckoutShortcode
 * @package ThemeGrill\Masteriyo\Shortcodes
 */

namespace ThemeGrill\Masteriyo\Shortcodes;

use ThemeGrill\Masteriyo\Abstracts\Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Checkout page shortcode.
 */
class CheckoutShortcode extends Shortcode {

	/**
	 * Shortcode tag.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $tag = 'masteriyo_checkout';

	/**
	 * Shortcode attributes with default values.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $attributes = array(
		'user_id' => null,
	);

	/**
	 * Get shortcode content.
	 *
	 * @since  0.1.0
	 *
	 * @return string
	 */
	public function get_content() {
		      $data   = $this->get_attributes();
		$data['cart'] = \masteriyo( 'cart' );
		$data['courses'] = $this->get_courses_form_cart( \masteriyo('cart') );

		return \masteriyo_get_template_html( 'checkout/form-checkout.php', $data );
	}

	/**
	 * Get the valid courses from cart.
	 *
	 * @param ThemeGrill\Masteriyo\Cart\Cart $cart Cart.
	 * @return Course[]
	 */
	protected function get_courses_form_cart( $cart ) {
		$courses = array();

		if( $cart->is_empty() ) {
			return array();
		}

		foreach( $cart->get_cart_contents() as $cart_content ) {
			if ( ! isset( $cart_content['course_id'] ) ) {
				continue;
			}

			$course = masteriyo_get_course( $cart_content['course_id'] );
			if ( is_null( $course ) ) {
				continue;
			}

			$courses[] = $course;
		}

		return $courses;
	}
}
