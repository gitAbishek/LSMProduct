<?php
/**
 * Ajax.
 *
 * @package ThemeGrill\Masteriyo
 *
 * @since 0.1.0
 */

namespace ThemeGrill\Masteriyo;

defined( 'ABSPATH' ) || exit;

/**
 * Main Masteriyo class.
 *
 * @class ThemeGrill\Masteriyo\Masteriyo
 */

class ScriptStyle {

	/**
	 * Scripts.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	public $scripts = array();

	/**
	 * Styles.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	public $styles = array();

	/**
	 * Localized scripts.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	public $localized_scripts = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Initialization.
	 *
	 * @since 0.1.0
	 */
	protected function init() {
		$this->init_hooks();
		$this->init_scripts();
		$this->init_styles();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private function init_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'load_public_scripts_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_public_localized_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_localized_scripts' ) );
	}

	/**
	 * Get application version.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	private function get_version() {
		return Constants::get( 'MASTERIYO_VERSION' );
	}

	/**
	 * Initialize the scripts.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	private function init_scripts() {
		$this->scripts = apply_filters(
			'masteriyo_enqueue_scripts',
			array(
				'admin'          => array(
					'src'      => $this->get_asset_url( '/assets/js/build/masteriyo-backend.js' ),
					'deps'     => array( 'react', 'wp-components', 'wp-element', 'wp-i18n', 'wp-polyfill' ),
					'context'  => 'admin',
					'callback' => 'masteriyo_is_admin_page',
				),
				'single-course'  => array(
					'src'      => $this->get_asset_url( '/assets/js/single-course.js' ),
					'deps'     => array( 'jquery' ),
					'context'  => 'public',
					'callback' => 'masteriyo_is_single_course_page',
				),
				'edit-myaccount' => array(
					'src'      => $this->get_asset_url( '/assets/js/edit-myaccount.js' ),
					'deps'     => array( 'jquery' ),
					'version'  => $this->get_version(),
					'context'  => 'public',
					'callback' => 'masteriyo_is_edit_myaccount_page',
				),
				'login-form'     => array(
					'src'      => $this->get_asset_url( '/assets/js/login-form.js' ),
					'deps'     => array( 'jquery' ),
					'version'  => $this->get_version(),
					'context'  => 'public',
					'callback' => 'masteriyo_is_load_login_form_assets',
				),
				'checkout'       => array(
					'src'      => $this->get_asset_url( '/assets/js/frontend/checkout.js' ),
					'deps'     => array( 'jquery' ),
					'version'  => $this->get_version(),
					'context'  => 'public',
					'callback' => 'masteriyo_is_checkout_page',
				),
			)
		);
	}


	/**
	 * Initialize the styles.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	private function init_styles() {
		$this->styles = apply_filters(
			'masteriyo_enqueue_styles',
			array(
				'admin'  => array(
					'src'      => $this->get_asset_url( '/assets/js/build/masteriyo-backend.css' ),
					'has_rtl'  => true,
					'context'  => 'admin',
					'callback' => 'masteriyo_is_admin_page',
				),
				'public' => array(
					'src'     => $this->get_asset_url( '/assets/css/public.css' ),
					'has_rtl' => true,
					'context' => 'public',
				),
			)
		);
	}

	/**
	 * Get styles according to context.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context Style/Script context (admin, public, none, etc.)
	 *
	 * @return array
	 */
	public function get_styles( $context ) {
		// Set default values.
		$styles = array_map(
			function( $style ) {
				return array_replace_recursive( $this->get_default_style_options(), $style );
			},
			$this->styles
		);

		// Filter according to admin or public context.
		$styles = array_filter(
			$styles,
			function( $style ) use ( $context ) {
				return $style['context'] === $context;
			}
		);

		return $styles;
	}

	/**
	 * Get scripts.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context Script context. (admin, public, none).
	 *
	 * @return array
	 */
	public function get_scripts( $context ) {
		// Set default values.
		$scripts = array_map(
			function( $script ) {
				return array_replace_recursive( $this->get_default_script_options(), $script );
			},
			$this->scripts
		);

		// Filter according to admin or public context.
		$scripts = array_filter(
			$scripts,
			function( $script ) use ( $context ) {
				return $script['context'] === $context;
			}
		);

		return $scripts;
	}

	/**
	 * Default script options.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_default_script_options() {
		return apply_filters(
			'masteriyo_get_default_script_options',
			array(
				'src'           => '',
				'deps'          => array( 'jquery' ),
				'version'       => $this->get_version(),
				'context'       => 'none',
				'in_footer'     => true,
				'register_only' => false,
				'callback'      => '',
			)
		);
	}

	/**
	 * Default style options.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_default_style_options() {
		return apply_filters(
			'masteriyo_get_default_style_options',
			array(
				'src'           => '',
				'deps'          => array(),
				'version'       => $this->get_version(),
				'media'         => 'all',
				'has_rtl'       => false,
				'context'       => 'none',
				'in_footer'     => true,
				'register_only' => false,
				'callback'      => '',
			)
		);
	}

	/**
	 * Return asset URL.
	 *
	 * @since 0.1.0
	 *
	 * @param string $path Assets path.
	 *
	 * @return string
	 */
	private function get_asset_url( $path ) {
		return apply_filters( 'masteriyo_get_asset_url', plugins_url( $path, Constants::get( 'MASTERIYO_PLUGIN_FILE' ) ), $path );
	}

	/**
	 * Register a script for use.
	 *
	 * @since 0.1.0
	 *
	 * @uses   wp_register_script()
	 * @param  string   $handle    Name of the script. Should be unique.
	 * @param  string   $path      Full URL of the script, or path of the script relative to the WordPress root directory.
	 * @param  string[] $deps      An array of registered script handles this script depends on.
	 * @param  string   $version   String specifying script version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
	 * @param  boolean  $in_footer Whether to enqueue the script before </body> instead of in the <head>. Default 'false'.
	 */
	private function register_script( $handle, $path, $deps = array( 'jquery' ), $version = '', $in_footer = true ) {
		wp_register_script( "masteriyo-{$handle}", $path, $deps, $version, $in_footer );
	}

	/**
	 * Register and enqueue a script for use.
	 *
	 * @since 0.1.0
	 *
	 * @uses   wp_enqueue_script()
	 * @param  string   $handle    Name of the script. Should be unique.
	 * @param  string   $path      Full URL of the script, or path of the script relative to the WordPress root directory.
	 * @param  string[] $deps      An array of registered script handles this script depends on.
	 * @param  string   $version   String specifying script version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
	 * @param  boolean  $in_footer Whether to enqueue the script before </body> instead of in the <head>. Default 'false'.
	 */
	private function enqueue_script( $handle, $path = '', $deps = array( 'jquery' ), $version = '', $in_footer = true ) {
		if ( ! in_array( $handle, $this->scripts, true ) && $path ) {
			wp_register_script( "masteriyo-{$handle}", $path, $deps, $version, $in_footer );
		}
		wp_enqueue_script( "masteriyo-{$handle}" );
	}

	/**
	 * Register a style for use.
	 *
	 *
	 * @since 0.1.0
	 *
	 * @uses   wp_register_style()
	 * @param  string   $handle  Name of the stylesheet. Should be unique.
	 * @param  string   $path    Full URL of the stylesheet, or path of the stylesheet relative to the WordPress root directory.
	 * @param  string[] $deps    An array of registered stylesheet handles this stylesheet depends on.
	 * @param  string   $version String specifying stylesheet version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
	 * @param  string   $media   The media for which this stylesheet has been defined. Accepts media types like 'all', 'print' and 'screen', or media queries like '( orientation: portrait )' and '( max-width: 640px )'.
	 * @param  boolean  $has_rtl If has RTL version to load too.
	 */
	private function register_style( $handle, $path, $deps = array(), $version = '', $media = 'all', $has_rtl = false ) {
		$this->styles[] = $handle;
		wp_register_style( "masteriyo-{$handle}", $path, $deps, $version, $media );

		if ( $has_rtl ) {
			wp_style_add_data( "masteriyo-{$handle}", 'rtl', 'replace' );
		}
	}

	/**
	 * Register and enqueue a styles for use.
	 *
	 * @since 0.1.0
	 *
	 * @uses   wp_enqueue_style()
	 * @param  string   $handle  Name of the stylesheet. Should be unique.
	 * @param  string   $path    Full URL of the stylesheet, or path of the stylesheet relative to the WordPress root directory.
	 * @param  string[] $deps    An array of registered stylesheet handles this stylesheet depends on.
	 * @param  string   $version String specifying stylesheet version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
	 * @param  string   $media   The media for which this stylesheet has been defined. Accepts media types like 'all', 'print' and 'screen', or media queries like '( orientation: portrait )' and '( max-width: 640px )'.
	 * @param  boolean  $has_rtl If has RTL version to load too.
	 */
	private function enqueue_style( $handle, $path = '', $deps = array(), $version = '', $media = 'all', $has_rtl = false ) {
		if ( ! in_array( $handle, $this->styles, true ) && $path ) {
			$this->register_style( $handle, $path, $deps, $version, $media, $has_rtl );
		}
		wp_enqueue_style( "masteriyo-{$handle}" );
	}

	/**
	 * Load public scripts and styles.
	 *
	 * @since 0.1.0
	 */
	public function load_public_scripts_styles() {
		$scripts = $this->get_scripts( 'public' );
		$styles  = $this->get_styles( 'public' );

		foreach ( $scripts as $handle => $script ) {
			if ( true === (bool) $script['register_only'] ) {
				$this->register_script( $handle, $script['src'], $script['deps'], $script['version'] );
				continue;
			}

			if ( empty( $script['callback'] ) ) {
				$this->enqueue_script( $handle, $script['src'], $script['deps'], $script['version'] );
			} elseif ( is_callable( $script['callback'] ) && call_user_func_array( $script['callback'], array() ) ) {
				$this->enqueue_script( $handle, $script['src'], $script['deps'], $script['version'] );
			}
		}

		foreach ( $styles as $handle => $style ) {
			if ( true === (bool) $style['register_only'] ) {
				$this->register_style( $handle, $style['src'], $style['deps'], $style['version'], $style['media'], $style['has_rtl'] );
				continue;
			}

			if ( empty( $style['callback'] ) ) {
				$this->enqueue_style( $handle, $style['src'], $style['deps'], $style['version'], $style['media'], $style['has_rtl'] );
			} elseif ( is_callable( $style['callback'] ) && call_user_func_array( $style['callback'], array() ) ) {
				$this->enqueue_style( $handle, $style['src'], $style['deps'], $style['version'], $style['media'], $style['has_rtl'] );
			}
		}
	}

	/**
	 * Load public scripts and styles.
	 *
	 * @since 0.1.1
	 */
	public function load_admin_scripts_styles() {
		$scripts = $this->get_scripts( 'admin' );
		$styles  = $this->get_styles( 'admin' );

		foreach ( $scripts as $handle => $script ) {
			if ( true === (bool) $script['register_only'] ) {
				$this->register_script( $handle, $script['src'], $script['deps'], $script['version'] );
				continue;
			}

			if ( empty( $script['callback'] ) ) {
				$this->enqueue_script( $handle, $script['src'], $script['deps'], $script['version'] );
			} elseif ( is_callable( $script['callback'] ) && call_user_func_array( $script['callback'], array() ) ) {
				$this->enqueue_script( $handle, $script['src'], $script['deps'], $script['version'] );
			}
		}

		foreach ( $styles as $handle => $style ) {
			if ( true === (bool) $style['register_only'] ) {
				$this->register_style( $handle, $style['src'], $style['deps'], $style['version'], $style['media'], $style['has_rtl'] );
				continue;
			}

			if ( empty( $style['callback'] ) ) {
				$this->enqueue_style( $handle, $style['src'], $style['deps'], $style['version'], $style['media'], $style['has_rtl'] );
			} elseif ( is_callable( $style['callback'] ) && call_user_func_array( $style['callback'], array() ) ) {
				$this->enqueue_style( $handle, $style['src'], $style['deps'], $style['version'], $style['media'], $style['has_rtl'] );
			}
		}

		wp_set_script_translations( 'masteriyo-admin', 'masteriyo', Constants::get( 'MASTERIYO_LANGUAGES' ) );
	}

	/**
	 * Load admin localized scripts.
	 *
	 * @since 0.1.1
	 */
	public function load_admin_localized_scripts() {
		$course_list_page = get_post( masteriyo_get_page_id( 'course-list' ) );
		$course_list_slug = ! is_null( $course_list_page ) ? $course_list_page->post_name : '';

		$myaccount_page = get_post( masteriyo_get_page_id( 'myaccount' ) );
		$myaccount_slug = ! is_null( $myaccount_page ) ? $myaccount_page->post_name : '';

		$checkout_page = get_post( masteriyo_get_page_id( 'checkout' ) );
		$checkout_slug = ! is_null( $checkout_page ) ? $checkout_page->post_name : '';

		$this->localized_scripts = apply_filters(
			'masteriyo_localized_scripts',
			array(
				'admin' => array(
					'name' => 'masteriyo',
					'data' => array(
						'rootApiUrl' => esc_url_raw( untrailingslashit( rest_url() ) ),
						'nonce'      => wp_create_nonce( 'wp_rest' ),
						'pageSlugs'  => array(
							'courseList' => $course_list_slug,
							'myaccount'  => $myaccount_slug,
							'checkout'   => $checkout_slug,
						),
						'currency'   => array(
							'code'     => \masteriyo_get_currency(),
							'symbol'   => \masteriyo_get_currency_symbol( masteriyo_get_currency() ),
							'position' => get_option( 'masteriyo.general.currency_position', 'left' ),
						),
						'imageSizes' => get_intermediate_image_sizes(),
					),
				),
			)
		);

		foreach ( $this->localized_scripts as $handle => $script ) {
			\wp_localize_script( "masteriyo-{$handle}", $script['name'], $script['data'] );
		}
	}

	/**
	 * Load public localized scripts.
	 *
	 * @since 0.1.1
	 */
	public function load_public_localized_scripts() {
		$this->localized_scripts = apply_filters(
			'masteriyo_localized_scripts',
			array(
				'edit-myaccount' => array(
					'name' => 'masteriyo_data',
					'data' => array(
						'rootApiUrl'      => esc_url_raw( rest_url() ),
						'current_user_id' => get_current_user_id(),
						'nonce'           => wp_create_nonce( 'wp_rest' ),
						'labels'          => array(
							'save'                   => __( 'Save', 'masteriyo' ),
							'saving'                 => __( 'Saving...', 'masteriyo' ),
							'profile_update_success' => __( 'Your profile was updated successfully', 'masteriyo' ),
						),
					),
				),
				'login-form'     => array(
					'name' => 'masteriyo_data',
					'data' => array(
						'ajax_url' => admin_url( 'admin-ajax.php' ),
						'nonce'    => wp_create_nonce( 'masteriyo_login_nonce' ),
					),
				),
				'checkout'       => array(
					'name' => 'mto_checkout_params',
					'data' => array(
						'ajax_url'            => admin_url( 'admin-ajax.php' ),
						'checkout_url'        => '/?mto-ajax=checkout',
						'i18n_checkout_error' => esc_html__( 'Error processing checkout. Please try again.', 'masteriyo' ),
						'is_checkout'         => true,
						'mto_ajax_url'        => '/?mto-ajax=%%endpoint%%',
					),
				),
			)
		);

		foreach ( $this->localized_scripts as $handle => $script ) {
			\wp_localize_script( "masteriyo-{$handle}", $script['name'], $script['data'] );
		}
	}
}
