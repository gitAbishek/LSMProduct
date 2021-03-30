<?php
/**
 * Lost password reset form.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'masteriyo_before_password_reset_form' );

?>

<section class="login mto-container mto-mx-auto">
	<div class="mto-flex mto-justify-center mto-items-center mto-h-screen">

		<div class="mto-login-wrapper">
			<h2 class="mto-mb-4">Reset Password</h2>
      <p>Enter your user account's verified email address and we will send you a password reset link.</p>
			<div class="reset-form mto-mt-2">
				<div class="mto-w-full">
					<form id="mto-reset-form">
						<input type="hidden" name="remember" value="true">
						<div class="mto-rounded-md mtoshadow-sm">
							<div class="reset-username mto-mb-6">
								<label for="reset-username-email-address" class="mto-block mto-text-sm mto-font-semibold mto-mb-4">Username or Email</label>
								<input id="reset-username-email-address" name="text" type="text" required class="mto-px-4 mto-rounded mto-block mto-w-full mto-py-2 mto-border mto-border-gray-300 focus:mto-outline-none focus:mto-shadow-outline focus:mto-border-primary" placeholder="">
							</div>
						</div>

						<div class="mto-block md:mto-flex mto-items-center md:mto-space-x-6">
							<button type="submit" class="btn mto-capitalize sign-in">
                Send Reset Email
							</button>

							<div class="go-back mto-text-sm mto-mt-6 md:mto-my-0 mto-text-center md:mto-text-left">
								<a
									href="#"
									class="mto-font-medium mto-text-primary-800 hover:mto-text-primary-700 hover:mto-underline"
								>
									Go back to sign-in
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

do_action( 'masteriyo_after_password_reset_form' );

