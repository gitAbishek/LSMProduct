<?php
/**
 * Faqs handlers.
 *
 * @package Masteriyo
 *
 * @since 0.1.0
 */

namespace Masteriyo;

defined( 'ABSPATH' ) || exit;

/**
 * Faqs class.
 */
class Faqs {
	/**
	 * Init.
	 *
	 * @since 0.1.0
	 */
	public static function init() {
		add_filter( 'comments_open', array( __CLASS__, 'comments_open' ), 10, 2 );
		add_action( 'comment_moderation_recipients', array( __CLASS__, 'comment_moderation_recipients' ), 10, 2 );
		add_filter( 'get_avatar_comment_types', array( __CLASS__, 'add_avatar_for_review_comment_type' ) );
	}

	/**
	 * See if comments are open.
	 *
	 * @since 0.1.0
	 *
	 * @param  bool $open    Whether the current post is open for comments.
	 * @param  int  $post_id Post ID.
	 *
	 * @return bool
	 */
	public static function comments_open( $open, $post_id ) {
		if ( 'course' === get_post_type( $post_id ) ) {
			$open = false;
		}
		return $open;
	}

	/**
	 * Make sure WP displays avatars for comments with the `masteriyo-faq` type.
	 *
	 * @since 0.1.0
	 *
	 * @param  array $comment_types Comment types.
	 *
	 * @return array
	 */
	public static function add_avatar_for_review_comment_type( $comment_types ) {
		return array_merge( $comment_types, array( 'masteriyo-faq' ) );
	}

	/**
	 * Modify recipient of review email.
	 *
	 * @since 0.1.0
	 *
	 * @param array $emails     Emails.
	 * @param int   $comment_id Comment ID.
	 *
	 * @return array
	 */
	public static function comment_moderation_recipients( $emails, $comment_id ) {
		$comment = get_comment( $comment_id );

		if ( $comment && 'course' === get_post_type( $comment->comment_post_ID ) ) {
			$emails = array( get_option( 'admin_email' ) );
		}

		return $emails;
	}
}
