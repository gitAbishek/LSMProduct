<?php
/**
 * The template for displaying myaccount.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * masteriyo_before_myaccount_content hook.
 */
do_action( 'masteriyo_before_myaccount_content' );

masteriyo_display_all_notices();

?>
<div class="mto-main">
	<div class="menu-open masteriyo-hidden">
		<svg class="mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
			<path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"/>
		</svg>
		<span><?php esc_html_e( 'Menu', 'masteriyo' ); ?></span>
	</div>

	<div id="vertical-menu" class="mto-vertical-menu">
		<?php
		do_action( 'masteriyo_myaccount_sidebar_content' );
		?>
	</div>

	<main class="mto-dashboard">
		<?php
		do_action( 'masteriyo_myaccount_main_content' );
		?>
	</main>
</div>

<?php

/**
 * masteriyo_after_myaccount_content hook.
 */
do_action( 'masteriyo_after_myaccount_content' );
