<?php
/**
 * Sign up form template content.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * masteriyo_before_sign_up_form_content hook.
 */
do_action( 'masteriyo_before_sign_up_form_content' );

?>

<section class="signup mto-container mto-mx-auto">
	<div class="mto-flex mto-justify-center mto-items-center mto-h-screen">

		<div class="mto-form-wrapper">
			<h2 class="mto-font-semibold mto-text-2xl mto-mb-4">Create Your Account</h2>
      <p>*Note: All fields are required.</p>

					<form id="mto-signup-form" class="mto-mt-10">
						<input type="hidden" name="remember" value="true">
						<div class="mto-rounded-md mtoshadow-sm mto-space-y-6">
							<div class="fullname">
								<label for="fullname" class="mto-block mto-text-sm mto-font-semibold mto-mb-2">Full Name</label>
								<input id="fullname" name="text" type="text" required class="mto-px-4 mto-rounded mto-block mto-w-full mto-py-2 mto-border mto-border-gray-300 focus:mto-outline-none focus:mto-shadow-outline focus:mto-border-primary" placeholder="">
							</div>

              <div class="email">
								<label for="email-address" class="mto-block mto-text-sm mto-font-semibold mto-mb-2">Email Address</label>
								<input id="email-address" name="text" type="text" required class="mto-px-4 mto-rounded mto-block mto-w-full mto-py-2 mto-border mto-border-gray-300 focus:mto-outline-none focus:mto-shadow-outline focus:mto-border-primary" placeholder="">
							</div>
            
							<div class="password">
								<label for="password" class="mto-block mto-text-sm mto-font-semibold mto-mb-2">Password</label>
								<input id="password" name="password" type="password" autocomplete="current-password" required class="mto-px-4 mto-rounded mto-block mto-w-full mto-py-2 mto-border mto-border-gray-300 focus:mto-outline-none focus:mto-shadow-outline focus:mto-border-primary" placeholder="">
                <p>
                  Use 10 or more characters that are a mixture of letters, numbers, and symbols
                </p>
              </div>
              
              <div class="confirm-password">
								<label for="confirm-password" class="mto-block mto-text-sm mto-font-semibold mto-mb-2">Confirm Password</label>
								<input id="confirm-password" name="password" type="password" autocomplete="current-password" required class="mto-px-4 mto-rounded mto-block mto-w-full mto-py-2 mto-border mto-border-gray-300 focus:mto-outline-none focus:mto-shadow-outline focus:mto-border-primary" placeholder="">
              </div>
						</div>

							<div class="remember-me mto-flex mto-items-center mto-my-6 md:mto-mb-10">
								<input id="remember_me" name="remember_me" type="checkbox" class="mto-h-4 mto-w-4 mto-text-primary-600 focus:mto-primary-500 mto-border-gray-300 mto-rounded">
								<label for="remember_me" class="mto-mb-0 mto-block mto-text-sm mto-text-gray-900">
								<span>I accept the <a href="#" class="mto-underline">Terms of Conditions</a>.</span>
								</label>
							</div>

							<button type="submit" class="btn mto-capitalize sign-in">
							  Get Started
							</button>

						<div id="mto-login-error-msg" class="mto-text-red-700 mto-hidden"></div>
					</form>

		</div>
	</div>
</section>

<?php

/**
 * masteriyo_after_sign_up_form_content hook.
 */
do_action( 'masteriyo_after_sign_up_form_content' );
