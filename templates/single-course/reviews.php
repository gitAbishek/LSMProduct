<?php
/**
 * The Template for displaying course reviews in single course page
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/single-course/reviews.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * masteriyo_before_single_course_reviews hook.
 */
do_action( 'masteriyo_before_single_course_reviews' );

?>
<div class="tab-content course-reviews masteriyo-hidden">
	<?php do_action( 'masteriyo_course_reviews_content', $course, $course_reviews, $replies ); ?>
</div>
<?php

/**
 * masteriyo_after_single_course_reviews hook.
 */
do_action( 'masteriyo_after_single_course_reviews' );
