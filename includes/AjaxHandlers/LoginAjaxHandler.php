<?php
/**
 * Login Ajax handler.
 *
 * @since x.x.x
 *
 * @package Masteriyo\AjaxHandlers
 */

namespace Masteriyo\AjaxHandlers;

use Masteriyo\Abstracts\AjaxHandler;

/**
 * Login ajax handler.
 */
class LoginAjaxHandler extends AjaxHandler {

	/**
	 * Login ajax action.
	 *
	 * @since x.x.x
	 * @var string
	 */
	public $action = 'masteriyo_login';

	/**
	 * Register ajax handler.
	 *
	 * @since x.x.x
	 */
	public function register() {
		add_action( "wp_ajax_nopriv_{$this->action}", array( $this, 'login' ) );
	}

	/**
	 * Process login ajax request.
	 *
	 * @since x.x.x
	 */
	public function login() {
		if ( isset( $_POST['nonce'] ) ) {
			try {
				if ( ! wp_verify_nonce( $_POST['nonce'], 'masteriyo_login_nonce' ) ) {
					throw new \Exception( __( 'Invalid nonce. Maybe you should reload the page.', 'masteriyo' ) );
				}
				if ( ! isset( $_POST['payload'] ) || ! is_array( $_POST['payload'] ) ) {
					throw new \Exception( __( 'Missing login credentials.', 'masteriyo' ) );
				}

				$username = isset( $_POST['payload']['username'] ) ? sanitize_text_field( $_POST['payload']['username'] ) : '';
				$password = isset( $_POST['payload']['password'] ) ? sanitize_text_field( $_POST['payload']['password'] ) : '';
				$remember = isset( $_POST['payload']['remember'] ) ? sanitize_text_field( $_POST['payload']['remember'] ) : 'no';

				if ( empty( $username ) ) {
					throw new \Exception( __( 'Username cannot be empty.', 'masteriyo' ) );
				}
				if ( empty( $password ) ) {
					throw new \Exception( __( 'Password cannot be empty.', 'masteriyo' ) );
				}

				$credentials = array(
					'user_password' => $password,
					'remember'      => 'yes' === $remember,
				);

				if ( is_email( $username ) ) {
					$user = get_user_by( 'email', $username );

					if ( isset( $user->user_login ) ) {
						$credentials['user_login'] = $user->user_login;
					} else {
						throw new \Exception( __( 'No user found with the given email address.', 'masteriyo' ) );
					}
				} else {
					$credentials['user_login'] = $username;
				}

				$user = wp_signon( $credentials, is_ssl() );

				if ( is_wp_error( $user ) ) {
					if ( 'incorrect_password' === $user->get_error_code() ) {
						throw new \Exception( __( 'Incorrect password. Please try again.', 'masteriyo' ) );
					}
					throw new \Exception( $user->get_error_message() );
				}

				wp_send_json_success(
					array(
						'message' => __( 'Signed in successfully.', 'masteriyo' ),
					)
				);
			} catch ( \Exception $e ) {
				wp_send_json_error(
					array(
						'message' => $e->getMessage(),
					)
				);
			}
		}

		wp_send_json_error(
			array(
				'message' => __( 'Nonce is required.', 'masteriyo' ),
			)
		);
	}
}
