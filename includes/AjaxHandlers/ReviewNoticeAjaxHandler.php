<?php
/**
 * ReviewNotice Ajax handler.
 *
 * @since x.x.x
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
	 * @since x.x.x
	 * @var string
	 */
	public $action = 'masteriyo_review_notice';

	/**
	 * Process review notice ajax request.
	 *
	 * @since x.x.x
	 */
	public function register() {
		add_action( "wp_ajax_{$this->action}", array( $this, '' ) );
	}

	/**
	 * Process ajax handler review notice.
	 *
	 * @since x.x.x
	 */
	public function process() {
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
				throw new \Exception( __( 'Invalid nonce. Maybe you should reload the page.', 'masteriyo' ) );
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
		} catch ( \Exception $e ) {
			wp_send_json_error(
				array(
					'message' => $e->getMessage(),
				)
			);
		}
	}
}
