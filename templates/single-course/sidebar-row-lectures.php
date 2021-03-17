<?php
/**
 * Sidebar row - Lectures count.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>

<div class="mto-py-4 mto-border-b mto-border-gray-200">
	<svg class="mto-inline-block mto-fill-current mto-text-primary mto-w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
		<path d="M21 3h-7a2.98 2.98 0 00-2 .78A2.98 2.98 0 0010 3H3a1 1 0 00-1 1v15a1 1 0 001 1h5.758c.526 0 1.042.214 1.414.586l1.121 1.121c.009.009.021.012.03.021.086.079.182.149.294.196h.002a.996.996 0 00.762 0h.002c.112-.047.208-.117.294-.196.009-.009.021-.012.03-.021l1.121-1.121A2.015 2.015 0 0115.242 20H21a1 1 0 001-1V4a1 1 0 00-1-1zM8.758 18H4V5h6c.552 0 1 .449 1 1v12.689A4.032 4.032 0 008.758 18zM20 18h-4.758c-.799 0-1.584.246-2.242.689V6c0-.551.448-1 1-1h6v13z"/>
	  </svg>
	<span class="mto-inline-block mto-text-xs mto-font-medium mto-text-gray-800 mto-ml-1"><?php echo masteriyo_get_lessons_count( $GLOBALS['course'] ) ?> Lectures</span>
</div>

<?php
