<?php
/**
 * Sidebar row - Course difficulty.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

global $course_difficulties;

if ( empty( $course_difficulties ) ) return;

?>

<div class="mto-py-4 mto-border-b mto-border-gray-200">
	<svg class="mto-inline-block mto-fill-current mto-text-primary mto-w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
		<path d="M3 3v17a1 1 0 001 1h17v-2H5V3H3z"/>
		<path d="M15.293 14.707a.999.999 0 001.414 0l5-5-1.414-1.414L16 12.586l-2.293-2.293a.999.999 0 00-1.414 0l-5 5 1.414 1.414L13 12.414l2.293 2.293z"/>
	  </svg>
	<span class="mto-inline-block mto-text-xs mto-font-medium mto-text-gray-800 mto-ml-1">
		<?php echo implode( ' | ', array_map(function( $dif ) { return $dif->get_name(); }, $course_difficulties )); ?>
	</span>
</div>

<?php
