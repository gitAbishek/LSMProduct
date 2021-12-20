<?php
/**
 * The template for displaying account.
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * masteriyo_before_account_content hook.
 */
do_action( 'masteriyo_before_account_content' );

masteriyo_display_all_notices();

?>
<div id="masteriyo-account-page">

</div>

<?php

/**
 * masteriyo_after_account_content hook.
 */
do_action( 'masteriyo_after_account_content' );
