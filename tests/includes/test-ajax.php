<?php
/**
 * Ajax
 *
 * @package EverestLMS
 */

use ThemeGrill\Masteriyo\Ajax;

/**
 * Sample test case.
 */
class AjaxTest extends WP_UnitTestCase {

	public function test_init_hooks() {
		$ajax = new Ajax();
		foreach ( $ajax->actions as $key => $action ) {
			foreach ( $action as $type => $callback ) {
				$type = 'priv' === $type ? '' : '_nopriv';
				$slug = MASTERIYO_SLUG;
				$has_action = has_action( "wp_ajax{$type}_{$slug}_{$key}", $callback );
				$this->assertTrue( is_int( $has_action ) );
			}
		}
	}
}
