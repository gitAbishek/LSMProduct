<?php
/**
 * Sidebar row - Categories.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$categories = $GLOBALS['course']->get_categories();

if ( empty( $categories ) ) return;

echo '<div class="mto-py-4 mto-border-b mto-border-gray-200">';

foreach ( $categories as $index => $cat ) {
	if ( ($index + 1) % 2 === 1 ) {
		// Red badge.
		printf(
			'<a href="%s" class="mto-inline-block md:mto-block lg:mto-inline-block mto-bg-secondary mto-rounded-full mto-mb-3 lg:mto-mb-0 mto-px-4 mto-py-1 mto-text-xs mto-uppercase mto-font-medium mto-text-white mto-ml-2">%s</a>',
			$cat->get_permalink(),
			$cat->get_name()
		);
	} else {
		// Green badge.
		printf(
			'<a href="%s" class="mto-inline-block md:mto-block lg:mto-inline-block mto-bg-green-400 mto-rounded-full mto-px-4 mto-py-1 mto-text-xs mto-uppercase mto-font-medium mto-text-white mto-ml-2">%s</a>',
			$cat->get_permalink(),
			$cat->get_name()
		);
	}
}

echo '</div>';
