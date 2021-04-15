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
			  <h2 class="account-content---name mto-mb-0"><?php echo esc_attr( $user->get_display_name() ); ?></h2>
  			<span class="account-holder--membership mto-text-xs mto-text-pColor">Gold Member</span>
    </div>
    <div class="account-holder-detail-more-info mto-mt-10">
      <div class="account-holder-detail-more-info-table mto-flex">
        <ul class="account-col-1">
          <li><strong>Email</strong></li>
          <li><strong>Contact Number</strong></li>
          <li><strong>Address</strong></li>
          <li><strong>City</strong></li>
          <li><strong>State</strong></li>
          <li><strong>Zip Code</strong></li>
          <li><strong>Country</strong></li>
        </ul>
        <ul class="account-col-2">
          <li><?php echo esc_attr( $user->get_user_email() ); ?></li>
          <li>+8 123-489-1236</li>
          <li>123 Moon Street, Mars</li>
          <li>Nuwa</li>
          <li>Abiboo</li>
          <li>8899</li>
          <li>Sinara</li>
      </div>
    </div>
  </div>
</div>

<?php

/**
 * masteriyo_after_view_myaccount_content hook.
 */
do_action( 'masteriyo_after_view_myaccount_content' );
