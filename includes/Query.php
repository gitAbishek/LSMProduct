<?php
/**
 * Contains the query functions for Masteriyo which alter the front-end post queries and loops
 *
 * @version 3.2.0
 * @package ThemeGrill\Masteriyo\Classes
 */

namespace ThemeGrill\Masteriyo;

defined( 'ABSPATH' ) || exit;

/**
 * Query Class.
 */
class Query {
	/**
	 * Query vars to add to wp.
	 *
	 * @var array
	 */
	public $query_vars = array();

	/**
	 * Reference to the main product query on the page.
	 *
	 * @var WP_Query
	 */
	private static $product_query;

	/**
	 * Stores chosen attributes.
	 *
	 * @var array
	 */
	private static $chosen_attributes;


	/**
	 * Constructor for the query class. Hooks in methods.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Initialize.
	 *
	 * @return void
	 */
	protected function init() {
		$this->init_hooks();
		$this->init_query_vars();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	protected function init_hooks() {
		add_action( 'init', array( $this, 'add_endpoints' ) );
		if ( ! is_admin() ) {
			add_action( 'wp_loaded', array( $this, 'get_errors' ), 20 );
			add_filter( 'query_vars', array( $this, 'add_query_vars' ), 0 );
			add_action( 'parse_request', array( $this, 'parse_request' ), 0 );
			add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
			add_filter( 'get_pagenum_link', array( $this, 'remove_add_to_cart_pagination' ), 10, 1 );
		}
	}

	/**
	 * Init query vars by loading options.
	 *
	 * @since 0.1.0
	 */
	public function init_query_vars() {
		// Query vars to add to WP.
		$this->query_vars = array(
			// Checkout actions.
			'order-pay'                  => get_option( 'masteriyo_checkout_pay_endpoint', 'order-pay' ),
			'order-received'             => get_option( 'masteriyo_checkout_order_received_endpoint', 'order-received' ),
			// My account actions.
			'orders'                     => get_option( 'masteriyo_myaccount_orders_endpoint', 'orders' ),
			'view-order'                 => get_option( 'masteriyo_myaccount_view_order_endpoint', 'view-order' ),
			'edit-account'               => get_option( 'masteriyo_myaccount_edit_account_endpoint', 'edit-account' ),
			'edit-address'               => get_option( 'masteriyo_myaccount_edit_address_endpoint', 'edit-address' ),
			'payment-methods'            => get_option( 'masteriyo_myaccount_payment_methods_endpoint', 'payment-methods' ),
			'lost-password'              => get_option( 'masteriyo_myaccount_lost_password_endpoint', 'lost-password' ),
			'customer-logout'            => get_option( 'masteriyo_logout_endpoint', 'customer-logout' ),
			'add-payment-method'         => get_option( 'masteriyo_myaccount_add_payment_method_endpoint', 'add-payment-method' ),
			'delete-payment-method'      => get_option( 'masteriyo_myaccount_delete_payment_method_endpoint', 'delete-payment-method' ),
			'set-default-payment-method' => get_option( 'masteriyo_myaccount_set_default_payment_method_endpoint', 'set-default-payment-method' ),
		);
	}

	/**
	 * Get page title for an endpoint.
	 *
	 * @param string $endpoint Endpoint key.
	 * @param string $action Optional action or variation within the endpoint.
	 *
	 * @since 0.1.0
	 * @return string The page title.
	 */
	public function get_endpoint_title( $endpoint, $action = '' ) {
		global $wp;

		switch ( $endpoint ) {
			case 'order-pay':
				$title = __( 'Pay for order', 'masteriyo' );
				break;
			case 'order-received':
				$title = __( 'Order received', 'masteriyo' );
				break;
			case 'orders':
				if ( ! empty( $wp->query_vars['orders'] ) ) {
					/* translators: %s: page */
					$title = sprintf( __( 'Orders (page %d)', 'masteriyo' ), intval( $wp->query_vars['orders'] ) );
				} else {
					$title = __( 'Orders', 'masteriyo' );
				}
				break;
			case 'view-order':
				$order = wc_get_order( $wp->query_vars['view-order'] );
				/* translators: %s: order number */
				$title = ( $order ) ? sprintf( __( 'Order #%s', 'masteriyo' ), $order->get_order_number() ) : '';
				break;
			case 'downloads':
				$title = __( 'Downloads', 'masteriyo' );
				break;
			case 'edit-account':
				$title = __( 'Account details', 'masteriyo' );
				break;
			case 'edit-address':
				$title = __( 'Addresses', 'masteriyo' );
				break;
			case 'payment-methods':
				$title = __( 'Payment methods', 'masteriyo' );
				break;
			case 'add-payment-method':
				$title = __( 'Add payment method', 'masteriyo' );
				break;
			case 'lost-password':
				if ( in_array( $action, array( 'rp', 'resetpass', 'newaccount' ) ) ) {
					$title = __( 'Set password', 'masteriyo' );
				} else {
					$title = __( 'Lost password', 'masteriyo' );
				}
				break;
			default:
				$title = '';
				break;
		}

		/**
		 * Filters the page title used for my-account endpoints.
		 *
		 * @since 0.1.0
		 *
		 * @param string $title Default title.
		 * @param string $endpoint Endpoint key.
		 * @param string $action Optional action or variation within the endpoint.
		 */
		return apply_filters( 'masteriyo_endpoint_' . $endpoint . '_title', $title, $endpoint, $action );
	}

	/**
	 * Endpoint mask describing the places the endpoint should be added.
	 *
	 * @since 0.1.0
	 * @return int
	 */
	public function get_endpoints_mask() {
		if ( 'page' === get_option( 'show_on_front' ) ) {
			$page_on_front     = get_option( 'page_on_front' );
			$myaccount_page_id = get_option( 'masteriyo_myaccount_page_id' );
			$checkout_page_id  = get_option( 'masteriyo_checkout_page_id' );

			if ( in_array( $page_on_front, array( $myaccount_page_id, $checkout_page_id ), true ) ) {
				return EP_ROOT | EP_PAGES;
			}
		}

		return EP_PAGES;
	}
}
