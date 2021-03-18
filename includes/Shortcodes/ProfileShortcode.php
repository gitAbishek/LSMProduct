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
		/**
		 * Enqueue assets.
		 */
		wp_enqueue_script( 'masteriyo-profile-page' );
		wp_enqueue_style( 'masteriyo-profile-page' );

		/**
		 * Setup current logged in user data.
		 */
		masteriyo_setup_current_user_data();

		/**
		 * Prepare Template.
		 */
		$template_path = masteriyo( 'template' )->locate( 'profile.php' );

		/**
		 * Render the template.
		 */
		return $this->get_rendered_html( $this->get_attributes(), $template_path );
	}
}
