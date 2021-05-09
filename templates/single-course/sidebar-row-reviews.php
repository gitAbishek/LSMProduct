<?php
/**
 * Sidebar row - Reviews.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

global $course;
?>

<div class="mto-rating-reviews mto-scourse--mdetail mto-flex">
	<span class="mto-rating mto-icon-svg mto-flex">
		<?php masteriyo_render_stars( $rating, '' );?>
	</span>

	<span class="mto-reviews">
		<?php echo esc_html( $review_count ); ?> <?php echo esc_html( $review_text ) ?>
	</span>
</div>

<?php
