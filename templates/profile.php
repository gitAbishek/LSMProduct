<?php
/**
 * Masteriyo profile page template.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * masteriyo_before_profile hook.
 */
do_action( 'masteriyo_before_profile' );

if ( is_user_logged_in() ) {
	masteriyo_get_template_part( 'content', 'profile-page' );
}

/**
 * masteriyo_after_profile hook.
 */
do_action( 'masteriyo_after_profile' );

