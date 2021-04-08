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
class CommentsController extends PostsController {
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
	protected $rest_base = 'comments';

	/**
	 * Post Type.
	 *
	 * @var string
	 */
	protected $object_type = 'comment';

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
					// 'args'                => $this->get_collection_params(),
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
	public function get_collection_params() {}

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
			$comment = masteriyo( 'comment' );
			$comment->set_id( $id );
			$comment_repo = masteriyo( 'comment.store' );
			$comment_repo->read( $comment );
		} catch ( \Exception $e ) {
			return false;
		}

		return $comment;
	}

	/**
	 * Get objects.
	 *
	 * @since  0.1.0
	 * @param  array $query_args Query args.
	 * @return array
	 */
	protected function get_objects( $query_args ) {
		$comments    = get_comments( $query_args );
		$total_posts = count( $comments );

		if ( $total_posts < 1 ) {
			// Out-of-bounds, run the query again without LIMIT for total count.
			unset( $query_args['paged'] );
			$comments    = get_comments( $query_args );
			$total_posts = count( $comments );
		}

		return array(
			'objects' => array_filter( array_map( array( $this, 'get_object' ), $comments ) ),
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
		$data     = $this->get_comment_data( $object, $context );
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
	 * Get comment data.
	 *
	 * @param comment $comment Comment instance.
	 * @param string  $context Request context.
	 *                        Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_comment_data( $comment, $context = 'view' ) {
		$data = array(
			'comment_ID'            => $comment->get_id(),
			'comment_author'        => $comment->get_comment_author( $context ),
			'comment_author_email'  => $comment->get_comment_author_email( $context ),
			'comment_author_IP'     => $comment->get_comment_author_IP( $context ),
			'comment_date'          => $comment->get_comment_date( $context ),
			'comment_date_gmt'      => $comment->get_comment_date_gmt( $context ),
			'comment_content'       => $comment->get_comment_author( $context ),
			'comment_karma'         => $comment->get_comment_karma( $context ),
			'comment_approved'      => $comment->get_comment_approved( $context ),
			'comment_agent'         => $comment->get_comment_agent( $context ),
			'comment_type'          => $comment->get_comment_type( $context ),
			'comment_parent'        => $comment->get_comment_parent( $context ),
			'user_id'               => $comment->get_user_id( $context ),
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
			'offset'  => $request['offset'],
			'order'   => $request['order'],
			'orderby' => $request['orderby'],
			'paged'   => $request['page'],
			's'       => $request['search'],
			'role'    => $request['role'],
			'number'  => $request['per_page'],
		);

		if ( 'date' === $args['orderby'] ) {
			$args['orderby'] = 'date ID';
		}

		/**
		 * Filter the query arguments for a request.
		 *
		 * Enables adding extra arguments or setting defaults for a post
		 * collection request.
		 *
		 * @param array           $args    Key value array of query var to query value.
		 * @param WP_REST_Request $request The request used.
		 */
		$args = apply_filters( "masteriyo_rest_{$this->post_type}_object_query", $args, $request );

		return $args;
	}

	/**
	 * Get the Comment's schema, conforming to JSON Schema.
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
				'comment_ID'      => array(
					'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'comment_post_ID'      => array(
					'description' => __( 'Comment Post ID', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'comment_author'  => array(
					'description' => __( 'Comment Author.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'comment_author_email' => array(
					'description'      => __( 'Comment Author Email.', 'masteriyo' ),
					'type'             => 'string',
					'context'          => array( 'view', 'edit' ),
				),
				'comment_author_url'           => array(
					'description' => __( 'Comment Author URL.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'comment_author_IP'             => array(
					'description' => __( 'Comment Author IP', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'comment_date'      => array(
					'description' => __( 'Comment Date.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'comment_date_gmt'  => array(
					'description' => __( 'Comment Date GMT.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'comment_content'          => array(
					'description' => __( 'Comment Description.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'comment_karma'         => array(
					'description' => __( 'Comment Karma.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'comment_approved'             => array(
					'description' => __( 'Comment Approved.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'comment_agent'           => array(
					'description' => __( 'Comment Agent.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'comment_type'            => array(
					'description' => __( 'Comment Type.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'comment_parent'          => array(
					'description' => __( 'Comment Parent.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'user_id'         => array(
					'description' => __( 'The User ID.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'meta_data'            => array(
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
	 * Prepare a single comment object for create or update.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|Model
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		global $masteriyo_container;

		$id   = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
		$comment = masteriyo( 'comment' );

		if ( 0 !== $id ) {
			$comment->set_id( $id );
			$comment_repo = masteriyo( \ThemeGrill\Masteriyo\Repository\CommentRepository::class );
			$comment_repo->read( $comment );
		}

		// Comment Author.
		if ( isset( $request['comment_author'] ) ) {
			$comment->set_comment_author( $request['comment_author'] );
		}

		// Comment Author Email.
		if ( isset( $request['comment_author_email'] ) ) {
			$comment->set_comment_author_email( $request['comment_author_email'] );
		}

		// Comment Author URL.
		if ( isset( $request['comment_author_url'] ) ) {
			$comment->set_comment_author_url( $request['comment_author_url'] );
		}

		// Comment Author IP.
		if ( isset( $request['comment_author_IP'] ) ) {
			$comment->set_comment_author_IP( $request['comment_author_IP'] );
		}

		// Comment Date.
		if ( isset( $request['comment_date'] ) ) {
			$comment->set_comment_date( $request['comment_date'] );
		}
	
		// Comment Date GMT.
		if ( isset( $request['comment_date_gmt'] ) ) {
			$comment->set_comment_date_gmt( $request['comment_date_gmt'] );
		}

		// Comment Content.
		if ( isset( $request['comment_content'] ) ) {
			$comment->set_comment_content( $request['comment_content'] );
		}

		// Comment Karma.
		if ( isset( $request['comment_karma'] ) ) {
			$comment->set_comment_karma( $request['comment_karma'] );
		}

		// Comment Approved.
		if ( isset( $request['comment_approved'] ) ) {
			$comment->set_comment_approved( $request['comment_approved'] );
		}

		// Comment Agent.
		if ( isset( $request['comment_agent'] ) ) {
			$comment->set_comment_agent( $request['comment_agent'] );
		}

		// Comment Type.
		if ( isset( $request['comment_type'] ) ) {
			$comment->set_comment_type( $request['comment_type'] );
		}

		// Comment Parent.
		if ( isset( $request['comment_parent'] ) ) {
			$comment->set_comment_parent( $request['comment_parent'] );
		}

		// User ID.
		if ( isset( $request['user_id'] ) ) {
			$comment->set_user_id( $request['user_id'] );
		}

		// Allow set meta_data.
		if ( isset( $request['meta_data'] ) && is_array( $request['meta_data'] ) ) {
			foreach ( $request['meta_data'] as $meta ) {
				$comment->update_meta_data( $meta['key'], $meta['value'], isset( $meta['id'] ) ? $meta['id'] : '' );
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
		return apply_filters( "masteriyo_rest_pre_insert_{$this->object_type}_object", $comment, $request, $creating );
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

		$user = get_user_by( 'id', (int) $request['id'] );

		if ( $user && ! $this->permission->rest_check_users_manipulation_permissions( 'read' ) ) {
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
	 * Check if a given request has access to read items.
	 *
	 * @since 0.1.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|boolean
	 */
	public function get_items_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( ! $this->permission->rest_check_users_manipulation_permissions( 'read' ) ) {
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
	 * Check if a given request has access to create an item.
	 *
	 * @since 0.1.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|boolean
	 */
	public function create_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( ! $this->permission->rest_check_users_manipulation_permissions( 'create' ) ) {
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
	 * Check if a given request has access to delete an item.
	 *
	 * @since 0.1.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|boolean
	 */
	public function delete_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		$user_id = (int) $request['id'];

		if ( get_current_user_id() === $user_id ) {
			return new \WP_Error(
				'masteriyo_cannot_delete_yourself',
				__( 'Sorry, you cannot delete yourself.', 'masteriyo' )
			);
		}

		$user = get_user_by( 'id', $user_id );

		if ( $user && ! $this->permission->rest_check_users_manipulation_permissions( 'delete' ) ) {
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
	 *
	 * @return WP_Error|boolean
	 */
	public function update_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		$user = get_user_by( 'id', (int) $request['id'] );

		if ( $user && ! $this->permission->rest_check_users_manipulation_permissions( 'edit' ) ) {
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
	 * Check permissions for an item.
	 *
	 * @since 0.1.0
	 *
	 * @param string $object_type Object type.
	 * @param string $context   Request context.
	 * @param int    $object_id Object ID.
	 *
	 * @return bool
	 */
	protected function check_item_permission( $object_type, $context = 'read', $object_id = 0 ) {
		return true;
	}
}