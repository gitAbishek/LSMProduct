<?php
/**
 * CommentController class.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\RestApi\Controllers\Version1;
 */

namespace ThemeGrill\Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use ThemeGrill\Masteriyo\Helper\Utils;
use ThemeGrill\Masteriyo\Helper\Permission;

/**
 * Main class for CommentController.
 */
class CourseReviewsController extends CommentsController {
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
	protected $rest_base = 'courses/reviews';

	/**
	 * Post Type.
	 *
	 * @var string
	 */
	protected $object_type = 'course_review';

	/**
	 * If object is hierarchial.
	 *
	 * @var bool
	 */
	protected $hierarchial = false;

	/**
	 * Permission class.
	 *
	 * @since 0.1.0
	 *
	 * @var ThemeGrill\Masteriyo\Helper\Permission;
	 */
	protected $permission = null;

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param Permission $permission Permission instance.
	 */
	public function __construct( Permission $permission = null ) {
		$this->permission = $permission;
	}

	/**
	 * Register Routes.
	 *
	 * @since 0.1.0
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
							'type'        => 'boolean',
							'description' => __( 'Required to be true, as the resource does not support trashing.', 'masteriyo' ),
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
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();

		return $params;

	}

	/**
	 * Get object.
	 *
	 * @since 0.1.0
	 *
	 * @param  int|WP_Comment|Model $object Object ID or WP_Comment or Model.
	 *
	 * @return object Model object or WP_Error object.
	 */
	protected function get_object( $object ) {
		try {
			if ( is_int( $object ) ) {
				$id = $object;
			} else {
				$id = is_a( $object, '\WP_Comment' ) ? $object->comment_ID : $object->get_id();
			}
			$course_review = masteriyo( 'course_review' );
			$course_review->set_id( $id );
			$course_review_repo = masteriyo( 'course_review.store' );
			$course_review_repo->read( $course_review );
		} catch ( \Exception $e ) {
			return false;
		}

		return $course_review;
	}

	/**
	 * Get objects.
	 *
	 * @since  0.1.0
	 * @param  array $query_args Query args.
	 * @return array
	 */
	protected function get_objects( $query_args ) {
		$course_reviews = new \WP_Comment_Query( $query_args );
		$course_reviews = $course_reviews->comments;
		$total_posts    = count( $course_reviews );
		if ( $total_posts < 1 ) {
			// Out-of-bounds, run the query again without LIMIT for total count.
			unset( $query_args['paged'] );
			$course_reviews = new \WP_Comment_Query( $query_args );
			$course_reviews = $course_reviews->comments;
			$total_posts    = count( $course_reviews );
		}

		return array(
			'objects' => array_filter( array_map( array( $this, 'get_object' ), $course_reviews ) ),
			'total'   => (int) $total_posts,
			'pages'   => (int) ceil( $total_posts / (int) 10 ),
		);
	}

	/**
	 * Prepares the object for the REST response.
	 *
	 * @since  0.1.0
	 *
	 * @param  Model           $object  Model object.
	 * @param  WP_REST_Request $request Request object.
	 *
	 * @return WP_Error|WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	protected function prepare_object_for_response( $object, $request ) {
		$context  = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data     = $this->get_course_review_data( $object, $context );
		$data     = $this->add_additional_fields_to_object( $data, $request );
		$data     = $this->filter_response_by_context( $data, $context );
		$response = rest_ensure_response( $data );
		$response->add_links( $this->prepare_links( $object, $request ) );

		/**
		 * Filter the data for a response.
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
	 * Get course review data.
	 *
	 * @param CourseReview $course_review Course Review instance.
	 * @param string       $context Request context.
	 *                             Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_course_review_data( $course_review, $context = 'view' ) {
		$data = array(
			'id'           => $course_review->get_id(),
			'course_id'    => $course_review->get_course_id(),
			'author_name'  => $course_review->get_author_name( $context ),
			'author_email' => $course_review->get_author_email( $context ),
			'author_url'   => $course_review->get_author_url( $context ),
			'ip_address'   => $course_review->get_ip_address( $context ),
			'date_created' => $course_review->get_date_created( $context ),
			'title'        => $course_review->get_title( $context ),
			'description'  => $course_review->get_content( $context ),
			'rating'       => $course_review->get_rating( $context ),
			'status'       => $course_review->get_status( $context ),
			'agent'        => $course_review->get_agent( $context ),
			'type'         => $course_review->get_type( $context ),
			'parent'       => $course_review->get_parent( $context ),
			'author_id'    => $course_review->get_author_id( $context ),
		);

		return $data;
	}

	/**
	 * Prepare objects query.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @since  0.1.0
	 *
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$args = array(
			'offset'   => $request['offset'],
			'paged'    => $request['page'],
			'per_page' => $request['per_page'],
			's'        => $request['search'],
			'type'     => 'course_review',
		);

		/**
		 * Filter the query arguments for a request.
		 *
		 * Enables adding extra arguments or setting defaults for a post
		 * collection request.
		 *
		 * @param array           $args    Key value array of query var to query value.
		 * @param WP_REST_Request $request The request used.
		 */
		$args = apply_filters( "masteriyo_rest_{$this->object_type}_object_query", $args, $request );

