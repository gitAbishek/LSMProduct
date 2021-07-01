<?php
/**
 * Checkout functionality
 *
 * The Masteriyo checkout class handles the checkout process, collecting user data and processing the payment.
 *
 * @package Masteriyo\Classes
 * @version 3.4.0
 */

namespace ThemeGrill\Masteriyo;

use ThemeGrill\Masteriyo\Cart\Cart;
use ThemeGrill\Masteriyo\Query\UserCourseQuery;

use ThemeGrill\Masteriyo\Session\Session;

defined( 'ABSPATH' ) || exit;

/**
 * Checkout class.
 */
class Checkout {

	/**
	 * Cart instance.
	 *
	 * @since 0.1.0
	 *
	 * @var ThemeGrill\masteriyo\Cart\Cart
	 */
	private $cart = null;

	/**
	 * Session instance.
	 *
	 * @since 0.1.0
	 *
	 * @var ThemeGrill\Masteriyo\Session\Session
	 */
	private $session = null;

	/**
	 * Checkout fields.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	private $fields = null;

	/**
	 * Caches User object. @see get_value.
	 *
	 * @since 0.1.0
	 *
	 * @var ThemeGrill\Masteriyo\Models\User
	 */
	private $logged_in_user = null;

	/**
	 * Constructor.
	 */
	public function __construct( Cart $cart, Session $session ) {
		$this->cart    = $cart;
		$this->session = $session;

		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 *
	 * @since 0.1.0
	 */
	private function init_hooks() {
		add_action( 'masteriyo_checkout_form', array( $this, 'billing_form' ), 10 );
	}

	/**
	 * Display the billing form.
	 *
	 * @since 0.1.0
	 */
	public function billing_form() {
		$current_user = masteriyo_get_current_user();

		if ( is_null( $current_user ) ) {
			return;
		}

		masteriyo_get_template(
			'checkout/form-billing.php',
			array(
				'user'     => $current_user,
				'checkout' => $this,
			)
		);
	}

	/**
	 * Process the checkout after the confirm order button is pressed.
	 *
	 * @since 0.1.0
	 *
	 * @throws Exception When validation fails.
	 */
	public function process_checkout() {
		try {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$nonce_value = masteriyo_get_var( $_REQUEST['masteriyo-process-checkout-nonce'], masteriyo_get_var( $_REQUEST['_wpnonce'], '' ) );

			if ( empty( $nonce_value ) || ! wp_verify_nonce( $nonce_value, 'masteriyo-process_checkout' ) ) {
				$this->session->put( 'refresh_totals', true );
				throw new \Exception( __( 'We were unable to process your order, please try again.', 'masteriyo' ) );
			}

			masteriyo_maybe_define_constant( 'MASTERIYO_CHECKOUT', true );
			masteriyo_set_time_limit( 0 );

			do_action( 'masteriyo_before_checkout_process' );

			if ( $this->cart->is_empty() ) {
				throw new \Exception(
					sprintf(
						/* translators: %s: course list url */
						__( 'Sorry, your session has expired. <a href="%s" class="masteriyo-backward">Return to course list</a>', 'masteriyo' ),
						esc_url( masteriyo_get_page_permalink( 'course-list' ) )
					)
				);
			}

			do_action( 'masteriyo_checkout_process' );

			$errors      = new \WP_Error();
			$posted_data = $this->get_posted_data();

			// Update session for user and totals.
			$this->update_session( $posted_data );

			// Validate posted data and cart items before proceeding.
			$this->validate_checkout( $posted_data, $errors );

			foreach ( $errors->errors as $code => $messages ) {
				$data = $errors->get_error_data( $code );
				foreach ( $messages as $message ) {
					masteriyo_add_notice( $message, Notice::ERROR, $data );
				}
			}

			if ( empty( $posted_data['masteriyo_checkout_update_totals'] ) && 0 === masteriyo_notice_count( Notice::ERROR ) ) {
				$this->process_user( $posted_data );
				$order_id = $this->create_order( $posted_data );
				$order    = masteriyo_get_order( $order_id );

				if ( is_wp_error( $order_id ) ) {
					throw new Exception( $order_id->get_error_message() );
				}

				if ( ! $order ) {
					throw new \Exception( __( 'Unable to create order.', 'masteriyo' ) );
				}

				do_action( 'masteriyo_checkout_order_processed', $order_id, $posted_data, $order );

				if ( $order->needs_payment() ) {
					$this->process_order_payment( $order_id, $posted_data['payment_method'] );
				} else {
					$this->process_order_without_payment( $order_id );
				}
			}
		} catch ( Exception $e ) {
			masteriyo_add_notice( $e->getMessage(), Notice::ERROR );
		}

		$this->send_ajax_failure_response();
	}

	/**
	 * Get posted data from the checkout form.
	 *
	 * @since  0.1.0
	 * @return array of data.
	 */
	public function get_posted_data() {
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$data = array(
			'terms'                            => (int) isset( $_POST['terms'] ),
			'payment_method'                   => isset( $_POST['payment_method'] ) ? masteriyo_clean( wp_unslash( $_POST['payment_method'] ) ) : '',
			'masteriyo_checkout_update_totals' => isset( $_POST['masteriyo_checkout_update_totals'] ),
		);
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		$skipped = array();
		foreach ( $this->get_checkout_fields() as $fieldset_key => $fieldset ) {

			if ( $this->maybe_skip_fieldset( $fieldset_key, $data ) ) {
				$skipped[] = $fieldset_key;
				continue;
			}

			foreach ( $fieldset as $key => $field ) {
				$type = sanitize_title( isset( $field['type'] ) ? $field['type'] : 'text' );

				// phpcs:disable WordPress.Security.NonceVerification.Missing
				switch ( $type ) {
					case 'checkbox':
						$value = isset( $_POST[ $key ] ) ? 1 : '';
						break;
					case 'multiselect':
						$value = isset( $_POST[ $key ] ) ? implode( ', ', masteriyo_clean( wp_unslash( $_POST[ $key ] ) ) ) : '';
						break;
					case 'textarea':
						$value = isset( $_POST[ $key ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) ) : '';
						break;
					case 'password':
						$value = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
						break;
					default:
						$value = isset( $_POST[ $key ] ) ? masteriyo_clean( wp_unslash( $_POST[ $key ] ) ) : '';
						break;
				}
				// phpcs:enable WordPress.Security.NonceVerification.Missing

				$data[ $key ] = apply_filters( 'masteriyo_process_checkout_' . $type . '_field', apply_filters( 'masteriyo_process_checkout_field_' . $key, $value ) );
			}
		}

		return apply_filters( 'masteriyo_checkout_posted_data', $data );
	}

	/**
	 * See if a fieldset should be skipped.
	 *
	 * @since 0.1.0
	 * @param string $fieldset_key Fieldset key.
	 * @param array  $data         Posted data.
	 * @return bool
	 */
	protected function maybe_skip_fieldset( $fieldset_key, $data ) {
		return false;
	}

	/**
	 * Is registration required to checkout?
	 *
	 * @since  0.1.0
	 * @return boolean
	 */
	public function is_registration_required() {
		return apply_filters( 'masteriyo_checkout_registration_required', true );
	}


	/**
	 * Get an array of checkout fields.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $fieldset to get.
	 * @return array
	 */
	public function get_checkout_fields( $fieldset = '' ) {
		if ( ! is_null( $this->fields ) ) {
			return $fieldset ? $this->fields[ $fieldset ] : $this->fields;
		}

		$billing_country = $this->get_value( 'billing_country' );

		$this->fields = array(
			'billing' => masteriyo( 'countries' )->get_address_fields( $billing_country, 'billing_' ),
		);

		$this->fields = apply_filters( 'masteriyo_checkout_fields', $this->fields );

		return $fieldset ? $this->fields[ $fieldset ] : $this->fields;
	}

	/**
	 * Update user and session data from the posted checkout data.
	 *
	 * @since 0.1.0
	 * @param array $data Posted data.
	 */
	protected function update_session( $data ) {
		// Update both shipping and billing to the passed billing address first if set.
		$address_fields = array(
			'first_name',
			'last_name',
			'company',
			'email',
			'phone',
			'address_1',
			'address_2',
			'city',
			'postcode',
			'state',
			'country',
		);

		array_walk( $address_fields, array( $this, 'set_user_address_fields' ), $data );
		masteriyo_get_current_user()->save();

		$this->session->put( 'chosen_payment_method', $data['payment_method'] );

		// Update cart totals now we have user address.
		$this->cart->calculate_totals();
	}

	/**
	 * Set address field for user.
	 *
	 * @since 0.1.0
	 * @param string $field String to update.
	 * @param string $key   Field key.
	 * @param array  $data  Array of data to get the value from.
	 */
	protected function set_user_address_fields( $field, $key, $data ) {
		$current_user  = masteriyo_get_current_user();
		$billing_value = null;

		if ( isset( $data[ "billing_{$field}" ] ) && is_callable( array( $current_user, "set_billing_{$field}" ) ) ) {
			$billing_value = $data[ "billing_{$field}" ];
		}

		if ( ! is_null( $billing_value ) && is_callable( array( $current_user, "set_billing_{$field}" ) ) ) {
			$current_user->{"set_billing_{$field}"}( $billing_value );
		}
	}

	/**
	 * Gets the value either from POST.
	 *
	 * @since 0.1.0
	 *
	 * @param string $input Name of the input we want to grab data for. e.g. billing_country.
	 * @return string The default value.
	 */
	public function get_value( $input ) {
		// If the form was posted, get the posted value. This will only tend to happen when JavaScript is disabled client side.
		if ( ! empty( $_POST[ $input ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			return masteriyo_clean( wp_unslash( $_POST[ $input ] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		}

		// Allow 3rd parties to short circuit the logic and return their own default value.
		$value = apply_filters( 'masteriyo_checkout_get_value', null, $input );

		if ( ! is_null( $value ) ) {
			return $value;
		}

		return apply_filters( 'masteriyo_default_checkout_' . $input, $value, $input );
	}

	/**
	 * Validates that the checkout has enough info to proceed.
	 *
	 * @since  0.1.0
	 * @param  array    $data   An array of posted data.
	 * @param  WP_Error $errors Validation errors.
	 */
	protected function validate_checkout( &$data, &$errors ) {
		masteriyo_clear_notices();

		$this->validate_posted_data( $data, $errors );
		$this->check_cart_items();

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( empty( $data['masteriyo_checkout_update_totals'] ) && empty( $data['terms'] ) && ! empty( $_POST['terms-field'] ) ) {
			$errors->add( 'terms', __( 'Please read and accept the terms and conditions to proceed with your order.', 'masteriyo' ) );
		}

		if ( $this->cart->needs_payment() ) {
			$available_gateways = masteriyo( 'payment-gateways' )->get_available_payment_gateways();

			if ( ! isset( $available_gateways[ $data['payment_method'] ] ) ) {
				$errors->add( 'payment', __( 'Invalid payment method.', 'masteriyo' ) );
			} else {
				$available_gateways[ $data['payment_method'] ]->validate_fields();
			}
		}

		do_action( 'masteriyo_after_checkout_validation', $data, $errors );
	}

	/**
	 * When we process the checkout, lets ensure cart items are rechecked to prevent checkout.
	 */
	public function check_cart_items() {
		do_action( 'masteriyo_check_cart_items' );
	}

	/**
	 * Validates the posted checkout data based on field properties.
	 *
	 * @since  0.1.0
	 * @param  array    $data   An array of posted data.
	 * @param  WP_Error $errors Validation error.
	 */
	protected function validate_posted_data( &$data, &$errors ) {
		foreach ( $this->get_checkout_fields() as $fieldset_key => $fieldset ) {
			$validate_fieldset = true;

			if ( $this->maybe_skip_fieldset( $fieldset_key, $data ) ) {
				$validate_fieldset = false;
			}

			foreach ( $fieldset as $key => $field ) {
				if ( ! isset( $data[ $key ] ) ) {
					continue;
				}

				$required    = ! empty( $field['required'] );
				$format      = array_filter( isset( $field['validate'] ) ? (array) $field['validate'] : array() );
				$field_label = isset( $field['label'] ) ? $field['label'] : '';

				if ( $validate_fieldset &&
					( isset( $field['type'] ) && 'country' === $field['type'] && '' !== $data[ $key ] ) &&
					! masteriyo( 'countries' )->country_exists( $data[ $key ] ) ) {
						/* translators: ISO 3166-1 alpha-2 country code */
						$errors->add( $key . '_validation', sprintf( __( "'%s' is not a valid country code.", 'masteriyo' ), $data[ $key ] ) );
				}

				switch ( $fieldset_key ) {
					case 'billing':
						/* translators: %s: field name */
						$field_label = sprintf( _x( 'Billing %s', 'checkout-validation', 'masteriyo' ), $field_label );
						break;
				}

				if ( in_array( 'postcode', $format, true ) ) {
					$country      = isset( $data[ $fieldset_key . '_country' ] ) ? $data[ $fieldset_key . '_country' ] : masteriyo( 'current_user' )->{"get_{$fieldset_key}_country"}();
					$data[ $key ] = masteriyo_format_postcode( $data[ $key ], $country );

					if ( $validate_fieldset && '' !== $data[ $key ] && ! masteriyo_is_postcode( $data[ $key ], $country ) ) {
						switch ( $country ) {
							case 'IE':
								$postcode_validation_notice = sprintf(
									/* translators: %1$s: field name, %2$s finder.eircode.ie URL */
									__( '%1$s is not valid. You can look up the correct Eircode <a target="_blank" href="%2$s">here</a>.', 'masteriyo' ),
									'<strong>' . esc_html( $field_label ) . '</strong>',
									'https://finder.eircode.ie'
								);
								break;
							default:
								/* translators: %s: field name */
								$postcode_validation_notice = sprintf( __( '%s is not a valid postcode / ZIP.', 'masteriyo' ), '<strong>' . esc_html( $field_label ) . '</strong>' );
						}
						$errors->add( $key . '_validation', apply_filters( 'masteriyo_checkout_postcode_validation_notice', $postcode_validation_notice, $country, $data[ $key ] ), array( 'id' => $key ) );
					}
				}

				if ( in_array( 'phone', $format, true ) ) {
					if ( $validate_fieldset && '' !== $data[ $key ] && ! masteriyo_is_phone( $data[ $key ] ) ) {
						/* translators: %s: phone number */
						$errors->add( $key . '_validation', sprintf( __( '%s is not a valid phone number.', 'masteriyo' ), '<strong>' . esc_html( $field_label ) . '</strong>' ), array( 'id' => $key ) );
					}
				}

				if ( in_array( 'email', $format, true ) && '' !== $data[ $key ] ) {
					$email_is_valid = is_email( $data[ $key ] );
					$data[ $key ]   = sanitize_email( $data[ $key ] );

					if ( $validate_fieldset && ! $email_is_valid ) {
						/* translators: %s: email address */
						$errors->add( $key . '_validation', sprintf( __( '%s is not a valid email address.', 'masteriyo' ), '<strong>' . esc_html( $field_label ) . '</strong>' ), array( 'id' => $key ) );
						continue;
					}
				}

				if ( '' !== $data[ $key ] && in_array( 'state', $format, true ) ) {
					$country      = isset( $data[ $fieldset_key . '_country' ] ) ? $data[ $fieldset_key . '_country' ] : masteriyo_get_current_user()->{"get_{$fieldset_key}_country"}();
					$valid_states = masteriyo( 'countries' )->get_states( $country );

					if ( ! empty( $valid_states ) && is_array( $valid_states ) && count( $valid_states ) > 0 ) {
						$valid_state_values = array_map( 'masteriyo_strtoupper', array_flip( array_map( 'masteriyo_strtoupper', $valid_states ) ) );
						$data[ $key ]       = masteriyo_strtoupper( $data[ $key ] );

						if ( isset( $valid_state_values[ $data[ $key ] ] ) ) {
							// With this part we consider state value to be valid as well, convert it to the state key for the valid_states check below.
							$data[ $key ] = $valid_state_values[ $data[ $key ] ];
						}

						if ( $validate_fieldset && ! in_array( $data[ $key ], $valid_state_values, true ) ) {
							/* translators: 1: state field 2: valid states */
							$errors->add( $key . '_validation', sprintf( __( '%1$s is not valid. Please enter one of the following: %2$s', 'masteriyo' ), '<strong>' . esc_html( $field_label ) . '</strong>', implode( ', ', $valid_states ) ), array( 'id' => $key ) );
						}
					}
				}

				if ( $validate_fieldset && $required && '' === $data[ $key ] ) {
					/* translators: %s: field name */
					$errors->add( $key . '_required', apply_filters( 'masteriyo_checkout_required_field_notice', sprintf( __( '%s is a required field.', 'masteriyo' ), '<strong>' . esc_html( $field_label ) . '</strong>' ), $field_label ), array( 'id' => $key ) );
				}
			}
		}
	}

	/**
	 * Create a new user account if needed.
	 *
	 * @throws Exception When not able to create user.
	 * @param array $data Posted data.
	 */
	protected function process_user( $data ) {
		$user_id = apply_filters( 'masteriyo_checkout_user_id', get_current_user_id() );

		// On multisite, ensure user exists on current site, if not add them before allowing login.
		if ( $user_id && is_multisite() && is_user_logged_in() && ! is_user_member_of_blog() ) {
			add_user_to_blog( get_current_blog_id(), $user_id, 'user' );
		}

		// Add user info from other fields.
		if ( $user_id && apply_filters( 'masteriyo_checkout_update_user_data', true, $this ) ) {
			$user = masteriyo( 'user' );
			$user->set_id( $user_id );

			if ( ! empty( $data['billing_first_name'] ) && '' === $user->get_first_name() ) {
				$user->set_first_name( $data['billing_first_name'] );
			}

			if ( ! empty( $data['billing_last_name'] ) && '' === $user->get_last_name() ) {
				$user->set_last_name( $data['billing_last_name'] );
			}

			// If the display name is an email, update to the user's full name.
			if ( is_email( $user->get_display_name() ) ) {
				$user->set_display_name( $user->get_first_name() . ' ' . $user->get_last_name() );
			}

			foreach ( $data as $key => $value ) {
				// Use setters where available.
				if ( is_callable( array( $user, "set_{$key}" ) ) ) {
					$user->{"set_{$key}"}( $value );
				}
			}

			/**
			 * Action hook to adjust user before save during checkout.
			 *
			 * @since 0.1.0
			 */
			do_action( 'masteriyo_checkout_update_user', $user, $data );

			$user->save();
		}

		do_action( 'masteriyo_checkout_update_user_meta', $user_id, $data );
	}

	/**
	 * Create an order. Error codes:
	 *      520 - Cannot insert order into the database.
	 *      521 - Cannot get order after creation.
	 *      522 - Cannot update order.
	 *      525 - Cannot create line item.
	 *      526 - Cannot create fee item.
	 *
	 * @since 0.1.0
	 *
	 * @throws Exception When checkout validation fails.
	 * @param  array $data Posted data.
	 * @return int|WP_ERROR
	 */
	public function create_order( $data ) {
		// Give plugins the opportunity to create an order themselves.
		$order_id = apply_filters( 'masteriyo_create_order', null, $this );

		if ( $order_id ) {
			return $order_id;
		}

		try {
			$order_id           = absint( $this->session->get( 'order_awaiting_payment' ) );
			$cart_hash          = $this->cart->get_cart_hash();
			$available_gateways = masteriyo( 'payment-gateways' )->get_available_payment_gateways();
			$order              = $order_id ? masteriyo_get_order( $order_id ) : null;

			/**
			 * If there is an order pending payment, we can resume it here so
			 * long as it has not changed. If the order has changed, i.e.
			 * different items or cost, create a new order. We use a hash to
			 * detect changes which is based on cart items + order total.
			 */
			if ( $order && $order->has_cart_hash( $cart_hash ) && $order->has_status( array( 'pending', 'failed' ) ) ) {
				// Action for 3rd parties.
				do_action( 'masteriyo_resume_order', $order_id );

				// Remove all items - we will re-add them later.
				$order->remove_order_items();
			} else {
				$order = masteriyo( 'order' );
			}

			$fields_prefix = array(
				'billing' => true,
			);

			foreach ( $data as $key => $value ) {
				if ( is_callable( array( $order, "set_{$key}" ) ) ) {
					$order->{"set_{$key}"}( $value );
				}
			}

			$order->set_created_via( 'checkout' );
			$order->set_cart_hash( $cart_hash );
			$order->set_customer_id( apply_filters( 'masteriyo_checkout_user_id', get_current_user_id() ) );
			$order->set_currency( masteriyo_get_currency() );
			$order->set_customer_ip_address( masteriyo_get_current_ip_address() );
			$order->set_customer_user_agent( masteriyo_get_user_agent() );
			$order->set_payment_method( isset( $available_gateways[ $data['payment_method'] ] ) ? $available_gateways[ $data['payment_method'] ] : $data['payment_method'] );
			$this->set_data_from_cart( $order );

			/**
			 * Action hook to adjust order before save.
			 *
			 * @since 0.1.0
			 */
			do_action( 'masteriyo_checkout_create_order', $order, $data );

			// Save the order.
			$order_id = $order->save();

			/**
			 * Action hook fired after an order is created used to add custom meta to the order.
			 *
			 * @since 0.1.0
			 */
			do_action( 'masteriyo_checkout_update_order_meta', $order_id, $data );

			/**
			 * Action hook fired after an order is created.
			 *
			 * @since 0.1.0
			 */
			do_action( 'masteriyo_checkout_order_created', $order );

			return $order_id;
		} catch ( Exception $e ) {
			if ( $order && is_a( $order, 'ThemeGrill\Masteriyo\Models\Order' ) ) {
				do_action( 'masteriyo_checkout_order_exception', $order );
			}
			return new \WP_Error( 'checkout-error', $e->getMessage() );
		}
	}

	/**
	 * If checkout failed during an AJAX call, send failure response.
	 *
	 * @since 0.1.0
	 */
	protected function send_ajax_failure_response() {
		// Bail early if not ajax.
		if ( ! masteriyo_is_ajax() ) {
			return;
		}

		// Only print notices if not reloading the checkout, otherwise they're lost in the page reload.
		if ( ! is_null( $this->session->get( 'reload_checkout' ) ) ) {
			$messages = masteriyo_print_notices( true );
		}

		$response = array(
			'result'   => 'failure',
			'messages' => isset( $messages ) ? $messages : '',
			'refresh'  => is_null( $this->session->get( 'refresh_totals' ) ),
			'reload'   => is_null( $this->session->get( 'reload_checkout' ) ),
		);

		$this->session->remove( 'refresh_totals' );
		$this->session->remove( 'reload_checkout' );

		wp_send_json( $response );
	}

	/**
	 * Copy line items, tax, totals data from cart to order.
	 *
	 * @since 0.1.0
	 *
	 * @param Order $order Order object.
	 *
	 * @throws Exception When unable to create order.
	 */
	public function set_data_from_cart( &$order ) {
		$order->set_total( $this->cart->get_total( 'edit' ) );
		$this->create_order_course_items( $order );
	}

	/**
	 * Add line items to the order.
	 *
	 * @since 0.1.0
	 *
	 * @param Order $order Order instance.
	 */
	public function create_order_course_items( &$order ) {
		foreach ( $this->cart->get_cart() as $cart_item_key => $values ) {
			$item   = apply_filters( 'masteriyo_checkout_create_order_line_item_object', masteriyo( 'order-item.course' ), $cart_item_key, $values, $order );
			$course = $values['data'];

			$item->set_props(
				array(
					'quantity' => $values['quantity'],
					'subtotal' => $values['line_subtotal'],
					'total'    => $values['line_total'],
				)
			);

			if ( $course ) {
				$item->set_props(
					array(
						'name'      => $course->get_name(),
						'course_id' => $course->get_id(),
					)
				);
			}

			do_action( 'masteriyo_checkout_create_order_line_item', $item, $cart_item_key, $values, $order );

			// Add item to order and save.
			$order->add_item( $item );
		}
	}

	/**
	 * Process an order that does require payment.
	 *
	 * @since 0.1.0
	 * @param int    $order_id       Order ID.
	 * @param string $payment_method Payment method.
	 */
	protected function process_order_payment( $order_id, $payment_method ) {
		$available_gateways = masteriyo( 'payment-gateways' )->get_available_payment_gateways();

		if ( ! isset( $available_gateways[ $payment_method ] ) ) {
			return;
		}

		// Store Order ID in session so it can be re-used after payment failure.
		$this->session->put( 'order_awaiting_payment', $order_id );

		// Process Payment.
		$result = $available_gateways[ $payment_method ]->process_payment( $order_id );

		// Redirect to success/confirmation/payment page.
		if ( isset( $result['result'] ) && 'success' === $result['result'] ) {
			$result['order_id'] = $order_id;

			$result = apply_filters( 'masteriyo_payment_successful_result', $result, $order_id );

			if ( ! masteriyo_is_ajax() ) {
				// phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect
				wp_redirect( $result['redirect'] );
				exit;
			}

			wp_send_json( $result );
		}
	}

	/**
	 * Process an order that doesn't require payment.
	 *
	 * @since 0.1.0
	 * @param int $order_id Order ID.
	 */
	protected function process_order_without_payment( $order_id ) {
		$order = masteriyo_get_order( $order_id );
		$order->payment_complete();

		$this->cart->clear();

		if ( ! masteriyo_is_ajax() ) {
			wp_safe_redirect(
				apply_filters( 'masteriyo_checkout_no_payment_needed_redirect', $order->get_checkout_order_received_url(), $order )
			);
			exit;
		}

		wp_send_json(
			array(
				'result'   => 'success',
				'redirect' => apply_filters( 'masteriyo_checkout_no_payment_needed_redirect', $order->get_checkout_order_received_url(), $order ),
			)
		);
	}
}
