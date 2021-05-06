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

<div class="mto-myaccount mto-flex">
  <div class="mto-myaccount--imgwrap">
		<img class="mto-myaccount-img" src="<?php echo esc_attr( $user->get_avatar_url() ); ?>" alt="" />
  </div>

  <div class="mto-myaccount--detail">
    <div class="mto-myaccount mto-myaccount--header">
	  	<h2 class="mto-myaccount--name"><?php echo esc_html( $user->get_display_name() ); ?></h2>

  	  <span class="mto-myaccount--membership"><?php echo esc_html__( 'Gold Member', 'masteriyo' ); ?></span>
    </div>

    <div class="mto-myaccount--body mto-flex">
        <ul class="mto-title account-col-1">
          <li><strong><?php echo esc_html__( 'Email', 'masteriyo' ); ?></strong></li>
          <li><strong><?php echo esc_html__( 'First Name', 'masteriyo' ); ?></strong></li>
          <li><strong><?php echo esc_html__( 'Last Name', 'masteriyo' ); ?></strong></li>
          <li><strong><?php echo esc_html__( 'Address', 'masteriyo' ); ?></strong></li>
          <li><strong><?php echo esc_html__( 'City', 'masteriyo' ); ?></strong></li>
          <li><strong><?php echo esc_html__( 'State', 'masteriyo' ); ?></strong></li>
          <li><strong><?php echo esc_html__( 'Zip Code', 'masteriyo' ); ?></strong></li>
          <li><strong><?php echo esc_html__( 'Country', 'masteriyo' ); ?></strong></li>
        </ul>

        <ul class="mto-content account-col-2">
          <li><?php echo esc_html( $user->get_email() ); ?></li>
          <li><?php echo esc_html( $user->get_first_name() ); ?></li>
          <li><?php echo esc_html( $user->get_last_name() ); ?></li>
          <li><?php echo esc_html( $user->get_billing_address() ); ?></li>
          <li><?php echo esc_html( $user->get_billing_city() ); ?></li>
          <li><?php echo esc_html( $user->get_billing_state() ); ?></li>
          <li><?php echo esc_html( $user->get_billing_postcode() ); ?></li>
          <li><?php echo esc_html( $user->get_billing_country() ); ?></li>
      </div>
    </div>
</div>

<?php

/**
 * masteriyo_after_view_myaccount_content hook.
 */
do_action( 'masteriyo_after_view_myaccount_content' );
