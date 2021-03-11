<?php
/**
 * Sidebar row - Categories.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

global $course_categories;

$read_badge = '<a href="%s" class="mto-inline-block md:mto-block lg:mto-inline-block mto-bg-secondary mto-rounded-full mto-mb-3 lg:mto-mb-0 mto-px-4 mto-py-1 mto-text-xs mto-uppercase mto-font-medium mto-text-white mto-ml-2">%s</a>';
$green_badge = '<a href="%s" class="mto-inline-block md:mto-block lg:mto-inline-block mto-bg-green-400 mto-rounded-full mto-px-4 mto-py-1 mto-text-xs mto-uppercase mto-font-medium mto-text-white mto-ml-2">%s</a>';
$bool = true;


echo '<div class="mto-py-4 mto-border-b mto-border-gray-200">';

foreach ( $course_categories as $cat ) {
	$term_link = get_term_link( $cat->get_id(), $cat->get_taxonomy() );

	if ( $bool ) {
		printf( $read_badge, $term_link, $cat->get_name() );
	} else {
		printf( $green_badge, $term_link, $cat->get_name() );
	}
	$bool = ! $bool;
}

echo '</div>';
