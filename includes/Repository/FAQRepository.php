<?php
/**
 * FAQRepository class.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Repository;
 */

namespace ThemeGrill\Masteriyo\Repository;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Models\Faq;

/**
 * FAQRepository class.
 */
class FAQRepository extends AbstractRepository implements RepositoryInterface {

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 0.1.0
	 * @var array
	 */
	protected $internal_meta_keys = array();

	/**
	 * Create a faq in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $faq FAQ object.
	 */
	public function create( Model &$faq ) {
		if ( ! $faq->get_date_created( 'edit' ) ) {
			$faq->set_date_created( current_time( 'mysql', true ) );
		}

		$id = wp_insert_post(
			apply_filters(
				'masteriyo_new_faq_data',
				array(
					'post_type'      => 'faq',
					'post_status'    => 'publish',
					'post_author'    => get_current_user_id(),
					'post_title'     => $faq->get_name(),
					'post_content'   => $faq->get_description(),
					'post_parent'    => $faq->get_course_id(),
					'post_name'      => '',
					'comment_status' => 'closed',
					'ping_status'    => 'closed',
					'menu_order'     => $faq->get_menu_order(),
					'post_date'      => $faq->get_date_created( 'edit' ),
					'post_date_gmt'  => $faq->get_date_created( 'edit' ),
				),
				$faq
			)
		);

		if ( $id && ! is_wp_error( $id ) ) {
			$faq->set_id( $id );
			// TODO Invalidate caches.

			$faq->apply_changes();

			do_action( 'masteriyo_new_faq', $id, $faq );
		}

	}

	/**
	 * Read a FAQ.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $faq FAQ object.
	 * @throws \Exception If invalid faq.
	 */
	public function read( Model &$faq ) {
		$faq_post = get_post( $faq->get_id() );

		if ( ! $faq->get_id() || ! $faq_post || 'faq' !== $faq_post->post_type ) {
			throw new \Exception( __( 'Invalid FAQ.', 'masteriyo' ) );
		}

		$faq->set_props(
			array(
				'name'          => $faq_post->post_title,
				'date_created'  => $faq_post->post_date_gmt,
				'date_modified' => $faq_post->post_modified_gmt,
				'description'   => $faq_post->post_content,
				'course_id'     => $faq_post->post_parent,
				'menu_order'    => $faq_post->menu_order,
			)
		);
		$faq->set_object_read( true );

		do_action( 'masteriyo_faq_read', $faq->get_id(), $faq );
	}

	/**
	 * Update a faq in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $faq FAQ object.
	 *
	 * @return void
	 */
	public function update( Model &$faq ) {
		$changes = $faq->get_changes();

		$post_data_keys = array(
			'description',
			'name',
			'parent_id',
			'menu_order',
			'date_created',
			'date_modified',
		);

		// Only update the post when the post data changes.
		if ( array_intersect( $post_data_keys, array_keys( $changes ) ) ) {
			$post_data = array(
				'post_content'   => $faq->get_description( 'edit' ),
				'post_title'     => $faq->get_name( 'edit' ),
				'post_parent'    => $faq->get_course_id( 'edit' ),
				'comment_status' => 'closed',
				'post_status'    => 'publish',
				'menu_order'     => $faq->get_menu_order( 'edit' ),
				'post_type'      => 'faq',
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
				$GLOBALS['wpdb']->update( $GLOBALS['wpdb']->posts, $post_data, array( 'ID' => $faq->get_id() ) );
				clean_post_cache( $faq->get_id() );
			} else {
				wp_update_post( array_merge( array( 'ID' => $faq->get_id() ), $post_data ) );
			}
			$faq->read_meta_data( true ); // Refresh internal meta data, in case things were hooked into `save_post` or another WP hook.
		} else { // Only update post modified time to record this save event.
			$GLOBALS['wpdb']->update(
				$GLOBALS['wpdb']->posts,
				array(
					'post_modified'     => current_time( 'mysql' ),
					'post_modified_gmt' => current_time( 'mysql', true ),
				),
				array(
					'ID' => $faq->get_id(),
				)
			);
			clean_post_cache( $faq->get_id() );
		}

		$faq->apply_changes();

		do_action( 'masteriyo_update_faq', $faq->get_id(), $faq );
	}

