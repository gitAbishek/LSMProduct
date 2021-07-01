<?php
/**
 * Course progress controller.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\RestApi\Controllers\Version1;
 */

namespace ThemeGrill\Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use ThemeGrill\Masteriyo\Helper\Permission;
use ThemeGrill\Masteriyo\Models\Order\OrderItem;
use ThemeGrill\Masteriyo\Exceptions\RestException;
use ThemeGrill\Masteriyo\Query\CourseProgressQuery;
use ThemeGrill\Masteriyo\Query\CourseProgressItemQuery;

/**
 * User activities controller class.
 */
class CourseProgressController extends CrudController {

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
	protected $rest_base = 'course-progress';

	/**
	 * Object type.
	 *
	 * @var string
	 */
	protected $object_type = 'course_progress';

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $post_type = 'course_progress';

	/**
	 * If object is hierarchical.
	 *
	 * @var bool
	 */
	protected $hierarchical = false;

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
	 * @param Permission $permission Permision object.
	 */
	public function __construct( Permission $permission = null ) {
		$this->permission = $permission;
	}

	/**
	 * Register routes.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			$this->rest_base,
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
			$this->rest_base . '/start',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'start_course_progress' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
					'args'                => $this->get_start_schema(),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			$this->rest_base . '/(?P<id>[\d]+)',
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
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params['page'] = array(
			'description'       => __( 'Paginate the course progress.', 'masteriyo' ),
			'type'              => 'integer',
			'default'           => 1,
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
			'minimum'           => 1,
		);

		$params['per_page'] = array(
			'description'       => __( 'Limit course progress per page.', 'masteriyo' ),
			'type'              => 'integer',
			'default'           => 10,
			'minimum'           => 1,
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['user_id'] = array(
			'description'       => __( 'User ID.', 'masteriyo' ),
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
			'default'           => 0,
		);

		$params['course_id'] = array(
			'description'       => __( 'Course ID.', 'masteriyo' ),
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
			'default'           => 0,
		);

		$params['status'] = array(
			'description'       => __( 'User activity status.', 'masteriyo' ),
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_title',
			'validate_callback' => 'rest_validate_request_arg',
			'default'           => 'any',
			'enum'              => masteriyo_get_user_activity_statuses(),
		);

		$params['date_start'] = array(
			'description'       => __( 'Limit response to resources started after a given ISO8601 compliant date.', 'masteriyo' ),
			'type'              => 'string',
			'format'            => 'date-time',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['date_complete'] = array(
			'description'       => __( 'Limit response to resources started after a given ISO8601 compliant date.', 'masteriyo' ),
			'type'              => 'string',
			'format'            => 'date-time',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['date_update'] = array(
			'description'       => __( 'Limit response to resources started after a given ISO8601 compliant date.', 'masteriyo' ),
			'type'              => 'string',
			'format'            => 'date-time',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['order'] = array(
			'description'       => __( 'Order sort attribute ascending or descending.', 'masteriyo' ),
			'type'              => 'string',
			'default'           => 'desc',
			'enum'              => array( 'asc', 'desc' ),
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['orderby'] = array(
			'description'       => __( 'Sort collection by object attribute.', 'masteriyo' ),
			'type'              => 'string',
			'default'           => 'id',
			'enum'              => array(
				'id',
				'type',
				'date_start',
				'date_update',
				'date_complete',
			),
			'validate_callback' => 'rest_validate_request_arg',
		);

		return $params;
	}

	/**
	 * Get object.
	 *
	 * @since 0.1.0
	 *
	 * @param  int|CourseProgress $id Object ID.
	 * @return object Model object or WP_Error object.
	 */
	protected function get_object( $id ) {
		try {
			$id              = is_a( $id, 'ThemeGrill\Masteriyo\Database\Model' ) ? $id->get_id() : $id;
			$course_progress = masteriyo_get_course_progress( $id );
		} catch ( \Exception $e ) {
			return false;
		}

		return $course_progress;
	}

