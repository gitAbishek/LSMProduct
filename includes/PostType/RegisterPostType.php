<?php
/**
 * Register post types.
 *
 * @since 0.1.0
 */

namespace ThemeGrill\Masteriyo\PostType;

use ThemeGrill\Masteriyo\Traits\Singleton;

class RegisterPostType {

	use Singleton;

	/**
	 * Post types.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	private $post_types = array(
		'course'   => 'ThemeGrill\Masteriyo\PostType\Course',
		'lesson'   => 'ThemeGrill\Masteriyo\PostType\Lesson',
		'section'  => 'ThemeGrill\Masteriyo\PostType\Section',
		'quiz'     => 'ThemeGrill\Masteriyo\PostType\Quiz',
		'question' => 'ThemeGrill\Masteriyo\PostType\Question',
		'order'    => 'ThemeGrill\Masteriyo\PostType\Order',
	);

	/**
	 * Register post types
	 */
	public function register() {
		if ( ! is_blog_installed() ) {
			return;
		}

		do_action( 'masteriyo_register_post_type' );

		$post_types = apply_filters( 'masteriyo_register_post_types', $this->post_types );
		foreach ( $post_types as $post_type => $class ) {
			$post_type = new $class();
			$post_type->register();
		}

		do_action( 'masteriyo_after_register_post_type' );
	}
}
