<?php
/**
 * ReviewNotice Ajax handler.
 *
 * @since 1.4.3
 * @package Masteriyo\AjaxHandlers
 */

namespace Masteriyo\AjaxHandlers;

use Masteriyo\Abstracts\AjaxHandler;

/**
 * ReviewNotice ajax handler.
 */
class ReviewNoticeAjaxHandler extends AjaxHandler {

	/**
	 * ReviewNotice ajax action.
	 *
	 * @since 1.4.3
	 * @var string
	 */
	public $action = 'masteriyo_review_notice';

	/**
	 * Process review notice ajax request.
	 *
	 * @since 1.4.3
	 */
	public function register() {
		add_action( "wp_ajax_{$this->action}", array( $this, 'process' ) );
	}

	/**
	 * Process ajax handler review notice.
	 *
	 * @since 1.4.3
	 */
	public function process() {
		if ( ! isset( $_POST['nonce'] ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Nonce is required.', 'masteriyo' ),
				),
				400
			);
			return;
		}

		try {
			if ( ! wp_verify_nonce( $_POST['nonce'], 'masteriyo_review_notice_nonce' ) ) {
				throw new \Exception( __( 'Invalid nonce. Maybe you should reload the page.', 'masteriyo' ) );
			}

			$action = isset( $_POST['masteriyo_action'] ) ? sanitize_text_field( $_POST['masteriyo_action'] ) : null;
			$notice = get_option( 'masteriyo_review_notice', array() );
			$notice = wp_parse_args(
				$notice,
				array(
					'time_to_ask'  => time() + WEEK_IN_SECONDS,
					'reviewed'     => false,
					'closed_count' => 0,
				)
			);

			if ( 'review_received' === $action ) {
				$notice['reviewed'] = true;
			}

			if ( 'remind_me_later' === $action ) {
				$notice['time_to_ask'] = time() + DAY_IN_SECONDS;
			}

			if ( 'close_notice' === $action ) {
				$notice['closed_count'] = $notice['closed_count'] + 1;
				$notice['time_to_ask']  = time() + DAY_IN_SECONDS;
			}

			if ( 'already_reviewed' === $action ) {
				$notice['reviewed'] = true;
			}

			update_option( 'masteriyo_review_notice', $notice, true );

			wp_send_json_success();
		} catch ( \Exception $e ) {
			wp_send_json_error(
				array(
					'message' => $e->getMessage(),
				),
				400
			);
		}
	}
}
