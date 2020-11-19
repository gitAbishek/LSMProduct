<?php
/**
 * Question repository.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Repository
 */

namespace ThemeGrill\Masteriyo\Repository;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Models\Question;

/**
 * Question repository class.
 *
 * @since 0.1.0
 */
class QuestionRepository extends AbstractRepository implements RepositoryInterface {

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $internal_meta_keys = array(
		'_type'              => 'type',
		'_answer_required'   => 'answer_required',
		'_randomize'         => 'randomize',
		'_points'            => 'points',
		'_positive_feedback' => 'positive_feedback',
		'_negative_feedback' => 'negative_feedback',
		'_feedback'          => 'feedback',
	);

	/**
	 * Create a question in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $question Question object.
	 */
	public function create( Model &$question ) {
		if ( ! $question->get_date_created( 'edit' ) ) {
			$question->set_date_created( current_time( 'mysql', true ) );
		}

		$id = wp_insert_post(
			apply_filters(
				'masteriyo_new_question_data',
				array(
					'post_type'      => 'question',
					'post_status'    => $question->get_status() ? $question->get_status() : 'publish',
					'post_author'    => get_current_user_id(),
					'post_title'     => $question->get_name() ? $question->get_name() : __( 'Question', 'masteriyo' ),
					'post_content'   => serialize( $question->get_answers() ),
					'post_excerpt'   => $question->get_description(),
					'ping_status'    => 'closed',
					'post_date'      => $question->get_date_created( 'edit' ),
					'post_date_gmt'  => $question->get_date_created( 'edit' ),
					'post_name'      => $question->get_slug( 'edit' )
				),
				$question
			)
		);

		if ( $id && ! is_wp_error( $id ) ) {
			$question->set_id( $id );
			$this->update_post_meta( $question, true );
			// $this->update_terms( $question, true );
			// TODO Invalidate caches.

			$question->save_meta_data();
			$question->apply_changes();

			do_action( 'masteriyo_new_question', $id, $question );
		}
	}

	/**
	 * Read a question.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $question Cource object.
	 * @throws Exception If invalid question.
	 */
	public function read( Model &$question ) {
		$question_post = get_post( $question->get_id() );

		if ( ! $question->get_id() || ! $question_post || 'question' !== $question_post->post_type ) {
			throw new \Exception( __( 'Invalid question.', 'masteriyo' ) );
		}

		$question->set_props( array(
			'name'          => $question_post->post_title,
			'slug'          => $question_post->post_name,
			'date_created'  => $question_post->post_date_gmt,
			'date_modified' => $question_post->post_modified_gmt,
			'status'        => $question_post->post_status,
			'answers'       => maybe_unserialize( $question_post->post_content ),
			'description'   => $question_post->post_excerpt,
		) );

		$this->read_question_data( $question );
		$this->read_extra_data( $question );
		$question->set_object_read( true );

		do_action( 'masteriyo_question_read', $question->get_id(), $question );
	}

	/**
	 * Update a question in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $question Question object.
	 *
	 * @return void
	 */
	public function update( Model &$question ) {
		$changes = $question->get_changes();

		$post_data_keys = array(
			'description',
			'answers',
			'name',
			'status',
			'date_created',
			'date_modified',
			'slug'
		);

		// Only update the post when the post data changes.
		if ( array_intersect( $post_data_keys, array_keys( $changes ) ) ) {
			$post_data = array(
				'post_content'   => $question->get_answers( 'edit' ),
				'post_excerpt'   => $question->get_description( 'edit' ),
				'post_title'     => $question->get_name( 'edit' ),
				'post_status'    => $question->get_status( 'edit' ) ? $question->get_status( 'edit' ) : 'publish',
				'post_name'      => $question->get_slug( 'edit' ),
				'post_type'      => 'question',
			);

			/**
			 * When updating this object, to prevent infinite loops, use $wpdb
			 * to update data, since wp_update_post spawns more calls to the
			 * save_post action.
			 *
			 * This ensures hooks are fired by either WP itself (admin screen save),
			 * or an update purely from CRUD.
			 */
			if ( doing_action( 'save_post' ) ) {
				// TODO Abstract the $wpdb WordPress class.
				$GLOBALS['wpdb']->update( $GLOBALS['wpdb']->posts, $post_data, array( 'ID' => $question->get_id() ) );
				clean_post_cache( $question->get_id() );
			} else {
				wp_update_post( array_merge( array( 'ID' => $question->get_id() ), $post_data ) );
			}
			$question->read_meta_data( true ); // Refresh internal meta data, in case things were hooked into `save_post` or another WP hook.
		} else { // Only update post modified time to record this save event.
			$GLOBALS['wpdb']->update(
				$GLOBALS['wpdb']->posts,
				array(
					'post_modified'     => current_time( 'mysql' ),
					'post_modified_gmt' => current_time( 'mysql', true ),
				),
				array(
					'ID' => $question->get_id(),
				)
			);
			clean_post_cache( $question->get_id() );
		}

		$this->update_post_meta( $question );

		$question->apply_changes();

		do_action( 'masteriyo_update_question', $question->get_id(), $question );
	}

