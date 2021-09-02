<?php
/**
 * Masteriyo setup.
 *
 * @package Masteriyo
 *
 * @since 0.1.0
 */

namespace Masteriyo;

use Masteriyo\AdminMenu;
use Masteriyo\ScriptStyle;
use Masteriyo\Capabilities;
use Masteriyo\Setup\Onboard;
use Masteriyo\RestApi\RestApi;
use Masteriyo\Emails\EmailHooks;
use Masteriyo\Query\UserCourseQuery;
use Masteriyo\Shortcodes\Shortcodes;
use Masteriyo\FormHandler\FormHandlers;
use Masteriyo\PostType\RegisterPostType;
use Masteriyo\Taxonomy\RegisterTaxonomies;
use Masteriyo\FileRestrictions\FileRestrictions;

defined( 'ABSPATH' ) || exit;

/**
 * Main Masteriyo class.
 *
 * @class Masteriyo\Masteriyo
 */

class Masteriyo {

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
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
		Capabilities::init();
		Activation::init();
		Deactivation::init();
		FormHandlers::init();
		FileRestrictions::init();
		CourseReviews::init();
		CourseQuestionAnswers::init();
		Faqs::init();
		EmailHooks::init();
		RestApi::init();
		AdminMenu::init();
		Ajax::init();
		ScriptStyle::init();

		masteriyo( 'query.frontend' );

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
		add_action( 'init', array( $this, 'after_wp_init' ), 0 );
		add_action( 'admin_bar_menu', array( $this, 'add_courses_page_link' ), 35 );
		add_action( 'admin_notices', array( $this, 'masteriyo_display_compatibility_notice' ) );

		// add_filter( 'plugin_row_meta', array( $this, 'add_plugin_links' ), 10, 2 );
		add_filter( 'plugin_action_links_' . Constants::get( 'MASTERIYO_PLUGIN_BASENAME' ), array( $this, 'add_plugin_action_links' ) );
		add_filter( 'template_include', array( $this, 'template_loader' ) );
		add_filter( 'template_redirect', array( $this, 'redirect_reset_password_link' ) );

		add_action( 'switch_blog', array( $this, 'define_tables' ), 0 );
		add_action( 'admin_init', array( $this, 'admin_redirects' ) );
		add_action( 'after_setup_theme', array( $this, 'add_image_sizes' ) );

