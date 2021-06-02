<?php
/**
 * FileRestrictions class.
 *
 * @since 0.1.0
 */

namespace ThemeGrill\Masteriyo\FileRestrictions;

use ThemeGrill\Masteriyo\Abstracts\FileRestriction;

class LessonVideoRestriction extends FileRestriction {
	/**
	 * Run the lesson video file restriction.
	 *
	 * @since 0.1.0
	 */
	public function run() {
		if ( ! isset( $_GET['mto-lesson-vid'] ) ) return;

		$this->validate_lesson_video_url();

		do_action( 'masteriyo_validate_video_lesson_url' );

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			$this->send_lesson_video_file();
		}

		$course = masteriyo_get_course( $_GET['course-id'] );

		if ( $course->get_author_id() === get_current_user_id() ) {
			$this->send_lesson_video_file();
		}

		if ( masteriyo_is_current_user_enrolled_in_course( $course->get_id() ) ) {
			$this->send_lesson_video_file();
		}

		$this->send_error( __( 'You are not allowed to access this file', 'masteriyo' ), '', 403 );
	}

	/**
	 * Send the lesson video file.
	 *
	 * @since 0.1.0
	 */
	public function send_lesson_video_file() {
		$lesson = masteriyo_get_lesson( $_GET['lesson-id'] );

		do_action( 'masteriyo_before_send_lesson_video_file', $lesson );

		if ( 'yes' === apply_filters( 'masteriyo_lesson_video_restriction_redirect_to_file', 'no' ) ) {
			$file_url = wp_get_attachment_url( $lesson->get_video_source_url( 'edit' ) );
			$file_url = apply_filters( 'masteriyo_self_hosted_lesson_video_fileurl', $file_url, $lesson );

			if ( ! is_string( $file_url ) || empty( $file_url ) ) {
				$this->send_error( __( 'File not found', 'masteriyo' ) );
			}
			$this->redirect( $file_url );
		}

		$file_path = get_attached_file( $lesson->get_video_source_url( 'edit' ) );
		$file_path = apply_filters( 'masteriyo_self_hosted_lesson_video_filepath', $file_path, $lesson );

		if ( ! is_string( $file_path ) || empty( $file_path ) ) {
			$this->send_error( __( 'File not found', 'masteriyo' ) );
		}

		$this->send_file( $file_path );
	}

	/**
	 * Validate the lesson video URL.
	 *
	 * @since 0.1.0
	 */
	public function validate_lesson_video_url() {
		if ( empty( $_GET['course-id'] ) ) {
			$this->send_error( __( 'Invalid URL', 'masteriyo' ) );
		}
		if ( empty( $_GET['lesson-id'] ) ) {
			$this->send_error( __( 'Invalid URL', 'masteriyo' ) );
		}
		if ( is_null( masteriyo_get_course( $_GET['course-id'] ) ) ) {
			$this->send_error( __( 'Invalid URL', 'masteriyo' ) );
		}
		if ( is_null( masteriyo_get_lesson( $_GET['lesson-id'] ) ) ) {
			$this->send_error( __( 'Invalid URL', 'masteriyo' ) );
		}
	}
}
