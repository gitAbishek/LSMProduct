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
<div class="masteriyo-main">
	<div class="menu-open masteriyo-hidden">
		<svg class="masteriyo-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
			<path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"/>
		</svg>
		<span><?php esc_html_e( 'Menu', 'masteriyo' ); ?></span>
	</div>

	<div id="vertical-menu" class="masteriyo-vertical-menu">
		<?php
		do_action( 'masteriyo_account_sidebar_content' );
		?>
	</div>

	<main class="masteriyo-dashboard">
		<?php
		do_action( 'masteriyo_account_main_content' );
		?>
	</main>
</div>

<?php

/**
 * masteriyo_after_account_content hook.
 */
do_action( 'masteriyo_after_account_content' );
