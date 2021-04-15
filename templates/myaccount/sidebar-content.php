<?php
/**
 * The template for sidebar in myaccount.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * masteriyo_before_myaccount_page_sidebar_content hook.
 */
do_action( 'masteriyo_before_myaccount_page_sidebar_content' );

?>

<div class="menu-close mto-absolute mto-top-4 mto-right-4 mto-cursor-pointer md:mto-hidden">
	<svg class="mto-fill-current mto-text-gray-800 mto-w-8 mto-h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
		<path d="M16.192 6.344l-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"/>
	</svg>
</div>
<div class="md:mto-w-full mto-p-8 mto-pr-0 md:mto-border-r">
	<div class="mto-flex mto-w-full mto-items-center mto-space-x-4 mto-mb-10">
		<img class="mto-inline-block mto-h-10 mto-w-10 mto-rounded-full mto-ring-2 mto-ring-white mto-shadow-lg" src="<?php echo esc_attr( $user->get_avatar_url() ); ?>" alt="" />
		<div>
			<h2 class="mto-flex mto-font-semibold mto-text-sm mto-space-x-2 mto-mb-0">
				<a
					id="label-username"
					href="<?php echo esc_url( masteriyo_get_account_endpoint_url( 'view-myaccount' ) ); ?>"
				>
					<?php echo esc_attr( $user->get_display_name() ); ?>
				</a>
				<a href="<?php echo esc_url( masteriyo_get_account_endpoint_url( 'edit-myaccount' ) ); ?>">
					<?php masteriyo_get_svg( 'edit', true ); ?>
				</a>
			</h2>

			<span class="mto-text-xs mto-text-pColor">Gold Member</span>
		</div>
	</div>
	<aside class="sidebar-wrapper">
		<ul>
			<?php foreach( $menu_items as $slug => $endpoint ): ?>
				<li>
					<a class="<?php masteriyo_echo_if( $slug === $current_endpoint, 'active' ); ?>" href="<?php echo esc_url( masteriyo_get_account_endpoint_url( $slug ) ); ?>">
						<span class="mto-inline-block">
							<?php echo $endpoint['icon']; ?>
						</span>
						<div class="mto-inline-block"><?php echo esc_html( $endpoint['label'] ); ?></div>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</aside>
</div>

<?php

/**
 * masteriyo_after_myaccount_page_sidebar_content hook.
 */
do_action( 'masteriyo_after_myaccount_page_sidebar_content' );
