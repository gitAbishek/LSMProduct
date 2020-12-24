<?php
/**
 * Class this->session_handler Test
 *
 * @package ThemeGrill\Masteriyo
 */

use ThemeGrill\Masteriyo\Session\SessionHandler;
use ThemeGrill\Masteriyo\Repository\SessionRepository;

/**
 * SessionHandler test class.
 */
class SessionHandlerTest extends WP_UnitTestCase {

	/**
	 * Session instance.
	 *
	 * @since 0.1.0
	 *
	 * @var ThemeGrill\Masteriyo\Session\SessionHandler
	 */
	private $session_handler;

	/**
	 * Setup.
	 *
	 * @since 0.1.0
	 */
	public function setUp() {
		parent::setUp();
		\WP_Mock::setUp();
		$this->session_handler = new SessionHandler( new SessionRepository() );
	}

	/**
	 * Teardown
	 *
	 * @since 0.1.0
	 */
	public function tearDown() {
		$this->addToAssertionCount(
	        \Mockery::getContainer()->mockery_getExpectationCount()
		);
		\WP_Mock::tearDown();
	}

	/**
	 * Test start hooks and session data.
	 *
	 * @since 0.1.0
	 */
	public function test_start() {
		// Test actions are added.
		$mock_session_handler = $this->getMockBuilder( SessionHandler::class )
			->setConstructorArgs( [ new SessionRepository ] )
			->setMethods( ['init_session_cookie'] )->getMock();
		$mock_session_handler->method( 'init_session_cookie' )->willReturn( true );

		$mock_session_handler->start();

		$cart_cookie_action = has_action( 'masteriyo_set_cart_cookies', array( $mock_session_handler, 'set_user_session_cookie' ) );
		$this->assertEquals( $cart_cookie_action, 10 );

		$save_data_action = has_action( 'shutdown', array( $mock_session_handler, 'save_data' ) );
		$this->assertEquals( $save_data_action, 20 );

		$destory_session_action = has_action( 'wp_logout', array( $mock_session_handler, 'destroy_session' ) );
		$this->assertEquals( $destory_session_action, 10 );
	}

	/**
	 * Test get session cookie.
	 *
	 * @since 0.1.0
	 */
	public function test_get_session_cookie() {
		// Should return false, when no cookie is set.
		$this->assertFalse( $this->session_handler->get_session_cookie() );

		// Should return false, if the cookie value is not string.
		$_COOKIE[ $this->session_handler->get_name() ] = 1234;
		$this->assertFalse( $this->session_handler->get_session_cookie() );

		// Should return false, when no user id is set in the cookie.
		$_COOKIE[ $this->session_handler->get_name() ] = '||1234||4567||abcdef';
		$this->assertFalse( $this->session_handler->get_session_cookie() );

		// Should return false, if the cookie hash is empty.
		$_COOKIE[ $this->session_handler->get_name() ] = '1||1234||4567||';
		$this->assertFalse( $this->session_handler->get_session_cookie() );

		// Should return false, if the cookie hash doesn't matches.
		$_COOKIE[ $this->session_handler->get_name() ] = '1||1234||4567||abcdef';
		$this->assertFalse( $this->session_handler->get_session_cookie() );

		//Should return cookie data array, if the cookie hash matches.
		list( $user_id, $session_expiration ) = array( '1', '1234' );
		$to_hash = $user_id . '|' . $session_expiration;
		$cookie_hash = hash_hmac( 'md5', $to_hash, wp_hash( $to_hash ) );

		$_COOKIE[ $this->session_handler->get_name() ] = "1||1234||4567||{$cookie_hash}";
		$diff = array_diff( $this->session_handler->get_session_cookie(), explode( '||', $_COOKIE[ $this->session_handler->get_name() ] ) );
		$this->assertTrue( empty( $diff ) );
	}

	/**
	 * Test init session cookie.
	 *
	 * @since 0.1.0
	 */
	public function test_init_session_cookie() {
		// Return true, when there is cookie is set.
		$mock_session_handler = $this->getMockBuilder( SessionHandler::class )
			->setConstructorArgs( [ new SessionRepository ] )
			->setMethods( ['get_session_cookie'] )->getMock();
		$mock_session_handler->method( 'get_session_cookie' )->willReturn( false );

		$mock_session_handler->init_session_cookie();
		$id = $mock_session_handler->get_id();
		$this->assertEquals( strlen( $mock_session_handler->get_id() ), 32 );
	}

	/**
	 * Test is_started
	 *
	 * @since 0.1.0
	 */
	public function test_is_started() {
		// Return true, when there is cookie is set.
		$mock_session_handler = $this->getMockBuilder( SessionHandler::class )
			->setConstructorArgs( [ new SessionRepository ] )
			->setMethods( ['has_cookie'] )->getMock();
		$mock_session_handler->method( 'has_cookie' )->willReturn( true );
		$this->assertTrue( $mock_session_handler->is_started() );

		// Return true, when the user is logged in.
		\WP_Mock::userFunction( 'is_user_logged_in', array(
			'times' => 1,
			'return' => true,
		) );
		$this->assertTrue( $this->session_handler->is_started() );

		// Return false, when the cookie is not set and the user is not logged in.
		\WP_Mock::userFunction( 'is_user_logged_in', array(
			'times' => 1,
			'return' => false,
		) );
		$mock_session_handler = $this->getMockBuilder( SessionHandler::class )
			->setConstructorArgs( [ new SessionRepository ] )
			->setMethods( ['has_cookie'] )->getMock();
		$mock_session_handler->method( 'has_cookie' )->willReturn( false );
		$this->assertFalse( $mock_session_handler->is_started() );
	}

	/**
	 * Test has cookie.
	 *
	 * @since 0.1.0
	 */
	public function has_cookie() {
		// Return false, when the cookie is not set.
		unset( $_COOKIE[ $this->session_handler->cookie ] );
		$this->assertFalse( $this->session_handler->has_cookie() );

		// Return true, when the cookie is set.
		$_COOKIE[ $this->session_handler->cookie ] = 'cookie';
		$this->assertTrue( $this->session_handler->has_cookie() );
		unset( $_COOKIE[ $this->session_handler->cookie ] );
	}
}
