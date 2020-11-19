<?php
/**
 * Question factory.
 *
 * @since 0.1.0
 */

namespace ThemeGrill\Masteriyo\Models\Question;

defined( 'ABSPATH' ) || exit;

use ThemeGrill\Masteriyo\Models\Question\TrueFalse;

/**
 * Question factory class.
 *
 * @since 0.1.0
 */
class QuestionFactory {
	public static function create( $type ) {
		global $masteriyo_container;

		if ( 'true-false' === $type ) {
			return $masteriyo_container->get( 'true-false' );
		}
	}
}
