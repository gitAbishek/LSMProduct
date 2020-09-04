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
	 *
	 * @since 0.1.0
	 *
	 */
	public function __construct() {
		$this->init();
	}


	/**
	 * Initialization.
	 *
	 * @since 0.1.0
	 */
	public function init() {
		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private function init_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'load_public_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ) );
	}

	/**
	 * Get styles.
	 *
	 * @since 0.1.0
	 *
	 * @param string $type Style/Script type. (admin, public, none).
	 *
	 * @return array
	 */
	public function get_styles( $type = 'none' ) {
		$version = Constants::get_constant( 'MASTERIYO_VERSION' );

		$styles = apply_filters( 'masteriyo_enqueue_styles', array(
			'masteriyo-admin' => array(
				'src'      => $this->get_asset_url( '/assets/build/admin.css' ),
				'deps'     => '',
				'version'  => $version,
				'media'    => 'all',
				'has_rtl' => true,
				'type'     => 'admin'
			),
			'masteriyo-public' => array(
				'src'      => $this->get_asset_url( '/assets/build/public.css' ),
				'deps'     => '',
				'version'  => $version,
				'media'    => 'all',
				'has_rtl' => true,
				'type'     => 'public'
			),
		) );


		// Filter according to admin or public type.
		if ( 'admin' === $type || 'public' === $type ) {
			$styles = array_filter( $styles, function( $style )  use( $type ) {
				$style = array_replace_recursive( $this->get_default_style_options(), $style );
				return $style['type'] === $type;
			} );
		}

		return $styles;
	}

	/**
	 * Get scripts.
	 *
	 * @since 0.1.0
	 *
	 * @param string $type Style/Script type. (admin, public, none).
	 *
	 * @return array
	 */
	public function get_scripts( $type = 'none' ) {
		$version = Constants::get_constant( 'MASTERIYO_VERSION' );

		$scripts = apply_filters( 'masteriyo_enqueue_scripts', array(
			'masteriyo-admin' => array(
				'src'      => $this->get_asset_url( '/assets/build/admin.js' ),
				'deps'     => array( 'react', 'wp-components', 'wp-element', 'wp-i18n', 'wp-polyfill' ),
				'version'  => $version,
				'type'     => 'admin',
				'callback' => ''
			),
			'masteriyo-public' => array(
				'src'      => $this->get_asset_url( '/assets/build/public.js' ),
				'deps'     => array( 'wp-polyfill' ),
				'version'  => $version,
				'type'     => 'public',
				'callback' => ''
			),
		) );

		// Set default values.
		$scripts = array_map( function( $script ) {
			return array_replace_recursive( $this->get_default_script_options(), $script );
		}, $scripts );

		// Filter according to admin or public type.
		if ( 'admin' === $type || 'public' === $type ) {
			$scripts = array_filter( $scripts, function( $script )  use( $type ) {
				return $script['type'] === $type;
			} );
		}

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
		return apply_filters( 'masteriyo_get_default_script_options', array(
			'src'       => '',
			'deps'      => array( 'jquery' ),
			'version'   => '',
			'type'      => 'none',
			'in_footer' => true,
			'callback'  => ''
		) );
	}

	/**
	 * Default style options.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_default_style_options() {
		return apply_filters( 'masteriyo_get_default_style_options', array(
			'src'       => '',
			'deps'      => array(),
			'version'   => '',
			'media'     => 'all',
			'has_rtl'   => false,
			'type'      => 'none',
			'in_footer' => true,
			'callback'  => ''
		) );
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
		return apply_filters( 'masteriyo_get_asset_url', plugins_url( $path, MASTERIYO_PLUGIN_FILE ), $path );
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
		$scripts = $handle;
		wp_register_script( $handle, $path, $deps, $version, $in_footer );
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
			wp_register_script( $handle, $path, $deps, $version, $in_footer );
		}
		wp_enqueue_script( $handle );
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
		wp_register_style( $handle, $path, $deps, $version, $media );

		if ( $has_rtl ) {
			wp_style_add_data( $handle, 'rtl', 'replace' );
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
		wp_enqueue_style( $handle );
	}

	/**
	 * Load public scripts and styles.
	 *
	 * @since 0.1.1
	 */
	public function load_public_scripts() {
		$scripts = $this->get_scripts( 'public' );

		foreach ( $scripts as $handle => $script ) {
			if ( ! \is_callable( $script ) ) {
				$a = 1;
			}
		}
	}

	/**
	 * Load public scripts and styles.
	 *
	 * @since 0.1.1
	 */
	public function load_admin_scripts() {
		$scripts = $this->get_scripts( 'admin' );
		$styles  = $this->get_styles( 'admin' );

		foreach ( $scripts as $handle => $script ) {
			if ( is_callable( $script ) ) {
				$this->register_script(
					$handle, $script['src'], $script['deps'], $script['version'] );
			} else {
				$this->enqueue_script( $handle, $script['src'], $script['deps'], $script['version'] );
			}
		}

		foreach ( $styles as $handle => $style ) {
			if ( is_callable( $style ) ) {
				$this->register_style( $handle, $style['src'], $style['deps'], $style['version'], $style['media'], $style['has_rtl'] );
			} else {
				$this->enqueue_style( $handle, $style['src'], $style['deps'], $style['version'], $style['media'], $style['has_rtl'] );
			}
		}
	}
}
