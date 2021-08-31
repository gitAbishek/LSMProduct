<?php
/**
 * Password reset request form handler class.
 *
 * @package Masetriyo\Classes\
 */

namespace Masteriyo\FormHandler;

defined( 'ABSPATH' ) || exit;

/**
 * Password reset request form handler class.
 *
 * @since 0.1.0
 */
class RequestPasswordResetFormHandler {
	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		add_action( 'wp_loaded', array( $this, 'process_password_reset_request' ), 20 );
	}

	/**
	 * Handle Password reset request.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function process_password_reset_request() {

		try {
			if ( ! isset( $_POST['masteriyo-password-reset-request'] ) ) {
				return;
			}

			$nonce_value = isset( $_POST['_wpnonce'] ) ? wp_unslash( $_POST['_wpnonce'] ) : '';

			if ( empty( $nonce_value ) ) {
				throw new \Exception( __( 'Nonce is missing', 'masteriyo' ) );
			}
			if ( ! wp_verify_nonce( $nonce_value, 'masteriyo-password-reset-request' ) ) {
				throw new \Exception( __( 'Invalid nonce', 'masteriyo' ) );
			}

			$this->validate_form();

			$data = $this->get_form_data();

			if ( is_email( $data['user_login'] ) && apply_filters( 'masteriyo_get_username_from_email', true ) ) {
				$wp_user = get_user_by( 'email', $data['user_login'] );
			} else {
				$wp_user = get_user_by( 'login', $data['user_login'] );
			}

			if ( ! $wp_user ) {
				throw new \Exception( __( 'Invalid username or email', 'masteriyo' ) );
			}

			$user   = masteriyo_get_user( $wp_user );
			$errors = new \WP_Error();

			do_action( 'lostpassword_post', $errors );

			if ( $errors->get_error_code() ) {
				throw new \Exception( $errors->get_error_message() );
			}

			if ( is_multisite() && ! is_user_member_of_blog( $user->get_id(), get_current_blog_id() ) ) {
				throw new \Exception( __( 'Invalid username or email', 'masteriyo' ) );
			}

			do_action( 'retrieve_password', $user->get_username() );

			if ( ! apply_filters( 'allow_password_reset', true, $user ) ) {
				throw new \Exception( __( 'Password reset is not allowed for this user', 'masteriyo' ) );
			}

			// Get password reset key (function introduced in WordPress 4.4).
			$key = get_password_reset_key( $wp_user );

			if ( is_wp_error( $key ) ) {
				throw new \Exception( $key->get_error_message() );
			}

			masteriyo( 'email.password-reset' )->trigger( $user->get_id(), $key );

			do_action( 'masteriyo_after_password_reset_email', $user, $key );

			wp_safe_redirect( add_query_arg( 'reset-link-sent', 'true', masteriyo_get_account_endpoint_url( 'reset-password' ) ) );
			exit;

		} catch ( \Exception $e ) {
			if ( $e->getMessage() ) {
				masteriyo_add_notice( sprintf( '<strong>%s: %s</strong> ', __( 'Error', 'masteriyo' ), $e->getMessage() ), 'error' );
			}
		}
	}

	/**
	 * Validate the submitted form.
	 *
	 * @since 0.1.0
	 */
	protected function validate_form() {
		$data = $this->get_form_data();

		if ( empty( $data['user_login'] ) ) {
			throw new \Exception( __( 'Enter a username or email address', 'masteriyo' ) );
		}

		$validation_error  = new \WP_Error();
		$validation_error  = apply_filters( 'masteriyo_validate_password_reset_request_form_data', $validation_error, $data );
		$validation_errors = $validation_error->get_error_messages();

		if ( count( $validation_errors ) > 0 ) {
			foreach ( $validation_errors as $message ) {
				masteriyo_add_notice( sprintf( '<strong>%s: %s</strong> ', __( 'Error', 'masteriyo' ), $message ), 'error' );
			}
			throw new \Exception();
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
		$nonce_value = isset( $_POST['_wpnonce'] ) ? wp_unslash( $_POST['_wpnonce'] ) : '';

		if ( empty( $nonce_value ) ) {
			throw new \Exception( __( 'Nonce is missing', 'masteriyo' ) );
		}
		if ( ! wp_verify_nonce( $nonce_value, 'masteriyo-password-reset-request' ) ) {
			throw new \Exception( __( 'Invalid nonce', 'masteriyo' ) );
		}

		if ( isset( $_POST['user_login'] ) ) {
			return array(
				'user_login' => sanitize_user( trim( $_POST['user_login'] ) ),
			);
		}

		return array(
			'user_login' => '',
		);
	}
}
