<?php
/**
 * The Template for displaying course overview in single course page
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/single-course/overview.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

global $course;

/**
 * masteriyo_before_single_course_overview hook.
 */
do_action( 'masteriyo_before_single_course_overview' );

?>
<div class="tab-content course-overview plr-32">
	<?php the_content(); ?>
</div>
<?php

/**
 * masteriyo_after_single_course_overview hook.
 */
do_action( 'masteriyo_after_single_course_overview' );
