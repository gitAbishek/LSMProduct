<?php

/**
 * Get full list of course visibilty term ids.
 *
 * @since  0.1.0
 * @return int[]
 */
function masteriyo_get_course_visibility_term_ids() {
	if ( ! taxonomy_exists( 'course_visibility' ) ) {
		masteriyo_doing_it_wrong( __FUNCTION__, 'masteriyo_get_course_visibility_term_ids should not be called before taxonomies are registered (woocommerce_after_register_post_type action).', '3.1' );
		return array();
	}
	return array_map(
		'absint',
		wp_parse_args(
			wp_list_pluck(
				get_terms(
					array(
						'taxonomy'   => 'course_visibility',
						'hide_empty' => false,
					)
				),
				'term_taxonomy_id',
				'name'
			),
			array(
				'exclude-from-catalog' => 0,
				'exclude-from-search'  => 0,
				'featured'             => 0,
				'outofstock'           => 0,
				'rated-1'              => 0,
				'rated-2'              => 0,
				'rated-3'              => 0,
				'rated-4'              => 0,
				'rated-5'              => 0,
			)
		)
	);
}
