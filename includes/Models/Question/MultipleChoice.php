<?php
/**
 * Multiple choice question model.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Models
 */

namespace ThemeGrill\Masteriyo\Models\Question;

use ThemeGrill\Masteriyo\Models\Question\Question;

defined( 'ABSPATH' ) || exit;

/**
 * Multiple choice question model.
 *
 * @since 0.1.0
 */
class MultipleChoice extends Question implements QuestionInterface {
	/**
	 * Question type.
	 *
	 * @since 0.1.0
	 *
	 * @var string $type Question type.
	 */
	protected $type = 'multiple-choice';

	/**
	 * Check whether the chosen answer is correct or not.
	 *
	 * @param array  $chosen_answers Answer chosen by user.
	 * @param string $context Options: 'edit', 'view'.
	 *
	 * @return bool
	 */
	public function check_answer( $chosen_answers, $context = 'edit' ) {
		$answers        = $this->get_answers( 'edit' );
		$chosen_answers = (array) $chosen_answers;

		// Return true if there are no answers stored.
		if ( empty( $answers ) ) {
			return true;
		}

		// Bail early if the chosen answer is empty.
		if ( empty( $chosen_answers ) ) {
			return false;
		}

		// Convert the answers to map.
		$answers_map = array();
		foreach ( $answers as $answer ) {
			$answers_map[ $answer->name ] = $answer->correct;
		}

		$correct = true;
		foreach ( $chosen_answers as $chosen_answer ) {
			if ( isset( $answers_map[ $chosen_answer ] ) ) {
				$correct = $correct && $answers_map[ $chosen_answer ];
			} else {
				$correct = false;
			}
		}

		return $correct;
	}
}
