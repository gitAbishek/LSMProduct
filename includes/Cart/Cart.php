<?php
/**
 * Masteriyo cart
 *
 * The Masteriyo cart class stores cart data as well as handling customer sessions and some cart related urls.
 * The cart class also has a price calculation function which calls upon other classes to calculate totals.
 *
 * @package ThemeGrill\Masteriyo\Classes
 * @version 0.1.0
 */

namespace ThemeGrill\Masteriyo\Cart;

use ThemeGrill\Masteriyo\Session\Session;
use ThemeGrill\Masteriyo\Helper\Utils;
use ThemeGrill\Masteriyo\Helper\Arr;
use ThemeGrill\Masteriyo\Notice;

defined( 'ABSPATH' ) || exit;

/**
 * Cart class.
 */
class Cart {
	/**
	 * Contains an array of cart items.
	 *
	 * @since 0.1.0
	 * @var array
	 */
	public $cart_contents = array();

	/**
	 * Contains an array of removed cart items so we can restore them if needed.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	public $removed_cart_contents = array();

	/**
	 * Total defaults used to reset.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $default_totals = array(
		'subtotal'            => 0,
		'cart_contents_total' => 0,
		'fee_total'           => 0,
		'total'               => 0,
	);

	/**
	 * Store calculated totals.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $totals = array();

	/**
	 * Reference to the session handling class.
	 *
	 * @var Session
	 */
	protected $session;

	/**
	 * Reference to the cart session handling class.
	 *
	 * @var Notice
	 */
	protected $notice;

	/**
	 * Reference to the cart fees API class.
	 *
	 * @var Cart\Fees
	 */
	protected $fees_api;

	/**
	 * Constructor for the cart class. Loads options and hooks in the init method.
	 *
	 * @param \ThemeGrill\Masteriyo\Session\SessionHanlder $session Session handler.
	 * @param \ThemeGrill\Masteriyo\Notice                 $notice Notice.
	 *
	 */
	public function __construct( Session $session, Notice $notice, Fees $fees_api ) {
		$this->session  = $session;
		$this->notice   = $notice;
		$this->fees_api = $fees_api;

		// Start the session.
		$this->session->start();

		$this->fees->set_cart( $this );

		$this->init_hooks();
	}

	/**
	 * Initialization hooks.
	 *
	 * @since 0.1.0
	 */
	protected function init_hooks() {
		add_action( 'masteriyo_add_to_cart', array( $this, 'calculate_totals' ), 20, 0 );
		add_action( 'masteriyo_cart_item_removed', array( $this, 'calculate_totals' ), 20, 0 );
		add_action( 'masteriyo_cart_item_restored', array( $this, 'calculate_totals' ), 20, 0 );
		add_action( 'masteriyo_check_cart_items', array( $this, 'check_cart_items' ), 1 );

		// Load the cart from session
		add_action( 'wp_loaded', array( $this, 'get_cart_from_session' ) );
		add_action( 'masteriyo_cart_emptied', array( $this, 'destroy_cart_session' ) );
		// add_action( 'masteriyo_after_calculate_totals', array( $this, 'set_session' ) );
		// add_action( 'masteriyo_cart_loaded_from_session', array( $this, 'set_session' ) );

		// Cookie events - cart cookies need to be set before headers are sent.
		add_action( 'masteriyo_add_to_cart', array( $this, 'maybe_set_cart_cookies' ) );
		add_action( 'wp', array( $this, 'maybe_set_cart_cookies' ), 99 );
		add_action( 'shutdown', array( $this, 'maybe_set_cart_cookies' ), 0 );
	}

	/**
	 * Will set cart cookies if needed and when possible.
	 *
	 * @since 0.1.0
	 */
	public function maybe_set_cart_cookies() {
		if ( ! headers_sent() && did_action( 'wp_loaded' ) ) {
			if ( ! $this->is_empty() ) {
				$this->set_cart_cookies( true );
			} elseif ( isset( $_COOKIE['masteriyo_items_in_cart'] ) ) {
				$this->set_cart_cookies( false );
			}
		}
	}

	/**
	 * Set cart hash cookie and items in cart if not already set.
	 *
	 * @since 0.1.0
	 *
	 * @param bool $set Should cookies be set (true) or unset.
	 */
	private function set_cart_cookies( $set = true ) {
		if ( $set ) {
			$setcookies = array(
				'masteriyo_items_in_cart' => '1',
				'masteriyo_cart_hash'     => $this->get_cart_hash(),
			);
			foreach ( $setcookies as $name => $value ) {
				if ( ! isset( $_COOKIE[ $name ] ) || $_COOKIE[ $name ] !== $value ) {
					Utils::set_cookie( $name, $value );
				}
			}
		} else {
			$unsetcookies = array(
				'masteriyo_items_in_cart',
				'masteriyo_cart_hash',
			);
			foreach ( $unsetcookies as $name ) {
				if ( isset( $_COOKIE[ $name ] ) ) {
					Utils::set_cookie( $name, 0, time() - HOUR_IN_SECONDS );
					unset( $_COOKIE[ $name ] );
				}
			}
		}

		do_action( 'masteriyo_set_cart_cookies', $set );
	}

