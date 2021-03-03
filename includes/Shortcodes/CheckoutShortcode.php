<?php
/**
 * Checkout page shortcode.
 *
 * @since 0.1.0
 * @class CheckoutShortcode
 * @package ThemeGrill\Masteriyo\Shortcodes
 */

namespace ThemeGrill\Masteriyo\Shortcodes;

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
		/**
		 * Prepare Template.
		 */
		$template_path = masteriyo( 'template' )->locate( 'checkout.php' );

		/**
		 * Render the template.
		 */
		return $this->get_rendered_html( $this->get_attributes(), $template_path );
	}
}
