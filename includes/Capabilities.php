<?php
/**
 * Capabilities class.
 *
 * @since 1.0.0
 */

namespace Masteriyo;

/**
 * Capabilities class.
 */
class Capabilities {

	public static function init() {
		add_filter( 'map_meta_cap', array( __CLASS__, 'map_meta_cap' ), 10, 4 );
	}

	/**
	 * Map custom capabilities.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $caps    Primitive capabilities required of the user.
	 * @param string   $cap     Capability being checked.
	 * @param int      $user_id The user ID.
	 * @param array    $args    Adds context to the capability check, typically
	 *                          starting with an object ID.
	 * @return array
	 */
	public static function map_meta_cap( $caps, $cap, $user_id, $args ) {
		if ( masteriyo_ends_with( $cap, 'course_progress' ) ) {
			$caps = self::course_progress_map_meta_cap( $caps, $cap, $user_id, $args );
		}

		if ( masteriyo_ends_with( $cap, 'course_qa' ) ) {
			$caps = self::course_qa_map_meta_cap( $caps, $cap, $user_id, $args );
		}

		return $caps;
	}

	/**
	 * Handle course progress meta cap.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected static function course_progress_map_meta_cap( $caps, $cap, $user_id, $args ) {
		switch ( $cap ) {
			case 'edit_course_progress':
				$progress = masteriyo_get_course_progress( $args[0] );

				if ( ! $progress ) {
					$caps[] = 'do_not_allow';
					break;
				}

				if ( $progress->get_user_id() && strval( $user_id ) === $progress->get_user_id() ) {
					$caps = array( 'edit_course_progresses' );
				} else {
					$caps = user_can( $user_id, 'edit_others_course_progresses' )
					? array( 'edit_course_progresses' )
					: array( 'do_not_allow' );
				}

				break;

			case 'delete_course_progress':
				$progress = masteriyo_get_course_progress( $args[0] );

				if ( ! $progress ) {
					$caps[] = 'do_not_allow';
					break;
				}

				if ( $progress->get_user_id() && strval( $user_id ) === $progress->get_user_id() ) {
					$caps = array( 'delete_course_progresses' );
				} else {
					$caps = user_can( $user_id, 'delete_others_course_progresses' ) ? array( 'delete_course_progresses' ) : array( 'do_not_allow' );
				}

				break;

		}

		return $caps;
	}

	/**
	 * Handle course questions & answers meta cap.
	 *
	 * @since 1.0.3
	 *
	 * @return array
	 */
	protected static function course_qa_map_meta_cap( $caps, $cap, $user_id, $args ) {
		switch ( $cap ) {
			case 'read_course_qa':
				if ( user_can( $user_id, 'read_course_qas' ) ) {
					$caps = array( 'read_course_qas' );
				} else {
					$caps[] = 'do_not_allow';
				}

				break;

			case 'edit_course_qa':
				$question_answers = masteriyo_get_course_qa( $args[0] );

				if ( ! $question_answers ) {
					$caps[] = 'do_not_allow';
					break;
				}

				if ( $question_answers->get_user_id() && absint( $user_id ) === $question_answers->get_user_id() ) {
					$caps = array( 'edit_course_qas' );
				} else {
					$caps = user_can( $user_id, 'edit_others_course_qas' ) ? array( 'edit_course_qas' ) : array( 'do_not_allow' );
				}

				break;

			case 'delete_course_qa':
				$question_answers = masteriyo_get_course_qa( $args[0] );

				if ( ! $question_answers ) {
					$caps[] = 'do_not_allow';
					break;
				}

				if ( $question_answers->get_user_id() && absint( $user_id ) === $question_answers->get_user_id() ) {
					$caps = array( 'delete_course_qas' );
				} else {
					$caps = user_can( $user_id, 'delete_others_course_qas' ) ? array( 'delete_course_qas' ) : array( 'do_not_allow' );
				}

				break;
		}

		return $caps;
	}

