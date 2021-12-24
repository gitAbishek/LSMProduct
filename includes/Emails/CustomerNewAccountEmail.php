<?php
/**
 * CustomerNewAccountEmail class.
 *
 * @package Masteriyo\Emails
 *
 * @since 1.0.0
 */

namespace Masteriyo\Emails;

use Masteriyo\Models\User;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * CustomerNewAccountEmail Class. Used for sending new account email.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Emails
 */
class CustomerNewAccountEmail extends Email {
	/**
	 * Email method ID.
	 *
	 * @since 1.0.0
	 *
	 * @var String
	 */
	protected $id = 'customer-new-account';

	/**
	 * HTML template path.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $html_template = 'emails/customer-new-account.php';

	/**
	 * Send this email.
	 *
	 * @since 1.0.0
	 *
	 * @param User $user
	 */
	public function trigger( $user ) {
		if ( ! $user instanceof User ) {
			$user = masteriyo_get_user( $user );
		}

		// Bail early if user doesn't exist.
		if ( is_wp_error( $user ) ) {
			return;
		}

		$this->set_recipient( stripslashes( $user->get_email() ) );

		// Bail if recipient is empty.
		if ( empty( $this->get_recipient() ) ) {
			return;
		}

		$this->setup_locale();
		$this->set_object( $user );

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
		return __( 'User registered in {site_title}', 'masteriyo' );
	}

	/**
	 * Get default email heading.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_default_heading() {
		return __( 'New user registered.', 'masteriyo' );
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
}
