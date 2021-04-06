<?php
/**
 * Class Format Test
 *
 * @package ThemeGrill\Masteriyo
 */

use ThemeGrill\Masteriyo\Helper\Format;
use ThemeGrill\Masteriyo\Constants;

/**
 * Session test class.
 */
class FormatTest extends WP_UnitTestCase {

	/**
	 * Format instance.
	 *
	 * @since 0.1.0
	 *
	 * @var ThemeGrill\Masteriyo\Helper\Format
	 */
	private $format;

	/**
	 * Setup.
	 *
	 * @since 0.1.0
	 */
	public function setUp() {
		parent::setUp();
		\WP_Mock::setUp();

		$this->format = new Format();
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
	 * Test get_price_decimal_separator.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function test_decimal() {
		// $price = $this->format->decimal( 123456789.566544 );
		// $this->assertEquals( $price,  123456789.5665 );

		$price = $this->format->decimal( '123456789...566.544', 6 );
		$this->assertEquals( $price,  123456789566.544000 );
	}

	/**
	 * Test get_price_decimal_separator.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function test_get_price_decimal_separator() {
		// Test when price decimal separator is not set in the database.
		\WP_Mock::userFunction( 'get_option', array(
			'times'  => 1,
			'args'   => 'masteriyo_price_decimal_sep',
			'return' => ''
		));
		$this->assertEquals( $this->format->get_price_decimal_separator(), '.' );

		// Test when price decimal separator is set in the database.
		\WP_Mock::userFunction( 'get_option', array(
			'times'  => 1,
			'args'   => 'masteriyo_price_decimal_sep',
			'return' => '#'
		));
		$this->assertEquals( $this->format->get_price_decimal_separator(), '#' );

		// Test when the filter is used.
		\WP_Mock::userFunction( 'get_option', array(
			'times'  => 1,
			'args'   => 'masteriyo_price_decimal_sep',
			'return' => ''
		));
		\WP_Mock::userFunction( 'apply_filters', array(
			'times'  => 1,
			'args'   => array( 'masteriyo_get_price_decimal_separator', '' ),
			'return' => '$'
		));
		$this->assertEquals( $this->format->get_price_decimal_separator(), '$' );
	}

	/**
	 * Test get_price_decimals.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function test_get_price_decimals() {
		// Test when price number decimals is not set in the database.
		\WP_Mock::userFunction( 'get_option', array(
			'times'  => 1,
			'args'   => array( 'masteriyo_price_num_decimals', 2 ),
			'return' => 2
		));
		$this->assertEquals( $this->format->get_price_decimals(), 2 );

		// Test when price number decimals is set in the database.
		\WP_Mock::userFunction( 'get_option', array(
			'times'  => 1,
			'args'   => array( 'masteriyo_price_num_decimals', 2 ),
			'return' => 10
		));
		$this->assertEquals( $this->format->get_price_decimals(), 10 );

		// Test when the filter is used.
		\WP_Mock::userFunction( 'get_option', array(
			'times'  => 1,
			'args'   => array( 'masteriyo_price_num_decimals', 2 ),
			'return' => 15
		));
		\WP_Mock::userFunction( 'apply_filters', array(
			'times'  => 1,
			'args'   => array( 'masteriyo_get_price_decimals', 15 ),
			'return' => -21
		));
		$this->assertEquals( $this->format->get_price_decimals(), 21 );
	}

	/**
	 * Test get_price_format.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function test_get_price_format() {
		// Test default.
		\WP_Mock::userFunction( 'get_option', array(
			'times'  => 1,
			'args'   => 'masteriyo_currency_pos',
			'return' => ''
		));
		$this->assertEquals( $this->format->get_price_format(), '%1$s%2$s' );

		// Test when currency position is left.
		\WP_Mock::userFunction( 'get_option', array(
			'times'  => 1,
			'args'   => 'masteriyo_currency_pos',
			'return' => 'left'
		));
		$this->assertEquals( $this->format->get_price_format(), '%1$s%2$s' );

		// Test when currency position is right.
		\WP_Mock::userFunction( 'get_option', array(
			'times'  => 1,
			'args'   => 'masteriyo_currency_pos',
			'return' => 'right'
		));
		$this->assertEquals( $this->format->get_price_format(), '%2$s%1$s' );

		// Test when currency position is left space.
		\WP_Mock::userFunction( 'get_option', array(
			'times'  => 1,
			'args'   => 'masteriyo_currency_pos',
			'return' => 'left_space'
		));
		$this->assertEquals( $this->format->get_price_format(), '%1$s&nbsp;%2$s' );

		// Test when currency position is right space.
		\WP_Mock::userFunction( 'get_option', array(
			'times'  => 1,
			'args'   => 'masteriyo_currency_pos',
			'return' => 'right_space'
		));
		$this->assertEquals( $this->format->get_price_format(), '%2$s&nbsp;%1$s' );

		// Test apply_filters.
		\WP_Mock::userFunction( 'get_option', array(
			'times'  => 1,
			'args'   => 'masteriyo_currency_pos',
			'return' => ''
		));
		\WP_Mock::userFunction( 'apply_filters', array(
			'times'  => 1,
			'args'   => array( 'masteriyo_price_format', '%1$s%2$s', '' ),
			'return' => 'test'
		));
		$this->assertEquals( $this->format->get_price_format(), 'test' );
	}

	/**
	 * Test get_rounding_precision.
	 *
	 * @since 0.1.0
	 */
	public function test_get_rounding_precision() {
		$format = $this->getMockBuilder( Format::class )
		->setMethods( ['get_price_decimals'] )->getMock();

		// Test when price decimals is 2 and rounding precision is not set.
		$format->method( 'get_price_decimals')->willReturn(3);
		$this->assertEquals( $format->get_rounding_precision(), 5 );

		// Test when price decimals is 2 and rounding precision is not set.
		Constants::set( 'MASTERIYO_ROUNDING_PRECISION', -12 );
		$this->assertEquals( $format->get_rounding_precision(), 12 );
	}
}
