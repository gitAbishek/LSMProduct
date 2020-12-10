<?php
/**
 * Masteriyo setup.
 *
 * @package ThemeGrill\Masteriyo
 *
 * @since 0.1.0
 */

namespace ThemeGrill\Masteriyo;

use ThemeGrill\Masteriyo\PostType\RegisterPostTypes;
use ThemeGrill\Masteriyo\Taxonomy\RegisterTaxonomies;
use ThemeGrill\Masteriyo\RestApi\RestApi;

defined( 'ABSPATH' ) || exit;

/**
 * Main Masteriyo class.
 *
 * @class ThemeGrill\Masteriyo\Masteriyo
 */

final class Masteriyo {

	/**
	 * Script Style.
	 *
	 * @since 0.1.0
	 *
	 * @var ThemeGrill\Masteriyo\ScriptStyle
	 */
	public $script_style;

	/**
	 * Ajax.
	 *
	 * @since 0.1.0
	 *
	 * @var ThemeGrill\Masteriyo\Ajax
	 */
	public $ajax;

	/**
	 * The single instance of the class.
	 *
	 * @since 0.1.0
	 *
	 * @var ThemeGrill\Masteriyo\Masteriyo
	 */
	protected static $instance = null;

	/**
	 * Get Masteriyo instance.
	 *
	 * Ensures only one instance of Masteriyo is loaded
	 * or can be loaded.
	 *
	 * @since 0.1.0
	 * @static
	 *
	 * @return ThemeGrill\Masteriyo\Masteriyo
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 */
	private function __construct() {
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
		add_action( 'init', array( $this, 'init' ), 0 );
	}

	/**
	 * Initialize Masteriyo when WordPress initializes.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function init() {
		// Before init action.
		do_action( 'before_masteriyo_init' );

		// Set up localization.
		$this->load_plugin_textdomain();

		// Update the plugin version.
		$this->update_plugin_version();

		// Load class instances.
		$this->ajax         = new Ajax();
		$this->script_style = new ScriptStyle();
		$this->admin_menu   = new AdminMenu();

		RegisterPostTypes::instance();
		RegisterTaxonomies::register();
		Install::init();
		RestApi::instance()->init();

		// After init action.
		do_action( 'after_masteriyo_init' );
	}

	/**
	 * Load localization files.
	 *
	 * Note: the first-loaded translation file overrides any following ones
	 * if the same translation is present.
	 *
	 * Locales found in:
	 *      - WP_LANG_DIR/masteriyo/masteriyo-LOCALE.mo
	 *      - WP_LANG_DIR/plugins/masteriyo-LOCALE.mo
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function load_plugin_textdomain() {
		if ( function_exists( 'determine_locale' ) ) {
			$locale = determine_locale();
		} else {
			// TODO Remove when start supporting WP 5.0 or later.
			$locale = is_admin() ? get_user_locale() : get_locale();
		}

		$locale = apply_filters( 'plugin_locale', $locale, 'masteriyo' );

		unload_textdomain( 'masteriyo' );
		load_textdomain(
			'masteriyo',
			WP_LANG_DIR . '/masteriyo/masteriyo-' . $locale . '.mo'
		);
		load_plugin_textdomain(
			'masteriyo',
			false,
			MASTERIYO_LANGUAGES
		);
	}

	/**
	 * Get the plugin url.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', MASTERIYO_PLUGIN_FILE ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( MASTERIYO_PLUGIN_FILE ) );
	}

	/**
	 * Update the plugin version.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private function update_plugin_version() {
		if ( false === get_option( 'masteriyo_version' ) ) {
			update_option( 'masteriyo_version', MASTERIYO_VERSION );
		}
	}
}


