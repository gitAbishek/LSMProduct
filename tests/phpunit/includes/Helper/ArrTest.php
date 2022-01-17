<?php
/**
 * Class ArrTest
 *
 * @package Masteriyo
 */

/**
 * Arr helper file test.
 */
class ArrTest extends WP_UnitTestCase {

	/**
	 * A single example test.
	 */
	public function test_masteriyo_array_is_list() {
		$list = array( 'John Doe', '18');
		$this->assertTrue( masteriyo_array_is_list( $list, 'Array should list.' ) );

		$assoc = array( 'name' => 'John Doe', 'age' => 18 );
		$this->assertFalse( masteriyo_array_is_list( $assoc, 'Array should not be associative.'));
	}
}
