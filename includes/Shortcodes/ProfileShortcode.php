<?php
/**
 * Profile page shortcode.
 *
 * @since 0.1.0
 * @class ProfileShortcode
 * @package ThemeGrill\Masteriyo\Shortcodes
 */

namespace ThemeGrill\Masteriyo\Shortcodes;

use ThemeGrill\Masteriyo\Abstracts\Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Profile page shortcode.
 */
class ProfileShortcode extends Shortcode {

	/**
	 * Shortcode tag.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $tag = 'masteriyo_profile';

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

	protected function get_template_path() {
		if ( masteriyo_is_signup_page() ) {
			return masteriyo( 'template' )->locate( 'profile/form-signup.php' );
		}
		if ( masteriyo_is_lost_password_page() ) {
			return masteriyo( 'template' )->locate( 'profile/form-reset-password.php' );
		}
		if ( is_user_logged_in() ) {
			return masteriyo( 'template' )->locate( 'profile.php' );
		}

		return masteriyo( 'template' )->locate( 'profile/form-login.php' );
	}
}
