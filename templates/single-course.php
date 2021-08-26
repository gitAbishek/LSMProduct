<?php
/**
 * The Template for displaying single course.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'masteriyo-course' );

/**
 * Wrapper div opening.
 *
 * @since 0.1.0
 */
echo '<div class="w-100">';

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

/**
 * Wrapper div closing.
 *
 * @since 0.1.0
 */
echo '</div>';

get_footer( 'masteriyo-course' );
