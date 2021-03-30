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

/**
 * masteriyo_single_course_sidebar_content
 */
do_action( 'masteriyo_single_course_sidebar_content' );

/**
 * masteriyo_before_single_course_sidebar_content hook.
 */
do_action( 'masteriyo_after_single_course_sidebar_content' );

