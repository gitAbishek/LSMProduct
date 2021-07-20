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
		return $this->get_rendered_html(
			array_merge(
				$this->get_attributes(),
				$this->get_template_args()
			),
			$template_path
		);
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
			return $this->get_lost_password_page_template();
		}
		if ( is_user_logged_in() ) {
			return masteriyo( 'template' )->locate( 'myaccount.php' );
		}

		return masteriyo( 'template' )->locate( 'myaccount/form-login.php' );
	}

	/**
	 * Get lost password page template.
	 *
	 * @since  0.1.0
	 *
	 * @return string
	 */
	protected function get_lost_password_page_template() {
		if ( ! empty( $_GET['reset-link-sent'] ) ) { // WPCS: input var ok, CSRF ok.
			masteriyo_add_notice( esc_html__( 'Password reset email has been sent.', 'masteriyo' ) );

			return masteriyo( 'template' )->locate( 'myaccount/reset-password-confirmation.php' );
		}
		if ( ! empty( $_GET['show-reset-form'] ) ) { // WPCS: input var ok, CSRF ok.
			if ( isset( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ] ) && 0 < strpos( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ], ':' ) ) {  // @codingStandardsIgnoreLine
				list( $rp_id, $rp_key ) = array_map( 'masteriyo_clean', explode( ':', wp_unslash( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ] ), 2 ) ); // @codingStandardsIgnoreLine
				$user                   = masteriyo_get_user( absint( $rp_id ) );
				$rp_login               = $user ? $user->get_username() : '';

				if ( is_wp_error( check_password_reset_key( $rp_key, $rp_login ) ) ) {
					masteriyo_add_notice( __( 'This key is invalid or has already been used. Please request to reset your password again if needed.', 'masteriyo' ), 'error' );
				} else {
					$this->set_template_args(
						array(
							'key'   => $rp_key,
							'login' => $rp_login,
						)
					);
					return masteriyo( 'template' )->locate( 'myaccount/form-reset-password.php' );
				}
			}
		}

		return masteriyo( 'template' )->locate( 'myaccount/form-reset-password-request.php' );
	}
}
