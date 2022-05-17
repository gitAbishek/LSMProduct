<?php
/**
 * ResetPasswordEmail class.
 *
 * @package Masteriyo\Emails
 *
 * @since 1.0.0
 */

namespace Masteriyo\Emails;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * ResetPasswordEmail Class. Used for sending password reset email.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Emails
 */
class ResetPasswordEmail extends Email {
	/**
	 * Email method ID.
	 *
	 * @since 1.0.0
	 *
	 * @var String
	 */
	protected $id = 'user_reset_password';

	/**
	 * Password reset key.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $reset_key;

	/**
	 * HTML template path.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $html_template = 'emails/reset-password.php';

	/**
	 * Send this email.
	 *
	 * @since 1.0.0
	 *
	 * @param string $user_id User ID.
	 * @param string $reset_key Password reset key.
	 */
	public function trigger( $user_id, $reset_key ) {
		$user = masteriyo_get_user( $user_id );

		// Bail early if user doesn't exist.
		if ( is_wp_error( $user ) ) {
			return;
		}

		/**
		 * Filters boolean-like value: 'yes' if reset password email should be disabled, otherwise 'no'.
		 *
		 * @since 1.0.0
		 *
		 * @param string $is_disabled 'yes' if reset password email should be disabled, otherwise 'no'.
		 */
		$is_disabled = apply_filters( 'masteriyo_disable_reset_password_email', 'no' );

		// Bail early if this email notification is disabled.
		if ( 'yes' === $is_disabled ) {
			return;
		}

		$this->set_recipient( stripslashes( $user->get_email() ) );

		// Bail if recipient is empty.
		if ( empty( $this->get_recipient() ) ) {
			return;
		}

		$this->setup_locale();
		$this->set_object( $user );
		$this->set_reset_key( $reset_key );

		$this->send(
			$this->get_recipient(),
			$this->get_subject(),
			$this->get_content(),
			$this->get_headers(),
			$this->get_attachments()
		);

		$this->restore_locale();
	}

	/**
	 * Get email content.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_content() {
		return masteriyo_get_template_html(
			$this->html_template,
			array(
				'email_heading'      => $this->get_heading(),
				'user'               => $this->get_object(),
				'reset_key'          => $this->get_reset_key(),
				'blogname'           => $this->get_blogname(),
				'additional_content' => $this->get_additional_content(),
				'email'              => $this,
			)
		);
	}

	/**
	 * Get default email subject.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_default_subject() {
		return __( 'Password Reset Request for {site_title}', 'masteriyo' );
	}

	/**
	 * Get default email heading.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_default_heading() {
		return __( 'Password Reset Request', 'masteriyo' );
	}

	/**
	 * Default content to show above the email footer.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_default_additional_content() {
		return __( 'Thanks for reading.', 'masteriyo' );
	}

	/**
	 * Set the pasword reset key.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key
	 */
	public function set_reset_key( $key ) {
		$this->reset_key = $key;
	}

	/**
	 * Get the password reset key.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_reset_key() {
		return $this->reset_key;
	}
}
