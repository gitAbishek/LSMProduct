<?php
/**
 * Tab content - Reviews.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

global $course;

$review_count = $course->get_review_count();
$rating = $course->get_average_rating();

/**
 * masteriyo_before_single_course_reviews_content hook.
 */
do_action( 'masteriyo_before_single_course_reviews_content' );

?>

<div class="course-reviews mto-flex mto-justify-center mto-text-center mto-items-center mto-mt-14">
		<div class="mto-inline-block mto-px-8 mto-py-5 mto-bg-white mto-rounded-full mto-shadow-lg">
			<span class="mto-inline-block ">
				<?php masteriyo_render_stars( $rating, 'mto-text-primary mto-w-6 mto-h-6' ); ?>
			</span>

			<span class="mto-inline-block mto-ml-4 mto-text-base mto-font-medium mto-text-gray-500 "><?php echo $rating; ?> out of 5</span>
		</div>
</div>
<p class="mto-text-center mto-text-sm mto-text-gray-500 mto-mt-4"><?php echo $review_count; ?> user ratings</span>

<?php

/**
 * masteriyo_after_single_course_reviews_content hook.
 */
do_action( 'masteriyo_after_single_course_reviews_content' );