	/**
	 * Get the cart data from the PHP session and store it in class variables.
	 *
	 * @since 3.2.0
	 */
	public function get_cart_from_session() {
		do_action( 'masteriyo_load_cart_from_session' );

		$this->set_totals( $this->session->get( 'cart_totals', null ) );
		$this->set_removed_cart_contents( $this->session->get( 'removed_cart_contents', array() ) );

		$update_cart_session = false; // Flag to indicate the stored cart should be updated.
		$order_again         = false; // Flag to indicate whether this is a re-order.
		$cart                = $this->session->get( 'cart', null );

		// Populate cart from order.
		if ( isset( $_GET['order_again'], $_GET['_wpnonce'] )
			&& is_user_logged_in()
			&& wp_verify_nonce( wp_unslash( $_GET['_wpnonce'] ), 'masteriyo-order_again' ) ) { // WPCS: input var ok, sanitization ok.
			$cart        = $this->populate_cart_from_order( absint( $_GET['order_again'] ), $cart ); // WPCS: input var ok.
			$order_again = true;
		}

		// Prime caches to reduce future queries.
		if ( is_callable( '_prime_post_caches' ) ) {
			_prime_post_caches( wp_list_pluck( $cart, 'course_id' ) );
		}

		$cart_contents = array();

		foreach ( $cart as $key => $values ) {
			if ( ! is_customize_preview() && 'customize-preview' === $key ) {
				continue;
			}

			$course = masteriyo_get_course( $values['course_id'] );

			if ( empty( $course ) || ! $course->exists() || 0 >= $values['quantity'] ) {
				continue;
			}

			/**
			 * Allow 3rd parties to validate this item before it's added to cart and add their own notices.
			 *
			 * @since 0.1.0
			 *
			 * @param bool $remove_cart_item_from_session If true, the item will not be added to the cart. Default: false.
			 * @param string $key Cart item key.
			 * @param array $values Cart item values e.g. quantity and product_id.
			 */
			if ( apply_filters( 'masteriyo_pre_remove_cart_item_from_session', false, $key, $values ) ) {
				$update_cart_session = true;
				do_action( 'masteriyo_remove_cart_item_from_session', $key, $values );

			} elseif ( ! $course->is_purchasable() ) {
				$update_cart_session = true;
				/* translators: %s: product name */
				$message = sprintf( __( '%s has been removed from your cart because it can no longer be purchased. Please contact us if you need assistance.', 'masteriyo' ), $course->get_name() );
				/**
				 * Filter message about item removed from the cart.
				 *
				 * @since 3.8.0
				 * @param string     $message Message.
				 * @param Course $course Product data.
				 */
				$message = apply_filters( 'masteriyo_cart_item_removed_message', $message, $course );
				$this->notice->add( $message, Notice::ERROR );
				do_action( 'masteriyo_remove_cart_item_from_session', $key, $values );

			} elseif ( ! empty( $values['data_hash'] ) && ! hash_equals( $values['data_hash'], $this->get_cart_item_data_hash( $course ) ) ) { // phpcs:ignore PHPCompatibility.PHP.NewFunctions.hash_equalsFound
				$update_cart_session = true;
				$this->notice->add(
					sprintf(
						/* translators: %1$s: product name. %2$s product permalink */
						__( '%1$s has been removed from your cart because it has since been modified. You can add it back to your cart <a href="%2$s">here</a>.', 'masteriyo' ),
						$course->get_name(),
						$course->get_permalink()
					),
					Notice::INFO
				);
				do_action( 'masteriyo_remove_cart_item_from_session', $key, $values );

			} else {
				// Put session data into array. Run through filter so other plugins can load their own session data.
				$session_data = array_merge(
					$values,
					array(
						'data' => $course,
					)
				);

				$cart_contents[ $key ] = apply_filters( 'masteriyo_get_cart_item_from_session', $session_data, $values, $key );

				// Add to cart right away so the product is visible in masteriyo_get_cart_item_from_session hook.
				$this->set_cart_contents( $cart_contents );
			}
		}

		// If it's not empty, it's been already populated by the loop above.
		if ( ! empty( $cart_contents ) ) {
			$this->set_cart_contents( apply_filters( 'masteriyo_cart_contents_changed', $cart_contents ) );
		}

		do_action( 'masteriyo_cart_loaded_from_session', $this );

		if ( $update_cart_session || is_null( $this->session->get( 'cart_totals', null ) ) ) {
			$this->session->put( 'cart', $this->get_cart_for_session() );
			$this->calculate_totals();
		}

		// If this is a re-order, redirect to the cart page to get rid of the `order_again` query string.
		if ( $order_again ) {
			wp_safe_redirect( masteriyo_get_checkout_url() );
			exit;
		}
	}

	/**
	 * Returns the contents of the cart in an array without the 'data' element.
	 *
	 * @since 0.1.0
	 *
	 * @return array contents of the cart
	 */
	public function get_cart_for_session() {
		$cart_session = array();

		foreach ( $this->get_cart() as $key => $values ) {
			$cart_session[ $key ] = $values;
			unset( $cart_session[ $key ]['data'] ); // Unset product object.
		}

		return $cart_session;
	}


