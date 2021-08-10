<?php
/**
 * The Template for displaying course highlights in single course page
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/single-course/highlights.php.
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

do_action( 'masteriyo_before_single_course_highlights' );

?>

<?php if ( ! empty( $course->get_highlights() ) ) : ?>
	<div class="mto-course-description">
		<h5 class="title"><?php esc_html_e( 'This course includes', 'masteriyo' ); ?></h5>
		<?php echo apply_filters( 'masteriyo_single_course_highlights_content', $course->get_highlights() ); ?>
	</div>
<?php endif; ?>

<?php

do_action( 'masteriyo_after_single_course_highlights' );
