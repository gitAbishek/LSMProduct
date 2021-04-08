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

<div class="mto-tabs mto-px-4 md:mto-px-0">
	<div class="tab-menu mto-flex mto-font-bold mto-text-base mto-border-b-2 mto-border-gray-2 mto-mb-10">
		<div data-tab="edit-profile-tab" class="mto-tab active-tab mto-pt-6 mto-pb-4 mto--mb-0.5 mto-cursor-pointer"><?php echo esc_html__( 'Edit Profile', 'masteriyo' ); ?></div>
		<div data-tab="password-security-tab" class="mto-tab mto-pt-6 mto-pb-4 mto--mb-0.5 mto-ml-12 mto-cursor-pointer"><?php echo esc_html__( 'Password & Security', 'masteriyo' ); ?></div>
	</div>
	<div id="edit-profile-tab" class="tab-content mto-block">
		<div class="edit-profile mto-my-20">
			<div class="mto-flex mto-justify-center">
				<a href="#" aria-label="Change Profile Picture">
					<div class="mto-group mto-w-24 mto-h-24 mto-bg-cover mto-bg-center mto-rounded-full mto-border-4 mto-shadow-md mto-relative mto-overflow-hidden">
						<form action="/action_page.php">
							<label for="img" class="mto-hidden group-hover:mto-block mto-absolute mto-inset-5 mto-w-12 mto-h-12 mto-bg-gray-900 mto-opacity-50 mto-p-3 mto-rounded-full mto-cursor-pointer">
								<svg class="mto-w-6 mto-h-6 mto-fill-current mto-text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12,8c-2.168,0-4,1.832-4,4c0,2.168,1.832,4,4,4s4-1.832,4-4C16,9.832,14.168,8,12,8z M12,14c-1.065,0-2-0.935-2-2 s0.935-2,2-2s2,0.935,2,2S13.065,14,12,14z"></path><path d="M20,5h-2.586l-2.707-2.707C14.52,2.105,14.266,2,14,2h-4C9.734,2,9.48,2.105,9.293,2.293L6.586,5H4C2.897,5,2,5.897,2,7v11 c0,1.103,0.897,2,2,2h16c1.103,0,2-0.897,2-2V7C22,5.897,21.103,5,20,5z M4,18V7h3c0.266,0,0.52-0.105,0.707-0.293L10.414,4h3.172 l2.707,2.707C16.48,6.895,16.734,7,17,7h3l0.002,11H4z"></path></svg>
							</label>
							<input class="mto-hidden" type="file" id="img" name="img" accept="image/*">
						</form>
						<img src="<?php echo esc_attr( $user->get_avatar_url() ); ?>" alt="">
					</div>
				</a>
			</div>
		</div>

		<form id="mto-edit-profile-form">
			<div class="mto-space-y-6 md:mto-space-y-8">
				<div class="mto-flex mto-flex-col md:mto-flex-row mto-space-y-6 md:mto-space-y-0">
					<div class="mto-flex-grow">
						<label for="user-email" class="mto-block mto-text-sm mto-font-semibold mto-mb-2"><?php echo esc_html__( 'Username', 'masteriyo' ); ?></label>
						<input value="<?php echo esc_attr( $user->get_display_name() ); ?>" id="username" name="text" type="text" required class="mto-px-4 mto-rounded mto-block mto-w-full mto-py-2 mto-border mto-border-gray-300 focus:mto-outline-none focus:mto-shadow-outline focus:mto-border-primary" placeholder="">
					</div>
				</div>
				<div class="mto-flex mto-flex-col md:mto-flex-row mto-space-y-6 md:mto-space-y-0 md:mto-space-x-8">
					<div class="mto-flex-grow">
						<label for="user-first-name" class="mto-block mto-text-sm mto-font-semibold mto-mb-2"><?php echo esc_html__( 'First Name', 'masteriyo' ); ?></label>
						<input value="<?php echo esc_attr( $user->get_first_name() ); ?>" id="user-first-name" name="text" type="text" required class="mto-px-4 mto-rounded mto-block mto-w-full mto-py-2 mto-border mto-border-gray-300 focus:mto-outline-none focus:mto-shadow-outline focus:mto-border-primary" placeholder="">
					</div>

					<div class="mto-flex-grow">
						<label for="user-last-name" class="mto-block mto-text-sm mto-font-semibold mto-mb-2"><?php echo esc_html__( 'Last Name', 'masteriyo' ); ?></label>
						<input value="<?php echo esc_attr( $user->get_last_name() ); ?>" id="user-last-name" name="text" type="text" required class="mto-px-4 mto-rounded mto-block mto-w-full mto-py-2 mto-border mto-border-gray-300 focus:mto-outline-none focus:mto-shadow-outline focus:mto-border-primary" placeholder="">
					</div>
				</div>
				<div class="mto-flex mto-flex-col md:mto-flex-row mto-space-y-6 md:mto-space-y-0">
					<div class="mto-flex-grow">
						<label for="user-email" class="mto-block mto-text-sm mto-font-semibold mto-mb-2"><?php echo esc_html__( 'Email', 'masteriyo' ); ?></label>
						<input value="<?php echo esc_attr( $user->get_email() ); ?>" id="user-email" name="text" type="email" required class="mto-px-4 mto-rounded mto-block mto-w-full mto-py-2 mto-border mto-border-gray-300 focus:mto-outline-none focus:mto-shadow-outline focus:mto-border-primary" placeholder="">
					</div>
				</div>
				<div class="mto-flex mto-flex-col md:mto-flex-row mto-space-y-6 md:mto-space-y-0">
					<div class="mto-flex-grow">
						<label for="user-address" class="mto-block mto-text-sm mto-font-semibold mto-mb-2"><?php echo esc_html__( 'Address', 'masteriyo' ); ?></label>
						<input value="<?php echo esc_attr( $user->get_address() ); ?>" id="user-address" name="text" type="text" required class="mto-px-4 mto-rounded mto-block mto-w-full mto-py-2 mto-border mto-border-gray-300 focus:mto-outline-none focus:mto-shadow-outline focus:mto-border-primary" placeholder="">
					</div>
				</div>
				<div class="mto-flex mto-flex-col md:mto-flex-row mto-space-y-6 md:mto-space-y-0 md:mto-space-x-8">
					<div class="mto-flex-grow">
						<label for="user-city" class="mto-block mto-text-sm mto-font-semibold mto-mb-2"><?php echo esc_html__( 'City', 'masteriyo' ); ?></label>
						<input value="<?php echo esc_attr( $user->get_city() ); ?>" id="user-city" name="text" type="text" required class="mto-px-4 mto-rounded mto-block mto-w-full mto-py-2 mto-border mto-border-gray-300 focus:mto-outline-none focus:mto-shadow-outline focus:mto-border-primary" placeholder="">
					</div>

					<div class="mto-flex-grow">
						<label for="user-state" class="mto-block mto-text-sm mto-font-semibold mto-mb-2"><?php echo esc_html__( 'State', 'masteriyo' ); ?></label>
						<input value="<?php echo esc_attr( $user->get_state() ); ?>" id="user-state" name="text" type="text" required class="mto-px-4 mto-rounded mto-block mto-w-full mto-py-2 mto-border mto-border-gray-300 focus:mto-outline-none focus:mto-shadow-outline focus:mto-border-primary" placeholder="">
					</div>
				</div>
				<div class="mto-flex mto-flex-col md:mto-flex-row mto-space-y-6 md:mto-space-y-0 md:mto-space-x-8">
					<div class="mto-flex-grow">
						<label for="user-zip-code" class="mto-block mto-text-sm mto-font-semibold mto-mb-2"><?php echo esc_html__( 'Zip Code', 'masteriyo' ); ?></label>
						<input value="<?php echo esc_attr( $user->get_zip_code() ); ?>" id="user-zip-code" name="text" type="text" required class="mto-px-4 mto-rounded mto-block mto-w-full mto-py-2 mto-border mto-border-gray-300 focus:mto-outline-none focus:mto-shadow-outline focus:mto-border-primary" placeholder="">
					</div>

					<div class="mto-flex-grow">
						<label for="user-country" class="mto-block mto-text-sm mto-font-semibold mto-mb-2"><?php echo esc_html__( 'Country', 'masteriyo' ); ?></label>
						<input value="<?php echo esc_attr( $user->get_country() ); ?>" id="user-country" name="text" type="text" required class="mto-px-4 mto-rounded mto-block mto-w-full mto-py-2 mto-border mto-border-gray-300 focus:mto-outline-none focus:mto-shadow-outline focus:mto-border-primary" placeholder="">
					</div>
				</div>

				<div>
					<button id="mto-btn-submit-edit-profile-form" type="submit" class="btn mto-text-sm mto-py-3 mto-px-6">
						<?php echo esc_html__( 'Save', 'masteriyo' ); ?>
					</button>
				</div>
			</div>
		</form>
	</div>
	<div id="password-security-tab" class="tab-content mto-hidden">
		<div class="password-security mto-w-full lg:mto-w-2/5">
			<h2 class="password--security--title mto-font-semibold mto-text-2xl"><?php echo esc_html__( 'Change Password', 'masteriyo' ); ?></h2>

				<form id="password-security-form" class="mto-mt-10">
					<input type="hidden" name="remember" value="true">
					<div class="mto-rounded-md mtoshadow-sm mto-space-y-6">
						<div class="current-password">
							<label for="current-password" class="mto-block mto-text-sm mto-font-semibold mto-mb-2"><?php echo esc_html__( 'Current Password', 'masteriyo' ); ?></label>
							<input id="current-password" name="password" type="password" required class="mto-px-4 mto-rounded mto-block mto-w-full mto-py-2 mto-border mto-border-gray-300 focus:mto-outline-none focus:mto-shadow-outline focus:mto-border-primary" placeholder="">
						</div>

						<div class="new-password">
							<label for="new-password" class="mto-block mto-text-sm mto-font-semibold mto-mb-2"><?php echo esc_html__( 'New Password', 'masteriyo' ); ?></label>
							<input id="new-password" name="password" type="password" required class="mto-px-4 mto-rounded mto-block mto-w-full mto-py-2 mto-border mto-border-gray-300 focus:mto-outline-none focus:mto-shadow-outline focus:mto-border-primary" placeholder="">
						</div>

						<div class="confirm-password">
							<label for="confirm-password" class="mto-block mto-text-sm mto-font-semibold mto-mb-2"><?php echo esc_html__( 'Confirm Password', 'masteriyo' ); ?></label>
							<input id="confirm-password" name="password" type="password" required class="mto-px-4 mto-rounded mto-block mto-w-full mto-py-2 mto-border mto-border-gray-300 focus:mto-outline-none focus:mto-shadow-outline focus:mto-border-primary" placeholder="">
						</div>
					</div>

					<div class="password-security-btn mto-mt-10">
						<button type="submit" class="change-password btn mto-text-sm mto-py-3 mto-px-6 mto-uppercase">
							<?php echo esc_html__( 'Change Password', 'masteriyo' ); ?>
						</button>
					</div>

					<div id="mto-login-error-msg" class="mto-text-red-700 mto-hidden"></div>
				</form>
		</div>
	</div>
</div>

<?php

/**
 * masteriyo_after_edit_myaccount_tab_content hook.
 */
do_action( 'masteriyo_after_edit_myaccount_tab_content' );
