<?php
/**
 * Login form template content.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * masteriyo_before_login_form_content hook.
 */
do_action( 'masteriyo_before_login_form_content' );

?>

<section class="login mto-container mto-mx-auto">
	<div class="mto-flex mto-justify-center mto-items-center mto-h-screen">

		<div class="mto-login-wrapper">
			<h2>Sign In</h2>

			<div class="login-form">
				<div class="mto-w-full">
					<form id="mto-login-form">
						<input type="hidden" name="remember" value="true">
						<div class="mto-rounded-md mtoshadow-sm mto-space-y-px">
							<div class="username mto-mb-6">
								<label for="username-email-address" class="mto-block mto-text-sm mto-font-semibold mto-mb-4">Username or Email</label>
								<input id="username-email-address" name="text" type="text" required class="mto-px-4 mto-rounded mto-block mto-w-full mto-py-2 mto-border mto-border-gray-300 focus:mto-outline-none focus:mto-shadow-outline focus:mto-border-primary" placeholder="">
							</div>

							<div class="password mto-mb-6">
								<label for="password" class="mto-block mto-text-sm mto-font-semibold mto-pb-4">Password</label>
								<input id="password" name="password" type="password" autocomplete="current-password" required class="mto-px-4 mto-rounded mto-block mto-w-full mto-py-2 mto-border mto-border-gray-300 focus:mto-outline-none focus:mto-shadow-outline focus:mto-border-primary" placeholder="">
							</div>
						</div>

						<div class="mto-flex mto-items-center mto-justify-between  mto-mt-6 mto-mb-6 md:mto-mb-10">
							<div class="remember-me mto-flex mto-items-center">
								<input id="remember_me" name="remember_me" type="checkbox" class="mto-h-4 mto-w-4 mto-text-primary-600 focus:mto-primary-500 mto-border-gray-300 mto-rounded">
								<label for="remember_me" class="mto-mb-0 mto-block mto-text-sm mto-text-gray-900">
								Remember me
								</label>
							</div>

							<div class="forgot-password mto-text-sm">
								<a href="<?php echo esc_url( masteriyo_get_account_endpoint_url( 'reset-password' ) ); ?>" class="mto-font-medium mto-text-primary-800 hover:mto-text-primary-700 hover:mto-underline">
								Forgot your password?
								</a>
							</div>
						</div>

						<div class="mto-block md:mto-flex mto-items-center mto-justify-between">
							<button type="submit" class="btn mto-capitalize sign-in">
								Sign in
							</button>

							<div class="sign-up mto-text-sm mto-mt-6 md:mto-my-0 mto-text-center md:mto-text-right">
								<span>Don't have an account?</span>
								<a
									href="<?php echo esc_url( masteriyo_get_account_endpoint_url( 'signup' ) ); ?>"
									class="mto-font-medium mto-text-primary-800 hover:mto-text-primary-700 hover:mto-underline"
								>
									Sign Up Now
								</a>
							</div>
						</div>

						<div id="mto-login-error-msg" class="mto-text-red-700 mto-hidden"></div>
					</form>
				</div>
				
			</div>
		</div>
	</div>
</section>

<?php

/**
 * masteriyo_after_login_form_content hook.
 */
do_action( 'masteriyo_after_login_form_content' );
