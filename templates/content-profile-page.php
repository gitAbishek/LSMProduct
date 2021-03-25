<?php
/**
 * The template for displaying user profile.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

$templates = array(
	'edit-profile'     => 'profile/tab-content-edit-profile.php',
	'dashboard'        => 'profile/tab-content-dashboard.php',
	'my-courses'       => 'profile/tab-content-my-courses.php',
	'my-grades'        => 'profile/tab-content-my-grades.php',
	'my-memberships'   => 'profile/tab-content-my-memberships.php',
	'my-certificates'  => 'profile/tab-content-my-certificates.php',
	'my-order-history' => 'profile/tab-content-my-order-history.php',
);
$tab = masteriyo_get_current_profile_page_tab();
$template = isset( $templates[ $tab ] ) ? $templates[ $tab ] : 'profile/tab-content-edit-profile.php';

/**
 * masteriyo_before_profile_page_content hook.
 */
do_action( 'masteriyo_before_profile_page_content' );

?>
<div class="mto-flex mto-flex-col md:mto-flex-row">
	<div class="menu-open md:mto-hidden mto-flex mto-items-center mto-cursor-pointer">
		<svg class="mto-fill-current mto-text-gray-800 mto-w-8 mto-h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
			<path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"/>
		</svg>
		<span>Menu</span>
	</div>
	<div id="slide-menu" class="mto-flex-none mto-fixed mto-inset-0 mto--ml-96 md:mto-ml-0 mto-h-screen mto-w-4/5 mto-shadow-2xl md:mto-shadow-none mto-bg-white mto-z-50 md:mto-flex md:mto-w-64 md:mto-sticky md:mto-top-0">
		<div class="menu-close mto-absolute mto-top-4 mto-right-4 mto-cursor-pointer md:mto-hidden">
			<svg class="mto-fill-current mto-text-gray-800 mto-w-8 mto-h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
			<path d="M16.192 6.344l-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"/>
			</svg>
		</div>
		<?php masteriyo_get_template( 'profile/sidebar-content.php' ); ?>
	</div>
	<main class="mto-flex-auto mto-w-full md:mto-flex-1 md:mto-w-4/5 mto-p-4 md:mto-p-16 mto-space-y-6 md:mto-space-y-12">
		<?php masteriyo_get_template( $template ); ?>
	</main>
</div>

<?php

/**
 * masteriyo_after_profile_page_content hook.
 */
do_action( 'masteriyo_after_profile_page_content' );
