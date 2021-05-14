<?php
/**
 * Capabilities class.
 *
 * @since 0.1.0
 */

namespace ThemeGrill\Masteriyo;

/**
 * Capabilities class.
 */
class Capabilities {
	/**
	 * Get masteriyo manager capabilites.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public static function get_manager_capabilities() {
		$core_capabilities = array(
			'read_users',

			'delete_course',
			'delete_courses',
			'delete_others_courses',
			'delete_published_courses',
			'read_courses',

			'delete_section',
			'delete_sections',
			'delete_others_sections',
			'delete_published_sections',
			'read_sections',

			'delete_lesson',
			'delete_lessons',
			'delete_others_lessons',
			'delete_published_lessons',
			'read_lessons',

			'delete_quiz',
			'delete_quizes',
			'delete_others_quizes',
			'delete_published_quizes',
			'read_quizes',

			'delete_question',
			'delete_questions',
			'delete_others_questions',
			'delete_published_questions',
			'read_questions',

			'manage_masteriyo_setting',

			'delete_order',
			'delete_orders',
			'delete_others_orders',
			'delete_published_orders',
			'read_orders',
			'read_others_orders',
			'edit_order',
			'edit_orders',
			'edit_others_orders',
			'edit_published_orders',

			'delete_faq',
			'delete_faqs',
			'delete_others_faqs',
			'delete_published_faqs',
			'read_faqs',

			'delete_course_review',
			'delete_course_reviews',
			'delete_others_course_reviews',
			'delete_published_course_reviews',
			'read_course_reviews',
			'edit_course_review',
			'edit_course_reviews',
			'edit_others_course_reviews',
			'edit_published_course_reviews',
		);

		return apply_filters(
			'masteriyo_get_manager_capabilities',
			array_merge(
				array_fill_keys( $core_capabilities, true ),
				get_role( 'editor' )->capabilities
			)
		);
	}

	/**
	 * Get masteriyo instructor capabilites.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public static function get_instructor_capabilities() {
		$core_capabilities = array(
			'read_users',

			'publish_courses',
			'delete_courses',
			'delete_published_courses',
			'read_courses',
			'edit_course',
			'edit_courses',
			'edit_published_courses',

			'publish_lessons',
			'delete_lessons',
			'delete_published_lessons',
			'delete_lesson',
			'read_lessons',
			'edit_lesson',
			'edit_lessons',
			'edit_published_lessons',

			'publish_sections',
			'delete_sections',
			'delete_published_sections',
			'delete_section',
			'read_sections',
			'edit_section',
			'edit_sections',
			'edit_published_sections',

			'publish_quizes',
			'delete_quizes',
			'delete_published_quizes',
			'delete_quiz',
			'read_quizes',
			'edit_quize',
			'edit_quizes',
			'edit_published_quizes',

			'publish_questions',
			'delete_questions',
			'delete_published_questions',
			'delete_question',
			'read_questions',
			'edit_question',
			'edit_questions',
			'edit_published_questions',

			'publish_faqs',
			'delete_faqs',
			'delete_published_faqs',
			'delete_faq',
			'read_faqs',
			'edit_faq',
			'edit_faqs',
			'edit_published_faqs',

			'read_course_reviews',
		);

		return apply_filters(
			'masteriyo_get_instructor_capabilities',
			array_merge(
				array_fill_keys( $core_capabilities, true ),
				get_role( 'author' )->capabilities
			)
		);
	}

	/**
	 * Get masteriyo student capabilites.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public static function get_student_capabilities() {
		$core_capabilities = array(
			'read_users',
			'read_courses',
			'read_lessons',
			'read_sections',
			'read_quizes',
			'read_questions',
			'read_faqs',

			'publish_orders',
			'delete_orders',
			'delete_published_orders',
			'delete_order',
			'read_orders',
			'edit_order',
			'edit_orders',
			'edit_published_orders',

			'publish_course_reviews',
			'delete_course_reviews',
			'delete_published_course_reviews',
			'delete_course_review',
			'read_course_reviews',
			'edit_course_review',
			'edit_course_reviews',
			'edit_published_course_reviews',
		);

		return apply_filters(
			'masteriyo_get_student_capabilities',
			array_merge(
				array_fill_keys( $core_capabilities, true ),
				get_role( 'contributor' )->capabilities
			)
		);
	}

	/**
	 * Get admin's core capabilites.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public static function get_admin_capabilities() {
		$core_capabilities = array(
			'delete_course',
			'delete_courses',
			'delete_others_courses',
			'delete_published_courses',
			'read_courses',

			'delete_lesson',
			'delete_lessons',
			'delete_others_lessons',
			'delete_published_lessons',
			'read_lessons',

			'delete_section',
			'delete_sections',
			'delete_others_sections',
			'delete_published_sections',
			'read_sections',

			'delete_quiz',
			'delete_quizes',
			'delete_others_quizes',
			'delete_published_quizes',
			'read_quizes',

			'delete_question',
			'delete_questions',
			'delete_others_questions',
			'delete_published_questions',
			'read_questions',

			'manage_masteriyo_setting',

			'delete_order',
			'delete_orders',
			'delete_others_orders',
			'delete_published_orders',
			'read_orders',
			'read_others_orders',
			'edit_order',
			'edit_orders',
			'edit_others_orders',
			'edit_published_orders',

			'delete_faq',
			'delete_faqs',
			'delete_others_faqs',
			'delete_published_faqs',
			'read_faqs',

			'delete_course_review',
			'delete_course_reviews',
			'delete_others_course_reviews',
			'delete_published_course_reviews',
			'read_course_reviews',
			'edit_course_review',
			'edit_course_reviews',
			'edit_others_course_reviews',
			'edit_published_course_reviews',
		);

		return apply_filters(
			'masteriyo_get_administrator_capabilities',
			array_fill_keys( $core_capabilities, true ),
		);
	}
}
