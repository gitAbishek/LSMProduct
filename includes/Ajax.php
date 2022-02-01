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
 * Ajax class.
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
				'login'         => array(
					'priv'   => array( __CLASS__, 'login' ),
					'nopriv' => array( __CLASS__, 'login' ),
				),
				'review_notice' => array(
					'priv' => array( __CLASS__, 'review_notice' ),
				),
				'test'          => array(
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

				$credentials = array(
					'user_password' => $password,
					'remember'      => 'yes' === $remember,
				);

				if ( is_email( $username ) ) {
					$user = get_user_by( 'email', $username );

					if ( isset( $user->user_login ) ) {
						$credentials['user_login'] = $user->user_login;
					} else {
						throw new Exception( __( 'No user found with the given email address.', 'masteriyo' ) );
					}
				} else {
					$credentials['user_login'] = $username;
				}

				$user = wp_signon( $credentials, is_ssl() );

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
	 * Review notice action.
	 *
	 * @since 1.4.0
	 */
	public static function review_notice() {
		if ( ! isset( $_POST['nonce'] ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Nonce is required.', 'masteriyo' ),
				)
			);
			return;
		}

		try {
			if ( ! wp_verify_nonce( $_POST['nonce'], 'masteriyo_review_notice_nonce' ) ) {
				throw new Exception( __( 'Invalid nonce. Maybe you should reload the page.', 'masteriyo' ) );
			}
			$action = isset( $_POST['masteriyo_action'] ) ? sanitize_text_field( $_POST['masteriyo_action'] ) : null;

			if ( 'review_received' === $action ) {
				masteriyo_set_setting( 'general.review_notice.reviewed', true );
			}
			if ( 'remind_me_later' === $action ) {
				masteriyo_set_setting( 'general.review_notice.time_to_ask', time() + DAY_IN_SECONDS );
			}
			if ( 'close_notice' === $action ) {
				$closed_count = absint( masteriyo_get_setting( 'general.review_notice.closed_count' ) );

				masteriyo_set_setting( 'general.review_notice.time_to_ask', time() + DAY_IN_SECONDS );
				masteriyo_set_setting( 'general.review_notice.closed_count', $closed_count + 1 );
			}
			if ( 'already_reviewed' === $action ) {
				masteriyo_set_setting( 'general.review_notice.reviewed', true );
			}

			wp_send_json_success();
		} catch ( Exception $e ) {
			wp_send_json_error(
				array(
					'message' => $e->getMessage(),
				)
			);
		}
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
