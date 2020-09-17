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
<<<<<<< HEAD
	private $post_types = array(
		'courses' => 'ThemeGrill\Masteriyo\PostType\Courses',
		'lessons' => 'ThemeGrill\Masteriyo\PostType\Lessons',
		'quizes'  => 'ThemeGrill\Masteriyo\PostType\Quizes',
	);

	/**
	 * Default constructor.
	 */
	private function __construct() {
		foreach( $this->post_types as $post_type => $class ) {
			$post_type = new $class;
			$post_type->register();
		}
=======
	public static function register() {
		PostTypeFactory::create( 'masteriyo_courses' )
		->set_label( 'name', esc_html__( 'Courses', 'masteriyo' ) )
		->set_label( 'singular_name', esc_html__( 'Course', 'masteriyo' ) )
		->set_label( 'all_items', esc_html__( 'All Courses', 'masteriyo' ) )
		->set_label( 'menu_name', esc_html__( 'Courses', 'masteriyo' ) )
		->register();

		PostTypeFactory::create( 'masteriyo_lessons' )
		->set_label( 'name', esc_html__( 'Lessons', 'masteriyo' ) )
		->set_label( 'singular_name', esc_html__( 'Lesson', 'masteriyo' ) )
		->set_label( 'all_items', esc_html__( 'All Lessons', 'masteriyo' ) )
		->set_label( 'menu_name', esc_html__( 'Lessons', 'masteriyo' )  )
		->register();

		PostTypeFactory::create( 'masteriyo_quizes' )
		->set_label( 'name', esc_html__( 'Quizes', 'masteriyo' ) )
		->set_label( 'singular_name', esc_html__( 'Quiz', 'masteriyo' ) )
		->set_label( 'all_items', esc_html__( 'All Quizes', 'masteriyo' ) )
		->set_label( 'menu_name', esc_html__( 'Quizes', 'masteriyo' ) )
		->register();
>>>>>>> ab1212c... Register taxonomies
	}
}
