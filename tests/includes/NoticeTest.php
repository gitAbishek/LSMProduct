<?php
/**
 * Class this->session_handler Test
 *
 * @package ThemeGrill\Masteriyo
 */

use ThemeGrill\Masteriyo\Notice;
use ThemeGrill\Masteriyo\Session\Session;
use ThemeGrill\Masteriyo\Repository\SessionRepository;
use ThemeGrill\Masteriyo\Template;

/**
 * Session test class.
 */
class NoticeTest extends WP_UnitTestCase {

	/**
	 * Notice instance.
	 *
	 * @since 0.1.0
	 *
	 * @var ThemeGrill\Masteriyo\Notice
	 */
	private $notice;

	/**
	 * Setup.
	 *
	 * @since 0.1.0
	 */
	public function setUp() {
		parent::setUp();
		\WP_Mock::setUp();

		// Test actions are added.
		$mock_session_handler = $this->getMockBuilder( Session::class )
			->setConstructorArgs( [ new SessionRepository ] )
			->setMethods( ['get', 'set'] )->getMock();

		$mock_session_handler->method( 'get' )->willReturn(
			array(
				array(
					'type'   => 'error',
					'notice' => 'Notice Error 1',
					'data'   => array( 'price' => 10.45 )
				),
				array(
					'type'   => 'error',
					'notice' => 'Notice Error 2',
					'data'   => array( 'price' => 10.45 )
				),
				array(
					'type'   => 'success',
					'notice' => 'Notice Success 1',
					'data'   => array( 'price' => 10.45 )
				),
				array(
					'type'   => 'info',
					'notice' => 'Notice Info 1',
					'data'   => array( 'price' => 10.45 )
				),
				array(
					'type'   => 'warning',
					'notice' => 'Notice Warning 1',
					'data'   => array( 'price' => 10.45 )
				),
			)
		);

		$this->notice = new Notice( $mock_session_handler, new Template );
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
	 * Test count.
	 *
	 * @since 0.1.0
	 */
	public function test_count() {
		$this->assertEquals( $this->notice->count() , 5 );
		$this->assertEquals( $this->notice->count( Notice::ERROR ), 2 );
		$this->assertEquals( $this->notice->count( Notice::SUCCESS ), 1 );
	}

	/**
	 * Test has.
	 *
	 * @since 0.1.0
	 */
	public function test_has() {
		$this->assertTrue( $this->notice->has( 'Notice Success 1', Notice::SUCCESS ) );
		$this->assertFalse( $this->notice->has( 'Notice Success 1', Notice::ERROR ) );
		$this->assertFalse( $this->notice->has( 'Notice Success 2', Notice::SUCCESS ) );
	}

	/**
	 * Test get.
	 *
	 * @since 0.1.0
	 */
	public function test_get() {
		$this->assertEquals( count( $this->notice->get() ), 5 );
		$this->assertEquals( count( $this->notice->get( Notice::SUCCESS ) ), 1 );
		$this->assertEquals( count( $this->notice->get( Notice::ERROR) ), 2 );
	}

	/**
	 * Test add.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function test_add() {
		$session_handler = $this->getMockBuilder( Session::class )
			->setConstructorArgs( [ new SessionRepository ] )
			->setMethods( ['get', 'set'] )->getMock();

		$session_handler->expects($this->exactly(1))->method('get')->willReturn( array() );
		$session_handler->method('set')->willReturn( array() );

		\WP_Mock::expectFilter( 'masteriyo_add_notice_' . Notice::SUCCESS, 'Notice Message' );

		$notice = new Notice( $session_handler, new Template );

		$notice->add( 'Notice Message' );
	}
}
