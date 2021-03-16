<?php
/**
 * Course Repository.
 *
 * @package ThemeGrill\Masteriyo\Repository;
 */

namespace ThemeGrill\Masteriyo\Repository;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Models\Course;
use ThemeGrill\Masteriyo\Helper\Number;

/**
 * Course repository class.
 */
class CourseRepository extends AbstractRepository implements RepositoryInterface {

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 3.0.0
	 * @var array
	 */
	protected $internal_meta_keys = array(
		'price'             => '_price',
		'regular_price'     => '_regular_price',
		'sale_price'        => '_sale_price',
		'category_ids'      => '_category_ids',
		'tag_ids'           => '_tag_ids',
		'difficulty_id'    => '_difficulty_id',
		'featured_image'    => '_thumbnail_id',
		'rating_counts'     => '_rating_counts',
		'average_rating'    => '_average_rating',
		'review_count'      => '_review_count',
		'date_on_sale_from' => '_date_on_sale_from',
		'date_on_sale_to'   => '_date_on_sale_to',
	);

	/**
	 * Create a course in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $course Course object.
	 */
	public function create( Model &$course ) {
		if ( ! $course->get_date_created( 'edit' ) ) {
			$course->set_date_created( current_time( 'mysql', true ) );
		}

		$id = wp_insert_post(
			apply_filters(
				'masteriyo_new_course_data',
				array(
					'post_type'      => 'course',
					'post_status'    => $course->get_status() ? $course->get_status() : 'publish',
					'post_author'    => get_current_user_id(),
					'post_title'     => $course->get_name() ? $course->get_name() : __( 'Course', 'masteriyo' ),
					'post_content'   => $course->get_description(),
					'post_excerpt'   => $course->get_short_description(),
					'post_parent'    => $course->get_parent_id(),
					'comment_status' => $course->get_reviews_allowed() ? 'open' : 'closed',
					'ping_status'    => 'closed',
					'menu_order'     => $course->get_menu_order(),
					'post_password'  => $course->get_post_password( 'edit' ),
					'post_date'      => $course->get_date_created( 'edit' ),
					'post_date_gmt'  => $course->get_date_created( 'edit' ),
					'post_name'      => $course->get_slug( 'edit' )
				),
				$course
			)
		);

		if ( $id && ! is_wp_error( $id ) ) {
			$course->set_id( $id );
			$this->update_post_meta( $course, true );
			$this->update_terms( $course, true );
			$this->update_visibility( $course, true );
			$this->handle_updated_props( $course );
			// TODO Invalidate caches.

			$course->save_meta_data();
			$course->apply_changes();

			do_action( 'masteriyo_new_course', $id, $course );
		}

	}

	/**
	 * Read a course.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $course Cource object.
	 * @throws Exception If invalid course.
	 */
	public function read( Model &$course ) {
		$course_post = get_post( $course->get_id() );

		if ( ! $course->get_id() || ! $course_post || 'course' !== $course_post->post_type ) {
			throw new \Exception( __( 'Invalid course.', 'masteriyo' ) );
		}

		$course->set_props( array(
			'name'              => $course_post->post_title,
			'slug'              => $course_post->post_name,
			'date_created'      => $course_post->post_date_gmt,
			'date_modified'     => $course_post->post_modified_gmt,
			'status'            => $course_post->post_status,
			'description'       => $course_post->post_content,
			'short_description' => $course_post->post_excerpt,
			'parent_id'         => $course_post->post_parent,
			'author_id'         => $course_post->post_author,
			'menu_order'        => $course_post->menu_order,
			'post_password'     => $course_post->post_password,
			'reviews_allowed'   => 'open' === $course_post->comment_status,
		) );

		$this->read_visibility( $course );
		$this->read_course_data( $course );
		$this->read_extra_data( $course );
		$course->set_object_read( true );

		do_action( 'masteriyo_course_read', $course->get_id(), $course );
	}