	/**
	 * Prepares the object for the REST response.
	 *
	 * @since  0.1.0
	 * @param  Model           $object  Model object.
	 * @param  WP_REST_Request $request Request object.
	 * @return WP_Error|WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	protected function prepare_object_for_response( $object, $request ) {
		$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data    = $this->get_course_progress_data( $object, $context );

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
		 * @since 0.1.0
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param Model          $object   Object data.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( "masteriyo_rest_prepare_{$this->object_type}_object", $response, $object, $request );
	}

	/**
	 * Get user activity data.
	 *
	 * @since 0.1.0
	 *
	 * @param CourseProgress  $course_progress User activity instance.
	 * @param string $context Request context.
	 *                        Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_course_progress_data( $course_progress, $context = 'view' ) {
		$data = array(
			'id'            => $course_progress->get_id( $context ),
			'user_id'       => $course_progress->get_user_id( $context ),
			'course_id'     => $course_progress->get_course_id( $context ),
			'status'        => $course_progress->get_status( $context ),
			'date_start'    => masteriyo_rest_prepare_date_response( $course_progress->get_date_start( $context ) ),
			'date_update'   => masteriyo_rest_prepare_date_response( $course_progress->get_date_update( $context ) ),
			'date_complete' => masteriyo_rest_prepare_date_response( $course_progress->get_date_complete( $context ) ),
		);

		return $data;
	}

	/**
	 * Prepare objects query.
	 *
	 * @since  0.1.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$args = wp_parse_args(
			$request->get_params(),
			array(
				'page'          => 1,
				'per_page'      => 10,
				'user_id'       => 0,
				'status'        => 'any',
				'date_start'    => null,
				'date_update'   => null,
				'date_complete' => null,
			)
		);

		/**
		 * Filter the query arguments for a request.
		 *
		 * Enables adding extra arguments or setting defaults for a post
		 * collection request.
		 *
		 * @since 0.1.0
		 *
		 * @param array           $args    Key value array of query var to query value.
		 * @param WP_REST_Request $request The request used.
		 */
		$args = apply_filters( "masteriyo_rest_{$this->object_type}_object_query", $args, $request );

