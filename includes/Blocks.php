<?php
/**
 * Blocks class.
 *
 * @since 1.3.0
 */

namespace Masteriyo;

use Masteriyo\Query\CourseCategoryQuery;
use Masteriyo\Query\CourseQuery;

defined( 'ABSPATH' ) || exit;

class Blocks {
	/**
	 * Init.
	 *
	 * @since 1.3.0
	 */
	public function init() {
		$this->init_hooks();
	}

	/**
	 * Constructor.
	 *
	 * @since 1.3.0
	 */
	private function init_hooks() {
		if ( version_compare( get_bloginfo( 'version' ), '5.8', '>=' ) ) {
			add_filter( 'block_categories_all', array( $this, 'block_categories' ), 999999, 2 );
		} else {
			add_filter( 'block_categories', array( $this, 'block_categories' ), 999999, 2 );
		}
		add_action( 'init', array( $this, 'register_blocks' ) );
	}

	/**
	 * Add "Masteriyo" category to the the blocks listing in post edit screen.
	 *
	 * @since 1.3.0
	 *
	 * @param array $block_categories
	 *
	 * @return array
	 */
	public function block_categories( $block_categories ) {
		array_unshift(
			$block_categories,
			array(
				'slug'  => 'masteriyo',
				'title' => esc_html__( 'Masteriyo LMS', 'masteriyo' ),
			)
		);
		return $block_categories;
	}

	/**
	 * Register all the blocks.
	 *
	 * @since 1.3.0
	 */
	public function register_blocks() {
		$this->register_courses_block();
		$this->register_course_categories_block();
	}

	/**
	 * Register the course categories block.
	 *
	 * @since 1.3.0
	 */
	private function register_course_categories_block() {
		register_block_type(
			'masteriyo/course-categories',
			array(
				'attributes'      => array(
					'clientId'           => array(
						'type'    => 'string',
						'default' => '',
					),
					'count'              => array(
						'type'    => 'number',
						'default' => 12,
					),
					'columns'            => array(
						'type'    => 'number',
						'default' => 3,
					),
					'hide_courses_count' => array(
						'type'    => 'string',
						'default' => 'no',
					),
				),
				'style'           => 'masteriyo-public',
				'editor_script'   => 'masteriyo-blocks',
				'editor_style'    => 'masteriyo-public',
				'render_callback' => array( $this, 'render_course_categories_block' ),
			)
		);
	}

	/**
	 * Render callback for the course categories block.
	 *
	 * @since 1.3.0
	 */
	public function render_course_categories_block( $attr ) {
		$columns = absint( $attr['columns'] );

		if ( 0 === $columns ) {
			$columns = 1;
		}
		if ( 4 < $columns ) {
			$columns = 4;
		}
		$attr['columns']    = $columns;
		$categories         = $this->get_categories( $attr );
		$attr['categories'] = $categories;

		\ob_start();

		/**
		 * Hook: masteriyo_blocks_before_course_categories.
		 *
		 * @since 1.3.0
		 */
		do_action( 'masteriyo_blocks_before_course_categories', $attr, $categories );

		masteriyo_get_template( 'shortcodes/course-categories/list.php', $attr );

		/**
		 * Hook: masteriyo_blocks_after_course_categories.
		 *
		 * @since 1.3.0
		 */
		do_action( 'masteriyo_blocks_after_course_categories', $attr, $categories );
		return \ob_get_clean();
	}

	/**
	 * Get categories to display for the shortcode.
	 *
	 * @since 1.3.0
	 *
	 * @return CourseCategory[]
	 */
	protected function get_categories( $attr ) {
		$args       = array(
			'order'   => 'ASC',
			'orderby' => 'name',
			'number'  => absint( $attr['count'] ),
		);
		$query      = new CourseCategoryQuery( $args );
		$categories = $query->get_categories();

		/**
		 * Hook: masteriyo_shortcode_course_categories.
		 *
		 * @since 1.3.0
		 */
		return apply_filters( 'masteriyo_shortcode_course_categories', $categories, $args, $query );
	}

	/**
	 * Register the courses block.
	 *
	 * @since 1.3.0
	 */
	private function register_courses_block() {
		register_block_type(
			'masteriyo/courses',
			array(
				'attributes'      => array(
					'clientId'                => array(
						'type'    => 'string',
						'default' => '',
					),
					'count'                   => array(
						'type'    => 'number',
						'default' => 12,
					),
					'columns'                 => array(
						'type'    => 'number',
						'default' => 3,
					),
					'categoryIds'             => array(
						'type'    => 'array',
						'default' => array(),
					),
					'sortBy'                  => array(
						'type'    => 'string',
						'default' => 'date',
					),
					'sortOrder'               => array(
						'type'    => 'string',
						'default' => 'desc',
					),
					'startCourseButtonBorder' => array(
						'type' => 'object',
					),
				),
				'style'           => 'masteriyo-public',
				'editor_script'   => 'masteriyo-blocks',
				'editor_style'    => 'masteriyo-public',
				'render_callback' => array( $this, 'render_courses_block' ),
			)
		);
	}

	/**
	 * Render callback for the courses block.
	 *
	 * @since 1.3.0
	 */
	public function render_courses_block( $attr ) {
		$args         = array(
			'limit'    => absint( $attr['count'] ),
			'order'    => 'DESC',
			'orderby'  => 'date',
			'category' => empty( $attr['categoryIds'] ) ? null : $attr['categoryIds'],
		);
		$course_query = new CourseQuery( $args );
		$courses      = apply_filters( 'masteriyo_shortcode_courses_result', $course_query->get_courses() );
		$client_id    = (string) $attr['clientId'];

		masteriyo_set_loop_prop( 'columns', absint( $attr['columns'] ) );

		\ob_start();

		printf( '<div class="masteriyo-block masteriyo-block-%s masteriyo-courses-block">', esc_attr( $client_id ) );

		if ( count( $courses ) > 0 ) {
			$original_course = isset( $GLOBALS['course'] ) ? $GLOBALS['course'] : null;

			/**
			 * Hook: masteriyo_blocks_before_courses_loop.
			 *
			 * @since 1.3.0
			 */
			do_action( 'masteriyo_blocks_before_courses_loop', $attr, $courses );
			masteriyo_course_loop_start();

			foreach ( $courses as $course ) {
				$GLOBALS['course'] = $course;

				\masteriyo_get_template_part( 'content', 'course' );
			}

			$GLOBALS['course'] = $original_course;

			masteriyo_course_loop_end();

			/**
			 * Hook: masteriyo_blocks_after_courses_loop.
			 *
			 * @since 1.3.0
			 */
			do_action( 'masteriyo_blocks_after_courses_loop', $attr, $courses );
			masteriyo_reset_loop();
		} else {
			/**
			 * Hook: masteriyo_blocks_no_courses_found.
			 *
			 * @since 1.3.0
			 */
			do_action( 'masteriyo_blocks_no_courses_found' );
		}
		echo '</div>';

		return \ob_get_clean();
	}
}
