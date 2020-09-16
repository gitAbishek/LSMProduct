<?php
/**
 * Register post types.
 */

namespace ThemeGrill\Masteriyo\PostType;

class PostTypeFactory {

	/**
	 * Post type slug.
	 *
	 * @since 0.1.0
	 *
	 * @param string $slug Post type slug.
	 *
	 * @return ThemeGrill\Masteriyo\PostType\PostType;
	 */
	public static function create( $slug, $labels = array(), $supports = array() ) {
		return new PostType( $slug, $labels, $supports );
	}
}
