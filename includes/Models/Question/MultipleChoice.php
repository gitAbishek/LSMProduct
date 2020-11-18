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
class MultipleChoice extends Question {
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

		foreach ( $answers as $answer => $is_correct ) {
			if ( $is_correct && ! in_array( $answer, $chosen_answers ) ) {
				return false;
			}
		}

		return true;
	}
}
