<?php
/**
 * Abstract class controller.
 */

namespace Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Helper\Utils;
use Masteriyo\Helper\Permission;

class CoursesController extends PostsController {
	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'masteriyo/v1';

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'courses';

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $object_type = 'course';

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $post_type = 'mto-course';

	/**
	 * If object is hierarchical.
	 *
	 * @var bool
	 */
	protected $hierarchical = true;

	/**
	 * Permission class.
	 *
	 * @since 1.0.0
	 *
	 * @var Masteriyo\Helper\Permission;
	 */
	protected $permission = null;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param Permission $permission
	 */
	public function __construct( Permission $permission ) {
		$this->permission = $permission;
	}

	/**
	 * Register routes.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_item' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( \WP_REST_Server::CREATABLE ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<id>[\d]+)',
			array(
				'args'   => array(
					'id' => array(
						'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
					'args'                => array(
						'context' => $this->get_context_param(
							array(
								'default' => 'view',
							)
						),
					),
				),
				array(
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_item' ),
					'permission_callback' => array( $this, 'update_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( \WP_REST_Server::EDITABLE ),
				),
				array(
					'methods'             => \WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_item' ),
					'permission_callback' => array( $this, 'delete_item_permissions_check' ),
					'args'                => array(
						'force' => array(
							'default'     => false,
							'description' => __( 'Whether to bypass trash and force deletion.', 'masteriyo' ),
							'type'        => 'boolean',
						),
					),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
	}

	/**
	 * Get the query params for collections of attachments.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();

		$params['slug'] = array(
			'description'       => __( 'Limit result set to courses with a specific slug.', 'masteriyo' ),
			'type'              => 'string',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['status'] = array(
			'default'           => 'any',
			'description'       => __( 'Limit result set to courses assigned a specific status.', 'masteriyo' ),
			'type'              => 'string',
			'enum'              => array_merge( array( 'any', 'future' ), array_keys( get_post_statuses() ) ),
			'sanitize_callback' => 'sanitize_key',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['featured'] = array(
			'description'       => __( 'Limit result set to featured courses.', 'masteriyo' ),
			'type'              => 'boolean',
			'sanitize_callback' => 'masteriyo_string_to_bool',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['price'] = array(
			'description'       => __( 'List courses with specific price.', 'masteriyo' ),
			'type'              => 'string',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['price_type'] = array(
			'description'       => __( 'List courses with specific price type (free or paid).', 'masteriyo' ),
			'type'              => 'string',
			'enum'              => array( 'free', 'paid' ),
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['category'] = array(
			'description'       => __( 'Limit result set to courses assigned a specific category ID.', 'masteriyo' ),
			'type'              => 'string',
			'sanitize_callback' => 'wp_parse_id_list',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['tag'] = array(
			'description'       => __( 'Limit result set to courses assigned a specific tag ID.', 'masteriyo' ),
			'type'              => 'string',
			'sanitize_callback' => 'wp_parse_id_list',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['difficulty'] = array(
			'description'       => __( 'Limit result set to courses assigned a specific difficulty ID.', 'masteriyo' ),
			'type'              => 'string',
			'sanitize_callback' => 'wp_parse_id_list',
			'validate_callback' => 'rest_validate_request_arg',
		);

		return $params;
	}

	/**
	 * Get object.
	 *
	 * @since 1.0.0
	 *
	 * @param  Model|WP_Post $object Model or WP_Post object.
	 * @return object Model object or WP_Error object.
	 */
	protected function get_object( $object ) {
		try {
			if ( is_int( $object ) ) {
				$id = $object;
			} else {
				$id = is_a( $object, '\WP_Post' ) ? $object->ID : $object->get_id();
			}

			$course = masteriyo( 'course' );
			$course->set_id( $id );
			$course_repo = masteriyo( 'course.store' );
			$course_repo->read( $course );
		} catch ( \Exception $e ) {
			return false;
		}

		return $course;
	}