	/**
	 * Get a cart from an order, if user has permission.
	 *
	 * @since  3.5.0
	 *
	 * @param int   $order_id Order ID to try to load.
	 * @param array $cart Current cart array.
	 *
	 * @return array
	 */
	private function populate_cart_from_order( $order_id, $cart ) {
		$order = masteriyo_get_order( $order_id );

		if ( ! $order->get_id()
			|| ! $order->has_status( apply_filters( 'masteriyo_valid_order_statuses_for_order_again', array( 'completed' ) ) )
			|| ! current_user_can( 'order_again', $order->get_id() ) ) {
			return;
		}

		if ( apply_filters( 'masteriyo_empty_cart_when_order_again', true ) ) {
			$cart = array();
		}

		$inital_cart_size = count( $cart );
		$order_items      = $order->get_items();

		foreach ( $order_items as $item ) {
			$course_id      = (int) apply_filters( 'masteriyo_add_to_cart_product_id', $item->get_product_id() );
			$quantity       = $item->get_quantity();
			$variation_id   = (int) $item->get_variation_id();
			$variations     = array();
			$cart_item_data = apply_filters( 'masteriyo_order_again_cart_item_data', array(), $item, $order );
			$course         = $item->get_product();

			if ( ! $course ) {
				continue;
			}

			// Prevent reordering variable products if no selected variation.
			if ( ! $variation_id && $course->is_type( 'variable' ) ) {
				continue;
			}

			// Prevent reordering items specifically out of stock.
			if ( ! $course->is_in_stock() ) {
				continue;
			}

			foreach ( $item->get_meta_data() as $meta ) {
				if ( taxonomy_is_product_attribute( $meta->key ) ) {
					$term                     = get_term_by( 'slug', $meta->value, $meta->key );
					$variations[ $meta->key ] = $term ? $term->name : $meta->value;
				} elseif ( meta_is_product_attribute( $meta->key, $meta->value, $course_id ) ) {
					$variations[ $meta->key ] = $meta->value;
				}
			}

			if ( ! apply_filters( 'masteriyo_add_to_cart_validation', true, $course_id, $quantity, $variation_id, $variations, $cart_item_data ) ) {
				continue;
			}

			// Add to cart directly.
			$cart_id          = MASTERIYO()->cart->generate_cart_id( $course_id, $variation_id, $variations, $cart_item_data );
			$course_data      = masteriyo_get_product( $variation_id ? $variation_id : $course_id );
			$cart[ $cart_id ] = apply_filters(
				'masteriyo_add_order_again_cart_item',
				array_merge(
					$cart_item_data,
					array(
						'key'          => $cart_id,
						'product_id'   => $course_id,
						'variation_id' => $variation_id,
						'variation'    => $variations,
						'quantity'     => $quantity,
						'data'         => $course_data,
						'data_hash'    => masteriyo_get_cart_item_data_hash( $course_data ),
					)
				),
				$cart_id
			);
		}

		do_action_ref_array( 'masteriyo_ordered_again', array( $order->get_id(), $order_items, &$cart ) );

		$num_items_in_cart           = count( $cart );
		$num_items_in_original_order = count( $order_items );
		$num_items_added             = $num_items_in_cart - $inital_cart_size;

		if ( $num_items_in_original_order > $num_items_added ) {
			masteriyo_add_notice(
				sprintf(
					/* translators: %d item count */
					_n(
						'%d item from your previous order is currently unavailable and could not be added to your cart.',
						'%d items from your previous order are currently unavailable and could not be added to your cart.',
						$num_items_in_original_order - $num_items_added,
						'masteriyo'
					),
					$num_items_in_original_order - $num_items_added
				),
				'error'
			);
		}

		if ( 0 < $num_items_added ) {
			masteriyo_add_notice( __( 'The cart has been filled with the items from your previous order.', 'masteriyo' ) );
		}

		return $cart;
	}

	/*
	|--------------------------------------------------------------------------
	| Getters.
	|--------------------------------------------------------------------------
	|
	| Methods to retrieve class properties and avoid direct access.
	*/

	/**
	 * Gets cart contents.
	 *
	 * @since 0.1.0
	 * @return array of cart items
	 */
	public function get_cart_contents() {
		return apply_filters( 'masteriyo_get_cart_contents', (array) $this->cart_contents );
	}

	/**
	 * Return items removed from the cart.
	 *
	 * @since 0.1.0
	 * @return array
	 */
	public function get_removed_cart_contents() {
		return (array) $this->removed_cart_contents;
	}

	/**
	 * Return all calculated totals.
	 *
	 * @since 0.1.0
	 * @return array
	 */
	public function get_totals() {
		return empty( $this->totals ) ? $this->default_totals : $this->totals;
	}

	/**
	 * Get a total.
	 *
	 * @since 0.1.0
	 * @param string $key Key of element in $totals array.
	 * @return mixed
	 */
	protected function get_totals_var( $key ) {
		return isset( $this->totals[ $key ] ) ? $this->totals[ $key ] : $this->default_totals[ $key ];
	}

