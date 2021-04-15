<?php
/**
 * ResetPasswordEmail class.
 *
 * @package ThemeGrill\Masteriyo\Emails
 *
 * @since 0.1.0
 */

namespace ThemeGrill\Masteriyo\Emails;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * ResetPasswordEmail Class. Used for sending password reset email.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Emails
 */
class ResetPasswordEmail extends Email {
	/**
	 * Email method ID.
	 *
	 * @since 0.1.0
	 *
	 * @var String
	 */
	protected $id = 'user_reset_password';

	/**
	 * Password reset key.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public $reset_key;

	/**
	 * HTML template path.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public $html_template = 'emails/reset-password.php';

	/**
	 * Send this email.
	 *
	 * @since 0.1.0
	 *
	 * @param string $user_id User ID.
	 * @param string $reset_key Password reset key.
	 */
	public function trigger( $user_id, $reset_key ) {
		$user = masteriyo_get_user( $user_id );

		// Bail early if user doesn't exist.
		if ( is_null( $user ) ) {
			return;
		}

		// Bail early if this email notification is disabled.
		if ( ! $this->is_enabled() ) {
			return;
		}

		$this->set_recipient( stripslashes( $user->get_user_email() ) );

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
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_content() {
		return masteriyo_get_template_html(
			$this->html_template,
			array(
				'email_heading'      => $this->get_heading(),
				'user_login'         => $this->get_object()->get_user_login(),
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
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_default_subject() {
		return __( 'Password Reset Request for {site_title}', 'masteriyo' );
	}

	/**
	 * Get default email heading.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_default_heading() {
		return __( 'Password Reset Request', 'masteriyo' );
	}

	/**
	 * Default content to show above the email footer.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_default_additional_content() {
		return __( 'Thanks for reading.', 'masteriyo' );
	}

	/**
	 * Set the pasword reset key.
	 *
	 * @since 0.1.0
	 *
	 * @param string $key
	 */
	public function set_reset_key( $key ) {
		$this->reset_key = $key;
	}

	/**
	 * Get the password reset key.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_reset_key() {
		return $this->reset_key;
	}
}
