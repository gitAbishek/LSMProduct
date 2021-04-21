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

<div class="myaccount mto-flex mto-space-x-16">
  <div class="myaccount-img">
		<img class="mto-inline-block mto-h-40 mto-w-40 mto-rounded-full mto-ring-2 mto-ring-white mto-shadow-lg" src="<?php echo esc_attr( $user->get_avatar_url() ); ?>" alt="" />
  </div>
  <div class="myaccount-content">
    <div class="account-content-wrap">
		<h2 class="account-content---name mto-mb-0"><?php echo esc_html( $user->get_display_name() ); ?></h2>
		<span class="account-holder--membership mto-text-xs mto-text-pColor"><?php echo esc_html__( 'Gold Member', 'masteriyo' ); ?></span>
    </div>
    <div class="account-holder-detail-more-info mto-mt-10">
      <div class="account-holder-detail-more-info-table mto-flex">
        <ul class="account-col-1">
          <li><strong><?php echo esc_html__( 'Email', 'masteriyo' ); ?></strong></li>
          <li><strong><?php echo esc_html__( 'First Name', 'masteriyo' ); ?></strong></li>
          <li><strong><?php echo esc_html__( 'Last Name', 'masteriyo' ); ?></strong></li>
          <li><strong><?php echo esc_html__( 'Address', 'masteriyo' ); ?></strong></li>
          <li><strong><?php echo esc_html__( 'City', 'masteriyo' ); ?></strong></li>
          <li><strong><?php echo esc_html__( 'State', 'masteriyo' ); ?></strong></li>
          <li><strong><?php echo esc_html__( 'Zip Code', 'masteriyo' ); ?></strong></li>
          <li><strong><?php echo esc_html__( 'Country', 'masteriyo' ); ?></strong></li>
        </ul>
        <ul class="account-col-2">
          <li><?php echo esc_html( $user->get_user_email() ); ?></li>
          <li><?php echo esc_html( $user->get_first_name() ); ?></li>
          <li><?php echo esc_html( $user->get_last_name() ); ?></li>
          <li><?php echo esc_html( $user->get_address() ); ?></li>
          <li><?php echo esc_html( $user->get_city() ); ?></li>
          <li><?php echo esc_html( $user->get_state() ); ?></li>
          <li><?php echo esc_html( $user->get_zip_code() ); ?></li>
          <li><?php echo esc_html( $user->get_country() ); ?></li>
      </div>
    </div>
  </div>
</div>

<?php

/**
 * masteriyo_after_view_myaccount_content hook.
 */
do_action( 'masteriyo_after_view_myaccount_content' );
