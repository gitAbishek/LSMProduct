<?php
/**
<<<<<<< HEAD:includes/Shortcodes/CartShortcode.php
 * Cart page shortcode.
 *
 * @since 0.1.0
 * @class CartShortcode
=======
 * My Account page shortcode.
 *
 * @since 0.1.0
 * @class MyAccountShortcode
>>>>>>> Implementing order system.:includes/Shortcodes/MyAccountShortcode.php
 * @package ThemeGrill\Masteriyo\Shortcodes
 */

namespace ThemeGrill\Masteriyo\Shortcodes;

use ThemeGrill\Masteriyo\Abstracts\Shortcode;

defined( 'ABSPATH' ) || exit;

/**
<<<<<<< HEAD:includes/Shortcodes/CartShortcode.php
 * Cart page shortcode.
 */
class CartShortcode extends Shortcode {
=======
 * My Account page shortcode.
 */
class MyAccountShortcode extends Shortcode {
>>>>>>> Implementing order system.:includes/Shortcodes/MyAccountShortcode.php

	/**
	 * Shortcode tag.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
<<<<<<< HEAD:includes/Shortcodes/CartShortcode.php
	protected $tag = 'masteriyo_cart';
=======
	protected $tag = 'masteriyo_myaccount';
>>>>>>> Implementing order system.:includes/Shortcodes/MyAccountShortcode.php

	/**
	 * Shortcode attributes with default values.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $attributes = array();

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
<<<<<<< HEAD:includes/Shortcodes/CartShortcode.php
		$template_path = masteriyo( 'template' )->locate( 'cart.php' );
=======
		$template_path = masteriyo( 'template' )->locate( 'myaccount.php' );
>>>>>>> Implementing order system.:includes/Shortcodes/MyAccountShortcode.php

		/**
		 * Render the template.
		 */
		return $this->get_rendered_html( $this->get_attributes(), $template_path );
	}
}
