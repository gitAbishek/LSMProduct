<?php
/**
 * Single choice question model.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Models
 */

namespace Masteriyo\Models\Question;

use Masteriyo\Models\Question\Question;

defined( 'ABSPATH' ) || exit;

/**
 * Single choice question model.
 *
 * @since 1.0.0
 */
class SingleChoice extends Question implements QuestionInterface {
	/**
	 * Question type.
	 *
	 * @since 1.0.0
	 *
	 * @var string $type Question type.
	 */
	protected $type = 'single-choice';

	/**
	 * Check whether the chosen answer is correct or not.
	 *
	 * @since 1.0.0
	 *
	 * @param string $chosen_answer Answer chosen by user.
	 * @param string $context Options: 'edit', 'view'.
	 *
	 * @return bool
	 */
	public function check_answer( $chosen_answer, $context = 'edit' ) {
		$answers       = $this->get_answers( 'edit' );
		$chosen_answer = (array) $chosen_answer;

		$correct_answers = array_filter(
			$answers,
			function( $answer ) {
				return isset( $answer->correct ) && $answer->correct;
			}
		);

		$correct_answers = array_column( $correct_answers, 'name' );

		// There can only be one correct answer for SingleChoice question type.
		$correct_answers = (array) current( $correct_answers );

		$correct = $chosen_answer === $correct_answers;

		return apply_filters( "masteriyo_question_check_answer_{$this->type}", $correct, $context, $this );
	}
}
