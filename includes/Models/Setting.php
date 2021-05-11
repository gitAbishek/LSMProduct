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

		/** General Setting */

		// Store.
		'general.address_line1'                  => '',
		'general.address_line2'                  => '',
		'general.city'                           => '',
		'general.country'                        => '',
		'general.postcode'                       => '',

		// Currency.
		'general.currency'                       => 'USD',
		'general.currency_position'              => 'left',
		'general.thousand_separator'             => ',',
		'general.decimal_separator'              => '.',
		'general.number_of_decimals'             => 3,

		/** Courses Setting */

		// General.
		'courses.placeholder_image'              => '',
		'courses.add_to_cart_behaviour'          => '',
		'courses.per_page'                       => '',
		'courses.enable_editing'                 => false,

		// Single Course.
		'courses.category_base'                  => '',
		'courses.tag_base'                       => '',
		'courses.difficulty_base'                => '',
		'courses.single_course_permalink'        => '',
		'courses.single_lesson_permalink'        => '',
		'courses.single_quiz_permalink'          => '',
		'courses.single_section_permalink'       => '',
		'courses.enable_single_course_permalink' => '',
		'courses.single_course_enable_editing'   => '',

		// Course Thumbnail.
		'courses.show_thumbnail'                 => '',
		'courses.thumbnail_size'                 => '',

		/** Pages Setting */

		// Page Setup.
		'pages.myaccount_page_id'                => '',
		'pages.course_list_page_id'              => '',
		'pages.terms_conditions_page_id'         => '',
		'pages.checkout_page_id'                 => '',

		// Checkout Endpoints.
		'pages.pay'                              => '',
		'pages.order_received'                   => '',
		'pages.add_payment_method'               => '',
		'pages.delete_payment_method'            => '',
		'pages.set_default_payment_method'       => '',

		// Account Endpoints.
		'pages.orders'                           => '',
		'pages.view_order'                       => '',
		'pages.my_courses'                       => '',
		'pages.edit_account'                     => '',
		'pages.payment_methods'                  => '',
		'pages.lost_password'                    => '',
		'pages.logout'                           => '',

		/** Payments Settings. */

		// Standard Paypal
		'payments.paypal_enable'                 => false,
		'payments.paypal_production_email'       => '',
		'payments.paypal_sandbox_enable'         => false,
		'payments.paypal_sandbox_email'          => '',

		/** Emails Setting */

		// General->Email Options.
		'emails.general_from_name'               => '',
		'emails.general_from_email'              => '',

		// General->Email Templates.
		'emails.general_default_content'         => '',
		'emails.general_header_image'            => '',
		'emails.general_footer_text'             => '',

		//New Order.
		'emails.new_order_enable'                => false,
		'emails.new_order_recipients'            => array(),
		'emails.new_order_subject'               => '',
		'emails.new_order_heading'               => '',
		'emails.new_order_content'               => '',

		// Processing Order.
		'emails.processing_order_enable'         => false,
		'emails.processing_order_subject'        => '',
		'emails.processing_order_heading'        => '',
		'emails.processing_order_content'        => '',

		// Completed Order.
		'emails.completed_order_enable'          => false,
		'emails.completed_order_subject'         => '',
		'emails.completed_order_heading'         => '',
		'emails.completed_order_content'         => '',

		// On Hold Order.
		'emails.onhold_order_enable'             => false,
		'emails.onhold_order_subject'            => '',
		'emails.onhold_order_heading'            => '',
		'emails.onhold_order_content'            => '',

		// Cancelled Order.
		'emails.cancelled_order_enable'          => false,
		'emails.cancelled_order_recipients'      => array(),
		'emails.cancelled_order_subject'         => '',
		'emails.cancelled_order_heading'         => '',
		'emails.cancelled_order_content'         => '',

		// Enrolled Course.
		'emails.enrolled_course_enable'          => false,
		'emails.enrolled_course_subject'         => '',
		'emails.enrolled_course_heading'         => '',
		'emails.enrolled_course_content'         => '',

		// Completed Course.
		'emails.completed_course_enable'         => false,
		'emails.completed_course_subject'        => '',
		'emails.completed_course_heading'        => '',
		'emails.completed_course_content'        => '',

		// Become An Instructor.
		'emails.become_an_instructor_enable'     => false,
		'emails.become_an_instructor_subject'    => '',
		'emails.become_an_instructor_heading'    => '',
		'emails.become_an_instructor_content'    => '',

		/** Advanced Setting */

		// Debug.
		'advanced.template_debug_enable'         => false,
		'advanced.debug_enable'                  => false,
		'advanced.styles_mode'                   => '',
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
	 * @return array $data Default datas.
	 */
	public function get_default_datas() {
		return $this->data;
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
		return $this->get_prop( 'general.address_line1', $context );
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
		return $this->get_prop( 'general.address_line2', $context );
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
		return $this->get_prop( 'general.city', $context );
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
		return $this->get_prop( 'general.country', $context );
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
		return $this->get_prop( 'general.postcode', $context );
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
		return $this->get_prop( 'general.currency', $context );
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
		return $this->get_prop( 'general.currency_position', $context );
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
		return $this->get_prop( 'general.thousand_separator', $context );
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
		return $this->get_prop( 'general.decimal_separator', $context );
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
		return $this->get_prop( 'general.number_of_decimals', $context );
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
		return $this->get_prop( 'courses.placeholder_image', $context );
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
		return $this->get_prop( 'courses.add_to_cart_behaviour', $context );
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
		return $this->get_prop( 'courses.per_page', $context );
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
		return $this->get_prop( 'courses.enable_editing', $context );
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
		return $this->get_prop( 'courses.category_base', $context );
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
		return $this->get_prop( 'courses.tag_base', $context );
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
		return $this->get_prop( 'courses.difficulty_base', $context );
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
		return $this->get_prop( 'courses.single_course_permalink', $context );
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
		return $this->get_prop( 'courses.single_lesson_permalink', $context );
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
		return $this->get_prop( 'courses.single_quiz_permalink', $context );
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
		return $this->get_prop( 'courses.single_section_permalink', $context );
	}

	/**
	 * Get option courses_enable_single_course_permalink.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_courses_enable_single_course_permalink( $context = 'view' ) {
		return $this->get_prop( 'courses.enable_single_course_permalink', $context );
	}

	/**
	 * Get option courses_single_course_enable_editing.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_courses_single_course_enable_editing( $context = 'view' ) {
		return $this->get_prop( 'courses.single_course_enable_editing', $context );
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
		return $this->get_prop( 'courses.show_thumbnail', $context );
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
		return $this->get_prop( 'courses.thumbnail_size', $context );
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
		return $this->get_prop( 'pages.myaccount_page_id', $context );
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
		return $this->get_prop( 'pages.course_list_page_id', $context );
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
		return $this->get_prop( 'pages.terms_conditions_page_id', $context );
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
		return $this->get_prop( 'pages.checkout_page_id', $context );
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
		return $this->get_prop( 'pages.pay', $context );
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
		return $this->get_prop( 'pages.order_received', $context );
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
		return $this->get_prop( 'pages.add_payment_method', $context );
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
		return $this->get_prop( 'pages.delete_payment_method', $context );
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
		return $this->get_prop( 'pages.set_default_payment_method', $context );
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
		return $this->get_prop( 'pages.orders', $context );
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
		return $this->get_prop( 'pages.view_order', $context );
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
		return $this->get_prop( 'pages.my_courses', $context );
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
		return $this->get_prop( 'pages.edit_account', $context );
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
		return $this->get_prop( 'pages.payment_methods', $context );
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
		return $this->get_prop( 'pages.lost_password', $context );
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
		return $this->get_prop( 'pages.logout', $context );
	}

	// Payments Setting Getter.

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
		return $this->get_prop( 'payments.paypal_enable', $context );
	}

	/**
	 * Get option payments_paypal_production_email.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_payments_paypal_production_email( $context = 'view' ) {
		return $this->get_prop( 'payments.paypal_production_email', $context );
	}

	/**
	 * Get option payments_paypal_sandbox_enable.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_payments_paypal_sandbox_enable( $context = 'view' ) {
		return $this->get_prop( 'payments.paypal_sandbox_enable', $context );
	}

	/**
	 * Get option payments_paypal_sandbox_email.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_payments_paypal_sandbox_email( $context = 'view' ) {
		return $this->get_prop( 'payments.paypal_sandbox_email', $context );
	}


	// Email Setting Getter.

	// General.

	/**
	 * Get option emails_general_from_name.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_general_from_name( $context = 'view' ) {
		return $this->get_prop( 'emails.general_from_name', $context );
	}

	/**
	 * Get option emails_general_from_email.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_general_from_email( $context = 'view' ) {
		return $this->get_prop( 'emails.general_from_email', $context );
	}

	/**
	 * Get option emails_general_default_content.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_general_default_content( $context = 'view' ) {
		return $this->get_prop( 'emails.general_default_content', $context );
	}

	/**
	 * Get option emails_general_header_image.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_general_header_image( $context = 'view' ) {
		return $this->get_prop( 'emails.general_header_image', $context );
	}

	/**
	 * Get option emails_general_footer_text.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_emails_general_footer_text( $context = 'view' ) {
		return $this->get_prop( 'emails.general_footer_text', $context );
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
		return $this->get_prop( 'emails.new_order_enable', $context );
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
		return $this->get_prop( 'emails.new_order_recipients', $context );
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
		return $this->get_prop( 'emails.new_order_subject', $context );
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
		return $this->get_prop( 'emails.new_order_heading', $context );
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
		return $this->get_prop( 'emails.new_order_content', $context );
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
		return $this->get_prop( 'emails.processing_order_enable', $context );
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
		return $this->get_prop( 'emails.processing_order_subject', $context );
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
		return $this->get_prop( 'emails.processing_order_heading', $context );
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
		return $this->get_prop( 'emails.processing_order_content', $context );
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
		return $this->get_prop( 'emails.completed_order_enable', $context );
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
		return $this->get_prop( 'emails.completed_order_subject', $context );
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
		return $this->get_prop( 'emails.completed_order_heading', $context );
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
		return $this->get_prop( 'emails.completed_order_content', $context );
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
		return $this->get_prop( 'emails.onhold_order_enable', $context );
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
		return $this->get_prop( 'emails.onhold_order_subject', $context );
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
		return $this->get_prop( 'emails.onhold_order_heading', $context );
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
		return $this->get_prop( 'emails.onhold_order_content', $context );
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
		return $this->get_prop( 'emails.cancelled_order_enable', $context );
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
		return $this->get_prop( 'emails.cancelled_order_recipients', $context );
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
		return $this->get_prop( 'emails.cancelled_order_subject', $context );
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
		return $this->get_prop( 'emails.cancelled_order_heading', $context );
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
		return $this->get_prop( 'emails.cancelled_order_content', $context );
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
		return $this->get_prop( 'emails.enrolled_course_enable', $context );
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
		return $this->get_prop( 'emails.enrolled_course_subject', $context );
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
		return $this->get_prop( 'emails.enrolled_course_heading', $context );
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
		return $this->get_prop( 'emails.enrolled_course_content', $context );
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
		return $this->get_prop( 'emails.completed_course_enable', $context );
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
		return $this->get_prop( 'emails.completed_course_subject', $context );
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
		return $this->get_prop( 'emails.completed_course_heading', $context );
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
		return $this->get_prop( 'emails.completed_course_content', $context );
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
		return $this->get_prop( 'emails.become_an_instructor_enable', $context );
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
		return $this->get_prop( 'emails.become_an_instructor_subject', $context );
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
		return $this->get_prop( 'emails.become_an_instructor_heading', $context );
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
		return $this->get_prop( 'emails.become_an_instructor_content', $context );
	}

	// Advanced Setting.

	// Debug.

	/**
	 * Get option advanced_template_debug_enable.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_advanced_template_debug_enable( $context = 'view' ) {
		return $this->get_prop( 'advanced.template_debug_enable', $context );
	}

	/**
	 * Get option advanced_debug_enable.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_advanced_debug_enable( $context = 'view' ) {
		return $this->get_prop( 'advanced.debug_enable', $context );
	}

	/**
	 * Get option advanced_styles_mode.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_advanced_styles_mode( $context = 'view' ) {
		return $this->get_prop( 'advanced.styles_mode', $context );
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
		return $this->set_prop( 'general.address_line1', $address_line1 );
	}

	/**
	 * Set option general address line2.
	*
	* @since 0.1.0
	* @param string $address_line2
	*/
	public function set_general_address_line2( $address_line2 ) {
		return $this->set_prop( 'general.address_line2', $address_line2 );
	}

	/**
	 * Set option general city.
	*
	* @since 0.1.0
	* @param string $city
	*/
	public function set_general_city( $city ) {
		return $this->set_prop( 'general.city', $city );
	}

	/**
	 * Set option general country.
	*
	* @since 0.1.0
	* @param string $country
	*/
	public function set_general_country( $country ) {
		return $this->set_prop( 'general.country', $country );
	}

	/**
	 * Set option general postcode.
	*
	* @since 0.1.0
	* @param string $postcode
	*/
	public function set_general_postcode( $postcode ) {
		return $this->set_prop( 'general.postcode', $postcode );
	}

	/**
	 * Set option general currency.
	*
	* @since 0.1.0
	* @param string $currency
	*/
	public function set_general_currency( $currency ) {
		return $this->set_prop( 'general.currency', $currency );
	}

	/**
	 * Set option general currency position.
	*
	* @since 0.1.0
	* @param string $currency_position
	*/
	public function set_general_currency_position( $currency_position ) {
		return $this->set_prop( 'general.currency_position', $currency_position );
	}

	/**
	 * Set option general thousand separator.
	*
	* @since 0.1.0
	* @param string $thousand_separator
	*/
	public function set_general_thousand_separator( $thousand_separator ) {
		return $this->set_prop( 'general.thousand_separator', $thousand_separator );
	}

	/**
	 * Set option general decimal separator.
	*
	* @since 0.1.0
	* @param string $decimal_separator
	*/
	public function set_general_decimal_separator( $decimal_separator ) {
		return $this->set_prop( 'general.decimal_separator', $decimal_separator );
	}

	/**
	 * Set option general number of decimals.
	*
	* @since 0.1.0
	* @param string $number_of_decimals
	*/
	public function set_general_number_of_decimals( $number_of_decimals ) {
		return $this->set_prop( 'general.number_of_decimals', absint( $number_of_decimals ) );
	}

	// Courses Setting Setter.

	/**
	 * Set option courses placeholder image.
	*
	* @since 0.1.0
	* @param string $placeholder_image
	*/
	public function set_courses_placeholder_image( $placeholder_image ) {
		return $this->set_prop( 'courses.placeholder_image', absint( $placeholder_image ) );
	}

	/**
	 * Set option courses add to cart behaviour.
	*
	* @since 0.1.0
	* @param string $add_to_cart_behaviour
	*/
	public function set_courses_add_to_cart_behaviour( $add_to_cart_behaviour ) {
		return $this->set_prop( 'courses.add_to_cart_behaviour', $add_to_cart_behaviour );
	}

	/**
	 * Set option courses per page.
	*
	* @since 0.1.0
	* @param string $per_page
	*/
	public function set_courses_per_page( $per_page ) {
		return $this->set_prop( 'courses.per_page', absint( $per_page ) );
	}

	/**
	 * Set option courses enable editing.
	*
	* @since 0.1.0
	* @param boolean $enable_editing
	*/
	public function set_courses_enable_editing( $enable_editing ) {
		return $this->set_prop( 'courses.enable_editing', masteriyo_string_to_bool( $enable_editing ) );
	}

	/**
	 * Set option courses category base.
	*
	* @since 0.1.0
	* @param string $category_base
	*/
	public function set_courses_category_base( $category_base ) {
		return $this->set_prop( 'courses.category_base', masteriyo_sanitize_permalink( $category_base ) );
	}

	/**
	 * Set option courses tag base.
	*
	* @since 0.1.0
	* @param string $tag_base
	*/
	public function set_courses_tag_base( $tag_base ) {
		return $this->set_prop( 'courses.tag_base', masteriyo_sanitize_permalink( $tag_base ) );
	}

	/**
	 * Set option courses difficulty base.
	*
	* @since 0.1.0
	* @param string $difficulty_base
	*/
	public function set_courses_difficulty_base( $difficulty_base ) {
		return $this->set_prop( 'courses.difficulty_base', masteriyo_sanitize_permalink( $difficulty_base ) );
	}

	/**
	 * Set option courses single course permalink.
	*
	* @since 0.1.0
	* @param string $single_course_permalink
	*/
	public function set_courses_single_course_permalink( $single_course_permalink ) {

		if ( $single_course_permalink ) {
			$course_base = preg_replace( '#/+#', '/', '/' . str_replace( '#', '', trim( wp_unslash( $single_course_permalink ) ) ) );
		} else {
			$course_base = '/';
		}

		// This is an invalid base structure and breaks pages.
		if ( '/%course_cat%/' === trailingslashit( $course_base ) ) {
			$course_base = '/' . _x( 'course', 'slug', 'masteriyo' ) . $course_base;
		}

		return $this->set_prop( 'courses.single_course_permalink', masteriyo_sanitize_permalink( $course_base ) );
	}

	/**
	 * Set option courses single lesson permalink.
	*
	* @since 0.1.0
	* @param string $single_lesson_permalink
	*/
	public function set_courses_single_lesson_permalink( $single_lesson_permalink ) {
		return $this->set_prop( 'courses.single_lesson_permalink', masteriyo_sanitize_permalink( $single_lesson_permalink ) );
	}

	/**
	 * Set option courses single quiz permalink.
	*
	* @since 0.1.0
	* @param string $single_quiz_permalink
	*/
	public function set_courses_single_quiz_permalink( $single_quiz_permalink ) {
		return $this->set_prop( 'courses.single_quiz_permalink', masteriyo_sanitize_permalink( $single_quiz_permalink ) );
	}

	/**
	 * Set option courses single section permalink.
	*
	* @since 0.1.0
	* @param string $single_section_permalink
	*/
	public function set_courses_single_section_permalink( $single_section_permalink ) {
		return $this->set_prop( 'courses.single_section_permalink', masteriyo_sanitize_permalink( $single_section_permalink ) );
	}

	/**
	 * Set option courses enable single course permalink.
	*
	* @since 0.1.0
	* @param string $enable_single_course_permalink
	*/
	public function set_courses_enable_single_course_permalink( $enable_single_course_permalink ) {
		return $this->set_prop( 'courses.enable_single_course_permalink', masteriyo_string_to_bool( $enable_single_course_permalink ) );
	}

	/**
	 * Set option courses single course enable editing.
	*
	* @since 0.1.0
	* @param string $single_course_enable_editing
	*/
	public function set_courses_single_course_enable_editing( $single_course_enable_editing ) {
		return $this->set_prop( 'courses.single_course_enable_editing', masteriyo_string_to_bool( $single_course_enable_editing ) );
	}

	/**
	 * Set option courses show thumbnail.
	*
	* @since 0.1.0
	* @param string $show_thumbnail
	*/
	public function set_courses_show_thumbnail( $show_thumbnail ) {
		return $this->set_prop( 'courses.show_thumbnail', masteriyo_string_to_bool( $show_thumbnail ) );
	}

	/**
	 * Set option courses thumbnail size.
	*
	* @since 0.1.0
	* @param string $thumbnail_size
	*/
	public function set_courses_thumbnail_size( $thumbnail_size ) {
		return $this->set_prop( 'courses.thumbnail_size', $thumbnail_size );
	}

	// Pages Setting Setter.

	/**
	 * Set option pages myaccount page id.
	*
	* @since 0.1.0
	* @param string $myaccount_page_id
	*/
	public function set_pages_myaccount_page_id( $myaccount_page_id ) {
		return $this->set_prop( 'pages.myaccount_page_id', absint( $myaccount_page_id ) );
	}

	/**
	 * Set option pages course list page id.
	*
	* @since 0.1.0
	* @param string $course_list_page_id
	*/
	public function set_pages_course_list_page_id( $course_list_page_id ) {
		return $this->set_prop( 'pages.course_list_page_id', absint( $course_list_page_id ) );
	}

	/**
	 * Set option pages terms conditions page_id.
	*
	* @since 0.1.0
	* @param string $terms_conditions_page_id
	*/
	public function set_pages_terms_conditions_page_id( $terms_conditions_page_id ) {
		return $this->set_prop( 'pages.terms_conditions_page_id', absint( $terms_conditions_page_id ) );
	}

	/**
	 * Set option pages checkout page_id.
	*
	* @since 0.1.0
	* @param string $checkout_page_id
	*/
	public function set_pages_checkout_page_id( $checkout_page_id ) {
		return $this->set_prop( 'pages.checkout_page_id', absint( $checkout_page_id ) );
	}

	// Checkout endpoints.

	/**
	 * Set option pages pay.
	*
	* @since 0.1.0
	* @param string $pay
	*/
	public function set_pages_pay( $pay ) {
		return $this->set_prop( 'pages.pay', $pay );
	}

	/**
	 * Set option pages order received.
	*
	* @since 0.1.0
	* @param string $order_received
	*/
	public function set_pages_order_received( $order_received ) {
		return $this->set_prop( 'pages.order_received', $order_received );
	}

	/**
	 * Set option pages add payment method.
	*
	* @since 0.1.0
	* @param string $add_payment_method
	*/
	public function set_pages_add_payment_method( $add_payment_method ) {
		return $this->set_prop( 'pages.add_payment_method', $add_payment_method );
	}

	/**
	 * Set option pages delete payment method.
	*
	* @since 0.1.0
	* @param string $delete_payment_method
	*/
	public function set_pages_delete_payment_method( $delete_payment_method ) {
		return $this->set_prop( 'pages.delete_payment_method', $delete_payment_method );
	}

	/**
	 * Set option pages set default payment method.
	*
	* @since 0.1.0
	* @param string $set_default_payment_method
	*/
	public function set_pages_set_default_payment_method( $set_default_payment_method ) {
		return $this->set_prop( 'pages.set_default_payment_method', $set_default_payment_method );
	}

	// Account endpoints.

	/**
	 * Set option pages orders.
	*
	* @since 0.1.0
	* @param string $orders
	*/
	public function set_pages_orders( $orders ) {
		return $this->set_prop( 'pages.orders', $orders );
	}

	/**
	 * Set option pages view order.
	*
	* @since 0.1.0
	* @param string $view_order
	*/
	public function set_pages_view_order( $view_order ) {
		return $this->set_prop( 'pages.view_order', $view_order );
	}

	/**
	 * Set option pages my courses.
	*
	* @since 0.1.0
	* @param string $my_courses
	*/
	public function set_pages_my_courses( $my_courses ) {
		return $this->set_prop( 'pages.my_courses', $my_courses );
	}

	/**
	 * Set option pages edit account.
	*
	* @since 0.1.0
	* @param string $edit_account
	*/
	public function set_pages_edit_account( $edit_account ) {
		return $this->set_prop( 'pages.edit_account', $edit_account );
	}

	/**
	 * Set option pages payment methods.
	*
	* @since 0.1.0
	* @param string $payment_methods
	*/
	public function set_pages_payment_methods( $payment_methods ) {
		return $this->set_prop( 'pages.payment_methods', $payment_methods );
	}

	/**
	 * Set option pages lost password.
	*
	* @since 0.1.0
	* @param string $lost_password
	*/
	public function set_pages_lost_password( $lost_password ) {
		return $this->set_prop( 'pages.lost_password', $lost_password );
	}

	/**
	 * Set option pages logout.
	*
	* @since 0.1.0
	* @param string $logout
	*/
	public function set_pages_logout( $logout ) {
		return $this->set_prop( 'pages.logout', $logout );
	}

	// Payments Setting Setter.

	// Paypal.

	/**
	 * Set option payments paypal enable.
	*
	* @since 0.1.0
	* @param boolean $paypal_enable
	*/
	public function set_payments_paypal_enable( $paypal_enable ) {
		return $this->set_prop( 'payments.paypal_enable', masteriyo_string_to_bool( $paypal_enable ) );
	}

	/**
	 * Set option payments paypal production email.
	*
	* @since 0.1.0
	* @param string $paypal_production_email
	*/
	public function set_payments_paypal_production_email( $paypal_production_email ) {
		return $this->set_prop( 'payments.paypal_production_email', $paypal_production_email );
	}

	/**
	 * Set option payments paypal sandbox enable.
	*
	* @since 0.1.0
	* @param boolean $paypal_sandbox_enable
	*/
	public function set_payments_paypal_sandbox_enable( $paypal_sandbox_enable ) {
		return $this->set_prop( 'payments.paypal_sandbox_enable', masteriyo_string_to_bool( $paypal_sandbox_enable ) );
	}

	/**
	 * Set option payments paypal sandbox email.
	*
	* @since 0.1.0
	* @param string $paypal_sandbox_email
	*/
	public function set_payments_paypal_sandbox_email( $paypal_sandbox_email ) {
		return $this->set_prop( 'payments.paypal_sandbox_email', $paypal_sandbox_email );
	}

	// Email Setting Setter.

	// General.

	/**
	 * Set general from name.
	*
	* @since 0.1.0
	* @param string $from_name
	*/
	public function set_emails_general_from_name( $from_name ) {
		return $this->set_prop( 'emails.general_from_name', $from_name );
	}

	/**
	 * Set general from email.
	*
	* @since 0.1.0
	* @param string $from_email
	*/
	public function set_emails_general_from_email( $from_email ) {
		return $this->set_prop( 'emails.general_from_email', $from_email );
	}

	/**
	 * Set general email default content.
	*
	* @since 0.1.0
	* @param string $default_content
	*/
	public function set_emails_general_default_content( $default_content ) {
		return $this->set_prop( 'emails.general_default_content', $default_content );
	}

	/**
	 * Set general email header image.
	*
	* @since 0.1.0
	* @param string $header_image
	*/
	public function set_emails_general_header_image( $header_image ) {
		return $this->set_prop( 'emails.general_header_image', $header_image );
	}

	/**
	 * Set general email footer text.
	*
	* @since 0.1.0
	* @param string $footer
	*/
	public function set_emails_general_footer_text( $footer ) {
		return $this->set_prop( 'emails.general_footer_text', $footer );
	}

	// New order.

	/**
	 * Enable/Disable new order email.
	*
	* @since 0.1.0
	* @param boolean $enable
	*/
	public function set_emails_new_order_enable( $enable ) {
		return $this->set_prop( 'emails.new_order_enable', masteriyo_string_to_bool( $enable ) );
	}

	/**
	 * Set new order recipients.
	*
	* @since 0.1.0
	* @param string[] $recipients
	*/
	public function set_emails_new_order_recipients( $recipients ) {
		return $this->set_prop( 'emails.new_order_recipients', maybe_unserialize( $recipients ) );
	}

	/**
	 * Set new order email subject.
	*
	* @since 0.1.0
	* @param string $subject
	*/
	public function set_emails_new_order_subject( $subject ) {
		return $this->set_prop( 'emails.new_order_subject', $subject );
	}

	/**
	 * Set new order email heading.
	*
	* @since 0.1.0
	* @param string $heading
	*/
	public function set_emails_new_order_heading( $heading ) {
		return $this->set_prop( 'emails.new_order_heading', $heading );
	}

	/**
	 * Set new order email content.
	*
	* @since 0.1.0
	* @param string $content
	*/
	public function set_emails_new_order_content( $content ) {
		return $this->set_prop( 'emails.new_order_content', $content );
	}

	// Processing order.

	/**
	 * Enable/Disable order processing email.
	*
	* @since 0.1.0
	* @param boolean $enable
	*/
	public function set_emails_processing_order_enable( $enable ) {
		return $this->set_prop( 'emails.processing_order_enable', masteriyo_string_to_bool( $enable ) );
	}

	/**
	 * Set order processing email subject.
	*
	* @since 0.1.0
	* @param string $subject
	*/
	public function set_emails_processing_order_subject( $subject ) {
		return $this->set_prop( 'emails.processing_order_subject', $subject );
	}

	/**
	 * Set order processing email heading.
	*
	* @since 0.1.0
	* @param string $heading
	*/
	public function set_emails_processing_order_heading( $heading ) {
		return $this->set_prop( 'emails.processing_order_heading', $heading );
	}

	/**
	 * Set order processing email content.
	*
	* @since 0.1.0
	* @param string $content
	*/
	public function set_emails_processing_order_content( $content ) {
		return $this->set_prop( 'emails.processing_order_content', $content );
	}

	// Completed order.

	/**
	 * Enable/Disable order completed email.
	*
	* @since 0.1.0
	* @param boolean $enable
	*/
	public function set_emails_completed_order_enable( $enable ) {
		return $this->set_prop( 'emails.completed_order_enable', masteriyo_string_to_bool( $enable ) );
	}

	/**
	 * Set order completed email subject.
	*
	* @since 0.1.0
	* @param string $subject
	*/
	public function set_emails_completed_order_subject( $subject ) {
		return $this->set_prop( 'emails.completed_order_subject', $subject );
	}

	/**
	 * Set order completed email heading.
	*
	* @since 0.1.0
	* @param string $heading
	*/
	public function set_emails_completed_order_heading( $heading ) {
		return $this->set_prop( 'emails.completed_order_heading', $heading );
	}

	/**
	 * Set order completed email content.
	*
	* @since 0.1.0
	* @param string $content
	*/
	public function set_emails_completed_order_content( $content ) {
		return $this->set_prop( 'emails.completed_order_content', $content );
	}

	// On Hold Order.

	/**
	 * Enable/Dsiable order onhold email.
	*
	* @since 0.1.0
	* @param boolean $enable
	*/
	public function set_emails_onhold_order_enable( $enable ) {
		return $this->set_prop( 'emails.onhold_order_enable', masteriyo_string_to_bool( $enable ) );
	}

	/**
	 * Set order onhold email subject.
	*
	* @since 0.1.0
	* @param string $subject
	*/
	public function set_emails_onhold_order_subject( $subject ) {
		return $this->set_prop( 'emails.onhold_order_subject', $subject );
	}

	/**
	 * Set order onhold email heading
	*
	* @since 0.1.0
	* @param string $heading
	*/
	public function set_emails_onhold_order_heading( $heading ) {
		return $this->set_prop( 'emails.onhold_order_heading', $heading );
	}

	/**
	 * Set order onhold email content.
	*
	* @since 0.1.0
	* @param string $content
	*/
	public function set_emails_onhold_order_content( $content ) {
		return $this->set_prop( 'emails.onhold_order_content', $content );
	}

	// Cancelled Order.

	/**
	 * Enable/Dsiable order cancelled email
	*
	* @since 0.1.0
	* @param boolean $enable
	*/
	public function set_emails_cancelled_order_enable( $enable ) {
		return $this->set_prop( 'emails.cancelled_order_enable', masteriyo_string_to_bool( $enable ) );
	}

	/**
	 * Set order cancelled email recipient.
	*
	* @since 0.1.0
	* @param string[] $recipients
	*/
	public function set_emails_cancelled_order_recipients( $recipients ) {
		return $this->set_prop( 'emails.cancelled_order_recipients', maybe_unserialize( $recipients ) );
	}

	/**
	 * Set order cancelled email subject.
	*
	* @since 0.1.0
	* @param string $subject
	*/
	public function set_emails_cancelled_order_subject( $subject ) {
		return $this->set_prop( 'emails.cancelled_order_subject', $subject );
	}

	/**
	 * Set order cancelled email heading.
	*
	* @since 0.1.0
	* @param string $heading
	*/
	public function set_emails_cancelled_order_heading( $heading ) {
		return $this->set_prop( 'emails.cancelled_order_heading', $heading );
	}

	/**
	 * Set order cancelled email content.
	*
	* @since 0.1.0
	* @param string $content
	*/
	public function set_emails_cancelled_order_content( $content ) {
		return $this->set_prop( 'emails.cancelled_order_content', $content );
	}

	// Enrolled Course.

	/**
	 * Enable/Disable course enrolled email.
	*
	* @since 0.1.0
	* @param boolean $enable
	*/
	public function set_emails_enrolled_course_enable( $enable ) {
		return $this->set_prop( 'emails.enrolled_course_enable', masteriyo_string_to_bool( $enable ) );
	}

	/**
	 * Set course enrolled email subject.
	*
	* @since 0.1.0
	* @param string $subject
	*/
	public function set_emails_enrolled_course_subject( $subject ) {
		return $this->set_prop( 'emails.enrolled_course_subject', $subject );
	}

	/**
	 * Set course enrolled email heading.
	*
	* @since 0.1.0
	* @param string $heading
	*/
	public function set_emails_enrolled_course_heading( $heading ) {
		return $this->set_prop( 'emails.enrolled_course_heading', $heading );
	}

	/**
	 * Set course enrolled email content.
	*
	* @since 0.1.0
	* @param string $content
	*/
	public function set_emails_enrolled_course_content( $content ) {
		return $this->set_prop( 'emails.enrolled_course_content', $content );
	}

	// Completed Course.

	/**
	 * Enable/Disable course completed email.
	*
	* @since 0.1.0
	* @param boolean $enable
	*/
	public function set_emails_completed_course_enable( $enable ) {
		return $this->set_prop( 'emails.completed_course_enable', masteriyo_string_to_bool( $enable ) );
	}

	/**
	 * Set completed course email subject.
	*
	* @since 0.1.0
	* @param string $subject
	*/
	public function set_emails_completed_course_subject( $subject ) {
		return $this->set_prop( 'emails.completed_course_subject', $subject );
	}

	/**
	 * Set completed course email heading.
	*
	* @since 0.1.0
	* @param string $heading
	*/
	public function set_emails_completed_course_heading( $heading ) {
		return $this->set_prop( 'emails.completed_course_heading', $heading );
	}

	/**
	 * Set emails completed course content.
	*
	* @since 0.1.0
	* @param string $content
	*/
	public function set_emails_completed_course_content( $content ) {
		return $this->set_prop( 'emails.completed_course_content', $content );
	}

	// Become An Instructor.

	/**
	 * Enable/disable becone an instructor email.
	*
	* @since 0.1.0
	* @param boolean $enable
	*/
	public function set_emails_become_an_instructor_enable( $enable ) {
		return $this->set_prop( 'emails.become_an_instructor_enable', masteriyo_string_to_bool( $enable ) );
	}

	/**
	 * Set become an instructor email subject.
	*
	* @since 0.1.0
	* @param string $subject
	*/
	public function set_emails_become_an_instructor_subject( $subject ) {
		return $this->set_prop( 'emails.become_an_instructor_subject', $subject );
	}

	/**
	 * Set become an insctuctor email heading.
	*
	* @since 0.1.0
	* @param string $heading
	*/
	public function set_emails_become_an_instructor_heading( $heading ) {
		return $this->set_prop( 'emails.become_an_instructor_heading', $heading );
	}

	/**
	 * Set become an instructor email content.
	*
	* @since 0.1.0
	* @param string $content
	*/
	public function set_emails_become_an_instructor_content( $content ) {
		return $this->set_prop( 'emails.become_an_instructor_content', $content );
	}

	// Advanced Setting.

	// Debug.

	/**
	 * Enable/Disable template debug mode.
	*
	* @since 0.1.0
	* @param string $mode
	*/
	public function set_advanced_template_debug_enable( $mode ) {
		return $this->set_prop( 'advanced.template_debug_enable', masteriyo_string_to_bool( $mode ) );
	}

	/**
	 * Enable/Disable debug mode.
	*
	* @since 0.1.0
	* @param string $mode
	*/
	public function set_advanced_debug_enable( $mode ) {
		return $this->set_prop( 'advanced.debug_enable', masteriyo_string_to_bool( $mode ) );
	}

	/**
	 * Set advance styles mode.
	*
	* @since 0.1.0
	* @param string $style
	*/
	public function set_advanced_styles_mode( $style ) {
		return $this->set_prop( 'advanced.styles_mode', $style );
	}

}
