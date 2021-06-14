<?php
/**
 * Quiz progress controller.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\RestApi\Controllers\Version1;
 */

namespace ThemeGrill\Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use ThemeGrill\Masteriyo\Helper\Permission;
use ThemeGrill\Masteriyo\Models\Order\OrderItem;
use ThemeGrill\Masteriyo\Query\UserActivityQuery;
use ThemeGrill\Masteriyo\Query\QuizProgressQuery;
use ThemeGrill\Masteriyo\RestApi\Controllers\Version1\UserActivitiesController;

/**
 * User activities controller class.
 */
class QuizProgressController extends UserActivitiesController {

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
	protected $rest_base = 'quiz-progress';

	/**
	 * Object type.
	 *
	 * @var string
	 */
	protected $object_type = 'quiz_progress';

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $post_type = 'quiz_progress';

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
		$params = parent::get_collection_params();

		unset( $params['activity_type'] );
		unset( $params['item_id'] );

		$params['quiz_id'] = array(
			'description'       => __( 'Quiz ID.', 'masteriyo' ),
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
			'default'           => 0,
		);

		return $params;
	}

	/**
	 * Get object.
	 *
	 * @since 0.1.0
	 *
	 * @param  int|UserActivity $id Object ID.
	 * @return object Model object or WP_Error object.
	 */
	protected function get_object( $id ) {
		try {
			$id            = is_a( $id, 'ThemeGrill\Masteriyo\Database\Model' ) ? $id->get_id() : $id;
			$quiz_progress = masteriyo_get_quiz_progress( $id );
		} catch ( \Exception $e ) {
			return false;
		}

		return $quiz_progress;
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
		$data    = $this->get_quiz_progress_data( $object, $context );

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
	 * @param UserActivity  $quiz_progress User activity instance.
	 * @param string $context Request context.
	 *                        Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_quiz_progress_data( $quiz_progress, $context = 'view' ) {
		$data = array(
			'id'            => $quiz_progress->get_id( $context ),
			'user_id'       => $quiz_progress->get_user_id( $context ),
			'quiz_id'       => $quiz_progress->get_quiz_id( $context ),
			'type'          => $quiz_progress->get_type( $context ),
			'status'        => $quiz_progress->get_status( $context ),
			'date_start'    => masteriyo_rest_prepare_date_response( $quiz_progress->get_date_start( $context ) ),
			'date_update'   => masteriyo_rest_prepare_date_response( $quiz_progress->get_date_update( $context ) ),
			'date_complete' => masteriyo_rest_prepare_date_response( $quiz_progress->get_date_complete( $context ) ),
			'items'         => $quiz_progress->get_items(),
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

		if ( isset( $args['orderby'] ) && 'id' === $args['orderby'] ) {
			unset( $args['orderby'] );
			$args['orderby'] = 'activity_id';
		}

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
				'quiz_id'       => array(
					'description' => __( 'Quiz ID.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'status'        => array(
					'description' => __( 'Quiz progress status.', 'masteriyo' ),
					'type'        => 'string',
					'enum'        => masteriyo_get_user_activity_statuses(),
					'context'     => array( 'view', 'edit' ),
				),
				'date_start'    => array(
					'description' => __( 'Quiz progress start date in GMT.', 'masteriyo' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view', 'edit' ),
				),
				'date_complete' => array(
					'description' => __( 'Quiz progress complete date in GMT.', 'masteriyo' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view', 'edit' ),
				),
				'date_update'   => array(
					'description' => __( 'Quiz progress update date in GMT.', 'masteriyo' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view', 'edit' ),
				),
				'items'         => array(
					'description' => __( 'Quiz items (lesson, quiz) ', 'masteriyo' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'         => 'object',
						'id'           => array(
							'description' => __( 'ID.', 'masteriyo' ),
							'type'        => 'integer',
							'readonly'    => true,
							'context'     => array( 'view', 'edit' ),
						),
						'item_id'      => array(
							'description' => __( 'Quiz item ID (lesson, quiz).', 'masteriyo' ),
							'type'        => 'integer',
							'required'    => true,
							'context'     => array( 'view', 'edit' ),
						),
						'item_type'    => array(
							'description' => __( 'Quiz item ID (lesson, quiz).', 'masteriyo' ),
							'type'        => 'integer',
							'context'     => array( 'view', 'edit' ),
						),
						'is_completed' => array(
							'description' => __( 'Quiz item ID.', 'masteriyo' ),
							'type'        => 'boolean',
							'context'     => array( 'view', 'edit' ),
						),
					),
				),
			),
		);

		return $schema;
	}

	/**
	 * Prepare a single quiz progress for create or update.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|Model
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		$id            = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
		$quiz_progress = masteriyo( 'quiz-progress' );

		if ( 0 !== $id ) {
			$quiz_progress->set_id( $id );
			$quiz_progress_repo = masteriyo( 'quiz-progress.store' );
			$quiz_progress_repo->read( $quiz_progress );
		}

		// User ID.
		if ( isset( $request['user_id'] ) && ! empty( $request['user_id'] ) ) {
			$quiz_progress->set_user_id( $request['user_id'] );
		} else {
			$quiz_progress->set_user_id( get_current_user_id() );
		}

		// Validate the user ID.
		$user = get_user_by( 'id', $quiz_progress->get_user_id( 'edit' ) );
		if ( ! $user ) {
			return new \WP_Error( 'invalid_user_id', __( 'Invalid user ID.', 'masteriyo' ) );
		}

		// Quiz ID.
		if ( isset( $request['quiz_id'] ) ) {
			$quiz_progress->set_quiz_id( $request['quiz_id'] );

			// Validate quiz ID.
			$quiz_post = get_post( $quiz_progress->get_quiz_id( 'edit' ) );
			if ( ! $quiz_post || 'quiz' !== $quiz_post->post_type ) {
				return new \WP_Error( 'invalid_quiz_id', __( 'Invalid quiz ID.', 'masteriyo' ) );
			}
		}

		// Activity status.
		if ( isset( $request['status'] ) ) {
			$quiz_progress->set_status( $request['status'] );
		}

		// Activity start date.
		if ( isset( $request['date_start'] ) ) {
			$quiz_progress->set_date_start( $request['date_start'] );
		}

		// Activity update date.
		if ( isset( $request['date_update'] ) ) {
			$quiz_progress->set_date_update( $request['date_update'] );
		}

		// Activity complete date.
		if ( isset( $request['date_complete'] ) ) {
			$quiz_progress->set_date_complete( $request['date_complete'] );
		}

		// Acitivity items.
		if ( isset( $request['items'] ) ) {
			$quiz_progress->set_items( $request['items'] );
		}

		/**
		 * Filters an object before it is inserted via the REST API.
		 *
		 * The dynamic portion of the hook name, `$this->object_type`,
		 * refers to the object type slug.
		 *
		 * @since 0.1.0
		 *
		 * @param Model         $quiz_progress  Object object.
		 * @param WP_REST_Request $request  Request object.
		 * @param bool            $creating If is creating a new object.
		 */
		return apply_filters( "masteriyo_rest_pre_insert_{$this->object_type}_object", $quiz_progress, $request, $creating );
	}

	/**
	 * Get objects.
	 *
	 * @since  0.1.0
	 * @param  array $query_args Query args.
	 * @return array
	 */
	protected function get_objects( $query_args ) {
		$query   = new QuizProgressQuery( $query_args );
		$objects = $query->get_quiz_progress();

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

		$quiz_post = get_post( (int) $request['quiz_id'] );

		if ( ! $quiz_post || 'quiz' !== $quiz_post->post_type ) {
			return new \WP_Error( 'invalid_quiz_id', __( 'Invalid quiz ID.', 'masteriyo' ) );
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

		$quiz_progress = masteriyo_get_quiz_progress( (int) $request['id'] );

		if ( ! $quiz_progress || 'quiz' !== $quiz_progress->get_type() ) {
			return new \WP_Error( 'invalid_quiz_progress_id', __( 'Invalid quiz progress ID.', 'masteriyo' ) );
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
}
