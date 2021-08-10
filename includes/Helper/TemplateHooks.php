<?php
/**
 * Masteriyo Template Hooks
 *
 * Action/filter hooks used for Masteriyo functions/templates.
 *
 * @package Masteriyo\Templates
 * @version 0.1.0
 */


if ( ! ( function_exists( 'add_filter' ) && function_exists( 'add_action' ) ) ) {
	return;
}

add_action( 'masteriyo_template_enroll_button', 'masteriyo_template_enroll_button' );

/**
 * Course Archive.
 */
add_action( 'masteriyo_after_main_content', 'masteriyo_archive_navigation' );

/**
 * Single course.
 */
add_action( 'masteriyo_single_course_featured_image', 'masteriyo_single_course_featured_image' );
add_action( 'masteriyo_single_course_categories', 'masteriyo_single_course_categories' );
add_action( 'masteriyo_single_course_title', 'masteriyo_single_course_title' );
add_action( 'masteriyo_single_course_author_and_rating', 'masteriyo_single_course_author_and_rating' );
add_action( 'masteriyo_single_course_price_and_enroll_button', 'masteriyo_single_course_price_and_enroll_button' );
add_action( 'masteriyo_single_course_stats', 'masteriyo_single_course_stats' );
add_action( 'masteriyo_single_course_highlights', 'masteriyo_single_course_highlights' );
add_action( 'masteriyo_single_course_main_content', 'masteriyo_single_course_main_content' );
add_action( 'masteriyo_single_course_content', 'masteriyo_single_course_tab_handles', 10 );
add_action( 'masteriyo_single_course_content', 'masteriyo_single_course_overview', 20 );
add_action( 'masteriyo_single_course_content', 'masteriyo_single_course_curriculum', 30 );
add_action( 'masteriyo_single_course_content', 'masteriyo_single_course_reviews', 40 );
add_action( 'masteriyo_single_course_review_form', 'masteriyo_single_course_review_form' );

/**
 * My account page.
 */
add_action( 'masteriyo_myaccount_sidebar_content', 'masteriyo_myaccount_sidebar_content' );
add_action( 'masteriyo_myaccount_main_content', 'masteriyo_myaccount_main_content' );
add_action( 'masteriyo_account_edit-account_endpoint', 'masteriyo_account_edit_myaccount_endpoint' );
add_action( 'masteriyo_account_view-myaccount_endpoint', 'masteriyo_account_view_myaccount_endpoint' );
add_action( 'masteriyo_account_courses_endpoint', 'masteriyo_account_courses_endpoint' );
add_action( 'masteriyo_account_order-history_endpoint', 'masteriyo_account_order_history_endpoint' );
add_action( 'masteriyo_account_view-order_endpoint', 'masteriyo_account_view_order_endpoint' );

/**
 * Emails.
 */
add_action( 'masteriyo_email_header', 'masteriyo_email_header' );
add_action( 'masteriyo_email_footer', 'masteriyo_email_footer' );
add_action( 'masteriyo_email_order_details', 'masteriyo_email_order_details', 20 );
add_action( 'masteriyo_email_order_meta', 'masteriyo_email_order_meta', 10 );
add_action( 'masteriyo_email_customer_details', 'masteriyo_email_customer_addresses', 20 );


/**
 * Checkout form.
 */
add_action( 'masteriyo_checkout_summary', 'masteriyo_checkout_order_summary', 10 );
add_action( 'masteriyo_checkout_summary', 'masteriyo_checkout_payment', 20 );


