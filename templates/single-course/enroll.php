<?php
/**
 * "Add to Cart" button.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

global $course;
?>

<?php do_action( 'masteriyo_before_add_to_cart_form' ); ?>

	<form class="add_to_cart" method="post" enctype="multipart/form-data"
		action="<?php echo esc_url( apply_filters( 'masteriyo_add_to_cart_form_action', $course->get_permalink() ) ); ?>">

		<?php do_action( 'masteriyo_before_add_to_cart_button' ); ?>

		<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $course->get_id() ); ?>"
			class="single_add_to_cart_button button alt btn mto-py-4 mto-px-6 md:mto-px-10 mto-text-base mto-mb-6 md:mto-mb-32">
			<?php echo esc_html( $course->single_add_to_cart_text() ); ?>
		</button>

		<?php do_action( 'masteriyo_after_add_to_cart_button' ); ?>

	</form>

<?php do_action( 'masteriyo_after_add_to_cart_form' ); ?>
<?php
