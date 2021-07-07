<?php
/**
 * CourseQuestionAnswer Repository class.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Repository;
 */

namespace ThemeGrill\Masteriyo\Repository;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Models\CourseQuestionAnswer;

/**
 * CourseQuestionAnswer Repository class.
 */
class CourseQuestionAnswerRepository extends AbstractRepository implements RepositoryInterface {
	/**
	* Meta type.
	*
	* @since 0.1.0
	*
	* @var string
	*/
	protected $meta_type = 'comment';

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 0.1.0
	 * @var array
	 */
	protected $internal_meta_keys = array();

	/**
	 * Create course question answer (comment) in database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $mto_course_qa Course question-answer object.
	 */
	public function create( Model &$mto_course_qa ) {
		$current_user = wp_get_current_user();

		if ( ! $mto_course_qa->get_created_at( 'edit' ) ) {
			$mto_course_qa->set_created_at( current_time( 'mysql', true ) );
		}

		if ( ! $mto_course_qa->get_ip_address( 'edit' ) ) {
			$mto_course_qa->set_ip_address( masteriyo_get_current_ip_address() );
		}

		if ( ! $mto_course_qa->get_agent( 'edit' ) ) {
			$mto_course_qa->set_agent( masteriyo_get_user_agent() );
		}

		if ( ! empty( $current_user ) ) {
			if ( ! $mto_course_qa->get_user_email( 'edit' ) ) {
				$mto_course_qa->set_user_email( $current_user->user_email );
			}

			if ( ! $mto_course_qa->get_user_id( 'edit' ) ) {
				$mto_course_qa->set_user_id( $current_user->ID );
			}

			if ( ! $mto_course_qa->get_user_name( 'edit' ) ) {
				$mto_course_qa->set_user_name( $current_user->user_nicename );
			}

			if ( ! $mto_course_qa->get_user_url( 'edit' ) ) {
				$mto_course_qa->set_user_url( $current_user->user_url );
			}
		}

		$id = wp_insert_comment(
			apply_filters(
				'masteriyo_new_mto_course_qa_data',
				array(
					'comment_post_ID'      => $mto_course_qa->get_course_id(),
					'comment_author'       => $mto_course_qa->get_user_name( 'edit' ),
					'comment_author_email' => $mto_course_qa->get_user_email( 'edit' ),
					'comment_author_url'   => $mto_course_qa->get_user_url( 'edit' ),
					'comment_author_IP'    => $mto_course_qa->get_ip_address( 'edit' ),
					'comment_date'         => $mto_course_qa->get_created_at( 'edit' ),
					'comment_date_gmt'     => $mto_course_qa->get_created_at( 'edit' ),
					'comment_content'      => $mto_course_qa->get_content(),
					'comment_approved'     => $mto_course_qa->get_status( 'edit' ),
					'comment_agent'        => $mto_course_qa->get_agent( 'edit' ),
					'comment_type'         => $mto_course_qa->get_type( 'edit' ),
					'comment_parent'       => $mto_course_qa->get_parent( 'edit' ),
					'user_id'              => $mto_course_qa->get_user_id( 'edit' ),
				),
				$mto_course_qa
			)
		);

		if ( $id && ! is_wp_error( $id ) ) {
			// Set comment status.
			wp_set_comment_status( $id, $mto_course_qa->get_status() );

			$mto_course_qa->set_id( $id );
			$this->update_comment_meta( $mto_course_qa, true );
			$mto_course_qa->save_meta_data();
			$mto_course_qa->apply_changes();

			do_action( 'masteriyo_new_mto_course_qa', $id, $mto_course_qa );
		}
	}

	/**
	 * Read a course question-answer.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $mto_course_qa course question-answer object.
	 *
	 * @throws \Exception If invalid course question-answer.
	 */
	public function read( Model &$mto_course_qa ) {
		$mto_course_qa_obj = get_comment( $mto_course_qa->get_id() );

		if ( ! $mto_course_qa_obj || 'mto_course_qa' !== $mto_course_qa_obj->comment_type ) {
			throw new \Exception( __( 'Invalid Course Question Answer.', 'masteriyo' ) );
		}

		// Map the comment status from numberical to word.
		$status = $mto_course_qa_obj->comment_approved;
		if ( '1' === $status ) {
			$status = 'approve';
		} elseif ( '0' === $status ) {
			$status = 'hold';
		}

		$mto_course_qa->set_props(
			array(
				'course_id'  => $mto_course_qa_obj->comment_post_ID,
				'user_name'  => $mto_course_qa_obj->comment_author,
				'user_email' => $mto_course_qa_obj->comment_author_email,
				'user_url'   => $mto_course_qa_obj->comment_author_url,
				'ip_address' => $mto_course_qa_obj->comment_author_IP,
				'created_at' => $mto_course_qa_obj->comment_date_gmt,
				'content'    => $mto_course_qa_obj->comment_content,
				'status'     => $status,
				'agent'      => $mto_course_qa_obj->comment_agent,
				'parent'     => $mto_course_qa_obj->comment_parent,
				'user_id'    => $mto_course_qa_obj->user_id,
			)
		);

		$this->read_comment_data( $mto_course_qa );
		$this->read_extra_data( $mto_course_qa );
		$mto_course_qa->set_object_read( true );

		do_action( 'masteriyo_mto_course_qa_read', $mto_course_qa->get_id(), $mto_course_qa );
	}

