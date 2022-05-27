<?php
/**
 * Email class.
 *
 * @package Masteriyo\Emails
 *
 * @since 1.0.0
 */

namespace Masteriyo\Emails;

use Pelago\Emogrifier;
use Pelago\Emogrifier\HtmlProcessor\HtmlPruner;

defined( 'ABSPATH' ) || exit;

/**
 * Email Class.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Emails
 */
class Email {

	/**
	 * Email method ID.
	 *
	 * @since 1.0.0
	 *
	 * @var String
	 */
	protected $id;

	/**
	 * 'yes' if the method is enabled.
	 *
	 * @since 1.0.0
	 *
	 * @var string yes, no
	 */
	protected $enabled;

	/**
	 * Setting name to check if this email is enabled.
	 * Option name will be in format of "masteriyo.emails.{setting_name}" .
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $setting_name_for_enable = '';

	/**
	 * Setting name to get email subject from.
	 * Option name will be in format of "masteriyo.emails.{setting_name}" .
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $setting_name_for_subject = '';

	/**
	 * Setting name to get email heading from.
	 * Option name will be in format of "masteriyo.emails.{setting_name}" .
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $setting_name_for_heading = '';

	/**
	 * Setting name to get email recipients from.
	 * Option name will be in format of "masteriyo.emails.{setting_name}" .
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $setting_name_for_recipients = '';

	/**
	 * Recipients for the email.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $recipient;

	/**
	 * Object associated with this email, for example a customer, product, or email.
	 *
	 * @since 1.0.0
	 *
	 * @var object|bool
	 */
	protected $object;