		add_action( 'in_admin_header', array( $this, 'hide_admin_notices' ) );
	}

	/**
	 * Initialization after WordPress is initialized.
	 *
	 * @since 0.1.0
	 */
	public function after_wp_init() {
		RegisterPostType::instance()->register();
		RegisterTaxonomies::register();
		Shortcodes::instance()->register_shortcodes();
		Install::init();

		$this->register_order_status();
		$this->load_text_domain();
		$this->setup_wizard();

		$this->handle_paypal_ipn();

		add_rewrite_endpoint( 'course', EP_PAGES | EP_ROOT, 'course_id' );

		do_action( 'masteriyo_init' );
	}

	/**
	 * Setup wizard method.
	 *
	 * @return void
	 */
	public function setup_wizard() {
		// Setup.
		if ( ! empty( $_GET['page'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			if ( 'masteriyo-onboard' === $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$onboard_obj = new Onboard();
				$onboard_obj->init();
			}
		}
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
	 * Add the "Courses" link in admin bar main menu.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_Admin_Bar $wp_admin_bar Admin bar instance.
	 */
	public function add_courses_page_link( $wp_admin_bar ) {
		if ( ! is_admin() || ! is_admin_bar_showing() ) {
			return;
		}

		// Show only when the user is a member of this site, or they're a super admin.
		if ( ! is_user_member_of_blog() && ! is_super_admin() ) {
			return;
		}

		// Add an option to visit the courses page.
		$wp_admin_bar->add_node(
			array(
				'parent' => 'site-name',
				'id'     => 'courses-page',
				'title'  => __( 'Courses', 'masteriyo' ),
				'href'   => masteriyo_get_page_permalink( 'courses' ),
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
				'url'        => admin_url( 'admin.php?page=masteriyo#/settings' ),
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

		// Handle interactive page.
		if ( masteriyo_is_learning_page() ) { // phpcs:ignore
			$course_id = absint( get_query_var( 'course_id' ) );
			$user_id   = get_current_user_id();

			$query = new UserCourseQuery(
				array(
					'course_id' => $course_id,
					'user_id'   => $user_id,
				)
			);

			$user_courses = $query->get_user_courses();

			$course = masteriyo_get_course( $course_id );
			if ( empty( $user_courses ) && ! is_null( $course ) && 'open' === $course->get_access_mode() ) {
				$user_courses = masteriyo( 'user-course' );
				$user_courses->set_status( 'active' );
				$user_courses->set_course_id( $course_id );
				$user_courses->set_user_id( $user_id );
				$user_courses->save();
				$user_courses->set_object_read( true );
			}

			if ( empty( $user_courses ) || ! masteriyo_can_start_course( $course_id, $user_id ) ) {
				wp_safe_redirect( \masteriyo_get_courses_url(), 307 );
				exit();
			}

			$user_course = is_array( $user_courses ) ? $user_courses[0] : $user_courses;

			if ( 'active' === $user_course->get_status() ) {
				$user_course->set_date_start( current_time( 'mysql' ), true );
				$user_course->set_status( 'enrolled' );
			}

			if ( 'enrolled' === $user_course->get_status() ) {
				$user_course->set_date_modified( current_time( 'mysql' ), true );
				$user_course->save();
			}

			$template = masteriyo_locate_template( 'learning.php' );
		}

		return $template;
	}

	/**
	 * Redirect to password reset form after setting password reset cookie.
	 *
	 * @since 0.1.0
	 */
	public function redirect_reset_password_link() {
		if ( masteriyo_is_myaccount_page() && isset( $_GET['key'] ) && ( isset( $_GET['id'] ) || isset( $_GET['login'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			// If available, get $user_id from query string parameter for fallback purposes.
			if ( isset( $_GET['login'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$user    = get_user_by( 'login', sanitize_user( wp_unslash( $_GET['login'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$user_id = $user ? $user->ID : 0;
			} else {
				$user_id = absint( $_GET['id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
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

		if ( ( ! empty( $_GET['page'] ) && in_array( $_GET['page'], array( 'masteriyo-onboard' ) ) ) || is_network_admin() || isset( $_GET['activate-multi'] ) || ! current_user_can( 'manage_options' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.PHP.StrictInArray.MissingTrueStrict
			return;
		}

		// If plugin is running for first time, redirect to onboard page.
		if ( '1' !== get_option( 'masteriyo_first_time_activation_flag' ) ) {
			wp_safe_redirect( admin_url( 'index.php?page=masteriyo-onboard' ) );
			exit;
		}

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
	public function masteriyo_display_compatibility_notice() {
		if ( version_compare( get_bloginfo( 'version' ), '5.0', '<' ) ) {
			// translators: %s: Dismiss link
			echo wp_sprintf(
				'<div class="notice notice-warning"><p><strong>%s</strong>: %s</p></div>',
				'Masteriyo',
				esc_html__( 'Minimum WordPress version required to work Masteriyo is v5.0.', 'masteriyo' )
			);
		}
	}

	/**
	 * Hide admin notices from Masteriyo admin screens.
	 *
	 * @since 0.1.0
	 */
	public function hide_admin_notices() {
		// Bail if we're not on a Masteriyo screen or page.
		if ( empty( $_REQUEST['page'] ) || false === strpos( sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ), 'masteriyo' ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			return;
		}

		global $wp_filter;
		$ignore_notices = array( 'masteriyo_display_compatibility_notice' );

		foreach ( array( 'user_admin_notices', 'admin_notices', 'all_admin_notices' ) as $wp_notice ) {
			if ( empty( $wp_filter[ $wp_notice ] ) ) {
				continue;
			}

			$hook_callbacks = $wp_filter[ $wp_notice ]->callbacks;

			if ( empty( $hook_callbacks ) || ! is_array( $hook_callbacks ) ) {
				continue;
			}

			foreach ( $hook_callbacks as $priority => $hooks ) {
				foreach ( $hooks as $name => $callback ) {
					if ( ! empty( $name ) && in_array( $name, $ignore_notices, true ) ) {
						continue;
					}
					if (
						! empty( $callback['function'] ) &&
						! is_a( $callback['function'], '\Closure' ) &&
						isset( $callback['function'][0], $callback['function'][1] ) &&
						is_object( $callback['function'][0] ) &&
						in_array( $callback['function'][1], $ignore_notices, true )
					) {
						continue;
					}
					unset( $wp_filter[ $wp_notice ]->callbacks[ $priority ][ $name ] );
				}
			}
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

	/**
	 * Handle Paypal IPN listener.
	 *
	 * @return void
	 */
	public function handle_paypal_ipn() {
		// phpcs:disable
		if ( ( isset( $_POST['paypalListener'] ) && 'paypal_standard_IPN' === $_POST['paypalListener'] )
			|| isset( $_POST['test_ipn'] ) && '1' === $_POST['test_ipn'] ) {
			masteriyo( 'payment-gateways' )->get_available_payment_gateways();
			do_action( 'masteriyo_api_gateway_paypal' );
		}
		// phpcs:enable
	}

	/**
	 * Add image sizes.
	 *
	 * @since 0.1.0
	 */
	public function add_image_sizes() {
		add_image_size( 'masteriyo_single', 1137, 635, true );
		add_image_size( 'masteriyo_thumbnail', 375, 210, true );
	}
}