		return $args;
	}

	/**
	 * Get the Course review's schema, conforming to JSON Schema.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => $this->object_type,
			'type'       => 'object',
			'properties' => array(
				'id'           => array(
					'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'course_id'    => array(
					'description' => __( 'Course ID', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'name'         => array(
					'description' => __( 'Course Reviewer Author.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'email'        => array(
					'description' => __( 'Course Reviewer Author Email.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'url'          => array(
					'description' => __( 'Course Reviewer Author URL.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'ip_address'   => array(
					'description' => __( 'The IP address of the reviewer', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_created' => array(
					'description' => __( "The date the course was created, in the site's timezone.", 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'description'  => array(
					'description' => __( 'Course Review Description.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'title'        => array(
					'description' => __( 'Course Review Title.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'content'      => array(
					'description' => __( 'Course Review Content.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'required'    => true,
				),
				'rating'       => array(
					'description' => __( 'Course Review rating.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'status'       => array(
					'description' => __( 'Course Review Status.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'agent'        => array(
					'description' => __( 'Course Review Agent.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'type'         => array(
					'description' => __( 'Course Review Type.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'parent'       => array(
					'description' => __( 'Course Review Parent.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'author_id'    => array(
					'description' => __( 'The User ID.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'meta_data'    => array(
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
	 * Prepare a single course review object for create or update.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|Model
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		$id            = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
		$course_review = masteriyo( 'course_review' );
		$user          = masteriyo_get_current_user();

		if ( 0 !== $id ) {
			$course_review->set_id( $id );
			$course_review_repo = masteriyo( \ThemeGrill\Masteriyo\Repository\CourseReviewRepository::class );
			$course_review_repo->read( $course_review );
		}

		if (
			! is_null( $user ) &&
			! isset( $request['author_id'] ) &&
			! isset( $request['author_name'] ) &&
			! isset( $request['author_email'] )
		) {
			$course_review->set_author_id( $user->get_id() );
			$course_review->set_author_email( $user->get_email() );
			$course_review->set_author_name( $user->get_display_name() );
			$course_review->set_author_url( $user->get_url() );
		}

		// Course Review Author.
		if ( isset( $request['author_name'] ) ) {
			$course_review->set_author_name( $request['author_name'] );
		}

		// Course Review Author Email.
		if ( isset( $request['author_email'] ) ) {
			$course_review->set_author_email( $request['author_email'] );
		}

		// Course Review Author URL.
		if ( isset( $request['author_url'] ) ) {
			$course_review->set_author_url( $request['author_url'] );
		}

		// Course Review Author IP.
		if ( isset( $request['ip_address'] ) ) {
			$course_review->set_ip_address( $request['ip_address'] );
		}

		// Course Review Date.
		if ( isset( $request['date_created'] ) ) {
			$course_review->set_date_created( $request['date_created'] );
		}

		// Course Review Title.
		if ( isset( $request['title'] ) ) {
			$course_review->set_title( $request['title'] );
		}

		// Course Review Content.
		if ( isset( $request['content'] ) ) {
			$course_review->set_content( $request['content'] );
		}

		// Course Review Rating.
		if ( isset( $request['rating'] ) ) {
			$course_review->set_rating( $request['rating'] );
		}

		// Course Review Approved.
		if ( isset( $request['status'] ) ) {
			$course_review->set_status( $request['status'] );
		}

		// Course Review Agent.
		if ( isset( $request['agent'] ) ) {
			$course_review->set_agent( $request['agent'] );
		}

		// Course Review Type.
		if ( isset( $request['type'] ) ) {
			$course_review->set_type( $request['type'] );
		}

		// Course ID.
		if ( isset( $request['course_id'] ) ) {
			$course_review->set_course_id( $request['course_id'] );
		}

		// Course Review Parent.
		if ( isset( $request['parent'] ) ) {
			$course_review->set_parent( $request['parent'] );
		}

		// User ID.
		if ( isset( $request['author_id'] ) ) {
			$course_review->set_author_id( $request['author_id'] );
		}

		// Allow set meta_data.
		if ( isset( $request['meta_data'] ) && is_array( $request['meta_data'] ) ) {
			foreach ( $request['meta_data'] as $meta ) {
				$course_review->update_meta_data( $meta['key'], $meta['value'], isset( $meta['id'] ) ? $meta['id'] : '' );
			}
		}

		/**
		 * Filters an object before it is inserted via the REST API.
		 *
		 * The dynamic portion of the hook name, `$this->object_type`,
		 * refers to the object type slug.
		 *
		 * @param Model         $comment  Object object.
		 * @param WP_REST_Request $request  Request object.
		 * @param bool            $creating If is creating a new object.
		 */
		return apply_filters( "masteriyo_rest_pre_insert_{$this->object_type}_object", $course_review, $request, $creating );
	}

	/**
	 * Check if a given request has access to read items.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function get_items_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( ! $this->permission->rest_check_course_reviews_permissions( 'read' ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_read',
				__( 'Sorry, you cannot list resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Checks if a given request has access to get a specific item.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return boolean|WP_Error True if the request has read access for the item, WP_Error object otherwise.
	 */
	public function get_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( ! $this->permission->rest_check_course_reviews_permissions( 'read', absint( $request['id'] ) ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_read',
				__( 'Sorry, you are not allowed to read resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Check if a given request has access to create an item.
	 *
	 * @since 0.1.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function create_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
		}

		if ( ! $this->permission->rest_check_course_reviews_permissions( 'create' ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_create',
				__( 'Sorry, you are not allowed to create course reviews.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( isset( $request['author_id'] ) && absint( $request['author_id'] ) === 0 ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_create',
				__( 'Sorry, author_id cannot be empty or zero.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( isset( $request['author_id'] ) && absint( $request['author_id'] ) !== get_current_user_id() ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_create',
				__( 'Sorry, you are not allowed to create course reviews for others.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		$course = masteriyo_get_course( absint( $request['course_id'] ) );

		if ( is_null( $course ) ) {
			return new \WP_Error(
				"masteriyo_rest_{$this->post_type}_invalid_id",
				__( 'Invalid course ID.', 'masteriyo' ),
				array(
					'status' => 404,
				)
			);
		}

		if ( $course->get_author_id() === get_current_user_id() ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_create',
				__( 'Sorry, you cannot create review for your own course.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Check if a given request has access to delete an item.
	 *
	 * @since 0.1.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function delete_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
		}

		$review = $this->get_object( absint( $request['id'] ) );

		if ( ! is_object( $review ) ) {
			return new \WP_Error(
				'masteriyo_rest_invalid_id',
				__( 'Invalid ID.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( get_current_user_id() !== $review->get_author_id() ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_delete',
				__( 'Sorry, you are not allowed to delete this resource.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( ! $this->permission->rest_check_course_reviews_permissions( 'delete', absint( $request['id'] ) ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_delete',
				__( 'Sorry, you are not allowed to delete resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Check if a given request has access to update an item.
	 *
	 * @since 0.1.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function update_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
		}

		$review = $this->get_object( absint( $request['id'] ) );

		if ( ! is_object( $review ) ) {
			return new \WP_Error(
				'masteriyo_rest_invalid_id',
				__( 'Invalid ID.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( get_current_user_id() !== $review->get_author_id() ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_update',
				__( 'Sorry, you are not allowed to update this resource.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( ! $this->permission->rest_check_course_reviews_permissions( 'edit', absint( $request['id'] ) ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_update',
				__( 'Sorry, you are not allowed to update resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( isset( $request['course_id'] ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_update',
				__( 'Sorry, you cannot move a review to another course.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Check permissions for an item.
	 *
	 * @since 0.1.0
	 *
	 * @param string $object_type Object type.
	 * @param string $context   Request context.
	 * @param int    $object_id Post ID.
	 *
	 * @return bool
	 */
	protected function check_item_permission( $object_type, $context = 'read', $object_id = 0 ) {
		return true;
	}
}
