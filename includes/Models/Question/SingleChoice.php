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
class SingleChoice extends Question {
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
		$answers = $this->get_answers( 'edit' );

		return isset( $answers[ $chosen_answer ] ) && ! ! $answers[ $chosen_answer ];
	}
}
