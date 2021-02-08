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
use ThemeGrill\Masteriyo\PostType\RegisterPostTypes;
use ThemeGrill\Masteriyo\Taxonomy\RegisterTaxonomies;
use ThemeGrill\Masteriyo\RestApi\RestApi;

defined( 'ABSPATH' ) || exit;

/**
 * Main Masteriyo class.
 *
 * @class ThemeGrill\Masteriyo\Masteriyo
 */

class Masteriyo extends Container {
	/**
	 * Masteriyo version.
	 *
	 * @var string
	 */
	const VERSION = '0.1.0';

	public function __construct() {
		parent::__construct();

		$this->register_service_providers();
		RestApi::instance()->init();

		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Initialization.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function init() {
		RegisterPostTypes::instance()->register();
	}

	/**
	 * Register service providers.
	 *
	 * @since 0.1.0
	 *
	 * @return void
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
	 * @return void
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
		) );
	}

}


