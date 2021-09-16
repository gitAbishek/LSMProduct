<?php
/**
 * CourseCategoryRepository class.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Repository;
 */

namespace Masteriyo\Repository;

use Masteriyo\Database\Model;
use Masteriyo\Models\CourseCategory;

/**
 * CourseCategoryRepository class.
 */
class CourseCategoryRepository extends AbstractRepository implements RepositoryInterface {

	/**
	 * Meta type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $meta_type = 'term';

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 3.0.0
	 * @var array
	 */
	protected $internal_meta_keys = array(
		'_display' => 'display',
	);

	/**
	 * Create a course_cat in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $course_cat Course_cat object.
	 */
	public function create( Model &$course_cat ) {

		$ids = wp_insert_term(
			$course_cat->get_name(),
			'course_cat',
			apply_filters(
				'masteriyo_new_course_cat_data',
				array(
					'description' => $course_cat->get_description(),
					'parent'      => $course_cat->get_parent_id(),
					'slug'        => $course_cat->get_slug( 'edit' ),
				),
				$course_cat
			)
		);

		if ( $ids && ! is_wp_error( $ids ) ) {
			$course_cat->set_id( isset( $ids['term_id'] ) ? $ids['term_id'] : 0 );
			$this->update_term_meta( $course_cat, true );
			// TODO Invalidate caches.

			$course_cat->save_meta_data();
			$course_cat->apply_changes();

			do_action( 'masteriyo_new_course_cat', $ids, $course_cat );
		} elseif ( isset( $ids->error_data['term_exists'] ) ) {
			$course_cat->set_id( $ids->error_data['term_exists'] );
			do_action( 'masteriyo_old_course_cat', $ids, $course_cat );
		}

	}

	/**
	 * Read a course_cat.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $course_cat Cource object.
	 *
	 * @throws \Exception If invalid course_cat.
	 */
	public function read( Model &$course_cat ) {
		$term = get_term( $course_cat->get_id() );

		if ( ! $course_cat->get_id() || ! $term || 'course_cat' !== $term->taxonomy ) {
			throw new \Exception( __( 'Invalid course_cat.', 'masteriyo' ) );
		}

		$course_cat->set_props(
			array(
				'name'             => $term->name,
				'slug'             => $term->slug,
				'term_group'       => $term->term_group,
				'term_taxonomy_id' => $term->term_taxonomy_id,
				'taxonomy'         => $term->taxonomy,
				'description'      => $term->description,
				'parent_id'        => $term->parent,
				'count'            => $term->count,
			)
		);

		$this->read_course_cat_data( $course_cat );
		$this->read_extra_data( $course_cat );
		$course_cat->set_object_read( true );

		do_action( 'masteriyo_course_cat_read', $course_cat->get_id(), $course_cat );
	}

	/**
	 * Update a course_cat in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $course_cat Course_cat object.
	 *
	 * @return void
	 */
	public function update( Model &$course_cat ) {
		$changes = $course_cat->get_changes();

		$term_data_keys = array(
			'name',
			'slug',
			'description',
			'parent_id',
		);

		// Only update the post when the post data changes.
		if ( array_intersect( $term_data_keys, array_keys( $changes ) ) ) {
			$term_data = array(
				'name' => $course_cat->get_name( 'edit' ),
				'slug' => $course_cat->get_slug( 'edit' ),
			);

			$term_taxonomy_data = array(
				'description' => $course_cat->get_description( 'edit' ),
				'parent'      => $course_cat->get_parent_id( 'edit' ),
			);

			/**
			 * When updating this object, to prevent infinite loops, use $wpdb
			 * to update data, since wp_update_post spawns more calls to the
			 * save_post action.
			 *
			 * This ensures hooks are fired by either WP itself (admin screen save),
			 * or an update purely from CRUD.
			 */
			if ( doing_action( 'saved_term' ) ) {
				$term_taxonomy_data = array_merge( $term_taxonomy_data, array( 'taxonomy' => 'course_cat' ) );
				// TODO Abstract the $wpdb WordPress class.
				$GLOBALS['wpdb']->update( $GLOBALS['wpdb']->terms, $term_data, array( 'ID' => $course_cat->get_id() ) );
				$GLOBALS['wpdb']->update( $GLOBALS['wpdb']->posts, $term_taxonomy_data, array( 'ID' => $course_cat->get_id() ) );
				clean_term_cache( $course_cat->get_id() );
			} else {
				wp_update_term( $course_cat->get_id(), 'course_cat', array_merge( $term_data, $term_taxonomy_data ) );
			}
			$course_cat->read_meta_data( true ); // Refresh internal meta data, in case things were hooked into `save_post` or another WP hook.
		}

		$this->update_term_meta( $course_cat );

		$course_cat->apply_changes();

		do_action( 'masteriyo_update_course_cat', $course_cat->get_id(), $course_cat );
	}

	/**
	 * Delete a course_cat from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $course_cat Course_cat object.
	 * @param array $args   Array of args to pass.alert-danger.
	 */
	public function delete( Model &$course_cat, $args = array() ) {
		$id          = $course_cat->get_id();
		$object_type = $course_cat->get_object_type();

		if ( ! $id ) {
			return;
		}

		do_action( 'masteriyo_before_delete_' . $object_type, $id, $course_cat );
		wp_delete_term( $id, $object_type );
		$course_cat->set_id( 0 );
		do_action( 'masteriyo_after_delete_' . $object_type, $id, $course_cat );

	}

	/**
	 * Read course_cat data. Can be overridden by child classes to load other props.
	 *
	 * @since 1.0.0
	 *
	 * @param CourseCategory $course_cat course_cat object.
	 */
	protected function read_course_cat_data( &$course_cat ) {
		$id          = $course_cat->get_id();
		$meta_values = $this->read_meta( $course_cat );

		$set_props = array();

		$meta_values = array_reduce(
			$meta_values,
			function( $result, $meta_value ) {
				$result[ $meta_value->key ][] = $meta_value->value;
				return $result;
			},
			array()
		);

		foreach ( $this->internal_meta_keys as $meta_key => $prop ) {
			$meta_value         = isset( $meta_values[ $meta_key ][0] ) ? $meta_values[ $meta_key ][0] : null;
			$set_props[ $prop ] = maybe_unserialize( $meta_value ); // get_post_meta only unserializes single values.
		}

		$course_cat->set_props( $set_props );
	}

	/**
	 * Read extra data associated with the course_cat, like button text or course_cat URL for external course_cats.
	 *
	 * @since 1.0.0
	 *
	 * @param CourseCategory $course_cat course_cat object.
	 */
	protected function read_extra_data( &$course_cat ) {
		$meta_values = $this->read_meta( $course_cat );

		foreach ( $course_cat->get_extra_data_keys() as $key ) {
			$function = 'set_' . $key;
			if ( is_callable( array( $course_cat, $function ) )
				&& isset( $meta_values[ '_' . $key ] ) ) {
				$course_cat->{$function}( $meta_values[ '_' . $key ] );
			}
		}
	}
}
