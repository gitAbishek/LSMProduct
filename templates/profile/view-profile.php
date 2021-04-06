<?php
/**
 * View profile template.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * masteriyo_before_view_profile_content hook.
 */
do_action( 'masteriyo_before_view_profile_content' );

?>

<div>This is where you view your profile</div>

<?php

/**
 * masteriyo_after_view_profile_content hook.
 */
do_action( 'masteriyo_after_view_profile_content' );
