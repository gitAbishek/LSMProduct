<?php
/**
 * Change password form handler class. The form is located in myaccount page.
 *
 * @package ThemeGrill\Masetriyo\Classes\
 */

namespace ThemeGrill\Masteriyo\FormHandler;

defined( 'ABSPATH' ) || exit;

/**
 * Change password form handler class.
 *
 * @since 0.1.0
 */
class ChangePasswordFormHandler {
	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		add_action( 'wp_loaded', array( $this, 'handle' ), 20 );
	}

	/**
	 * Handle change password form.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function handle() {
		if ( ! isset( $_POST['masteriyo-change-password'] ) || ! is_user_logged_in() ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			return;
		}

		try {
			$this->verify_nonce();
			$this->validate_form();

			masteriyo_nocache_headers();

			$data = $this->get_form_data();
			$user = masteriyo_get_user( get_current_user_id() );

			$user->set_user_pass( $data['password_1'] );

			masteriyo('user.store')->update( $user );

			masteriyo_add_notice( __( 'Your password was changed successfully', 'masteriyo' ) );

			do_action( 'masteriyo_changed_password', $user, $data );

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
		if ( ! wp_verify_nonce( $nonce_value, 'masteriyo-change-password' ) ) {
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
		$user = wp_get_current_user();

		if ( ! empty( $data['current_password'] ) && empty( $data['password_1'] ) && empty( $data['password_2'] ) ) {
			throw new \Exception( __( 'Please fill out all password fields', 'masteriyo' ) );
		}
		if ( empty( $data['current_password'] ) ) {
			throw new \Exception( __( 'Please enter your current password', 'masteriyo' ) );
		}
		if ( ! wp_check_password( $data['current_password'], $user->user_pass, $user->ID ) ) {
			throw new \Exception( __( 'Your current password is incorrect', 'masteriyo' ) );
		}
		if ( empty( $data['password_1'] ) ) {
			throw new \Exception( __( 'Please enter a new password', 'masteriyo' ) );
		}
		if ( empty( $data['password_2'] ) ) {
			throw new \Exception( __( 'Please re-enter your new password', 'masteriyo' ) );
		}
		if ( $data['password_1'] !== $data['password_2'] ) {
			throw new \Exception( __( 'New passwords do not match', 'masteriyo' ) );
		}

		$validation_error  = new \WP_Error();
		$validation_error  = apply_filters( 'masteriyo_validate_change_password_form_data', $validation_error, $data );
		$validation_errors = $validation_error->get_error_messages();

		if ( count( $validation_errors ) > 0 ) {
			foreach ( $validation_errors as $message ) {
				masteriyo_add_notice( sprintf( '<strong>%s: %s</strong> ', __( 'Error', 'masteriyo' ), $message ), 'error' );
			}
			throw new \Exception;
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
		$fields = array( 'current_password', 'password_1', 'password_2' );

		foreach( $fields as $key ) {
			if ( ! isset( $_POST[ $key ] ) ) {
				$data[ $key ] = '';
				continue;
			}
			$data[ $key ] = trim( $_POST[ $key ] );
		}
		return $data;
	}
}