	/**
	 * Prepares the object for the REST response.
	 *
	 * @since  1.0.0
	 * @param  Model           $object  Model object.
	 * @param  WP_REST_Request $request Request object.
	 * @return WP_Error|WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	protected function prepare_object_for_response( $object, $request ) {
		$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data    = $this->get_course_data( $object, $context );

		$data     = $this->add_additional_fields_to_object( $data, $request );
		$data     = $this->filter_response_by_context( $data, $context );
		$response = rest_ensure_response( $data );
		$response->add_links( $this->prepare_links( $object, $request ) );

		/**
		 * Filter the data for a response.
		 *
		 * @since 0.1.0
		 *
		 * The dynamic portion of the hook name, $this->object_type,
		 * refers to object type being prepared for the response.
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param Model          $object   Object data.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( "masteriyo_rest_prepare_{$this->object_type}_object", $response, $object, $request );
	}

	/**
	 * Process objects collection.
	 *
	 * @since 1.0.0
	 *
	 * @param array $objects Courses data.
	 * @param array $query_args Query arguments.
	 * @param array $query_results Courses query result data.
	 *
	 * @return array
	 */
	protected function process_objects_collection( $objects, $query_args, $query_results ) {
		return array(
			'data' => $objects,
			'meta' => array(
				'total'        => $query_results['total'],
				'pages'        => $query_results['pages'],
				'current_page' => $query_args['paged'],
				'per_page'     => $query_args['posts_per_page'],
			),
		);
	}

	/**
	 * Get course data.
	 *
	 * @since 1.0.0
	 *
	 * @param Course $course Course instance.
	 * @param string $context Request context.
	 *                        Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_course_data( $course, $context = 'view' ) {
		$author = masteriyo_get_user( $course->get_author_id( $context ) );

		if ( ! is_wp_error( $author ) ) {
			$author = array(
				'id'           => $author->get_id(),
				'display_name' => $author->get_display_name(),
				'avatar_url'   => $author->get_avatar_url(),
			);
		}

		$data = array(
			'id'                => $course->get_id(),
			'name'              => wp_specialchars_decode( $course->get_name( $context ) ),
			'slug'              => $course->get_slug( $context ),
			'permalink'         => $course->get_permalink(),
			'preview_permalink' => $course->get_preview_course_link(),
			'status'            => $course->get_status( $context ),
			'description'       => 'view' === $context ? wpautop( do_shortcode( $course->get_description() ) ) : $course->get_description( $context ),
			'short_description' => 'view' === $context ? apply_filters( 'masteriyo_short_description', $course->get_short_description() ) : $course->get_short_description( $context ),
			'reviews_allowed'   => $course->get_reviews_allowed( $context ),
			'parent_id'         => $course->get_parent_id( $context ),
			'menu_order'        => $course->get_menu_order( $context ),
			'author'            => $author,
			'date_created'      => $course->get_date_created( $context ),
			'date_modified'     => $course->get_date_modified( $context ),
			'featured'          => $course->get_featured( $context ),
			'price'             => $course->get_price( $context ),
			'regular_price'     => $course->get_regular_price( $context ),
			'sale_price'        => $course->get_sale_price( $context ),
			'price_type'        => $course->get_price_type( $context ),
			'featured_image'    => $course->get_featured_image( $context ),
			'enrollment_limit'  => $course->get_enrollment_limit( $context ),
			'duration'          => $course->get_duration( $context ),
			'access_mode'       => $course->get_access_mode( $context ),
			'billing_cycle'     => $course->get_billing_cycle( $context ),
			'show_curriculum'   => $course->get_show_curriculum( $context ),
			'highlights'        => $course->get_highlights( $context ),
			'edit_post_link'    => get_edit_post_link( $course->get_id(), $context ),
			'categories'        => $this->get_taxonomy_terms( $course, 'cat' ),
			'tags'              => $this->get_taxonomy_terms( $course, 'tag' ),
			'difficulty'        => $this->get_taxonomy_terms( $course, 'difficulty' ),
		);

		return $data;
	}

	/**
	 * Get taxonomy terms.
	 *
	 * @since 1.0.0
	 *
	 * @param Course $course Course object.
	 * @param string $taxonomy Taxonomy slug.
	 *
	 * @return array
	 */
	protected function get_taxonomy_terms( $course, $taxonomy = 'cat' ) {
		$terms = Utils::get_object_terms( $course->get_id(), 'course_' . $taxonomy );

		$terms = array_map(
			function( $term ) {
				return array(
					'id'   => $term->term_id,
					'name' => $term->name,
					'slug' => $term->slug,
				);
			},
			$terms
		);

		$terms = 'difficulty' === $taxonomy ? array_shift( $terms ) : $terms;

		return $terms;
	}

