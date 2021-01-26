<?php
/**
 * Setting rest controller.
 */

namespace ThemeGrill\Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use ThemeGrill\Masteriyo\Helper\Utils;
use ThemeGrill\Masteriyo\Helper\Permission;
use ThemeGrill\Masteriyo\Models\Setting;
class SettingsController extends CrudController {

	/**
	 * Endpoint namespace.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $namespace = 'masteriyo/v1';

	/**
	 * Route base.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $rest_base = 'settings';

	/**
	 * Object type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $object_type = 'setting';

	/**
	 * If object is hierarchical.
	 *
	 * @since 0.1.0
	 *
	 * @var bool
	 */
	protected $hierarchical = true;

	/**
	 * Permission class.
	 *
	 * @since 0.1.0
	 *
	 * @var ThemeGrill\Masteriyo\Helper\Permission
	 */
	protected $permission = null;

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param Permission $permission
	 */
	public function __construct( Permission $permission ) {
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

		return current_user_can( 'manage_options' ) || current_user_can( 'masteriyo_settings' );
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

		return current_user_can( 'manage_options' ) || current_user_can( 'masteriyo_settings' );
	}

	/**
	 * Get the query params for collections of attachments.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params                       = array();
		$params['context']            = $this->get_context_param();
		$params['context']['default'] = 'view';

		$params['page']     = array(
			'description'       => __( 'Current page of the collection.', 'masteriyo' ),
			'type'              => 'integer',
			'default'           => 1,
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
			'minimum'           => 1,
		);
		$params['per_page'] = array(
			'description'       => __( 'Maximum number of items to be returned in result set.', 'masteriyo' ),
			'type'              => 'integer',
			'default'           => 10,
			'minimum'           => 1,
			'maximum'           => 100,
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['search']   = array(
			'description'       => __( 'Limit results to those matching a string.', 'masteriyo' ),
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['exclude'] = array(
			'description'       => __( 'Ensure result set excludes specific IDs.', 'masteriyo' ),
			'type'              => 'array',
			'items'             => array(
				'type' => 'integer',
			),
			'default'           => array(),
			'sanitize_callback' => 'wp_parse_id_list',
		);
		$params['include'] = array(
			'description'       => __( 'Limit result set to specific ids.', 'masteriyo' ),
			'type'              => 'array',
			'items'             => array(
				'type' => 'integer',
			),
			'default'           => array(),
			'sanitize_callback' => 'wp_parse_id_list',
		);

		/**
		 * Filter collection parameters for the posts controller.
		 *
		 * The dynamic part of the filter `$this->object_type` refers to the post
		 * type slug for the controller.
		 *
		 * This filter registers the collection parameter, but does not map the
		 * collection parameter to an internal WP_Query parameter. Use the
		 * `rest_{$this->object_type}_query` filter to set WP_Query parameters.
		 *
		 * @since 0.1.0
		 *
		 * @param array        $query_params JSON Schema-formatted collection parameters.
		 * @param WP_object_type $object_type    Post type object.
		 */
		return apply_filters( "rest_{$this->object_type}_collection_params", $params, $this->object_type );
	}

	/**
	 * Get the settings' schema, conforming to JSON Schema.
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
				'id'                => array(
					'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'group'         => array(
					'description' => __( 'The group option belongs to.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'name'            => array(
					'description' => __( 'Option name', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'value'            => array(
					'description' => __( 'Option value', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				)
			),
		);

		return $schema;
	}

	/**
	 * Prepare objects query.
	 *
	 * @since  0.1.0
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$args = array(
			'offset'   => $request['offset'],
			'paged'    => $request['page'],
			'per_page' => $request['per_page'],
			's'        => $request['search'],
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
	 * Get objects.
	 *
	 * @since  0.1.0
	 * @param  array $query_args Query args.
	 * @return array
	 */
	protected function get_objects( $query_args ) {
		global $wpdb;

		$options = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}options WHERE option_name LIKE %s ORDER BY option_id LIMIT %d, %d",
				esc_sql( 'masteriyo.' . $query_args['s'] . '%' ),
				( $query_args['paged'] - 1 ) * $query_args['per_page'],
				$query_args['per_page']
			),
			ARRAY_A
		);

		$total_options = count( $options );
		if ( $total_options < 1 ) {
			$options = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM {$wpdb->prefix}options WHERE option_name LIKE %s ORDER BY option_id",
					esc_sql( 'masteriyo_%' )
				),
				ARRAY_A
			);
		}

		return array(
			'objects' => array_filter( array_map( array( $this, 'get_object' ), $options ) ),
			'total'   => $total_options,
			'pages'   => ceil( $total_options / $query_args['per_page'] ),
		);
	}

	/**
	 * Check permissions for an item.
	 *
	 * @since 0.1.0
	 * @param string $object_type Object type.
	 * @param string $context   Request context.
	 * @param int    $object_id Post ID.
	 * @return bool
	 */
	protected function check_item_permission( $object_type, $context = 'read', $object_id = 0 ) {
		return current_user_can( 'manage_options' ) || current_user_can( 'masteriyo_settings' );
	}

	/**
	 * Get object.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $option Option.
	 * @return Setting
	 */
	public function get_object( $option) {
		global $masteriyo_container;

		$setting = $masteriyo_container->get( 'setting' );

		if ( is_a( $option, '\ThemeGrill\Masteriyo\Models\Setting' ) ) {
			$setting->set_id( $option->get_id() );
			$setting->set_group( $option->get_group( true ) );
			$setting->set_name( $option->get_name() );
			$setting_repo = $masteriyo_container->get( \ThemeGrill\Masteriyo\Repository\SettingRepository::class );
			$setting_repo->read( $setting );
		} else {
			$option_arr  = explode( '.', $option['option_name'] );
			$group       = count( $option_arr ) > 2 ? $option_arr[1] : '';
			$option_name = end( $option_arr );

			$setting->set_id( $option['option_id'] );
			$setting->set_group( $group );
			$setting->set_name( $option_name );
			$setting->set_value( $option['option_value'] );
		}

		return $setting;
	}

	/**
	 * Prepares the object for the REST response.
	 *
	 * @since  0.1.0
	 * @param  Model         $object  Model object.
	 * @param  WP_REST_Request $request Request object.
	 * @return WP_Error|WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	protected function prepare_object_for_response( $object, $request ) {
		$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data    = $this->get_setting_data( $object, $context );

		$data     = $this->filter_response_by_context( $data, $context );
		$response = rest_ensure_response( $data );

		/**
		 * Filter the data for a response.
		 *
		 * The dynamic portion of the hook name, $this->object_type,
		 * refers to object type being prepared for the response.
		 *
		 * @since 0.1.0
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param Model            $object   Object data.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( "masteriyo_rest_prepare_{$this->object_type}_object", $response, $object, $request );
	}

	/**
	 * Get settings data.
	 *
	 * @since 0.1.0
	 *
	 * @param array  $setting Setting instance.
	 * @param string $context Request context. Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_setting_data( $setting, $context = 'view' ) {
		return array(
			'id'       => $setting->get_id( $context ),
			'group'    => $setting->get_group(),
			'name'     => $setting->get_name( $context ),
			'fullname' => $setting->get_full_name( $context ),
			'value'    => $setting->get_value( $context ),
		);
	}

	/**
	 * Prepare a single settings for create or update.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|Model
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		global $masteriyo_container;

		$setting = $masteriyo_container->get( 'setting' );

		if ( isset( $request['group'] ) ) {
			$setting->set_group( $request['group'] );
		}

		if ( isset( $request['name'] ) ) {
			$setting->set_name( $request['name'] );
		}

		if ( isset( $request['value'] ) ) {
			$setting->set_value( $request['value'] );
		}

		/**
		 * Filters an object before it is inserted via the REST API.
		 *
		 * The dynamic portion of the hook name, `$this->object_type`,
		 * refers to the object type slug.
		 *
		 * @param Model         $setting  Object object.
		 * @param WP_REST_Request $request  Request object.
		 * @param bool            $creating If is creating a new object.
		 */
		return apply_filters( "masteriyo_rest_pre_insert_{$this->object_type}_object", $setting, $request, $creating );
	}
}
