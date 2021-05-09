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

<div class="mto-stab--treviews">
		<div class="mto-stab-rs mto-flex mto-flex-ycenter">
			<span class="mto-icon-svg mto-flex mto-rstar">
				<?php masteriyo_render_stars( $rating, '' ); ?>
			</span>

			<span class="mto-rnumber"><?php echo esc_html( $rating ); ?> out of 5</span>
		</div>
</div>
<p class="mto-stab--turating">
	<?php /* translators: %s: Review Count */ ?>
	<span><?php printf( esc_html__( '%s user ratings', 'masteriyo' ), esc_html( $review_count ) ); ?></span>
</p>

<?php

/**
 * masteriyo_after_single_course_reviews_content hook.
 */
do_action( 'masteriyo_after_single_course_reviews_content' );