	/**
	 * Prepare objects query.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$args = parent::prepare_objects_query( $request );

		// Set post_status.
		$args['post_status'] = $request['status'];

		// Taxonomy query to filter courses by category, tag and difficult
		$tax_query = array();

		// Map between taxonomy name and arg's key.
		$taxonomies = array(
			'course_cat'        => 'category',
			'course_tag'        => 'tag',
			'course_difficulty' => 'difficulty',
		);

		// Set tax_query for each passed arg.
		foreach ( $taxonomies as $taxonomy => $key ) {
			if ( ! empty( $request[ $key ] ) ) {
				$tax_query[] = array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $request[ $key ],
				);
			}
		}

		// Filter featured.
		if ( is_bool( $request['featured'] ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'course_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
				'operator' => true === $request['featured'] ? 'IN' : 'NOT IN',
			);
		}

		if ( $request['price_type'] ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'course_visibility',
				'field'    => 'name',
				'terms'    => $request['price_type'],
			);
		}

		// Build tax_query if taxonomies are set.
		if ( ! empty( $tax_query ) ) {
			if ( ! empty( $args['tax_query'] ) ) {
				$args['tax_query'] = array_merge( $tax_query, $args['tax_query'] ); // WPCS: slow query ok.
			} else {
				$args['tax_query'] = $tax_query; // WPCS: slow query ok.
			}
		}

		if ( isset( $request['price'] ) ) {
			$args['meta_query'] = array(
				'relation' => 'AND',
				array(
					'key'     => '_price',
					'value'   => abs( $request['price'] ),
					'compare' => '=',
				),
			);
		}

		return $args;
	}

	/**
	 * Get the courses'schema, conforming to JSON Schema.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => $this->object_type,
			'type'       => 'object',
			'properties' => array(
				'id'                 => array(
					'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'name'               => array(
					'description' => __( 'Course name.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'slug'               => array(
					'description' => __( 'Course slug.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'permalink'          => array(
					'description' => __( 'Course URL.', 'masteriyo' ),
					'type'        => 'string',
					'format'      => 'uri',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'preview_permalink'  => array(
					'description' => __( 'Course Preview URL.', 'masteriyo' ),
					'type'        => 'string',
					'format'      => 'uri',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_created'       => array(
					'description' => __( "The date the course was created, in the site's timezone.", 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'date_created_gmt'   => array(
					'description' => __( 'The date the course was created, as GMT.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'date_modified'      => array(
					'description' => __( "The date the course was last modified, in the site's timezone.", 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_modified_gmt'  => array(
					'description' => __( 'The date the course was last modified, as GMT.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'status'             => array(
					'description' => __( 'Course status (post status).', 'masteriyo' ),
					'type'        => 'string',
					'default'     => 'publish',
					'enum'        => array_merge( array_keys( get_post_statuses() ), array( 'future' ) ),
					'context'     => array( 'view', 'edit' ),
				),
				'featured'           => array(
					'description' => __( 'Featured course.', 'masteriyo' ),
					'type'        => 'boolean',
					'default'     => false,
					'context'     => array( 'view', 'edit' ),
				),
				'catalog_visibility' => array(
					'description' => __( 'Catalog visibility.', 'masteriyo' ),
					'type'        => 'string',
					'default'     => 'visible',
					'enum'        => array( 'visible', 'catalog', 'search', 'hidden' ),
					'context'     => array( 'view', 'edit' ),
				),
				'description'        => array(
					'description' => __( 'Course description.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'short_description'  => array(
					'description' => __( 'Course short description.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'price'              => array(
					'description' => __( 'Current course price.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'regular_price'      => array(
					'description' => __( 'Course regular price.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'sale_price'         => array(
					'description' => __( 'Course sale price.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'price_type'         => array(
					'description' => __( 'Course price type.', 'masteriyo' ),
					'type'        => 'string',
					'readonly'    => true,
					'context'     => array( 'view', 'edit' ),
				),
				'reviews_allowed'    => array(
					'description' => __( 'Allow reviews.', 'masteriyo' ),
					'type'        => 'boolean',
					'default'     => true,
					'context'     => array( 'view', 'edit' ),
				),
				'average_rating'     => array(
					'description' => __( 'Reviews average rating.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'rating_count'       => array(
					'description' => __( 'Amount of reviews that the course have.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'parent_id'          => array(
					'description' => __( 'Course parent ID.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'featured_image'     => array(
					'description'       => __( 'Course featured image.', 'masteriyo' ),
					'type'              => 'integer',
					'default'           => null,
					'validate_callback' => array( $this, 'validate_featured_image' ),
					'context'           => array( 'view', 'edit' ),
				),
				'categories'         => array(
					'description' => __( 'List of categories.', 'masteriyo' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'   => array(
								'description' => __( 'Category ID.', 'masteriyo' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
							),
							'name' => array(
								'description' => __( 'Category name.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'slug' => array(
								'description' => __( 'Category slug.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
						),
					),
				),
				'tags'               => array(
					'description' => __( 'List of tags.', 'masteriyo' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'   => array(
								'description' => __( 'Tag ID.', 'masteriyo' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
							),
							'name' => array(
								'description' => __( 'Tag name.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'slug' => array(
								'description' => __( 'Tag slug.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
						),
					),
				),
				'difficulty'         => array(
					'description' => __( 'Course difficulty', 'masteriyo' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'   => array(
								'description' => __( 'Difficulty ID.', 'masteriyo' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
							),
							'name' => array(
								'description' => __( 'Difficulty name.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'slug' => array(
								'description' => __( 'Difficulty slug.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
						),
					),
				),
				'menu_order'         => array(
					'description' => __( 'Menu order, used to custom sort courses.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'enrollment_limit'   => array(
					'description' => __( 'Course enrollment limit. Default unlimited.', 'masteriyo' ),
					'type'        => 'integer',
					'default'     => 0,
					'context'     => array( 'view', 'edit' ),
				),
				'duration'           => array(
					'description' => __( 'Course duration (minutes).', 'masteriyo' ),
					'type'        => 'integer',
					'default'     => 0,
					'context'     => array( 'view', 'edit' ),
				),
				'access_mode'        => array(
					'description' => __( 'Course access mode', 'masteriyo' ),
					'type'        => 'string',
					'default'     => 'open',
					'enum'        => masteriyo_get_course_access_modes(),
					'context'     => array( 'view', 'edit' ),
				),
				'billing_cycle'      => array(
					'description' => __( 'Course billing cycle (1d, 2w, 3m, 4y).', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'show_curriculum'    => array(
					'description' => __( 'Course show curriculum. ( True = Visible to all, False = Visible to only enrollees)', 'masteriyo' ),
					'type'        => 'boolean',
					'default'     => true,
					'context'     => array( 'view', 'edit' ),
				),
				'highlights'         => array(
					'description' => __( 'Course highlights', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'wp_edit_link'       => array(
					'description' => __( 'Course WordPress edit link.', 'masteriyo' ),
					'type'        => 'string',
					'readonly'    => true,
					'context'     => array( 'view', 'edit' ),
				),
				'meta_data'          => array(
					'description' => __( 'Meta data.', 'masteriyo' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'    => array(
								'description' => __( 'Meta ID.', 'masteriyo' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'key'   => array(
								'description' => __( 'Meta key.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'value' => array(
								'description' => __( 'Meta value.', 'masteriyo' ),
								'type'        => 'mixed',
								'context'     => array( 'view', 'edit' ),
							),
						),
					),
				),
			),
		);

		return $schema;
	}

	/**
	 * Prepare a single course for create or update.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|Model
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		global $masteriyo;

		$id     = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
		$course = masteriyo( 'course' );

		if ( 0 !== $id ) {
			$course->set_id( $id );
			$course_repo = masteriyo( \Masteriyo\Repository\CourseRepository::class );
			$course_repo->read( $course );
		}

		// Post title.
		if ( isset( $request['name'] ) ) {
			$course->set_name( wp_filter_post_kses( $request['name'] ) );
		}

		// Post content.
		if ( isset( $request['description'] ) ) {
			$course->set_description( wp_filter_post_kses( $request['description'] ) );
		}

		// Post excerpt.
		if ( isset( $request['short_description'] ) ) {
			$course->set_short_description( wp_filter_post_kses( $request['short_description'] ) );
		}

		// Post status.
		if ( isset( $request['status'] ) ) {
			$course->set_status( get_post_status_object( $request['status'] ) ? $request['status'] : 'draft' );
		}

		// Post slug.
		if ( isset( $request['slug'] ) ) {
			$course->set_slug( $request['slug'] );
		}

		// Menu order.
		if ( isset( $request['menu_order'] ) ) {
			$course->set_menu_order( $request['menu_order'] );
		}

		// Comment status.
		if ( isset( $request['reviews_allowed'] ) ) {
			$course->set_reviews_allowed( $request['reviews_allowed'] );
		}

		// Featured Course.
		if ( isset( $request['featured'] ) ) {
			$course->set_featured( $request['featured'] );
		}

		// Regular Price.
		if ( isset( $request['regular_price'] ) ) {
			$course->set_regular_price( $request['regular_price'] );
		}

		// Sale Price.
		if ( isset( $request['sale_price'] ) ) {
			$course->set_sale_price( $request['sale_price'] );
		}

		// Course parent ID.
		if ( isset( $request['parent_id'] ) ) {
			$course->set_parent_id( $request['parent_id'] );
		}

		// Course featured image.
		if ( isset( $request['featured_image'] ) ) {
			$course->set_featured_image( $request['featured_image'] );
		}

		// Course enrollment limit.
		if ( isset( $request['enrollment_limit'] ) ) {
			$course->set_enrollment_limit( $request['enrollment_limit'] );
		}

		// Course duration.
		if ( isset( $request['duration'] ) ) {
			$course->set_duration( $request['duration'] );
		}

		// Course access mode.
		if ( isset( $request['access_mode'] ) ) {
			$course->set_access_mode( $request['access_mode'] );
		}

		// Course billing cycle.
		if ( isset( $request['billing_cycle'] ) ) {
			$course->set_billing_cycle( $request['billing_cycle'] );
		}

		// Course show curriculum.
		if ( isset( $request['show_curriculum'] ) ) {
			$course->set_show_curriculum( $request['show_curriculum'] );
		}

		// Course highlights.
		if ( isset( $request['highlights'] ) ) {
			$course->set_highlights( $request['highlights'] );
		}

		// Course categories.
		if ( isset( $request['categories'] ) && is_array( $request['categories'] ) ) {
			$course = $this->save_taxonomy_terms( $course, $request['categories'] );
		}

		// Course tags.
		if ( isset( $request['tags'] ) && is_array( $request['tags'] ) ) {
			$course = $this->save_taxonomy_terms( $course, $request['tags'], 'tag' );
		}

		// Course difficulties.
		if ( isset( $request['difficulty'] ) && is_array( $request['difficulty'] ) ) {
			$course = $this->save_taxonomy_terms( $course, $request['difficulty'], 'difficulty' );
		}

		// Allow set meta_data.
		if ( isset( $request['meta_data'] ) && is_array( $request['meta_data'] ) ) {
			foreach ( $request['meta_data'] as $meta ) {
				$course->update_meta_data( $meta['key'], $meta['value'], isset( $meta['id'] ) ? $meta['id'] : '' );
			}
		}

		/**
		 * Filters an object before it is inserted via the REST API.
		 *
		 * The dynamic portion of the hook name, `$this->object_type`,
		 * refers to the object type slug.
		 *
		 * @param Model         $course  Object object.
		 * @param WP_REST_Request $request  Request object.
		 * @param bool            $creating If is creating a new object.
		 */
		return apply_filters( "masteriyo_rest_pre_insert_{$this->object_type}_object", $course, $request, $creating );
	}

	/**
	 * Save taxonomy terms.
	 *
	 * @since 1.0.0
	 *
	 * @param Course $course  Course instance.
	 * @param array  $terms    Terms data.
	 * @param string $taxonomy Taxonomy name.
	 *
	 * @return Course
	 */
	protected function save_taxonomy_terms( $course, $terms, $taxonomy = 'cat' ) {
		$term_ids = 'difficulty' === $taxonomy ? array_values( $terms ) : wp_list_pluck( $terms, 'id' );

		if ( 'cat' === $taxonomy ) {
			$course->set_category_ids( $term_ids );
		} elseif ( 'tag' === $taxonomy ) {
			$course->set_tag_ids( $term_ids );
		} elseif ( 'difficulty' === $taxonomy ) {
			$course->set_difficulty_id( array_shift( $term_ids ) );
		}

		return $course;
	}

	/**
	 * Validate the existence of featured image.
	 * Since the featured image will use the WordPress attachment.
	 *
	 * @param int $featured_image_id Featured image id.
	 * @return bool|WP_Error
	 */
	public function validate_featured_image( $featured_image_id ) {
		if ( ! is_numeric( $featured_image_id ) ) {
			return new \WP_Error( 'rest_invalid_type', 'featured image is not of type integer' );
		}

		$featured_image_id = absint( $featured_image_id );
		$featured_image    = get_post(
			$featured_image_id,
			array(
				'post_type' => 'attachment',
			)
		);

		if ( is_null( $featured_image_obj ) ) {
			return new \WP_Error( 'rest_invalid_featured_image', 'invalid featured image id' );
		}

		return true;
	}

	/**
	 * Prepare links for the request.
	 *
	 * @since 1.0.0
	 *
	 * @param Model           $object  Object data.
	 * @param WP_REST_Request $request Request object.
	 * @return array                   Links for the given post.
	 */
	protected function prepare_links( $object, $request ) {
		$links = parent::prepare_links( $object, $request );

		$query = new \WP_Query(
			array(
				'post_type'      => array( 'mto-lesson', 'mto-quiz' ),
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'meta_key'       => '_course_id',
				'meta_compare'   => '=',
				'meta_value'     => $object->get_id(),
				'orderby'        => array(
					'parent'     => 'ASC',
					'menu_order' => 'ASC',
				),
			)
		);

		$links['first'] = array(
			'href' => ( 1 === $query->post_count ) ? $this->get_navigation_link( $query->posts[0] ) : '',
		);

		return $links;
	}
}