	/**
	 * Get subtotal.
	 *
	 * @since 0.1.0
	 * @return float
	 */
	public function get_subtotal() {
		return apply_filters( 'masteriyo_cart_' . __FUNCTION__, $this->get_totals_var( 'subtotal' ) );
	}

	/**
	 * Get discount_total.
	 *
	 * @since 0.1.0
	 * @return float
	 */
	public function get_discount_total() {
		return apply_filters( 'masteriyo_cart_' . __FUNCTION__, $this->get_totals_var( 'discount_total' ) );
	}

	/**
	 * Gets cart total. This is the total of items in the cart, but after discounts. Subtotal is before discounts.
	 *
	 * @since 0.1.0
	 * @return float
	 */
	public function get_cart_contents_total() {
		return apply_filters( 'masteriyo_cart_' . __FUNCTION__, $this->get_totals_var( 'cart_contents_total' ) );
	}

	/**
	 * Gets cart total after calculation.
	 *
	 * @since 0.1.0
	 * @param string $context If the context is view, the value will be formatted for display. This keeps it compatible with pre-3.2 versions.
	 * @return float
	 */
	public function get_total( $context = 'view' ) {
		$total = apply_filters( 'masteriyo_cart_' . __FUNCTION__, $this->get_totals_var( 'total' ) );
		return 'view' === $context ? apply_filters( 'masteriyo_cart_total', masteriyo_price( $total ) ) : $total;
	}

	/**
	 * Get total fee amount.
	 *
	 * @since 0.1.0
	 * @return float
	 */
	public function get_fee_total() {
		return apply_filters( 'masteriyo_cart_' . __FUNCTION__, $this->get_totals_var( 'fee_total' ) );
	}

	/*
	|--------------------------------------------------------------------------
	| Setters.
	|--------------------------------------------------------------------------
	|
	| Methods to set class properties and avoid direct access.
	*/

	/**
	 * Sets the contents of the cart.
	 *
	 * @param array $value Cart array.
	 */
	public function set_cart_contents( $value ) {
		$this->cart_contents = (array) $value;
	}

	/**
	 * Set items removed from the cart.
	 *
	 * @since 0.1.0
	 * @param array $value Item array.
	 */
	public function set_removed_cart_contents( $value = array() ) {
		$this->removed_cart_contents = (array) $value;
	}

	/**
	 * Set all calculated totals.
	 *
	 * @since 0.1.0
	 * @param array $value Value to set.
	 */
	public function set_totals( $value = array() ) {
		$this->totals = wp_parse_args( $value, $this->default_totals );
	}

	/**
	 * Set subtotal.
	 *
	 * @since 0.1.0
	 * @param string $value Value to set.
	 */
	public function set_subtotal( $value ) {
		$this->totals['subtotal'] = masteriyo_format_decimal( $value, masteriyo_get_price_decimals() );
	}

	/**
	 * Set discount_total.
	 *
	 * @since 0.1.0
	 * @param string $value Value to set.
	 */
	public function set_discount_total( $value ) {
		$this->totals['discount_total'] = $value;
	}

	/**
	 * Set cart_contents_total.
	 *
	 * @since 0.1.0
	 * @param string $value Value to set.
	 */
	public function set_cart_contents_total( $value ) {
		$this->totals['cart_contents_total'] = masteriyo_format_decimal( $value, masteriyo_get_price_decimals() );
	}

	/**
	 * Set cart total.
	 *
	 * @since 0.1.0
	 * @param string $value Value to set.
	 */
	public function set_total( $value ) {
		$this->totals['total'] = masteriyo_format_decimal( $value, masteriyo_get_price_decimals() );
	}

	/**
	 * Set fee amount.
	 *
	 * @since 0.1.0
	 * @param string $value Value to set.
	 */
	public function set_fee_total( $value ) {
		$this->totals['fee_total'] = masteriyo_format_decimal( $value, masteriyo_get_price_decimals() );
	}

	/*
	|--------------------------------------------------------------------------
	| Helper methods.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Returns the contents of the cart in an array.
	 *
	 * @return array contents of the cart
	 */
	public function get_cart() {
		if ( ! did_action( 'wp_loaded' ) ) {
			Utils::doing_it_wrong( __FUNCTION__, __( 'Get cart should not be called before the wp_loaded action.', 'masteriyo' ), '0.1.0' );
		}
		if ( ! did_action( 'masteriyo_load_cart_from_session' ) ) {
			$this->get_cart_from_session();
		}

		return array_filter( $this->get_cart_contents() );
	}

	/**
	 * Returns a specific item in the cart.
	 *
	 * @param string $item_key Cart item key.
	 * @return array Item data
	 */
	public function get_cart_item( $item_key ) {
		return isset( $this->cart_contents[ $item_key ] ) ? $this->cart_contents[ $item_key ] : array();
	}

	/**
	 * Checks if the cart is empty.
	 *
	 * @return bool
	 */
	public function is_empty() {
		return 0 === count( $this->get_cart() );
	}


