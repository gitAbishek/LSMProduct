<?php
/**
 * Shortcode for listing courses.
 *
 * @since 1.0.6
 * @class CoursesShortcode
 * @package Masteriyo\Shortcodes
 */

namespace Masteriyo\Shortcodes;

use Masteriyo\Abstracts\Shortcode;
use Masteriyo\Query\CourseQuery;

defined( 'ABSPATH' ) || exit;

class CoursesShortcode extends Shortcode {

	/**
	 * Shortcode tag.
	 *
	 * @since 1.0.6
	 *
	 * @var string
	 */
	protected $tag = 'masteriyo_courses';

	/**
	 * Shortcode default attributes.
	 *
	 * @since 1.0.6
	 *
	 * @var array
	 */
	protected $default_attributes = array(
		'count'    => 12,
		'columns'  => 3,
		'category' => null,
	);

	/**
	 * Get shortcode content.
	 *
	 * @since  1.0.6
	 *
	 * @return string
	 */
	public function get_content() {
		$attr         = $this->get_attributes();
		$args         = array(
			'limit'    => absint( $attr['count'] ),
			'order'    => 'DESC',
			'orderby'  => 'date',
			'category' => empty( $attr['category'] ) ? array() : array( $attr['category'] ),
		);
		$course_query = new CourseQuery( $args );
		$courses      = apply_filters( 'masteriyo_shortcode_courses_result', $course_query->get_courses() );

		masteriyo_set_loop_prop( 'columns', absint( $attr['columns'] ) );

		\ob_start();

		echo '<div class="masteriyo-w-100">';

		if ( count( $courses ) > 0 ) {
			$original_course = isset( $GLOBALS['course'] ) ? $GLOBALS['course'] : null;

			do_action( 'masteriyo_shortcode_before_courses_loop', $attr, $courses );
			masteriyo_course_loop_start();

			foreach ( $courses as $course ) {
				$GLOBALS['course'] = $course;

				\masteriyo_get_template_part( 'content', 'course' );
			}

			$GLOBALS['course'] = $original_course;

			masteriyo_course_loop_end();
			do_action( 'masteriyo_shortcode_after_courses_loop', $attr, $courses );
			masteriyo_reset_loop();
		} else {
			do_action( 'masteriyo_shortcode_no_courses_found' );
		}

		echo '</div>';

		return \ob_get_clean();
	}
}
