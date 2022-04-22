<?php
/**
 * Course progress controller.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\RestApi\Controllers\Version1;
 */

namespace Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Enums\CourseProgressStatus;
use Masteriyo\ModelException;
use Masteriyo\Helper\Permission;
use Masteriyo\Models\Order\OrderItem;
use Masteriyo\Exceptions\RestException;
use Masteriyo\Models\CourseProgress;
use Masteriyo\Query\CourseProgressQuery;
use Masteriyo\Query\CourseProgressItemQuery;

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
	 * @param Permission $permission Permission object.
	 */
	public function __construct( Permission $permission = null ) {
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
							'default'     => true,
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
			'description'       => __( 'User ID', 'masteriyo' ),
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
			'default'           => 0,
		);

		$params['course_id'] = array(
			'description'       => __( 'Course ID', 'masteriyo' ),
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['status'] = array(
			'description'       => __( 'User activity status.', 'masteriyo' ),
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_title',
			'validate_callback' => 'rest_validate_request_arg',
			'enum'              => CourseProgressStatus::all(),
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
	 * @since 1.0.0
	 *
	 * @param  int|CourseProgress $id Object ID.
	 * @return object Model object or WP_Error object.
	 */
	protected function get_object( $id ) {
		try {
			$id              = is_a( $id, 'Masteriyo\Database\Model' ) ? $id->get_id() : $id;
			$course_progress = masteriyo_get_course_progress( $id );
		} catch ( \Exception $e ) {
			return false;
		}

		return $course_progress;
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
		$data    = $this->get_course_progress_data( $object, $context );

		$data     = $this->add_additional_fields_to_object( $data, $request );
		$data     = $this->filter_response_by_context( $data, $context );
		$response = rest_ensure_response( $data );

		/**
		 * Filter the data for a response.
		 *
		 * The dynamic portion of the hook name, $this->object_type,
		 * refers to object type being prepared for the response.
		 *
		 * @since 1.0.0
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
	 * @since 1.0.0
	 *
	 * @param CourseProgress  $course_progress User activity instance.
	 * @param string $context Request context.
	 *                        Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_course_progress_data( $course_progress, $context = 'view' ) {
		$course  = masteriyo_get_course( $course_progress->get_course_id( $context ) );
		$summary = $this->get_course_progress_summary( $course_progress );

		if ( 0 === $summary['total']['pending'] ) {
			$course_progress->set_status( CourseProgressStatus::COMPLETED );
		}

		$data = array(
			'id'               => $course_progress->get_id( $context ),
			'user_id'          => $course_progress->get_user_id( $context ),
			'course_id'        => $course_progress->get_course_id( $context ),
			'course_permalink' => get_the_permalink( $course_progress->get_course_id( $context ) ),
			'name'             => $course ? wp_specialchars_decode( $course->get_name( $context ) ) : '',
			'status'           => $course_progress->get_status( $context ),
			'started_at'       => masteriyo_rest_prepare_date_response( $course_progress->get_started_at( $context ) ),
			'modified_at'      => masteriyo_rest_prepare_date_response( $course_progress->get_modified_at( $context ) ),
			'completed_at'     => masteriyo_rest_prepare_date_response( $course_progress->get_completed_at( $context ) ),
			'items'            => $this->get_course_progress_items( $course_progress ),
			'summary'          => $summary,
		);

		return $data;
	}

	/**
	 * Prepare objects query.
	 *
	 * @since  1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$args = wp_parse_args(
			$request->get_params(),
			array(
				'page'         => 1,
				'per_page'     => 10,
				'user_id'      => 0,
				'status'       => '',
				'started_at'   => null,
				'modified_at'  => null,
				'completed_at' => null,
			)
		);

		$args['paged'] = $args['page'];

		if ( masteriyo_is_current_user_student() ) {
			$args['user_id'] = get_current_user_id();
		}

		/**
		 * Filter the query arguments for a request.
		 *
		 * Enables adding extra arguments or setting defaults for a post
		 * collection request.
		 *
		 * @since 1.0.0
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
				'id'           => array(
					'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'user_id'      => array(
					'description' => __( 'User ID', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'course_id'    => array(
					'description' => __( 'Course ID', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'status'       => array(
					'description' => __( 'Course progress status.', 'masteriyo' ),
					'type'        => 'string',
					'enum'        => CourseProgressStatus::all(),
					'context'     => array( 'view', 'edit' ),
				),
				'started_at'   => array(
					'description' => __( 'Course progress start date in GMT.', 'masteriyo' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view', 'edit' ),
				),
				'modified_at'  => array(
					'description' => __( 'Course progress modified date in GMT.', 'masteriyo' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view', 'edit' ),
				),
				'completed_at' => array(
					'description' => __( 'Course progress complete date in GMT.', 'masteriyo' ),
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
	 * @since 1.0.0
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
			if ( ! is_null( $course_id ) ) {
				$course_progress->set_course_id( $course_id );
			}
		} catch ( RestException $e ) {
			return new \WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
		}

		// Activity status.
		if ( isset( $request['status'] ) ) {
			$course_progress->set_status( $request['status'] );
		}

		// Activity start date.
		if ( isset( $request['started_at'] ) ) {
			$course_progress->set_started_at( $request['started_at'] );
		}

		// Activity update date.
		if ( isset( $request['modified_at'] ) ) {
			$course_progress->set_modified_at( $request['modified_at'] );
		}

		// Activity complete date.
		if ( isset( $request['completed_at'] ) ) {
			$course_progress->set_completed_at( $request['completed_at'] );
		}

		/**
		 * Filters an object before it is inserted via the REST API.
		 *
		 * The dynamic portion of the hook name, `$this->object_type`,
		 * refers to the object type slug.
		 *
		 * @since 1.0.0
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
	 * @since  1.0.0
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
	 * @since 1.0.0
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
	 * @since 1.0.0
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
	 * @since 1.0.0
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

		$course = masteriyo_get_course( $request['course_id'] );
		if ( ! is_null( $course ) && 'open' === $course->get_access_mode() ) {
			return true;
		}

		if ( ! $this->permission->rest_check_course_progress_permissions( 'create' ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_create',
				__( 'Sorry, you are not allowed to create resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Check if a given request has access to create/update an item.
	 *
	 * @since 1.0.0
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

		$progress = masteriyo_get_course_progress( (int) $request['id'] );

		if ( $progress && ! $this->permission->rest_check_course_progress_permissions( 'update', $request['id'] ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_update',
				__( 'Sorry, you are not allowed to update resources.', 'masteriyo' ),
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
	 * @since 1.0.0
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

		$progress = masteriyo_get_course_progress( (int) $request['id'] );

		if ( $progress && ! $this->permission->rest_check_course_progress_permissions( 'delete', $request['id'] ) ) {
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
	 * Check permissions for an item.
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
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

		// Return the auto generated guest user id.
		if ( ! is_user_logged_in() ) {
			return $user_id;
		}

		// Validate the user ID.
		$user = get_user_by( 'id', $user_id );
		if ( is_user_logged_in() && ! $user ) {
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
	 * @since 1.0.0
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
			if ( ! $course_post || 'mto-course' !== $course_post->post_type ) {
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
	 * Validate start course progress request data.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	protected function validate_start_course_progress( $request ) {
		$item_types = array( 'lesson', 'quiz' );

		if ( ! isset( $request['items'] ) || empty( $request['items'] ) ) {
			return;
		}

		foreach ( $request['items'] as $item ) {
			if ( ! isset( $item['item_id'] ) ) {
				return new \WP_Error(
					'rest_missing_callback_param',
					/* translators: %s: item id */
					sprintf( __( 'Missing parameter(s): %s', 'masteriyo' ), 'item_id' ),
					array( 'status' => rest_authorization_required_code() )
				);
			}

			if ( ! isset( $item['item_type'] ) ) {
				return new \WP_Error(
					'rest_missing_callback_param',
					/* translators: %s: item type */
					sprintf( __( 'Missing parameter(s): %s', 'masteriyo' ), 'item_type' ),
					array( 'status' => rest_authorization_required_code() )
				);
			}

			if ( ! in_array( $item['item_type'], $item_types, true ) ) {
				return new \WP_Error(
					'rest_invalid_param',
					/* translators: %s: item type */
					sprintf( __( 'Invalid parameter(s): %s', 'masteriyo' ), 'item_type' ),
					array(
						'status' => rest_authorization_required_code(),
						'params' => array(
							'item_type' => sprintf(
								/* translators: %s: item types */
								__( 'item_type is not of type %s', 'masteriyo' ),
								implode( ' and ', $item_types )
							),
						),
					)
				);
			}

			if ( isset( $item['completed'] ) && ! is_bool( $item['completed'] ) ) {
				return new \WP_Error(
					'rest_invalid_param',
					/* translators: %s: item type */
					sprintf( __( 'Invalid parameter(s): %s', 'masteriyo' ), 'item_type' ),
					array(
						'status' => rest_authorization_required_code(),
						'params' => array(
							'completed' => __( 'completed is not of type boolean', 'masteriyo' ),
						),
					)
				);
			}
		}

		return true;
	}

	/**
	 * Save course progress items if any.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @param CourseProgress $course_progress Course progress item.
	 */
	protected function save_course_progress_items( $request, $course_progress ) {
		global $wpdb;

		$user_id = get_current_user_id();
		$query   = new CourseProgressItemQuery(
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

		$progress_items = $request['items'];

		// Create a map of progress items which are from DB.
		foreach ( $items as $item ) {
			$item_key               = $item->get_item_id() . ':' . $item->get_user_id() . ':' . $item->get_item_type() . ':' . $item->get_progress_id();
			$items_map[ $item_key ] = $item;
		}

		foreach ( $progress_items as $progress_item ) {
			$item_key               = $progress_item['item_id'] . ':' . $user_id . ':' . $progress_item['item_type'] . ':' . $course_progress->get_id();
			$item_obj               = isset( $items_map[ $item_key ] ) ? $items_map[ $item_key ] : masteriyo( 'course-progress-item' );
			$items_map[ $item_key ] = $item_obj;

			$item_obj->set_item_id( $progress_item['item_id'] );
			$item_obj->set_item_type( $progress_item['item_type'] );
			$item_obj->set_completed( isset( $progress_item['completed'] ) ? $progress_item['completed'] : false );
			$item_obj->set_user_id( $user_id );
			$item_obj->set_progress_id( $course_progress->get_id() );

			$item_obj->save();
		}

		return array_values( $items_map );
	}

	/**
	 * Get course progress item data.
	 *
	 * @since 1.0.0
	 *
	 * @param CourseProgressItem  $course_progress_item Course progress item object.
	 * @param string $context Request context.
	 *                        Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_course_progress_item_data( $course_progress_item, $context = 'view' ) {
		$video = '';

		if ( 'lesson' === $course_progress_item->get_item_type() ) {
			$video = get_post_meta( $course_progress_item->get_item_id( $context ), '_video_source_url', true );
		}

		$data = array(
			'item_id'    => $course_progress_item->get_item_id( $context ),
			'item_title' => wp_specialchars_decode( $course_progress_item->get_item_title( $context ) ),
			'item_type'  => $course_progress_item->get_item_type( $context ),
			'completed'  => $course_progress_item->get_completed( $context ),
			'video'      => ! empty( trim( $video ) ),
		);

		return apply_filters( 'masteriyo_course_progress_item_data', $data, $course_progress_item, $context );
	}

	/**
	 * Get course progress items.
	 *
	 * @since 1.0.0
	 *
	 * @param CourseProgress $course_progress
	 * @return array
	 */
	protected function get_course_progress_items( $course_progress ) {
		if ( is_user_logged_in() ) {
			$progress_items = $this->get_course_progress_items_from_db( $course_progress );
		} else {
			$progress_items = $this->get_course_progress_items_from_session( $course_progress );
		}

		return $progress_items;
	}

	/**
	 * Get course progress items from database.
	 *
	 * @since 1.3.8
	 *
	 * @param \Masteriyo\Models\CourseProgress $course_progress Course progress object.
	 * @return array
	 */
	protected function get_course_progress_items_from_db( $course_progress ) {
		$progress_items = array();

		foreach ( $course_progress->get_items() as $progress_item ) {
			$progress_items[ $progress_item->get_item_id() ] = $progress_item;
		}

		$query = new \WP_Query(
			array(
				'post_type'      => array( 'mto-section', 'mto-lesson', 'mto-quiz' ),
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'meta_key'       => '_course_id',
				'meta_value'     => $course_progress->get_course_id( 'edit' ),
			)
		);

		$sections = $this->filter_course_sections( $query->posts );

		foreach ( $sections as $id => $section ) {
			$sections[ $id ]['contents'] = $this->filter_course_lessons_quizzes( $query->posts, $section['item_id'] );
		}

		return $sections;
	}

	/**
	 * Get course progress items from session.
	 *
	 * @since 1.3.8
	 *
	 * @param \Masteriyo\Models\CourseProgress $course_progress Course progress object.
	 * @return array
	 */
	protected function get_course_progress_items_from_session( $course_progress ) {
		$session = masteriyo( 'session' );

		$progress_items_from_db = $this->get_course_progress_items_from_db( $course_progress );

		foreach ( $progress_items_from_db as $index => $progress_item_from_db ) {
			if ( 'section' !== $progress_item_from_db['item_type'] ) {
				continue;
			}

			$lesson_quizzes = $progress_item_from_db['contents'];

			// Convert the actual all course progress items to dictionary.
			$lesson_quizzes_map = array_reduce(
				$lesson_quizzes,
				function( $result, $lesson_quiz ) use ( $course_progress ) {
					$key            = $lesson_quiz['item_id'] . ':' . $lesson_quiz['item_type'] . ':' . $course_progress->get_course_id();
					$result[ $key ] = $lesson_quiz;

					return $result;
				},
				array()
			);

			// Get the course progress items of the specific course from session.
			$lesson_quizzes_from_session = array_filter(
				$session->get( 'course_progress_items', array() ),
				function( $lesson_quiz ) use ( $course_progress ) {
					return $course_progress->get_course_id() === $lesson_quiz['course_id'];
				}
			);

			// Merge course progress items from session to the actual course progress items list.
			foreach ( $lesson_quizzes_from_session as $key => $lesson_quiz ) {
				if ( isset( $lesson_quizzes_map[ $key ] ) ) {
					$lesson_quizzes_map[ $key ]['completed'] = $lesson_quiz['completed'];
				}
			}

			$progress_item_from_db['contents'] = array_values( $lesson_quizzes_map );
			$progress_items_from_db[ $index ]  = $progress_item_from_db;
		}

		return $progress_items_from_db;
	}

	/**
	 * Filter course sections.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post[] $posts
	 * @return array
	 */
	protected function filter_course_sections( $posts ) {
		$sections = array_filter(
			$posts,
			function( $post ) {
				return 'mto-section' === $post->post_type;
			}
		);

		// Sort sections by menu order in ascending order.
		usort(
			$sections,
			function( $a, $b ) {
				if ( $a->menu_order === $b->menu_order ) {
					return 0;
				}

				return $a->menu_order > $b->menu_order ? 1 : -1;
			}
		);

		$sections = array_map(
			function( $section ) {
				return array(
					'item_id'    => $section->ID,
					'item_title' => wp_specialchars_decode( $section->post_title ),
					'item_type'  => str_replace( 'mto-', '', $section->post_type ),
				);
			},
			$sections
		);

		return $sections;
	}

	/**
	 * Filter course lessons and quizzes.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post[] $posts
	 * @param int $section_id Section ID.
	 * @return array
	 */
	protected function filter_course_lessons_quizzes( $posts, $section_id ) {
		$lessons_quizzes = array_filter(
			$posts,
			function( $post ) use ( $section_id ) {
				return in_array( $post->post_type, array( 'mto-lesson', 'mto-quiz' ), true ) && $section_id === $post->post_parent;
			}
		);

		// Sort lessons and quizzes by menu order in ascending order.
		usort(
			$lessons_quizzes,
			function( $a, $b ) {
				if ( $a->menu_order === $b->menu_order ) {
					return 0;
				}

				return $a->menu_order > $b->menu_order ? 1 : -1;
			}
		);

		$lessons_quizzes = array_filter(
			array_map(
				function( $lesson_quiz ) {
					$progress_item = $this->get_course_progress_item( $lesson_quiz );

					if ( ! $progress_item ) {
						$progress_item = masteriyo( 'course-progress-item' );
						$progress_item->set_item_id( $lesson_quiz->ID );
						$progress_item->set_item_type( str_replace( 'mto-', '', $lesson_quiz->post_type ) );
					}

					return $this->get_course_progress_item_data( $progress_item );
				},
				$lessons_quizzes
			)
		);

		return $lessons_quizzes;
	}

	/**
	 * Get the course progress item.
	 *
	 * @since 1.3.8
	 *
	 * @param \WP_Post $lesson_quiz Either lesson or quiz post type.
	 * @return Masteriyo\Models\CourseProgressItem
	 */
	protected function get_course_progress_item( $lesson_quiz ) {
		$course_progress_item = null;

		if ( is_user_logged_in() ) {
			$query = new CourseProgressItemQuery(
				array(
					'user_id' => masteriyo_get_current_user_id(),
					'item_id' => $lesson_quiz->ID,
				)
			);

			$course_progress_item = current( $query->get_course_progress_items() );
		} else {
			$session = masteriyo( 'session' );

			$course_progress_items = $session->get( 'course_progress_items', array() );

			if ( isset( $course_progress_items[ $lesson_quiz->ID ] ) ) {
				$course_progress_item = masteriyo( 'course-progress-item' );
				$course_progress_item->set_item_id( $lesson_quiz->ID );
				$course_progress_item->set_item_type( str_replace( 'mto-', '', $lesson_quiz->post_type ) );
				$course_progress_item->set_completed( $course_progress_items[ $lesson_quiz->ID ]['completed'] );
			}
		}

		return apply_filters( 'masteriyo_rest_get_course_progress_item', $course_progress_item, $lesson_quiz, $this );
	}

	/**
	 * Save an object data.
	 *
	 * @since  1.3.8
	 *
	 * @param  WP_REST_Request $request  Full details about the request.
	 * @param  bool            $creating If is creating a new object.
	 *
	 * @return Model|WP_Error
	 */
	protected function save_object( $request, $creating = false ) {
		// Save the object to database if the user is logged in.
		if ( is_user_logged_in() ) {
			$object = parent::save_object( $request, $creating );
			return $object;
		}

		return $this->save_object_in_session( $request, $creating );
	}

	/**
	 * Save an object in the session.
	 *
	 * @since  1.3.8
	 *
	 * @param  WP_REST_Request $request  Full details about the request.
	 * @param  bool            $creating If is creating a new object.
	 *
	 * @return Model|WP_Error
	 */
	protected function save_object_in_session( $request, $creating = false ) {
		try {
			$session = masteriyo( 'session' );

			$object = $this->prepare_object_for_database( $request, $creating );

			if ( is_wp_error( $object ) ) {
				return $object;
			}

			if ( ! $object->get_started_at() ) {
				$object->set_started_at( current_time( 'mysql' ), true );
			}

			$object->set_modified_at( current_time( 'mysql' ), true );

			if ( CourseProgressStatus::COMPLETED === $object->get_status() ) {
				$object->set_completed_at( current_time( 'mysql' ), true );
			}

			$course_progress = $session->get( 'course_progress', array() );

			if ( ! isset( $course_progress[ $object->get_course_id() ] ) ) {
				$course_progress[] = $object->get_data();
			}

			$session->put( 'course_progress', $course_progress );

			return $object;
		} catch ( ModelException $e ) {
			return new \WP_Error( $e->getErrorCode(), $e->getMessage(), $e->getErrorData() );
		} catch ( RestException $e ) {
			return new \WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
		}
	}

	/**
	 * Get course progress summary.
	 *
	 * @since 1.3.8
	 *
	 * @param \Masteriyo\Models\CourseProgress $course_progress Course progress object.
	 *
	 * @return array
	 */
	protected function get_course_progress_summary( $course_progress ) {
		if ( is_user_logged_in() ) {
			$summary = $course_progress->get_summary( 'all' );
		} else {
			$summary = $this->get_course_progress_summary_from_session( $course_progress );
		}

		return apply_filters( "masteriyo_rest_{$this->object_type}_summary", $summary, $course_progress, $this );
	}

	/**
	 * Get the summary of course progress items by type.
	 *
	 * @since 1.3.8
	 *
	 * @param array $course_progress_items Array of course progress items.
	 * @param string $type Course progress item type. (lesson and quiz).
	 *
	 * @return array
	 */
	protected function get_course_progress_item_summary( $course_progress_items, $type ) {
		// Get the specific type of course progress items only.
		$course_progress_items = array_filter(
			$course_progress_items,
			function( $course_progress_item ) use ( $type ) {
				return $type === $course_progress_item['item_type'];
			}
		);

		// Get the completed course progress items.
		$completed_course_progress_items = array_filter(
			$course_progress_items,
			function( $course_progress_item ) {
				return $course_progress_item['completed'];
			}
		);

		return array(
			'completed' => count( $completed_course_progress_items ),
			'pending'   => count( $course_progress_items ) - count( $completed_course_progress_items ),
		);
	}

	/**
	 * Get course progress summary from session for guest user.
	 *
	 * @since 1.3.8
	 *
	 * @param Masteriyo\Models\CourseProgress $course_progress Course progress object.
	 * @return array
	 */
	protected function get_course_progress_summary_from_session( $course_progress ) {
		$course_progress_items = $this->get_course_progress_items_from_session( $course_progress );

		// Get the lessons and quizzes from the course progress items.
		$lesson_quizzes_items = array_reduce(
			$course_progress_items,
			function( $result, $course_progress_item ) {
				if ( ! empty( $course_progress_item['contents'] ) ) {
					$result = $course_progress_item['contents'];
				}

				return $result;
			},
			array()
		);

		$summary['lesson'] = $this->get_course_progress_item_summary( $lesson_quizzes_items, 'lesson' );
		$summary['quiz']   = $this->get_course_progress_item_summary( $lesson_quizzes_items, 'quiz' );

		$summary['total'] = array(
			'completed' => $summary['lesson']['completed'] + $summary['quiz']['completed'],
			'pending'   => $summary['lesson']['pending'] + $summary['quiz']['pending'],
		);

		return $summary;
	}
}
