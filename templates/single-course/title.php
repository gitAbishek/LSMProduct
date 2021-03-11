<?php
/**
 * Single course title.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

global $course;

?>

<h2 class="mto-font-bold mto-text-4xl mto-flex mto-mb-14">
	<?php echo $course->get_name(); ?>
	<span class="mto-self-start mto-rounded-full mto-text-white mto-uppercase mto-font-medium mto-text-xs mto-bg-red-500 mto-px-3 mto-py-1 mto-ml-2">Hot</span>
</h2>

<?php
