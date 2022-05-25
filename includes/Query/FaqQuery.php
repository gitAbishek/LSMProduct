<?php
/**
 * Class for parameter-based Faq querying
 *
 * @package  Masteriyo\Query
 * @version 1.0.0
 * @since   1.0.0
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
	 * @since 1.0.0
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
	 * @since 1.0.0
	 *
	 * @return Masteriyo\Models\CourseFaq[] Faq objects
	 */
	public function get_faqs() {
		/**
		 * Filters FAQ object query args.
		 *
		 * @since 1.0.0
		 *
		 * @param array $query_args The object query args.
		 */
		$args    = apply_filters( 'masteriyo_faq_object_query_args', $this->get_query_vars() );
		$results = masteriyo( 'faq.store' )->query( $args );

		/**
		 * Filters FAQ object query results.
		 *
		 * @since 1.0.0
		 *
		 * @param Masteriyo\Models\CourseFaq $results The query results.
		 * @param array $query_args The object query args.
		 */
		return apply_filters( 'masteriyo_faq_object_query', $results, $args );
	}
}
