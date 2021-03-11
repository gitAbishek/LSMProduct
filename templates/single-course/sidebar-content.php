<?php
/**
 * Single course page's sidebar.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * masteriyo_before_single_course_sidebar_content hook.
 */
do_action( 'masteriyo_before_single_course_sidebar_content' );


masteriyo_get_template( 'single-course/enroll-now-button.php' );

masteriyo_get_template( 'single-course/sidebar-row-reviews.php' );

masteriyo_get_template( 'single-course/sidebar-row-categories.php' );

masteriyo_get_template( 'single-course/sidebar-row-enrolled-students.php' );

masteriyo_get_template( 'single-course/sidebar-row-hours.php' );

masteriyo_get_template( 'single-course/sidebar-row-lectures.php' );

masteriyo_get_template( 'single-course/sidebar-row-difficulty.php' );


/**
 * masteriyo_before_single_course_sidebar_content hook.
 */
do_action( 'masteriyo_after_single_course_sidebar_content' );

