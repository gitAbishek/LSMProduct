<?php
/**
 * Masteriyo account page template.
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * masteriyo_before_account hook.
 */
do_action( 'masteriyo_before_account' );

masteriyo_get_template_part( 'content', 'account' );

/**
 * masteriyo_after_account hook.
 */
do_action( 'masteriyo_after_account' );

