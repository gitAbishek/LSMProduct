<?php
/**
 * Register post types.
 *
 * @since 0.1.0
 */

namespace ThemeGrill\Masteriyo\Taxonomy;

class RegisterTaxonomies {
	/**
	 * Register post types.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public static function register() {
		TaxonomyFactory::create( 'courses_cat' )->register();
		TaxonomyFactory::create( 'courses_tag' )->register();
		TaxonomyFactory::create( 'courses_difficulty' )->register();
		TaxonomyFactory::create( 'lessons_cat' )->register();
		TaxonomyFactory::create( 'lessons_tag' )->register();
	}
}
