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

		$params['orderby'] = array(
			'default'     => 'name',
			'description' => __( 'Sort collection by object attribute.', 'masteriyo' ),
			'enum'        => array(
				'id',
				'include',
				'name',
				'date_created',
				'slug',
				'include_slugs',
				'email',
				'url',
			),
			'type'        => 'string',
		);

		$params['roles'] = array(
			'description'       => __( 'Limit result set to users matching at least one specific role provided. Accepts csv list or single role.', 'masteriyo' ),
			'type'              => 'array',
			'items'             => array(
				'type' => 'string',
			),
			'validate_callback' => 'rest_validate_request_arg',
			'enum'              => masteriyo_get_wp_roles(),

		);
		$params['per_page'] = array(
			'description'       => __( 'Number of users per page.', 'masteriyo' ),
			'type'              => 'number',
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
		);

		return apply_filters( 'masteriyo_user_collection_params', $params );
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
			'username'             => $user->get_username(),
			'nicename'             => $user->get_nicename(),
			'email'                => $user->get_email(),
			'url'                  => $user->get_url(),
			'date_created'         => $user->get_date_created(),
			'status'               => $user->get_status(),
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
			'billing'              => array(
				'first_name' => $user->get_billing_first_name(),
				'last_name'  => $user->get_billing_last_name(),
				'company'    => $user->get_billing_company(),
				'address_1'  => $user->get_billing_address_1(),
				'address_2'  => $user->get_billing_address_2(),
				'city'       => $user->get_billing_city(),
				'postcode'   => $user->get_billing_postcode(),
				'country'    => $user->get_billing_country(),
				'state'      => $user->get_billing_state(),
				'email'      => $user->get_billing_email(),
				'phone'      => $user->get_billing_phone(),
			),
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
				'username'             => array(
					'description' => __( 'User login username.', 'masteriyo' ),
					'type'        => 'string',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
				),
				'password'             => array(
					'description' => __( 'User login password.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'nicename'             => array(
					'description' => __( 'User nicename.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'email'                => array(
					'description' => __( 'User email.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'url'                  => array(
					'description' => __( 'Site url of the user.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'date_created'         => array(
					'description' => __( 'User date created', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'activation_key'       => array(
					'description' => __( 'User activation key.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'status'               => array(
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
				'spam'                 => array(
					'description' => __( 'Mark the user as spam. Multi site only.', 'masteriyo' ),
					'type'        => 'boolean',
					'default'     => false,
					'context'     => array( 'view', 'edit' ),
				),
				'use_ssl'              => array(
					'description' => __( 'Use SSL.', 'masteriyo' ),
					'type'        => 'boolean',
					'default'     => false,
					'context'     => array( 'view', 'edit' ),
				),
				'show_admin_bar_front' => array(
					'description' => __( 'Whether to show the admin bar on the frontend.', 'masteriyo' ),
					'type'        => 'boolean',
					'default'     => true,
					'context'     => array( 'view', 'edit' ),
				),
				'locale'               => array(
					'description' => __( 'User specific locale.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'roles'                => array(
					'description' => __( 'User role.', 'masteriyo' ),
					'type'        => 'array',
					'enum'        => masteriyo_get_wp_roles(),
					'context'     => array( 'view', 'edit' ),
				),
				'billing'              => array(
					'description' => __( 'User billing details.', 'masteriyo' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'first_name' => array(
							'description' => __( 'User billing first name.', 'masteriyo' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'last_name'  => array(
							'description' => __( 'User billing last name.', 'masteriyo' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'company'    => array(
							'description' => __( 'User billing company name.', 'masteriyo' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'address_1'  => array(
							'description' => __( 'User billing address 1.', 'masteriyo' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'address_1'  => array(
							'description' => __( 'User billing address 1.', 'masteriyo' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'address_2'  => array(
							'description' => __( 'User billing address 2.', 'masteriyo' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'city'       => array(
							'description' => __( 'User billing city.', 'masteriyo' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'postcode'   => array(
							'description' => __( 'User billing post code.', 'masteriyo' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'country'    => array(
							'description' => __( 'User billing country.', 'masteriyo' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'state'      => array(
							'description' => __( 'User billing state.', 'masteriyo' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'email'      => array(
							'description' => __( 'User billing email address.', 'masteriyo' ),
							'type'        => 'email',
							'context'     => array( 'view', 'edit' ),
						),
						'phone'      => array(
							'description' => __( 'User billing phone number.', 'masteriyo' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
					),
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

	/**`
	 * Prepare a single user object for create or update.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|Model
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		$id   = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
		$user = masteriyo( 'user' );

		if ( 0 !== $id ) {
			$user->set_id( $id );
			$user_repo = masteriyo( 'user.store' );
			$user_repo->read( $user );
		}

		// User's username.
		if ( isset( $request['username'] ) ) {
			$user->set_username( $request['username'] );
		}

		// User's password.
		if ( isset( $request['password'] ) ) {
			$user->set_password( $request['password'] );
		}

		// User's nicename.
		if ( isset( $request['nicename'] ) ) {
			$user->set_nicename( $request['nicename'] );
		}

		// User's email.
		if ( isset( $request['email'] ) ) {
			$user->set_email( $request['email'] );
		}

		// User's url.
		if ( isset( $request['url'] ) ) {
			$user->set_url( $request['url'] );
		}

		// User's activation_key.
		if ( isset( $request['activation_key'] ) ) {
			$user->set_activation_key( $request['activation_key'] );
		}

		// User's status.
		if ( isset( $request['status'] ) ) {
			$user->set_status( $request['status'] );
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

		// User billing details.
		if ( isset( $request['billing']['first_name'] ) ) {
			$user->set_billing_first_name( $request['billing']['first_name'] );
		}

		if ( isset( $request['billing']['last_name'] ) ) {
			$user->set_billing_last_name( $request['billing']['last_name'] );
		}

		if ( isset( $request['billing']['company'] ) ) {
			$user->set_billing_company( $request['billing']['company'] );
		}

		if ( isset( $request['billing']['address_1'] ) ) {
			$user->set_billing_address_1( $request['billing']['address_1'] );
		}

		if ( isset( $request['billing']['address_2'] ) ) {
			$user->set_billing_address_2( $request['billing']['address_2'] );
		}

		if ( isset( $request['billing']['city'] ) ) {
			$user->set_billing_city( $request['billing']['city'] );
		}

		if ( isset( $request['billing']['postcode'] ) ) {
			$user->set_billing_postcode( $request['billing']['postcode'] );
		}

		if ( isset( $request['billing']['country'] ) ) {
			$user->set_billing_country( $request['billing']['country'] );
		}

		if ( isset( $request['billing']['state'] ) ) {
			$user->set_billing_state( $request['billing']['state'] );
		}

		if ( isset( $request['billing']['email'] ) ) {
			$user->set_billing_email( $request['billing']['email'] );
		}

		if ( isset( $request['billing']['phone'] ) ) {
			$user->set_billing_phone( $request['billing']['phone'] );
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

		if ( ! $user || ! $this->permission->rest_check_users_manipulation_permissions( 'edit' ) ) {
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
