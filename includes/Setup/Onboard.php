<?php
/**
 * Masteriyo Onboard class.
 *
 * @since 0.1.0
 *
 * @package  ThemeGrill\Masteriyo\Setup
 */

namespace ThemeGrill\Masteriyo\Setup;

// use ThemeGrill\Masteriyo\Setup\Onboard;

defined( 'ABSPATH' ) || exit;

class Onboard {

	/**
	 * Page name.
	 *
	 * @since 0.1.0
	 * @access private
	 * @var string Current page name.
	 */
	private $page_name = 'masteriyo-onboard';

	/**
	 * Initializing onboarding class.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function init() {

		// if we are here, we assume we don't need to run the wizard again
		// and the user doesn't need to be redirected here
		update_option( 'masteriyo_first_time_activation_flag', true );

		add_action( 'admin_menu', array( $this, 'add_onboarding_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'onboard_setup_wizard' ), 30 );
	}

	/**
	 * Add Menu for onboard process.
	 *
	 * @since 0.1.0
	 */
	public function add_onboarding_admin_menu() {
		add_dashboard_page( '', '', 'manage_options', $this->page_name, '' );
	}

	/**
	 * Onboarding process.
	 *
	 * @since 0.1.0
	 */
	public function onboard_setup_wizard() {

		// Proceeding only when we are on right page.
		if ( ! isset( $_GET['page'] ) || $this->page_name !== $_GET['page'] ) {
			return;
		}

		ob_start();

		$this->setup_wizard_header();
		$this->setup_wizard_body();
		$this->setup_wizard_footer();

		exit;
	}

	/**
	 * Setup wizard header content.
	 *
	 * @since 0.1.0
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
					<?php // wp_print_styles( 'masteriyo-onboard' ); ?>
				</head>
		<?php
	}

	/**
	 * Setup wizard body content.
	 *
	 * @since 0.1.0
	 */
	public function setup_wizard_body() {
		?>
			<body class="masteriyo-user-onboarding-wizard">
				<div id="masteriyo-onboarding" class="masteriyo-main-wrap">
					<h1>OnBoard Page</h1>
				</div>
			</body>
		<?php
	}

	/**
	 * Setup wizard footer content.
	 *
	 * @since 0.1.0
	 */
	public function setup_wizard_footer() {
		if ( function_exists( 'wp_print_media_templates' ) ) {
			wp_print_media_templates();
		}
		wp_print_footer_scripts();
		?>
		</html>
		<?php
	}

}
