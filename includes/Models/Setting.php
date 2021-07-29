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
				'state'         => '',
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
				'course_list_page_id'      => 0,
				'myaccount_page_id'        => 0,
				'checkout_page_id'         => 0,
				'terms_conditions_page_id' => 0,
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
							$this->set( "{$group}.{$sub_group}.{$prop}", $value );
						}
					} else {
						$this->set( "{$group}.{$sub_group}", $props );
					}
				}
			} else {
				$this->set( "{$group}", $sub_groups );
			}
		}

		$a = 1;
	}

	/**
	 * Initialize sanitize callbacks.
	 *
	 * @since 0.1.0
	 */
	protected function init_sanitize_callbacks() {
		$this->add_sanitize_callback( 'payments.currency.number_of_decimals', 'absint' );

		$this->add_sanitize_callback( 'course_archive.display.enable_search', 'masteriyo_string_to_bool' );
		$this->add_sanitize_callback( 'course_archive.display.per_page', 'absint' );
		$this->add_sanitize_callback( 'course_archive.display.per_row', 'absint' );

		$this->add_sanitize_callback( 'single_course.display.enable_review', 'masteriyo_string_to_bool' );

		$this->add_sanitize_callback( 'learing_page.display.enable_questions_answers', 'masteriyo_string_to_bool' );

		$this->add_sanitize_callback( 'advance.permalinks.category_base', 'sanitize_title' );
		$this->add_sanitize_callback( 'advance.permalinks.tag_base', 'sanitize_title' );
		$this->add_sanitize_callback( 'advance.permalinks.difficulty_base', 'sanitize_title' );
		$this->add_sanitize_callback( 'advance.permalinks.single_course_permalink', 'sanitize_title' );
		$this->add_sanitize_callback( 'advance.permalinks.single_lesson_permalink', 'sanitize_title' );
		$this->add_sanitize_callback( 'advance.permalinks.single_quiz_permalink', 'sanitize_title' );
		$this->add_sanitize_callback( 'advance.permalinks.single_section_permalink', 'sanitize_title' );

		$this->add_sanitize_callback( 'advance.pages.myaccount_page_id', 'absint' );
		$this->add_sanitize_callback( 'advance.pages.course_list_page_id', 'absint' );
		$this->add_sanitize_callback( 'advance.pages.terms_conditions_page_id', 'absint' );
		$this->add_sanitize_callback( 'advance.pages.checkout_page_id', 'absint' );

		$this->add_sanitize_callback( 'advance.checkout.pay', 'sanitize_title' );
		$this->add_sanitize_callback( 'advance.checkout.order_received', 'sanitize_title' );
		$this->add_sanitize_callback( 'advance.checkout.add_payment_method', 'sanitize_title' );
		$this->add_sanitize_callback( 'advance.checkout.delete_payment_method', 'sanitize_title' );
		$this->add_sanitize_callback( 'advance.checkout.set_default_payment_method', 'sanitize_title' );

		$this->add_sanitize_callback( 'advance.account.dashboard', 'sanitize_title' );
		$this->add_sanitize_callback( 'advance.account.orders', 'sanitize_title' );
		$this->add_sanitize_callback( 'advance.account.view_order', 'sanitize_title' );
		$this->add_sanitize_callback( 'advance.account.order_history', 'sanitize_title' );
		$this->add_sanitize_callback( 'advance.account.my_courses', 'sanitize_title' );
		$this->add_sanitize_callback( 'advance.account.view_myaccount', 'sanitize_title' );
		$this->add_sanitize_callback( 'advance.account.edit_account', 'sanitize_title' );
		$this->add_sanitize_callback( 'advance.account.payment_methods', 'sanitize_title' );
		$this->add_sanitize_callback( 'advance.account.lost_password', 'sanitize_title' );
		$this->add_sanitize_callback( 'advance.account.signup', 'sanitize_title' );
		$this->add_sanitize_callback( 'advance.account.logout', 'sanitize_title' );

		$this->add_sanitize_callback( 'quiz.styling.questions_display_per_page', 'absint' );

		$this->add_sanitize_callback( 'payments.offline.enable', 'masteriyo_string_to_bool' );
		$this->add_sanitize_callback( 'payments.offline.ipn_email_notifications', 'masteriyo_string_to_bool' );
		$this->add_sanitize_callback( 'payments.offline.sandbox', 'masteriyo_string_to_bool' );
		$this->add_sanitize_callback( 'payments.offline.debug', 'masteriyo_string_to_bool' );

		$this->add_sanitize_callback( 'emails.general.enable', 'masteriyo_string_to_bool' );
		$this->add_sanitize_callback( 'emails.new_order.enable', 'masteriyo_string_to_bool' );
		$this->add_sanitize_callback( 'emails.processing_order.enable', 'masteriyo_string_to_bool' );
		$this->add_sanitize_callback( 'emails.completed_order.enable', 'masteriyo_string_to_bool' );
		$this->add_sanitize_callback( 'emails.onhold_order.enable', 'masteriyo_string_to_bool' );
		$this->add_sanitize_callback( 'emails.cancelled_order.enable', 'masteriyo_string_to_bool' );
		$this->add_sanitize_callback( 'emails.enrolled_course.enable', 'masteriyo_string_to_bool' );
		$this->add_sanitize_callback( 'emails.completed_course.enable', 'masteriyo_string_to_bool' );
		$this->add_sanitize_callback( 'emails.become_an_instructor.enable', 'masteriyo_string_to_bool' );
	}

	/**
	 * Sanitize the settings
	 *
	 * @since 0.1.0
	 *
	 * @param string $prop    Name of prop to set.
	 * @param array|string $callback Sanitize callback.
	 */
	protected function add_sanitize_callback( $prop, $callback ) {
		$this->sanitize_callbacks[ $prop ] = $callback;
	}

	/**
	 * Sanitize the settings
	 *
	 * @since 0.1.0
	 *
	 * @param string $prop    Name of prop to set.
	 * @param mixed  $value   Value of the prop.
	 *
	 * @return mixed
	 */
	protected function sanitize( $prop, $value ) {
		if ( isset( $this->sanitize_callbacks[ $prop ] ) ) {
			$value = call_user_func_array( $this->sanitize_callbacks[ $prop ], array( $value ) );
		}

		return $value;
	}

	/**
	 * Sets a prop for a setter method.
	 *
	 * @since 0.1.0
	 * @param string $prop    Name of prop to set.
	 * @param mixed  $value   Value of the prop.
	 */
	public function set( $prop, $value ) {
		$prop_arr  = explode( '.', $prop );
		$group     = '';
		$sub_group = '';
		$setting   = '';

		if ( count( $prop_arr ) >= 3 ) {
			$group     = isset( $prop_arr[0] ) ? $prop_arr[0] : '';
			$sub_group = isset( $prop_arr[1] ) ? $prop_arr[1] : '';
			$setting   = isset( $prop_arr[2] ) ? $prop_arr[2] : '';
		} elseif ( count( $prop_arr ) >= 2 ) {
			$group   = isset( $prop_arr[0] ) ? $prop_arr[0] : '';
			$setting = isset( $prop_arr[1] ) ? $prop_arr[1] : '';
		} else {
			$setting = isset( $prop_arr[0] ) ? $prop_arr[0] : '';
		}

		$value = $this->sanitize( $prop, $value );

		if ( ! empty( $sub_group ) && ! empty( $group ) && ! empty( $setting ) ) {
			$this->data[ $group ][ $sub_group ][ $setting ] = $value;
		} elseif ( ! empty( $group ) && ! empty( $setting ) ) {
			$this->data[ $group ][ $setting ] = $value;
		} elseif ( ! empty( $setting ) ) {
			$this->data[ $setting ] = $value;
		}
	}

	/**
	 * Gets a prop for a getter method.
	 *
	 * @since  0.1.0
	 * @param  string $prop Name of prop to get.
	 * @param  string $context What the value is for. Valid values are 'view' and 'edit'. What the value is for. Valid values are view and edit.
	 * @return mixed
	 */
	public function get( $prop, $context = 'view' ) {
		$prop_arr  = explode( '.', $prop );
		$group     = 'masteriyo';
		$sub_group = 'masteriyo';
		$setting   = 'masteriyo';

		if ( count( $prop_arr ) >= 3 ) {
			$group     = isset( $prop_arr[0] ) ? $prop_arr[0] : '';
			$sub_group = isset( $prop_arr[1] ) ? $prop_arr[1] : '';
			$setting   = isset( $prop_arr[2] ) ? $prop_arr[2] : '';
		} elseif ( count( $prop_arr ) >= 2 ) {
			$group   = isset( $prop_arr[0] ) ? $prop_arr[0] : '';
			$setting = isset( $prop_arr[1] ) ? $prop_arr[1] : '';
		} else {
			$setting = isset( $prop_arr[0] ) ? $prop_arr[0] : '';
		}

		$value = null;

		if ( isset( $this->data[ $group ][ $sub_group ][ $setting ] ) ) {
			$value = $this->data[ $group ] [ $sub_group ][ $setting ];
		} elseif ( isset( $this->data[ $group ] [ $setting ] ) ) {
			$value = $this->data[ $group ] [ $setting ];
		} elseif ( isset( $this->dat[ $setting ] ) ) {
			$value = $this->data[ $setting ];
		}

		if ( 'view' === $context ) {
			$value = apply_filters( 'masteriyo_get_setting_value', $value, $prop, $this );
		}

		return $value;
	}
}
