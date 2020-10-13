<?php
/**
 * Repository
 */

namespace ThemeGrill\Masteriyo\Repository;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Models\Lesson;

class LessonRepository extends AbstractRepository implements RepositoryInterface {

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 3.0.0
	 * @var array
	 */
	protected $internal_meta_keys = array(
		'_featured'       => 'featured',
		'_category_ids'   => 'category_ids',
		'_tag_ids'       => 'tag_ids',
		'_thumbnail_id'   => 'featured_image'
	);

	/**
	 * Create a lesson in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $lesson Lesson object.
	 */
	public function create( Model &$lesson ) {
		if ( ! $lesson->get_date_created( 'edit' ) ) {
			$lesson->set_date_created( current_time( 'mysql', true ) );
		}

		$id = wp_insert_post(
			apply_filters(
				'masteriyo_new_lesson_data',
				array(
					'post_type'      => 'lesson',
					'post_status'    => $lesson->get_status() ? $lesson->get_status() : 'publish',
					'post_author'    => get_current_user_id(),
					'post_title'     => $lesson->get_name() ? $lesson->get_name() : __( 'Lesson', 'masteriyo' ),
					'post_content'   => $lesson->get_description(),
					'post_excerpt'   => $lesson->get_short_description(),
					'post_parent'    => $lesson->get_parent_id(),
					'comment_status' => $lesson->get_reviews_allowed() ? 'open' : 'closed',
					'ping_status'    => 'closed',
					'menu_order'     => $lesson->get_menu_order(),
					'post_password'  => $lesson->get_post_password( 'edit' ),
					'post_date'      => $lesson->get_date_created( 'edit' ),
					'post_date_gmt'  => $lesson->get_date_created( 'edit' ),
					'post_name'      => $lesson->get_slug( 'edit' )
				),
				$lesson
			)
		);

		if ( $id && ! is_wp_error( $id ) ) {
			$lesson->set_id( $id );
			$this->update_post_meta( $lesson, true );
			$this->update_terms( $lesson, true );
			// TODO Invalidate caches.

			$lesson->save_meta_data();
			$lesson->apply_changes();

			do_action( 'masteriyo_new_lesson', $id, $lesson );
		}

	}

	/**
	 * Read a lesson.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $lesson Cource object.
	 * @throws Exception If invalid lesson.
	 */
	public function read( Model &$lesson ) {
		$lesson_post = get_post( $lesson->get_id() );

		if ( ! $lesson->get_id() || ! $lesson_post || 'lesson' !== $lesson_post->post_type ) {
			throw new \Exception( __( 'Invalid lesson.', 'masteriyo' ) );
		}

		$lesson->set_props( array(
			'name'              => $lesson_post->post_title,
			'slug'              => $lesson_post->post_name,
			'date_created'      => $lesson_post->post_date_gmt,
			'date_modified'     => $lesson_post->post_modified_gmt,
			'status'            => $lesson_post->post_status,
			'description'       => $lesson_post->post_content,
			'short_description' => $lesson_post->post_excerpt,
			'parent_id'         => $lesson_post->post_parent,
			'menu_order'        => $lesson_post->menu_order,
			'post_password'     => $lesson_post->post_password,
			'reviews_allowed'   => 'open' === $lesson_post->comment_status,
		) );

		$this->read_lesson_data( $lesson );
		$this->read_extra_data( $lesson );
		$lesson->set_object_read( true );

		do_action( 'masteriyo_lesson_read', $lesson->get_id(), $lesson );
	}

