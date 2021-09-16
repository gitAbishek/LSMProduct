<?php
/**
 * Register post types.
 *
 * @since 1.0.0
 */

namespace Masteriyo\Taxonomy;

class RegisterTaxonomies {
	/**
	 * Register post types.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function register() {
		if ( ! is_blog_installed() ) {
			return;
		}

		do_action( 'masteriyo_register_taxonomy' );

		TaxonomyFactory::create( 'course_cat' )->register();
		TaxonomyFactory::create( 'course_tag' )->register();
		TaxonomyFactory::create( 'course_difficulty' )->register();
		TaxonomyFactory::create( 'course_visibility' )->register();

		do_action( 'masteriyo_after_register_taxonomy' );
	}
}
