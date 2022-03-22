<?php
/**
 * "Add to Cart" button.
 *
 * @version 1.0.0
*/

use Masteriyo\Enums\CourseProgressStatus;
use Masteriyo\Notice;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! $course->is_purchasable() ) {
	return;
}

do_action( 'masteriyo_before_add_to_cart_button' );

?>

<?php if ( masteriyo_can_start_course( $course ) ) : ?>
	<?php if ( $progress && CourseProgressStatus::COMPLETED === $progress->get_status() ) : ?>
		<a href="<?php echo esc_url( $course->start_course_url() ); ?>" target="_blank" class="masteriyo-btn masteriyo-course-complete masteriyo-btn-primary masteriyo-single-course--btn mb-0">
			<?php echo esc_html( $course->single_course_completed_text() ); ?>
		</a>
	<?php elseif ( $progress && CourseProgressStatus::PROGRESS === $progress->get_status() ) : ?>
		<a href="<?php echo esc_url( $course->start_course_url() ); ?>" target="_blank" class="masteriyo-btn masteriyo-course-continue masteriyo-btn-primary masteriyo-single-course--btn mb-0">
			<?php echo esc_html( $course->single_course_continue_text() ); ?>
		</a>
	<?php else : ?>
		<a href="<?php echo esc_url( $course->start_course_url() ); ?>" target="_blank" class="masteriyo-btn masteriyo-course-start-course masteriyo-btn-primary masteriyo-single-course--btn mb-0">
			<?php echo esc_html( $course->single_course_start_text() ); ?>
		</a>
	<?php endif; ?>
<?php else : ?>
	<a href="<?php echo esc_url( $course->add_to_cart_url() ); ?>" class="masteriyo-course--btn masteriyo-btn masteriyo-btn-primary">
		<?php echo esc_html( apply_filters( 'masteriyo_add_to_cart_text', __( 'Buy Now', 'masteriyo' ) ) ); ?>
	</a>
<?php endif; ?>

<?php

if ( 0 !== $course->get_enrollment_limit() && 0 === $course->get_available_seats() && ! masteriyo_can_start_course( $course ) ) {
	masteriyo_display_notice(
		esc_html__( 'Sorry, students limit reached. Course closed for enrollment.', 'masteriyo' ),
		Notice::WARNING
	);
}

do_action( 'masteriyo_after_add_to_cart_button' );
