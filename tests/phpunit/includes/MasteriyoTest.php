<?php
/**
 * Class MasteriyoTest
 *
 * @package Masteriyo
 */

use Masteriyo\Constants;
use Masteriyo\Masteriyo;
use Brain\Monkey;
use Brain\Monkey\Filters;


/**
 * Masteriyo class test.
 */
class MasteriyoTest extends WP_UnitTestCase {

	/**
	 * Main masteriyo class instance.
	 *
	 * @var Masteriyo\Masteriyo
	 */
	private static $masteriyo;

	/**
	 * Setup.
	 */
	public static function set_up_before_class() {
		parent::set_up_before_class();

		self::$masteriyo = new Masteriyo;
	}

	 protected function set_up() {
        parent::set_up();
        Monkey\setUp();
    }

	/**
	 * Tear down
	 */
	protected function tear_down() {
		Monkey\tearDown();
        parent::tear_down();
    }

	/**
	 * Test init hooks.
	 */
	public function test_init_hooks() {
		$this->assertNotFalse( has_action( 'init', array( self::$masteriyo, 'after_wp_init' ) ) );
		$this->assertNotFalse( has_action( 'admin_bar_menu', array( self::$masteriyo, 'add_courses_page_link' ) ) );
		$this->assertNotFalse( has_action( 'admin_notices', array( self::$masteriyo, 'masteriyo_display_compatibility_notice' ) ) );

		$this->assertNotFalse( has_action( 'plugin_row_meta', array( self::$masteriyo, 'add_plugin_links' ) ) );
		$this->assertNotFalse( has_action( 'template_include', array( self::$masteriyo, 'template_loader' ) ) );
		$this->assertNotFalse( has_action( 'template_redirect', array( self::$masteriyo, 'redirect_reset_password_link' ) ) );

		$this->assertNotFalse( has_action( 'switch_blog', array( self::$masteriyo, 'define_tables' ) ) );
		$this->assertNotFalse( has_action( 'admin_init', array( self::$masteriyo, 'admin_redirects' ) ) );
		$this->assertNotFalse( has_action( 'after_setup_theme', array( self::$masteriyo, 'add_image_sizes' ) ) );

		$this->assertNotFalse( has_action( 'in_admin_header', array( self::$masteriyo, 'hide_admin_notices' ) ) );
		$this->assertNotFalse( has_action( 'admin_enqueue_scripts', 'wp_enqueue_media' ) );

		$this->assertNotFalse( has_action( 'cli_init', array( 'Masteriyo\Cli\Cli', 'register' ), 'CLI is not hooked.' ) );

		$this->assertNotFalse( has_action( 'wp_kses_allowed_html', array( self::$masteriyo, 'register_custom_kses_allowed_html' ), 'Custom WP Kses allowed html not hooked.' ) );
	}

	/**
	 * Test plugin links.
	 */
	public function test_add_plugin_links() {
		$links = self::$masteriyo->add_plugin_links( array(), 'test.php' );
		$this->assertEmpty( $links, 'Links should be empty for others plugins.' );

		$links = self::$masteriyo->add_plugin_links( array(),  Constants::get( 'MASTERIYO_PLUGIN_BASENAME' ));
		$diff = array_diff_assoc(
			$links,
			array(
				'docs' => '<a target="_blank" href="https://docs.masteriyo.com/" aria-label="View Masteriyo documentation">Docs</a>',
				'support' => '<a target="_blank" href="https://wordpress.org/support/plugin/learning-management-system/" aria-label="Visit community forums">Community Support</a>',
				'review' => '<a target="_blank" href="https://wordpress.org/support/plugin/learning-management-system/reviews/#new-post" aria-label="Rate the plugin.">Rate the plugin ★★★★★</a>',
			)
		);
		$this->assertEmpty( $diff, 'docs, support and review links should be added.');
	}

	/**
	 * Test plugin actions links.
	 */
	public function test_add_plugin_action_links() {
		$action_links = self::$masteriyo->add_plugin_action_links( array() );
		$diff         = array_diff_assoc(
			$action_links,
			array(
				'settings' => '<a href="http://example.org/wp-admin/admin.php?page=masteriyo#/settings" aria-label="View Masteriyo settings">Settings</a>',
			)
		);

		$this->assertEmpty( $diff, 'Plugin action links are added.' );
	}
}
