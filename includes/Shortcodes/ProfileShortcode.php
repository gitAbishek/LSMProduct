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
		if ( masteriyo_is_signup_page() ) {
			/**
			 * Enqueue assets.
			 */
			wp_enqueue_script( 'masteriyo-signup-form' );
			wp_enqueue_style( 'masteriyo-signup-form' );

			/**
			 * Find Template.
			 */
			$template_path = masteriyo( 'template' )->locate( 'profile/form-signup.php' );
		} elseif ( masteriyo_is_lost_password_page() ) {
			/**
			 * Enqueue assets.
			 */
			wp_enqueue_script( 'masteriyo-reset-form' );
			wp_enqueue_style( 'masteriyo-reset-form' );

			/**
			 * Find Template.
			 */
			$template_path = masteriyo( 'template' )->locate( 'profile/form-reset-password.php' );
		} elseif ( is_user_logged_in() ) {
			/**
			 * Enqueue assets.
			 */
			wp_enqueue_script( 'masteriyo-myaccount' );
			wp_enqueue_style( 'masteriyo-myaccount' );

			/**
			 * Setup current logged in user data.
			 */
			masteriyo_setup_current_user_data();

			/**
			 * Find Template.
			 */
			$template_path = masteriyo( 'template' )->locate( 'profile.php' );
		} else {
			/**
			 * Enqueue login form assets.
			 */
			wp_enqueue_script( 'masteriyo-login-form' );
			wp_enqueue_style( 'masteriyo-myaccount' );

			/**
			 * Find Template.
			 */
			$template_path = masteriyo( 'template' )->locate( 'profile/form-login.php' );
		}

		/**
		 * Render the template.
		 */
		return $this->get_rendered_html( $this->get_attributes(), $template_path );
	}
}