	/**
	 * Update a course question-answer in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $mto_course_qa course question-answer object.
	 *
	 * @return void
	 */
	public function update( Model &$mto_course_qa ) {
		$changes = $mto_course_qa->get_changes();

		$mto_course_qa_data_keys = array(
			'author_name',
			'author_email',
			'author_url',
			'ip_address',
			'date_created',
			'content',
			'status',
			'parent',
		);

		// Only update the course question-answer when the course question-answer data changes.
		if ( array_intersect( $mto_course_qa_data_keys, array_keys( $changes ) ) ) {
			$mto_course_qa_data = array(
				'comment_author'       => $mto_course_qa->get_author_name( 'edit' ),
				'comment_author_email' => $mto_course_qa->get_author_email( 'edit' ),
				'comment_author_url'   => $mto_course_qa->get_author_url( 'edit' ),
				'comment_author_IP'    => $mto_course_qa->get_ip_address( 'edit' ),
				'comment_content'      => $mto_course_qa->get_content( 'edit' ),
				'comment_approved'     => $mto_course_qa->get_status( 'edit' ),
				'comment_parent'       => $mto_course_qa->get_parent( 'edit' ),
				'user_id'              => $mto_course_qa->get_author_id( 'edit' ),
			);

			wp_update_comment( array_merge( array( 'comment_ID' => $mto_course_qa->get_id() ), $mto_course_qa_data ) );
		}

		$this->update_comment_meta( $mto_course_qa );
		$mto_course_qa->apply_changes();

		do_action( 'masteriyo_update_mto_course_qa', $mto_course_qa->get_id(), $mto_course_qa );
	}

	/**
	 * Delete a course question-answer from the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $mto_course_qa course question-answer object.
	 * @param array $args Array of args to pass.alert-danger.
	 */
	public function delete( Model &$mto_course_qa, $args = array() ) {
		$id          = $mto_course_qa->get_id();
		$object_type = $mto_course_qa->get_object_type();
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
			do_action( 'masteriyo_before_delete_' . $object_type, $id, $mto_course_qa );
			wp_delete_comment( $id, true );
			$mto_course_qa->set_id( 0 );
			do_action( 'masteriyo_after_delete_' . $object_type, $id, $mto_course_qa );
		} else {
			do_action( 'masteriyo_before_trash_' . $object_type, $id, $mto_course_qa );
			wp_trash_comment( $id );
			$mto_course_qa->set_status( 'trash' );
			do_action( 'masteriyo_before_trash_' . $object_type, $id, $mto_course_qa );
		}
	}

	/**
	 * Read course question-answer data. Can be overridden by child classes to load other props.
	 *
	 * @since 0.1.0
	 *
	 * @param User $mto_course_qa Course question-answer object.
	 */
	protected function read_comment_data( &$mto_course_qa ) {
		$meta_values = $this->read_meta( $mto_course_qa );
		$set_props   = array();
		$meta_values = array_reduce(
			$meta_values,
			function( $result, $meta_value ) {
				$result[ $meta_value->key ][] = $meta_value->value;
				return $result;
			},
			array()
		);

		foreach ( $this->internal_meta_keys as $prop => $meta_key ) {
			$meta_value         = isset( $meta_values[ $meta_key ][0] ) ? $meta_values[ $meta_key ][0] : null;
			$set_props[ $prop ] = maybe_unserialize( $meta_value ); // get_post_meta only unserializes single values.
		}

		$mto_course_qa->set_props( $set_props );
	}

	/**
	 * Read extra data associated with the course question-answer.
	 *
	 * @since 0.1.0
	 *
	 * @param CourseQuestionAnswer $mto_course_qa course question-answer object.
	 */
	protected function read_extra_data( &$mto_course_qa ) {
		$meta_values = $this->read_meta( $mto_course_qa );

		foreach ( $mto_course_qa->get_extra_data_keys() as $key ) {
			$function = 'set_' . $key;
			if ( is_callable( array( $mto_course_qa, $function ) )
				&& isset( $meta_values[ '_' . $key ] ) ) {
				$mto_course_qa->{$function}( $meta_values[ '_' . $key ] );
			}
		}
	}

	/**
	 * Fetch course question-answers.
	 *
	 * @since 0.1.0
	 *
	 * @param array $query_vars Query vars.
	 * @return CourseQuestionAnswer[]
	 */
	public function query( $query_vars ) {
		$args = $this->get_wp_query_args( $query_vars );

		// Set the comment type to course question answer.s
		$args['type'] = 'mto_course_qa';

		if ( isset( $query_vars['course_id'] ) ) {
			$args['post_id'] = $query_vars['course_id'];
		}

		if ( ! empty( $args['errors'] ) ) {
			$query = (object) array(
				'posts'         => array(),
				'found_posts'   => 0,
				'max_num_pages' => 0,
			);
		} else {
			$query = new \WP_Comment_Query( $args );
		}

		if ( isset( $query_vars['return'] ) && 'objects' === $query_vars['return'] && ! empty( $query->comments ) ) {
			// Prime caches before grabbing objects.
			update_comment_cache( $query->comments );
		}

		if ( isset( $query_vars['return'] ) && 'ids' === $query_vars['return'] ) {
			$mto_course_qa = $query->comments;
		} else {
			$mto_course_qa = array_filter( array_map( 'masteriyo_get_mto_course_qa', $query->comments ) );
		}

		if ( isset( $query_vars['paginate'] ) && $query_vars['paginate'] ) {
			return (object) array(
				'mto_course_qa' => $mto_course_qa,
				'total'         => $query->found_posts,
				'max_num_pages' => $query->max_num_pages,
			);
		}

		return $mto_course_qa;
	}
}
