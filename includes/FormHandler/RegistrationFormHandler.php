<?php
/**
 * Handle registration form.
 *
 * @package Masetriyo\Classes\
 */

namespace Masteriyo\FormHandler;

use Masteriyo\Notice;

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
		try {
			if ( ! isset( $_POST['masteriyo-registration'] ) ) {
				return;
			}

			$nonce_value = isset( $_POST['_wpnonce'] ) ? wp_unslash( $_POST['_wpnonce'] ) : '';

			if ( ! wp_verify_nonce( $nonce_value, 'masteriyo-register' ) ) {
				throw new \Exception( __( 'Invalid nonce', 'masteriyo' ) );
			}

			$data  = $this->get_form_data();
			$error = $this->validate_form( $data );

			if ( is_wp_error( $error ) ) {
				foreach ( $error->get_error_messages() as $message ) {
					masteriyo_add_notice( $message, Notice::ERROR );
				}

				throw new \Exception( 'Registration failed' );
			}

			$user = masteriyo_create_new_user(
				$data['email'],
				$data['username'],
				$data['password'],
				array(
					'first_name' => $data['first-name'],
					'last_name'  => $data['last-name'],
				)
			);

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
			$error = $e->getMessage();
		}
	}

	/**
	 * Validate the submitted form.
	 *
	 * @param array $data Form data.
	 *
	 * @since 0.1.0
	 */
	protected function validate_form( $data ) {
		$error = new \WP_Error();

		if ( ! masteriyo_registration_is_generate_username() && empty( $data['username'] ) ) {
			$error->add( 'username_required', __( 'Username is required.', 'masteriyo' ) );
		}

		if ( empty( $data['email'] ) ) {
			$error->add( 'email_required', __( 'Email is required.', 'masteriyo' ) );
		}

		if ( ! is_email( $data['email'] ) ) {
			$error->add( 'invalid_email', __( 'Email is invalid.', 'masteriyo' ) );
		}

		if ( empty( $data['first-name'] ) ) {
			$error->add( 'first_name_required', __( 'First name is required.', 'masteriyo' ) );
		}

		if ( empty( $data['last-name'] ) ) {
			$error->add( 'last_name_required', __( 'Last name is required.', 'masteriyo' ) );
		}

		if ( ! masteriyo_registration_is_generate_password() ) {
			if ( empty( $data['password'] ) ) {
				$error->add( 'password_required', __( 'Password is required.', 'masteriyo' ) );
			}

			if ( empty( $data['confirm-password'] ) ) {
				$error->add( 'confirm_password_required', __( 'Confirm password is required.', 'masteriyo' ) );
			}

			if ( $data['password'] !== $data['confirm-password'] ) {
				$error->data( 'passwords_do_not_match', __( 'The passwords doesn\'t match.', 'masteriyo' ) );
			}
		}

		if ( ! isset( $data['accept-terms-and-conditions'] ) || 'yes' !== $data['accept-terms-and-conditions'] ) {
			$error->add( 'terms_and_conditions_required', __( 'You must accept the Terms & Conditions to proceed.', 'masteriyo' ) );
		}

		$error = apply_filters( 'masteriyo_validate_registration_form_data', $error, $data );

		if ( $error->has_errors() ) {
			return $error;
		} else {
			return true;
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
		$nonce_value = isset( $_POST['_wpnonce'] ) ? wp_unslash( $_POST['_wpnonce'] ) : '';

		if ( empty( $nonce_value ) ) {
			throw new \Exception( __( 'Nonce is missing', 'masteriyo' ) );
		}
		if ( ! wp_verify_nonce( $nonce_value, 'masteriyo-register' ) ) {
			throw new \Exception( __( 'Invalid nonce', 'masteriyo' ) );
		}

		$data   = array();
		$fields = array( 'first-name', 'last-name', 'username', 'email', 'password', 'confirm-password', 'accept-terms-and-conditions' );

		foreach ( $fields as $key ) {
			if ( ! isset( $_POST[ $key ] ) ) {
				$data[ $key ] = '';
				continue;
			}

			if ( 'email' === $key ) {
				$data[ $key ] = sanitize_email( wp_unslash( trim( $_POST[ $key ] ) ) );
			}

			if ( 'username' === $key ) {
				$data[ $key ] = sanitize_user( trim( $_POST[ $key ] ) );
			}

			$data[ $key ] = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
		}
		return $data;
	}
}
