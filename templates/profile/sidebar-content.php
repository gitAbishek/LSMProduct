<?php
/**
 * The template for displaying user profile.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

global $user;

$tab = masteriyo_get_current_profile_page_tab();

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
			</h2>
			<span class="mto-text-xs mto-text-gray-600">Gold Member</span>
		</div>
	</div>
	<aside class="sidebar-wrapper">
		<ul>
			<li>
				<a class="<?php echo 'dashboard' === $tab ? 'active' : ''; ?>" href="?tab=dashboard">
					<span class="mto-inline-block">
						<svg class="mto-fill-current mto-w-6 mto-h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
							<path d="M9 21h12V3H3v18h6zm10-4v2h-6v-6h6v4zM15 5h4v6h-6V5h2zM5 7V5h6v6H5V7zm0 12v-6h6v6H5z"/>
						</svg>
					</span>
					<div class="mto-inline-block">Dashboard</div>
				</a>
			</li>
			<li>
				<a class="<?php echo 'my-courses' === $tab ? 'active' : ''; ?>"  href="?tab=my-courses">
					<span class="mto-inline-block">
						<svg class="mto-fill-current mto-w-6 mto-h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
							<path d="M6 22h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1H21V4c0-1.103-.897-2-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3zM5 8V5c0-.805.55-.988 1-1h13v12H5V8z"/>
							<path d="M8 6h9v2H8z"/>
						</svg>
					</span>
					<div class="mto-inline-block">My Courses</div>
				</a>
			</li>
			<li>
				<a class="<?php echo 'my-grades' === $tab ? 'active' : ''; ?>" href="?tab=my-grades">
					<span class="mto-inline-block">
						<svg class="mto-fill-current mto-w-6 mto-h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
							<path d="M22.8 7.6l-9.7-3.2c-.7-.2-1.4-.2-2.2 0L1.2 7.6C.5 7.9 0 8.6 0 9.4s.5 1.5 1.2 1.8l.8.3c-.1.2-.2.4-.2.7-.4.2-.7.6-.7 1.2 0 .4.2.8.5 1L.6 19c-.1.4.2.8.6.8h2.1c.4 0 .7-.4.6-.8l-1-4.6c.3-.2.5-.6.5-1s-.2-.8-.5-1c0-.1.1-.3.2-.4l2.1.7-.5 4.6c0 1.4 3.2 2.6 7.2 2.6s7.2-1.2 7.2-2.6l-.5-4.6 4.1-1.4c.7-.2 1.2-1 1.2-1.8.1-.9-.4-1.6-1.1-1.9zm-5.5 9.2c-2.2 1.4-8.5 1.4-10.7 0l.4-3.6 3.8 1.3c.4.1 1.2.3 2.2 0l3.8-1.3.5 3.6zm-4.8-4.2c-.4.1-.7.1-1.1 0l-5.7-1.9L12 9.4c.3-.1.5-.4.5-.8-.1-.4-.4-.6-.7-.5L4.2 9.7c-.2 0-.4.1-.7.2L2 9.4l9.5-3.1c.4-.1.7-.1 1.1 0L22 9.4l-9.5 3.2z"/>
						</svg>
					</span>
					<div class="mto-inline-block">My Grades</div>
				</a>

			</li>
			<li>
				<a class="<?php echo 'my-memberships' === $tab ? 'active' : ''; ?>" href="?tab=my-memberships">
					<span class="mto-inline-block">
						<svg class="mto-fill-current mto-w-6 mto-h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
							<path fill="none" d="M6 8c0 1.178.822 2 2 2s2-.822 2-2-.822-2-2-2-2 .822-2 2z"/>
							<path d="M19 8h-2v3h-3v2h3v3h2v-3h3v-2h-3zM4 8c0 2.28 1.72 4 4 4s4-1.72 4-4-1.72-4-4-4-4 1.72-4 4zm6 0c0 1.178-.822 2-2 2s-2-.822-2-2 .822-2 2-2 2 .822 2 2zM4 18c0-1.654 1.346-3 3-3h2c1.654 0 3 1.346 3 3v1h2v-1c0-2.757-2.243-5-5-5H7c-2.757 0-5 2.243-5 5v1h2v-1z"/>
						</svg>
					</span>
					<div class="mto-inline-block">My Memberships</div>
				</a>
			</li>
			<li>
				<a class="<?php echo 'my-certificates' === $tab ? 'active' : ''; ?>" href="?tab=my-certificates">
					<span class="mto-inline-block">
						<svg class="mto-fill-current mto-w-6 mto-h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
							<path d="M12 22c3.859 0 7-3.141 7-7s-3.141-7-7-7c-3.86 0-7 3.141-7 7s3.14 7 7 7zm0-12c2.757 0 5 2.243 5 5s-2.243 5-5 5-5-2.243-5-5 2.243-5 5-5zm-1-8H7v5.518a8.957 8.957 0 014-1.459V2zm6 0h-4v4.059a8.957 8.957 0 014 1.459V2z"/>
							<path d="M10.019 15.811l-.468 2.726L12 17.25l2.449 1.287-.468-2.726 1.982-1.932-2.738-.398L12 11l-1.225 2.481-2.738.398z"/>
						</svg>
					</span>
					<div class="mto-inline-block">My Certificates</div>
				</a>
			</li>
			<li>
				<a class="<?php echo 'my-order-history' === $tab ? 'active' : ''; ?>" href="?tab=my-order-history">
					<span class="mto-inline-block">
						<svg class="mto-fill-current mto-w-6 mto-h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
							<path d="M12 8v5h5v-2h-3V8z"/>
							<path d="M21.292 8.497a8.957 8.957 0 00-1.928-2.862 9.004 9.004 0 00-4.55-2.452 9.09 9.09 0 00-3.626 0 8.965 8.965 0 00-4.552 2.453 9.048 9.048 0 00-1.928 2.86A8.963 8.963 0 004 12l.001.025H2L5 16l3-3.975H6.001L6 12a6.957 6.957 0 011.195-3.913 7.066 7.066 0 011.891-1.892 7.034 7.034 0 012.503-1.054 7.003 7.003 0 018.269 5.445 7.117 7.117 0 010 2.824 6.936 6.936 0 01-1.054 2.503c-.25.371-.537.72-.854 1.036a7.058 7.058 0 01-2.225 1.501 6.98 6.98 0 01-1.313.408 7.117 7.117 0 01-2.823 0 6.957 6.957 0 01-2.501-1.053 7.066 7.066 0 01-1.037-.855l-1.414 1.414A8.985 8.985 0 0013 21a9.05 9.05 0 003.503-.707 9.009 9.009 0 003.959-3.26A8.968 8.968 0 0022 12a8.928 8.928 0 00-.708-3.503z"/>
						</svg>
					</span>
					<div class="mto-inline-block">My Order History</div>
				</a>
			</li>
		</ul>
	</aside>
</div>

<?php

/**
 * masteriyo_after_profile_page_sidebar_content hook.
 */
do_action( 'masteriyo_after_profile_page_sidebar_content' );
