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
	<div class="mto-flex-none mto-hidden md:mto-flex mto-w-full md:mto-w-64 md:mto-h-screen mto-sticky mto-top-0">
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
