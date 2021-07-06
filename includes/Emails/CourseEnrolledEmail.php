<?php
/**
 * CourseEnrolledEmail class.
 *
 * @package ThemeGrill\Masteriyo\Emails
 *
 * @since 0.1.0
 */

namespace ThemeGrill\Masteriyo\Emails;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * CourseEnrolledEmail Class.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Emails
 */
class CourseEnrolledEmail extends Email {
	/**
	 * Email method ID.
	 *
	 * @since 0.1.0
	 *
	 * @var String
	 */
	protected $id = 'course_enrolled_email';

	/**
	 * Setting name to check if this email is enabled.
	 * Option name will be in format of "masteriyo.emails.{setting_name}" .
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $setting_name_for_enable = 'enrolled_course_enable';

	/**
	 * Setting name to get email subject from.
	 * Option name will be in format of "masteriyo.emails.{setting_name}" .
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $setting_name_for_subject = 'enrolled_course_subject';

	/**
	 * Setting name to get email heading from.
	 * Option name will be in format of "masteriyo.emails.{setting_name}" .
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $setting_name_for_heading = 'enrolled_course_enable';

	/**
	 * Setting name to get email content from.
	 * Option name will be in format of "masteriyo.emails.{setting_name}" .
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $setting_name_for_content = 'enrolled_course_content';

	/**
	 * HTML template path.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public $html_template = 'emails/course-enrolled.php';

	/**
	 * Send this email.
	 *
	 * @since 0.1.0
	 *
	 * @param string $course_id Order ID.
	 * @param string $user_id User ID.
	 */
	public function trigger( $course_id, $user_id ) {
		$course = masteriyo_get_course( $course_id );
		$user   = masteriyo_get_user( $user_id );

		// Bail early if course or user doesn't exist.
		if ( is_null( $course ) || is_null( $user ) ) {
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
					'{user_email}'        => $user->get_email(),
					'{user_display_name}' => $user->get_display_name(),
				)
			)
		);

		$this->set_recipient( $user->get_email() );

		// Bail if recipient is empty.
		if ( empty( $this->get_recipient() ) ) {
			return;
		}

		$this->setup_locale();
		$this->set_object( (object) compact( 'course', 'user' ) );

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
		$content = get_option( 'masteriyo.emails.' . $this->setting_name_for_content );

		if ( ! empty( $content ) ) {
			return $this->format_string( $content );
		}

		$object = $this->get_object();

		return masteriyo_get_template_html(
			$this->html_template,
			array(
				'email_heading' => $this->get_heading(),
				'course'        => $object->course,
				'user'          => $object->user,
				'email'         => $this,
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
		return __( 'Course Enrolled in {site_title}', 'masteriyo' );
	}

	/**
	 * Get default email heading.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_default_heading() {
		return __( 'Course Enrolled', 'masteriyo' );
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
}
