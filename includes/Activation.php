<?php
/**
 * Activation class.
 *
 * @since 0.1.0
 */

namespace ThemeGrill\Masteriyo;

class Activation {

	/**
	 * Initialization.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public static function init() {
		self::create_pages();
	}
	
	/**
	 * Create pages that the plugin relies on, storing page IDs in variables.
	 *
	 * @since 0.1.0
	 */
	public static function create_pages() {
		include_once dirname( __FILE__ ) . '/Helper/Core.php';

		$pages = apply_filters(
			'masteriyo_create_pages',
			array(
				'course-list'        => array(
					'name'    => _x( 'course-list', 'Page slug', 'masteriyo' ),
					'title'   => _x( 'Masteriyo Course List', 'Page title', 'masteriyo' ),
					'content' => '',
				),
				'myaccount'          => array(
					'name'    => _x( 'myaccount', 'Page slug', 'masteriyo' ),
					'title'   => _x( 'Masteriyo My Account', 'Page title', 'masteriyo' ),
					'content' => '<!-- wp:shortcode -->[' . apply_filters( 'masteriyo_myaccount_shortcode_tag', 'masteriyo_myaccount' ) . ']<!-- /wp:shortcode -->',
				),
				'masteriyo-checkout' => array(
					// Checkout slug is 'masteriyo-checkout' as 'checkout' slug might be used by other plugins like WooCommerce.
					'name'    => _x( 'masteriyo-checkout', 'Page slug', 'masteriyo' ),
					'title'   => _x( 'Masteriyo Checkout', 'Page title', 'masteriyo' ),
					'content' => '<!-- wp:shortcode -->[' . apply_filters( 'masteriyo_checkout_shortcode_tag', 'masteriyo_checkout' ) . ']<!-- /wp:shortcode -->',
				),
			)
		);

		foreach ( $pages as $key => $page ) {
			masteriyo_create_page( esc_sql( $page['name'] ), 'masteriyo_' . $key . '_page_id', $page['title'], $page['content'], ! empty( $page['parent'] ) ? masteriyo_get_page_id( $page['parent'] ) : '' );
		}
	}

}