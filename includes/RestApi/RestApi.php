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
		foreach ( $this->get_rest_namespaces() as $namespace => $controllers ) {
			foreach ( $controllers as $controller_name => $controller_class ) {
				$this->controllers[ $namespace ][ $controller_name ] = masteriyo( $controller_class );
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
				'masteriyo/v1'    => $this->get_v1_controllers(),
				// 'masteriyo/store' => $this->get_store_controllers(),
			)
		);
	}

	/**
	 * Store controllers (cart and checkout).
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	protected function get_store_controllers() {
		$namespace = '\\ThemeGrill\\Masteriyo\\RestApi\\Controllers\\Store';

		return array(
			'cart-items' => "{$namespace}\\CartItemsController",
		);
	}

	/**
	 * List of controllers in the masteriyo/v1 namespace.
	 *
	 * @since 0.1.0
	 *
	 * @return array\
	 */
	protected function get_v1_controllers() {
		$namespace = '\\ThemeGrill\\Masteriyo\\RestApi\\Controllers\\Version1';

		return array(
			'courses'              => "{$namespace}\\CoursesController",
			'courses.categories'   => "{$namespace}\\CourseCategoriesController",
			'courses.tags'         => "{$namespace}\\CourseTagsController",
			'courses.difficulties' => "{$namespace}\\CourseDifficultiesController",
			'courses.children'     => "{$namespace}\\CourseChildrenController",
			'lessons'              => "{$namespace}\\LessonsController",
			'questions'            => "{$namespace}\\QuestionsController",
			'quizes'               => "{$namespace}\\QuizesController",
			'sections'             => "{$namespace}\\SectionsController",
			'sections.children'    => "{$namespace}\\SectionChildrenController",
			'faqs'                 => "{$namespace}\\FaqsController",
			'orders'               => "{$namespace}\\OrdersController",
			'orders.items'         => "{$namespace}\\OrderItemsController",
			'users'                => "{$namespace}\\UsersController",
			'settings'             => "{$namespace}\\SettingsController",
			'courses.reviews'      => "{$namespace}\\CourseReviewsController",
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