	/**
	 * Delete a question from the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $question Question object.
	 * @param array $args	Array of args to pass.alert-danger
	 */
	public function delete( Model &$question, $args = array() ) {
		$id          = $question->get_id();
		$object_type = $question->get_object_type();

		$args = array_merge( array(
			'force_delete' => false,
		), $args );

		if ( ! $id ) {
			return;
		}

		if ( $args['force_delete'] ) {
			do_action( 'masteriyo_before_delete_' . $object_type, $id, $question );
			wp_delete_post( $id );
			$question->set_id( 0 );
			do_action( 'masteriyo_after_delete_' . $object_type, $id, $question );
		} else {
			do_action( 'masteriyo_before_trash_' . $object_type, $id, $question );
			wp_trash_post( $id );
			$question->set_status( 'trash' );
			do_action( 'masteriyo_before_trash_' . $object_type, $id, $question );
		}
	}

	/**
	 * For all stored terms in all taxonomies, save them to the DB.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $model Model object.
	 * @param bool       $force Force update. Used during create.
	 */
	protected function update_terms( &$model, $force = false ) {
		$changes     = $model->get_changes();

		if ( $force || array_key_exists( 'category_ids', $changes ) ) {
			$categories = $model->get_category_ids( 'edit' );

			if ( empty( $categories ) && get_option( "default_question_cat", 0 ) ) {
				$categories = array( get_option( "default_question_cat", 0 ) );
			}

			wp_set_post_terms( $model->get_id(), $categories, "question_cat", false );
		}

		if ( $force || array_key_exists( 'tag_ids', $changes ) ) {
			wp_set_post_terms( $model->get_id(), $model->get_tag_ids( 'edit' ), "question_tag", false );
		}
	}

	/**
	 * Read question data. Can be overridden by child classes to load other props.
	 *
	 * @since 0.1.0
	 *
	 * @param Question $question question object.
	 */
	protected function read_question_data( &$question ) {
		$id              = $question->get_id();
		$meta_values     = $this->read_meta( $question );
		$set_props       = array();

		$meta_values = array_reduce( $meta_values, function( $result, $meta_value ) {
			$result[ $meta_value->key ][] = $meta_value->value;
			return $result;
		}, array() );

		foreach( $this->internal_meta_keys as $meta_key => $prop ) {
			$meta_value         = isset( $meta_values[ $meta_key ][0] ) ? $meta_values[ $meta_key ][0] : null;
			$set_props[ $prop ] = maybe_unserialize( $meta_value ); // get_post_meta only unserializes single values.
		}

		$question->set_props( $set_props );
	}

	/**
	 * Read extra data associated with the question, like button text or question URL for external questions.
	 *
	 * @since 0.1.0
	 *
	 * @param Question $question question object.
	 */
	protected function read_extra_data( &$question ) {
		$meta_values = $this->read_meta( $question );

		foreach ( $question->get_extra_data_keys() as $key ) {
			$function = 'set_' . $key;
			if ( is_callable( array( $question, $function ) )
				&& isset( $meta_values[ '_' . $key ] ) ) {
				$question->{$function}( $meta_values[ '_' . $key ] );
			}
		}
	}
}