	/**
	 * Empties the cart and optionally the persistent cart too.
	 *
	 * @param bool $clear_persistent_cart Should the persistant cart be cleared too. Defaults to true.
	 */
	public function empty_cart( $clear_persistent_cart = true ) {

		do_action( 'masteriyo_before_cart_emptied', $clear_persistent_cart );

		$this->cart_contents         = array();
		$this->removed_cart_contents = array();
		$this->totals                = $this->default_totals;

		if ( $clear_persistent_cart ) {
			$this->session->persistent_cart_destroy();
		}

		$this->fees_api->remove_all_fees();

		do_action( 'masteriyo_cart_emptied', $clear_persistent_cart );
	}

	/**
	 * Get number of items in the cart.
	 *
	 * @return int
	 */
	public function get_cart_contents_count() {
		return apply_filters( 'masteriyo_cart_contents_count', array_sum( wp_list_pluck( $this->get_cart(), 'quantity' ) ) );
	}

	/**
	 * Get cart items quantities - merged so we can do accurate stock checks on items across multiple lines.
	 *
	 * @return array
	 */
	public function get_cart_item_quantities() {
		$quantities = array();

		foreach ( $this->get_cart() as $cart_item_key => $values ) {
			$course = $values['data'];
			$quantities[ $course->get_stock_managed_by_id() ] = isset( $quantities[ $course->get_stock_managed_by_id() ] ) ? $quantities[ $course->get_stock_managed_by_id() ] + $values['quantity'] : $values['quantity'];
		}

		return $quantities;
	}

	/**
	 * Check all cart items for errors.
	 */
	public function check_cart_items() {
		$return = true;
		$result = $this->check_cart_item_validity();

		if ( is_wp_error( $result ) ) {
			masteriyo_add_notice( $result->get_error_message(), 'error' );
			$return = false;
		}

		$result = $this->check_cart_item_stock();

		if ( is_wp_error( $result ) ) {
			masteriyo_add_notice( $result->get_error_message(), 'error' );
			$return = false;
		}

		return $return;

	}

	/**
	 * Looks through cart items and checks the posts are not trashed or deleted.
	 *
	 * @return bool|WP_Error
	 */
	public function check_cart_item_validity() {
		$return = true;

		foreach ( $this->get_cart() as $cart_item_key => $values ) {
			$course = $values['data'];

			if ( ! $course || ! $course->exists() || 'trash' === $course->get_status() ) {
				$this->set_quantity( $cart_item_key, 0 );
				$return = new WP_Error( 'invalid', __( 'An item which is no longer available was removed from your cart.', 'masteriyo' ) );
			}
		}

		return $return;
	}

	/**
	 * Looks through the cart to check each item is in stock. If not, add an error.
	 *
	 * @return bool|WP_Error
	 */
	public function check_cart_item_stock() {
		$error                    = new WP_Error();
		$course_qty_in_cart       = $this->get_cart_item_quantities();
		$current_session_order_id = isset( MASTERIYO()->session->order_awaiting_payment ) ? absint( MASTERIYO()->session->order_awaiting_payment ) : 0;

		foreach ( $this->get_cart() as $cart_item_key => $values ) {
			$course = $values['data'];

			// Check stock based on stock-status.
			if ( ! $course->is_in_stock() ) {
				/* translators: %s: course name */
				$error->add( 'out-of-stock', sprintf( __( 'Sorry, "%s" is not in stock. Please edit your cart and try again. We apologize for any inconvenience caused.', 'masteriyo' ), $course->get_name() ) );
				return $error;
			}

			// We only need to check courses managing stock, with a limited stock qty.
			if ( ! $course->managing_stock() || $course->backorders_allowed() ) {
				continue;
			}

			// Check stock based on all items in the cart and consider any held stock within pending orders.
			$held_stock     = masteriyo_get_held_stock_quantity( $course, $current_session_order_id );
			$required_stock = $course_qty_in_cart[ $course->get_stock_managed_by_id() ];

			/**
			 * Allows filter if course have enough stock to get added to the cart.
			 *
			 * @since 4.6.0
			 * @param bool       $has_stock If have enough stock.
			 * @param MASTERIYO_Course $course   Course instance.
			 * @param array      $values    Cart item values.
			 */
			if ( apply_filters( 'masteriyo_cart_item_required_stock_is_not_enough', $course->get_stock_quantity() < ( $held_stock + $required_stock ), $course, $values ) ) {
				/* translators: 1: course name 2: quantity in stock */
				$error->add( 'out-of-stock', sprintf( __( 'Sorry, we do not have enough "%1$s" in stock to fulfill your order (%2$s available). We apologize for any inconvenience caused.', 'masteriyo' ), $course->get_name(), masteriyo_format_stock_quantity_for_display( $course->get_stock_quantity() - $held_stock, $course ) ) );
				return $error;
			}
		}

		return true;
	}

