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

/**
 * My account page.
 */
add_action( 'masteriyo_myaccount_sidebar_content', 'masteriyo_myaccount_sidebar_content' );
add_action( 'masteriyo_myaccount_main_content', 'masteriyo_myaccount_main_content' );
add_action( 'masteriyo_account_edit-myaccount_endpoint', 'masteriyo_account_edit_myaccount_endpoint' );
add_action( 'masteriyo_account_view-myaccount_endpoint', 'masteriyo_account_view_myaccount_endpoint' );
add_action( 'masteriyo_account_courses_endpoint', 'masteriyo_account_courses_endpoint' );
add_action( 'masteriyo_account_grades_endpoint', 'masteriyo_account_grades_endpoint' );
add_action( 'masteriyo_account_memberships_endpoint', 'masteriyo_account_memberships_endpoint' );
add_action( 'masteriyo_account_certificates_endpoint', 'masteriyo_account_certificates_endpoint' );
add_action( 'masteriyo_account_order-history_endpoint', 'masteriyo_account_order_history_endpoint' );

/**
 * Emails.
 */
add_action( 'masteriyo_email_header', 'masteriyo_email_header' );
add_action( 'masteriyo_email_footer', 'masteriyo_email_footer' );

