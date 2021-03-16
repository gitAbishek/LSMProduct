<?php
/**
 * Class for parameter-based FAQ querying
 *
 * @package  ThemeGrill\Masteriyo\Query
 * @version 0.1.0
 * @since   0.1.0
 */

namespace ThemeGrill\Masteriyo\Query;

use ThemeGrill\Masteriyo\Abstracts\ObjectQuery;

defined( 'ABSPATH' ) || exit;

/**
 * FAQ query class.
 */
class FAQsQuery extends ObjectQuery {

	/**
	 * Valid query vars for FAQs.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {
		return array_merge(
			parent::get_default_query_vars(),
			array(
				'menu_order'    => '',
				'parent_id'     => '',
				'date_created'  => '',
				'date_modified' => '',
			)
		);
	}

	/**
	 * Get FAQs matching the current query vars.
	 *
	 * @since 0.0.1
	 *
	 * @return array|Model FAQ objects
	 */
	public function get_faqs() {
		$args    = apply_filters( 'masteriyo_faq_object_query_args', $this->get_query_vars() );
		$results = masteriyo('faq.store' )->query( $args );
		return apply_filters( 'masteriyo_faq_object_query', $results, $args );
	}
}
