<?php
/**
 * Initialize REST API.
 *
 * @since 0.1.0
 *
 * @package  ThemeGrill\Masteriyo\RestApi
 */

namespace ThemeGrill\Masteriyo\RestApi;

defined( 'ABSPATH' ) || exit;

use ThemeGrill\Masteriyo\Traits\Singleton;

class RestApi {
	use Singleton;

	/**
	 * REST API namespaces and endpoints.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $controllers = array();

	/**
	 * Hook into WordPress ready to init the REST API as needed.
	 *
	 * @since 0.1.0
	 */
	public function init() {
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
	}

	/**
	 * Register REST API routes.
	 *
	 * @since 0.1.0
	 */
	public function register_rest_routes() {
		global $masteriyo_container;

		foreach ( $this->get_rest_namespaces() as $namespace => $controllers ) {
			foreach ( $controllers as $controller_name => $controller_class ) {
				// $this->controllers[ $namespace ][ $controller_name ] = new $controller_class;
				$this->controllers[ $namespace ][ $controller_name ] = $masteriyo_container->get( $controller_class );
				$this->controllers[ $namespace ][ $controller_name ]->register_routes();
			}
		}
	}

	/**
	 * Get API namespaces - new namespaces should be registered here.
	 *
	 * @since 0.1.0
	 *
	 * @return array List of Namespaces and Main controller classes.
	 */
	protected function get_rest_namespaces() {
		return apply_filters(
			'masteriyo_rest_api_get_rest_namespaces',
			array(
				'masteriyo/v1' => $this->get_v1_controllers(),
			)
		);
	}

	/**
	 * List of controllers in the masteriyo/v1 namespace.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	protected function get_v1_controllers() {
		$namespace = '\\ThemeGrill\\Masteriyo\\RestApi\\Controllers\\Version1';

		return array(
			'courses'             => "{$namespace}\\CoursesController",
			'course-categories'   => "{$namespace}\\CourseCategoriesController",
			'course-tags'         => "{$namespace}\\CourseTagsController",
			'course-difficulties' => "{$namespace}\\CourseDifficultiesController",
			'course-children'     => "{$namespace}\\CourseChildrenController",
			'lessons'             => "{$namespace}\\LessonsController",
			'questions'           => "{$namespace}\\QuestionsController",
			'quizes'              => "{$namespace}\\QuizesController",
			'sections'            => "{$namespace}\\SectionsController",
			'section-children'    => "{$namespace}\\SectionChildrenController",
			'orders'              => "{$namespace}\\OrdersController",
			'users'               => "{$namespace}\\UsersController",
		);
	}

	/**
	 * Return the path to the package.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public static function get_path() {
		return dirname( __DIR__ );
	}
}
