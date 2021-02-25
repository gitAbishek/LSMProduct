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
		'_price'             => 'price',
		'_regular_price'     => 'regular_price',
		'_sale_price'        => 'sale_price',
		'_featured'          => 'featured',
		'_category_ids'      => 'category_ids',
		'_tag_ids'           => 'tag_ids',
		'_difficulty_ids'    => 'difficulty_ids',
		'_thumbnail_id'      => 'featured_image',
		'_rating_counts'     => 'rating_counts',
		'_average_rating'    => 'average_rating',
		'_review_count'      => 'review_count',
		'_date_on_sale_from' => 'date_on_sale_from',
		'_date_on_sale_to'   => 'date_on_sale_to',
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
			'menu_order'        => $course_post->menu_order,
			'post_password'     => $course_post->post_password,
			'reviews_allowed'   => 'open' === $course_post->comment_status,
		) );

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

		if ( $force || array_key_exists( 'difficulty_ids', $changes ) ) {
			wp_set_post_terms( $model->get_id(), $model->get_difficulty_ids( 'edit' ), 'course_difficulty', false );
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

			// if ( 'outofstock' === $course->get_stock_status() ) {
			// 	$terms[] = 'outofstock';
			// }

			// $rating = min( 5, Number::round( $course->get_average_rating(), 0 ) );

			// if ( $rating > 0 ) {
			// 	$terms[] = 'rated-' . $rating;
			// }

			// switch ( $course->get_catalog_visibility() ) {
			// 	case 'hidden':
			// 		$terms[] = 'exclude-from-search';
			// 		$terms[] = 'exclude-from-catalog';
			// 		break;
			// 	case 'catalog':
			// 		$terms[] = 'exclude-from-search';
			// 		break;
			// 	case 'search':
			// 		$terms[] = 'exclude-from-catalog';
			// 		break;
			// }

			// if ( ! is_wp_error( wp_set_post_terms( $course->get_id(), $terms, 'course_visibility', false ) ) ) {
			// 	do_action( 'masteriyo_course_set_visibility', $course->get_id(), $course->get_catalog_visibility() );
			// }
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

		foreach ( $this->internal_meta_keys as $meta_key => $prop ) {
			$meta_value         = isset( $meta_values[ $meta_key ][0] ) ? $meta_values[ $meta_key ][0] : null;
			$set_props[ $prop ] = maybe_unserialize( $meta_value ); // get_post_meta only unserializes single values.
		}

		$set_props['category_ids']   = $this->get_term_ids( $course, 'course_cat' );
		$set_props['tag_ids']        = $this->get_term_ids( $course, 'course_tag' );
		$set_props['difficulty_ids'] = $this->get_term_ids( $course, 'course_difficulty' );

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
}