	/**
	 * Update a lesson in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $lesson Lesson object.
	 *
	 * @return void
	 */
	public function update( Model &$lesson ) {
		$changes = $lesson->get_changes();

		$post_data_keys = array(
			'description',
			'short_description',
			'name',
			'parent_id',
			'reviews_allowed',
			'status',
			'menu_order',
			'date_created',
			'date_modified',
			'slug'
		);

		// Only update the post when the post data changes.
		if ( array_intersect( $post_data_keys, array_keys( $changes ) ) ) {
			$post_data = array(
				'post_content'   => $lesson->get_description( 'edit' ),
				'post_excerpt'   => $lesson->get_short_description( 'edit' ),
				'post_title'     => $lesson->get_name( 'edit' ),
				'post_parent'    => $lesson->get_parent_id( 'edit' ),
				'comment_status' => $lesson->get_reviews_allowed( 'edit' ) ? 'open' : 'closed',
				'post_status'    => $lesson->get_status( 'edit' ) ? $lesson->get_status( 'edit' ) : 'publish',
				'menu_order'     => $lesson->get_menu_order( 'edit' ),
				'post_password'  => $lesson->get_post_password( 'edit' ),
				'post_name'      => $lesson->get_slug( 'edit' ),
				'post_type'      => 'lesson',
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
				$GLOBALS['wpdb']->update( $GLOBALS['wpdb']->posts, $post_data, array( 'ID' => $lesson->get_id() ) );
				clean_post_cache( $lesson->get_id() );
			} else {
				wp_update_post( array_merge( array( 'ID' => $lesson->get_id() ), $post_data ) );
			}
			$lesson->read_meta_data( true ); // Refresh internal meta data, in case things were hooked into `save_post` or another WP hook.
		} else { // Only update post modified time to record this save event.
			$GLOBALS['wpdb']->update(
				$GLOBALS['wpdb']->posts,
				array(
					'post_modified'     => current_time( 'mysql' ),
					'post_modified_gmt' => current_time( 'mysql', true ),
				),
				array(
					'ID' => $lesson->get_id(),
				)
			);
			clean_post_cache( $lesson->get_id() );
		}

		$this->update_post_meta( $lesson );

		$lesson->apply_changes();

		do_action( 'masteriyo_update_lesson', $lesson->get_id(), $lesson );
	}

	/**
	 * Delete a lesson from the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $lesson Lesson object.
	 * @param array $args	Array of args to pass.alert-danger
	 */
	public function delete( Model &$lesson, $args = array() ) {
		$id          = $lesson->get_id();
		$object_type = $lesson->get_object_type();

		$args = array_merge( array(
			'force_delete' => false,
		), $args );

		if ( ! $id ) {
			return;
		}

		if ( $args['force_delete'] ) {
			do_action( 'masteriyo_before_delete_' . $object_type, $id, $lesson );
			wp_delete_post( $id );
			$lesson->set_id( 0 );
			do_action( 'masteriyo_after_delete_' . $object_type, $id, $lesson );
		} else {
			do_action( 'masteriyo_before_trash_' . $object_type, $id, $lesson );
			wp_trash_post( $id );
			$lesson->set_status( 'trash' );
			do_action( 'masteriyo_before_trash_' . $object_type, $id, $lesson );
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

			if ( empty( $categories ) && get_option( "default_lesson_cat", 0 ) ) {
				$categories = array( get_option( "default_lesson_cat", 0 ) );
			}

			wp_set_post_terms( $model->get_id(), $categories, "lesson_cat", false );
		}

		if ( $force || array_key_exists( 'tag_ids', $changes ) ) {
			wp_set_post_terms( $model->get_id(), $model->get_tag_ids( 'edit' ), "lesson_tag", false );
		}

		if ( $force || array_key_exists( 'difficulty_ids', $changes ) ) {
			wp_set_post_terms( $model->get_id(), $model->get_difficulty_ids( 'edit' ), "lesson_difficulty", false );
		}
	}

	/**
	 * Read lesson data. Can be overridden by child classes to load other props.
	 *
	 * @since 0.1.0
	 *
	 * @param Lesson $lesson lesson object.
	 */
	protected function read_lesson_data( &$lesson ) {
		$id              = $lesson->get_id();
		$meta_values     = $this->read_meta( $lesson );;
		$set_props       = array();

		$meta_values = array_reduce( $meta_values, function( $result, $meta_value ) {
			$result[ $meta_value->key ][] = $meta_value->value;
			return $result;
		}, array() );

		foreach( $this->internal_meta_keys as $meta_key => $prop ) {
			$meta_value         = isset( $meta_values[ $meta_key ][0] ) ? $meta_values[ $meta_key ][0] : null;
			$set_props[ $prop ] = maybe_unserialize( $meta_value ); // get_post_meta only unserializes single values.
		}

		$set_props['category_ids']   = $this->get_term_ids( $lesson, 'lesson_cat' );
		$set_props['tag_ids']        = $this->get_term_ids( $lesson, 'lesson_tag' );
		$set_props['difficulty_ids'] = $this->get_term_ids( $lesson, 'lesson_difficulty' );

		$lesson->set_props( $set_props );
	}

	/**
	 * Read extra data associated with the lesson, like button text or lesson URL for external lessons.
	 *
	 * @since 0.1.0
	 *
	 * @param Lesson $lesson lesson object.
	 */
	protected function read_extra_data( &$lesson ) {
		$meta_values = $this->read_meta( $lesson );

		foreach ( $lesson->get_extra_data_keys() as $key ) {
			$function = 'set_' . $key;
			if ( is_callable( array( $lesson, $function ) )
				&& isset( $meta_values[ '_' . $key ] ) ) {
				$lesson->{$function}( $meta_values[ '_' . $key ] );
			}
		}
	}
}
