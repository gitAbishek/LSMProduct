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

<div class="mto-sidebar-wrapper">
	<div class="mto-profile">
		<img class="mto-profile--img" src="<?php echo esc_attr( $user->get_avatar_url() ); ?>" alt="" />
		<div class="mto-profile--details">
			<div class="mto-profile--name">
				<a
					id="label-username"
					href="<?php echo esc_url( masteriyo_get_account_endpoint_url( 'view-myaccount' ) ); ?>"
				>
					<?php echo esc_attr( $user->get_display_name() ); ?>
				</a>
				<a href="<?php echo esc_url( masteriyo_get_account_endpoint_url( 'edit-account' ) ); ?>">
					<?php masteriyo_get_svg( 'edit', true ); ?>
				</a>
			</div>
		</div>
	</div>
	<nav class="mto-menu">
	<ul>
			<?php foreach ( $menu_items as $slug => $endpoint ) : ?>
				<li class="<?php masteriyo_echo_if( $slug === $current_endpoint['endpoint'], 'active' ); ?>">
					<a href="<?php echo esc_url( masteriyo_get_account_endpoint_url( $slug ) ); ?>">
						<?php echo wp_kses_post( $endpoint['icon'] ); ?>
						<span><?php echo esc_html( $endpoint['label'] ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>
</div>

<?php

/**
 * masteriyo_after_myaccount_page_sidebar_content hook.
 */
do_action( 'masteriyo_after_myaccount_page_sidebar_content' );
