<?php
/**
 * Class this->session Test
 *
 * @package ThemeGrill\Masteriyo
 */

use ThemeGrill\Masteriyo\Abstracts\Session\Session;

/**
 * this->session test class.
 */
class SessionTest extends WP_UnitTestCase {

	/**
	 * Session instance.
	 *
	 * @since 0.1.0
	 *
	 * @var ThemeGrill\Masteriyo\Abstracts\Session\Session
	 */
	private $session;

	/**
	 * Setup.
	 *
	 * @since 0.1.0
	 */
	public function setUp() {
		parent::setUp();
		\WP_Mock::setUp();
		$this->session = $this->getMockForAbstractClass( Session::class );
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
	 * Test get id.
	 *
	 * @since 0.1.0
	 */
	public function test_get_id() {
		$this->session->set_id( 12 );
		$session_id = $this->session->get_id();
		$this->assertTrue( is_string( $session_id ) );
		$this->assertEquals( $session_id, '12' );
	}

	/**
	 * Test get id.
	 *
	 * @since 0.1.0
	 */
	public function test_set_id() {
		$this->session->set_id( 'session124' );
		$session_id = $this->session->get_id();
		$this->assertEquals( $session_id, $session_id );
	}

	/**
	 * Test get expiry.
	 *
	 * @since 0.1.0
	 */
	public function test_get_expiry() {
		$this->session->set_expiry( 12345 );
		$this->assertEquals( $this->session->get_expiry(), 12345 );
		$this->assertTrue( is_int( $this->session->get_expiry() ) );
	}

	/**
	 * Test set expiry.
	 *
	 * @since 0.1.0
	 */
	public function test_set_expiry() {
		$this->session->set_expiry( -12345 );
		$this->assertEquals( $this->session->get_expiry(), 12345 );
		$this->session->set_expiry( -12345.54564 );
		$this->assertEquals( $this->session->get_expiry(), 12345 );
	}

	/**
	 * Test generate user id.
	 *
	 * @since 0.1.0
	 */
	public function test_generate_id() {
		// Test when user is not logged in.
		$user_id = $this->session->generate_id();
		$this->assertEquals( strlen( $user_id ) , 32 );
		preg_match('/^([a-f]|\d){32}/', $user_id, $matches);
		$this->assertFalse( empty( $matches ) );

		// Test when the user is logged in.
		\WP_Mock::userFunction( 'is_user_logged_in', array(
			'times' => 1,
			'return' => true,
		) );
		\WP_Mock::userFunction( 'get_current_user_id', array(
			'times' => 1,
			'return' => 12
		) );
		$user_id = $this->session->generate_id();

		$this->assertTrue( is_string( $user_id ) );
		$this->assertEquals( $user_id, '12' );
	}

	/**
	 * Test get.
	 *
	 * @since 0.1.0
	 */
	public function test_get() {
		$this->session->put( 'firstname', 'john' );
		$firstname = $this->session->get( 'firstname' );
		$this->assertEquals( $firstname, 'john' );
	}

	/**
	 * Test set.
	 *
	 * @since 0.1.0
	 */
	public function test_set() {
		$this->session->put( 'weekdays', array( 'sunday', 'monday' ) );
		$weekdays = $this->session->get( 'weekdays' );
		$diff = array_diff( $weekdays, array( 'sunday', 'monday' ) );
		$this->assertTrue( is_array( $weekdays ) );
		$this->assertTrue( empty( $diff ) );
	}

	/**
	 * Test all.
	 *
	 * @since 0.1.0
	 */
	public function test_all() {
		$this->session->put( 'firstname', 'john' );
		$this->session->put( 'lastname', 'doe' );
		$user_details = $this->session->all();
		$diff = array_diff( $user_details, array( 'john', 'doe' ) );
		$this->assertTrue( empty( $diff ) );
	}

	/**
	 * Test exists.
	 *
	 * @since 0.1.0
	 */
	public function test_exists() {
		$this->session->put( 'firstname', 'john' );
		$this->session->put( 'lastname', 'doe' );
		$this->assertTrue( $this->session->exists( 'firstname' ) );
		$this->assertTrue( $this->session->exists( array( 'firstname', 'lastname' ) ) );
		$this->assertFalse( $this->session->exists( 'age' ) );
		$this->assertFalse( $this->session->exists( array( 'age', 'height' ) ) );
	}

	/**
	 * Test has.
	 *
	 * @since 0.1.0
	 */
	public function test_has() {
		$this->session->put( 'firstname', 'john' );
		$this->session->put( 'lastname', 'doe' );
		$this->session->put( 'age', null );

		$this->assertTrue( $this->session->has( 'firstname' ) );
		$this->assertTrue( $this->session->has( array( 'firstname', 'lastname' ) ) );
		$this->assertFalse( $this->session->has( 'age', null ) );
		$this->assertFalse( $this->session->has( 'height', null ) );
		$this->assertFalse( $this->session->has( array( 'age', 'lastname' ) ) );

	}

	/**
	 * Test remove.
	 *
	 * @since 0.1.0
	 */
	public function test_remove() {
		$this->session->put( 'firstname', 'john' );
		$firstname = $this->session->remove( 'firstname' );
		$this->assertEquals( $firstname, 'john' );
		$firstname = $this->session->remove( 'firstname' );
		$this->assertEquals( $firstname, null );
	}

	/**
	 * Test forget.
	 *
	 * @since 0.1.0
	 */
	public function test_forget() {
		$this->session->put( 'firstname', 'john' );
		$this->session->put( 'lastname', 'doe' );
		$this->session->put( 'age', 27 );

		$this->session->forget( 'firstname' );
		$this->assertFalse( $this->session->exists( 'firstname' ) );

		$this->session->forget( array( 'lastname', 'age' ) );
		$this->assertFalse( $this->session->exists( 'lastname' ) );
		$this->assertFalse( $this->session->exists( 'age' ) );
	}
}
