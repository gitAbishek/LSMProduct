<?php
/**
 * The Template for displaying price and enroll button in single course page
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/single-course/price-and-enroll-button.php.
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

do_action( 'masteriyo_before_single_course_price_and_enroll_button' );

?>
<div class="mto-time-btn">
	<div class="mto-course-price">
		<span class="current-amount"><?php echo masteriyo_price( $course->get_price() ); ?></span>
	</div>
	<?php do_action( 'masteriyo_template_enroll_button', $course ); ?>
</div>
<?php

do_action( 'masteriyo_after_single_course_price_and_enroll_button' );
