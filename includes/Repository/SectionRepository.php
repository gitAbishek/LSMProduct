<?php
/**
 * SectionRepository class.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Repository;
 */

namespace ThemeGrill\Masteriyo\Repository;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Models\Section;

/**
 * SectionRepository class.
 */
class SectionRepository extends AbstractRepository implements RepositoryInterface {

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 0.1.0
	 * @var array
	 */
	protected $internal_meta_keys = array();

	/**
	 * Create a section in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $section Section object.
	 */
	public function create( Model &$section ) {
		if ( ! $section->get_date_created( 'edit' ) ) {
			$section->set_date_created( current_time( 'mysql', true ) );
		}

		$id = wp_insert_post(
			apply_filters(
				'masteriyo_new_section_data',
				array(
					'post_type'      => 'section',
					'post_status'    => 'publish',
					'post_author'    => get_current_user_id(),
					'post_title'     => $section->get_name(),
					'post_content'   => $section->get_description(),
					'post_parent'    => $section->get_parent_id(),
					'comment_status' => 'closed',
					'ping_status'    => 'closed',
					'menu_order'     => $section->get_menu_order(),
					'post_date'      => $section->get_date_created( 'edit' ),
					'post_date_gmt'  => $section->get_date_created( 'edit' ),
				),
				$section
			)
		);

		if ( $id && ! is_wp_error( $id ) ) {
			$section->set_id( $id );
			$this->update_post_meta( $section, true );
			// TODO Invalidate caches.

			$section->save_meta_data();
			$section->apply_changes();

			do_action( 'masteriyo_new_section', $id, $section );
		}

	}

	/**
	 * Read a section.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $section Section object.
	 * @throws \Exception If invalid section.
	 */
	public function read( Model &$section ) {
		$section_post = get_post( $section->get_id() );

		if ( ! $section->get_id() || ! $section_post || 'section' !== $section_post->post_type ) {
			throw new \Exception( __( 'Invalid section.', 'masteriyo' ) );
		}

		$section->set_props(
			array(
				'name'         => $section_post->post_title,
				'date_created'  => $section_post->post_date_gmt,
				'date_modified' => $section_post->post_modified_gmt,
				'description'   => $section_post->post_content,
				'parent_id'     => $section_post->post_parent,
				'menu_order'    => $section_post->menu_order,
			)
		);

		$this->read_section_data( $section );
		$this->read_extra_data( $section );
		$section->set_object_read( true );

		do_action( 'masteriyo_section_read', $section->get_id(), $section );
	}

	/**
	 * Update a section in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $section Section object.
	 *
	 * @return void
	 */
	public function update( Model &$section ) {
		$changes = $section->get_changes();

		$post_data_keys = array(
			'description',
			'title',
			'parent_id',
			'menu_order',
			'date_created',
			'date_modified',
		);

		// Only update the post when the post data changes.
		if ( array_intersect( $post_data_keys, array_keys( $changes ) ) ) {
			$post_data = array(
				'post_content'   => $section->get_description( 'edit' ),
				'post_title'     => $section->get_name( 'edit' ),
				'post_parent'    => $section->get_parent_id( 'edit' ),
				'comment_status' => 'closed',
				'post_status'    => 'publish',
				'menu_order'     => $section->get_menu_order( 'edit' ),
				'post_type'      => 'section',
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
				$GLOBALS['wpdb']->update( $GLOBALS['wpdb']->posts, $post_data, array( 'ID' => $section->get_id() ) );
				clean_post_cache( $section->get_id() );
			} else {
				wp_update_post( array_merge( array( 'ID' => $section->get_id() ), $post_data ) );
			}
			$section->read_meta_data( true ); // Refresh internal meta data, in case things were hooked into `save_post` or another WP hook.
		} else { // Only update post modified time to record this save event.
			$GLOBALS['wpdb']->update(
				$GLOBALS['wpdb']->posts,
				array(
					'post_modified'     => current_time( 'mysql' ),
					'post_modified_gmt' => current_time( 'mysql', true ),
				),
				array(
					'ID' => $section->get_id(),
				)
			);
			clean_post_cache( $section->get_id() );
		}

		$this->update_post_meta( $section );

		$section->apply_changes();

		do_action( 'masteriyo_update_section', $section->get_id(), $section );
	}

	/**
	 * Delete a section from the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $section Section object.
	 * @param array $args   Array of args to pass.alert-danger.
	 */
	public function delete( Model &$section, $args = array() ) {
		$id          = $section->get_id();
		$object_type = $section->get_object_type();

		$args = array_merge(
			array(
				'force_delete' => false,
			),
			$args
		);

		if ( ! $id ) {
			return;
		}

		if ( $args['force_delete'] ) {
			do_action( 'masteriyo_before_delete_' . $object_type, $id, $section );
			wp_delete_post( $id );
			$section->set_id( 0 );
			do_action( 'masteriyo_after_delete_' . $object_type, $id, $section );
		} else {
			do_action( 'masteriyo_before_trash_' . $object_type, $id, $section );
			wp_trash_post( $id );
			$section->set_status( 'trash' );
			do_action( 'masteriyo_before_trash_' . $object_type, $id, $section );
		}
	}

	/**
	 * Read section data. Can be overridden by child classes to load other props.
	 *
	 * @since 0.1.0
	 *
	 * @param Section $section Section object.
	 */
	protected function read_section_data( &$section ) {
		$id          = $section->get_id();
		$meta_values = $this->read_meta( $section );

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

		$section->set_props( $set_props );
	}

	/**
	 * Read extra data associated with the section, like button text or section URL for external sections.
	 *
	 * @since 0.1.0
	 *
	 * @param Section $section Section object.
	 */
	protected function read_extra_data( &$section ) {
		$meta_values = $this->read_meta( $section );

		foreach ( $section->get_extra_data_keys() as $key ) {
			$function = 'set_' . $key;

			if ( is_callable( array( $section, $function ) )
				&& isset( $meta_values[ '_' . $key ] ) ) {
				$section->{$function}( $meta_values[ '_' . $key ] );
			}
		}
	}
}
