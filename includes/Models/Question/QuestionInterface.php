<?php
/**
 * Question interface.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Models\Question
 */

namespace ThemeGrill\Masteriyo\Models\Question;

defined( 'ABSPATH' ) || exit;

/**
 * Question interface.
 *
 * @since 0.1.0
 */
interface QuestionInterface {
	/**
	 * Check whether the chosen answer is correct or not.
	 *
	 * @param mixed  $chosen_answer Answer chosen by user.
	 * @param string $context Options: 'edit', 'view'.
	 */
	public function check_answer( $chosen_answer, $context = 'edit' );
}
