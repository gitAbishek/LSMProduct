<?php
/**
 * Abstract Setting API Class
 *
 * Admin Settings API used by Integrations, Shipping Methods, and Payment Gateways.
 *
 * @since 0.1.0
 *
 * @package  ThemeGrill\Masteriyo\Models
 */

namespace ThemeGrill\Masteriyo\Models;

defined( 'ABSPATH' ) || exit;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Repository\SettingRepository;

/**
 * Setting class.r
 */
class Setting extends Model {

	/**
	 * The plugin ID. Used for option names.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	private $plugin_id = 'masteriyo';

	/**
	 * Group of the class extending the settings API. Used in option names.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	private $group = '';

	/**
	 * Validation errors.
	 *
	 * @since 0.1.0
	 *
	 * @var array of strings
	 */
	private $errors = array();

	/**
	 * This is the name of this object type.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	protected $object_type = 'setting';

	/**
	 * Callbacks for sanitize.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	protected $sanitize_callbacks = array();

	/**
	 * The posted settings data. When empty, $_POST data will be used.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $data = array(
		'general'        => array(
			'styling' => array(
				'primary_color' => '',
				'theme'         => 'minimum',
			),
		),
		'course_archive' => array(
			'display' => array(
				'enable_search'  => true,
				'per_page'       => 12,
				'per_row'        => 4,
				'thumbnail_size' => 'thumbnail',
			),
		),
		'single_course'  => array(
			'display' => array(
				'enable_review' => true,
			),
		),
		'learning_page'  => array(
			'display' => array(
				'enable_questions_answers' => true,
			),
		),
		'payments'       => array(
			'store'    => array(
				'country'       => '',
				'city'          => '',
				'address_line1' => '',
				'address_line2' => '',
			),
			'currency' => array(
				'currency'           => 'USD',
				'currency_position'  => 'left',
				'thousand_separator' => ',',
				'decimal_separator'  => '.',
				'number_of_decimals' => 2,
			),
			'offline'  => array(
				// Offline payment
				'enable'       => false,
				'title'        => 'Offline payment',
				'description'  => 'Pay with offline payment.',
				'instructions' => 'Pay with offline payment',
			),
			'paypal'   => array(
				// Standard Paypal
				'enable'                  => false,
				'title'                   => 'Paypal',
				'description'             => 'Pay via PayPal; you can pay with your credit card if you don\'t have a PayPal account.',
				'ipn_email_notifications' => true,
				'sandbox'                 => false,
				'email'                   => '',
				'receiver_email'          => '',
				'identity_token'          => '',
				'invoice_prefix'          => 'masteriyo-',
				'payment_action'          => 'sale',
				'image_url'               => '',
				'debug'                   => false,
				'sandbox_api_username'    => '',
				'sandbox_api_password'    => '',
				'live_api_username'       => '',
				'live_api_password'       => '',
				'live_api_signature'      => '',
				'sandbox_api_signature'   => '',

			),
		),
		'quiz'           => array(
			'styling' => array(
				'questions_display_per_page' => 5,
			),
		),
		'emails'         => array(
			'general'              => array(
				'enable'          => true,
				'from_name'       => '',
				'from_email'      => '',
				'default_content' => '',
				'header_image'    => '',
				'footer_text'     => '',
			),
			'new_order'            => array(
				'enable'     => true,
				'recipients' => array(),
				'subject'    => '',
				'heading'    => '',
				'content'    => '',
			),
			'processing_order'     => array(
				'enable'  => true,
				'subject' => '',
				'heading' => '',
				'content' => '',
			),
			'completed_order'      => array(
				'enable'  => true,
				'subject' => '',
				'heading' => '',
				'content' => '',
			),
			'onhold_order'         => array(
				'enable'  => true,
				'subject' => '',
				'heading' => '',
				'content' => '',
			),
			'cancelled_order'      => array(
				'enable'     => true,
				'recipients' => array(),
				'subject'    => '',
				'heading'    => '',
				'content'    => '',
			),
			'enrolled_course'      => array(
				'enable'  => true,
				'subject' => '',
				'heading' => '',
				'content' => '',
			),
			'completed_course'     => array(
				'enable'  => true,
				'subject' => '',
				'heading' => '',
				'content' => '',
			),
			'become_an_instructor' => array(
				'enable'  => true,
				'subject' => '',
				'heading' => '',
				'content' => '',

			),
		),
		'advance'        => array(
			'pages'      => array(
				'course_list_page_id'      => -1,
				'myaccount_page_id'        => -1,
				'checkout_page_id'         => -1,
				'terms_conditions_page_id' => -1,
			),
			'permalinks' => array(
				'category_base'            => 'course-category',
				'tag_base'                 => 'course-tag',
				'difficulty_base'          => 'course-difficulty',
				'single_course_permalink'  => 'course',
				'single_section_permalink' => 'section',
				'single_lesson_permalink'  => 'lesson',
				'single_quiz_permalink'    => 'quiz',
			),
			// Account endpoints.
			'account'    => array(
				'dashboard'       => '',
				'orders'          => 'orders',
				'view_order'      => 'view-order',
				'order_history'   => 'order-history',
				'my_courses'      => 'my-courses',
				'view_myaccount'  => 'view-myaccount',
				'edit_account'    => 'edit-account',
				'payment_methods' => 'payment-methods',
				'lost_password'   => 'lost-methods',
				'signup'          => 'signup',
				'logout'          => 'logout',
			),
			// Checkout endpoints.
			'checkout'   => array(
				'pay'                        => 'order-pay',
				'order_received'             => 'order-recieved',
				'add_payment_method'         => 'add-payment-method',
				'delete_payment_method'      => 'delete-payment-method',
				'set_default_payment_method' => 'set-default-payment-method',
			),
			'debug'      => array(
				'template_debug' => false,
				'debug'          => false,
			),
		),
	);

	/**
	 * Get the setting if ID
	 *
	 * @since 0.1.0
	 *
	 * @param SettingRepository $setting_repository Setting Repository,
	 */
	public function __construct( SettingRepository $setting_repository ) {
		$this->repository = $setting_repository;
		$this->init_sanitize_callbacks();
	}

