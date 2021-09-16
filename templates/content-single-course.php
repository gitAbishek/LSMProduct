<?php
/**
 * The Template for displaying all single course detail
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/single-course/content-single-course.php.
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

defined( 'ABSPATH' ) || exit;

global $course;

// Ensure visibility.
if ( empty( $course ) || ! $course->is_visible() ) {
	return;
}

/**
 * Action Hook: masteriyo_before_single_course_content
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_before_single_course_content' );

$difficulty = $course->get_difficulty();

?>
<?php masteriyo_display_all_notices(); ?>

<div id="course-<?php the_ID(); ?>" class="masteriyo-single-course">
	<div class="masteriyo-col-8">
		<div class="masteriyo-single-course--main masteriyo-course--content">
			<div class="masteriyo-course--img-wrap">
				<!-- Diffculty Badge -->
				<?php if ( $difficulty ) : ?>
				<div class="difficulty-badge">
					<span class="masteriyo-badge <?php echo esc_attr( masteriyo_get_difficulty_badge_css_class( $difficulty['slug'] ) ); ?>"><?php echo esc_html( $difficulty['name'] ); ?></span>
				</div>
				<?php endif; ?>

				<?php do_action( 'masteriyo_single_course_featured_image' ); ?>
			</div>

			<!-- Category -->
			<?php do_action( 'masteriyo_single_course_categories' ); ?>

			<!-- Title -->
			<?php do_action( 'masteriyo_single_course_title' ); ?>

			<!-- Author and rating -->
			<?php do_action( 'masteriyo_single_course_author_and_rating' ); ?>

			<!-- Main contents: Overview, Curriculum, Reviews -->
			<?php do_action( 'masteriyo_single_course_main_content' ); ?>
		</div>
	</div>

	<div class="masteriyo-col-4">
		<aside class="masteriyo-single-course--aside masteriyo-course--content">
			<!-- Price and Enroll Now Button -->
			<?php do_action( 'masteriyo_single_course_price_and_enroll_button' ); ?>
			<!-- Course Stats -->
			<?php do_action( 'masteriyo_single_course_stats' ); ?>

			<!-- Course Highlights -->
			<?php do_action( 'masteriyo_single_course_highlights' ); ?>
		</aside>
</div>
<?php
/**
 * Action Hook: masteriyo_after_single_course_content
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_after_single_course_content' );
