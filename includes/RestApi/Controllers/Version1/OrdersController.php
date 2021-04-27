<?php
/**
 * Abstract class controller.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\RestApi\Controllers\Version1;
 */

namespace ThemeGrill\Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use ThemeGrill\Masteriyo\Helper\Permission;
use ThemeGrill\Masteriyo\Models\Order;

/**
 * OrdersController class.
 */
class OrdersController extends PostsController {

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
	protected $rest_base = 'orders';

	/**
	 * Object type.
	 *
	 * @var string
	 */
	protected $object_type = 'masteriyo_order';

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $post_type = 'masteriyo_order';

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

		$params['status']      = array(
			'description'       => __( 'Limit result set to orders assigned a specific status.', 'masteriyo' ),
			'type'              => 'string',
			'enum'              => array_merge( array_keys( masteriyo_get_order_statuses() ), array( 'any' ) ),
			'default'           => 'any',
			'sanitize_callback' => 'sanitize_key',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['customer_id'] = array(
			'description'       => __( 'Filter orders by customer ID.', 'masteriyo' ),
			'type'              => 'number',
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
		);

		return $params;
	}

	/**
	 * Get object.
	 *
	 * @param  int|WP_Post $id Object ID.
	 * @return object Model object or WP_Error object.
	 */
	protected function get_object( $id ) {
		try {
			$id    = $id instanceof \WP_Post ? $id->ID : $id;
			$id    = $id instanceof Order ? $id->get_id() : $id;
			$order = masteriyo( 'order' );
			$order->set_id( $id );
			$order_repo = masteriyo( 'order.store' );
			$order_repo->read( $order );
		} catch ( \Exception $e ) {
			return false;
		}

		return $order;
	}

