<?php
/**
 * Class for parameter-based Faq querying
 *
 * @package  Masteriyo\Query
 * @version 0.1.0
 * @since   0.1.0
 */

namespace Masteriyo\Query;

use Masteriyo\Abstracts\ObjectQuery;

defined( 'ABSPATH' ) || exit;

/**
 * Faq query class.
 */
class FaqQuery extends ObjectQuery {

	/**
	 * Valid query vars for Faqs.
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
	 * Get Faqs matching the current query vars.
	 *
	 * @since 0.0.1
	 *
	 * @return array|Model Faq objects
	 */
	public function get_faqs() {
		$args    = apply_filters( 'masteriyo_faq_object_query_args', $this->get_query_vars() );
		$results = masteriyo( 'faq.store' )->query( $args );
		return apply_filters( 'masteriyo_faq_object_query', $results, $args );
	}
}
