<?php
/**
 * CourseReview Repository class.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Repository;
 */

namespace ThemeGrill\Masteriyo\Repository;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Models\CourseReview;

/**
 * CourseReview Repository class.
 */
class CourseReviewRepository extends AbstractRepository implements RepositoryInterface {

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 0.1.0
	 * @var array
	 */
	protected $internal_meta_keys = array();

	/**
	 * Create course review (comment) in database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $course_review Course review object.
	 */
	public function create( Model &$course_review ) {

		if ( ! $course_review->get_date_created( 'edit' ) ) {
			$course_review->set_date_created( current_time( 'mysql', true ) );
		}

		$id = wp_insert_comment(
			apply_filters(
				'masteriyo_new_course_review_data',
				array(
					'comment_post_ID'      => $course_review->get_course_id(),
					'comment_author'       => $course_review->get_author_name( 'edit' ),
					'comment_author_email' => $course_review->get_author_email( 'edit' ),
					'comment_author_url'   => $course_review->get_author_url( 'edit' ),
					'comment_author_IP'    => $course_review->get_ip_address( 'edit' ),
					'comment_date'         => $course_review->get_date_created( 'edit' ),
					'comment_date_gmt'     => $course_review->get_date_created( 'edit' ),
					'comment_content'      => $course_review->get_content(),
					'comment_karma'        => $course_review->get_karma( 'edit' ),
					'comment_approved'     => $course_review->get_status( 'edit' ),
					'comment_agent'        => $course_review->get_agent( 'edit' ),
					'comment_type'         => $course_review->get_type( 'edit' ),
					'comment_parent'       => $course_review->get_parent( 'edit' ),
					'user_id'              => $course_review->get_author_id( 'edit' ),
				),
				$course_review
			)
		);

		if ( $id && ! is_wp_error( $id ) ) {
			$course_review->set_id( $id );
			$course_review->apply_changes();

			do_action( 'masteriyo_new_course_review', $id, $course_review );
		}
	}

	/**
	 * Read a course review.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $course_review course review object.
	 *
	 * @throws \Exception If invalid course review.
	 */
	public function read( Model &$course_review ) {
		$course_review_obj = get_comment( $course_review->get_id() );

		if ( ! $course_review->get_id() || ! $course_review_obj ) {
			throw new \Exception( __( 'Invalid Course Review.', 'masteriyo' ) );
		}

		$course_review->set_props(
			array(
				'course_id'    => $course_review_obj->comment_post_ID,
				'author_name'  => $course_review_obj->comment_author,
				'author_email' => $course_review_obj->comment_author_email,
				'author_url'   => $course_review_obj->comment_author_url,
				'ip_address'   => $course_review_obj->comment_author_IP,
				'date_created' => $course_review_obj->comment_date,
				'content'      => $course_review_obj->comment_content,
				'karma'        => $course_review_obj->comment_karma,
				'status'       => $course_review_obj->comment_approved,
				'agent'        => $course_review_obj->comment_agent,
				'type'         => $course_review_obj->comment_type,
				'parent'       => $course_review_obj->comment_parent,
				'author_id'    => $course_review_obj->user_id,
			)
		);

		$this->read_comment_data( $course_review_obj );
		$this->read_extra_data( $course_review );
		$course_review->set_object_read( true );

		do_action( 'masteriyo_course_review_read', $course_review->get_id(), $course_review );
	}

	/**
	 * Update a course review in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $course_review course review object.
	 *
	 * @return void
	 */
	public function update( Model &$course_review ) {
		$changes = $course_review->get_changes();

		$course_review_data_keys = array(
			'author_name',
			'author_email',
			'author_url',
			'ip_address',
			'date_created',
			'content',
			'status',
			'parent',
		);

		// Only update the course review when the course review data changes.
		if ( array_intersect( $course_review_data_keys, array_keys( $changes ) ) ) {
			$course_review_data = array(
				'comment_author'       => $course_review->get_author_name( 'edit' ),
				'comment_author_email' => $course_review->get_author_email( 'edit' ),
				'comment_author_url'   => $course_review->get_author_url( 'edit' ),
				'comment_author_IP'    => $course_review->get_ip_address( 'edit' ),
				'comment_content'      => $course_review->get_content( 'edit' ),
				'comment_approved'     => $course_review->get_status( 'edit' ),
				'comment_parent'       => $course_review->get_parent( 'edit' ),
				'user_id'              => $course_review->get_author_id( 'edit' ),
			);

			wp_update_comment( array_merge( array( 'comment_ID' => $course_review->get_id() ), $course_review_data ) );
		}

		$course_review->apply_changes();

		do_action( 'masteriyo_update_course_review', $course_review->get_id(), $course_review );
	}

	/**
	 * Delete a course review from the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $course_review course review object.
	 * @param array $args Array of args to pass.alert-danger.
	 */
	public function delete( Model &$course_review, $args = array() ) {
		$id          = $course_review->get_id();
		$object_type = $course_review->get_object_type();
		$args        = array_merge(
			array(
				'force_delete' => false,
			),
			$args
		);

		if ( ! $id ) {
			return;
		}

		if ( $args['force_delete'] ) {
			do_action( 'masteriyo_before_delete_' . $object_type, $id, $course_review );
			wp_delete_comment( $id, true );
			$course_review->set_id( 0 );
			do_action( 'masteriyo_after_delete_' . $object_type, $id, $course_review );
		} else {
			do_action( 'masteriyo_before_trash_' . $object_type, $id, $course_review );
			wp_trash_comment( $id );
			$course_review->set_status( 'trash' );
			do_action( 'masteriyo_before_trash_' . $object_type, $id, $course_review );
		}
	}

	/**
	 * Read course review data. Can be overridden by child classes to load other props.
	 *
	 * @since 0.1.0
	 *
	 * @param User $course_review Course review object.
	 */
	protected function read_comment_data( &$course_review ) {}

	/**
	 * Read extra data associated with the course review.
	 *
	 * @since 0.1.0
	 *
	 * @param CourseReview $course_review course review object.
	 */
	protected function read_extra_data( &$course_review ) {
		$meta_values = $this->read_meta( $course_review );

		foreach ( $course_review->get_extra_data_keys() as $key ) {
			$function = 'set_' . $key;
			if ( is_callable( array( $course_review, $function ) )
				&& isset( $meta_values[ '_' . $key ] ) ) {
				$course_review->{$function}( $meta_values[ '_' . $key ] );
			}
		}
	}
}
