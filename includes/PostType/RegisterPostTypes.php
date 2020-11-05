<?php
/**
 * Register post types.
 *
 * @since 0.1.0
 */

namespace ThemeGrill\Masteriyo\PostType;

use ThemeGrill\Masteriyo\Traits\Singleton;

class RegisterPostTypes {

	use Singleton;

	/**
	 * Post types.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	private $post_types = array(
		'courses'  => 'ThemeGrill\Masteriyo\PostType\Courses',
		'lessons'  => 'ThemeGrill\Masteriyo\PostType\Lessons',
		'sections' => 'ThemeGrill\Masteriyo\PostType\Sections',
		'quizes'   => 'ThemeGrill\Masteriyo\PostType\Quizes',
	);

	/**
	 * Default constructor.
	 */
	private function __construct() {
		foreach ( $this->post_types as $post_type => $class ) {
			$post_type = new $class();
			$post_type->register();
		}
	}
}
