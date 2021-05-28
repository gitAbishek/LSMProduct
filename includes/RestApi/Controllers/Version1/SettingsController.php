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
				array(
					'methods'             => \WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_items' ),
					'permission_callback' => array( $this, 'delete_items_permissions_check' ),
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

		return current_user_can( 'manage_options' ) || current_user_can( 'manage_masteriyo_settings' );
	}

	/**
	 * Check if a given request has access to update an item.
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

		return current_user_can( 'manage_options' ) || current_user_can( 'manage_masteriyo_settings' );
	}

	/**
	 * Check if a given request has access to delete an item.
	 *
	 * @since 0.1.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function delete_items_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		return current_user_can( 'manage_options' ) || current_user_can( 'manage_masteriyo_settings' );
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
				'general'  => array(
					'description' => __( 'General Settings.', 'masteriyo' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'               => 'object',
						'address_line1'      => array(
							'description' => __( 'Address Line 1.', 'masteriyo' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'address_line2'      => array(
							'description' => __( 'Address Line 2.', 'masteriyo' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'city'               => array(
							'description' => __( 'City Name.', 'masteriyo' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'country'            => array(
							'description' => __( 'Country Name.', 'masteriyo' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'postcode'           => array(
							'description' => __( 'Postal Code.', 'masteriyo' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'currency'           => array(
							'description' => __( 'Currency Code.', 'masteriyo' ),
							'type'        => 'string',
							'default'     => 'USD',
							'enum'        => masteriyo_get_currency_codes(),
							'context'     => array( 'view', 'edit' ),
						),
						'currency_position'  => array(
							'description' => __( 'Position of Currency.', 'masteriyo' ),
							'type'        => 'string',
							'default'     => 'left',
							'enum'        => array( 'left', 'right', 'left_space', 'right_space' ),
							'context'     => array( 'view', 'edit' ),
						),
						'thousand_separator' => array(
							'description' => __( 'Thousand Separator.', 'masteriyo' ),
							'type'        => 'string',
							'default'     => ',',
							'context'     => array( 'view', 'edit' ),
						),
						'decimal_separator'  => array(
							'description' => __( 'Decimal Separator.', 'masteriyo' ),
							'type'        => 'string',
							'default'     => '.',
							'context'     => array( 'view', 'edit' ),
						),
						'number_of_decimals' => array(
							'description' => __( 'Number of Decimals.', 'masteriyo' ),
							'type'        => 'integer',
							'default'     => 3,
							'context'     => array( 'view', 'edit' ),
						),
					),
				),
				'courses'  => array(
					'description' => __( 'Courses Settings.', 'masteriyo' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'                           => 'object',
						'placeholder_image'              => array(
							'description' => __( 'Placeholder image for courses.', 'masteriyo' ),
							'type'        => 'integer',
							'context'     => array( 'view', 'edit' ),
						),
						'add_to_cart_behaviour'          => array(
							'description' => __( 'Add to cart beahviour.', 'masteriyo' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'per_page'                       => array(
							'description' => __( 'Courses per page.', 'masteriyo' ),
							'type'        => 'integer',
							'context'     => array( 'view', 'edit' ),
						),
						'enable_editing'                 => array(
							'description' => __( 'Enable editing published course.', 'masteriyo' ),
							'type'        => 'boolean',
							'context'     => array( 'view', 'edit' ),
						),
						'category_base'                  => array(
							'description' => __( 'Course category base.', 'masteriyo' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'tag_base'                       => array(
							'description' => __( 'Course tag base', 'masteriyo' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'difficulty_base'                => array(
							'description' => __( 'Course difficulty base', 'masteriyo' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'single_course_permalink'        => array(
							'description' => __( 'Single course permalink.', 'masteriyo' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'single_lesson_permalink'        => array(
							'description' => __( 'Course lessons permalink', 'masteriyo' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'single_quiz_permalink'          => array(
							'description' => __( 'Course quizzes permalink.', 'masteriyo' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'single_section_permalink'       => array(
							'description' => __( 'Course sections permalink.', 'masteriyo' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'enable_single_course_permalink' => array(
							'description' => __( 'Enable single course permalink.', 'masteriyo' ),
							'type'        => 'boolean',
							'context'     => array( 'view', 'edit' ),
						),
						'single_course_enable_editing'   => array(
							'description' => __( 'Enable editing published single course.', 'masteriyo' ),
							'type'        => 'boolean',
							'context'     => array( 'view', 'edit' ),
						),
						'show_thumbnail'                 => array(
							'description' => __( 'Show course thumbnail.', 'masteriyo' ),
							'type'        => 'boolean',
							'context'     => array( 'view', 'edit' ),
						),
						'thumbnail_size'                 => array(
							'description' => __( 'Course thumbnail size', 'masteriyo' ),
							'type'        => 'string',
							'enum'        => array(
								'thumbnail',
								'medium',
								'medium_large',
								'large',
								'custom-size',
							),
							'context'     => array( 'view', 'edit' ),
						),
					),
				),
				'pages'    => array(
					'description' => __( 'Pages Setting', 'masteriyo' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'                     => 'object',
						'myaccount_page_id'        => array(
							'description' => __( 'My Account page ID.', 'masteriyo' ),
							'type'        => 'integer',
							'context'     => array( 'view', 'edit' ),
						),
						'course_list_page_id'      => array(
							'description' => __( 'Archive course page ID.', 'masteriyo' ),
							'type'        => 'integer',
							'context'     => array( 'view', 'edit' ),
						),
						'terms_conditions_page_id' => array(
							'description' => __( 'Terms and conditions page ID.', 'masteriyo' ),
							'type'        => 'integer',
							'context'     => array( 'view', 'edit' ),
						),
						'checkout_page_id'         => array(
							'description' => __( 'Checkout page ID.', 'masteriyo' ),
							'type'        => 'integer',
							'context'     => array( 'view', 'edit' ),
						),
						'checkout_endpoints'       => array(
							'pay'                        => array(
								'description' => __( 'Pay endpoint.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'order_received'             => array(
								'description' => __( 'Order received endpoint.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'add_payment_method'         => array(
								'description' => __( 'Add payment method endpoint.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'delete_payment_method'      => array(
								'description' => __( 'Delete payment method endpoint.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'set_default_payment_method' => array(
								'description' => __( 'Set default payment menthod endpoint.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
						),
						'account_endpoints'        => array(
							'orders'          => array(
								'description' => __( 'Orders endpoint.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'view_order'      => array(
								'description' => __( 'View order endpoint.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'my_courses'      => array(
								'description' => __( 'My courses endpoint.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'edit_account'    => array(
								'description' => __( 'Edit account endpoint.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'payment_methods' => array(
								'description' => __( 'Payment methods endpoint.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'lost_password'   => array(
								'description' => __( 'Lost password endpoint.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'logout'          => array(
								'description' => __( 'Logout endpoint.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
						),
					),
				),
				'payments' => array(
					'description' => __( 'Payments Settings.', 'masteriyo' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'   => 'object',
						'paypal' => array(
							'enable'           => array(
								'description' => __( 'Enable standard paypal.', 'masteriyo' ),
								'type'        => 'boolean',
								'context'     => array( 'view', 'edit' ),
							),
							'production_email' => array(
								'description' => __( 'Paypal production email address.', 'masteriyo' ),
								'type'        => 'email',
								'context'     => array( 'view', 'edit' ),
							),
							'sandbox_enable'   => array(
								'description' => __( 'Enable sandbox mode on paypal.', 'masteriyo' ),
								'type'        => 'boolean',
								'context'     => array( 'view', 'edit' ),
							),
							'sandbox_email'    => array(
								'description' => __( 'Paypal sandbox email address.', 'masteriyo' ),
								'type'        => 'email',
								'context'     => array( 'view', 'edit' ),
							),
						),
					),
				),
				'emails'   => array(
					'description' => __( 'Email Setting', 'masteriyo' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'general'              => array(
							'from_name'       => array(
								'description' => __( 'Email send from.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'from_email'      => array(
								'description' => __( 'Email address to send email.', 'masteriyo' ),
								'type'        => 'email',
								'context'     => array( 'view', 'edit' ),
							),
							'default_content' => array(
								'description' => __( 'Default content for email.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'header_image'    => array(
								'description' => __( 'Email header image.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'footer_text'     => array(
								'description' => __( 'Email footer text.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
						),
						'new_order'            => array(
							'enable'     => array(
								'description' => __( 'Enable new order.', 'masteriyo' ),
								'type'        => 'boolean',
								'context'     => array( 'view', 'edit' ),
							),
							'recipients' => array(
								'description' => __( 'Recipients email address.', 'masteriyo' ),
								'type'        => 'array',
								'context'     => array( 'view', 'edit' ),
								'items'       => array(
									'type' => 'email',
								),
							),
							'subject'    => array(
								'description' => __( 'New order email subject.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'heading'    => array(
								'description' => __( 'New order email heading.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'content'    => array(
								'description' => __( 'New order email content.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
						),
						'processing_order'     => array(
							'enable'  => array(
								'description' => __( 'Enable processing order.', 'masteriyo' ),
								'type'        => 'boolean',
								'context'     => array( 'view', 'edit' ),
							),
							'subject' => array(
								'description' => __( 'Processing order email subject.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'heading' => array(
								'description' => __( 'Processing order email heading.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'content' => array(
								'description' => __( 'Processing order email content.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
						),
						'completed_order'      => array(
							'enable'  => array(
								'description' => __( 'Enable completed order.', 'masteriyo' ),
								'type'        => 'boolean',
								'context'     => array( 'view', 'edit' ),
							),
							'subject' => array(
								'description' => __( 'Completed order email subject.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'heading' => array(
								'description' => __( 'Completed order email heading.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'content' => array(
								'description' => __( 'Completed order email content.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
						),
						'onhold_order'         => array(
							'enable'  => array(
								'description' => __( 'Enable on hold order.', 'masteriyo' ),
								'type'        => 'boolean',
								'context'     => array( 'view', 'edit' ),
							),
							'subject' => array(
								'description' => __( 'On hold order email subject.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'heading' => array(
								'description' => __( 'On hold order email heading.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'content' => array(
								'description' => __( 'On hold order email content.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
						),
						'cancelled_order'      => array(
							'enable'     => array(
								'description' => __( 'Enable cancelled order.', 'masteriyo' ),
								'type'        => 'boolean',
								'context'     => array( 'view', 'edit' ),
							),
							'recipients' => array(
								'description' => __( 'Recipients email address.', 'masteriyo' ),
								'type'        => 'email',
								'context'     => array( 'view', 'edit' ),
								'items'       => array(
									'type' => 'email',
								),
							),
							'subject'    => array(
								'description' => __( 'Cancelled order email subject.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'heading'    => array(
								'description' => __( 'Cancelled order email heading.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'content'    => array(
								'description' => __( 'Cancelled order email content.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
						),
						'enrolled_course'      => array(
							'enable'  => array(
								'description' => __( 'Enable enrolled course.', 'masteriyo' ),
								'type'        => 'boolean',
								'context'     => array( 'view', 'edit' ),
							),
							'subject' => array(
								'description' => __( 'Enrolled course email subject.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'heading' => array(
								'description' => __( 'Enrolled course email heading.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'content' => array(
								'description' => __( 'Enrolled course email content.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
						),
						'completed_course'     => array(
							'enable'  => array(
								'description' => __( 'Enable completed course.', 'masteriyo' ),
								'type'        => 'boolean',
								'context'     => array( 'view', 'edit' ),
							),
							'subject' => array(
								'description' => __( 'Completed course email subject.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'heading' => array(
								'description' => __( 'Completed course email heading.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'content' => array(
								'description' => __( 'Completed course email content.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
						),
						'become_an_instructor' => array(
							'enable'  => array(
								'description' => __( 'Enable become an instructor.', 'masteriyo' ),
								'type'        => 'boolean',
								'context'     => array( 'view', 'edit' ),
							),
							'subject' => array(
								'description' => __( 'Become an instructor email subject.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'heading' => array(
								'description' => __( 'Become an instructor email heading.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'content' => array(
								'description' => __( 'Become an instructor email content.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
						),
					),
				),
				'advanced' => array(
					'description' => __( 'Advanced Setting', 'masteriyo' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'template_debug_enable' => array(
							'description' => __( 'Enable template debug.', 'masteriyo' ),
							'type'        => 'boolean',
							'context'     => array( 'view', 'edit' ),
						),
						'debug_enable'          => array(
							'description' => __( 'Enable debug mode.', 'masteriyo' ),
							'type'        => 'boolean',
							'context'     => array( 'view', 'edit' ),
						),
						'styles_mode'           => array(
							'description' => __( 'Choose styles mode.', 'masteriyo' ),
							'type'        => 'string',
							'enum'        => array( 'none', 'simple', 'advance' ),
							'context'     => array( 'view', 'edit' ),
						),
					),
				),
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
		$setting = masteriyo( 'setting' );

		$options = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}options WHERE option_name LIKE %s",
				esc_sql( 'masteriyo.%' )
			),
			ARRAY_A
		);

		$total_options = count( $options );

		$setting_data = array();
		foreach ( $options as $option ) {
			$option_arr  = explode( '.', $option['option_name'] );
			$group       = count( $option_arr ) > 2 ? $option_arr[1] : '';
			$option_name = end( $option_arr );

			$setter_callback = "set_{$group}_{$option_name}"; // E.g "set_general_currency", "set_general_city" etc.

			if ( is_callable( array( $setting, $setter_callback ) ) ) {
				$setting->{$setter_callback}( $option['option_value'] );
			}
		}
		$setting_data[] = $setting; // Returning setting data as per required structure.

		return array(
			'objects' => $setting_data,
			'total'   => (int) $total_options,
			'pages'   => (int) ceil( $total_options / (int) 1 ),
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
		return current_user_can( 'manage_options' ) || current_user_can( 'manage_masteriyo_settings' );
	}

	/**
	 * Get object.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $option Option.
	 * @return Setting
	 */
	public function get_object( $option ) {
		try {
			$setting      = masteriyo( 'setting' );
			$setting_repo = masteriyo( 'setting.store' );
			$setting_repo->read( $setting );
		} catch ( \Exception $e ) {
			return false;
		}

		return $setting;
	}

	/**
	 * Reset the default value to settings.
	 *
	 * @since 0.1.0
	 * @return Setting
	 */
	public function delete_items( $request ) {
			$setting = masteriyo( 'setting' );
			$setting->delete( $setting );
			$request->set_param( 'context', 'edit' );
			$response = $this->prepare_object_for_response( $setting, $request );

			return $response;
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
	 * @param object $setting Setting instance.
	 * @param string $context Request context. Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_setting_data( $setting, $context = 'view' ) {
		return array(
			'general'  => array(
				'address_line1'      => $setting->get_general_address_line1( $context ),
				'address_line2'      => $setting->get_general_address_line2( $context ),
				'city'               => $setting->get_general_city( $context ),
				'country'            => $setting->get_general_country( $context ),
				'postcode'           => $setting->get_general_postcode( $context ),
				'currency'           => $setting->get_general_currency( $context ),
				'currency_position'  => $setting->get_general_currency_position( $context ),
				'thousand_separator' => $setting->get_general_thousand_separator( $context ),
				'decimal_separator'  => $setting->get_general_decimal_separator( $context ),
				'number_of_decimals' => $setting->get_general_number_of_decimals( $context ),
			),
			'courses'  => array(
				'placeholder_image'              => $setting->get_courses_placeholder_image( $context ),
				'add_to_cart_behaviour'          => $setting->get_courses_add_to_cart_behaviour( $context ),
				'per_page'                       => $setting->get_courses_per_page( $context ),
				'enable_editing'                 => $setting->get_courses_enable_editing( $context ),
				'category_base'                  => $setting->get_courses_category_base( $context ),
				'tag_base'                       => $setting->get_courses_tag_base( $context ),
				'difficulty_base'                => $setting->get_courses_difficulty_base( $context ),
				'single_course_permalink'        => $setting->get_courses_single_course_permalink( $context ),
				'single_lesson_permalink'        => $setting->get_courses_single_lesson_permalink( $context ),
				'single_quiz_permalink'          => $setting->get_courses_single_quiz_permalink( $context ),
				'single_section_permalink'       => $setting->get_courses_single_section_permalink( $context ),
				'enable_single_course_permalink' => $setting->get_courses_enable_single_course_permalink( $context ),
				'single_course_enable_editing'   => $setting->get_courses_single_course_enable_editing( $context ),
				'show_thumbnail'                 => $setting->get_courses_show_thumbnail( $context ),
				'thumbnail_size'                 => $setting->get_courses_thumbnail_size( $context ),
			),
			'pages'    => array(
				'myaccount_page_id'        => $setting->get_pages_myaccount_page_id( $context ),
				'course_list_page_id'      => $setting->get_pages_course_list_page_id( $context ),
				'terms_conditions_page_id' => $setting->get_pages_terms_conditions_page_id( $context ),
				'checkout_page_id'         => $setting->get_pages_checkout_page_id( $context ),
				'checkout_endpoints'       => array(
					'pay'                        => $setting->get_pages_pay( $context ),
					'order_received'             => $setting->get_pages_order_received( $context ),
					'add_payment_method'         => $setting->get_pages_add_payment_method( $context ),
					'delete_payment_method'      => $setting->get_pages_delete_payment_method( $context ),
					'set_default_payment_method' => $setting->get_pages_set_default_payment_method( $context ),
				),
				'account_endpoints'        => array(
					'orders'          => $setting->get_pages_orders( $context ),
					'view_order'      => $setting->get_pages_view_order( $context ),
					'my_courses'      => $setting->get_pages_my_courses( $context ),
					'edit_account'    => $setting->get_pages_edit_account( $context ),
					'payment_methods' => $setting->get_pages_payment_methods( $context ),
					'lost_password'   => $setting->get_pages_lost_password( $context ),
					'logout'          => $setting->get_pages_logout( $context ),
				),
			),
			'payments' => array(
				'paypal' => array(
					'enable'           => $setting->get_payments_paypal_enable( $context ),
					'production_email' => $setting->get_payments_paypal_production_email( $context ),
					'sandbox_enable'   => $setting->get_payments_paypal_sandbox_enable( $context ),
					'sandbox_email'    => $setting->get_payments_paypal_sandbox_email( $context ),
				),
			),
			'emails'   => array(
				'general'              => array(
					'from_name'       => $setting->get_emails_general_from_name( $context ),
					'from_email'      => $setting->get_emails_general_from_email( $context ),
					'default_content' => $setting->get_emails_general_default_content( $context ),
					'header_image'    => $setting->get_emails_general_header_image( $context ),
					'footer_text'     => $setting->get_emails_general_footer_text( $context ),
				),
				'new_order'            => array(
					'enable'     => $setting->get_emails_new_order_enable( $context ),
					'recipients' => $setting->get_emails_new_order_recipients( $context ),
					'subject'    => $setting->get_emails_new_order_subject( $context ),
					'heading'    => $setting->get_emails_new_order_heading( $context ),
					'content'    => $setting->get_emails_new_order_content( $context ),
				),
				'processing_order'     => array(
					'enable'  => $setting->get_emails_processing_order_enable( $context ),
					'subject' => $setting->get_emails_processing_order_subject( $context ),
					'heading' => $setting->get_emails_processing_order_heading( $context ),
					'content' => $setting->get_emails_processing_order_content( $context ),
				),
				'completed_order'      => array(
					'enable'  => $setting->get_emails_completed_order_enable( $context ),
					'subject' => $setting->get_emails_completed_order_subject( $context ),
					'heading' => $setting->get_emails_completed_order_heading( $context ),
					'content' => $setting->get_emails_completed_order_content( $context ),
				),
				'onhold_order'         => array(
					'enable'  => $setting->get_emails_onhold_order_enable( $context ),
					'subject' => $setting->get_emails_onhold_order_subject( $context ),
					'heading' => $setting->get_emails_onhold_order_heading( $context ),
					'content' => $setting->get_emails_onhold_order_content( $context ),
				),
				'cancelled_order'      => array(
					'enable'     => $setting->get_emails_cancelled_order_enable( $context ),
					'recipients' => $setting->get_emails_cancelled_order_recipients( $context ),
					'subject'    => $setting->get_emails_cancelled_order_subject( $context ),
					'heading'    => $setting->get_emails_cancelled_order_heading( $context ),
					'content'    => $setting->get_emails_cancelled_order_content( $context ),
				),
				'enrolled_course'      => array(
					'enable'  => $setting->get_emails_enrolled_course_enable( $context ),
					'subject' => $setting->get_emails_enrolled_course_subject( $context ),
					'heading' => $setting->get_emails_enrolled_course_heading( $context ),
					'content' => $setting->get_emails_enrolled_course_content( $context ),
				),
				'completed_course'     => array(
					'enable'  => $setting->get_emails_completed_course_enable( $context ),
					'subject' => $setting->get_emails_completed_course_subject( $context ),
					'heading' => $setting->get_emails_completed_course_heading( $context ),
					'content' => $setting->get_emails_completed_course_content( $context ),
				),
				'become_an_instructor' => array(
					'enable'  => $setting->get_emails_become_an_instructor_enable( $context ),
					'subject' => $setting->get_emails_become_an_instructor_subject( $context ),
					'heading' => $setting->get_emails_become_an_instructor_heading( $context ),
					'content' => $setting->get_emails_become_an_instructor_content( $context ),
				),
			),
			'advanced' => array(
				'template_debug_enable' => $setting->get_advanced_template_debug_enable( $context ),
				'debug_enable'          => $setting->get_advanced_debug_enable( $context ),
				'styles_mode'           => $setting->get_advanced_styles_mode( $context ),
			),
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

		$setting      = masteriyo( 'setting' );
		$setting_repo = masteriyo( 'setting.store' );
		$setting_repo->read( $setting );

		// General Setting.

		if ( isset( $request['general']['address_line1'] ) ) {
			$setting->set_general_address_line1( $request['general']['address_line1'] );
		}

		if ( isset( $request['general']['address_line2'] ) ) {
			$setting->set_general_address_line2( $request['general']['address_line2'] );
		}

		if ( isset( $request['general']['city'] ) ) {
			$setting->set_general_city( $request['general']['city'] );
		}

		if ( isset( $request['general']['country'] ) ) {
			$setting->set_general_country( $request['general']['country'] );
		}

		if ( isset( $request['general']['postcode'] ) ) {
			$setting->set_general_postcode( $request['general']['postcode'] );
		}

		if ( isset( $request['general']['currency'] ) ) {
			$setting->set_general_currency( $request['general']['currency'] );
		}

		if ( isset( $request['general']['currency_position'] ) ) {
			$setting->set_general_currency_position( $request['general']['currency_position'] );
		}

		if ( isset( $request['general']['thousand_separator'] ) ) {
			$setting->set_general_thousand_separator( $request['general']['thousand_separator'] );
		}

		if ( isset( $request['general']['decimal_separator'] ) ) {
			$setting->set_general_decimal_separator( $request['general']['decimal_separator'] );
		}

		if ( isset( $request['general']['number_of_decimals'] ) ) {
			$setting->set_general_number_of_decimals( $request['general']['number_of_decimals'] );
		}

		// Courses Setting.

		if ( isset( $request['courses']['placeholder_image'] ) ) {
			$setting->set_courses_placeholder_image( $request['courses']['placeholder_image'] );
		}

		if ( isset( $request['courses']['add_to_cart_behaviour'] ) ) {
			$setting->set_courses_add_to_cart_behaviour( $request['courses']['add_to_cart_behaviour'] );
		}

		if ( isset( $request['courses']['per_page'] ) ) {
			$setting->set_courses_per_page( $request['courses']['per_page'] );
		}

		if ( isset( $request['courses']['enable_editing'] ) ) {
			$setting->set_courses_enable_editing( $request['courses']['enable_editing'] );
		}

		if ( isset( $request['courses']['category_base'] ) ) {
			$setting->set_courses_category_base( $request['courses']['category_base'] );
		}

		if ( isset( $request['courses']['tag_base'] ) ) {
			$setting->set_courses_tag_base( $request['courses']['tag_base'] );
		}

		if ( isset( $request['courses']['difficulty_base'] ) ) {
			$setting->set_courses_difficulty_base( $request['courses']['difficulty_base'] );
		}

		if ( isset( $request['courses']['single_course_permalink'] ) ) {
			$setting->set_courses_single_course_permalink( $request['courses']['single_course_permalink'] );
		}

		if ( isset( $request['courses']['single_lesson_permalink'] ) ) {
			$setting->set_courses_single_lesson_permalink( $request['courses']['single_lesson_permalink'] );
		}

		if ( isset( $request['courses']['single_quiz_permalink'] ) ) {
			$setting->set_courses_single_quiz_permalink( $request['courses']['single_quiz_permalink'] );
		}

		if ( isset( $request['courses']['single_section_permalink'] ) ) {
			$setting->set_courses_single_section_permalink( $request['courses']['single_section_permalink'] );
		}

		if ( isset( $request['courses']['enable_single_course_permalink'] ) ) {
			$setting->set_courses_enable_single_course_permalink( $request['courses']['enable_single_course_permalink'] );
		}

		if ( isset( $request['courses']['single_course_enable_editing'] ) ) {
			$setting->set_courses_single_course_enable_editing( $request['courses']['single_course_enable_editing'] );
		}

		if ( isset( $request['courses']['show_thumbnail'] ) ) {
			$setting->set_courses_show_thumbnail( $request['courses']['show_thumbnail'] );
		}

		if ( isset( $request['courses']['thumbnail_size'] ) ) {
			$setting->set_courses_thumbnail_size( $request['courses']['thumbnail_size'] );
		}

		// Pages Setting.

		if ( isset( $request['pages']['myaccount_page_id'] ) ) {
			$setting->set_pages_myaccount_page_id( $request['pages']['myaccount_page_id'] );
		}

		if ( isset( $request['pages']['course_list_page_id'] ) ) {
			$setting->set_pages_course_list_page_id( $request['pages']['course_list_page_id'] );
		}

		if ( isset( $request['pages']['terms_conditions_page_id'] ) ) {
			$setting->set_pages_terms_conditions_page_id( $request['pages']['terms_conditions_page_id'] );
		}

		if ( isset( $request['pages']['checkout_page_id'] ) ) {
			$setting->set_pages_checkout_page_id( $request['pages']['checkout_page_id'] );
		}

		// Checkout Endpoints.
		if ( isset( $request['pages']['checkout_endpoints']['pay'] ) ) {
			$setting->set_pages_pay( $request['pages']['checkout_endpoints'] ['pay'] );
		}

		if ( isset( $request['pages']['checkout_endpoints']['order_received'] ) ) {
			$setting->set_pages_order_received( $request['pages']['checkout_endpoints']['order_received'] );
		}

		if ( isset( $request['pages']['checkout_endpoints']['add_payment_method'] ) ) {
			$setting->set_pages_add_payment_method( $request['pages']['checkout_endpoints']['add_payment_method'] );
		}

		if ( isset( $request['pages']['checkout_endpoints']['delete_payment_method'] ) ) {
			$setting->set_pages_delete_payment_method( $request['pages']['checkout_endpoints']['delete_payment_method'] );
		}

		if ( isset( $request['pages']['checkout_endpoints']['set_default_payment_method'] ) ) {
			$setting->set_pages_set_default_payment_method( $request['pages']['checkout_endpoints']['set_default_payment_method'] );
		}

		// Account Endpoints.
		if ( isset( $request['pages']['account_endpoints']['orders'] ) ) {
			$setting->set_pages_orders( $request['pages']['account_endpoints']['orders'] );
		}

		if ( isset( $request['pages']['account_endpoints']['view_order'] ) ) {
			$setting->set_pages_view_order( $request['pages']['account_endpoints']['view_order'] );
		}

		if ( isset( $request['pages']['account_endpoints']['my_courses'] ) ) {
			$setting->set_pages_my_courses( $request['pages']['account_endpoints']['my_courses'] );
		}

		if ( isset( $request['pages']['account_endpoints']['edit_account'] ) ) {
			$setting->set_pages_edit_account( $request['pages']['account_endpoints']['edit_account'] );
		}

		if ( isset( $request['pages']['account_endpoints']['payment_methods'] ) ) {
			$setting->set_pages_payment_methods( $request['pages']['account_endpoints']['payment_methods'] );
		}

		if ( isset( $request['pages']['account_endpoints']['lost_password'] ) ) {
			$setting->set_pages_lost_password( $request['pages']['account_endpoints']['lost_password'] );
		}

		if ( isset( $request['pages']['account_endpoints']['logout'] ) ) {
			$setting->set_pages_logout( $request['pages']['account_endpoints']['logout'] );
		}

		// Payments Setting.

		// Paypal.
		if ( isset( $request['payments']['paypal']['enable'] ) ) {
			$setting->set_payments_paypal_enable( $request['payments']['paypal']['enable'] );
		}

		if ( isset( $request['payments']['paypal']['production_email'] ) ) {
			$setting->set_payments_paypal_production_email( $request['payments']['paypal']['production_email'] );
		}

		if ( isset( $request['payments']['paypal']['sandbox_enable'] ) ) {
			$setting->set_payments_paypal_sandbox_enable( $request['payments']['paypal']['sandbox_enable'] );
		}

		if ( isset( $request['payments']['paypal']['sandbox_email'] ) ) {
			$setting->set_payments_paypal_sandbox_email( $request['payments']['paypal']['sandbox_email'] );
		}

		// Emails Setting.

		// General.
		if ( isset( $request['emails']['general']['from_name'] ) && ! empty( $request['emails']['general']['from_name'] ) ) {
			$setting->set_emails_general_from_name( $request['emails']['general']['from_name'] );
		} else {
			$setting->set_emails_general_from_name( get_bloginfo( 'name' ) );
		}

		if ( isset( $request['emails']['general']['from_email'] ) && ! empty( $request['emails']['general']['from_email'] ) ) {
			$setting->set_emails_general_from_email( $request['emails']['general']['from_email'] );
		} else {
			$setting->set_emails_general_from_email( get_bloginfo( 'admin_email' ) );
		}

		if ( isset( $request['emails']['general']['default_content'] ) ) {
			$setting->set_emails_general_default_content( $request['emails']['general']['default_content'] );
		}

		if ( isset( $request['emails']['general']['header_image'] ) ) {
			$setting->set_emails_general_header_image( $request['emails']['general']['header_image'] );
		}

		if ( isset( $request['emails']['general']['footer_text'] ) ) {
			$setting->set_emails_general_footer_text( $request['emails']['general']['footer_text'] );
		}

		// New order.
		if ( isset( $request['emails']['new_order']['enable'] ) ) {
			$setting->set_emails_new_order_enable( $request['emails']['new_order']['enable'] );
		}

		if ( isset( $request['emails']['new_order']['recipients'] ) ) {
			$setting->set_emails_new_order_recipients( $request['emails']['new_order']['recipients'] );
		}

		if ( isset( $request['emails']['new_order']['subject'] ) ) {
			$setting->set_emails_new_order_subject( $request['emails']['new_order']['subject'] );
		}

		if ( isset( $request['emails']['new_order']['heading'] ) ) {
			$setting->set_emails_new_order_heading( $request['emails']['new_order']['heading'] );
		}

		if ( isset( $request['emails']['new_order']['content'] ) ) {
			$setting->set_emails_new_order_content( $request['emails']['new_order']['content'] );
		}

		// Processing Order.
		if ( isset( $request['emails']['processing_order']['enable'] ) ) {
			$setting->set_emails_processing_order_enable( $request['emails']['processing_order']['enable'] );
		}

		if ( isset( $request['emails']['processing_order']['subject'] ) ) {
			$setting->set_emails_processing_order_subject( $request['emails']['processing_order']['subject'] );
		}

		if ( isset( $request['emails']['processing_order']['heading'] ) ) {
			$setting->set_emails_processing_order_heading( $request['emails']['processing_order']['heading'] );
		}

		if ( isset( $request['emails']['processing_order']['content'] ) ) {
			$setting->set_emails_processing_order_content( $request['emails']['processing_order']['content'] );
		}

		// Completed Order.
		if ( isset( $request['emails']['completed_order']['enable'] ) ) {
			$setting->set_emails_completed_order_enable( $request['emails']['completed_order']['enable'] );
		}

		if ( isset( $request['emails']['completed_order']['subject'] ) ) {
			$setting->set_emails_completed_order_subject( $request['emails']['completed_order']['subject'] );
		}

		if ( isset( $request['emails']['completed_order']['heading'] ) ) {
			$setting->set_emails_completed_order_heading( $request['emails']['completed_order']['heading'] );
		}

		if ( isset( $request['emails']['completed_order']['content'] ) ) {
			$setting->set_emails_completed_order_content( $request['emails']['completed_order']['content'] );
		}

		// On Hold Order.
		if ( isset( $request['emails']['onhold_order']['enable'] ) ) {
			$setting->set_emails_onhold_order_enable( $request['emails']['onhold_order']['enable'] );
		}

		if ( isset( $request['emails']['onhold_order']['subject'] ) ) {
			$setting->set_emails_onhold_order_subject( $request['emails']['onhold_order']['subject'] );
		}

		if ( isset( $request['emails']['onhold_order']['heading'] ) ) {
			$setting->set_emails_onhold_order_heading( $request['emails']['onhold_order']['heading'] );
		}

		if ( isset( $request['emails']['onhold_order']['content'] ) ) {
			$setting->set_emails_onhold_order_content( $request['emails']['onhold_order']['content'] );
		}

		// Cancelled Order.
		if ( isset( $request['emails']['cancelled_order']['enable'] ) ) {
			$setting->set_emails_cancelled_order_enable( $request['emails']['cancelled_order']['enable'] );
		}

		if ( isset( $request['emails']['cancelled_order']['recipients'] ) ) {
			$setting->set_emails_cancelled_order_recipients( $request['emails']['cancelled_order']['recipients'] );
		}

		if ( isset( $request['emails']['cancelled_order']['subject'] ) ) {
			$setting->set_emails_cancelled_order_subject( $request['emails']['cancelled_order']['subject'] );
		}

		if ( isset( $request['emails']['cancelled_order']['heading'] ) ) {
			$setting->set_emails_cancelled_order_heading( $request['emails']['cancelled_order']['heading'] );
		}

		if ( isset( $request['emails']['cancelled_order']['content'] ) ) {
			$setting->set_emails_cancelled_order_content( $request['emails']['cancelled_order']['content'] );
		}

		// Enrolled Course.
		if ( isset( $request['emails']['enrolled_course']['enable'] ) ) {
			$setting->set_emails_enrolled_course_enable( $request['emails']['enrolled_course']['enable'] );
		}

		if ( isset( $request['emails']['enrolled_course']['subject'] ) ) {
			$setting->set_emails_enrolled_course_subject( $request['emails']['enrolled_course']['subject'] );
		}

		if ( isset( $request['emails']['enrolled_course']['heading'] ) ) {
			$setting->set_emails_enrolled_course_heading( $request['emails']['enrolled_course']['heading'] );
		}

		if ( isset( $request['emails']['enrolled_course']['content'] ) ) {
			$setting->set_emails_enrolled_course_content( $request['emails']['enrolled_course']['content'] );
		}

		// Completed Course

		if ( isset( $request['emails']['completed_course']['enable'] ) ) {
			$setting->set_emails_completed_course_enable( $request['emails']['completed_course']['enable'] );
		}

		if ( isset( $request['emails']['completed_course']['subject'] ) ) {
			$setting->set_emails_completed_course_subject( $request['emails']['completed_course']['subject'] );
		}

		if ( isset( $request['emails']['completed_course']['heading'] ) ) {
			$setting->set_emails_completed_course_heading( $request['emails']['completed_course']['heading'] );
		}

		if ( isset( $request['emails']['completed_course']['content'] ) ) {
			$setting->set_emails_completed_course_content( $request['emails']['completed_course']['content'] );
		}

		// Become an instructor.

		if ( isset( $request['emails']['become_an_instructor']['enable'] ) ) {
			$setting->set_emails_become_an_instructor_enable( $request['emails']['become_an_instructor']['enable'] );
		}

		if ( isset( $request['emails']['become_an_instructor']['subject'] ) ) {
			$setting->set_emails_become_an_instructor_subject( $request['emails']['become_an_instructor']['subject'] );
		}

		if ( isset( $request['emails']['become_an_instructor']['heading'] ) ) {
			$setting->set_emails_become_an_instructor_heading( $request['emails']['become_an_instructor']['heading'] );
		}

		if ( isset( $request['emails']['become_an_instructor']['content'] ) ) {
			$setting->set_emails_become_an_instructor_content( $request['emails']['become_an_instructor']['content'] );
		}

		// Advanced Setting.

		if ( isset( $request['advanced']['template_debug_enable'] ) ) {
			$setting->set_advanced_template_debug_enable( $request['advanced']['template_debug_enable'] );
		}

		if ( isset( $request['advanced']['debug_enable'] ) ) {
			$setting->set_advanced_debug_enable( $request['advanced']['debug_enable'] );
		}

		if ( isset( $request['advanced']['styles_mode'] ) ) {
			$setting->set_advanced_styles_mode( $request['advanced']['styles_mode'] );
		}

		/**
		 * Filters an object before it is inserted via the REST API.
		 *
		 * The dynamic portion of the hook name, `$this->object_type`,
		 * refers to the object type slug.
		 *
		 * @since 0.1.0
		 *
		 * @param Model         $setting  Object object.
		 * @param WP_REST_Request $request  Request object.
		 * @param bool            $creating If is creating a new object.
		 */
		return apply_filters( "masteriyo_rest_pre_insert_{$this->object_type}_object", $setting, $request, $creating );
	}

	/**
	 * Return settings as object.
	 *
	 * @since 0.1.0
	 */
	protected function process_objects_collection( $settings ) {
		return array_shift( $settings );
	}
}
