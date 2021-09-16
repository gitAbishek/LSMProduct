<?php
/**
 * Register post types.
 *
 * @since 1.0.0
 */

namespace Masteriyo\PostType;

use Masteriyo\Traits\Singleton;

class RegisterPostType {

	use Singleton;

	/**
	 * Post types.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private $post_types = array(
		'course'   => 'Masteriyo\PostType\Course',
		'lesson'   => 'Masteriyo\PostType\Lesson',
		'section'  => 'Masteriyo\PostType\Section',
		'quiz'     => 'Masteriyo\PostType\Quiz',
		'question' => 'Masteriyo\PostType\Question',
		'order'    => 'Masteriyo\PostType\Order',
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
