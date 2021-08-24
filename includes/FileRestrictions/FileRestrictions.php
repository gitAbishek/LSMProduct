<?php
/**
 * FileRestrictions class.
 *
 * @since 0.1.0
 */

namespace Masteriyo\FileRestrictions;

class FileRestrictions {
	/**
	 * Initialize file restriction handlers.
	 *
	 * @since 0.1.0
	 */
	public static function init() {
		LessonVideoRestriction::init();
	}
}
