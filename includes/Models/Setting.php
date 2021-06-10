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
	 * Form option fields.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	private $form_fields = array();

	/**
	 * The posted settings data. When empty, $_POST data will be used.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $data = array(
		'general'  => array(
			'address_line1'      => '',
			'address_line2'      => '',
			'city'               => '',
			'country'            => '',
			'postcode'           => '',
			'currency'           => 'USD',
			'currency_position'  => 'left',
			'thousand_separator' => ',',
			'decimal_separator'  => '.',
			'number_of_decimals' => 2,
		),
		'courses'  => array(
			// General.
			'placeholder_image'        => 0,
			'add_to_cart_behaviour'    => '',
			'per_page'                 => 12,
			'per_row'                  => 4,
			'enable_editing'           => false,

			// Single Course.
			'category_base'            => '',
			'tag_base'                 => '',
			'difficulty_base'          => '',
			'single_course_permalink'  => '',
			'single_lesson_permalink'  => '',
			'single_quiz_permalink'    => '',
			'single_section_permalink' => '',

			// Course Thumbnail.
			'show_thumbnail'           => true,
			'thumbnail_size'           => 'thumbnail',
		),
		'pages'    => array(
			// Page Setup.
			'myaccount_page_id'          => '',
			'course_list_page_id'        => '',
			'terms_conditions_page_id'   => '',
			'checkout_page_id'           => '',

			// Checkout Endpoints.
			'pay'                        => '',
			'order_received'             => '',
			'add_payment_method'         => '',
			'delete_payment_method'      => '',
			'set_default_payment_method' => '',

			// Account Endpoints.
			'orders'                     => '',
			'view_order'                 => '',
			'my_courses'                 => '',
			'edit_account'               => '',
			'payment_methods'            => '',
			'lost_password'              => '',
			'logout'                     => '',
		),
		'quizzes'  => array(
			'time_limit'       => 60,
			'attempts_allowed' => 5,
		),
		'payments' => array(
			// Offline payment
			'offline_enable'                 => false,
			'offline_title'                  => 'Offline payment',
			'offline_description'            => 'Pay with offline payment.',
			'offline_instructions'           => 'Pay with offline payment',

			// Standard Paypal
			'paypal_enable'                  => false,
			'paypal_title'                   => 'Paypal',
			'paypal_description'             => 'Pay via PayPal; you can pay with your credit card if you don\'t have a PayPal account.',
			'paypal_ipn_email_notifications' => true,
			'paypal_sandbox'                 => false,
			'paypal_email'                   => '',
			'paypal_receiver_email'          => '',
			'paypal_identity_token'          => '',
			'paypal_invoice_prefix'          => 'masteriyo-',
			'paypal_payment_action'          => 'sale',
			'paypal_image_url'               => '',
			'paypal_debug'                   => false,
			'paypal_sandbox_api_username'    => '',
			'paypal_sandbox_api_password'    => '',
			'paypal_sandbox_api_signature'   => '',
			'paypal_live_api_username'       => '',
			'paypal_live_api_password'       => '',
			'paypal_live_api_signature'      => '',
		),
		'emails'   => array(
			// General Options.
			'general_from_name'            => '',
			'general_from_email'           => '',

			// General Templates.
			'general_default_content'      => '',
			'general_header_image'         => '',
			'general_footer_text'          => '',

			//New Order.
			'new_order_enable'             => true,
			'new_order_recipients'         => array(),
			'new_order_subject'            => '',
			'new_order_heading'            => '',
			'new_order_content'            => '',

			// Processing Order.
			'processing_order_enable'      => true,
			'processing_order_subject'     => '',
			'processing_order_heading'     => '',
			'processing_order_content'     => '',

			// Completed Order.
			'completed_order_enable'       => true,
			'completed_order_subject'      => '',
			'completed_order_heading'      => '',
			'completed_order_content'      => '',

			// On Hold Order.
			'onhold_order_enable'          => true,
			'onhold_order_subject'         => '',
			'onhold_order_heading'         => '',
			'onhold_order_content'         => '',

			// Cancelled Order.
			'cancelled_order_enable'       => true,
			'cancelled_order_recipients'   => array(),
			'cancelled_order_subject'      => '',
			'cancelled_order_heading'      => '',
			'cancelled_order_content'      => '',

			// Enrolled Course.
			'enrolled_course_enable'       => true,
			'enrolled_course_subject'      => '',
			'enrolled_course_heading'      => '',
			'enrolled_course_content'      => '',

			// Completed Course.
			'completed_course_enable'      => true,
			'completed_course_subject'     => '',
			'completed_course_heading'     => '',
			'completed_course_content'     => '',

			// Become An Instructor.
			'become_an_instructor_enable'  => true,
			'become_an_instructor_subject' => '',
			'become_an_instructor_heading' => '',
			'become_an_instructor_content' => '',
		),
		'advance'  => array(
			'template_debug' => false,
			'debug'          => false,
			'style'          => 'simple',
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
	 * ############# CRUD getters ###########################
	 */

	// General Setting Getter.

	/**
	 * Get option general_address_line1.
	 *
	 * @since 0.10
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_general_address_line1( $context = 'view' ) {
		return $this->get_setting_prop( 'address_line1', 'general', $context );
	}

	/**
	 * Get option general_address_line2.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_general_address_line2( $context = 'view' ) {
		return $this->get_setting_prop( 'address_line2', 'general', $context );
	}

	/**
	 * Get option general_city.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_general_city( $context = 'view' ) {
		return $this->get_setting_prop( 'city', 'general', $context );
	}

	/**
	 * Get option general_country.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_general_country( $context = 'view' ) {
		return $this->get_setting_prop( 'country', 'general', $context );
	}

	/**
	 * Get option general_postcode.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_general_postcode( $context = 'view' ) {
		return $this->get_setting_prop( 'postcode', 'general', $context );
	}

	/**
	 * Get option general_currency.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_general_currency( $context = 'view' ) {
		return $this->get_setting_prop( 'currency', 'general', $context );
	}

	/**
	 * Get option general_currency_position.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_general_currency_position( $context = 'view' ) {
		return $this->get_setting_prop( 'currency_position', 'general', $context );
	}

	/**
	 * Get option general_thousand_separator.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_general_thousand_separator( $context = 'view' ) {
		return $this->get_setting_prop( 'thousand_separator', 'general', $context );
	}

	/**
	 * Get option general_decimal_separator.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_general_decimal_separator( $context = 'view' ) {
		return $this->get_setting_prop( 'decimal_separator', 'general', $context );
	}

	/**
	 * Get option general_number_of_decimals.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_general_number_of_decimals( $context = 'view' ) {
		return $this->get_setting_prop( 'number_of_decimals', 'general', $context );
	}

	// Courses Setting Getter.

	/**
	 * Get option courses_placeholder_image.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_courses_placeholder_image( $context = 'view' ) {
		return $this->get_setting_prop( 'placeholder_image', 'courses', $context );
	}

	/**
	 * Get option courses_add_to_cart_behaviour.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_courses_add_to_cart_behaviour( $context = 'view' ) {
		return $this->get_setting_prop( 'add_to_cart_behavior', 'courses', $context );
	}

	/**
	 * Get option courses_per_page.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_courses_per_page( $context = 'view' ) {
		return $this->get_setting_prop( 'per_page', 'courses', $context );
	}

	/**
	 * Get option courses_per_row.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_courses_per_row( $context = 'view' ) {
		return $this->get_setting_prop( 'per_row', 'courses', $context );
	}

	/**
	 * Get option courses_enable_editing.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_courses_enable_editing( $context = 'view' ) {
		return $this->get_setting_prop( 'enable_editing', 'courses', $context );
	}

	/**
	 * Get option courses_category_base.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_courses_category_base( $context = 'view' ) {
		return $this->get_setting_prop( 'category_base', 'courses', $context );
	}

	/**
	 * Get option courses_tag_base.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_courses_tag_base( $context = 'view' ) {
		return $this->get_setting_prop( 'tag_base', 'courses', $context );
	}

	/**
	 * Get option courses_difficulty_base.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_courses_difficulty_base( $context = 'view' ) {
		return $this->get_setting_prop( 'difficulty_base', 'courses', $context );
	}

	/**
	 * Get option courses_single_course_permalink.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_courses_single_course_permalink( $context = 'view' ) {
		return $this->get_setting_prop( 'single_course_permalink', 'courses', $context );
	}

	/**
	 * Get option courses_single_lesson_permalink.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_courses_single_lesson_permalink( $context = 'view' ) {
		return $this->get_setting_prop( 'single_lesson_permalink', 'courses', $context );
	}

	/**
	 * Get option courses_single_quiz_permalink.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_courses_single_quiz_permalink( $context = 'view' ) {
		return $this->get_setting_prop( 'single_quiz_permalink', 'courses', $context );
	}

	/**
	 * Get option courses_single_section_permalink.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_courses_single_section_permalink( $context = 'view' ) {
		return $this->get_setting_prop( 'single_section_permalink', 'courses', $context );
	}

	/**
	 * Get option courses_show_thumbnail.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_courses_show_thumbnail( $context = 'view' ) {
		return $this->get_setting_prop( 'show_thumbnail', 'courses', $context );
	}

	/**
	 * Get option courses_thumbnail_size.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_courses_thumbnail_size( $context = 'view' ) {
		return $this->get_setting_prop( 'thumbnail_size', 'courses', $context );
	}

	// Pages Setting Getter.

	/**
	 * Get option pages_myaccount_page_id.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_pages_myaccount_page_id( $context = 'view' ) {
		return $this->get_setting_prop( 'myaccount_page_id', 'pages', $context );
	}

	/**
	 * Get option pages_course_list_page_id.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_pages_course_list_page_id( $context = 'view' ) {
		return $this->get_setting_prop( 'course_list_page_id', 'pages', $context );
	}

	/**
	 * Get option pages_terms_conditions_page_id.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_pages_terms_conditions_page_id( $context = 'view' ) {
		return $this->get_setting_prop( 'terms_conditions_page_id', 'pages', $context );
	}

	/**
	 * Get option pages_checkout_page_id.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_pages_checkout_page_id( $context = 'view' ) {
		return $this->get_setting_prop( 'checkout_page_id', 'pages', $context );
	}

	// Checkout endpoints.

	/**
	 * Get option pages_pay.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_pages_pay( $context = 'view' ) {
		return $this->get_setting_prop( 'pay', 'pages', $context );
	}

	/**
	 * Get option pages_order_received.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_pages_order_received( $context = 'view' ) {
		return $this->get_setting_prop( 'order_received', 'pages', $context );
	}

	/**
	 * Get option pages_add_payment_method.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_pages_add_payment_method( $context = 'view' ) {
		return $this->get_setting_prop( 'add_payment_method', 'pages', $context );
	}

	/**
	 * Get option pages_delete_payment_method.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_pages_delete_payment_method( $context = 'view' ) {
		return $this->get_setting_prop( 'delete_payment_method', 'pages', $context );
	}

	/**
	 * Get option pages_set_default_payment_method.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_pages_set_default_payment_method( $context = 'view' ) {
		return $this->get_setting_prop( 'set_default_payment_method', 'pages', $context );
	}

	// Acoount endpoints.

	/**
	 * Get option pages_orders.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_pages_orders( $context = 'view' ) {
		return $this->get_setting_prop( 'orders', 'pages', $context );
	}

	/**
	 * Get option pages_view_order.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_pages_view_order( $context = 'view' ) {
		return $this->get_setting_prop( 'view_order', 'pages', $context );
	}

	/**
	 * Get option pages_my_courses.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_pages_my_courses( $context = 'view' ) {
		return $this->get_setting_prop( 'my_courses', 'pages', $context );
	}

	/**
	 * Get option pages_edit_account.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_pages_edit_account( $context = 'view' ) {
		return $this->get_setting_prop( 'edit_account', 'pages', $context );
	}

	/**
	 * Get option pages_payment_methods.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_pages_payment_methods( $context = 'view' ) {
		return $this->get_setting_prop( 'payment_methods', 'pages', $context );
	}

	/**
	 * Get option pages_lost_password.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_pages_lost_password( $context = 'view' ) {
		return $this->get_setting_prop( 'lost_password', 'pages', $context );
	}

	/**
	 * Get option pages_logout.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_pages_logout( $context = 'view' ) {
		return $this->get_setting_prop( 'logout', 'pages', $context );
	}

	// Quizzes Setting Getter.

	/**
	 * Get option quizzes_time_limit.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_quizzes_time_limit( $context = 'view' ) {
		return $this->get_setting_prop( 'time_limit', 'quizzes', $context );
	}

	/**
	 * Get option quizzes_attempts_allowed.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_quizzes_attempts_allowed( $context = 'view' ) {
		return $this->get_setting_prop( 'attempts_allowed', 'quizzes', $context );
	}


	// Payments Setting Getter.

	// Offline

	/**
	 * Check whether the offline payment is enable or not.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_payments_offline_enable( $context = 'view' ) {
		return $this->get_setting_prop( 'offline_enable', 'payments', $context );
	}

	/**
	 * Get offline payment title.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_payments_offline_title( $context = 'view' ) {
		return $this->get_setting_prop( 'offline_title', 'payments', $context );
	}

	/**
	 * Get offline payment description.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_payments_offline_description( $context = 'view' ) {
		return $this->get_setting_prop( 'offline_description', 'payments', $context );
	}

	/**
	 * Get offline payment instructions.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_payments_offline_instructions( $context = 'view' ) {
		return $this->get_setting_prop( 'offline_instructions', 'payments', $context );
	}


	// Paypal

	/**
	 * Get option payments_paypal_enable.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_payments_paypal_enable( $context = 'view' ) {
		return $this->get_setting_prop( 'paypal_enable', 'payments', $context );
	}

	/**
	 * Get paypal payment gateway title.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_payments_paypal_title( $context = 'view' ) {
		return $this->get_setting_prop( 'paypal_title', 'payments', $context );
	}

	/**
	 * Get paypal payment gateway description.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_payments_paypal_description( $context = 'view' ) {
		return $this->get_setting_prop( 'paypal_description', 'payments', $context );
	}

	/**
	 * Check whether the paypal ipn email notifications is enabled or not.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_payments_paypal_ipn_email_notifications( $context = 'view' ) {
		return $this->get_setting_prop( 'paypal_ipn_email_notifications', 'payments', $context );
	}

	/**
	 * Check whether the paypal sandbox.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_payments_paypal_sandbox( $context = 'view' ) {
		return $this->get_setting_prop( 'paypal_sandbox', 'payments', $context );
	}

	/**
	 * Get paypal payment gateway email.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_payments_paypal_email( $context = 'view' ) {
		$email = $this->get_setting_prop( 'paypal_email', 'payments', 'edit' );

		if ( empty( $email ) ) {
			$this->set_setting_prop( 'paypal_email', 'payments', get_bloginfo( 'admin_email' ) );
		}

		return $this->get_setting_prop( 'paypal_email', 'payments', $context );

	}

	/**
	 * Get paypal payment gateway receiver email.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_payments_paypal_receiver_email( $context = 'view' ) {
		$email = $this->get_setting_prop( 'paypal_receiver_email', 'payments', 'edit' );

		if ( empty( $email ) ) {
			$this->set_setting_prop( 'paypal_receiver_email', 'payments', get_bloginfo( 'admin_email' ) );
		}

		return $this->get_setting_prop( 'paypal_receiver_email', 'payments', $context );
	}

	/**
	 * Get paypal payment gateway identity token.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_payments_paypal_identity_token( $context = 'view' ) {
		return $this->get_setting_prop( 'paypal_identity_token', 'payments', $context );
	}

	/**
	 * Get paypal payment gateway invoice prefix.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_payments_paypal_invoice_prefix( $context = 'view' ) {
		return $this->get_setting_prop( 'paypal_invoice_prefix', 'payments', $context );
	}

	/**
	 * Get paypal payment gateway paymentaction.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_payments_paypal_payment_action( $context = 'view' ) {
		return $this->get_setting_prop( 'paypal_payment_action', 'payments', $context );
	}

	/**
	 * Get paypal payment gateway image url.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_payments_paypal_image_url( $context = 'view' ) {
		return $this->get_setting_prop( 'paypal_image_url', 'payments', $context );
	}

	/**
	 * Check whether the paypal log is enabled or not.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_payments_paypal_debug( $context = 'view' ) {
		return $this->get_setting_prop( 'paypal_debug', 'payments', $context );
	}

	/**
	 * Get paypal sandbox api username.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_payments_paypal_sandbox_api_username( $context = 'view' ) {
		return $this->get_setting_prop( 'paypal_sandbox_api_username', 'payments', $context );
	}

	/**
	 * Get paypal sandbox api passsword.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_payments_paypal_sandbox_api_password( $context = 'view' ) {
		return $this->get_setting_prop( 'paypal_sandbox_api_password', 'payments', $context );
	}

	/**
	 * Get paypal sandbox api signature.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_payments_paypal_sandbox_api_signature( $context = 'view' ) {
		return $this->get_setting_prop( 'paypal_sandbox_api_signature', 'payments', $context );
	}

	/**
	 * Get paypal live api username.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_payments_paypal_live_api_username( $context = 'view' ) {
		return $this->get_setting_prop( 'paypal_live_api_username', 'payments', $context );
	}

	/**
	 * Get paypal live api passsword.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_payments_paypal_live_api_password( $context = 'view' ) {
		return $this->get_setting_prop( 'paypal_live_api_password', 'payments', $context );
	}

	/**
	 * Get paypal live api signature.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_payments_paypal_live_api_signature( $context = 'view' ) {
		return $this->get_setting_prop( 'paypal_live_api_signature', 'payments', $context );
	}


	// Email Setting Getter.

	// General.

	/**
	 * Get email general mail from name.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_general_from_name( $context = 'view' ) {
		return $this->get_setting_prop( 'general_from_name', 'emails', $context );
	}

	/**
	 * Get email general from email.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_general_from_email( $context = 'view' ) {
		return $this->get_setting_prop( 'general_from_email', 'emails', $context );
	}

	/**
	 * Get email general default content.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_general_default_content( $context = 'view' ) {
		return $this->get_setting_prop( 'general_default_content', 'emails', $context );
	}

	/**
	 * Get email general header image.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_general_header_image( $context = 'view' ) {
		return $this->get_setting_prop( 'general_header_image', 'emails', $context );
	}

	/**
	 * Get email general footer text.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_general_footer_text( $context = 'view' ) {
		return $this->get_setting_prop( 'general_footer_text', 'emails', $context );
	}

	// New order.

	/**
	 * Get option emails_new_order_enable.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_new_order_enable( $context = 'view' ) {
		return $this->get_setting_prop( 'new_order_enable', 'emails', $context );
	}

	/**
	 * Get option emails_new_order_recipients.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_new_order_recipients( $context = 'view' ) {
		return $this->get_setting_prop( 'new_order_recipients', 'emails', $context );
	}

	/**
	 * Get option emails_new_order_subject.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_new_order_subject( $context = 'view' ) {
		return $this->get_setting_prop( 'new_order_subject', 'emails', $context );
	}

	/**
	 * Get option emails_new_order_heading.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_new_order_heading( $context = 'view' ) {
		return $this->get_setting_prop( 'new_order_heading', 'emails', $context );
	}

	/**
	 * Get option emails_new_order_content.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_new_order_content( $context = 'view' ) {
		return $this->get_setting_prop( 'new_order_content', 'emails', $context );
	}

	// Processing order.

	/**
	 * Get option emails_processing_order_enable.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_processing_order_enable( $context = 'view' ) {
		return $this->get_setting_prop( 'processing_order_enable', 'emails', $context );
	}

	/**
	 * Get option emails_processing_order_subject.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_processing_order_subject( $context = 'view' ) {
		return $this->get_setting_prop( 'processing_order_subject', 'emails', $context );
	}

	/**
	 * Get option emails_processing_order_heading.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_processing_order_heading( $context = 'view' ) {
		return $this->get_setting_prop( 'processing_order_heading', 'emails', $context );
	}

	/**
	 * Get option emails_processing_order_content.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_processing_order_content( $context = 'view' ) {
		return $this->get_setting_prop( 'processing_order_content', 'emails', $context );
	}

	// Completed order.

	/**
	 * Get option emails_completed_order_enable.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_completed_order_enable( $context = 'view' ) {
		return $this->get_setting_prop( 'completed_order_enable', 'emails', $context );
	}

	/**
	 * Get option emails_completed_order_subject.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_completed_order_subject( $context = 'view' ) {
		return $this->get_setting_prop( 'completed_order_subject', 'emails', $context );
	}

	/**
	 * Get option emails_completed_order_heading.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_completed_order_heading( $context = 'view' ) {
		return $this->get_setting_prop( 'completed_order_heading', 'emails', $context );
	}

	/**
	 * Get option emails_completed_order_content.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_completed_order_content( $context = 'view' ) {
		return $this->get_setting_prop( 'completed_order_content', 'emails', $context );
	}

	// On Hold Order.

	/**
	 * Get option emails_onhold_order_enable.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_onhold_order_enable( $context = 'view' ) {
		return $this->get_setting_prop( 'onhold_order_enable', 'emails', $context );
	}

	/**
	 * Get option emails_onhold_order_subject.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_onhold_order_subject( $context = 'view' ) {
		return $this->get_setting_prop( 'onhold_order_subject', 'emails', $context );
	}

	/**
	 * Get option emails_onhold_order_heading.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_onhold_order_heading( $context = 'view' ) {
		return $this->get_setting_prop( 'onhold_order_heading', 'emails', $context );
	}

	/**
	 * Get option emails_onhold_order_content.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_onhold_order_content( $context = 'view' ) {
		return $this->get_setting_prop( 'onhold_order_content', 'emails', $context );
	}

	// Cancelled Order.

	/**
	 * Get option emails_cancelled_order_enable.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_cancelled_order_enable( $context = 'view' ) {
		return $this->get_setting_prop( 'cancelled_order_enable', 'emails', $context );
	}

	/**
	 * Get option emails_cancelled_order_recipients.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_cancelled_order_recipients( $context = 'view' ) {
		return $this->get_setting_prop( 'cancelled_order_recipients', 'emails', $context );
	}

	/**
	 * Get option emails_cancelled_order_subject.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_cancelled_order_subject( $context = 'view' ) {
		return $this->get_setting_prop( 'cancelled_order_subject', 'emails', $context );
	}

	/**
	 * Get option emails_cancelled_order_heading.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_cancelled_order_heading( $context = 'view' ) {
		return $this->get_setting_prop( 'cancelled_order_heading', 'emails', $context );
	}

	/**
	 * Get option emails_cancelled_order_content.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_cancelled_order_content( $context = 'view' ) {
		return $this->get_setting_prop( 'cancelled_order_content', 'emails', $context );
	}

	// Enrolled Course.

	/**
	 * Get option emails_enrolled_course_enable.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_enrolled_course_enable( $context = 'view' ) {
		return $this->get_setting_prop( 'enrolled_course_enable', 'emails', $context );
	}

	/**
	 * Get option emails_enrolled_course_subject.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_enrolled_course_subject( $context = 'view' ) {
		return $this->get_setting_prop( 'enrolled_course_subject', 'emails', $context );
	}

	/**
	 * Get option emails_enrolled_course_heading.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_enrolled_course_heading( $context = 'view' ) {
		return $this->get_setting_prop( 'enrolled_course_heading', 'emails', $context );
	}

	/**
	 * Get option emails_enrolled_course_content.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_enrolled_course_content( $context = 'view' ) {
		return $this->get_setting_prop( 'enrolled_course_content', 'emails', $context );
	}

	// Completed Course.

	/**
	 * Get option emails_completed_course_enable.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_completed_course_enable( $context = 'view' ) {
		return $this->get_setting_prop( 'completed_course_enable', 'emails', $context );
	}

	/**
	 * Get option emails_completed_course_subject.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_completed_course_subject( $context = 'view' ) {
		return $this->get_setting_prop( 'completed_course_subject', 'emails', $context );
	}

	/**
	 * Get option emails_completed_course_heading.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_completed_course_heading( $context = 'view' ) {
		return $this->get_setting_prop( 'completed_course_heading', 'emails', $context );
	}

	/**
	 * Get option emails_completed_course_content.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_completed_course_content( $context = 'view' ) {
		return $this->get_setting_prop( 'completed_course_content', 'emails', $context );
	}

	// Become An Instructor.

	/**
	 * Get option emails_become_an_instructor_enable.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_become_an_instructor_enable( $context = 'view' ) {
		return $this->get_setting_prop( 'become_an_instructor_enable', 'emails', $context );
	}

	/**
	 * Get option emails_become_an_instructor_subject.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_become_an_instructor_subject( $context = 'view' ) {
		return $this->get_setting_prop( 'become_an_instructor_subject', 'emails', $context );
	}

	/**
	 * Get option emails_become_an_instructor_heading.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_become_an_instructor_heading( $context = 'view' ) {
		return $this->get_setting_prop( 'become_an_instructor_heading', 'emails', $context );
	}

	/**
	 * Get option emails_become_an_instructor_content.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_become_an_instructor_content( $context = 'view' ) {
		return $this->get_setting_prop( 'become_an_instructor_content', 'emails', $context );
	}

	// Advanced Setting.

	// Debug.

	/**
	 * Retruns true if the template debug is enabled.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_advance_template_debug( $context = 'view' ) {
		return $this->get_setting_prop( 'template_debug', 'advance', $context );
	}

	/**
	 * Returns true if the masteriyo debug is enabled.
	*
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_advance_debug( $context = 'view' ) {
		return $this->get_setting_prop( 'debug', 'advance', $context );
	}

	/**
	 * Get option advanced_style.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_advance_style( $context = 'view' ) {
		return $this->get_setting_prop( 'style', 'advance', $context );
	}

	/**
	 * ############# CRUD setters ###########################
	 */

	// General Setting Setter.

	/**
	 * Set option general address line1.
	 *
	 * @since 0.1.0
	 * @param string $address_line1
	 */
	public function set_general_address_line1( $address_line1 ) {
		$this->set_setting_prop( 'address_line1', 'general', $address_line1 );
	}

	/**
	 * Set option general address line2.
	 *
	 * @since 0.1.0
	 * @param string $address_line2
	 */
	public function set_general_address_line2( $address_line2 ) {
		$this->set_setting_prop( 'address_line2', 'general', $address_line2 );
	}

	/**
	 * Set option general city.
	 *
	 * @since 0.1.0
	 * @param string $city
	 */
	public function set_general_city( $city ) {
		$this->set_setting_prop( 'city', 'general', $city );
	}

	/**
	 * Set option general country.
	 *
	 * @since 0.1.0
	 * @param string $country
	 */
	public function set_general_country( $country ) {
		$this->set_setting_prop( 'country', 'general', $country );
	}

	/**
	 * Set option general postcode.
	 *
	 * @since 0.1.0
	 * @param string $postcode
	 */
	public function set_general_postcode( $postcode ) {
		$this->set_setting_prop( 'postcode', 'general', $postcode );
	}

	/**
	 * Set option general currency.
	 *
	 * @since 0.1.0
	 * @param string $currency
	 */
	public function set_general_currency( $currency ) {
		$this->set_setting_prop( 'currency', 'general', $currency );
	}

	/**
	 * Set option general currency position.
	*
	* @since 0.1.0
	* @param string $position
	*/
	public function set_general_currency_position( $position ) {
		$this->set_setting_prop( 'currency_position', 'general', $position );
	}

	/**
	 * Set option general thousand separator.
	*
	* @since 0.1.0
	* @param string $separator
	*/
	public function set_general_thousand_separator( $separator ) {
		$this->set_setting_prop( 'thousand_separator', 'general', $separator );
	}

	/**
	 * Set option general decimal separator.
	*
	* @since 0.1.0
	* @param string $separator
	*/
	public function set_general_decimal_separator( $separator ) {
		$this->set_setting_prop( 'decimal_separator', 'general', $separator );
	}

	/**
	 * Set option general number of decimals.
	*
	* @since 0.1.0
	* @param int $number_of_decimals
	*/
	public function set_general_number_of_decimals( $number_of_decimals ) {
		$this->set_setting_prop( 'number_of_decimals', 'general', absint( $number_of_decimals ) );
	}

	// Courses Setting Setter.

	/**
	 * Set option courses placeholder image.
	*
	* @since 0.1.0
	* @param int $image
	*/
	public function set_courses_placeholder_image( $image ) {
		$this->set_setting_prop( 'placeholder_image', 'courses', absint( $image ) );
	}

	/**
	 * Set option courses add to cart behaviour.
	 *
	 * @since 0.1.0
	 * @param string $add_to_cart_behaviour
	 */
	public function set_courses_add_to_cart_behaviour( $add_to_cart_behaviour ) {
		$this->set_setting_prop( 'add_to_cart_behaviour', 'courses', $add_to_cart_behaviour );
	}

	/**
	 * Set option courses per page.
	*
	* @since 0.1.0
	* @param int $per_page
	*/
	public function set_courses_per_page( $per_page ) {
		$this->set_setting_prop( 'per_page', 'courses', absint( $per_page ) );
	}

	/**
	 * Set option courses per row.
	*
	* @since 0.1.0
	* @param int $per_row
	*/
	public function set_courses_per_row( $per_row ) {
		return $this->set_setting_prop( 'per_row', 'courses', absint( $per_row ) );
	}

	/**
	 * Set option courses enable editing.
	*
	* @since 0.1.0
	* @param boolean $enable
	*/
	public function set_courses_enable_editing( $enable ) {
		$this->set_setting_prop( 'enable_editing', 'courses', masteriyo_string_to_bool( $enable ) );
	}

	/**
	 * Set option courses category base.
	*
	* @since 0.1.0
	* @param string $base
	*/
	public function set_courses_category_base( $base ) {
		$this->set_setting_prop( 'category_base', 'courses', $base );
	}

	/**
	 * Set option courses tag base.
	*
	* @since 0.1.0
	* @param string $base
	*/
	public function set_courses_tag_base( $base ) {
		$this->set_setting_prop( 'tag_base', 'courses', $base );
	}

	/**
	 * Set option courses difficulty base.
	*
	* @since 0.1.0
	* @param string $base
	*/
	public function set_courses_difficulty_base( $base ) {
		$this->set_setting_prop( 'difficulty_base', 'courses', $base );
	}

	/**
	 * Set option courses single course permalink.
	*
	* @since 0.1.0
	* @param string $single_course_permalink
	*/
	public function set_courses_single_course_permalink( $permalink ) {
		$permalink = trim( $permalink );

		if ( $permalink ) {
			$course_base = preg_replace( '#/+#', '/', '/' . str_replace( '#', '', trim( wp_unslash( $permalink ) ) ) );
		} else {
			$course_base = '/';
		}

		// This is an invalid base structure and breaks pages.
		if ( '/%course_cat%/' === trailingslashit( $course_base ) ) {
			$course_base = '/' . _x( 'course', 'slug', 'masteriyo' ) . $course_base;
		}

		$this->set_setting_prop( 'single_course_permalink', 'courses', $permalink );
	}

	/**
	 * Set option courses single lesson permalink.
	*
	* @since 0.1.0
	* @param string $permalink
	*/
	public function set_courses_single_lesson_permalink( $permalink ) {
		$this->set_setting_prop( 'single_lesson_permalink', 'courses', $permalink );
	}

	/**
	 * Set option courses single quiz permalink.
	*
	* @since 0.1.0
	* @param string $permalink
	*/
	public function set_courses_single_quiz_permalink( $permalink ) {
		$this->set_setting_prop( 'single_quiz_permalink', 'courses', $permalink );
	}

	/**
	 * Set option courses single section permalink.
	*
	* @since 0.1.0
	* @param string $permalink
	*/
	public function set_courses_single_section_permalink( $permalink ) {
		$this->set_setting_prop( 'single_section_permalink', 'courses', $permalink );
	}

	/**
	 * Set option courses show thumbnail.
	*
	* @since 0.1.0
	* @param string $show
	*/
	public function set_courses_show_thumbnail( $show ) {
		$this->set_setting_prop( 'show_thumbnail', 'courses', masteriyo_string_to_bool( $show ) );
	}

	/**
	 * Set option courses thumbnail size.
	*
	* @since 0.1.0
	* @param string $size
	*/
	public function set_courses_thumbnail_size( $size ) {
		$this->set_setting_prop( 'thumbnail_size', 'courses', $size );
	}

	// Pages Setting Setter.

	/**
	 * Set option pages myaccount page id.
	*
	* @since 0.1.0
	* @param string $page_id
	*/
	public function set_pages_myaccount_page_id( $page_id ) {
		$this->set_setting_prop( 'myaccount_page_id', 'pages', absint( $page_id ) );
	}

	/**
	 * Set option pages course list page id.
	*
	* @since 0.1.0
	* @param string $page_id
	*/
	public function set_pages_course_list_page_id( $page_id ) {
		$this->set_setting_prop( 'course_list_page_id', 'pages', absint( $page_id ) );
	}

	/**
	 * Set option pages terms conditions page_id.
	*
	* @since 0.1.0
	* @param string $page_id
	*/
	public function set_pages_terms_conditions_page_id( $page_id ) {
		$this->set_setting_prop( 'terms_conditions_page_id', 'pages', absint( $page_id ) );
	}

	/**
	 * Set option pages checkout page_id.
	*
	* @since 0.1.0
	* @param string $page_id
	*/
	public function set_pages_checkout_page_id( $page_id ) {
		$this->set_setting_prop( 'checkout_page_id', 'pages', absint( $page_id ) );
	}

	// Checkout endpoints.

	/**
	 * Set option pages pay.
	 *
	 * @since 0.1.0
	 * @param string $pay
	 */
	public function set_pages_pay( $pay ) {
		$this->set_setting_prop( 'pay', 'pages', $pay );
	}

	/**
	 * Set option pages order received.
	 *
	 * @since 0.1.0
	 * @param string $order_received
	 */
	public function set_pages_order_received( $order_received ) {
		$this->set_setting_prop( 'order_received', 'pages', $order_received );
	}

	/**
	 * Set option pages add payment method.
	*
	* @since 0.1.0
	* @param string $payment_method
	*/
	public function set_pages_add_payment_method( $payment_method ) {
		$this->set_setting_prop( 'add_payment_method', 'pages', $payment_method );
	}

	/**
	 * Set option pages delete payment method.
	*
	* @since 0.1.0
	* @param string $payment_method
	*/
	public function set_pages_delete_payment_method( $payment_method ) {
		$this->set_setting_prop( 'delete_payment_method', 'pages', $payment_method );
	}

	/**
	 * Set option pages set default payment method.
	*
	* @since 0.1.0
	* @param string $payment_method
	*/
	public function set_pages_set_default_payment_method( $payment_method ) {
		$this->set_setting_prop( 'set_default_payment_method', 'pages', $payment_method );
	}

	// Account endpoints.

	/**
	 * Set option pages orders.
	 *
	 * @since 0.1.0
	 * @param string $orders
	 */
	public function set_pages_orders( $orders ) {
		$this->set_setting_prop( 'orders', 'pages', $orders );
	}

	/**
	 * Set option pages view order.
	 *
	 * @since 0.1.0
	 * @param string $view_order
	 */
	public function set_pages_view_order( $view_order ) {
		$this->set_setting_prop( 'view_order', 'pages', $view_order );
	}

	/**
	 * Set option pages my courses.
	 *
	 * @since 0.1.0
	 * @param string $my_courses
	 */
	public function set_pages_my_courses( $my_courses ) {
		$this->set_setting_prop( 'my_courses', 'pages', $my_courses );
	}

	/**
	 * Set option pages edit account.
	 *
	 * @since 0.1.0
	 * @param string $edit_account
	 */
	public function set_pages_edit_account( $edit_account ) {
		$this->set_setting_prop( 'edit_account', 'pages', $edit_account );
	}

	/**
	 * Set option pages payment methods.
	 *
	 * @since 0.1.0
	 * @param string $payment_methods
	 */
	public function set_pages_payment_methods( $payment_methods ) {
		$this->set_setting_prop( 'payment_methods', 'pages', $payment_methods );
	}

	/**
	 * Set option pages lost password.
	 *
	 * @since 0.1.0
	 * @param string $lost_password
	 */
	public function set_pages_lost_password( $lost_password ) {
		$this->set_setting_prop( 'lost_password', 'pages', $lost_password );
	}

	/**
	 * Set option pages logout.
	 *
	 * @since 0.1.0
	 * @param string $logout
	 */
	public function set_pages_logout( $logout ) {
		$this->set_setting_prop( 'logout', 'pages', $logout );
	}

	// Quizzes Setting Setter.

	/**
	 * Set option quizzes time limit.
	 *
	 * @since 0.1.0
	 * @param string $time_limit
	 */
	public function set_quizzes_time_limit( $time_limit ) {
		return $this->set_setting_prop( 'time_limit', 'quizzes', absint( $time_limit ) );
	}

	/**
	 * Set option quizzes attempts allowed.
	 *
	 * @since 0.1.0
	 * @param string $attempts_allowed
	 */
	public function set_quizzes_attempts_allowed( $attempts_allowed ) {
		return $this->set_setting_prop( 'attempts_allowed', 'quizzes', absint( $attempts_allowed ) );
	}

	// Payments Setting Setter.

	// Offline

	/**
	 * Set the enable/disable offline payment.
	 *
	 * @since 0.1.0
	 *
	 * @param boolean $enable
	 */
	public function set_payments_offline_enable( $enable ) {
		$this->set_setting_prop( 'offline_enable', 'payments', masteriyo_string_to_bool( $enable ) );
	}

	/**
	 * Set offline payment title.
	 *
	 * @since 0.1.0
	 *
	 * @param string $title
	 */
	public function set_payments_offline_title( $title ) {
		$this->set_setting_prop( 'offline_title', 'payments', $title );
	}

	/**
	 * Set offline payment description.
	 *
	 * @since 0.1.0
	 *
	 * @param string $description
	 */
	public function set_payments_offline_description( $description ) {
		$this->set_setting_prop( 'offline_description', 'payments', $description );
	}

	/**
	 * set offline payment instructions.
	 *
	 * @since 0.1.0
	 *
	 * @param string $instructions
	 */
	public function set_payments_offline_instructions( $instructions ) {
		$this->set_setting_prop( 'offline_instructions', 'payments', $instructions );
	}

	// Paypal.

	/**
	 * Set option payments paypal enable.
	*
	* @since 0.1.0
	* @param boolean $enable
	*/
	public function set_payments_paypal_enable( $enable ) {
		$this->set_setting_prop( 'paypal_enable', 'payments', masteriyo_string_to_bool( $enable ) );
	}

	/**
	 * Set paypal title.
	*
	* @since 0.1.0
	* @param boolean $title
	*/
	public function set_payments_paypal_title( $title ) {
		$this->set_setting_prop( 'paypal_title', 'payments', $title );
	}

	/**
	 * Set paypal description.
	*
	* @since 0.1.0
	* @param boolean $description
	*/
	public function set_payments_paypal_description( $description ) {
		$this->set_setting_prop( 'paypal_description', 'payments', $description );
	}

	/**
	 * Set paypal enable ipn email notifications.
	*
	* @since 0.1.0
	* @param boolean $enable
	*/
	public function set_payments_paypal_ipn_email_notifications( $enable ) {
		$this->set_setting_prop( 'paypal_ipn_email_notifications', 'payments', masteriyo_string_to_bool( $enable ) );
	}

	/**
	 * Set option payments paypal sandbox.
	*
	* @since 0.1.0
	* @param boolean $enable
	*/
	public function set_payments_paypal_sandbox( $enable ) {
		$this->set_setting_prop( 'paypal_sandbox', 'payments', masteriyo_string_to_bool( $enable ) );
	}

	/**
	 * Set option payments paypal email.
	*
	* @since 0.1.0
	* @param string $email
	*/
	public function set_payments_paypal_email( $email ) {
		$this->set_setting_prop( 'paypal_email', 'payments', trim( $email ) );
	}

	/**
	 * Set option payments paypal receiver email.
	*
	* @since 0.1.0
	* @param string $email
	*/
	public function set_payments_paypal_receiver_email( $email ) {
		$this->set_setting_prop( 'paypal_receiver_email', 'payments', trim( $email ) );
	}

	/**
	 * Set option payments paypal identity token.
	*
	* @since 0.1.0
	* @param string $token
	*/
	public function set_payments_paypal_identity_token( $token ) {
		$this->set_setting_prop( 'paypal_identity_token', 'payments', trim( $token ) );
	}

	/**
	 * Set option payments paypal invoice prefix.
	*
	* @since 0.1.0
	* @param string $invoice_prefix
	*/
	public function set_payments_paypal_invoice_prefix( $invoice_prefix ) {
		$this->set_setting_prop( 'paypal_invoice_prefix', 'payments', trim( $invoice_prefix ) );
	}

	/**
	 * Set option payments paypal payment action.
	*
	* @since 0.1.0
	* @param string $action
	*/
	public function set_payments_paypal_payment_action( $action ) {
		$this->set_setting_prop( 'paypal_payment_action', 'payments', trim( $action ) );
	}

	/**
	 * Set option payments paypal image url.
	*
	* @since 0.1.0
	* @param string $url
	*/
	public function set_payments_paypal_image_url( $url ) {
		$this->set_setting_prop( 'paypal_image_url', 'payments', trim( $url ) );
	}

	/**
	 * Set paypal enable log.
	*
	* @since 0.1.0
	* @param string $enable
	*/
	public function set_payments_paypal_debug( $enable ) {
		$this->set_setting_prop( 'paypal_debug', 'payments', masteriyo_string_to_bool( $enable ) );
	}

	/**
	 * Set paypal sandbox api username.
	*
	* @since 0.1.0
	* @param string $username
	*/
	public function set_payments_paypal_sandbox_api_username( $username ) {
		$this->set_setting_prop( 'paypal_sandbox_api_username', 'payments', $username );
	}

	/**
	 * Set paypal sandbox api password.
	*
	* @since 0.1.0
	* @param string $password
	*/
	public function set_payments_paypal_sandbox_api_password( $password ) {
		$this->set_setting_prop( 'paypal_sandbox_api_password', 'payments', $password );
	}

	/**
	 * Set paypal sandbox api signature.
	*
	* @since 0.1.0
	* @param string $siognature
	*/
	public function set_payments_paypal_sandbox_api_signature( $signature ) {
		$this->set_setting_prop( 'paypal_sandbox_api_signature', 'payments', $signature );
	}

	/**
	 * Set paypal live api username.
	*
	* @since 0.1.0
	* @param string $username
	*/
	public function set_payments_paypal_live_api_username( $username ) {
		$this->set_setting_prop( 'paypal_live_api_username', 'payments', $username );
	}

	/**
	 * Set paypal live api password.
	*
	* @since 0.1.0
	* @param string $password
	*/
	public function set_payments_paypal_live_api_password( $password ) {
		$this->set_setting_prop( 'paypal_live_api_password', 'payments', $password );
	}

	/**
	 * Set paypal live api signature.
	*
	* @since 0.1.0
	* @param string $siognature
	*/
	public function set_payments_paypal_live_api_signature( $signature ) {
		$this->set_setting_prop( 'paypal_live_api_signature', 'payments', $signature );
	}

	// Email Setting Setter.

	// General.

	/**
	 * Set general from name.
	*
	* @since 0.1.0
	* @param string $name
	*/
	public function set_emails_general_from_name( $name ) {
		$this->set_setting_prop( 'general_from_name', 'emails', $name );
	}

	/**
	 * Set general from email.
	*
	* @since 0.1.0
	* @param string $email
	*/
	public function set_emails_general_from_email( $email ) {
		$this->set_setting_prop( 'general_from_email', 'emails', trim( $email ) );
	}

	/**
	 * Set general email default content.
	*
	* @since 0.1.0
	* @param string $content
	*/
	public function set_emails_general_default_content( $content ) {
		$this->set_setting_prop( 'general_default_content', 'emails', $content );
	}

	/**
	 * Set general email header image.
	*
	* @since 0.1.0
	* @param string $image
	*/
	public function set_emails_general_header_image( $image ) {
		$this->set_setting_prop( 'general_header_image', 'emails', $image );
	}

	/**
	 * Set general email footer text.
	 *
	 * @since 0.1.0
	 * @param string $footer
	 */
	public function set_emails_general_footer_text( $footer ) {
		$this->set_setting_prop( 'general_footer_text', 'emails', $footer );
	}

	// New order.

	/**
	 * Enable/Disable new order email.
	 *
	 * @since 0.1.0
	 * @param boolean $enable
	 */
	public function set_emails_new_order_enable( $enable ) {
		$this->set_setting_prop( 'new_order_enable', 'emails', masteriyo_string_to_bool( $enable ) );
	}

	/**
	 * Set new order recipients.
	 *
	 * @since 0.1.0
	 * @param string[] $recipients
	 */
	public function set_emails_new_order_recipients( $recipients ) {
		$this->set_setting_prop( 'new_order_recipients', 'emails', maybe_unserialize( $recipients ) );
	}

	/**
	 * Set new order email subject.
	 *
	 * @since 0.1.0
	 * @param string $subject
	 */
	public function set_emails_new_order_subject( $subject ) {
		$this->set_setting_prop( 'new_order_subject', 'emails', $subject );
	}

	/**
	 * Set new order email heading.
	 *
	 * @since 0.1.0
	 * @param string $heading
	 */
	public function set_emails_new_order_heading( $heading ) {
		$this->set_setting_prop( 'new_order_heading', 'emails', $heading );
	}

	/**
	 * Set new order email content.
	 *
	 * @since 0.1.0
	 * @param string $content
	 */
	public function set_emails_new_order_content( $content ) {
		$this->set_setting_prop( 'new_order_content', 'emails', $content );
	}

	// Processing order.

	/**
	 * Enable/Disable order processing email.
	 *
	 * @since 0.1.0
	 * @param boolean $enable
	 */
	public function set_emails_processing_order_enable( $enable ) {
		$this->set_setting_prop( 'processing_order_enable', 'emails', masteriyo_string_to_bool( $enable ) );
	}

	/**
	 * Set order processing email subject.
	 *
	 * @since 0.1.0
	 * @param string $subject
	 */
	public function set_emails_processing_order_subject( $subject ) {
		$this->set_setting_prop( 'processing_order_subject', 'emails', $subject );
	}

	/**
	 * Set order processing email heading.
	 *
	 * @since 0.1.0
	 * @param string $heading
	 */
	public function set_emails_processing_order_heading( $heading ) {
		$this->set_setting_prop( 'processing_order_heading', 'emails', $heading );
	}

	/**
	 * Set order processing email content.
	 *
	 * @since 0.1.0
	 * @param string $content
	 */
	public function set_emails_processing_order_content( $content ) {
		$this->set_setting_prop( 'processing_order_content', 'emails', $content );
	}

	// Completed order.

	/**
	 * Enable/Disable order completed email.
	 *
	 * @since 0.1.0
	 * @param boolean $enable
	 */
	public function set_emails_completed_order_enable( $enable ) {
		$this->set_setting_prop( 'completed_order_enable', 'emails', masteriyo_string_to_bool( $enable ) );
	}

	/**
	 * Set order completed email subject.
	 *
	 * @since 0.1.0
	 * @param string $subject
	 */
	public function set_emails_completed_order_subject( $subject ) {
		$this->set_setting_prop( 'completed_order_subject', 'emails', $subject );
	}

	/**
	 * Set order completed email heading.
	 *
	 * @since 0.1.0
	 * @param string $heading
	 */
	public function set_emails_completed_order_heading( $heading ) {
		$this->set_setting_prop( 'completed_order_heading', 'emails', $heading );
	}

	/**
	 * Set order completed email content.
	 *
	 * @since 0.1.0
	 * @param string $content
	 */
	public function set_emails_completed_order_content( $content ) {
		$this->set_setting_prop( 'completed_order_content', 'emails', $content );
	}

	// On Hold Order.

	/**
	 * Enable/Dsiable order onhold email.
	 *
	 * @since 0.1.0
	 * @param boolean $enable
	 */
	public function set_emails_onhold_order_enable( $enable ) {
		$this->set_setting_prop( 'onhold_order_enable', 'emails', masteriyo_string_to_bool( $enable ) );
	}

	/**
	 * Set order onhold email subject.
	 *
	 * @since 0.1.0
	 * @param string $subject
	 */
	public function set_emails_onhold_order_subject( $subject ) {
		$this->set_setting_prop( 'onhold_order_subject', 'emails', $subject );
	}

	/**
	 * Set order onhold email heading
	 *
	 * @since 0.1.0
	 * @param string $heading
	 */
	public function set_emails_onhold_order_heading( $heading ) {
		$this->set_setting_prop( 'onhold_order_heading', 'emails', $heading );
	}

	/**
	 * Set order onhold email content.
	 *
	 * @since 0.1.0
	 * @param string $content
	 */
	public function set_emails_onhold_order_content( $content ) {
		$this->set_setting_prop( 'onhold_order_content', 'emails', $content );
	}

	// Cancelled Order.

	/**
	 * Enable/Dsiable order cancelled email
	 *
	 * @since 0.1.0
	 * @param boolean $enable
	 */
	public function set_emails_cancelled_order_enable( $enable ) {
		$this->set_setting_prop( 'cancelled_order_enable', 'emails', masteriyo_string_to_bool( $enable ) );
	}

	/**
	 * Set order cancelled email recipient.
	 *
	 * @since 0.1.0
	 * @param string[] $recipients
	 */
	public function set_emails_cancelled_order_recipients( $recipients ) {
		$this->set_setting_prop( 'cancelled_order_recipients', 'emails', maybe_unserialize( $recipients ) );
	}

	/**
	 * Set order cancelled email subject.
	 *
	 * @since 0.1.0
	 * @param string $subject
	 */
	public function set_emails_cancelled_order_subject( $subject ) {
		$this->set_setting_prop( 'cancelled_order_subject', 'emails', $subject );
	}

	/**
	 * Set order cancelled email heading.
	 *
	 * @since 0.1.0
	 * @param string $heading
	 */
	public function set_emails_cancelled_order_heading( $heading ) {
		$this->set_setting_prop( 'cancelled_order_heading', 'emails', $heading );
	}

	/**
	 * Set order cancelled email content.
	 *
	 * @since 0.1.0
	 * @param string $content
	 */
	public function set_emails_cancelled_order_content( $content ) {
		$this->set_setting_prop( 'cancelled_order_content', 'emails', $content );
	}

	// Enrolled Course.

	/**
	 * Enable/Disable course enrolled email.
	 *
	 * @since 0.1.0
	 * @param boolean $enable
	 */
	public function set_emails_enrolled_course_enable( $enable ) {
		$this->set_setting_prop( 'enrolled_course_enable', 'emails', masteriyo_string_to_bool( $enable ) );
	}

	/**
	 * Set course enrolled email subject.
	 *
	 * @since 0.1.0
	 * @param string $subject
	 */
	public function set_emails_enrolled_course_subject( $subject ) {
		$this->set_setting_prop( 'enrolled_course_subject', 'emails', $subject );
	}

	/**
	 * Set course enrolled email heading.
	 *
	 * @since 0.1.0
	 * @param string $heading
	 */
	public function set_emails_enrolled_course_heading( $heading ) {
		$this->set_setting_prop( 'enrolled_course_heading', 'emails', $heading );
	}

	/**
	 * Set course enrolled email content.
	 *
	 * @since 0.1.0
	 * @param string $content
	 */
	public function set_emails_enrolled_course_content( $content ) {
		$this->set_setting_prop( 'enrolled_course_content', 'emails', $content );
	}

	// Completed Course.

	/**
	 * Enable/Disable course completed email.
	 *
	 * @since 0.1.0
	 * @param boolean $enable
	 */
	public function set_emails_completed_course_enable( $enable ) {
		$this->set_setting_prop( 'completed_course_enable', 'emails', masteriyo_string_to_bool( $enable ) );
	}

	/**
	 * Set completed course email subject.
	 *
	 * @since 0.1.0
	 * @param string $subject
	 */
	public function set_emails_completed_course_subject( $subject ) {
		$this->set_setting_prop( 'completed_course_subject', 'emails', $subject );
	}

	/**
	 * Set completed course email heading.
	 *
	 * @since 0.1.0
	 * @param string $heading
	 */
	public function set_emails_completed_course_heading( $heading ) {
		$this->set_setting_prop( 'completed_course_heading', 'emails', $heading );
	}

	/**
	 * Set emails completed course content.
	 *
	 * @since 0.1.0
	 * @param string $content
	 */
	public function set_emails_completed_course_content( $content ) {
		$this->set_setting_prop( 'completed_course_content', 'emails', $content );
	}

	// Become An Instructor.

	/**
	 * Enable/disable becone an instructor email.
	 *
	 * @since 0.1.0
	 * @param boolean $enable
	 */
	public function set_emails_become_an_instructor_enable( $enable ) {
		$this->set_setting_prop( 'become_an_instructor_enable', 'emails', masteriyo_string_to_bool( $enable ) );
	}

	/**
	 * Set become an instructor email subject.
	 *
	 * @since 0.1.0
	 * @param string $subject
	 */
	public function set_emails_become_an_instructor_subject( $subject ) {
		$this->set_setting_prop( 'become_an_instructor_subject', 'emails', $subject );
	}

	/**
	 * Set become an insctuctor email heading.
	 *
	 * @since 0.1.0
	 * @param string $heading
	 */
	public function set_emails_become_an_instructor_heading( $heading ) {
		$this->set_setting_prop( 'become_an_instructor_heading', 'emails', $heading );
	}

	/**
	 * Set become an instructor email content.
	 *
	 * @since 0.1.0
	 * @param string $content
	 */
	public function set_emails_become_an_instructor_content( $content ) {
		$this->set_setting_prop( 'become_an_instructor_content', 'emails', $content );
	}

	// Advance Setting.

	// Debug.

	/**
	 * Enable/Disable template debug mode.
	*
	* @since 0.1.0
	* @param boolean $enable
	*/
	public function set_advance_template_debug( $enable ) {
		$this->set_setting_prop( 'template_debug', 'advance', masteriyo_string_to_bool( $enable ) );
	}

	/**
	 * Enable/Disable debug mode.
	*
	* @since 0.1.0
	* @param string $enable
	*/
	public function set_advance_debug( $enable ) {
		$this->set_setting_prop( 'debug', 'advance', masteriyo_string_to_bool( $enable ) );
	}

	/**
	 * Set advance styles mode.
	*
	* @since 0.1.0
	* @param string $style
	*/
	public function set_advance_style( $style ) {
		$this->set_setting_prop( 'style', 'advance', $style );
	}

	/**
	 * Sets a prop for a setter method.
	 *
	 * @since 0.1.0
	 * @param string $prop    Name of prop to set.
	 * @param string $group Name of setting group.
	 * @param mixed  $value   Value of the prop.
	 */
	protected function set_setting_prop( $prop, $group, $value ) {
		if ( array_key_exists( $prop, $this->data[ $group ] ) ) {
			if ( true === $this->object_read ) {
				if ( $value !== $this->data[ $group ][ $prop ] || ( isset( $this->changes[ $group ] ) && array_key_exists( $prop, $this->changes[ $group ] ) ) ) {
					$this->changes[ $group ][ $prop ] = $value;
				}
			} else {
				$this->data[ $group ][ $prop ] = $value;
			}
		}
	}

	/**
	 * Gets a prop for a getter method.
	 *
	 * @since  0.1.0
	 * @param  string $prop Name of prop to get.
	 * @param  string $group Setting group.
	 * @param  string $context What the value is for. Valid values are 'view' and 'edit'. What the value is for. Valid values are view and edit.
	 * @return mixed
	 */
	protected function get_setting_prop( $prop, $group = 'general', $context = 'view' ) {
		$value = null;

		if ( array_key_exists( $prop, $this->data[ $group ] ) ) {
			$value = isset( $this->changes[ $group ][ $prop ] ) ? $this->changes[ $group ][ $prop ] : $this->data[ $group ][ $prop ];

			if ( 'view' === $context ) {
				$value = apply_filters( $this->get_hook_prefix() . $group . '_' . $prop, $value, $this );
			}
		}
		return $value;
	}
}
