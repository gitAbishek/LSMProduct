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
use ThemeGrill\Masteriyo\AdminMenu;
use ThemeGrill\Masteriyo\Shortcodes\Shortcodes;

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

		// Register admin menus
		AdminMenu::instance()->init();

		// Register scripts and styles.
		$this->get( 'script-style');
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
		add_action( 'admin_bar_menu', array( $this, 'add_course_list_page_link' ), 35 );
	}

	/**
	 * Initialization after WordPress is initialized.
	 *
	 * @since 0.1.0
	 */
	public function after_wp_init() {
		RegisterPostTypes::instance()->register();
		RegisterTaxonomies::register();
		Shortcodes::instance()->register_shortcodes();

		$this->load_text_domain();

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
		return apply_filters( 'masteriyo_service_providers', array(
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
			"{$namespace}\\ScriptStyleServiceProvider",
			"{$namespace}\\ShortcodesServiceProvider",
			"{$namespace}\\SettingsServiceProvider",
		) );
	}

	/**
	 * Load plugin textdomain.
	 */
	private function load_text_domain() {
		load_plugin_textdomain(
			'masteriyo',
			false,
			dirname(plugin_basename( Constants::get('MASTERIYO_PLUGIN_FILE') ) ) . '/' . Constants::get('MASTERIYO_PLUGIN_REL_LANGUAGES_PATH')
		);
	}

	/**
	 * Add the "Course List" link in admin bar main menu.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_Admin_Bar $wp_admin_bar Admin bar instance.
	 */
	public function add_course_list_page_link( $wp_admin_bar ) {
		if ( ! is_admin() || ! is_admin_bar_showing() ) {
			return;
		}

		// Show only when the user is a member of this site, or they're a super admin.
		if ( ! is_user_member_of_blog() && ! is_super_admin() ) {
			return;
		}

		// Add an option to visit the course list page.
		$wp_admin_bar->add_node(
			array(
				'parent' => 'site-name',
				'id'     => 'course-list-page',
				'title'  => __( 'Course List', 'masteriyo' ),
				'href'   => masteriyo_get_page_permalink( 'course-list' ),
			)
		);
	}
}