		return $args;
	}

	/**
	 * Get the orders' schema, conforming to JSON Schema.
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
				'id'            => array(
					'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'user_id'       => array(
					'description' => __( 'User ID.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'course_id'     => array(
					'description' => __( 'Course ID.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'status'        => array(
					'description' => __( 'Course progress status.', 'masteriyo' ),
					'type'        => 'string',
					'enum'        => masteriyo_get_user_activity_statuses(),
					'context'     => array( 'view', 'edit' ),
				),
				'date_start'    => array(
					'description' => __( 'Course progress start date in GMT.', 'masteriyo' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view', 'edit' ),
				),
				'date_complete' => array(
					'description' => __( 'Course progress complete date in GMT.', 'masteriyo' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view', 'edit' ),
				),
				'date_update'   => array(
					'description' => __( 'Course progress update date in GMT.', 'masteriyo' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view', 'edit' ),
				),
			),
		);

		return $schema;
	}

	/**
	 * Get the course progress start schema, conforming to JSON Schema.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_start_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => $this->object_type,
			'type'       => 'object',
			'properties' => array(
				'id'        => array(
					'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'course_id' => array(
					'description' => __( 'Course ID.', 'masteriyo' ),
					'type'        => 'integer',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
				),
				'items'     => array(
					'description' => __( 'Order billing details.', 'masteriyo' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'      => 'object',
						'item_id'   => array(
							'description' => __( 'Lesson/Quiz ID.', 'masteriyo' ),
							'type'        => 'integer',
							'required'    => true,
							'context'     => array( 'view', 'edit' ),
						),
						'item_type' => array(
							'description' => __( 'Course progress ( Lesson, Quiz) item type.', 'masteriyo' ),
							'type'        => 'string',
							'enum'        => array( 'lesson', 'quiz' ),
							'context'     => array( 'view', 'edit' ),
						),
						'completed' => array(
							'description' => __( 'Course progress item completed.', 'masteriyo' ),
							'type'        => 'boolean',
							'default'     => false,
							'context'     => array( 'view', 'edit' ),
						),
					),
				),
			),
		);

		return rest_get_endpoint_args_for_schema( $schema );
	}

	/**
	 * Prepare a single course progress for create or update.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|Model
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		$id              = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
		$course_progress = masteriyo( 'course-progress' );

		if ( 0 !== $id ) {
			$course_progress->set_id( $id );
			$course_progress_repo = masteriyo( 'course-progress.store' );
			$course_progress_repo->read( $course_progress );
		}

		try {
			$user_id = $this->validate_user_id( $request, $creating );
			$course_progress->set_user_id( $user_id );

			$course_id = $this->validate_course_id( $request, $creating );
			$course_progress->set_course_id( $course_id );
		} catch ( RestException $e ) {
			return new \WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
		}

		// Activity status.
		if ( isset( $request['status'] ) ) {
			$course_progress->set_status( $request['status'] );
		}

		// Activity start date.
		if ( isset( $request['date_start'] ) ) {
			$course_progress->set_date_start( $request['date_start'] );
		}

		// Activity update date.
		if ( isset( $request['date_update'] ) ) {
			$course_progress->set_date_update( $request['date_update'] );
		}

		// Activity complete date.
		if ( isset( $request['date_complete'] ) ) {
			$course_progress->set_date_complete( $request['date_complete'] );
		}

		/**
		 * Filters an object before it is inserted via the REST API.
		 *
		 * The dynamic portion of the hook name, `$this->object_type`,
		 * refers to the object type slug.
		 *
		 * @since 0.1.0
		 *
		 * @param Model         $course_progress  Object object.
		 * @param WP_REST_Request $request  Request object.
		 * @param bool            $creating If is creating a new object.
		 */
		return apply_filters( "masteriyo_rest_pre_insert_{$this->object_type}_object", $course_progress, $request, $creating );
	}

	/**
	 * Get objects.
	 *
	 * @since  0.1.0
	 * @param  array $query_args Query args.
	 * @return array
	 */
	protected function get_objects( $query_args ) {
		$query   = new CourseProgressQuery( $query_args );
		$objects = $query->get_course_progress();

		$total_items = count( $objects );

		return array(
			'objects' => $objects,
			'total'   => (int) $total_items,
			'pages'   => (int) ceil( $total_items / (int) $query_args['per_page'] ),
		);
	}

	/**
	 * Check if a given request has access to read item.
	 *
	 * @since 0.1.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function get_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
		}

		return true;
	}

	/**
	 * Check if a given request has access to read items.
	 *
	 * @since 0.1.0
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

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
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

		return true;
	}

	/**
	 * Check if a given request has access to create/update an item.
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

	/**
	 * Validate the user ID in the request.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|Model
	 */
	protected function validate_user_id( $request, $creating = false ) {
		$user_id = null;

		// User ID.
		if ( isset( $request['user_id'] ) && ! empty( $request['user_id'] ) ) {
			$user_id = $request['user_id'];
		} else {
			$user_id = get_current_user_id();
		}

		// Validate the user ID.
		$user = get_user_by( 'id', $user_id );
		if ( ! $user ) {
			throw new RestException(
				'masteriyo_rest_invalid_user_id',
				__( 'User ID is invalid.', 'masteriyo' ),
				400
			);
		}

		// If the current user is not administrator or manager, then the current
		// user must be same of the request suer id.
		if ( masteriyo_is_current_user_student() && get_current_user_id() !== $user_id ) {
			throw new RestException(
				'masteriyo_rest_access_denied_course_progress',
				__( 'Student cannot access other\'s course progress.', 'masteriyo' ),
				400
			);
		}

		return $user_id;
	}

	/**
	 * Validate the course ID in the request.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|Model
	 */
	protected function validate_course_id( $request, $creating = false ) {
		$course_id = null;

		// Course ID.
		if ( isset( $request['course_id'] ) && ! empty( $request['course_id'] ) ) {
			$course_id = $request['course_id'];

			// Validate course ID.
			$course_post = get_post( $course_id );
			if ( ! $course_post || 'course' !== $course_post->post_type ) {
				throw new RestException(
					'masteriyo_rest_invalid_course_id',
					__( 'Course ID is invalid.', 'masteriyo' ),
					400
				);
			}
		}

		return $course_id;
	}

	/**
	 * Start course progress.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function start_course_progress( $request ) {
		$user_id   = get_current_user_id();
		$course_id = absint( $request['course_id'] );

		$course = get_post( $course_id );
		if ( is_null( $course ) || 'course' !== $course->post_type ) {
			return new \WP_Error(
				'masteriyo_invalid_course_id',
				__( 'Invalid course ID', 'masteriyo' ),
				array( 'status' => 400 )
			);
		}

		$query = new CourseProgressQuery(
			array(
				'course_id' => $course_id,
				'user_id'   => $user_id,
				'per_page'  => 1,
				'page'      => 1,
				'order'     => 'desc',
				'orderby'   => 'id',
			)
		);

		$course_progress = $query->get_course_progress();

		if ( empty( $course_progress ) ) {
			$course_progress = masteriyo( 'course-progress' );
			$course_progress->set_user_id( $user_id );
			$course_progress->set_course_id( $course_id );
			$course_progress->get_date_start( current_time( 'mysql' ) );
			$course_progress->save();
		}

		if ( is_array( $course_progress ) ) {
			$course_progress = $course_progress[0];
		}

		$items = $this->save_course_progress_items( $request, $course_progress );

		return $this->get_start_progress_data( $course_progress, $items );
	}

	/**
	 * Save course progress items if any.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @param CourseProgress $course_progress Course progress item.
	 */
	protected function save_course_progress_items( $request, $course_progress ) {
		global $wpdb;

		$query = new CourseProgressItemQuery(
			array(
				'user_id'     => $user_id,
				'progress_id' => $course_progress->get_id(),
				'page'        => 0,
				'per_page'    => -1,
				'order'       => 'desc',
				'orderby'     => 'id',
			)
		);

		$items = $query->get_course_progress_items();

		if ( ! isset( $request['items'] ) || empty( $request['items'] ) ) {
			return $items;
		}

		$user_id        = get_current_user_id();
		$progress_items = $request['items'];

		// Create a map of progress items which are from DB.
		foreach ( $items as $item ) {
			$item_key               = $item->get_item_id() . ':' . $item->get_user_id() . ':' . $item->get_type() . ':' . $item->get_progress_id();
			$items_map[ $item_key ] = $item;
		}

		foreach ( $progress_items as $progress_item ) {
			$item_key               = $progress_item['item_id'] . ':' . $user_id . ':' . $progress_item['item_type'] . ':' . $course_progress->get_id();
			$item_obj               = isset( $items_map[ $item_key ] ) ? $items_map[ $item_key ] : masteriyo( 'course-progress-item' );
			$items_map[ $item_key ] = $item_obj;

			$item_obj->set_item_id( $progress_item['item_id'] );
			$item_obj->set_type( $progress_item['item_type'] );
			$item_obj->set_completed( isset( $progress_item['completed'] ) ? $progress_item['completed'] : false );
			$item_obj->set_user_id( $user_id );
			$item_obj->set_progress_id( $course_progress->get_id() );

			$item_obj->save();
		}

		return array_values( $items_map );
	}

	/**
	 * Get start progress data.
	 *
	 * @since 0.1.0
	 *
	 * @param CourseProgress $course_progress Course progress object.
	 * @param array $items Course progress items (lesson and quiz),
	 *
	 * @return array
	 */
	protected function get_start_progress_data( $course_progress, $items ) {
		$data = $this->get_course_progress_data( $course_progress );

		$query = new \WP_Query(
			array(
				'post_type'    => 'quiz',
				'post_status'  => 'any',
				'meta_key'     => '_course_id',
				'meta_value'   => $course_progress->get_course_id(),
				'meta_compare' => '=',
			)
		);

		$total_quizzes = $query->found_posts;

		$data['items'] = array();
		foreach ( $items as $item ) {
			$data['items'][] = $this->get_course_progress_item_data( $item );
		}

		$data['summary'] = array(
			'total'  => $this->get_total_summary( $course_progress, $items ),
			'lesson' => $this->get_lesson_summary( $course_progress, $items ),
			'quiz'   => $this->get_quiz_summary( $course_progress, $items ),
		);

		return $data;

	}

	/**
	 * Get total summary(completed, pending).
	 *
	 * @param CourseProgress $course_progress Course progress object.
	 * @param array $items Course progress items (total and quiz),
	 *
	 * @return array
	 */
	protected function get_total_summary( $course_progress, $items ) {
		$query = new \WP_Query(
			array(
				'post_type'    => array( 'lesson', 'quiz' ),
				'post_status'  => 'any',
				'meta_key'     => '_course_id',
				'meta_value'   => $course_progress->get_course_id(),
				'meta_compare' => '=',
			)
		);

		$total = $query->found_posts;

		$completed = count(
			array_filter(
				$items,
				function( $item ) {
					$item->get_completed( 'edit' );
				}
			)
		);

		return array(
			'completed' => $completed,
			'pending'   => ( $total - $completed ) > 0 ? ( $total - $completed ) : 0,
		);
	}

	/**
	 * Get lesson summary(completed, pending).
	 *
	 * @param CourseProgress $course_progress Course progress object.
	 * @param array $items Course progress items (lesson and quiz),
	 *
	 * @return array
	 */
	protected function get_lesson_summary( $course_progress, $items ) {
		$query = new \WP_Query(
			array(
				'post_type'    => 'lesson',
				'post_status'  => 'any',
				'meta_key'     => '_course_id',
				'meta_value'   => $course_progress->get_course_id(),
				'meta_compare' => '=',
			)
		);

		$total = $query->found_posts;

		$completed = count(
			array_filter(
				$items,
				function( $item ) {
					return 'lesson' === $item->get_type( 'edit' ) && $item->get_completed( 'edit' );
				}
			)
		);

		return array(
			'completed' => $completed,
			'pending'   => ( $total - $completed ) > 0 ? ( $total - $completed ) : 0,
		);
	}

	/**
	 * Get quiz summary(completed, pending).
	 *
	 * @param CourseProgress $course_progress Course progress object.
	 * @param array $items Course progress items (quiz and quiz),
	 *
	 * @return array
	 */
	protected function get_quiz_summary( $course_progress, $items ) {
		$query = new \WP_Query(
			array(
				'post_type'    => 'quiz',
				'post_status'  => 'any',
				'meta_key'     => '_course_id',
				'meta_value'   => $course_progress->get_course_id(),
				'meta_compare' => '=',
			)
		);

		$total = $query->found_posts;

		$completed = count(
			array_filter(
				$items,
				function( $item ) {
					return 'quiz' === $item->get_type( 'edit' ) && $item->get_completed( 'edit' );
				}
			)
		);

		return array(
			'completed' => $completed,
			'pending'   => ( $total - $completed ) > 0 ? ( $total - $completed ) : 0,
		);
	}

	/**
	 * Get course progress item data.
	 *
	 * @since 0.1.0
	 *
	 * @param CourseProgressItem  $course_progress_item Course progress item object.
	 * @param string $context Request context.
	 *                        Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_course_progress_item_data( $course_progress_item, $context = 'view' ) {
		$data = array(
			'item_id'   => $course_progress_item->get_item_id( $context ),
			'item_type' => $course_progress_item->get_type( $context ),
			'completed' => $course_progress_item->get_completed( $context ),
		);

		return $data;
	}
}
