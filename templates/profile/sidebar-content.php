<?php
/**
 * The template for displaying user profile.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

global $user;

$tab = masteriyo_get_current_myaccount_tab();

/**
 * masteriyo_before_profile_page_sidebar_content hook.
 */
do_action( 'masteriyo_before_profile_page_sidebar_content' );

?>

<div class="md:mto-w-full mto-p-8 mto-pr-0 md:mto-border-r">
	<div class="mto-flex mto-w-full mto-items-end mto-space-x-4 mto-mb-10">
		<img class="mto-inline-block mto-h-10 mto-w-10 mto-rounded-full mto-ring-2 mto-ring-white mto-shadow-lg" src="<?php echo $user->get_avatar_url(); ?>" alt="" />
		<div>
			<h2 class="mto-font-semibold mto-text-sm">
				<?php echo $user->get_display_name(); ?>
				<a href="<?php echo esc_url( masteriyo_get_account_endpoint_url( 'edit-profile' ) ); ?>">
					<svg class="mto-fill-current mto-w-6 mto-h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
						<path d="M19.045 7.401c.378-.378.586-.88.586-1.414s-.208-1.036-.586-1.414l-1.586-1.586c-.378-.378-.88-.586-1.414-.586s-1.036.208-1.413.585L4 13.585V18h4.413L19.045 7.401zm-3-3l1.587 1.585-1.59 1.584-1.586-1.585 1.589-1.584zM6 16v-1.585l7.04-7.018 1.586 1.586L7.587 16H6zm-2 4h16v2H4z"/>
					</svg>
				</a>
			</h2>
			<span class="mto-text-xs mto-text-gray-600">Gold Member</span>
		</div>
	</div>
	<aside class="sidebar-wrapper">
		<ul>
			<?php foreach( masteriyo_get_account_menu_items() as $slug => $endpoint ): ?>
				<li>
					<a class="<?php masteriyo_echo_if( $slug === $tab, 'active' ); ?>" href="<?php echo esc_url( masteriyo_get_account_endpoint_url( $slug ) ); ?>">
						<span class="mto-inline-block">
							<?php echo $endpoint['icon']; ?>
						</span>
						<div class="mto-inline-block"><?php echo $endpoint['label']; ?></div>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</aside>
</div>

<?php

/**
 * masteriyo_after_profile_page_sidebar_content hook.
 */
do_action( 'masteriyo_after_profile_page_sidebar_content' );
