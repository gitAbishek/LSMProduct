<?php
/**
 * Masteriyo Template Hooks
 *
 * Action/filter hooks used for Masteriyo functions/templates.
 *
 * @package Masteriyo\Templates
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! (function_exists( 'add_filter' ) && function_exists( 'add_action' ) ) ) {
	return;
}

/**
 * Single product sidebar.
 */
add_action( 'masteriyo_single_course_sidebar_content', 'masteriyo_template_sidebar_enroll_now_button', 10 );
add_action( 'masteriyo_single_course_sidebar_content', 'masteriyo_template_sidebar_row_reviews', 20 );
add_action( 'masteriyo_single_course_sidebar_content', 'masteriyo_template_sidebar_row_categories', 30 );
add_action( 'masteriyo_single_course_sidebar_content', 'masteriyo_template_sidebar_row_enrolled_students', 40 );
add_action( 'masteriyo_single_course_sidebar_content', 'masteriyo_template_sidebar_row_hours', 50 );
add_action( 'masteriyo_single_course_sidebar_content', 'masteriyo_template_sidebar_row_lectures', 60 );
add_action( 'masteriyo_single_course_sidebar_content', 'masteriyo_template_sidebar_row_difficulty', 70 );


