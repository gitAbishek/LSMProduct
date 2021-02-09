<?php
/**
 * UsersController class.
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
 * UsersController class.
 */
class UsersController extends PostsController {
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
	protected $rest_base = 'users';

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $object_type = 'user';

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
	 * @param Permission $permission Permission instance.
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

		$params['role']     = array(
			'description'       => __( 'Limit result by role.', 'masteriyo' ),
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_key',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['per_page'] = array(
			'description'       => __( 'Number of users per page.', 'masteriyo' ),
			'type'              => 'number',
			'sanitize_callback' => function( $value ) {
				return intval( $value );
			},
			'validate_callback' => 'rest_validate_request_arg',
		);

		return $params;
	}
	/**
	 * Get object.
	 *
	 * @since 0.1.0
	 *
	 * @param  int|WP_user|Model $object Object ID or WP_user or Model.
	 *
	 * @return object Model object or WP_Error object.
	 */
	protected function get_object( $object ) {
		try {
			if ( is_int( $object ) ) {
				$id = $object;
			} else {
				$id = is_a( $object, '\WP_user' ) ? $object->ID : $object->get_id();
			}
			$user = masteriyo( 'user' );
			$user->set_id( $id );
			$user_repo = masteriyo( 'user.store' );
			$user_repo->read( $user );
		} catch ( \Exception $e ) {
			return false;
		}

		return $user;
	}

	/**
	 * Get objects.
	 *
	 * @since  0.1.0
	 * @param  array $query_args Query args.
	 * @return array
	 */
	protected function get_objects( $query_args ) {
		$users       = get_users( $query_args );
		$total_posts = count( $users );

		if ( $total_posts < 1 ) {
			// Out-of-bounds, run the query again without LIMIT for total count.
			unset( $query_args['paged'] );
			$users       = get_users( $query_args );
			$total_posts = count( $users );
		}

		return array(
			'objects' => array_filter( array_map( array( $this, 'get_object' ), $users ) ),
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
		$object->set_user_pass( null );

		$context  = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data     = $this->get_user_data( $object, $context );
		$data     = $this->add_additional_fields_to_object( $data, $request );
		$data     = $this->filter_response_by_context( $data, $context );
		$response = rest_ensure_response( $data );

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
	 * Get user data.
	 *
	 * @param User   $user User instance.
	 * @param string $context Request context.
	 *                        Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_user_data( $user, $context = 'view' ) {
		$data = array(
			'id'                   => $user->get_id(),
			'user_login'           => $user->get_user_login(),
			'user_pass'            => $user->get_user_pass(),
			'user_nicename'        => $user->get_user_nicename(),
			'user_email'           => $user->get_user_email(),
			'user_url'             => $user->get_user_url(),
			'user_registered'      => $user->get_user_registered(),
			'user_activation_key'  => $user->get_user_activation_key(),
			'user_status'          => $user->get_user_status(),
			'display_name'         => $user->get_display_name(),
			'nickname'             => $user->get_nickname(),
			'first_name'           => $user->get_first_name(),
			'last_name'            => $user->get_last_name(),
			'description'          => $user->get_description(),
			'rich_editing'         => $user->get_rich_editing(),
			'syntax_highlighting'  => $user->get_syntax_highlighting(),
			'comment_shortcuts'    => $user->get_comment_shortcuts(),
			'use_ssl'              => $user->get_use_ssl(),
			'show_admin_bar_front' => $user->get_show_admin_bar_front(),
			'locale'               => $user->get_locale(),
			'roles'                => $user->get_roles(),
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
	 * Get the User's schema, conforming to JSON Schema.
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
				'id'                   => array(
					'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'user_login'           => array(
					'description' => __( 'User login.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'user_nicename'        => array(
					'description' => __( 'User nicename.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'user_email'           => array(
					'description' => __( 'User email.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'user_url'             => array(
					'description' => __( 'Site url of the user.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'user_registered'      => array(
					'description' => __( 'User registered date', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'user_activation_key'  => array(
					'description' => __( 'User activation key.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'user_status'          => array(
					'description' => __( 'User status.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'display_name'         => array(
					'description' => __( 'Display name.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'nickname'             => array(
					'description' => __( 'User nickname.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'first_name'           => array(
					'description' => __( 'User first name.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'last_name'            => array(
					'description' => __( 'User last name.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'description'          => array(
					'description' => __( 'User description.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'rich_editing'         => array(
					'description' => __( 'Enable rich editing.', 'masteriyo' ),
					'type'        => 'boolean',
					'default'     => true,
					'context'     => array( 'view', 'edit' ),
				),
				'syntax_highlighting'  => array(
					'description' => __( 'Enable syntax highlighting.', 'masteriyo' ),
					'type'        => 'boolean',
					'default'     => true,
					'context'     => array( 'view', 'edit' ),
				),
				'comment_shortcuts'    => array(
					'description' => __( 'Enable comment shortcuts.', 'masteriyo' ),
					'type'        => 'boolean',
					'default'     => false,
					'context'     => array( 'view', 'edit' ),
				),
				'use_ssl'              => array(
					'description' => __( 'Use SSL.', 'masteriyo' ),
					'type'        => 'number',
					'context'     => array( 'view', 'edit' ),
				),
				'show_admin_bar_front' => array(
					'description' => __( 'Whether to show the admin bar on the frontend.', 'masteriyo' ),
					'type'        => 'boolean',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'locale'               => array(
					'description' => __( 'User specific locale.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'roles'                => array(
					'description' => __( 'List of roles.', 'masteriyo' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type' => 'string',
					),
				),
				'allcaps'              => array(
					'description' => __( 'List of all the capabilities of the user.', 'masteriyo' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type' => 'string',
					),
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
	 * Prepare a single user object for create or update.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|Model
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		global $masteriyo_container;

		$id   = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
		$user = $masteriyo_container->get( 'user' );

		if ( 0 !== $id ) {
			$user->set_id( $id );
			$user_repo = $masteriyo_container->get( \ThemeGrill\Masteriyo\Repository\UserRepository::class );
			$user_repo->read( $user );
		}

		// User's user_login.
		if ( isset( $request['user_login'] ) ) {
			$user->set_user_login( $request['user_login'] );
		}

		// User's user_pass.
		if ( isset( $request['user_pass'] ) ) {
			$user->set_user_pass( $request['user_pass'] );
		}

		// User's user_nicename.
		if ( isset( $request['user_nicename'] ) ) {
			$user->set_user_nicename( $request['user_nicename'] );
		}

		// User's user_email.
		if ( isset( $request['user_email'] ) ) {
			$user->set_user_email( $request['user_email'] );
		}

		// User's user_url.
		if ( isset( $request['user_url'] ) ) {
			$user->set_user_url( $request['user_url'] );
		}

		// User's user_activation_key.
		if ( isset( $request['user_activation_key'] ) ) {
			$user->set_user_activation_key( $request['user_activation_key'] );
		}

		// User's user_status.
		if ( isset( $request['user_status'] ) ) {
			$user->set_user_status( $request['user_status'] );
		}

		// User's display_name.
		if ( isset( $request['display_name'] ) ) {
			$user->set_display_name( $request['display_name'] );
		}

		// User's nickname.
		if ( isset( $request['nickname'] ) ) {
			$user->set_nickname( $request['nickname'] );
		}

		// User's first_name.
		if ( isset( $request['first_name'] ) ) {
			$user->set_first_name( $request['first_name'] );
		}

		// User's last_name.
		if ( isset( $request['last_name'] ) ) {
			$user->set_last_name( $request['last_name'] );
		}

		// User's description.
		if ( isset( $request['description'] ) ) {
			$user->set_description( $request['description'] );
		}

		// User's rich_editing.
		if ( isset( $request['rich_editing'] ) ) {
			$user->set_rich_editing( $request['rich_editing'] );
		}

		// User's syntax_highlighting.
		if ( isset( $request['syntax_highlighting'] ) ) {
			$user->set_syntax_highlighting( $request['syntax_highlighting'] );
		}

		// User's comment_shortcuts.
		if ( isset( $request['comment_shortcuts'] ) ) {
			$user->set_comment_shortcuts( $request['comment_shortcuts'] );
		}

		// User's use_ssl.
		if ( isset( $request['use_ssl'] ) ) {
			$user->set_use_ssl( $request['use_ssl'] );
		}

		// User's show_admin_bar_front.
		if ( isset( $request['show_admin_bar_front'] ) ) {
			$user->set_show_admin_bar_front( $request['show_admin_bar_front'] );
		}

		// User's locale.
		if ( isset( $request['locale'] ) ) {
			$user->set_locale( $request['locale'] );
		}

		// User's role.
		if ( isset( $request['roles'] ) ) {
			$user->set_roles( $request['roles'] );
		}

		// Allow set meta_data.
		if ( isset( $request['meta_data'] ) && is_array( $request['meta_data'] ) ) {
			foreach ( $request['meta_data'] as $meta ) {
				$user->update_meta_data( $meta['key'], $meta['value'], isset( $meta['id'] ) ? $meta['id'] : '' );
			}
		}

		/**
		 * Filters an object before it is inserted via the REST API.
		 *
		 * The dynamic portion of the hook name, `$this->object_type`,
		 * refers to the object type slug.
		 *
		 * @param Model         $user  Object object.
		 * @param WP_REST_Request $request  Request object.
		 * @param bool            $creating If is creating a new object.
		 */
		return apply_filters( "masteriyo_rest_pre_insert_{$this->object_type}_object", $user, $request, $creating );
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
