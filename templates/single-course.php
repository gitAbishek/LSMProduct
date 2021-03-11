<?php
/**
 * The Template for displaying single course.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

get_header();

/**
 * masteriyo_before_single_course hook.
 */
do_action( 'masteriyo_before_single_course' );

masteriyo_get_template_part( 'content', 'single-course' );

/**
 * masteriyo_after_single_course hook.
 */
do_action( 'masteriyo_after_single_course' );

/**
 * masteriyo_before_related_posts hook.
 */
do_action( 'masteriyo_before_related_posts' );

masteriyo_get_template_part( 'content', 'related-posts' );

/**
 * masteriyo_after_related_posts hook.
 */
do_action( 'masteriyo_after_related_posts' );

get_footer();
