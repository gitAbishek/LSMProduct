<?php
/**
 * True/False question model.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Models
 */

namespace Masteriyo\Models\Question;

use Masteriyo\Models\Question\Question;

defined( 'ABSPATH' ) || exit;

/**
 * True/False question model.
 *
 * @since 1.0.0
 */
class TrueFalse extends Question implements QuestionInterface {
	/**
	 * Question type.
	 *
	 * @since 1.0.0
	 *
	 * @var string $type Question type.
	 */
	protected $type = 'true-false';

	/**
	 * Check whether the chosen answer is correct or not.
	 *
	 * @param array  $chosen_answer Answer chosen by user.
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
				return $answer->correct;
			}
		);

		$correct_answers = array_values(
			array_map(
				function( $correct_answer ) {
					return $correct_answer->name;
				},
				$correct_answers
			)
		);

		// There can only be one corrent answer for TrueFalse question.
		$correct_answers = (array) current( $correct_answers );

		$correct = $chosen_answer === $correct_answers;

		return apply_filters( "masteriyo_question_check_answer_{$this->type}", $correct, $chosen_answer, $context, $this );
	}
}
