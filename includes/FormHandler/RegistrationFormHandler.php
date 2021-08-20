<?php
/**
 * Handle registration form.
 *
 * @package ThemeGrill\Masetriyo\Classes\
 */

namespace ThemeGrill\Masteriyo\FormHandler;

defined( 'ABSPATH' ) || exit;

/**
 * Registration class.
 */
class RegistrationFormHandler {
	public function __construct() {
		add_action( 'wp_loaded', array( $this, 'process_registration' ), 20 );
	}

	/**
	 * Handle registration.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function process_registration() {
		if ( ! isset( $_POST['masteriyo-registration'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			return;
		}

		try {
			$this->verify_nonce();
			$this->validate_form();

			$data = $this->get_form_data();
			$user = masteriyo_create_new_user( $data['email'], $data['username'], $data['password'] );

			if ( is_wp_error( $user ) ) {
				throw new \Exception( $user->get_error_message() );
			}

			if ( masteriyo_registration_is_generate_password() ) {
				masteriyo_add_notice( __( 'Your account was created successfully and a password has been sent to your email address.', 'masteriyo' ) );
			} else {
				masteriyo_add_notice( __( 'Your account was created successfully. Your login details have been sent to your email address.', 'masteriyo' ) );
			}

			$this->redirect( $user );

		} catch ( \Exception $e ) {
			if ( $e->getMessage() ) {
				masteriyo_add_notice( sprintf( '<strong>%s: %s</strong> ', __( 'Error', 'masteriyo' ), $e->getMessage() ), 'error' );
			}
		}
	}

	/**
	 * Verify nonce.
	 *
	 * @since 0.1.0
	 */
	protected function verify_nonce() {
		$nonce_value = isset( $_POST['_wpnonce'] ) ? wp_unslash( $_POST['_wpnonce'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		if ( empty( $nonce_value ) ) {
			throw new \Exception( __( 'Nonce is missing', 'masteriyo' ) );
		}
		if ( ! wp_verify_nonce( $nonce_value, 'masteriyo-register' ) ) {
			throw new \Exception( __( 'Invalid nonce', 'masteriyo' ) );
		}
	}

	/**
	 * Validate the submitted form.
	 *
	 * @since 0.1.0
	 */
	protected function validate_form() {
		$data = $this->get_form_data();

		if ( ! masteriyo_registration_is_generate_username() && empty( $data['username'] ) ) {
			throw new \Exception( __( 'Username is required', 'masteriyo' ) );
		}

		if ( empty( $data['email'] ) ) {
			throw new \Exception( __( 'Email is required', 'masteriyo' ) );
		}

		if ( ! masteriyo_registration_is_generate_password() ) {
			if ( empty( $data['password'] ) ) {
				throw new \Exception( __( 'Password is required', 'masteriyo' ) );
			}
			if ( empty( $data['confirm-password'] ) ) {
				throw new \Exception( __( 'Confirm password is required', 'masteriyo' ) );
			}
			if ( $data['password'] !== $data['confirm-password'] ) {
				throw new \Exception( __( 'The passwords doesn\'t match', 'masteriyo' ) );
			}
		}

		if ( ! isset( $data['accept-terms-and-conditions'] ) || 'yes' !== $data['accept-terms-and-conditions'] ) {
			throw new \Exception( __( 'You must accept the Terms & Conditions to proceed', 'masteriyo' ) );
		}

		$validation_error  = new \WP_Error();
		$validation_error  = apply_filters( 'masteriyo_validate_registration_form_data', $validation_error, $data );
		$validation_errors = $validation_error->get_error_messages();

		if ( 1 === count( $validation_errors ) ) {
			throw new \Exception( $validation_error->get_error_message() );
		} elseif ( $validation_errors ) {
			foreach ( $validation_errors as $message ) {
				masteriyo_add_notice( sprintf( '<strong>%s:%s</strong> ', __( 'Error', 'masteriyo' ), $message ), 'error' );
			}
			throw new \Exception();
		}
	}

	/**
	 * Redirect after registration.
	 *
	 * @since 0.1.0
	 *
	 * @param User $user
	 */
	protected function redirect( $user ) {
		// Only redirect after a forced login - otherwise output a success notice.
		if ( apply_filters( 'masteriyo_registration_auth_new_user', masteriyo_registration_is_auth_new_user(), $user ) ) {
			masteriyo_set_customer_auth_cookie( $user->get_id() );

			$redirection_url = apply_filters( 'masteriyo_registration_redirect_url', masteriyo_get_page_permalink( 'myaccount' ) );
			$redirection_url = wp_validate_redirect( $redirection_url, masteriyo_get_page_permalink( 'myaccount' ) );

			wp_redirect( $redirection_url ); //phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect
			exit;
		}
	}

	/**
	 * Get the submitted form data.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	protected function get_form_data() {
		$data   = array();
		$fields = array( 'username', 'email', 'password', 'confirm-password', 'accept-terms-and-conditions' );

		foreach ( $fields as $key ) {
			if ( ! isset( $_POST[ $key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
				$data[ $key ] = '';
				continue;
			}

			if ( 'email' === $key ) {
				$data[ $key ] = sanitize_email( wp_unslash( trim( $_POST[ $key ] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
			}
			if ( 'username' === $key ) {
				$data[ $key ] = sanitize_user( trim( $_POST[ $key ] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
			}

			$data[ $key ] = wp_unslash( $_POST[ $key ] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		}
		return $data;
	}
}
