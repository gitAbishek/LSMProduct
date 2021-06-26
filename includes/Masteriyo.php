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
use ThemeGrill\Masteriyo\PostType\RegisterPostType;
use ThemeGrill\Masteriyo\Taxonomy\RegisterTaxonomies;
use ThemeGrill\Masteriyo\AdminMenu;
use ThemeGrill\Masteriyo\FileRestrictions\FileRestrictions;
use ThemeGrill\Masteriyo\Shortcodes\Shortcodes;
use ThemeGrill\Masteriyo\Setup\Onboard;

defined( 'ABSPATH' ) || exit;

/**
 * Main Masteriyo class.
 *
 * @class ThemeGrill\Masteriyo\Masteriyo
 */

class Masteriyo extends Container {
	/**
	 * Query instance.
	 *
	 * @var Query
	 */
	public $query = null;

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		parent::__construct();

		$this->init();

		$this->query = new Query();
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
		Activation::init();
		Deactivation::init();
		FileRestrictions::init();
		CourseReviews::init();
		CourseQuestionAnswers::init();

		// Register service providers.
		$this->register_service_providers();

		// Initialize the rest api controllers.
		RestApi::instance()->init();

		// Register admin menus
		AdminMenu::instance()->init();

		Ajax::init();

		// Register scripts and styles.
		$this->get( 'script-style' );

		$this->get( 'query.frontend' );

		$this->get( 'formhandlers' )->register();

		$this->define_tables();

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
		add_action( 'admin_notices', array( $this, 'display_admin_notices' ) );

		add_filter( 'plugin_row_meta', array( $this, 'add_plugin_links' ), 10, 2 );
		add_filter( 'plugin_action_links_' . Constants::get( 'MASTERIYO_PLUGIN_BASENAME' ), array( $this, 'add_plugin_action_links' ) );
		add_filter( 'template_include', array( $this, 'template_loader' ) );
		add_filter( 'template_redirect', array( $this, 'redirect_reset_password_link' ) );

		add_action( 'switch_blog', array( $this, 'define_tables' ), 0 );

