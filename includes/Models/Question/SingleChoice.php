<?php
/**
 * Single choice question model.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Models
 */

namespace ThemeGrill\Masteriyo\Models\Question;

use ThemeGrill\Masteriyo\Models\Question\Question;

defined( 'ABSPATH' ) || exit;

/**
 * Single choice question model.
 *
 * @since 0.1.0
 */
class SingleChoice extends Question implements QuestionInterface {
	/**
	 * Question type.
	 *
	 * @since 0.1.0
	 *
	 * @var string $type Question type.
	 */
	protected $type = 'single-choice';

	/**
	 * Check whether the chosen answer is correct or not.
	 *
	 * @param string $chosen_answer Answer chosen by user.
	 * @param string $context Options: 'edit', 'view'.
	 *
	 * @return bool
	 */
	public function check_answer( $chosen_answer, $context = 'edit' ) {
		// Return true if there are no answers stored.
		if ( empty( $answers ) ) {
			return true;
		}

		$chosen_answer = is_array( $chosen_answer ) ? $chosen_answer[0] : $chosen_answer;

		// Bail early if the chosen answer is empty.
		if ( empty( trim( $chosen_answer ) ) ) {
			return false;
		}

		foreach ( $answers as $answer ) {
			if ( $answer->name === $chosen_answer && $answer->correct ) {
				return true;
			}
		}

		return false;
	}
}
