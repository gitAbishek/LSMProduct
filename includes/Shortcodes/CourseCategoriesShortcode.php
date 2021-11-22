<?php
/**
 * Categories listing shortcode.
 *
 * @since 1.2.0
 * @class CourseCategoriesShortcode
 * @package Masteriyo\Shortcodes
 */

namespace Masteriyo\Shortcodes;

use Masteriyo\Abstracts\Shortcode;
use Masteriyo\Models\CourseCategory;

defined( 'ABSPATH' ) || exit;

class CourseCategoriesShortcode extends Shortcode {

	/**
	 * Shortcode tag.
	 *
	 * @since 1.2.0
	 *
	 * @var string
	 */
	protected $tag = 'masteriyo_course_categories';

	/**
	 * Shortcode default attributes.
	 *
	 * @since 1.2.0
	 *
	 * @var array
	 */
	protected $default_attributes = array(
		'count'              => 12,
		'columns'            => 4,
		'hide_courses_count' => 'no',
	);

	/**
	 * Get shortcode content.
	 *
	 * @since  1.2.0
	 *
	 * @return string
	 */
	public function get_content() {
		$categories = $this->get_categories();
		$attrs      = $this->get_attributes();
		$columns    = absint( $attrs['columns'] );

		if ( 0 === $columns ) {
			$columns = 1;
		}
		if ( 4 < $columns ) {
			$columns = 4;
		}
		$attrs['columns']    = $columns;
		$attrs['categories'] = $categories;

		\ob_start();
		do_action( 'masteriyo_template_shortcode_course_categories', $attrs );

		return \ob_get_clean();
	}

	/**
	 * Get categories to display for the shortcode.
	 *
	 * @since 1.2.0
	 *
	 * @return Array[CourseCategory]
	 */
	protected function get_categories() {
		$attr       = $this->get_attributes();
		$args       = array(
			'taxonomy'   => 'course_cat',
			'order'      => 'ASC',
			'orderby'    => 'name',
			'number'     => absint( $attr['count'] ),
			'hide_empty' => false,
		);
		$query      = new \WP_Term_Query();
		$result     = $query->query( $args );
		$categories = array_filter( array_map( 'masteriyo_get_course_cat', $result ) );

		return apply_filters( 'masteriyo_shortcode_course_categories', $categories, $args, $query, $this );
	}
}
