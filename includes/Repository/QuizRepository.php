<?php
/**
 * Quiz repository.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Repository
 */

namespace ThemeGrill\Masteriyo\Repository;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Models\Quiz;

/**
 * Quiz repository class.
 *
 * @since 0.1.0
 */
class QuizRepository extends AbstractRepository implements RepositoryInterface {

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $internal_meta_keys = array(
	);

	/**
	 * Create a quiz in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $quiz Quiz object.
	 */
	public function create( Model &$quiz ) {
		if ( ! $quiz->get_date_created( 'edit' ) ) {
			$quiz->set_date_created( current_time( 'mysql', true ) );
		}

		$id = wp_insert_post(
			apply_filters(
				'masteriyo_new_quiz_data',
				array(
					'post_type'     => 'quiz',
					'post_status'   => $quiz->get_status() ? $quiz->get_status() : 'publish',
					'post_author'   => get_current_user_id(),
					'post_title'    => $quiz->get_name() ? $quiz->get_name() : __( 'Quiz', 'masteriyo' ),
					'post_content'  => $quiz->get_description(),
					'post_excerpt'  => $quiz->get_short_description(),
					'post_parent'   => $quiz->get_parent_id(),
					'ping_status'   => 'closed',
					'menu_order'    => $quiz->get_menu_order(),
					'post_date'     => $quiz->get_date_created( 'edit' ),
					'post_date_gmt' => $quiz->get_date_created( 'edit' ),
					'post_name'     => $quiz->get_slug( 'edit' )
				),
				$quiz
			)
		);

		if ( $id && ! is_wp_error( $id ) ) {
			$quiz->set_id( $id );
			$this->update_post_meta( $quiz, true );
			// $this->update_terms( $quiz, true );
			// TODO Invalidate caches.

			$quiz->save_meta_data();
			$quiz->apply_changes();

			do_action( 'masteriyo_new_quiz', $id, $quiz );
		}

	}

	/**
	 * Read a quiz.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $quiz Cource object.
	 * @throws Exception If invalid quiz.
	 */
	public function read( Model &$quiz ) {
		$quiz_post = get_post( $quiz->get_id() );

		if ( ! $quiz->get_id() || ! $quiz_post || 'quiz' !== $quiz_post->post_type ) {
			throw new \Exception( __( 'Invalid quiz.', 'masteriyo' ) );
		}

		$quiz->set_props( array(
			'name'              => $quiz_post->post_title,
			'slug'              => $quiz_post->post_name,
			'date_created'      => $quiz_post->post_date_gmt,
			'date_modified'     => $quiz_post->post_modified_gmt,
			'status'            => $quiz_post->post_status,
			'parent_id'         => $quiz_post->post_parent,
			'menu_order'        => $quiz_post->menu_order,
			'description'       => $quiz_post->post_content,
			'short_description' => $quiz_post->post_excerpt,
		) );

		$this->read_quiz_data( $quiz );
		$this->read_extra_data( $quiz );
		$quiz->set_object_read( true );

		do_action( 'masteriyo_quiz_read', $quiz->get_id(), $quiz );
	}

