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

?>
<div class="mto-flex mto-flex-col md:mto-flex-row">
	<div class="menu-open md:mto-hidden mto-flex mto-items-center mto-cursor-pointer">
		<svg class="mto-fill-current mto-text-gray-800 mto-w-8 mto-h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
			<path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"/>
		</svg>
		<span>Menu</span>
	</div>
	<div id="vertical-menu" class="mto-flex-none mto-fixed mto-inset-0 mto--ml-9999 md:mto-ml-0 mto-h-screen mto-w-4/5 mto-shadow-2xl md:mto-shadow-none mto-bg-white mto-z-50 md:mto-flex md:mto-w-64 md:mto-sticky md:mto-top-0">
		<?php
		do_action( 'masteriyo_myaccount_sidebar_content' );
		?>
	</div>
	<main class="mto-flex-auto mto-w-full md:mto-flex-1 md:mto-w-4/5 mto-p-4 md:mto-p-16 mto-space-y-6 md:mto-space-y-12">
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
