<?php
/**
 * "Enroll Now" button.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

global $course;
?>

<a href="<?php esc_url( $link ); ?>"
	class="btn mto-py-4 mto-px-6 md:mto-px-10 mto-text-base mto-mb-6 md:mto-mb-32">
	<?php echo esc_html( $enroll_now ); ?>
</a>

<?php
