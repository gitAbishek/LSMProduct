<?php
/**
 * Class for parameter-based Section querying
 *
 * @package  ThemeGrill\Masteriyo\Query
 * @version 0.1.0
 * @since   0.1.0
 */

namespace ThemeGrill\Masteriyo\Query;

use ThemeGrill\Masteriyo\Abstracts\ObjectQuery;

defined( 'ABSPATH' ) || exit;

/**
 * Section query class.
 */
class SectionQuery extends ObjectQuery {

	/**
	 * Valid query vars for sections.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {
		return array_merge(
			parent::get_default_query_vars(),
			array(
				'description'   => '',
				'menu_order'    => '',
				'parent_id'     => '',
				'date_created'  => '',
				'date_modified' => '',
				'status'        => array( 'draft', 'pending', 'publish', 'ongoing', 'completed' ),
			)
		);
	}

	/**
	 * Get sections matching the current query vars.
	 *
	 * @since 0.0.1
	 *
	 * @return array|Model Section objects
	 */
	public function get_sections() {
		$args    = apply_filters( 'masteriyo_section_object_query_args', $this->get_query_vars() );
		$results = masteriyo('section.store' )->query( $args );
		return apply_filters( 'masteriyo_section_object_query', $results, $args );
	}
}