	/**
	 * Gets and formats a list of cart item data + variations for display on the frontend.
	 *
	 * @param array $cart_item Cart item object.
	 * @param bool  $flat Should the data be returned flat or in a list.
	 * @return string
	 */
	public function get_item_data( $cart_item, $flat = false ) {
		return masteriyo_get_formatted_cart_item_data( $cart_item, $flat );
	}

	/**
	 * Gets cross sells based on the items in the cart.
	 *
	 * @return array cross_sells (item ids)
	 */
	public function get_cross_sells() {
		$cross_sells = array();
		$in_cart     = array();
		if ( ! $this->is_empty() ) {
			foreach ( $this->get_cart() as $cart_item_key => $values ) {
				if ( $values['quantity'] > 0 ) {
					$cross_sells = array_merge( $values['data']->get_cross_sell_ids(), $cross_sells );
					$in_cart[]   = $values['course_id'];
				}
			}
		}
		$cross_sells = array_diff( $cross_sells, $in_cart );
		return apply_filters( 'masteriyo_cart_crosssell_ids', wp_parse_id_list( $cross_sells ), $this );
	}

	/**
	 * Gets the url to remove an item from the cart.
	 *
	 * @param string $cart_item_key contains the id of the cart item.
	 * @return string url to page
	 */
	public function get_remove_url( $cart_item_key ) {
		return masteriyo_get_cart_remove_url( $cart_item_key );
	}

	/**
	 * Gets the url to re-add an item into the cart.
	 *
	 * @param  string $cart_item_key Cart item key to undo.
	 * @return string url to page
	 */
	public function get_undo_url( $cart_item_key ) {
		return masteriyo_get_cart_undo_url( $cart_item_key );
	}

	/**
	 * Determines the value that the customer spent and the subtotal
	 * displayed, used for things like coupon validation.
	 *
	 * @since 0.1.0
	 * @return string
	 */
	public function get_displayed_subtotal() {
		return $this->get_subtotal();
	}

	/**
	 * Check if course is in the cart and return cart item key.
	 *
	 * Cart item key will be unique based on the item and its properties, such as variations.
	 *
	 * @param mixed $cart_id id of course to find in the cart.
	 * @return string cart item key
	 */
	public function find_course_in_cart( $cart_id = false ) {
		if ( false !== $cart_id ) {
			if ( is_array( $this->cart_contents ) && isset( $this->cart_contents[ $cart_id ] ) ) {
				return $cart_id;
			}
		}
		return '';
	}

	/**
	 * Generate a unique ID for the cart item being added.
	 *
	 * @param int   $course_id - id of the course the key is being generated for.
	 * @param array $cart_item_data other cart item data passed which affects this items uniqueness in the cart.
	 * @return string cart item key
	 */
	public function generate_cart_id( $course_id, $cart_item_data = array() ) {
		$id_parts = array( $course_id );

		if ( is_array( $cart_item_data ) && ! empty( $cart_item_data ) ) {
			$cart_item_data_key = '';
			foreach ( $cart_item_data as $key => $value ) {
				if ( is_array( $value ) || is_object( $value ) ) {
					$value = http_build_query( $value );
				}
				$cart_item_data_key .= trim( $key ) . trim( $value );

			}
			$id_parts[] = $cart_item_data_key;
		}

		return apply_filters( 'masteriyo_cart_id', md5( implode( '_', $id_parts ) ), $course_id, $cart_item_data );
	}

	/**
	 * Add a course to the cart.
	 *
	 * @throws Exception Plugins can throw an exception to prevent adding to cart.
	 * @param int   $course_id contains the id of the course to add to the cart.
	 * @param int   $quantity contains the quantity of the item to add.
	 * @param array $cart_item_data extra cart item data we want to pass into the item.
	 * @return string|bool $cart_item_key
	 */
	public function add_to_cart( $course_id = 0, $quantity = 1, $cart_item_data = array() ) {

		try {
			$course   = masteriyo_get_course( $course_id );
			$quantity = apply_filters( 'masteriyo_add_to_cart_quantity', $quantity, $course_id, $course );

			if ( $quantity <= 0 || ! $course || 'trash' === $course->get_status() ) {
				return false;
			}

			// Load cart item data - may be added by other plugins.
			$cart_item_data = (array) apply_filters(
				'masteriyo_add_cart_item_data',
				$cart_item_data,
				$course_id,
				$quantity
			);

			// Generate a ID based on course ID and other cart item data.
			$cart_id = $this->generate_cart_id( $course_id, $cart_item_data );

			// Find the cart item key in the existing cart.
			$cart_item_key = $this->find_course_in_cart( $cart_id );

			if ( ! $course->is_purchasable() ) {
				$message = __( 'Sorry, this course cannot be purchased.', 'masteriyo' );
				/**
				 * Filters message about course unable to be purchased.
				 *
				 * @since 0.1.0
				 * @param string $message Message.
				 * @param Course $course Course data.
				 */
				$message = apply_filters( 'masteriyo_cart_course_cannot_be_purchased_message', $message, $course );
				throw new \Exception( $message );
			}

			// If cart_item_key is set, the item is already in the cart.
			if ( $cart_item_key ) {
				$new_quantity = $quantity + $this->cart_contents[ $cart_item_key ]['quantity'];
				$this->set_quantity( $cart_item_key, $new_quantity, false );
			} else {
				$cart_item_key = $cart_id;

				// Add item after merging with $cart_item_data - hook to allow plugins to modify cart item.
				$this->cart_contents[ $cart_item_key ] = apply_filters(
					'masteriyo_add_cart_item',
					array_merge(
						$cart_item_data,
						array(
							'key'       => $cart_item_key,
							'course_id' => $course_id,
							'quantity'  => $quantity,
							'data'      => $course,
							'data_hash' => $this->get_cart_item_data_hash( $course ),
						)
					),
					$cart_item_key
				);
			}

			$this->cart_contents = apply_filters( 'masteriyo_cart_contents_changed', $this->cart_contents );

			do_action( 'masteriyo_add_to_cart', $cart_item_key, $course_id, $quantity, $cart_item_data );

			return $cart_item_key;

		} catch ( \Exception $e ) {
			if ( $e->getMessage() ) {
				$this->notice->add( $e->getMessage(), Notice::ERROR );
			}
			return false;
		}
	}

