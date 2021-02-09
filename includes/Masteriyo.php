<?php
/**
 * Masteriyo setup.
 *
 * @package ThemeGrill\Masteriyo
 *
 * @since 0.1.0
 */

namespace ThemeGrill\Masteriyo;

use League\Container\Container;
use ThemeGrill\Masteriyo\RestApi\RestApi;
use ThemeGrill\Masteriyo\PostType\RegisterPostTypes;
use ThemeGrill\Masteriyo\Taxonomy\RegisterTaxonomies;

defined( 'ABSPATH' ) || exit;

/**
 * Main Masteriyo class.
 *
 * @class ThemeGrill\Masteriyo\Masteriyo
 */

class Masteriyo extends Container {

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		parent::__construct();

		$this->init();
	}

	/**
	 * Get applicaiton version.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function version() {
		return MASTERIYO_VERSION;
	}

	/**
	 * Initialize the applicaiton.
	 *
	 * @since 0.1.0
	 */
	protected function init() {
		// Register service providers.
		$this->register_service_providers();

		// Initialize the rest api controllers.
		RestApi::instance()->init();

		// Initilize the hooks.
		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 0.1.0
	 */
	protected function init_hooks() {
		add_action( 'init', array( $this, 'after_wp_init' ) );
	}

	/**
	 * Initialization after WordPress is initialized.
	 *
	 * @since 0.1.0
	 */
	public function after_wp_init() {
		RegisterPostTypes::instance()->register();
		RegisterTaxonomies::register();

		do_action( 'masteriyo_init' );
	}

	/**
	 * Register service providers.
	 *
	 * @since 0.1.0
	 */
	private function register_service_providers() {
		foreach ( $this->get_service_providers() as $p ) {
			$this->addServiceProvider( $p );
		}
	}

	/**
	 * Get service providers.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	private function get_service_providers() {
		$namespace = 'ThemeGrill\\Masteriyo\\Providers';
		return apply_filters( 'masteriyo_get_service_providers', array(
			"{$namespace}\\CourseServiceProvider",
			"{$namespace}\\PermissionServiceProvider",
			"{$namespace}\\SessionServiceProvider",
			"{$namespace}\\LessonServiceProvider",
			"{$namespace}\\QuizServiceProvider",
			"{$namespace}\\SectionServiceProvider",
			"{$namespace}\\UserServiceProvider",
			"{$namespace}\\OrderServiceProvider",
			"{$namespace}\\CourseTagServiceProvider",
			"{$namespace}\\CourseCategoryServiceProvider",
			"{$namespace}\\CourseDifficultyServiceProvider",
			"{$namespace}\\NoticeServiceProvider",
			"{$namespace}\\CartServiceProvider",
			"{$namespace}\\TemplateServiceProvider",
			"{$namespace}\\QuestionServiceProvider",
		) );
	}
}


