<?php
/**
 * "Add to Cart" button.
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! $course->is_purchasable() ) {
	return;
}

do_action( 'masteriyo_before_add_to_cart_button' );

?>
<?php if ( masteriyo_can_start_course( $course ) ) : ?>
	<a href="<?php echo esc_url( $course->start_course_url() ); ?>" target="_blank" class="masteriyo-btn masteriyo-btn-primary masteriyo-single-course--btn mb-0">
		<?php echo esc_html( $course->single_course_start_text() ); ?>
	</a>
<?php else : ?>
	<a href="<?php echo esc_url( $course->add_to_cart_url() ); ?>" class="masteriyo-course--btn masteriyo-btn masteriyo-btn-primary">
		<?php apply_filters( 'masteriyo_add_to_cart_text', esc_html_e( 'Add to cart', 'masteriyo' ) ); ?>
	</a>
<?php endif; ?>

<?php
do_action( 'masteriyo_after_add_to_cart_button' );
