<?php
/**
 * Register post types.
 */

namespace ThemeGrill\Masteriyo\Taxonomy;

class TaxonomyFactory {

	/**
	 * Post type taxonomy.
	 *
	 * @since 0.1.0
	 *
	 * @param string $taxonomy Post type taxonomy.
	 *
	 * @return ThemeGrill\Masteriyo\Taxonomy\Taxonomy;
	 */
	public static function create( $taxonomy, $labels = array(), $args = array() ) {
<<<<<<< HEAD
		if ( 'course_cat' === $taxonomy) {
			return new Course\Categories();
		} elseif ( 'course_tag' === $taxonomy ) {
			return new Course\Tags();
		} elseif ( 'course_difficulty' === $taxonomy ) {
			return new Course\Difficulties();
		} elseif ( 'lesson_cat' === $taxonomy) {
			return new Lesson\Categories();
		} elseif ( 'lesson_tag' === $taxonomy ) {
			return new Lesson\Tags();
=======
		if ( 'courses_cat' === $taxonomy) {
			return new Courses\Categories();
		} elseif ( 'courses_tag' === $taxonomy ) {
			return new Courses\Tags();
		} elseif ( 'courses_difficulty' === $taxonomy ) {
			return new Courses\Difficulties();
		} elseif ( 'lessons_cat' === $taxonomy) {
			return new Lessons\Categories();
		} elseif ( 'lessons_tag' === $taxonomy ) {
			return new Lessons\Tags();
>>>>>>> ab1212c... Register taxonomies
		}
	}
}
