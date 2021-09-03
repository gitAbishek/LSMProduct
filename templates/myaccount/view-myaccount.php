<?php
/**
 * View myaccount template.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * masteriyo_before_view_myaccount_content hook.
 */
do_action( 'masteriyo_before_view_myaccount_content' );

?>

<div class="masteriyo-myaccount masteriyo-flex">
	<div class="masteriyo-myaccount--imgwrap">
		<img class="masteriyo-myaccount-img" src="<?php echo esc_attr( $user->get_avatar_url() ); ?>" alt="" />
	</div>

	<div class="masteriyo-myaccount--detail">
		<div class="masteriyo-myaccount masteriyo-myaccount--header">
			<h2 class="masteriyo-myaccount--name"><?php echo esc_html( $user->get_display_name() ); ?></h2>
		</div>
	</div>

	<div class="masteriyo-myaccount--body masteriyo-flex">
		<ul class="masteriyo-title account-col-1">
			<li><strong><?php echo esc_html__( 'Email', 'masteriyo' ); ?></strong></li>
			<li><strong><?php echo esc_html__( 'First Name', 'masteriyo' ); ?></strong></li>
			<li><strong><?php echo esc_html__( 'Last Name', 'masteriyo' ); ?></strong></li>
			<li><strong><?php echo esc_html__( 'Address', 'masteriyo' ); ?></strong></li>
			<li><strong><?php echo esc_html__( 'City', 'masteriyo' ); ?></strong></li>
			<li><strong><?php echo esc_html__( 'State', 'masteriyo' ); ?></strong></li>
			<li><strong><?php echo esc_html__( 'Zip Code', 'masteriyo' ); ?></strong></li>
			<li><strong><?php echo esc_html__( 'Country', 'masteriyo' ); ?></strong></li>
		</ul>

		<ul class="masteriyo-content account-col-2">
			<li><?php echo esc_html( $user->get_email() ); ?></li>
			<li><?php echo esc_html( $user->get_first_name() ); ?></li>
			<li><?php echo esc_html( $user->get_last_name() ); ?></li>
			<li><?php echo esc_html( $user->get_billing_address() ); ?></li>
			<li><?php echo esc_html( $user->get_billing_city() ); ?></li>
			<li><?php echo esc_html( $user->get_billing_state() ); ?></li>
			<li><?php echo esc_html( $user->get_billing_postcode() ); ?></li>
			<li><?php echo esc_html( masteriyo( 'countries' )->get_country_from_code( $user->get_billing_country() ) ); ?></li>
		</ul>
	</div>
</div>

<?php

/**
 * masteriyo_after_view_myaccount_content hook.
 */
do_action( 'masteriyo_after_view_myaccount_content' );
