<?php
/**
 * The template for editing user profile.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * masteriyo_before_edit_myaccount_tab_content hook.
 */
do_action( 'masteriyo_before_edit_myaccount_tab_content' );

?>

<div class="mto-edt-myaccount mto-tabs">
	<?php masteriyo_display_all_notices(); ?>

	<div class="mto-edt-myaccount--tab-menu mto-flex">
		<div data-tab="edit-profile-tab" class="mto-tab mto-active-tab"><?php echo esc_html__( 'Edit Profile', 'masteriyo' ); ?></div>
		<div data-tab="password-security-tab" class="mto-tab"><?php echo esc_html__( 'Password & Security', 'masteriyo' ); ?></div>
	</div>

	<div id="edit-profile-tab" class="mto-edt-myaccount--content mto-tab-content">
		<div class="mto-edt-profile mto-flex mto-flex-xcenter">
			<div class="mto-edt-profile--wrap">
				<img src="<?php echo esc_attr( $user->get_avatar_url() ); ?>" class="mto-edt-myaccount--img" alt="">
			</div>
		</div>

		<form id="mto-edit-profile-form" class="mto-edt-myaccount--form">
				<div class="mto-username">
					<label for="user-email" class="mto-label"><?php echo esc_html__( 'Username', 'masteriyo' ); ?></label>
					<input value="<?php echo esc_attr( $user->get_display_name() ); ?>" id="username" name="text" type="text" required class="mto-input" placeholder="">
				</div>

				<div class="mto-fname-lname mto-col-2 mto-flex">
					<div class="mto-fname">
						<label for="user-first-name" class="mto-label"><?php echo esc_html__( 'First Name', 'masteriyo' ); ?></label>
						<input value="<?php echo esc_attr( $user->get_first_name() ); ?>" id="user-first-name" name="text" type="text" class="mto-input" placeholder="">
					</div>

					<div class="mto-lname">
						<label for="user-last-name" class="mto-label"><?php echo esc_html__( 'Last Name', 'masteriyo' ); ?></label>
						<input value="<?php echo esc_attr( $user->get_last_name() ); ?>" id="user-last-name" name="text" type="text" class="mto-input" placeholder="">
					</div>
				</div>

				<div class="mto-email">
					<label for="user-email" class="mto-label"><?php echo esc_html__( 'Email', 'masteriyo' ); ?></label>
					<input value="<?php echo esc_attr( $user->get_email() ); ?>" id="user-email" name="text" type="email" required class="mto-input" placeholder="">
				</div>

				<div class="mto-address">
					<label for="user-address" class="mto-label"><?php echo esc_html__( 'Address', 'masteriyo' ); ?></label>
					<input value="<?php echo esc_attr( $user->get_billing_address() ); ?>" id="user-address" name="text" type="text" class="mto-input" placeholder="">
				</div>

				<div class="mto-city-state mto-col-2 mto-flex">
					<div class="mto-city">
						<label for="user-city" class="mto-label"><?php echo esc_html__( 'City', 'masteriyo' ); ?></label>
						<input value="<?php echo esc_attr( $user->get_billing_city() ); ?>" id="user-city" name="text" type="text" class="mto-input" placeholder="">
					</div>

					<div class="mto-state">
						<label for="user-state" class="mto-label"><?php echo esc_html__( 'State', 'masteriyo' ); ?></label>
						<input value="<?php echo esc_attr( $user->get_billing_state() ); ?>" id="user-state" name="text" type="text" class="mto-input" placeholder="">
					</div>
				</div>

				<div class="mto-zip-country mto-col-2 mto-flex">
					<div class="mto-zip">
						<label for="user-zip-code" class="mto-label"><?php echo esc_html__( 'Zip Code', 'masteriyo' ); ?></label>
						<input value="<?php echo esc_attr( $user->get_billing_postcode() ); ?>" id="user-zip-code" name="text" type="text" class="mto-input" placeholder="">
					</div>

					<div class="mto-country">
						<label for="user-country" class="mto-label"><?php echo esc_html__( 'Country', 'masteriyo' ); ?></label>
						<input value="<?php echo esc_attr( $user->get_billing_country() ); ?>" id="user-country" name="text" type="text" class="mto-input" placeholder="">
					</div>
				</div>

				<div class="mto-submit-btn">
					<button id="mto-btn-submit-edit-profile-form" type="submit" class="mto-edt-myaccount--btn mto-btn mto-btn-primary">
						<?php echo esc_html__( 'Save', 'masteriyo' ); ?>
					</button>
				</div>
		</form>
	</div>
	<div id="password-security-tab" class="mto-pwd-security mto-tab-content masteriyo-hidden">
			<h3 class="mto-pwd-security--title"><?php echo esc_html__( 'Change Password', 'masteriyo' ); ?></h3>

			<form class="mto-pwd-security--form" method="POST">
				<div class="mto-cr-pwd">
					<label for="current_password" class="mto-label"><?php echo esc_html__( 'Current Password', 'masteriyo' ); ?></label>
					<input id="current_password" name="current_password" type="password" required class="mto-input" placeholder="">
				</div>

				<div class="mto-nw-pwd password_1">
					<label for="password_1" class="mto-label"><?php echo esc_html__( 'New Password', 'masteriyo' ); ?></label>
					<input id="password_1" name="password_1" type="password" required autocomplete="new-password" class="mto-input" placeholder="">
				</div>

				<div class="mto-cf-pwd password_2">
					<label for="password_2" class="mto-label"><?php echo esc_html__( 'Confirm Password', 'masteriyo' ); ?></label>
					<input id="password_2" name="password_2" type="password" required autocomplete="new-password" class="mto-input" placeholder="">
				</div>
				<div class="mto-cpwd-btn">
					<button type="submit" name="masteriyo-change-password" value="yes" class="mto-pwd-security--btn mto-btn mto-btn-primary">
						<?php echo esc_html__( 'Change Password', 'masteriyo' ); ?>
					</button>
				</div>

				<?php wp_nonce_field( 'masteriyo-change-password' ); ?>
			</form>
	</div>
</div>

<?php

/**
 * masteriyo_after_edit_myaccount_tab_content hook.
 */
do_action( 'masteriyo_after_edit_myaccount_tab_content' );
