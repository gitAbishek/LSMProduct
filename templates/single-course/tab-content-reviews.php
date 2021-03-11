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
			<span class="mto-inline-block "><?php
			masteriyo_render_stars(
				$rating,
				5,
				'<svg class="mto-inline-block mto-fill-current mto-text-primary mto-w-6 mto-h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"/>
				</svg>',
				'<svg class=" mto-inline-block mto-fill-current mto-text-primary mto-w-6 mto-h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M5.025 20.775A.998.998 0 006 22a1 1 0 00.555-.168L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082a1 1 0 00-.59-1.74l-5.701-.454-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.214 4.107-1.491 6.452zM12 5.429l2.042 4.521.588.047h.001l3.972.315-3.271 2.944-.001.002-.463.416.171.597v.003l1.253 4.385L12 15.798V5.429z"/>
				</svg>',
				'<svg class=" mto-inline-block mto-fill-current mto-text-primary mto-w-6 mto-h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M6.516 14.323l-1.49 6.452a.998.998 0 001.529 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082a1 1 0 00-.59-1.74l-5.701-.454-2.467-5.461a.998.998 0 00-1.822 0L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.214 4.107zm2.853-4.326a.998.998 0 00.832-.586L12 5.43l1.799 3.981a.998.998 0 00.832.586l3.972.315-3.271 2.944c-.284.256-.397.65-.293 1.018l1.253 4.385-3.736-2.491a.995.995 0 00-1.109 0l-3.904 2.603 1.05-4.546a1 1 0 00-.276-.94l-3.038-2.962 4.09-.326z"/>
				</svg>'
			);
				?>
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