	/**
	 * Delete a FAQ from the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $faq FAQ object.
	 * @param array $args   Array of args to pass.alert-danger.
	 */
	public function delete( Model &$faq, $args = array() ) {
		$id          = $faq->get_id();
		$object_type = $faq->get_object_type();

		if ( ! $id ) {
			return;
		}

		do_action( 'masteriyo_before_delete_' . $object_type, $id, $faq );
		wp_delete_post( $id, true );
		$faq->set_id( 0 );
		do_action( 'masteriyo_after_delete_' . $object_type, $id, $faq );
	}

	/**
	 * Fetch faqs.
	 *
	 * @since 0.1.0
	 *
	 * @param array $query_vars Query vars.
	 * @return Faq[]
	 */
	public function query( $query_vars ) {
		$args = $this->get_wp_query_args( $query_vars );

		if ( ! empty( $args['errors'] ) ) {
			$query = (object) array(
				'posts'         => array(),
				'found_posts'   => 0,
				'max_num_pages' => 0,
			);
		} else {
			$query = new \WP_Query( $args );
		}

		if ( isset( $query_vars['return'] ) && 'objects' === $query_vars['return'] && ! empty( $query->posts ) ) {
			// Prime caches before grabbing objects.
			update_post_caches( $query->posts, array( 'faq' ) );
		}

		$faqs = ( isset( $query_vars['return'] ) && 'ids' === $query_vars['return'] ) ? $query->posts : array_filter( array_map( 'masteriyo_get_faq', $query->posts ) );

		if ( isset( $query_vars['paginate'] ) && $query_vars['paginate'] ) {
			return (object) array(
				'faqs'      => $faqs,
				'total'         => $query->found_posts,
				'max_num_pages' => $query->max_num_pages,
			);
		}

		return $faqs;
	}

	/**
	 * Get valid WP_Query args from a FAQQuery's query variables.
	 *
	 * @since 0.1.0
	 * @param array $query_vars Query vars from a FAQQuery.
	 * @return array
	 */
	protected function get_wp_query_args( $query_vars ) {
		// Map query vars to ones that get_wp_query_args or WP_Query recognize.
		$key_mapping = array(
			'page'   => 'paged',
			'parent_id' => 'post_parent',
		);

		foreach ( $key_mapping as $query_key => $db_key ) {
			if ( isset( $query_vars[ $query_key ] ) ) {
				$query_vars[ $db_key ] = $query_vars[ $query_key ];
				unset( $query_vars[ $query_key ] );
			}
		}

		$query_vars['post_type'] = 'faq';

		$wp_query_args = parent::get_wp_query_args( $query_vars );

		if ( ! isset( $wp_query_args['date_query'] ) ) {
			$wp_query_args['date_query'] = array();
		}
		if ( ! isset( $wp_query_args['meta_query'] ) ) {
			$wp_query_args['meta_query'] = array(); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		}

		// Handle date queries.
		$date_queries = array(
			'date_created'      => 'post_date',
			'date_modified'     => 'post_modified',
		);
		foreach ( $date_queries as $query_var_key => $db_key ) {
			if ( isset( $query_vars[ $query_var_key ] ) && '' !== $query_vars[ $query_var_key ] ) {

				// Remove any existing meta queries for the same keys to prevent conflicts.
				$existing_queries = wp_list_pluck( $wp_query_args['meta_query'], 'key', true );
				foreach ( $existing_queries as $query_index => $query_contents ) {
					unset( $wp_query_args['meta_query'][ $query_index ] );
				}

				$wp_query_args = $this->parse_date_for_wp_query( $query_vars[ $query_var_key ], $db_key, $wp_query_args );
			}
		}

		// Handle paginate.
		if ( ! isset( $query_vars['paginate'] ) || ! $query_vars['paginate'] ) {
			$wp_query_args['no_found_rows'] = true;
		}

		// Handle orderby.
		if ( isset( $query_vars['orderby'] ) && 'include' === $query_vars['orderby'] ) {
			$wp_query_args['orderby'] = 'post__in';
		}

		return apply_filters( 'masteriyo_faq_wp_query_args', $wp_query_args, $query_vars, $this );
	}
}
