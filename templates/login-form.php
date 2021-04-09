<?php
/**
 * Masteriyo login form template.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * masteriyo_before_login_form hook.
 */
do_action( 'masteriyo_before_login_form' );

masteriyo_get_template_part( 'content', 'login-form' );

/**
 * masteriyo_after_login_form hook.
 */
do_action( 'masteriyo_after_login_form' );

