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
		TaxonomyFactory::create( 'course_cat' )->register();
		TaxonomyFactory::create( 'course_tag' )->register();
		TaxonomyFactory::create( 'course_difficulty' )->register();
		TaxonomyFactory::create( 'course_visibility' )->register();
		TaxonomyFactory::create( 'lesson_cat' )->register();
		TaxonomyFactory::create( 'lesson_tag' )->register();
	}
}
