<?php
/**
 * Error Notice.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="mto-notify-message mto-alert mto-success-msg">
	<span><?php echo wp_kses_post( $message ); ?></span>
</div>

<?php
