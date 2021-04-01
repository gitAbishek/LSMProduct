<?php
/**
 * The template for displaying user profile.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

$user = masteriyo_get_current_user_data();
$tab = masteriyo_get_current_myaccount_tab();

/**
 * masteriyo_before_profile_page_sidebar_content hook.
 */
do_action( 'masteriyo_before_profile_page_sidebar_content' );

?>

<div class="md:mto-w-full mto-p-8 mto-pr-0 md:mto-border-r">
	<div class="mto-flex mto-w-full mto-items-center mto-space-x-4 mto-mb-10">
		<img class="mto-inline-block mto-h-10 mto-w-10 mto-rounded-full mto-ring-2 mto-ring-white mto-shadow-lg" src="<?php echo $user->get_avatar_url(); ?>" alt="" />
		<div>
			<h2 class="mto-flex mto-font-semibold mto-text-sm mto-space-x-2">
				<span><?php echo $user->get_display_name(); ?></span>
				<a href="<?php echo esc_url( masteriyo_get_account_endpoint_url( 'edit-profile' ) ); ?>">
					<svg class="mto-fill-current mto-text-gray-400 hover:mto-text-primary mto-w-5 mto-h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path fill="none" d="M16.589 9l-1.586-1.586-9.097 9.097-.529 2.114 2.114-.528zM19.588 6l-1.586 1.586L16.416 6l1.586-1.586z"/>
  				<path d="M4.003 21c.081 0 .162-.01.242-.03l4-1c.176-.044.337-.135.465-.263L21.003 7.414c.378-.378.586-.88.586-1.414s-.208-1.036-.586-1.414L19.417 3c-.756-.756-2.072-.756-2.828 0L4.296 15.293a1.003 1.003 0 00-.263.464l-1 4A1 1 0 004.003 21zm14-16.586L19.589 6l-1.586 1.586L16.417 6l1.586-1.586zM5.906 16.511l9.097-9.097L16.589 9l-9.098 9.097-2.114.528.529-2.114z"/>
					</svg>
				</a>
			</h2>

			<span class="mto-text-xs mto-text-pColor">Gold Member</span>
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