	/**
	 * Update a quiz in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $quiz Quiz object.
	 *
	 * @return void
	 */
	public function update( Model &$quiz ) {
		$changes = $quiz->get_changes();

		$post_data_keys = array(
			'description',
			'short_description',
			'name',
			'parent_id',
			'status',
			'date_created',
			'date_modified',
			'slug'
		);

		// Only update the post when the post data changes.
		if ( array_intersect( $post_data_keys, array_keys( $changes ) ) ) {
			$post_data = array(
				'post_content' => $quiz->get_description( 'edit' ),
				'post_excerpt' => $quiz->get_short_description( 'edit' ),
				'post_title'   => $quiz->get_name( 'edit' ),
				'post_status'  => $quiz->get_status( 'edit' ) ? $quiz->get_status( 'edit' ) : 'publish',
				'post_name'    => $quiz->get_slug( 'edit' ),
				'post_parent'  => $quiz->get_parent_id(),
				'post_type'    => 'quiz',
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
				$GLOBALS['wpdb']->update( $GLOBALS['wpdb']->posts, $post_data, array( 'ID' => $quiz->get_id() ) );
				clean_post_cache( $quiz->get_id() );
			} else {
				wp_update_post( array_merge( array( 'ID' => $quiz->get_id() ), $post_data ) );
			}
			$quiz->read_meta_data( true ); // Refresh internal meta data, in case things were hooked into `save_post` or another WP hook.
		} else { // Only update post modified time to record this save event.
			$GLOBALS['wpdb']->update(
				$GLOBALS['wpdb']->posts,
				array(
					'post_modified'     => current_time( 'mysql' ),
					'post_modified_gmt' => current_time( 'mysql', true ),
				),
				array(
					'ID' => $quiz->get_id(),
				)
			);
			clean_post_cache( $quiz->get_id() );
		}

		$this->update_post_meta( $quiz );

		$quiz->apply_changes();

		do_action( 'masteriyo_update_quiz', $quiz->get_id(), $quiz );
	}

	/**
	 * Delete a quiz from the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $quiz Quiz object.
	 * @param array $args	Array of args to pass.alert-danger
	 */
	public function delete( Model &$quiz, $args = array() ) {
		$id          = $quiz->get_id();
		$object_type = $quiz->get_object_type();

		$args = array_merge( array(
			'force_delete' => false,
		), $args );

		if ( ! $id ) {
			return;
		}

		if ( $args['force_delete'] ) {
			do_action( 'masteriyo_before_delete_' . $object_type, $id, $quiz );
			wp_delete_post( $id );
			$quiz->set_id( 0 );
			do_action( 'masteriyo_after_delete_' . $object_type, $id, $quiz );
		} else {
			do_action( 'masteriyo_before_trash_' . $object_type, $id, $quiz );
			wp_trash_post( $id );
			$quiz->set_status( 'trash' );
			do_action( 'masteriyo_before_trash_' . $object_type, $id, $quiz );
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

			if ( empty( $categories ) && get_option( "default_quiz_cat", 0 ) ) {
				$categories = array( get_option( "default_quiz_cat", 0 ) );
			}

			wp_set_post_terms( $model->get_id(), $categories, "quiz_cat", false );
		}

		if ( $force || array_key_exists( 'tag_ids', $changes ) ) {
			wp_set_post_terms( $model->get_id(), $model->get_tag_ids( 'edit' ), "quiz_tag", false );
		}
	}

	/**
	 * Read quiz data. Can be overridden by child classes to load other props.
	 *
	 * @since 0.1.0
	 *
	 * @param Quiz $quiz quiz object.
	 */
	protected function read_quiz_data( &$quiz ) {
		$id              = $quiz->get_id();
		$meta_values     = $this->read_meta( $quiz );;
		$set_props       = array();

		$meta_values = array_reduce( $meta_values, function( $result, $meta_value ) {
			$result[ $meta_value->key ][] = $meta_value->value;
			return $result;
		}, array() );

		foreach( $this->internal_meta_keys as $meta_key => $prop ) {
			$meta_value         = isset( $meta_values[ $meta_key ][0] ) ? $meta_values[ $meta_key ][0] : null;
			$set_props[ $prop ] = maybe_unserialize( $meta_value ); // get_post_meta only unserializes single values.
		}

		$quiz->set_props( $set_props );
	}

	/**
	 * Read extra data associated with the quiz, like button text or quiz URL for external quizs.
	 *
	 * @since 0.1.0
	 *
	 * @param Quiz $quiz quiz object.
	 */
	protected function read_extra_data( &$quiz ) {
		$meta_values = $this->read_meta( $quiz );

		foreach ( $quiz->get_extra_data_keys() as $key ) {
			$function = 'set_' . $key;
			if ( is_callable( array( $quiz, $function ) )
				&& isset( $meta_values[ '_' . $key ] ) ) {
				$quiz->{$function}( $meta_values[ '_' . $key ] );
			}
		}
	}
}
