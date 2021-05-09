<?php
/**
 * Single course title.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

global $course;

?>

<h2 class="mto-scourse--title">
	<?php echo esc_html( $course->get_name() ); ?>

	<?php if ( $course->is_featured() ) : ?>Hot
	<span class="mto-scourse--tag mto-bg-red"><?php echo esc_html__( 'Hot', 'masteriyo' ); ?></span>
	<?php endif ?>
</h2>

<?php
