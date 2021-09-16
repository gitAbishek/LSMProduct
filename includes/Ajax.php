<?php
/**
 * Ajax.
 *
 * @package Masteriyo
 *
 * @since 1.0.0
 */

namespace Masteriyo;

use Exception;
use Masteriyo\Query\CourseQuery;

defined( 'ABSPATH' ) || exit;

/**
 * Aajx class.
 *
 * @class Masteriyo\Ajax
 */

class Ajax {

	/**
	 * Actions.
	 *
	 * @static
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private static $actions = array();

	/**
	 * Initialize
	 *
	 * @static
	 * @since 1.0.0
	 */
	public static function init() {
		self::init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @static
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private static function init_hooks() {
		self::$actions = apply_filters(
			'masteriyo_ajax_actions',
			array(
				'login' => array(
					'priv'   => array( __CLASS__, 'login' ),
					'nopriv' => array( __CLASS__, 'login' ),
				),
				'test'  => array(
					'priv'   => array( __CLASS__, 'test' ),
					'nopriv' => array( __CLASS__, 'test' ),
				),
			)
		);

		foreach ( self::$actions as $key => $action ) {
			foreach ( $action as $type => $callback ) {
				$type = 'priv' === $type ? '' : '_nopriv';
				$slug = MASTERIYO_SLUG;
				add_action( "wp_ajax{$type}_{$slug}_{$key}", $callback );
			}
		}
	}

	/**
	 * Login a user.
	 *
	 * @since 1.0.0
	 */
	public static function login() {
		if ( isset( $_POST['nonce'] ) ) {
			try {
				if ( ! wp_verify_nonce( $_POST['nonce'], 'masteriyo_login_nonce' ) ) {
					throw new Exception( __( 'Invalid nonce. Maybe you should reload the page.', 'masteriyo' ) );
				}
				if ( ! isset( $_POST['payload'] ) || ! is_array( $_POST['payload'] ) ) {
					throw new Exception( __( 'Missing login credentials.', 'masteriyo' ) );
				}

				$username = isset( $_POST['payload']['username'] ) ? sanitize_text_field( $_POST['payload']['username'] ) : '';
				$password = isset( $_POST['payload']['password'] ) ? sanitize_text_field( $_POST['payload']['password'] ) : '';
				$remember = isset( $_POST['payload']['remember'] ) ? sanitize_text_field( $_POST['payload']['remember'] ) : 'no';

				if ( empty( $username ) ) {
					throw new Exception( __( 'Username cannot be empty.', 'masteriyo' ) );
				}
				if ( empty( $password ) ) {
					throw new Exception( __( 'Password cannot be empty.', 'masteriyo' ) );
				}

				$creds = array(
					'user_password' => $password,
					'remember'      => 'yes' === $remember,
				);

				if ( is_email( $username ) ) {
					$user = get_user_by( 'email', $username );

					if ( isset( $user->user_login ) ) {
						$creds['user_login'] = $user->user_login;
					} else {
						throw new Exception( __( 'No user found with the given email address.', 'masteriyo' ) );
					}
				} else {
					$creds['user_login'] = $username;
				}

				$user = wp_signon( $creds, is_ssl() );

				if ( is_wp_error( $user ) ) {
					if ( 'incorrect_password' === $user->get_error_code() ) {
						throw new Exception( __( 'Incorrect password. Please try again.', 'masteriyo' ) );
					}
					throw new Exception( $user->get_error_message() );
				}

				wp_send_json_success(
					array(
						'message' => __( 'Signed in successfully.', 'masteriyo' ),
					)
				);
			} catch ( Exception $e ) {
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

	/**
	 * Test ajax function.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function test() {
		$course_query = new CourseQuery();
		$courses      = $course_query->get_courses(
			array(
				'page'   => 1,
				'status' => 'publish',
			)
		);
	}
}

Ajax::init();
