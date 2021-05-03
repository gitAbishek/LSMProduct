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
		'courses.enable_editing'                 => '',

		// Single Course.
		'courses.category_base'                  => '',
		'courses.tag_base'                       => '',
		'courses.difficulty_base'                => '',
		'courses.single_course_permalink'        => '',
		'courses.lessons'                        => '',
		'courses.quizes'                         => '',
		'courses.enable_single_course_permalink' => '',
		'courses.single_course_enable_editing'   => '',

		// Course Thumbnail.
		'courses.show_thumbnail'                 => '',
		'courses.thumbnail_size'                 => '',

		/** Pages Setting */

		// Page Setup.
		'pages.profile_page_id'                  => '',
		'pages.course_list_page_id'              => '',
		'pages.terms_conditions_page_id'         => '',
		'pages.cart_page_id'                     => '',
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

		/** Emails Setting */

		// General->Email Options.
		'emails.general_from_name'               => '',
		'emails.general_from_email'              => '',

		// General->Email Templates.
		'emails.general_default_content'         => '',
		'emails.general_header_image'            => '',
		'emails.general_footer_text'             => '',

		//New Order.
		'emails.new_order_enable'                => '',
		'emails.new_order_recipients'            => '',
		'emails.new_order_subject'               => '',
		'emails.new_order_heading'               => '',
		'emails.new_order_content'               => '',

		// Processing Order.
		'emails.processing_order_enable'         => '',
		'emails.processing_order_subject'        => '',
		'emails.processing_order_heading'        => '',
		'emails.processing_order_content'        => '',

		// Completed Order.
		'emails.completed_order_enable'          => '',
		'emails.completed_order_subject'         => '',
		'emails.completed_order_heading'         => '',
		'emails.completed_order_content'         => '',

		// On Hold Order.
		'emails.onhold_order_enable'             => '',
		'emails.onhold_order_subject'            => '',
		'emails.onhold_order_heading'            => '',
		'emails.onhold_order_content'            => '',

		// Cancelled Order.
		'emails.cancelled_order_enable'          => '',
		'emails.cancelled_order_recipients'      => '',
		'emails.cancelled_order_subject'         => '',
		'emails.cancelled_order_heading'         => '',
		'emails.cancelled_order_content'         => '',

		// Enrolled Course.
		'emails.enrolled_course_enable'          => '',
		'emails.enrolled_course_subject'         => '',
		'emails.enrolled_course_heading'         => '',
		'emails.enrolled_course_content'         => '',

		// Completed Course.
		'emails.completed_course_enable'         => '',
		'emails.completed_course_subject'        => '',
		'emails.completed_course_heading'        => '',
		'emails.completed_course_content'        => '',

		// Become An Instructor.
		'emails.become_an_instructor_enable'     => '',
		'emails.become_an_instructor_subject'    => '',
		'emails.become_an_instructor_heading'    => '',
		'emails.become_an_instructor_content'    => '',

		/** Advanced Setting */

		// Debug.
		'advanced.template_debug_enable'         => '',
		'advanced.debug_enable'                  => '',
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
	 * Get option courses_lessons.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_courses_lessons( $context = 'view' ) {
		return $this->get_prop( 'courses.lessons', $context );
	}

	/**
	 * Get option courses_quizes.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_courses_quizes( $context = 'view' ) {
		return $this->get_prop( 'courses.quizes', $context );
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
	 * Get option pages_profile_page_id.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_pages_profile_page_id( $context = 'view' ) {
		return $this->get_prop( 'pages.profile_page_id', $context );
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
	 * Get option pages_cart_page_id.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_pages_cart_page_id( $context = 'view' ) {
		return $this->get_prop( 'pages.cart_page_id', $context );
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
	* Set option general_address_line1.
	*
	* @since 0.1.0
	* @param string $general_address_line1
	*/
	public function set_general_address_line1( $general_address_line1 ) {
		return $this->set_prop( 'general.address_line1', $general_address_line1 );
	}

	/**
	 * Set option general_address_line2.
	*
	* @since 0.1.0
	* @param string $general_address_line2
	*/
	public function set_general_address_line2( $general_address_line2 ) {
		return $this->set_prop( 'general.address_line2', $general_address_line2 );
	}

	/**
	 * Set option general_city.
	*
	* @since 0.1.0
	* @param string $general_city
	*/
	public function set_general_city( $general_city ) {
		return $this->set_prop( 'general.city', $general_city );
	}

	/**
	 * Set option general_country.
	*
	* @since 0.1.0
	* @param string $general_country
	*/
	public function set_general_country( $general_country ) {
		return $this->set_prop( 'general.country', $general_country );
	}

	/**
	 * Set option general_postcode.
	*
	* @since 0.1.0
	* @param string $general_postcode
	*/
	public function set_general_postcode( $general_postcode ) {
		return $this->set_prop( 'general.postcode', $general_postcode );
	}

	/**
	 * Set option general_currency.
	*
	* @since 0.1.0
	* @param string $general_currency
	*/
	public function set_general_currency( $general_currency ) {
		return $this->set_prop( 'general.currency', $general_currency );
	}

	/**
	 * Set option general_currency_position.
	*
	* @since 0.1.0
	* @param string $general_currency_position
	*/
	public function set_general_currency_position( $general_currency_position ) {
		return $this->set_prop( 'general.currency_position', $general_currency_position );
	}

	/**
	 * Set option general_thousand_separator.
	*
	* @since 0.1.0
	* @param string $general_thousand_separator
	*/
	public function set_general_thousand_separator( $general_thousand_separator ) {
		return $this->set_prop( 'general.thousand_separator', $general_thousand_separator );
	}

	/**
	 * Set option general_decimal_separator.
	*
	* @since 0.1.0
	* @param string $general_decimal_separator
	*/
	public function set_general_decimal_separator( $general_decimal_separator ) {
		return $this->set_prop( 'general.decimal_separator', $general_decimal_separator );
	}

	/**
	 * Set option general_number_of_decimals.
	*
	* @since 0.1.0
	* @param string $general_number_of_decimals
	*/
	public function set_general_number_of_decimals( $general_number_of_decimals ) {
		return $this->set_prop( 'general.number_of_decimals', $general_number_of_decimals );
	}

	// Courses Setting Setter.

	/**
	 * Set option courses_placeholder_image.
	*
	* @since 0.1.0
	* @param string $courses_placeholder_image
	*/
	public function set_courses_placeholder_image( $courses_placeholder_image ) {
		return $this->set_prop( 'courses.placeholder_image', $courses_placeholder_image );
	}

	/**
	 * Set option courses_add_to_cart_behaviour.
	*
	* @since 0.1.0
	* @param string $courses_add_to_cart_behaviour
	*/
	public function set_courses_add_to_cart_behaviour( $courses_add_to_cart_behaviour ) {
		return $this->set_prop( 'courses.add_to_cart_behaviour', $courses_add_to_cart_behaviour );
	}

	/**
	 * Set option courses_per_page.
	*
	* @since 0.1.0
	* @param string $courses_per_page
	*/
	public function set_courses_per_page( $courses_per_page ) {
		return $this->set_prop( 'courses.per_page', $courses_per_page );
	}

	/**
	 * Set option courses_enable_editing.
	*
	* @since 0.1.0
	* @param string $courses_enable_editing
	*/
	public function set_courses_enable_editing( $courses_enable_editing ) {
		return $this->set_prop( 'courses.enable_editing', $courses_enable_editing );
	}

	/**
	 * Set option courses_category_base.
	*
	* @since 0.1.0
	* @param string $courses_category_base
	*/
	public function set_courses_category_base( $courses_category_base ) {
		return $this->set_prop( 'courses.category_base', $courses_category_base );
	}

	/**
	 * Set option courses_tag_base.
	*
	* @since 0.1.0
	* @param string $courses_tag_base
	*/
	public function set_courses_tag_base( $courses_tag_base ) {
		return $this->set_prop( 'courses.tag_base', $courses_tag_base );
	}

	/**
	 * Set option courses_difficulty_base.
	*
	* @since 0.1.0
	* @param string $courses_difficulty_base
	*/
	public function set_courses_difficulty_base( $courses_difficulty_base ) {
		return $this->set_prop( 'courses.difficulty_base', $courses_difficulty_base );
	}

	/**
	 * Set option courses_single_course_permalink.
	*
	* @since 0.1.0
	* @param string $courses_single_course_permalink
	*/
	public function set_courses_single_course_permalink( $courses_single_course_permalink ) {
		return $this->set_prop( 'courses.single_course_permalink', $courses_single_course_permalink );
	}

	/**
	 * Set option courses_lessons.
	*
	* @since 0.1.0
	* @param string $courses_lessons
	*/
	public function set_courses_lessons( $courses_lessons ) {
		return $this->set_prop( 'courses.lessons', $courses_lessons );
	}

	/**
	 * Set option courses_quizes.
	*
	* @since 0.1.0
	* @param string $courses_quizes
	*/
	public function set_courses_quizes( $courses_quizes ) {
		return $this->set_prop( 'courses.quizes', $courses_quizes );
	}

	/**
	 * Set option courses_enable_single_course_permalink.
	*
	* @since 0.1.0
	* @param string $courses_enable_single_course_permalink
	*/
	public function set_courses_enable_single_course_permalink( $courses_enable_single_course_permalink ) {
		return $this->set_prop( 'courses.enable_single_course_permalink', $courses_enable_single_course_permalink );
	}

	/**
	 * Set option courses_single_course_enable_editing.
	*
	* @since 0.1.0
	* @param string $courses_single_course_enable_editing
	*/
	public function set_courses_single_course_enable_editing( $courses_single_course_enable_editing ) {
		return $this->set_prop( 'courses.single_course_enable_editing', $courses_single_course_enable_editing );
	}

	/**
	 * Set option courses_show_thumbnail.
	*
	* @since 0.1.0
	* @param string $courses_show_thumbnail
	*/
	public function set_courses_show_thumbnail( $courses_show_thumbnail ) {
		return $this->set_prop( 'courses.show_thumbnail', $courses_show_thumbnail );
	}

	/**
	 * Set option courses_thumbnail_size.
	*
	* @since 0.1.0
	* @param string $courses_thumbnail_size
	*/
	public function set_courses_thumbnail_size( $courses_thumbnail_size ) {
		return $this->set_prop( 'courses.thumbnail_size', $courses_thumbnail_size );
	}

	// Pages Setting Setter.

	/**
	 * Set option pages_profile_page_id.
	*
	* @since 0.1.0
	* @param string $pages_profile_page_id
	*/
	public function set_pages_profile_page_id( $pages_profile_page_id ) {
		return $this->set_prop( 'pages.profile_page_id', $pages_profile_page_id );
	}

	/**
	 * Set option pages_course_list_page_id.
	*
	* @since 0.1.0
	* @param string $pages_course_list_page_id
	*/
	public function set_pages_course_list_page_id( $pages_course_list_page_id ) {
		return $this->set_prop( 'pages.course_list_page_id', $pages_course_list_page_id );
	}

	/**
	 * Set option pages_terms_conditions_page_id.
	*
	* @since 0.1.0
	* @param string $pages_terms_conditions_page_id
	*/
	public function set_pages_terms_conditions_page_id( $pages_terms_conditions_page_id ) {
		return $this->set_prop( 'pages.terms_conditions_page_id', $pages_terms_conditions_page_id );
	}

	/**
	 * Set option pages_cart_page_id.
	*
	* @since 0.1.0
	* @param string $pages_cart_page_id
	*/
	public function set_pages_cart_page_id( $pages_cart_page_id ) {
		return $this->set_prop( 'pages.cart_page_id', $pages_cart_page_id );
	}

	/**
	 * Set option pages_checkout_page_id.
	*
	* @since 0.1.0
	* @param string $pages_checkout_page_id
	*/
	public function set_pages_checkout_page_id( $pages_checkout_page_id ) {
		return $this->set_prop( 'pages.checkout_page_id', $pages_checkout_page_id );
	}

	// Checkout endpoints.

	/**
	 * Set option pages_pay.
	*
	* @since 0.1.0
	* @param string $pages_pay
	*/
	public function set_pages_pay( $pages_pay ) {
		return $this->set_prop( 'pages.pay', $pages_pay );
	}

	/**
	 * Set option pages_order_received.
	*
	* @since 0.1.0
	* @param string $pages_order_received
	*/
	public function set_pages_order_received( $pages_order_received ) {
		return $this->set_prop( 'pages.order_received', $pages_order_received );
	}

	/**
	 * Set option pages_add_payment_method.
	*
	* @since 0.1.0
	* @param string $pages_add_payment_method
	*/
	public function set_pages_add_payment_method( $pages_add_payment_method ) {
		return $this->set_prop( 'pages.add_payment_method', $pages_add_payment_method );
	}

	/**
	 * Set option pages_delete_payment_method.
	*
	* @since 0.1.0
	* @param string $pages_delete_payment_method
	*/
	public function set_pages_delete_payment_method( $pages_delete_payment_method ) {
		return $this->set_prop( 'pages.delete_payment_method', $pages_delete_payment_method );
	}

	/**
	 * Set option pages_set_default_payment_method.
	*
	* @since 0.1.0
	* @param string $pages_set_default_payment_method
	*/
	public function set_pages_set_default_payment_method( $pages_set_default_payment_method ) {
		return $this->set_prop( 'pages.set_default_payment_method', $pages_set_default_payment_method );
	}

	// Account endpoints.

	/**
	 * Set option pages_orders.
	*
	* @since 0.1.0
	* @param string $pages_orders
	*/
	public function set_pages_orders( $pages_orders ) {
		return $this->set_prop( 'pages.orders', $pages_orders );
	}

	/**
	 * Set option pages_view_order.
	*
	* @since 0.1.0
	* @param string $pages_view_order
	*/
	public function set_pages_view_order( $pages_view_order ) {
		return $this->set_prop( 'pages.view_order', $pages_view_order );
	}

	/**
	 * Set option pages_my_courses.
	*
	* @since 0.1.0
	* @param string $pages_my_courses
	*/
	public function set_pages_my_courses( $pages_my_courses ) {
		return $this->set_prop( 'pages.my_courses', $pages_my_courses );
	}

	/**
	 * Set option pages_edit_account.
	*
	* @since 0.1.0
	* @param string $pages_edit_account
	*/
	public function set_pages_edit_account( $pages_edit_account ) {
		return $this->set_prop( 'pages.edit_account', $pages_edit_account );
	}

	/**
	 * Set option pages_payment_methods.
	*
	* @since 0.1.0
	* @param string $pages_payment_methods
	*/
	public function set_pages_payment_methods( $pages_payment_methods ) {
		return $this->set_prop( 'pages.payment_methods', $pages_payment_methods );
	}

	/**
	 * Set option pages_lost_password.
	*
	* @since 0.1.0
	* @param string $pages_lost_password
	*/
	public function set_pages_lost_password( $pages_lost_password ) {
		return $this->set_prop( 'pages.lost_password', $pages_lost_password );
	}

	/**
	 * Set option pages_logout.
	*
	* @since 0.1.0
	* @param string $pages_logout
	*/
	public function set_pages_logout( $pages_logout ) {
		return $this->set_prop( 'pages.logout', $pages_logout );
	}

	// Email Setting Setter.

	// General.

	/**
	 * Set option emails_general_from_name.
	*
	* @since 0.1.0
	* @param string $emails_general_from_name
	*/
	public function set_emails_general_from_name( $emails_general_from_name ) {
		return $this->set_prop( 'emails.general_from_name', $emails_general_from_name );
	}

	/**
	 * Set option emails_general_from_email.
	*
	* @since 0.1.0
	* @param string $emails_general_from_email
	*/
	public function set_emails_general_from_email( $emails_general_from_email ) {
		return $this->set_prop( 'emails.general_from_email', $emails_general_from_email );
	}

	/**
	 * Set option emails_general_default_content.
	*
	* @since 0.1.0
	* @param string $emails_general_default_content
	*/
	public function set_emails_general_default_content( $emails_general_default_content ) {
		return $this->set_prop( 'emails.general_default_content', $emails_general_default_content );
	}

	/**
	 * Set option emails_general_header_image.
	*
	* @since 0.1.0
	* @param string $emails_general_header_image
	*/
	public function set_emails_general_header_image( $emails_general_header_image ) {
		return $this->set_prop( 'emails.general_header_image', $emails_general_header_image );
	}

	/**
	 * Set option emails_general_footer_text.
	*
	* @since 0.1.0
	* @param string $emails_general_footer_text
	*/
	public function set_emails_general_footer_text( $emails_general_footer_text ) {
		return $this->set_prop( 'emails.general_footer_text', $emails_general_footer_text );
	}

	// New order.

	/**
	 * Set option emails_new_order_enable.
	*
	* @since 0.1.0
	* @param string $emails_new_order_enable
	*/
	public function set_emails_new_order_enable( $emails_new_order_enable ) {
		return $this->set_prop( 'emails.new_order_enable', $emails_new_order_enable );
	}

	/**
	 * Set option emails_new_order_recipients.
	*
	* @since 0.1.0
	* @param string $emails_new_order_recipients
	*/
	public function set_emails_new_order_recipients( $emails_new_order_recipients ) {
		return $this->set_prop( 'emails.new_order_recipients', $emails_new_order_recipients );
	}

	/**
	 * Set option emails_new_order_subject.
	*
	* @since 0.1.0
	* @param string $emails_new_order_subject
	*/
	public function set_emails_new_order_subject( $emails_new_order_subject ) {
		return $this->set_prop( 'emails.new_order_subject', $emails_new_order_subject );
	}

	/**
	 * Set option emails_new_order_heading.
	*
	* @since 0.1.0
	* @param string $emails_new_order_heading
	*/
	public function set_emails_new_order_heading( $emails_new_order_heading ) {
		return $this->set_prop( 'emails.new_order_heading', $emails_new_order_heading );
	}

	/**
	 * Set option emails_new_order_content.
	*
	* @since 0.1.0
	* @param string $emails_new_order_content
	*/
	public function set_emails_new_order_content( $emails_new_order_content ) {
		return $this->set_prop( 'emails.new_order_content', $emails_new_order_content );
	}

	// Processing order.

	/**
	 * Set option emails_processing_order_enable.
	*
	* @since 0.1.0
	* @param string $emails_processing_order_enable
	*/
	public function set_emails_processing_order_enable( $emails_processing_order_enable ) {
		return $this->set_prop( 'emails.processing_order_enable', $emails_processing_order_enable );
	}

	/**
	 * Set option emails_processing_order_subject.
	*
	* @since 0.1.0
	* @param string $emails_processing_order_subject
	*/
	public function set_emails_processing_order_subject( $emails_processing_order_subject ) {
		return $this->set_prop( 'emails.processing_order_subject', $emails_processing_order_subject );
	}

	/**
	 * Set option emails_processing_order_heading.
	*
	* @since 0.1.0
	* @param string $emails_processing_order_heading
	*/
	public function set_emails_processing_order_heading( $emails_processing_order_heading ) {
		return $this->set_prop( 'emails.processing_order_heading', $emails_processing_order_heading );
	}

	/**
	 * Set option emails_processing_order_content.
	*
	* @since 0.1.0
	* @param string $emails_processing_order_content
	*/
	public function set_emails_processing_order_content( $emails_processing_order_content ) {
		return $this->set_prop( 'emails.processing_order_content', $emails_processing_order_content );
	}

	// Completed order.

	/**
	 * Set option emails_completed_order_enable.
	*
	* @since 0.1.0
	* @param string $emails_completed_order_enable
	*/
	public function set_emails_completed_order_enable( $emails_completed_order_enable ) {
		return $this->set_prop( 'emails.completed_order_enable', $emails_completed_order_enable );
	}

	/**
	 * Set option emails_completed_order_subject.
	*
	* @since 0.1.0
	* @param string $emails_completed_order_subject
	*/
	public function set_emails_completed_order_subject( $emails_completed_order_subject ) {
		return $this->set_prop( 'emails.completed_order_subject', $emails_completed_order_subject );
	}

	/**
	 * Set option emails_completed_order_heading.
	*
	* @since 0.1.0
	* @param string $emails_completed_order_heading
	*/
	public function set_emails_completed_order_heading( $emails_completed_order_heading ) {
		return $this->set_prop( 'emails.completed_order_heading', $emails_completed_order_heading );
	}

	/**
	 * Set option emails_completed_order_content.
	*
	* @since 0.1.0
	* @param string $emails_completed_order_content
	*/
	public function set_emails_completed_order_content( $emails_completed_order_content ) {
		return $this->set_prop( 'emails.completed_order_content', $emails_completed_order_content );
	}

	// On Hold Order.

	/**
	 * Set option emails_onhold_order_enable.
	*
	* @since 0.1.0
	* @param string $emails_onhold_order_enable
	*/
	public function set_emails_onhold_order_enable( $emails_onhold_order_enable ) {
		return $this->set_prop( 'emails.onhold_order_enable', $emails_onhold_order_enable );
	}

	/**
	 * Set option emails_onhold_order_subject.
	*
	* @since 0.1.0
	* @param string $emails_onhold_order_subject
	*/
	public function set_emails_onhold_order_subject( $emails_onhold_order_subject ) {
		return $this->set_prop( 'emails.onhold_order_subject', $emails_onhold_order_subject );
	}

	/**
	 * Set option emails_onhold_order_heading.
	*
	* @since 0.1.0
	* @param string $emails_onhold_order_heading
	*/
	public function set_emails_onhold_order_heading( $emails_onhold_order_heading ) {
		return $this->set_prop( 'emails.onhold_order_heading', $emails_onhold_order_heading );
	}

	/**
	 * Set option emails_onhold_order_content.
	*
	* @since 0.1.0
	* @param string $emails_onhold_order_content
	*/
	public function set_emails_onhold_order_content( $emails_onhold_order_content ) {
		return $this->set_prop( 'emails.onhold_order_content', $emails_onhold_order_content );
	}

	// Cancelled Order.

	/**
	 * Set option emails_cancelled_order_enable.
	*
	* @since 0.1.0
	* @param string $emails_cancelled_order_enable
	*/
	public function set_emails_cancelled_order_enable( $emails_cancelled_order_enable ) {
		return $this->set_prop( 'emails.cancelled_order_enable', $emails_cancelled_order_enable );
	}

	/**
	 * Set option emails_cancelled_order_recipients.
	*
	* @since 0.1.0
	* @param string $emails_cancelled_order_recipients
	*/
	public function set_emails_cancelled_order_recipients( $emails_cancelled_order_recipients ) {
		return $this->set_prop( 'emails.cancelled_order_recipients', $emails_cancelled_order_recipients );
	}

	/**
	 * Set option emails_cancelled_order_subject.
	*
	* @since 0.1.0
	* @param string $emails_cancelled_order_subject
	*/
	public function set_emails_cancelled_order_subject( $emails_cancelled_order_subject ) {
		return $this->set_prop( 'emails.cancelled_order_subject', $emails_cancelled_order_subject );
	}

	/**
	 * Set option emails_cancelled_order_heading.
	*
	* @since 0.1.0
	* @param string $emails_cancelled_order_heading
	*/
	public function set_emails_cancelled_order_heading( $emails_cancelled_order_heading ) {
		return $this->set_prop( 'emails.cancelled_order_heading', $emails_cancelled_order_heading );
	}

	/**
	 * Set option emails_cancelled_order_content.
	*
	* @since 0.1.0
	* @param string $emails_cancelled_order_content
	*/
	public function set_emails_cancelled_order_content( $emails_cancelled_order_content ) {
		return $this->set_prop( 'emails.cancelled_order_content', $emails_cancelled_order_content );
	}

	// Enrolled Course.

	/**
	 * Set option emails_enrolled_course_enable.
	*
	* @since 0.1.0
	* @param string $emails_enrolled_course_enable
	*/
	public function set_emails_enrolled_course_enable( $emails_enrolled_course_enable ) {
		return $this->set_prop( 'emails.enrolled_course_enable', $emails_enrolled_course_enable );
	}

	/**
	 * Set option emails_enrolled_course_subject.
	*
	* @since 0.1.0
	* @param string $emails_enrolled_course_subject
	*/
	public function set_emails_enrolled_course_subject( $emails_enrolled_course_subject ) {
		return $this->set_prop( 'emails.enrolled_course_subject', $emails_enrolled_course_subject );
	}

	/**
	 * Set option emails_enrolled_course_heading.
	*
	* @since 0.1.0
	* @param string $emails_enrolled_course_heading
	*/
	public function set_emails_enrolled_course_heading( $emails_enrolled_course_heading ) {
		return $this->set_prop( 'emails.enrolled_course_heading', $emails_enrolled_course_heading );
	}

	/**
	 * Set option emails_enrolled_course_content.
	*
	* @since 0.1.0
	* @param string $emails_enrolled_course_content
	*/
	public function set_emails_enrolled_course_content( $emails_enrolled_course_content ) {
		return $this->set_prop( 'emails.enrolled_course_content', $emails_enrolled_course_content );
	}

	// Completed Course.

	/**
	 * Set option emails_completed_course_enable.
	*
	* @since 0.1.0
	* @param string $emails_completed_course_enable
	*/
	public function set_emails_completed_course_enable( $emails_completed_course_enable ) {
		return $this->set_prop( 'emails.completed_course_enable', $emails_completed_course_enable );
	}

	/**
	 * Set option emails_completed_course_subject.
	*
	* @since 0.1.0
	* @param string $emails_completed_course_subject
	*/
	public function set_emails_completed_course_subject( $emails_completed_course_subject ) {
		return $this->set_prop( 'emails.completed_course_subject', $emails_completed_course_subject );
	}

	/**
	 * Set option emails_completed_course_heading.
	*
	* @since 0.1.0
	* @param string $emails_completed_course_heading
	*/
	public function set_emails_completed_course_heading( $emails_completed_course_heading ) {
		return $this->set_prop( 'emails.completed_course_heading', $emails_completed_course_heading );
	}

	/**
	 * Set option emails_completed_course_content.
	*
	* @since 0.1.0
	* @param string $emails_completed_course_content
	*/
	public function set_emails_completed_course_content( $emails_completed_course_content ) {
		return $this->set_prop( 'emails.completed_course_content', $emails_completed_course_content );
	}

	// Become An Instructor.

	/**
	 * Set option emails_become_an_instructor_enable.
	*
	* @since 0.1.0
	* @param string $emails_become_an_instructor_enable
	*/
	public function set_emails_become_an_instructor_enable( $emails_become_an_instructor_enable ) {
		return $this->set_prop( 'emails.become_an_instructor_enable', $emails_become_an_instructor_enable );
	}

	/**
	 * Set option emails_become_an_instructor_subject.
	*
	* @since 0.1.0
	* @param string $emails_become_an_instructor_subject
	*/
	public function set_emails_become_an_instructor_subject( $emails_become_an_instructor_subject ) {
		return $this->set_prop( 'emails.become_an_instructor_subject', $emails_become_an_instructor_subject );
	}

	/**
	 * Set option emails_become_an_instructor_heading.
	*
	* @since 0.1.0
	* @param string $emails_become_an_instructor_heading
	*/
	public function set_emails_become_an_instructor_heading( $emails_become_an_instructor_heading ) {
		return $this->set_prop( 'emails.become_an_instructor_heading', $emails_become_an_instructor_heading );
	}

	/**
	 * Set option emails_become_an_instructor_content.
	*
	* @since 0.1.0
	* @param string $emails_become_an_instructor_content
	*/
	public function set_emails_become_an_instructor_content( $emails_become_an_instructor_content ) {
		return $this->set_prop( 'emails.become_an_instructor_content', $emails_become_an_instructor_content );
	}

	// Advanced Setting.

	// Debug.

	/**
	 * Set option advanced_template_debug_enable.
	*
	* @since 0.1.0
	* @param string $advanced_template_debug_enable
	*/
	public function set_advanced_template_debug_enable( $advanced_template_debug_enable ) {
		return $this->set_prop( 'advanced.template_debug_enable', $advanced_template_debug_enable );
	}

	/**
	 * Set option advanced_debug_enable.
	*
	* @since 0.1.0
	* @param string $advanced_debug_enable
	*/
	public function set_advanced_debug_enable( $advanced_debug_enable ) {
		return $this->set_prop( 'advanced.debug_enable', $advanced_debug_enable );
	}

	/**
	 * Set option advanced_styles_mode.
	*
	* @since 0.1.0
	* @param string $advanced_styles_mode
	*/
	public function set_advanced_styles_mode( $advanced_styles_mode ) {
		return $this->set_prop( 'advanced.styles_mode', $advanced_styles_mode );
	}

}
