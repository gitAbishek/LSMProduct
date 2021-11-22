<?php
/**
 * Masteriyo Onboard class.
 *
 * @since 1.0.0
 *
 * @package  Masteriyo\Setup
 */

namespace Masteriyo\Setup;

use Masteriyo\ScriptStyle;

defined( 'ABSPATH' ) || exit;

class Onboard {

	/**
	 * Page name.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string Current page name.
	 */
	private $page_name = 'masteriyo-onboard';

	/**
	 * Initializing onboarding class.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function init() {

		$wizard_ran = get_option( 'masteriyo_first_time_activation_flag', false );

		// If Wizard was ran already, then do not proceed to Wizard page again,
		if ( $wizard_ran ) {
			return;
		}

		add_action( 'admin_menu', array( $this, 'add_onboarding_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'onboard_setup_wizard' ), 30 );
	}

	/**
	 * Add Menu for onboard process.
	 *
	 * @since 1.0.0
	 */
	public function add_onboarding_admin_menu() {
		add_menu_page(
			__( 'Masteriyo Onboard', 'masteriyo' ),
			'masteriyo onboard',
			'manage_options',
			$this->page_name,
			''
		);
	}

	/**
	 * Onboarding process.
	 *
	 * @since 1.0.0
	 */
	public function onboard_setup_wizard() {

		// if we are here, we assume we don't need to run the wizard again
		// and the user doesn't need to be redirected here
		update_option( 'masteriyo_first_time_activation_flag', true );

		// Proceeding only when we are on right page.
		if ( ! isset( $_GET['page'] ) || $this->page_name !== $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		$onboard_dependencies = include_once MASTERIYO_PLUGIN_DIR . '/assets/js/build/masteriyo-gettingStarted.' . MASTERIYO_VERSION . '.asset.php';
		$suffix               = ScriptStyle::get_asset_suffix();

		wp_register_script(
			'masteriyo-onboarding',
			plugin_dir_url( MASTERIYO_PLUGIN_FILE ) . "/assets/js/build/masteriyo-gettingStarted{$suffix}.js",
			$onboard_dependencies['dependencies'],
			$onboard_dependencies['version'],
			true
		);

		wp_register_script(
			'masteriyo-dependencies',
			plugin_dir_url( MASTERIYO_PLUGIN_FILE ) . "/assets/js/build/masteriyo-dependencies{$suffix}.js",
			$onboard_dependencies['dependencies'],
			$onboard_dependencies['version'],
			true
		);

		// Add localization vars.
		wp_localize_script(
			'masteriyo-onboarding',
			'_MASTERIYO_',
			array(
				'rootApiUrl'           => esc_url_raw( untrailingslashit( rest_url() ) ),
				'nonce'                => wp_create_nonce( 'wp_rest' ),
				'adminURL'             => esc_url( admin_url() ),
				'siteURL'              => esc_url( home_url( '/' ) ),
				'pluginUrl'            => esc_url( plugin_dir_url( MASTERIYO_PLUGIN_FILE ) ),
				'permalinkStructure'   => get_option( 'permalink_structure' ),
				'permalinkOptionsPage' => esc_url( admin_url( 'options-permalink.php' ) ),
				'pageBuilderURL'       => esc_url( admin_url( '/admin.php?page=masteriyo#/courses/add-new-course' ) ),
				'pagesID'              => array(
					'courses'  => masteriyo_get_page_id_by_slug( 'courses' ),
					'account'  => masteriyo_get_page_id_by_slug( 'account' ),
					'checkout' => masteriyo_get_page_id_by_slug( 'masteriyo-checkout' ),
				),
			)
		);

		wp_enqueue_script( 'masteriyo-onboarding' );
		wp_enqueue_script( 'masteriyo-dependencies' );

		ob_start();

		$this->setup_wizard_header();
		$this->setup_wizard_body();
		$this->setup_wizard_footer();

		exit;
	}

	/**
	 * Setup wizard header content.
	 *
	 * @since 1.0.0
	 */
	public function setup_wizard_header() {
		?>
			<!DOCTYPE html>
			<html <?php language_attributes(); ?>>
				<head>
					<meta name="viewport" content="width=device-width"/>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
					<title>
						<?php esc_html_e( 'Masteriyo LMS - Onboarding', 'masteriyo' ); ?>
					</title>
					<?php wp_print_head_scripts(); ?>
				</head>
		<?php
	}

	/**
	 * Setup wizard body content.
	 *
	 * @since 1.0.0
	 */
	public function setup_wizard_body() {
		?>
			<body class="masteriyo-user-onboarding-wizard">
				<div id="masteriyo-onboarding" class="masteriyo-main-wrap">
				</div>
			</body>
		<?php
	}

	/**
	 * Setup wizard footer content.
	 *
	 * @since 1.0.0
	 */
	public function setup_wizard_footer() {
		if ( function_exists( 'wp_print_media_templates' ) ) {
			wp_print_media_templates();
		}
		wp_print_footer_scripts();
		wp_print_scripts( 'masteriyo-onboarding' );
		?>
		</html>
		<?php
	}

}