	/**
	 * Get masteriyo student capabilities.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_student_capabilities() {
		$capabilities = array(
			'read_courses'              => true,
			'read_sections'             => true,
			'read_lessons'              => true,
			'read_quizzes'              => true,
			'read_questions'            => true,
			'read_faqs'                 => true,
			'read_orders'               => true,
			'read_users'                => true,
			'read_course_reviews'       => true,
			'read_user_courses'         => true,

			// Course Progress
			'read_course_progresses'    => true,
			'publish_course_progresses' => true,
			'edit_course_progresses'    => true,

			// Course Qas
			'read_course_qas'           => true,
			'edit_course_qas'           => true,
			'delete_course_qas'         => true,
			'moderate_comments'         => true,

			// Taxonomy.
			'manage_course_categories'  => true,
		);

		$subscriber_caps = get_role( 'subscriber' )->capabilities;
		$capabilities    = array_merge( $capabilities, $subscriber_caps );

		return apply_filters( 'masteriyo_student_capabilities', $capabilities );
	}

	/**
	 * Get masteriyo instructor capabilities.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_instructor_capabilities() {
		$capabilities = array(
			// Courses
			'publish_courses'                 => true,
			'edit_courses'                    => true,
			'edit_private_courses'            => true,
			'edit_published_courses'          => true,
			'delete_courses'                  => true,
			'delete_published_courses'        => true,
			'delete_private_courses'          => true,

			// sections
			'publish_sections'                => true,
			'edit_sections'                   => true,
			'edit_private_sections'           => true,
			'edit_published_sections'         => true,
			'delete_sections'                 => true,
			'delete_published_sections'       => true,
			'delete_private_sections'         => true,

			// Lessons
			'publish_lessons'                 => true,
			'edit_lessons'                    => true,
			'edit_private_lessons'            => true,
			'edit_published_lessons'          => true,
			'delete_lessons'                  => true,
			'delete_published_lessons'        => true,
			'delete_private_lessons'          => true,

			// Quizzes
			'publish_quizzes'                 => true,
			'edit_quizzes'                    => true,
			'edit_private_quizzes'            => true,
			'edit_published_quizzes'          => true,
			'delete_quizzes'                  => true,
			'delete_published_quizzes'        => true,
			'delete_private_quizzes'          => true,

			// Questions
			'publish_questions'               => true,
			'edit_questions'                  => true,
			'edit_private_questions'          => true,
			'edit_published_questions'        => true,
			'delete_questions'                => true,
			'delete_published_questions'      => true,
			'delete_private_questions'        => true,

			// Course reviews
			'publish_course_reviews'          => true,
			'edit_course_reviews'             => true,
			'edit_private_course_reviews'     => true,
			'edit_published_course_reviews'   => true,
			'delete_course_reviews'           => true,
			'delete_published_course_reviews' => true,
			'delete_private_course_reviews'   => true,

			// Users
			'list_users'                      => true,

			// Taxonomy.
			'manage_course_categories'        => true,
			'edit_course_categories'          => true,
		);

		$author_caps  = get_role( 'author' )->capabilities;
		$capabilities = array_merge( $capabilities, $author_caps );
		$capabilities = array_merge( $capabilities, self::get_student_capabilities() );

		return apply_filters( 'masteriyo_instructor_capabilities', $capabilities );
	}

	/**
	 * Get masteriyo manager capabilites.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_manager_capabilities() {
		$capabilities = array(
			// Manage settings
			'manage_masteriyo_settings'       => true,

			// Users
			'create_users'                    => true,
			'delete_users'                    => true,
			'edit_users'                      => true,
			'list_users'                      => true,
			'promote_users'                   => true,
			'remove_users'                    => true,

			// Courses
			'edit_others_courses'             => true,
			'delete_others_courses'           => true,

			// Sections
			'edit_others_sections'            => true,
			'delete_others_sections'          => true,

			// Lessons
			'edit_others_lessons'             => true,
			'delete_others_lessons'           => true,

			// Quizzes
			'edit_others_quizzes'             => true,
			'delete_others_quizzes'           => true,

			// Questions
			'edit_others_questions'           => true,
			'delete_others_questions'         => true,

			// Course reviews
			'edit_others_course_reviews'      => true,
			'delete_others_course_reviews'    => true,

			// Course QAs
			'edit_others_course_qas'          => true,
			'delete_others_course_qas'        => true,

			// Course Progrsses
			'edit_others_course_progresses'   => true,
			'delete_others_course_progresses' => true,
			'delete_course_progresses'        => true,

			// Course Categories.
			'manage_course_categories'        => true,
			'edit_course_categories'          => true,
			'delete_course_categories'        => true,
			'assign_course_categories'        => true,
		);

		$editor_caps  = get_role( 'editor' )->capabilities;
		$capabilities = array_merge( $capabilities, $editor_caps );
		$capabilities = array_merge( $capabilities, self::get_instructor_capabilities() );

		return apply_filters( 'masteriyo_manager_capabilities', $capabilities );
	}

	/**
	 * Get admin's core capabilites.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_admin_capabilities() {
		$capabilities       = array();
		$administrator_caps = get_role( 'administrator' )->capabilities;
		$capabilities       = array_merge( $capabilities, $administrator_caps );
		$capabilities       = array_merge( $capabilities, self::get_manager_capabilities() );

		return apply_filters( 'masteriyo_administrator_capabilities', $capabilities );
	}
}
