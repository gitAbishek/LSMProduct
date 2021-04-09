<?php
/**
 * Courses list shortcode.
 *
 * @since 0.1.0
 * @class CoursesListShortcode
 * @package ThemeGrill\Masteriyo\Shortcodes
 */

namespace ThemeGrill\Masteriyo\Shortcodes;

use ThemeGrill\Masteriyo\Abstracts\Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Courses list shortcode.
 */
class CoursesListShortcode extends Shortcode {

	/**
	 * Shortcode tag.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $tag = 'masteriyo_course_list';

	/**
	 * Shortcode attributes with default values.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $attributes = array(
		'limit' => 10,
	);

	/**
	 * Get shortcode content.
	 *
	 * @since  0.1.0
	 *
	 * @return string
	 */
	public function get_content() {
		/**
		 * Prepare Template.
		 */
		$template_path = masteriyo( 'template' )->locate( 'course-list.php' );

		/**
		 * Render the template.
		 */
		return $this->get_rendered_html( $this->get_attributes(), $template_path );
	}
}