	/**
	 * Get data.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_data() {
		return $this->data;
	}

	/**
	 * Set data.
	 *
	 * @since 0.1.0
	 *
	 * @param array $data
	 */
	public function set_data( $data ) {
		foreach ( $data as $group => $sub_groups ) {
			if ( is_array( $sub_groups ) ) {
				foreach ( $sub_groups as $sub_group => $props ) {
					if ( is_array( $props ) ) {
						foreach ( $props as $prop => $value ) {
							$this->set( $prop, $group, $sub_group, $value );
						}
					} else {
						$this->set( $sub_group, $group, '', $props );
					}
				}
			} else {
				$this->set( $group, '', '', $sub_groups );
			}
		}
	}

	/**
	 * Default datas, might use for resetting data to default value.
	 *
	 * Default data will be returned if not read from store. Otherwise stored datas will return.
	 *
	 * @since 0.1.0
	 *
	 * @param boolean $flat Flat the data.
	 *
	 * @return array $data Default datas.
	 */
	public function get_default_data( $flat = false ) {
		$data = array();

		if ( $flat ) {
			$groups = array_keys( $this->data );
			foreach ( $groups as $group ) {
				foreach ( $this->data[ $group ] as $setting => $value ) {
					$data[ "${group}.${setting}" ] = $value;
				}
			}
		} else {
			$data = $this->data;
		}

		return apply_filters( 'masteriyo_setting_default_data', $data, $flat );
	}

	/**
	 * Get data keys.
	 *
	 * @since 0.1.0
	 */
	public function get_data_keys() {
		$data_keys = array();
		$groups    = array_keys( $this->data );

		foreach ( $groups as $group ) {
			foreach ( $this->data[ $group ] as $setting_name => $default_value ) {
				$data_keys[] = $group . '.' . $setting_name;
			}
		}

		return $data_keys;
	}

