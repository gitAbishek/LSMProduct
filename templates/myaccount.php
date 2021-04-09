<?php
/**
 * Masteriyo myaccount page template.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * masteriyo_before_myaccount hook.
 */
do_action( 'masteriyo_before_myaccount' );

masteriyo_get_template_part( 'content', 'myaccount' );

/**
 * masteriyo_after_myaccount hook.
 */
do_action( 'masteriyo_after_myaccount' );

