<?php
/**
 * True/False question model.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Models
 */

namespace ThemeGrill\Masteriyo\Models\Question;

use ThemeGrill\Masteriyo\Models\Question\Question;

defined( 'ABSPATH' ) || exit;

/**
 * True/False question model.
 *
 * @since 0.1.0
 */
class TrueFalse extends Question {
	/**
	 * Question type.
	 *
	 * @since 0.1.0
	 *
	 * @var string $type Question type.
	 */
	protected $type = 'true-false';

	/**
	 * Check whether the chosen answer is correct or not.
	 *
	 * @param bool   $chosen_answer Answer chosen by user.
	 * @param string $context Options: 'edit', 'view'.
	 *
	 * @return bool
	 */
	public function check_answer( $chosen_answer, $context = 'edit' ) {
		$answers = $this->get_answers( 'edit' );

		if ( is_bool( $chosen_answer ) ) {
			$chosen_answer = $chosen_answer ? 'true' : 'false';
		}

		return isset( $answers[ $chosen_answer ] ) && ! ! $answers[ $chosen_answer ];
	}
}
