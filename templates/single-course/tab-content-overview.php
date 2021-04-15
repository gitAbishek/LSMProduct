<?php
/**
 * Tab content - Overview.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

global $course;

/**
 * masteriyo_before_single_course_overview_content hook.
 */
do_action( 'masteriyo_before_single_course_overview_content' );

the_content();

/**
 * masteriyo_after_single_course_overview_content hook.
 */
do_action( 'masteriyo_after_single_course_overview_content' );
