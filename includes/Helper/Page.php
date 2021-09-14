<?php
/**
 * Utility functions.
 *
 * @since 0.1.0
 */

/**
 * Check if the current page is a single course page.
 *
 * @since 0.1.0
 *
 * @return boolean
 */
function masteriyo_is_archive_course_page() {
	return is_post_type_archive( 'mto-course' );
}
