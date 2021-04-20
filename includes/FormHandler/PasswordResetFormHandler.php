<?php
/**
 * Password reset form handler class.
 *
 * @package ThemeGrill\Masetriyo\Classes\
 */

namespace ThemeGrill\Masteriyo\FormHandler;

defined( 'ABSPATH' ) || exit;

/**
 * Password reset form handler class.
 *
 * @since 0.1.0
 */
class PasswordResetFormHandler {
	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		add_action( 'wp_loaded', array( $this, 'process' ) );
	}

	/**
	 * Handle Password reset.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function process() {
		if ( isset( $_GET['password-reset-complete'] ) ) {
			masteriyo_add_notice( __( 'Your password has been reset successfully.', 'masteriyo' ) );
		}

		if ( ! isset( $_REQUEST['masteriyo-password-reset'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			return;
		}

		try {
			$this->verify_nonce();
			$this->validate_form();
			$user = $this->validate_reset_key();
			$data = $this->get_form_data();

			do_action( 'password_reset', $user, $data['password'] );

			wp_set_password( $data['password'], $user->get_id() );

			masteriyo_set_password_reset_cookie();

			if ( ! apply_filters( 'masteriyo_disable_password_change_notification', false ) ) {
				wp_password_change_notification( $user );
			}

			do_action( 'masteriyo_user_password_reset', $user, $data );

			wp_safe_redirect( add_query_arg( 'password-reset-complete', 'true', masteriyo_get_page_permalink( 'myaccount' ) ) );
			exit;
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
		$nonce_value = isset( $_REQUEST['_wpnonce'] ) ? wp_unslash( $_REQUEST['_wpnonce'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		if ( empty( $nonce_value ) ) {
			throw new \Exception( __( 'Nonce is missing', 'masteriyo' ) );
		}
		if ( ! wp_verify_nonce( $nonce_value, 'masteriyo-password-reset' ) ) {
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

		if ( empty( $data['password'] ) ) {
			throw new \Exception( __( 'Password is required', 'masteriyo' ) );
		}
		if ( empty( $data['confirm-password'] ) ) {
			throw new \Exception( __( 'Confirm password is required', 'masteriyo' ) );
		}
		if ( $data['password'] !== $data['confirm-password'] ) {
			throw new \Exception( __( 'The passwords doesn\'t match', 'masteriyo' ) );
		}

		/**
		 * Allow to validate for third parties.
		 */
		$validation_error  = new \WP_Error();
		$validation_error  = apply_filters( 'masteriyo_validate_password_reset_form_data', $validation_error, $data );
		$validation_errors = $validation_error->get_error_messages();

		if ( count( $validation_errors ) > 0 ) {
			foreach ( $validation_errors as $message ) {
				masteriyo_add_notice( sprintf( '<strong>%s: %s</strong> ', __( 'Error', 'masteriyo' ), $message ), 'error' );
			}
			throw new \Exception;
		}
	}

	/**
	 * Validate password reset key and return the related user.
	 *
	 * @since 0.1.0
	 *
	 * @return User
	 */
	protected function validate_reset_key() {
		$data = $this->get_form_data();
		$user = check_password_reset_key( $data['reset_key'], $data['reset_login'] );

		if ( is_wp_error( $user ) ) {
			throw new \Exception( __( 'This key is invalid or has already been used. Please reset your password again if needed', 'masteriyo' ) );
		}

		$validation_errors = new \WP_Error();
		do_action( 'validate_password_reset', $validation_errors, $user );

		if ( count( $validation_errors ) > 0 ) {
			foreach ( $validation_errors as $message ) {
				masteriyo_add_notice( sprintf( '<strong>%s: %s</strong> ', __( 'Error', 'masteriyo' ), $message ), 'error' );
			}
			throw new \Exception;
		}

		return masteriyo_get_user( $user );
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
		$fields = array( 'password', 'confirm-password', 'reset_key', 'reset_login' );

		foreach( $fields as $key ) {
			if ( isset( $_REQUEST[ $key ] ) ) {
				if ( 'email' === $key ) {
					$data[ $key ] = sanitize_email( wp_unslash( trim( $_REQUEST[ $key ] ) ) );
					continue;
				}
				if ( 'username' === $key ) {
					$data[ $key ] = sanitize_user( trim( $_REQUEST[ $key ] ) );
					continue;
				}

				$data[ $key ] = wp_unslash( $_REQUEST[ $key ] );
			} else {
				$data[ $key ] = '';
			}
		}
		return $data;
	}
}
