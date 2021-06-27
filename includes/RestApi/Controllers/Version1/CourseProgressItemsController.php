<?php
/**
 * Course progress items controller.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\RestApi\Controllers\Version1;
 */

namespace ThemeGrill\Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use ThemeGrill\Masteriyo\Helper\Permission;
use ThemeGrill\Masteriyo\Exceptions\RestException;
use ThemeGrill\Masteriyo\Query\CourseProgressQuery;
use ThemeGrill\Masteriyo\Query\CourseProgressItemQuery;

/**
 * Course progress items controller class.
 */
class CourseProgressItemsController extends CrudController {

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
	protected $rest_base = 'course-progress/items';

	/**
	 * Object type.
	 *
	 * @var string
	 */
	protected $object_type = 'course_progress_item';

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
		$params['paged'] = array(
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

		$params['progress_id'] = array(
			'description'       => __( 'Course progress ID.', 'masteriyo' ),
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['user_id'] = array(
			'description'       => __( 'User ID.', 'masteriyo' ),
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['item_type'] = array(
			'description'       => __( 'Course progress (course, quiz) item type.', 'masteriyo' ),
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['completed'] = array(
			'description'       => __( 'Course progress item completed.', 'masteriyo' ),
			'type'              => 'boolean',
			'sanitize_callback' => 'masteriyo_string_to_bool',
			'validate_callback' => 'rest_validate_request_arg',
			'default'           => true,
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
			$id                   = is_a( $id, 'ThemeGrill\Masteriyo\Database\Model' ) ? $id->get_id() : $id;
			$course_progress_item = masteriyo_get_course_progress_item( $id );
		} catch ( \Exception $e ) {
			return false;
		}

		return $course_progress_item;
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
		$data    = $this->get_course_progress_item_data( $object, $context );

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
	 * @param CourseProgress  $course_progress_item User activity instance.
	 * @param string $context Request context.
	 *                        Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_course_progress_item_data( $course_progress_item, $context = 'view' ) {
		$data = array(
			'id'            => $course_progress_item->get_id( $context ),
			'user_id'       => $course_progress_item->get_user_id( $context ),
			'item_id'       => $course_progress_item->get_item_id( $context ),
			'progress_id'   => $course_progress_item->get_progress_id( $context ),
			'type'          => $course_progress_item->get_type( $context ),
			'completed'     => $course_progress_item->get_completed( $context ),
			'date_start'    => masteriyo_rest_prepare_date_response( $course_progress_item->get_date_start( $context ) ),
			'date_update'   => masteriyo_rest_prepare_date_response( $course_progress_item->get_date_update( $context ) ),
			'date_complete' => masteriyo_rest_prepare_date_response( $course_progress_item->get_date_complete( $context ) ),
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
				'paged'         => 1,
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
				'progress_id'   => array(
					'description' => __( 'Course progress ID.', 'masteriyo' ),
					'type'        => 'integer',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
				),
				'user_id'       => array(
					'description' => __( 'User ID.', 'masteriyo' ),
					'type'        => 'integer',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
				),
				'item_id'       => array(
					'description' => __( 'Lesson/Quiz ID.', 'masteriyo' ),
					'type'        => 'integer',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
				),
				'item_type'     => array(
					'description' => __( 'Course progress ( Lesson, Quiz) item type.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'completed'     => array(
					'description' => __( 'Course progress item completed.', 'masteriyo' ),
					'type'        => 'boolean',
					'default'     => false,
					'context'     => array( 'view', 'edit' ),
				),
				'date_start'    => array(
					'description' => __( 'Course progress item start date in GMT.', 'masteriyo' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view', 'edit' ),
				),
				'date_complete' => array(
					'description' => __( 'Course progress item complete date in GMT.', 'masteriyo' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view', 'edit' ),
				),
				'date_update'   => array(
					'description' => __( 'Course progress item update date in GMT.', 'masteriyo' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view', 'edit' ),
				),
			),
		);

		return $schema;
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
		$id                   = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
		$course_progress_item = masteriyo( 'course-progress-item' );

		if ( 0 !== $id ) {
			$course_progress_item->set_id( $id );
			$course_progress_item_repo = masteriyo( 'course-progress-item.store' );
			$course_progress_item_repo->read( $course_progress_item );
		}

		if ( isset( $request['progress_id'] ) ) {
			$course_progress_item->set_progress_id( $request['progress_id'] );
		}

		if ( isset( $request['item_id'] ) ) {
			$course_progress_item->set_item_id( $request['item_id'] );
		}

		if ( isset( $request['item_type'] ) ) {
			$course_progress_item->set_type( $request['item_type'] );
		}

		if ( isset( $request['user_id'] ) ) {
			$course_progress_item->set_user_id( $request['user_id'] );
		}

		// Course progress item completion.
		if ( isset( $request['completed'] ) ) {
			$course_progress_item->set_completed( $request['completed'] );
		}

		// Activity start date.
		if ( isset( $request['date_start'] ) ) {
			$course_progress_item->set_date_start( $request['date_start'] );
		}

		// Activity update date.
		if ( isset( $request['date_update'] ) ) {
			$course_progress_item->set_date_update( $request['date_update'] );
		}

		// Activity complete date.
		if ( isset( $request['date_complete'] ) ) {
			$course_progress_item->set_date_complete( $request['date_complete'] );
		}

		/**
		 * Filters an object before it is inserted via the REST API.
		 *
		 * The dynamic portion of the hook name, `$this->object_type`,
		 * refers to the object type slug.
		 *
		 * @since 0.1.0
		 *
		 * @param Model         $course_progress_item  Object object.
		 * @param WP_REST_Request $request  Request object.
		 * @param bool            $creating If is creating a new object.
		 */
		return apply_filters( "masteriyo_rest_pre_insert_{$this->object_type}_object", $course_progress_item, $request, $creating );
	}

	/**
	 * Get objects.
	 *
	 * @since  0.1.0
	 * @param  array $query_args Query args.
	 * @return array
	 */
	protected function get_objects( $query_args ) {
		$query   = new CourseProgressItemQuery( $query_args );
		$objects = $query->get_course_progress_items();

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
}
