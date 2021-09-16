<?php
/**
 * OrderCompletedEmail class.
 *
 * @package Masteriyo\Emails
 *
 * @since 1.0.0
 */

namespace Masteriyo\Emails;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * OrderCompletedEmail Class.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Emails
 */
class OrderCompletedEmail extends Email {
	/**
	 * Email method ID.
	 *
	 * @since 1.0.0
	 *
	 * @var String
	 */
	protected $id = 'order_completed_email';

	/**
	 * Setting name to check if this email is enabled.
	 * Option name will be in format of "masteriyo.emails.{setting_name}" .
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $setting_name_for_enable = 'completed_order.enable';

	/**
	 * Setting name to get email subject from.
	 * Option name will be in format of "masteriyo.emails.{setting_name}" .
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $setting_name_for_subject = 'completed_order_subject';

	/**
	 * Setting name to get email heading from.
	 * Option name will be in format of "masteriyo.emails.{setting_name}" .
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $setting_name_for_heading = 'completed_order_heading';

	/**
	 * Setting name to get email content from.
	 * Option name will be in format of "masteriyo.emails.{setting_name}" .
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $setting_name_for_content = 'completed_order_content';

	/**
	 * HTML template path.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $html_template = 'emails/order-completed.php';

	/**
	 * Send this email.
	 *
	 * @since 1.0.0
	 *
	 * @param string $order_id Order ID.
	 */
	public function trigger( $order_id ) {
		$order = masteriyo_get_order( $order_id );

		// Bail early if order doesn't exist.
		if ( is_wp_error( $order ) ) {
			return;
		}

		$customer = $order->get_customer();

		// Bail early if customer doesn't exist.
		if ( ! $customer ) {
			return;
		}

		// Bail early if this email notification is disabled.
		if ( ! $this->is_enabled() ) {
			return;
		}

		$this->set_placeholders(
			array_merge(
				$this->get_placeholders(),
				array(
					'{customer_email}'        => $customer->get_email(),
					'{customer_display_name}' => $customer->get_display_name(),
				)
			)
		);

		$this->set_recipient( $customer->get_email() );

		// Bail if recipient is empty.
		if ( empty( $this->get_recipient() ) ) {
			return;
		}

		$this->setup_locale();
		$this->set_object( $order );

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
		$content = get_option( 'masteriyo.emails.' . $this->setting_name_for_content );

		if ( ! empty( $content ) ) {
			return $this->format_string( $content );
		}

		return masteriyo_get_template_html(
			$this->html_template,
			array(
				'email_heading' => $this->get_heading(),
				'order'         => $this->get_object(),
				'email'         => $this,
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
		return __( 'Order completed — {site_title}', 'masteriyo' );
	}

	/**
	 * Get default email heading.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_default_heading() {
		return __( 'Order completed', 'masteriyo' );
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