	/**
	 * Remove a cart item.
	 *
	 * @since  2.3.0
	 * @param  string $cart_item_key Cart item key to remove from the cart.
	 * @return bool
	 */
	public function remove_cart_item( $cart_item_key ) {
		if ( isset( $this->cart_contents[ $cart_item_key ] ) ) {
			$this->removed_cart_contents[ $cart_item_key ] = $this->cart_contents[ $cart_item_key ];

			unset( $this->removed_cart_contents[ $cart_item_key ]['data'] );

			do_action( 'masteriyo_remove_cart_item', $cart_item_key, $this );

			unset( $this->cart_contents[ $cart_item_key ] );

			do_action( 'masteriyo_cart_item_removed', $cart_item_key, $this );

			return true;
		}
		return false;
	}

	/**
	 * Restore a cart item.
	 *
	 * @param  string $cart_item_key Cart item key to restore to the cart.
	 * @return bool
	 */
	public function restore_cart_item( $cart_item_key ) {
		if ( isset( $this->removed_cart_contents[ $cart_item_key ] ) ) {
			$restore_item                                  = $this->removed_cart_contents[ $cart_item_key ];
			$this->cart_contents[ $cart_item_key ]         = $restore_item;
			$this->cart_contents[ $cart_item_key ]['data'] = masteriyo_get_course( $restore_item['variation_id'] ? $restore_item['variation_id'] : $restore_item['course_id'] );

			do_action( 'masteriyo_restore_cart_item', $cart_item_key, $this );

			unset( $this->removed_cart_contents[ $cart_item_key ] );

			do_action( 'masteriyo_cart_item_restored', $cart_item_key, $this );

			return true;
		}
		return false;
	}

	/**
	 * Set the quantity for an item in the cart using it's key.
	 *
	 * @param string $cart_item_key contains the id of the cart item.
	 * @param int    $quantity contains the quantity of the item.
	 * @param bool   $refresh_totals whether or not to calculate totals after setting the new qty. Can be used to defer calculations if setting quantities in bulk.
	 * @return bool
	 */
	public function set_quantity( $cart_item_key, $quantity = 1, $refresh_totals = true ) {
		if ( 0 === $quantity || $quantity < 0 ) {
			masteriyo_do_deprecated_action( 'masteriyo_before_cart_item_quantity_zero', array( $cart_item_key, $this ), '3.7.0', 'masteriyo_remove_cart_item' );
			// If we're setting qty to 0 we're removing the item from the cart.
			return $this->remove_cart_item( $cart_item_key );
		}

		// Update qty.
		$old_quantity                                      = $this->cart_contents[ $cart_item_key ]['quantity'];
		$this->cart_contents[ $cart_item_key ]['quantity'] = $quantity;

		do_action( 'masteriyo_after_cart_item_quantity_update', $cart_item_key, $quantity, $old_quantity, $this );

		if ( $refresh_totals ) {
			$this->calculate_totals();
		}

		/**
		 * Fired after qty has been changed.
		 *
		 * @since 0.1.0
		 * @param string  $cart_item_key contains the id of the cart item. This may be empty if the cart item does not exist any more.
		 * @param int     $quantity contains the quantity of the item.
		 * @param Cart $this Cart class.
		 */
		do_action( 'masteriyo_cart_item_set_quantity', $cart_item_key, $quantity, $this );

		return true;
	}

	/**
	 * Get cart's owner.
	 *
	 * @since  3.2.0
	 * @return MASTERIYO_Customer
	 */
	public function get_customer() {
		return masteriyo( 'user' );
	}

	/**
	 * Calculate totals for the items in the cart.
	 *
	 * @since 0.1.0
	 *
	 * @uses Cart_Totals
	 */
	public function calculate_totals() {
		$this->reset_totals();

		if ( $this->is_empty() ) {
			$this->session->start();
			return;
		}

		do_action( 'masteriyo_before_calculate_totals', $this );

		new Totals( $this );

		do_action( 'masteriyo_after_calculate_totals', $this );
	}

