<?php
/**
 * Class ArrTest
 *
 * @package Masteriyo
 */

/**
 * Sample test case.
 */
class ArrTest extends WP_UnitTestCase {

	/**
	 * A single example test.
	 */
	public function test_masteriyo_array_is_list() {
		$this->assertTrue( masteriyo_array_is_list( array( 'John Doe', '18') ) );
	}
}
