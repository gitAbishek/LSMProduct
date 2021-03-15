<?php
/**
 * Sidebar row - Reviews.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

global $course;

$review_count = $course->get_review_count();
$rating = $course->get_average_rating();

?>

<div class="mto-py-4 mto-border-b mto-border-gray-200">
	<span class="mto-inline-block">
		<?php masteriyo_render_stars( $rating, 'single-course-page--sidebar-row' );?>
	</span>

	<span class="mto-inline-block mto-text-xs mto-font-medium mto-text-gray-600 mto-ml-2"><?php echo $review_count; ?> reviews</span>
</div>

<?php