	/**
	 * Looks at the totals to see if payment is actually required.
	 *
	 * @return bool
	 */
	public function needs_payment() {
		return apply_filters( 'masteriyo_cart_needs_payment', 0 < $this->get_total( 'edit' ), $this );
	}

	/**
	 * Trigger an action so 3rd parties can add custom fees.
	 *
	 * @since 2.0.0
	 */
	public function calculate_fees() {
		do_action( 'masteriyo_cart_calculate_fees', $this );
	}

	/**
	 * Return reference to fees API.
	 *
	 * @since  0.1.0
	 * @return ThemeGrill\Masteriyo\Cart\Fees
	 */
	public function fees_api() {
		return $this->fees;
	}

	/**
	 * Add additional fee to the cart.
	 *
	 * This method should be called on a callback attached to the
	 * masteriyo_cart_calculate_fees action during cart/checkout. Fees do not
	 * persist.
	 *
	 * @since 0.1.0
	 *
	 * @uses ThemeGrill\Masteriyo\Cart\Fees::add_fee
	 * @param string $name      Unique name for the fee. Multiple fees of the same name cannot be added.
	 * @param float  $amount    Fee amount (do not enter negative amounts).
	 */
	public function add_fee( $name, $amount ) {
		$this->fees_api()->add_fee(
			array(
				'name'   => $name,
				'amount' => (float) $amount,
			)
		);
	}

	/**
	 * Return all added fees from the Fees API.
	 *
	 * @since 0.1.0
	 *
	 * @uses ThemeGrill\Masteriyo\Cart\Fees::get_fees
	 * @return array
	 */
	public function get_fees() {
		$fees = $this->fees_api()->get_fees();

		if ( property_exists( $this, 'fees' ) ) {
			$fees = $fees + (array) $this->fees;
		}
		return $fees;
	}

	/**
	 * Gets the cart contents total (after calculation).
	 *
	 * @since 0.1.0
	 * @return string formatted price
	 */
	public function get_cart_total() {
		return apply_filters( 'masteriyo_cart_contents_total', masteriyo_price( $this->get_cart_contents_total() ) );
	}

	/**
	 * Gets the sub total (after calculation).
	 *
	 * @since 0.1.0
	 *
	 * @return string formatted price
	 */
	public function get_cart_subtotal() {
		$cart_subtotal = masteriyo_price( $this->get_subtotal() );
		return apply_filters( 'masteriyo_cart_subtotal', $cart_subtotal, $this );
	}

	/**
	 * Get the course row price per item.
	 *
	 * @since 0.1.0
	 *
	 * @param Course $course Course object.
	 * @return string formatted price
	 */
	public function get_course_price( $course ) {
		$course_price = masteriyo_get_price_excluding_tax( $course );
		return apply_filters( 'masteriyo_cart_course_price', masteriyo_price( $course_price ), $course );
	}

	/**
	 * Get the course row subtotal.
	 *
	 * @since 0.1.0
	 *
	 * @param MASTERIYO_Course $course Course object.
	 * @param int        $quantity Quantity being purchased.
	 * @return string formatted price
	 */
	public function get_course_subtotal( $course, $quantity ) {
		$price           = $course->get_price();
		$row_price       = $price * $quantity;
		$course_subtotal = masteriyo_price( $row_price );

		return apply_filters( 'masteriyo_cart_course_subtotal', $course_subtotal, $course, $quantity, $this );
	}

	/**
	 * Gets the total discount amount.
	 *
	 * @since 0.1.0
	 *
	 * @return mixed formatted price or false if there are none
	 */
	public function get_total_discount() {
		$total_discount = $this->get_discount_total() ? masteriyo_price( $this->get_discount_total() ) : false;
		return apply_filters( 'masteriyo_cart_total_discount', $total_discount, $this );
	}

	/**
	 * Reset cart totals to the defaults. Useful before running calculations.
	 *
	 * @since 0.1.0
	 */
	private function reset_totals() {
		$this->totals = $this->default_totals;
		$this->fees_api->remove_all();
		do_action( 'masteriyo_cart_reset', $this, false );
	}
	/**
	 * Returns the hash based on cart contents.
	 *
	 * @since 0.1.0
	 * @return string hash for cart content
	 */
	public function get_cart_hash() {
		$cart_session = $this->get_cart_for_session();
		$hash         = $cart_session ? md5( wp_json_encode( $cart_session ) . $this->get_total( 'edit' ) ) : '';

		return apply_filters( 'masteriyo_cart_hash', $hash, $cart_session );
	}

	/**
	 * Gets a hash of important course data that when changed should cause cart items to be invalidated.
	 *
	 * The masteriyo_cart_item_data_to_validate filter can be used to add custom properties.
	 *
	 * @param Cours $course Course object.
	 * @return string
	 */
	protected function get_cart_item_data_hash( $course ) {
		return md5(
			wp_json_encode(
				apply_filters(
					'masteriyo_cart_item_data_to_validate',
					array(
						'type'       => 'simple',
						'attributes' => '',
					),
					$course
				)
			)
		);
	}

}
