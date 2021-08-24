<?php
/**
 * Short answer question model.
 *
 * @since 0.1.0
 *
 * @package Masteriyo\Models
 */

namespace Masteriyo\Models\Question;

use Masteriyo\Models\Question\Question;

defined( 'ABSPATH' ) || exit;

/**
 * Short answer question model.
 *
 * @since 0.1.0
 */
class ShortAnswer extends Question {
	/**
	 * Question type.
	 *
	 * @since 0.1.0
	 *
	 * @var string $type Question type.
	 */
	protected $type = 'short-answer';

		/**
	 * Check whether the short answert is correct or not.
	 *
	 * @param array  $chosen_answer Answer chosen by user.
	 * @param string $context Options: 'edit', 'view'.
	 *
	 * @return bool
	 */
	public function check_answer( $chosen_answer, $context = 'edit' ) {
		return true;
	}

}
