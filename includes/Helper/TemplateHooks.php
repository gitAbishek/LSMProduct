<?php
/**
 * Masteriyo Template Hooks
 *
 * Action/filter hooks used for Masteriyo functions/templates.
 *
 * @package Masteriyo\Templates
 * @version 1.0.0
 */


if ( ! ( function_exists( 'add_filter' ) && function_exists( 'add_action' ) ) ) {
	return;
}

add_action( 'masteriyo_template_enroll_button', 'masteriyo_template_enroll_button' );
add_action( 'masteriyo_deleted_review_notice', 'masteriyo_deleted_review_notice' );

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
add_action( 'masteriyo_after_single_course', 'masteriyo_single_course_modals' );
add_action( 'masteriyo_course_review_template', 'masteriyo_course_review_template' );
add_action( 'masteriyo_course_review_reply_template', 'masteriyo_course_review_reply_template' );
add_action( 'masteriyo_course_reviews_content', 'masteriyo_course_reviews_stats_template', 10, 3 );
add_action( 'masteriyo_course_reviews_content', 'masteriyo_course_reviews_list_template', 20, 3 );
add_action( 'masteriyo_course_reviews_content', 'masteriyo_single_course_review_form', 30, 3 );

/**
 * Account page.
 */
add_action( 'masteriyo_account_sidebar_content', 'masteriyo_account_sidebar_content' );
add_action( 'masteriyo_account_main_content', 'masteriyo_account_main_content' );
add_action( 'masteriyo_account_edit-account_endpoint', 'masteriyo_account_edit_account_endpoint' );
add_action( 'masteriyo_account_view-account_endpoint', 'masteriyo_account_view_account_endpoint' );
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


