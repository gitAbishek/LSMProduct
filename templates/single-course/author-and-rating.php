<?php
/**
 * The Template for displaying Author and rating for single course
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/single-course/author-and-rating.php.
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

do_action( 'masteriyo_before_single_course_author_and_rating' );

?>
<div class="mto-course--content__rt">
	<?php if ( $author ) : ?>
	<div class="mto-course-author">
		<img src="<?php echo esc_attr( $author->get_avatar_url() ); ?>">
		<?php /* translators: %s: Username */ ?>
		<span class="mto-course-author--name"><?php echo esc_attr( sprintf( __( 'by %s', 'masteriyo' ), $author->get_display_name() ) ); ?></span>
	</div>
	<?php endif; ?>
	<span class="mto-icon-svg mto-rating">
		<?php masteriyo_format_rating( $course->get_average_rating(), true ); ?> <?php echo esc_html( masteriyo_format_decimal( $course->get_average_rating(), 1, true ) ); ?> (<?php echo esc_html( $course->get_rating_count() ); ?>)
	</span>
</div>
<?php

do_action( 'masteriyo_after_single_course_author_and_rating' );