	/**
	 * Update a course in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $course Course object.
	 *
	 * @return void
	 */
	public function update( Model &$course ) {
		$changes = $course->get_changes();

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
				'post_content'   => $course->get_description( 'edit' ),
				'post_excerpt'   => $course->get_short_description( 'edit' ),
				'post_title'     => $course->get_name( 'edit' ),
				'post_parent'    => $course->get_parent_id( 'edit' ),
				'comment_status' => $course->get_reviews_allowed( 'edit' ) ? 'open' : 'closed',
				'post_status'    => $course->get_status( 'edit' ) ? $course->get_status( 'edit' ) : 'publish',
				'menu_order'     => $course->get_menu_order( 'edit' ),
				'post_password'  => $course->get_post_password( 'edit' ),
				'post_name'      => $course->get_slug( 'edit' ),
				'post_type'      => 'course',
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
				$GLOBALS['wpdb']->update( $GLOBALS['wpdb']->posts, $post_data, array( 'ID' => $course->get_id() ) );
				clean_post_cache( $course->get_id() );
			} else {
				wp_update_post( array_merge( array( 'ID' => $course->get_id() ), $post_data ) );
			}
			$course->read_meta_data( true ); // Refresh internal meta data, in case things were hooked into `save_post` or another WP hook.
		} else { // Only update post modified time to record this save event.
			$GLOBALS['wpdb']->update(
				$GLOBALS['wpdb']->posts,
				array(
					'post_modified'     => current_time( 'mysql' ),
					'post_modified_gmt' => current_time( 'mysql', true ),
				),
				array(
					'ID' => $course->get_id(),
				)
			);
			clean_post_cache( $course->get_id() );
		}

		$this->update_post_meta( $course );
		$this->update_terms( $course );
		$this->update_visibility( $course );
		$this->handle_updated_props( $course );

		$course->apply_changes();

		do_action( 'masteriyo_update_course', $course->get_id(), $course );
	}

	/**
	 * Delete a course from the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $course Course object.
	 * @param array $args	Array of args to pass.alert-danger
	 */
	public function delete( Model &$course, $args = array() ) {
		$id          = $course->get_id();
		$object_type = $course->get_object_type();

		$args = array_merge( array(
			'force_delete' => false,
		), $args );

		if ( ! $id ) {
			return;
		}

		if ( $args['force_delete'] ) {
			do_action( 'masteriyo_before_delete_' . $object_type, $id, $course );
			wp_delete_post( $id, true );
			$course->set_id( 0 );
			do_action( 'masteriyo_after_delete_' . $object_type, $id, $course );
		} else {
			do_action( 'masteriyo_before_trash_' . $object_type, $id, $course );
			wp_trash_post( $id );
			$course->set_status( 'trash' );
			do_action( 'masteriyo_before_trash_' . $object_type, $id, $course );
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
		$changes = $model->get_changes();

		if ( $force || array_key_exists( 'category_ids', $changes ) ) {
			$categories = $model->get_category_ids( 'edit' );

			if ( empty( $categories ) && get_option( 'default_course_cat', 0 ) ) {
				$categories = array( get_option( 'default_course_cat', 0 ) );
			}

			wp_set_post_terms( $model->get_id(), $categories, 'course_cat', false );
		}

		if ( $force || array_key_exists( 'tag_ids', $changes ) ) {
			wp_set_post_terms( $model->get_id(), $model->get_tag_ids( 'edit' ), 'course_tag', false );
		}

		if ( $force || array_key_exists( 'difficulty_id', $changes ) ) {
			wp_set_post_terms( $model->get_id(), $model->get_difficulty_id( 'edit' ), 'course_difficulty', false );
		}
	}

	/**
	 * Handle updated meta props after updating meta data.
	 *
	 * @since 0.1.0
	 * @param Course $course Course Object.
	 */
	protected function handle_updated_props( $course ) {
		if ( in_array( 'regular_price', $this->updated_props, true ) || in_array( 'sale_price', $this->updated_props, true ) ) {
			if ( $course->get_sale_price( 'edit' ) >= $course->get_regular_price( 'edit' ) ) {
				update_post_meta( $course->get_id(), '_sale_price', '' );
				$course->set_sale_price( '' );
			}
		}

		if ( in_array( 'date_on_sale_from', $this->updated_props, true ) || in_array( 'date_on_sale_to', $this->updated_props, true )
			|| in_array( 'regular_price', $this->updated_props, true ) || in_array( 'sale_price', $this->updated_props, true ) ) {
			if ( $course->is_on_sale( 'edit' ) ) {
				update_post_meta( $course->get_id(), '_price', $course->get_sale_price( 'edit' ) );
				$course->set_price( $course->get_sale_price( 'edit' ) );
			} else {
				update_post_meta( $course->get_id(), '_price', $course->get_regular_price( 'edit' ) );
				$course->set_price( $course->get_regular_price( 'edit' ) );
			}
		}
	}

	/**
	 * Update visibility terms based on props.
	 *
	 * @since 0.1.0
	 *
	 * @param Course $course Course object.
	 * @param bool       $force Force update. Used during create.
	 */
	protected function update_visibility( &$course, $force = false ) {
		$changes = $course->get_changes();

		if ( $force || array_intersect( array( 'featured', 'stock_status', 'average_rating', 'catalog_visibility' ), array_keys( $changes ) ) ) {
			$terms = array();

			if ( $course->get_featured() ) {
				$terms[] = 'featured';
			}

			$rating = min( 5, Number::round( $course->get_average_rating(), 0 ) );

			if ( $rating > 0 ) {
				$terms[] = 'rated-' . $rating;
			}

			switch ( $course->get_catalog_visibility() ) {
				case 'hidden':
					$terms[] = 'exclude-from-search';
					$terms[] = 'exclude-from-catalog';
					break;
				case 'catalog':
					$terms[] = 'exclude-from-search';
					break;
				case 'search':
					$terms[] = 'exclude-from-catalog';
					break;
			}

			if ( ! is_wp_error( wp_set_post_terms( $course->get_id(), $terms, 'course_visibility', false ) ) ) {
				do_action( 'masteriyo_course_set_visibility', $course->get_id(), $course->get_catalog_visibility() );
			}
		}
	}


	/**
	 * Read course data. Can be overridden by child classes to load other props.
	 *
	 * @since 0.1.0
	 *
	 * @param Course $course course object.
	 */
	protected function read_course_data( &$course ) {
		$id          = $course->get_id();
		$meta_values = $this->read_meta( $course );

		$set_props = array();

		$meta_values = array_reduce( $meta_values, function( $result, $meta_value ) {
			$result[ $meta_value->key ][] = $meta_value->value;
			return $result;
		}, array() );

		foreach ( $this->internal_meta_keys as $prop => $meta_key ) {
			$meta_value         = isset( $meta_values[ $meta_key ][0] ) ? $meta_values[ $meta_key ][0] : null;
			$set_props[ $prop ] = maybe_unserialize( $meta_value ); // get_post_meta only unserializes single values.
		}

		$set_props['category_ids']   = $this->get_term_ids( $course, 'course_cat' );
		$set_props['tag_ids']        = $this->get_term_ids( $course, 'course_tag' );
		$set_props['difficulty_id']  = $this->get_term_ids( $course, 'course_difficulty' );

		$set_props['difficulty_id'] = count( $set_props['difficulty_id'] ) > 0 ? $set_props['difficulty_id'][0] : array();

		$course->set_props( $set_props );
	}

	/**
	 * Read extra data associated with the course, like button text or course URL for external courses.
	 *
	 * @since 0.1.0
	 *
	 * @param Course $course course object.
	 */
	protected function read_extra_data( &$course ) {
		$meta_values = $this->read_meta( $course );

		foreach ( $course->get_extra_data_keys() as $key ) {
			$function = 'set_' . $key;
			if ( is_callable( array( $course, $function ) )
				&& isset( $meta_values[ '_' . $key ] ) ) {
				$course->{$function}( $meta_values[ '_' . $key ] );
			}
		}
	}

	/**
	 * Fetch courses.
	 *
	 * @since 0.1.0
	 *
	 * @param array $query_vars Query vars.
	 * @return Course[]
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
			update_post_caches( $query->posts, array( 'course' ) );
		}

		$courses = ( isset( $query_vars['return'] ) && 'ids' === $query_vars['return'] ) ? $query->posts : array_filter( array_map( 'masteriyo_get_course', $query->posts ) );

		if ( isset( $query_vars['paginate'] ) && $query_vars['paginate'] ) {
			return (object) array(
				'courses'      => $courses,
				'total'         => $query->found_posts,
				'max_num_pages' => $query->max_num_pages,
			);
		}

		return $courses;
	}

	/**
	 * Convert visibility terms to props.
	 * Catalog visibility valid values are 'visible', 'catalog', 'search', and 'hidden'.
	 *
	 * @param Course $course Course object.
	 * @since 3.0.0
	 */
	protected function read_visibility( &$course ) {
		$terms           = get_the_terms( $course->get_id(), 'course_visibility' );
		$term_names      = is_array( $terms ) ? wp_list_pluck( $terms, 'name' ) : array();
		$featured        = in_array( 'featured', $term_names, true );
		$exclude_search  = in_array( 'exclude-from-search', $term_names, true );
		$exclude_catalog = in_array( 'exclude-from-catalog', $term_names, true );

		if ( $exclude_search && $exclude_catalog ) {
			$catalog_visibility = 'hidden';
		} elseif ( $exclude_search ) {
			$catalog_visibility = 'catalog';
		} elseif ( $exclude_catalog ) {
			$catalog_visibility = 'search';
		} else {
			$catalog_visibility = 'visible';
		}

		$course->set_props(
			array(
				'featured'           => $featured,
				'catalog_visibility' => $catalog_visibility,
			)
		);
	}

	/**
	 * Get valid WP_Query args from a CourseQuery's query variables.
	 *
	 * @since 0.1.0
	 * @param array $query_vars Query vars from a CourseQuery.
	 * @return array
	 */
	protected function get_wp_query_args( $query_vars ) {
		// These queries cannot be auto-generated so we have to remove them and build them manually.
		$manual_queries = array(
			'featured'   => '',
			'visibility' => '',
		);

		foreach ( $manual_queries as $key => $manual_query ) {
			if ( isset( $query_vars[ $key ] ) ) {
				$manual_queries[ $key ] = $query_vars[ $key ];
				unset( $query_vars[ $key ] );
			}
		}

		$wp_query_args = parent::get_wp_query_args( $query_vars );

		if ( ! isset( $wp_query_args['date_query'] ) ) {
			$wp_query_args['date_query'] = array();
		}
		if ( ! isset( $wp_query_args['meta_query'] ) ) {
			$wp_query_args['meta_query'] = array(); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		}

		// Handle course categories.
		if ( ! empty( $query_vars['category'] ) ) {
			$wp_query_args['tax_query'][] = array(
				'taxonomy' => 'course_cat',
				'field'    => 'slug',
				'terms'    => $query_vars['category'],
			);
		}

		// Handle course tags.
		if ( ! empty( $query_vars['tag'] ) ) {
			unset( $wp_query_args['tag'] );
			$wp_query_args['tax_query'][] = array(
				'taxonomy' => 'course_tag',
				'field'    => 'slug',
				'terms'    => $query_vars['tag'],
			);
		}

		// Handle course difficultyies.
		if ( ! empty( $query_vars['difficulty'] ) ) {
			unset( $wp_query_args['difficulty'] );
			$wp_query_args['tax_query'][] = array(
				'taxonomy' => 'course_difficulty',
				'field'    => 'slug',
				'terms'    => $query_vars['difficulty'],
			);
		}

		// Handle featured.
		if ( '' !== $manual_queries['featured'] ) {
			if ( $manual_queries['featured'] ) {
				$course_visibility_term_ids = masteriyo_get_course_visibility_term_ids();

				$wp_query_args['tax_query'][] = array(
					'taxonomy' => 'course_visibility',
					'field'    => 'term_taxonomy_id',
					'terms'    => array( $course_visibility_term_ids['featured'] ),
				);

				$wp_query_args['tax_query'][] = array(
					'taxonomy' => 'course_visibility',
					'field'    => 'term_taxonomy_id',
					'terms'    => array( $course_visibility_term_ids['exclude-from-catalog'] ),
					'operator' => 'NOT IN',
				);
			} else {
				$wp_query_args['tax_query'][] = array(
					'taxonomy' => 'course_visibility',
					'field'    => 'term_taxonomy_id',
					'terms'    => array( $course_visibility_term_ids['featured'] ),
					'operator' => 'NOT IN',
				);
			}
		}

		// Handle date queries.
		$date_queries = array(
			'date_created'      => 'post_date',
			'date_modified'     => 'post_modified',
			'date_on_sale_from' => '_sale_price_dates_from',
			'date_on_sale_to'   => '_sale_price_dates_to',
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

		// Handle reviews_allowed.
		if ( isset( $query_vars['reviews_allowed'] ) && is_bool( $query_vars['reviews_allowed'] ) ) {
			add_filter( 'posts_where', array( $this, 'reviews_allowed_query_where' ), 10, 2 );
		}

		// Handle orderby.
		if ( isset( $query_vars['orderby'] ) && 'include' === $query_vars['orderby'] ) {
			$wp_query_args['orderby'] = 'post__in';
		}

		return apply_filters( 'masteriyo_course_data_store_cpt_get_courses_query', $wp_query_args, $query_vars, $this );
	}
}