	/**
	 * Strings to find/replace in subjects/headings.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $placeholders = array();

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->set_placeholders(
			array_merge(
				array(
					'{site_title}'   => $this->get_blogname(),
					'{site_address}' => wp_parse_url( home_url(), PHP_URL_HOST ),
					'{site_url}'     => wp_parse_url( home_url(), PHP_URL_HOST ),
					'{admin_email}'  => get_option( 'admin_email' ),
				),
				$this->get_placeholders()
			)
		);
	}

	/**
	 * Get email subject.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_subject() {
		$setting_name = 'masteriyo.emails.' . $this->setting_name_for_subject;
		$subject      = $this->get_default_subject();

		/**
		 * Filters email subject.
		 *
		 * @since 1.0.0
		 *
		 * @param string $subject Email subject.
		 * @param mixed $object Object of email related data. Can be empty or null.
		 * @param Masteriyo\Emails\Email $email Email class object.
		 */
		return apply_filters( $setting_name, $this->format_string( $subject ), $this->get_object(), $this );
	}

	/**
	 * Get email heading.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_heading() {
		$setting_name = 'masteriyo.emails.' . $this->setting_name_for_heading;
		$heading      = get_option( $setting_name, $this->get_default_heading() );
		$heading      = empty( $heading ) ? $this->get_default_heading() : $heading;

		/**
		 * Filters email heading.
		 *
		 * @since 1.0.0
		 *
		 * @param string $heading Email heading.
		 * @param mixed $object Object of email related data. Can be empty or null.
		 * @param Masteriyo\Emails\Email $email Email class object.
		 */
		return apply_filters( $setting_name, $this->format_string( $heading ), $this->get_object(), $this );
	}

	/**
	 * Get content to show above the email footer.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_additional_content() {
		$setting_name = 'masteriyo_email_additional_content_' . $this->get_id();
		$content      = $this->get_default_additional_content();

		/**
		 * Filters email additional content.
		 *
		 * @since 1.0.0
		 *
		 * @param string $additional_content Email additional content.
		 * @param mixed $object Object of email related data. Can be empty or null.
		 * @param Masteriyo\Emails\Email $email Email class object.
		 */
		return apply_filters( $setting_name, $this->format_string( $content ), $this->get_object(), $this );
	}

	/**
	 * Format email string. Like processing placeholders.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string Text to format.
	 *
	 * @return string
	 */
	public function format_string( $string ) {
		$find    = array_keys( $this->get_placeholders() );
		$replace = array_values( $this->get_placeholders() );

		/**
		 * Filters formatted string in email.
		 *
		 * @since 1.0.0
		 *
		 * @param string $str Formatted string.
		 * @param Masteriyo\Emails\Email $email Email class object.
		 */
		return apply_filters( 'masteriyo_email_format_string', str_replace( $find, $replace, $string ), $this );
	}

	/**
	 * Set the locale to the site locale to make sure emails are in the site language.
	 *
	 * @since 1.0.0
	 */
	public function setup_locale() {
		/**
		 * Filters boolean: True if locale should be setup for email.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $bool True if locale should be setup for email.
		 */
		if ( apply_filters( 'masteriyo_email_setup_locale', true ) ) {
			masteriyo_switch_to_site_locale();
		}
	}

	/**
	 * Restore the locale to the default locale. Use after finished with setup_locale.
	 *
	 * @since 1.0.0
	 */
	public function restore_locale() {
		/**
		 * Filters boolean: True if the setup locale should be restored for email.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $bool True if the setup locale should be restored for email.
		 */
		if ( apply_filters( 'masteriyo_email_restore_locale', true ) ) {
			masteriyo_restore_locale();
		}
	}

	/**
	 * Send an email.
	 *
	 * @since 1.0.0
	 *
	 * @param string $to Email to.
	 * @param string $subject Email subject.
	 * @param string $message Email message.
	 * @param string $headers Email headers.
	 * @param array  $attachments Email attachments.
	 *
	 * @return bool Result
	 */
	public function send( $to, $subject, $message, $headers, $attachments ) {
		add_filter( 'wp_mail_from', array( $this, 'get_from_address' ) );
		add_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ) );
		add_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );

		/**
		 * Filters email content.
		 *
		 * @since 1.0.0
		 *
		 * @param string $content Email content.
		 * @param Masteriyo\Emails\Email $email Email class object.
		 */
		$message = apply_filters( 'masteriyo_mail_content', $this->apply_styles( $message ), $this );

		/**
		 * Filters email sender function.
		 *
		 * @since 1.0.0
		 *
		 * @param \Callable $email_sender Email sender function.
		 * @param Masteriyo\Emails\Email $email Email class object.
		 */
		$mail_callback = apply_filters( 'masteriyo_mail_callback', 'wp_mail', $this );

		/**
		 * Filters email sender function parameters.
		 *
		 * @since 1.0.0
		 *
		 * @param array $params Parameters for the email sender function.
		 * @param Masteriyo\Emails\Email $email Email class object.
		 */
		$mail_callback_params = apply_filters( 'masteriyo_mail_callback_params', array( $to, $subject, $message, $headers, $attachments ), $this );

		$return = $mail_callback( ...$mail_callback_params );

		remove_filter( 'wp_mail_from', array( $this, 'get_from_address' ) );
		remove_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ) );
		remove_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );

		return $return;
	}

	/**
	 * Get email headers.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_headers() {
		$header = 'Content-Type: ' . $this->get_content_type() . "\r\n";

		if ( $this->get_from_address() && $this->get_from_name() ) {
			$header .= 'Reply-to: ' . $this->get_from_name() . ' <' . $this->get_from_address() . ">\r\n";
		}

		/**
		 * Filters email headers.
		 *
		 * @since 1.0.0
		 *
		 * @param string $headers Email headers.
		 * @param string $headers Email object id.
		 * @param Masteriyo\Emails\Email $email Email class object.
		 */
		return apply_filters( 'masteriyo_email_headers', $header, $this->get_id(), $this );
	}

	/**
	 * Get email attachments.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_attachments() {
		/**
		 * Filters email attachments.
		 *
		 * @since 1.0.0
		 *
		 * @param array $attachments Absolute paths of attachments.
		 * @param string $headers Email object id.
		 * @param \Masteriyo\Emails\Email $email Email class object.
		 */
		return apply_filters( 'masteriyo_email_attachments', array(), $this->get_id(), $this );
	}

	/**
	 * Get email content type.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_content_type() {
		return 'text/html';
	}

	/**
	 * Get WordPress blog name.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_blogname() {
		return wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
	}

	/**
	 * Get email content.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_content() {
		return '';
	}

	/**
	 * Apply styles to dynamic content.
	 *
	 * @since 1.0.0
	 *
	 * @param string $content Content to apply styles on.
	 *
	 * @return string
	 */
	public function apply_styles( $content ) {
		/**
		 * Filters email styles.
		 *
		 * @since 1.0.0
		 *
		 * @param string $css Email style CSS.
		 * @param Masteriyo\Emails\Email $email Email class object.
		 */
		$css = apply_filters( 'masteriyo_email_styles', masteriyo_get_template_html( 'emails/email-styles.php' ), $this );

		try {
			$emogrifier = new Emogrifier( $content, $css );

			/**
			 * Fires before applying CSS into an email HTML.
			 *
			 * @since 1.0.0
			 *
			 * @param \Pelago\Emogrifier $emogrifier Object that provides functions for converting CSS styles into inline style attributes in your HTML code.
			 * @param \Masteriyo\Emails\Email $email Email object.
			 */
			do_action( 'masteriyo_emogrifier', $emogrifier, $this );

			$content    = $emogrifier->emogrify();
			$html_prune = HtmlPruner::fromHtml( $content );
			$html_prune->removeElementsWithDisplayNone();
			$content = $html_prune->render();
		} catch ( \Exception $e ) {
			$content = '<style type="text/css">' . $css . '</style>' . $content;
		}

		return $content;
	}

	/**
	 * Get the from_name for outgoing emails.
	 *
	 * @since 1.0.0
	 *
	 * @param string $from_name Default wp_mail() name associated with the "from" email address.
	 *
	 * @return string
	 */
	public function get_from_name( $from_name = '' ) {
		$from_name = masteriyo_get_setting( 'emails.general.from_name' );

		/**
		 * Filters "From" value of an email.
		 *
		 * @since 1.0.0
		 *
		 * @param string $from_name From name for an email.
		 * @param Masteriyo\Emails\Email $email Email class object.
		 */
		$from_name = apply_filters( 'masteriyo_email_from_name', $from_name, $this );

		return wp_specialchars_decode( esc_html( $from_name ), ENT_QUOTES );
	}

	/**
	 * Get the from_address for outgoing emails.
	 *
	 * @since 1.0.0
	 *
	 * @param string $from_email Default wp_mail() email address to send from.
	 *
	 * @return string
	 */
	public function get_from_address( $from_email = '' ) {
		$from_email = masteriyo_get_setting( 'emails.general.from_email' );

		/**
		 * Filters from address of an email.
		 *
		 * @since 1.0.0
		 *
		 * @param string $from_email From email address.
		 * @param Masteriyo\Emails\Email $email Email class object.
		 */
		$from_email = apply_filters( 'masteriyo_email_from_address', $from_email, $this );

		return sanitize_email( $from_email );
	}

	/**
	 * Get email identifier.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Check if this email is enabled.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_enabled() {
		$setting_name = 'emails.' . $this->setting_name_for_enable;
		$is_enabled   = masteriyo_string_to_bool( masteriyo_get_setting( $setting_name ) );

		/**
		 * Filters boolean: True if an email with the given ID is enabled.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $bool True if an email with the given ID is enabled.
		 * @param mixed $object Object of email related data. Can be empty or null.
		 * @param Masteriyo\Emails\Email $email Email class object.
		 */
		return apply_filters( $setting_name, $is_enabled, $this->get_object(), $this );
	}

	/**
	 * Set recipients.
	 *
	 * @since 1.0.0
	 *
	 * @param string $recipient
	 */
	public function set_recipient( $recipient ) {
		$this->recipient = $recipient;
	}

	/**
	 * Get valid recipients.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_recipient() {
		/**
		 * Filters recipient of an email.
		 *
		 * @since 1.0.0
		 *
		 * @param string $recipient Email recipient.
		 * @param mixed $object Object of email related data. Can be empty or null.
		 * @param Masteriyo\Emails\Email $email Email class object.
		 */
		$recipient  = apply_filters( 'masteriyo_email_recipient_' . $this->get_id(), $this->recipient, $this->get_object(), $this );
		$recipients = array_map( 'trim', explode( ',', $recipient ) );
		$recipients = array_map( array( $this, 'format_string' ), $recipients );

		return implode( ', ', $recipients );
	}

	/**
	 * Set associated object.
	 *
	 * @since 1.0.0
	 *
	 * @param object $object
	 */
	public function set_object( $object ) {
		if ( is_object( $object ) ) {
			$this->object = $object;
		}
	}

	/**
	 * Get the associated object.
	 *
	 * @since 1.0.0
	 *
	 * @return object
	 */
	public function get_object() {
		return $this->object;
	}

	/**
	 * Get placeholders.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_placeholders() {
		return $this->placeholders;
	}

	/**
	 * Set placeholders.
	 *
	 * @since 1.0.0
	 *
	 * @param array $placeholders
	 */
	public function set_placeholders( $placeholders ) {
		$this->placeholders = $placeholders;
	}

	/**
	 * Get email subject.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_default_subject() {
		return '';
	}

	/**
	 * Get email heading.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_default_heading() {
		return '';
	}

	/**
	 * Default content to show below main email content.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_default_additional_content() {
		return '';
	}
}
