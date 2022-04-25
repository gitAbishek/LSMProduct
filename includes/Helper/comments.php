<?php
/**
 * Comments related functions.
 *
 * @since 1.4.10
 */

use Masteriyo\Enums\CommentStatus;
use PHP_CodeSniffer\Util\Common;

/**
 * Return an array of comments count by status for specific comment type and post ID.
 *
 * @since 1.4.10
 *
 * @param string $type Comment type.
 * @param integer $post_id Post ID
 * @return array
 */
function masteriyo_count_comments( $type = 'comment', $post_id = 0 ) {
	global $wpdb;

	if ( $post_id ) {
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT comment_approved, COUNT( * ) AS num_comments FROM {$wpdb->comments } WHERE comment_type = %s AND comment_post_ID = %d GROUP by comment_approved",
				$type,
				$post_id
			),
			ARRAY_A
		);
	} else {
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT comment_approved, COUNT( * ) AS num_comments FROM {$wpdb->comments } WHERE comment_type = %s  GROUP by comment_approved",
				$type
			),
			ARRAY_A
		);
	}

	$counts = array_fill_keys( CommentStatus::readable(), 0 );

	foreach ( $results as $row ) {
		if ( CommentStatus::APPROVED === $row['comment_approved'] ) {
			$row['comment_approved'] = CommentStatus::APPROVED_STR;
		} elseif ( CommentStatus::HOLD === $row['comment_approved'] ) {
			$row['comment_approved'] = CommentStatus::HOLD_STR;
		}

		$counts[ $row['comment_approved'] ] = $row['num_comments'];
	}

	$counts = array_map( 'absint', $counts );

	/**
	 * Filter comments count by comment type and post ID.
	 *
	 * @since 1.4.10
	 *
	 * @param string $count Comment counts by status.
	 * @param string $type Comment type.
	 * @param int $post_id Post ID.
	 */
	return apply_filters( 'masteriyo_count_comments', $counts, $type, $post_id );
}