		add_filter(
			'query_vars',
			function( $query_vars ) {
				$query_vars[] = 'masteriyo';
				$query_vars[] = 'course';
				return $query_vars;
			}
		);
		add_action( 'admin_init', array( $this, 'admin_redirects' ) );
	}

	/**
	 * Initialization after WordPress is initialized.
	 *
	 * @since 0.1.0
	 */
	public function after_wp_init() {
		Install::init();
		RegisterPostType::instance()->register();
		RegisterTaxonomies::register();
		Shortcodes::instance()->register_shortcodes();

		$this->register_order_status();
		$this->load_text_domain();
		$this->setup_wizard();

		do_action( 'masteriyo_init' );
	}

	/**
	 * Setup wizard method.
	 *
	 * @return void
	 */
	public function setup_wizard() {
		// Setup.
		if ( ! empty( $_GET['page'] ) ) {

			if ( 'masteriyo-onboard' == $_GET['page'] ) {
				$onboard_obj = new Onboard();
				$onboard_obj->init();
			}
		}
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

		return array_unique(
			apply_filters(
				'masteriyo_service_providers',
				array(
					"{$namespace}\\CacheServiceProvider",
					"{$namespace}\\NoticeServiceProvider",
					"{$namespace}\\FrontendQueryServiceProvider",
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
					"{$namespace}\\CartServiceProvider",
					"{$namespace}\\TemplateServiceProvider",
					"{$namespace}\\QuestionServiceProvider",
					"{$namespace}\\ScriptStyleServiceProvider",
					"{$namespace}\\ShortcodesServiceProvider",
					"{$namespace}\\SettingsServiceProvider",
					"{$namespace}\\QueriesServiceProvider",
					"{$namespace}\\FaqServiceProvider",
					"{$namespace}\\EmailsServiceProvider",
					"{$namespace}\\CourseReviewServiceProvider",
					"{$namespace}\\CourseQuestionAnswerServiceProvider",
					"{$namespace}\\FrontendQueryServiceProvider",
					"{$namespace}\\FormHandlersServiceProvider",
					"{$namespace}\\CountriesServiceProvider",
					"{$namespace}\\CheckoutServiceProvider",
					"{$namespace}\\PaymentGatewaysServiceProvider",
					"{$namespace}\\CourseProgressServiceProvider",
					"{$namespace}\\UserCourseServiceProvider",
					"{$namespace}\\CourseProgressItemServiceProvider",
				)
			)
		);
	}

	/**
	 * Load plugin textdomain.
	 *
	 * @since 0.1.0
	 */
	public function load_text_domain() {
		load_plugin_textdomain(
			'masteriyo',
			false,
			dirname( plugin_basename( Constants::get( 'MASTERIYO_PLUGIN_FILE' ) ) ) . '/' . Constants::get( 'MASTERIYO_PLUGIN_REL_LANGUAGES_PATH' )
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

		// TODO Remove this after implement interactive page.
		$wp_admin_bar->add_node(
			array(
				'parent' => 'site-name',
				'id'     => 'masteriyo-interactive-page',
				'title'  => __( 'Interactive page', 'masteriyo' ),
				'href'   => home_url( '?masteriyo=interactive#/course/8' ),
			)
		);
	}

	/**
	 * Add plugin links on the plugins screen.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $links Plugin Row Meta.
	 * @param mixed $file  Plugin Base file.
	 *
	 * @return array
	 */
	public static function add_plugin_links( $links, $file ) {
		if ( Constants::get( 'MASTERIYO_PLUGIN_BASENAME' ) !== $file ) {
			return $links;
		}

		$masteriyo_links = array(
			'docs'    => array(
				'url'        => apply_filters( 'masteriyo_docs_url', '#' ),
				'label'      => __( 'Docs', 'masteriyo' ),
				'aria-label' => __( 'View masteriyo documentation', 'masteriyo' ),
			),
			'apidocs' => array(
				'url'        => apply_filters( 'masteriyo_apidocs_url', '#' ),
				'label'      => __( 'API docs', 'masteriyo' ),
				'aria-label' => __( 'View masteriyo API docs', 'masteriyo' ),
			),
			'support' => array(
				'url'        => apply_filters( 'masteriyo_community_support_url', 'https://wordpress.org/support/plugin/masteriyo/' ),
				'label'      => __( 'Community Support', 'masteriyo' ),
				'aria-label' => __( 'Visit community forums', 'masteriyo' ),
			),
		);

		foreach ( $masteriyo_links as $key => $link ) {
			$links[ $key ] = sprintf(
				'<a href="%s" aria-label="%s">%s</a>',
				esc_url( $link['url'] ),
				esc_attr( $link['aria-label'] ),
				esc_html( $link['label'] )
			);
		}

		return $links;
	}

	/**
	 * Add action links on the plugins screen.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $links Plugin Action links.
	 *
	 * @return array
	 */
	public static function add_plugin_action_links( $links ) {
		$action_links      = array(
			'settings' => array(
				'url'        => admin_url( 'admin.php?page=masteriyo' ),
				'label'      => __( 'Settings', 'masteriyo' ),
				'aria-label' => __( 'View masteriyo settings', 'masteriyo' ),
			),
		);
		$action_link_htmls = array();

		foreach ( $action_links as $key => $link ) {
			$action_link_htmls[ $key ] = sprintf(
				'<a href="%s" aria-label="%s">%s</a>',
				esc_url( $link['url'] ),
				esc_attr( $link['aria-label'] ),
				esc_html( $link['label'] )
			);
		}

		return array_merge( $action_link_htmls, $links );
	}

	/**
	 * Load a template.
	 *
	 * Handles template usage so that we can use our own templates instead of the theme's.
	 *
	 * @since 0.1.0
	 *
	 * @param string $template Template to load.
	 *
	 * @return string
	 */
	public function template_loader( $template ) {
		global $post;

		if ( masteriyo_is_single_course_page() ) {
			masteriyo_setup_course_data( $post );
			$template = masteriyo( 'template' )->locate( 'single-course.php' );
		} elseif ( masteriyo_is_archive_course_page() ) {
			$template = masteriyo( 'template' )->locate( 'archive-course.php' );
		}

		if ( isset( $_GET['masteriyo'] ) && 'interactive' === $_GET['masteriyo'] ) {
			$template = plugin_dir_path( Constants::get( 'MASTERIYO_PLUGIN_FILE' ) ) . '/templates/interactive.php';
			status_header( 200 );
		}

		return $template;
	}

	/**
	 * Redirect to password reset form after setting password reset cookie.
	 *
	 * @since 0.1.0
	 */
	public function redirect_reset_password_link() {
		if ( masteriyo_is_myaccount_page() && isset( $_GET['key'] ) && ( isset( $_GET['id'] ) || isset( $_GET['login'] ) ) ) {
			// If available, get $user_id from query string parameter for fallback purposes.
			if ( isset( $_GET['login'] ) ) {
				$user    = get_user_by( 'login', sanitize_user( wp_unslash( $_GET['login'] ) ) );
				$user_id = $user ? $user->ID : 0;
			} else {
				$user_id = absint( $_GET['id'] );
			}

			// If the reset token is not for the current user, ignore the reset request (don't redirect).
			$logged_in_user_id = get_current_user_id();
			if ( $logged_in_user_id && $logged_in_user_id !== $user_id ) {
				masteriyo_add_notice( __( 'This password reset key is for a different user account. Please log out and try again.', 'masteriyo' ), 'error' );
				return;
			}

			$value = sprintf( '%d:%s', $user_id, wp_unslash( $_GET['key'] ) ); // phpcs:ignore

			masteriyo_set_password_reset_cookie( $value );
			wp_safe_redirect(
				add_query_arg(
					array(
						'show-reset-form' => 'true',
					),
					masteriyo_get_account_endpoint_url( 'reset-password' )
				)
			);
			exit;
		}
	}

	/**
	 * Redirecting user to onboard or other page.
	 *
	 * @since 0.1.0
	 */
	public function admin_redirects() {

		if ( ! get_transient( '_masteriyo_activation_redirect' ) ) {
			return;
		}

		delete_transient( '_masteriyo_activation_redirect' );

		if ( ( ! empty( $_GET['page'] ) && in_array( $_GET['page'], array( 'masteriyo-onboard' ) ) ) || is_network_admin() || isset( $_GET['activate-multi'] ) || ! current_user_can( 'manage_options' ) ) {
			return;
		}

		 // If plugin is running for first time, redirect to onboard page.
		if ( get_option( 'masteriyo_first_time_activation_flag' ) != '1' ) {
			wp_safe_redirect( admin_url( 'index.php?page=masteriyo-onboard' ) );
			exit;
		}

		return;
	}

	/**
	 * Register order status.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private function register_order_status() {
		$order_statuses = \masteriyo_get_order_statuses();

		foreach ( $order_statuses as $order_status => $values ) {
			register_post_status( $order_status, $values );
		}
	}

	/**
	 * Display admin notices.
	 *
	 * @since 0.1.0
	 */
	public function display_admin_notices() {
		if ( version_compare( get_bloginfo( 'version' ), '4.7', '<=' ) ) {
			// translators: %s: Dismiss link
			$message = sprintf( esc_html__( 'Minimum WordPress version required to work Masteriyo is v4.7.', 'masteriyo' ) );
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			printf( '<div class="notice notice-warning"><p><strong>%s</strong>: %s</p></div>', 'Masteriyo', $message );
		}
	}

	/**
	 * Register custom tables within $wpdb object.
	 *
	 * @since 0.1.0
	 */
	public function define_tables() {
		global $wpdb;

		// List of tables without prefixes.
		$tables = array(
			'order_itemmeta'    => 'masteriyo_order_itemmeta',
			'user_activitymeta' => 'masteriyo_user_activitymeta',
			'user_itemmeta'     => 'masteriyo_user_itemmeta',
		);

		foreach ( $tables as $name => $table ) {
			$wpdb->$name    = $wpdb->prefix . $table;
			$wpdb->tables[] = $table;
		}
	}
}