	/**
	 * Initialize sanitize callbacks.
	 *
	 * @since 0.1.0
	 */
	protected function init_sanitize_callbacks() {
		$this->add_sanitize_callback( 'number_of_decimals', 'payments', 'currency', 'absint' );

		$this->add_sanitize_callback( 'enable_search', 'course_archive', 'display', 'masteriyo_string_to_bool' );
		$this->add_sanitize_callback( 'per_page', 'course_archive', 'display', 'absint' );
		$this->add_sanitize_callback( 'per_row', 'course_archive', 'display', 'absint' );

		$this->add_sanitize_callback( 'enable_review', 'single_course', 'display', 'masteriyo_string_to_bool' );

		$this->add_sanitize_callback( 'enable_questions_answers', 'learning_page', 'display', 'masteriyo_string_to_bool' );

		$this->add_sanitize_callback( 'category_base', 'advance', 'permalinks', 'sanitize_title' );
		$this->add_sanitize_callback( 'tag_base', 'advance', 'permalinks', 'sanitize_title' );
		$this->add_sanitize_callback( 'difficulty_base', 'advance', 'permalinks', 'sanitize_title' );
		$this->add_sanitize_callback( 'single_course_permalink', 'advance', 'permalinks', 'sanitize_title' );
		$this->add_sanitize_callback( 'single_lesson_permalink', 'advance', 'permalinks', 'sanitize_title' );
		$this->add_sanitize_callback( 'single_quiz_permalink', 'advance', 'permalinks', 'sanitize_title' );
		$this->add_sanitize_callback( 'single_section_permalink', 'advance', 'permalinks', 'sanitize_title' );

		$this->add_sanitize_callback( 'myaccount_page_id', 'advance', 'pages', 'absint' );
		$this->add_sanitize_callback( 'course_list_page_id', 'advance', 'pages', 'absint' );
		$this->add_sanitize_callback( 'terms_conditions_page_id', 'advance', 'pages', 'absint' );
		$this->add_sanitize_callback( 'checkout_page_id', 'advance', 'pages', 'absint' );

		$this->add_sanitize_callback( 'pay', 'advance', 'checkout', 'sanitize_title' );
		$this->add_sanitize_callback( 'order_received', 'advance', 'checkout', 'sanitize_title' );
		$this->add_sanitize_callback( 'add_payment_method', 'advance', 'checkout', 'sanitize_title' );
		$this->add_sanitize_callback( 'delete_payment_method', 'advance', 'checkout', 'sanitize_title' );
		$this->add_sanitize_callback( 'set_default_payment_method', 'advance', 'checkout', 'sanitize_title' );

		$this->add_sanitize_callback( 'dashboard', 'advance', 'account', 'sanitize_title' );
		$this->add_sanitize_callback( 'orders', 'advance', 'account', 'sanitize_title' );
		$this->add_sanitize_callback( 'view_order', 'advance', 'account', 'sanitize_title' );
		$this->add_sanitize_callback( 'order_history', 'advance', 'account', 'sanitize_title' );
		$this->add_sanitize_callback( 'my_courses', 'advance', 'account', 'sanitize_title' );
		$this->add_sanitize_callback( 'view_myaccount', 'advance', 'account', 'sanitize_title' );
		$this->add_sanitize_callback( 'edit_account', 'advance', 'account', 'sanitize_title' );
		$this->add_sanitize_callback( 'payment_methods', 'advance', 'account', 'sanitize_title' );
		$this->add_sanitize_callback( 'lost_password', 'advance', 'account', 'sanitize_title' );
		$this->add_sanitize_callback( 'signup', 'advance', 'account', 'sanitize_title' );
		$this->add_sanitize_callback( 'logout', 'advance', 'account', 'sanitize_title' );

		$this->add_sanitize_callback( 'questions_display_per_page', 'quiz', 'styling', 'absint' );

		$this->add_sanitize_callback( 'enable', 'payments', 'offline', 'masteriyo_string_to_bool' );
		$this->add_sanitize_callback( 'ipn_email_notifications', 'payments', 'paypal', 'masteriyo_string_to_bool' );
		$this->add_sanitize_callback( 'sandbox', 'payments', 'paypal', 'masteriyo_string_to_bool' );
		$this->add_sanitize_callback( 'debug', 'payments', 'paypal', 'masteriyo_string_to_bool' );

		$this->add_sanitize_callback( 'enable', 'emails', 'general', 'masteriyo_string_to_bool' );
		$this->add_sanitize_callback( 'enable', 'emails', 'new_order', 'masteriyo_string_to_bool' );
		$this->add_sanitize_callback( 'enable', 'emails', 'processing_order', 'masteriyo_string_to_bool' );
		$this->add_sanitize_callback( 'enable', 'emails', 'completed_order', 'masteriyo_string_to_bool' );
		$this->add_sanitize_callback( 'enable', 'emails', 'onhold_order', 'masteriyo_string_to_bool' );
		$this->add_sanitize_callback( 'enable', 'emails', 'cancelled_order', 'masteriyo_string_to_bool' );
		$this->add_sanitize_callback( 'enable', 'emails', 'enrolled_course', 'masteriyo_string_to_bool' );
		$this->add_sanitize_callback( 'enable', 'emails', 'completed_course', 'masteriyo_string_to_bool' );
		$this->add_sanitize_callback( 'enable', 'emails', 'become_an_instructor', 'masteriyo_string_to_bool' );
		$this->add_sanitize_callback( 'enable', 'emails', 'enrolled_course', 'masteriyo_string_to_bool' );
	}


