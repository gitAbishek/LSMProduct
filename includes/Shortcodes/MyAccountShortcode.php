<?php
/**
 * Myaccount shortcode.
 *
 * @since 0.1.0
 * @class MyAccountShortcode
 * @package ThemeGrill\Masteriyo\Shortcodes
 */

namespace ThemeGrill\Masteriyo\Shortcodes;

use ThemeGrill\Masteriyo\Abstracts\Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Myaccount shortcode.
 */
class MyAccountShortcode extends Shortcode {

	/**
	 * Shortcode tag.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $tag = 'masteriyo_myaccount';

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
		$template_path = $this->get_template_path();

		/**
		 * Render the template.
		 */
		return $this->get_rendered_html( $this->get_attributes(), $template_path );
	}

	/**
	 * Get template path to render.
	 *
	 * @since  0.1.0
	 *
	 * @return string
	 */
	protected function get_template_path() {
		if ( masteriyo_is_signup_page() ) {
			return masteriyo( 'template' )->locate( 'myaccount/form-signup.php' );
		}
		if ( masteriyo_is_lost_password_page() ) {
			return masteriyo( 'template' )->locate( 'myaccount/form-reset-password.php' );
		}
		if ( is_user_logged_in() ) {
			return masteriyo( 'template' )->locate( 'myaccount.php' );
		}

		return masteriyo( 'template' )->locate( 'myaccount/form-login.php' );
	}
}
