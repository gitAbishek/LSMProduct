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

<div class="masteriyo-sidebar-wrapper">
	<div class="masteriyo-profile">
		<img class="masteriyo-profile--img" src="<?php echo esc_attr( $user->get_avatar_url() ); ?>" alt="" />
		<div class="masteriyo-profile--details">
			<div class="masteriyo-profile--name">
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
	<nav class="masteriyo-menu">
	<ul>
			<?php foreach ( $menu_items as $slug => $endpoint ) : ?>
				<li class="<?php echo esc_attr( $slug === $current_endpoint['endpoint'] ? 'active' : '' ); ?>">
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
