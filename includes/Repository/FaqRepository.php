<?php
/**
 * FaqRepository class.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Repository;
 */

namespace Masteriyo\Repository;

use Masteriyo\Database\Model;
use Masteriyo\Models\Faq;

/**
 * FaqRepository class.
 */
class FaqRepository extends AbstractRepository implements RepositoryInterface {
	/**
	 * Meta type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $meta_type = 'comment';

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $internal_meta_keys = array(
		'title'      => '_title',
		'sort_order' => '_sort_order',
	);

	/**
	 * Create a faq in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $faq Faq object.
	 */
	public function create( Model &$faq ) {
		if ( ! $faq->get_created_at( 'edit' ) ) {
			$faq->set_created_at( current_time( 'mysql', true ) );
		}

		$id = wp_insert_comment(
			/**
			 * Filters new FAQ data before creating.
			 *
			 * @since 1.0.0
			 *
			 * @param array $data New FAQ data.
			 * @param Masteriyo\Models\Faq $faq FAQ object.
			 */
			apply_filters(
				'masteriyo_new_faq_data',
				array(
					'comment_author'       => $faq->get_user_name( 'edit' ),
					'comment_author_email' => $faq->get_user_email( 'edit' ),
					'comment_author_url'   => $faq->get_user_url( 'edit' ),
					'comment_content'      => $faq->get_content( 'edit' ),
					'comment_date'         => $faq->get_created_at( 'edit' ),
					'comment_date_gmt'     => $faq->get_created_at( 'edit' ),
					'comment_post_ID'      => $faq->get_course_id( 'edit' ),
					'comment_type'         => $faq->get_type(),
					'user_id'              => $faq->get_user_id( 'edit' ),
					'comment_agent'        => $faq->get_user_agent( 'edit' ),
					'comment_author_IP'    => $faq->get_user_ip( 'edit' ),
				),
				$faq
			),
			true
		);

		if ( $id && ! is_wp_error( $id ) ) {
			$faq->set_id( $id );
			$this->update_comment_meta( $faq, true );

			$faq->save_meta_data();
			$faq->apply_changes();

			do_action( 'masteriyo_new_faq', $id, $faq );
		}

	}

	/**
	 * Read a Faq.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $faq Faq object.
	 * @throws \Exception If invalid FAQ.
	 */
	public function read( Model &$faq ) {
		$faq_post = get_comment( $faq->get_id() );

		if ( ! $faq->get_id() || ! $faq_post || $faq->get_type() !== $faq_post->comment_type ) {
			throw new \Exception( __( 'Invalid FAQ.', 'masteriyo' ) );
		}

		// Map the comment status from numberical to word.
		$status = $faq_post->comment_approved;
		if ( '1' === $status ) {
			$status = 'approve';
		} elseif ( '0' === $status ) {
			$status = 'hold';
		}
		$faq->set_props(
			array(
				'content'    => $faq_post->comment_content,
				'course_id'  => $faq_post->comment_post_ID,
				'user_id'    => $faq_post->user_id,
				'user_name'  => $faq_post->comment_author,
				'user_email' => $faq_post->comment_author_email,
				'user_url'   => $faq_post->comment_author_url,
				'user_ip'    => $faq_post->comment_author_IP,
				'user_agent' => $faq_post->comment_agent,
				'status'     => $status,
				'created_at' => $faq_post->comment_date_gmt,
			)
		);
		$this->read_comment_meta_data( $faq );
		$faq->set_object_read( true );

		do_action( 'masteriyo_faq_read', $faq->get_id(), $faq );
	}

	/**
	 * Update a faq in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $faq Faq object.
	 *
	 * @return void
	 */
	public function update( Model &$faq ) {
		$changes = $faq->get_changes();

		$faq_data_keys = array(
			'content',
			'course_id',
			'user_id',
			'user_name',
			'user_email',
			'user_url',
			'user_ip',
			'user_agent',
			'status',
			'created_at',
		);

		// Only update the course review when the course review data changes.
		if ( array_intersect( $faq_data_keys, array_keys( $changes ) ) ) {
			$faq_data = array(
				'comment_author'       => $faq->get_user_name( 'edit' ),
				'comment_author_email' => $faq->get_user_email( 'edit' ),
				'comment_author_url'   => $faq->get_user_url( 'edit' ),
				'comment_content'      => $faq->get_content( 'edit' ),
				'comment_date'         => $faq->get_created_at( 'edit' ),
				'comment_date_gmt'     => $faq->get_created_at( 'edit' ),
				'comment_post_ID'      => $faq->get_course_id( 'edit' ),
				'comment_type'         => $faq->get_type(),
				'user_id'              => $faq->get_user_id( 'edit' ),
				'comment_agent'        => $faq->get_user_agent( 'edit' ),
				'comment_author_IP'    => $faq->get_user_ip( 'edit' ),
			);

			wp_update_comment( array_merge( array( 'comment_ID' => $faq->get_id() ), $faq_data ) );
		}

		$this->update_comment_meta( $faq );
		$faq->apply_changes();

		do_action( 'masteriyo_update_faq', $faq->get_id(), $faq );
	}

	/**
	 * Delete a Faq from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $faq Faq object.
	 * @param array $args   Array of args to pass.alert-danger.
	 */
	public function delete( Model &$faq, $args = array() ) {
		$id          = $faq->get_id();
		$object_type = $faq->get_object_type();

		if ( ! $id ) {
			return;
		}

		do_action( 'masteriyo_before_delete_' . $object_type, $id, $faq );
		wp_delete_comment( $id, true );
		$faq->set_id( 0 );
		do_action( 'masteriyo_after_delete_' . $object_type, $id, $faq );
	}

	/**
	 * Fetch faqs.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_vars Query vars.
	 * @return Faq[]
	 */
	public function query( $query_vars ) {
		$args = $this->get_wp_query_args( $query_vars );
		$args = array_merge( $args, array( 'type' => 'faq' ) );

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
			$faq = $query->comments;
		} else {
			$faq = array_filter( array_map( 'masteriyo_get_faq', $query->comments ) );
		}

		if ( isset( $query_vars['paginate'] ) && $query_vars['paginate'] ) {
			return (object) array(
				'faq'           => $faq,
				'total'         => $query->found_posts,
				'max_num_pages' => $query->max_num_pages,
			);
		}

		return $faq;
	}

	/**
	 * Get valid WP_Query args from a FaqQuery's query variables.
	 *
	 * @since 1.0.0
	 * @param array $query_vars Query vars from a FaqQuery.
	 * @return array
	 */
	protected function get_wp_query_args( $query_vars ) {
		// Map query vars to ones that get_wp_query_args or WP_Query recognize.
		$key_mapping = array(
			'page'      => 'paged',
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
			'date_created'  => 'post_date',
			'date_modified' => 'post_modified',
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

		/**
		 * Filters WP Query args for FAQ post type query.
		 *
		 * @since 1.0.0
		 *
		 * @param array $wp_query_args WP Query args.
		 * @param array $query_vars Query vars.
		 * @param \asteriyo\Repository\FaqRepository $repository FAQ repository object.
		 */
		return apply_filters( 'masteriyo_faq_wp_query_args', $wp_query_args, $query_vars, $this );
	}

	/**
	 * Read comment meta data. Can be overridden by child classes to load other props.
	 *
	 * @since 1.0.0
	 *
	 * @param Faq $faq Course review object.
	 */
	protected function read_comment_meta_data( &$faq ) {
		$meta_values = $this->read_meta( $faq );
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

		$faq->set_props( $set_props );
	}
}