	/**
	 * Prepares the object for the REST response.
	 *
	 * @since  3.0.0
	 * @param  Model           $object  Model object.
	 * @param  WP_REST_Request $request Request object.
	 * @return WP_Error|WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	protected function prepare_object_for_response( $object, $request ) {
		$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data    = $this->get_order_data( $object, $context );

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
	 * Get order data.
	 *
	 * @param Order  $order Order instance.
	 * @param string $context Request context.
	 *                        Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_order_data( $order, $context = 'view' ) {
		$data = array(
			'id'                  => $order->get_id(),
			'permalink'           => $order->get_permalink(),
			'status'              => $order->get_status( $context ),
			'total'               => $order->get_total( $context ),
			'currency'            => $order->get_currency( $context ),
			'expiry_date'         => $order->get_expiry_date( $context ),
			'date_created'        => $order->get_date_created( $context ),
			'date_modified'       => $order->get_date_modified( $context ),
			'customer_id'         => $order->get_customer_id( $context ),
			'payment_method'      => $order->get_payment_method( $context ),
			'transaction_id'      => $order->get_transaction_id( $context ),
			'date_paid'           => $order->get_date_paid( $context ),
			'date_completed'      => $order->get_date_completed( $context ),
			'created_via'         => $order->get_created_via( $context ),
			'customer_ip_address' => $order->get_customer_ip_address( $context ),
			'customer_user_agent' => $order->get_customer_user_agent( $context ),
			'version'             => $order->get_version( $context ),
		);

		return $data;
	}

	/**
	 * Prepare objects query.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @since  3.0.0
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$args = parent::prepare_objects_query( $request );

		// Set order status.
		$args['post_status'] = $request['status'];

		if ( ! empty( $request['customer_id'] ) ) {
			$args['author'] = $request['customer_id'];
		} else {
			$args['author'] = get_current_user_id();
		}

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
				'id'                  => array(
					'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'permalink'           => array(
					'description' => __( 'Order URL.', 'masteriyo' ),
					'type'        => 'string',
					'format'      => 'uri',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_created'        => array(
					'description' => __( "The date the Order was created, in the site's timezone.", 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'date_created_gmt'    => array(
					'description' => __( 'The date the Order was created, as GMT.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'date_modified'       => array(
					'description' => __( "The date the Order was last modified, in the site's timezone.", 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_modified_gmt'   => array(
					'description' => __( 'The date the Order was last modified, as GMT.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'status'              => array(
					'description' => __( 'Order status.', 'masteriyo' ),
					'type'        => 'string',
					'default'     => 'pending',
					'enum'        => array_keys( masteriyo_get_order_statuses() ),
					'context'     => array( 'view', 'edit' ),
				),
				'total'               => array(
					'description' => __( 'Total amount of the order.', 'masteriyo' ),
					'type'        => 'number',
					'context'     => array( 'view', 'edit' ),
				),
				'currency'            => array(
					'description' => __( 'Currency.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'expiry_date'         => array(
					'description' => __( 'Expiry date of this order.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'customer_id'         => array(
					'description' => __( 'Customer ID.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'payment_method'      => array(
					'description' => __( 'Payment method.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'default'     => 'paypal',
					'enum'        => array( 'paypal' ),
				),
				'transaction_id'      => array(
					'description' => __( 'Transaction ID.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'date_paid'           => array(
					'description' => __( 'Date of payment.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'date_completed'      => array(
					'description' => __( 'Date of order completion.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'created_via'         => array(
					'description' => __( 'Method of order creation.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'customer_ip_address' => array(
					'description' => __( 'Customer IP address.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'customer_user_agent' => array(
					'description' => __( 'Customer user agent.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'version'             => array(
					'description' => __( 'Version of Masteriyo which last updated the order.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'meta_data'           => array(
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
	 * Prepare a single order for create or update.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|Model
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		$id    = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
		$order = masteriyo( 'order' );

		if ( 0 !== $id ) {
			$order->set_id( $id );
			$order_repo = masteriyo( 'order.store' );
			$order_repo->read( $order );
		}

		// Order status.
		if ( isset( $request['status'] ) ) {
			$order->set_status( $request['status'] );
		}

		// Total Price.
		if ( isset( $request['total'] ) ) {
			$order->set_total( $request['total'] );
		}

		// Currency.
		if ( isset( $request['currency'] ) ) {
			$order->set_currency( $request['currency'] );
		}

		// Order's expiry date.
		if ( isset( $request['expiry_date'] ) ) {
			$order->set_expiry_date( $request['expiry_date'] );
		}

		// Customer ID.
		if ( isset( $request['customer_id'] ) ) {
			$order->set_customer_id( $request['customer_id'] );
		} else {
			$order->set_customer_id( get_current_user_id() );
		}

		// Set payment method.
		if ( isset( $request['payment_method'] ) ) {
			$order->set_payment_method( $request['payment_method'] );
		}

		// Set transaction ID.
		if ( isset( $request['transaction_id'] ) ) {
			$order->set_transaction_id( $request['transaction_id'] );
		}

		// Set date of payment.
		if ( isset( $request['date_paid'] ) ) {
			$order->set_date_paid( $request['date_paid'] );
		}

		// Set date of order completion.
		if ( isset( $request['date_completed'] ) ) {
			$order->set_date_completed( $request['date_completed'] );
		}

		// Set method of order creation.
		if ( isset( $request['created_via'] ) ) {
			$order->set_created_via( $request['created_via'] );
		}

		// Set customer IP address.
		if ( isset( $request['customer_ip_address'] ) ) {
			$order->set_customer_ip_address( $request['customer_ip_address'] );
		}

		// Set customer user agent.
		if ( isset( $request['customer_user_agent'] ) ) {
			$order->set_customer_user_agent( $request['customer_user_agent'] );
		}

		// Allow set meta_data.
		if ( isset( $request['meta_data'] ) && is_array( $request['meta_data'] ) ) {
			foreach ( $request['meta_data'] as $meta ) {
				$order->update_meta_data( $meta['key'], $meta['value'], isset( $meta['id'] ) ? $meta['id'] : '' );
			}
		}

		/**
		 * Filters an object before it is inserted via the REST API.
		 *
		 * The dynamic portion of the hook name, `$this->object_type`,
		 * refers to the object type slug.
		 *
		 * @param Model         $order  Object object.
		 * @param WP_REST_Request $request  Request object.
		 * @param bool            $creating If is creating a new object.
		 */
		return apply_filters( "masteriyo_rest_pre_insert_{$this->object_type}_object", $order, $request, $creating );
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

		if ( ! $this->permission->rest_check_order_permissions( absint( $request['id'] ), 'read' ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_read',
				__( 'Sorry, you are not allowed to read resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code()
				)
			);
		}

		return true;
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

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
		}

		$customer_id = 0;

		if ( isset( $request['customer_id'] ) ) {
			$customer_id = absint( $request['customer_id'] );
		}

		if ( ! $customer_id || get_current_user_id() !== $customer_id ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_read',
				__( 'Sorry, you cannot list resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code()
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

		if ( ! is_user_logged_in() ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_create',
				__( 'Sorry, you are not allowed to create resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code()
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

		if ( ! $this->permission->rest_check_order_permissions( absint( $request['id'] ), 'delete' ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_delete',
				__( 'Sorry, you are not allowed to delete resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code()
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

		/**
		 * Prevent from updating the order owner to someone else.
		 */
		if ( isset( $request['customer_id'] ) && absint( $request['customer_id'] ) !== get_current_user_id() ) {
			return false;
		}

		if ( ! $this->permission->rest_check_order_permissions( absint( $request['id'] ), 'update' ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_update',
				__( 'Sorry, you are not allowed to update resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code()
				)
			);
		}

		return true;
	}
}