	/**
	 * Sanitize the settings
	 *
	 * @since 0.1.0
	 *
	 * @param string $prop    Name of prop to set.
	 * @param string $group Name of setting group.
	 * @param string $sub_group Name of setting sub group.
	 * @param array|string $callback Sanitize callback.
	 */
	protected function add_sanitize_callback( $prop, $group, $sub_group, $callback ) {
		$key                              = "$group.$sub_group.$prop";
		$this->sanitize_callbacks[ $key ] = $callback;
	}

	/**
	 * Sanitize the settings
	 *
	 * @since 0.1.0
	 *
	 * @param string $prop    Name of prop to set.
	 * @param string $group Name of setting group.
	 * @param string $sub_group Name of setting sub group.
	 * @param mixed  $value   Value of the prop.
	 *
	 * @return mixed
	 */
	protected function sanitize( $prop, $group, $sub_group, $value ) {
		$key = "$group.$sub_group.$prop";

		if ( isset( $this->sanitize_callbacks[ $key ] ) ) {
			$value = call_user_func_array( $this->sanitize_callbacks[ $key ], array( $value ) );
		}

		return $value;
	}

	/**
	 * Sets a prop for a setter method.
	 *
	 * @since 0.1.0
	 * @param string $prop    Name of prop to set.
	 * @param string $group Name of setting group.
	 * @param string $sub_group Name of setting sub group.
	 * @param mixed  $value   Value of the prop.
	 */
	public function set( $prop, $group, $sub_group, $value ) {
		$value = $this->sanitize( $prop, $group, $sub_group, $value );

		if ( ! empty( $sub_group ) && ! empty( $group ) && ! empty( $prop ) ) {
			$this->data[ $group ][ $sub_group ][ $prop ] = $value;
		} elseif ( ! empty( $group ) && ! empty( $prop ) ) {
			$this->data[ $group ][ $prop ] = $value;
		} elseif ( ! empty( $prop ) ) {
			$this->data[ $prop ] = $value;
		}
	}

	/**
	 * Gets a prop for a getter method.
	 *
	 * @since  0.1.0
	 * @param  string $prop Name of prop to get.
	 * @param  string $group Setting group.
	 * @param  string $subgroup Setting subgroup.
	 * @param  string $context What the value is for. Valid values are 'view' and 'edit'. What the value is for. Valid values are view and edit.
	 * @return mixed
	 */
	public function get( $prop, $group = 'general', $sub_group = '', $context = 'view' ) {
		$value = null;

		if ( isset( $this->data[ $group ][ $sub_group ][ $prop ] ) ) {
			$value = $this->data[ $group ] [ $sub_group ][ $prop ];
		} elseif ( isset( $this->data[ $group ] [ $prop ] ) ) {
			$value = $this->data[ $group ] [ $prop ];
		} elseif ( isset( $this->dat[ $prop ] ) ) {
			$value = $this->data[ $prop ];
		}

		if ( 'view' === $context ) {
			$value = apply_filters( 'masteriyo_get_setting_value', $value, $prop, $group, $sub_group, $this );
		}

		return $value;
	}
}
