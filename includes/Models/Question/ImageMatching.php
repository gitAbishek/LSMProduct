<?php
/**
 * Image matching question model.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Models
 */

namespace ThemeGrill\Masteriyo\Models\Question;

use ThemeGrill\Masteriyo\Models\Question\Question;

defined( 'ABSPATH' ) || exit;

/**
 * Image matching question model.
 *
 * @since 0.1.0
 */
class ImageMatching extends Question {
	/**
	 * Question type.
	 *
	 * @since 0.1.0
	 *
	 * @var string $type Question type.
	 */
	protected $type = 'image-matching';
}
