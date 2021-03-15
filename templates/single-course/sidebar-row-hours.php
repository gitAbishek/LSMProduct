<?php
/**
 * Sidebar row - Total hours.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>

<div class="mto-py-4 mto-border-b mto-border-gray-200">
	<svg class="mto-inline-block mto-fill-current mto-text-primary mto-w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
		<path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"/>
		<path d="M13 7h-2v6h6v-2h-4z"/>
	</svg>
	<span class="mto-inline-block mto-text-xs mto-font-medium mto-text-gray-800 mto-ml-1">
		<?php echo masteriyo_get_lecture_hours( $GLOBALS['course'] ) ?>
	</span>
</div>

<?php
