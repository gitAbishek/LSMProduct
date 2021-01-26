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
		'name'     => '',
		'value'    => ''
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
	 * Get the form fields after they are initialized.
	 *
	 * @return array of options
	 */
	public function get_form_fields() {
		return apply_filters(
			'masteriyo_settings_api_form_fields_' . $this->id,
			array_map( array( $this, 'set_defaults' ), $this->form_fields )
		);
	}

	/**
	 * Set default required properties for each field.
	 *
	 * @param array $field Setting field array.
	 * @return array
	 */
	protected function set_defaults( $field ) {
		if ( ! isset( $field['default'] ) ) {
			$field['default'] = '';
		}
		return $field;
	}

	/**
	 * Return the name of the option in the WP DB.
	 *
	 * @since 2.6.0
	 * @return string
	 */
	public function get_option_key() {
		return $this->plugin_id . $this->id . '_settings';
	}

	/**
	 * Get a fields type. Defaults to "text" if not set.
	 *
	 * @param  array $field Field key.
	 * @return string
	 */
	public function get_field_type( $field ) {
		return empty( $field['type'] ) ? 'text' : $field['type'];
	}

	/**
	 * Get a fields default value. Defaults to "" if not set.
	 *
	 * @param  array $field Field key.
	 * @return string
	 */
	public function get_field_default( $field ) {
		return empty( $field['default'] ) ? '' : $field['default'];
	}

	/**
	 * Get a field's posted and validated value.
	 *
	 * @param string $key Field key.
	 * @param array  $field Field array.
	 * @param array  $post_data Posted data.
	 * @return string
	 */
	public function get_field_value( $key, $field, $post_data = array() ) {
		$type      = $this->get_field_type( $field );
		$field_key = $this->get_field_key( $key );
		$post_data = empty( $post_data ) ? $_POST : $post_data; // phpcs:ignore
		$value     = isset( $post_data[ $field_key ] ) ? $post_data[ $field_key ] : null;

		if ( isset( $field['sanitize_callback'] ) && is_callable( $field['sanitize_callback'] ) ) {
			return call_user_func( $field['sanitize_callback'], $value );
		}

		// Look for a validate_FIELDID_field method for special handling.
		if ( is_callable( array( $this, 'validate_' . $key . '_field' ) ) ) {
			return $this->{'validate_' . $key . '_field'}( $key, $value );
		}

		// Look for a validate_FIELDTYPE_field method.
		if ( is_callable( array( $this, 'validate_' . $type . '_field' ) ) ) {
			return $this->{'validate_' . $type . '_field'}( $key, $value );
		}

		// Fallback to text.
		return $this->validate_text_field( $key, $value );
	}

	/**
	 * Sets the POSTed data. This method can be used to set specific data, instead of taking it from the $_POST array.
	 *
	 * @param array $data Posted data.
	 */
	public function set_post_data( $data = array() ) {
		$this->data = $data;
	}

	/**
	 * Returns the POSTed data, to be used to save the settings.
	 *
	 * @return array
	 */
	public function get_post_data() {
		if ( ! empty( $this->data ) && is_array( $this->data ) ) {
			return $this->data;
		}
		return $_POST; // phpcs:ignore
	}

	/**
	 * Update a single option.
	 *
	 * @since 0.1.0
	 * @param string $key Option key.
	 * @param mixed  $value Value to set.
	 * @return bool was anything saved?
	 */
	public function update_option( $key, $value = '' ) {
		if ( empty( $this->settings ) ) {
			$this->init_settings();
		}

		$this->settings[ $key ] = $value;

		return update_option( $this->get_option_key(), apply_filters( 'masteriyo_settings_api_sanitized_fields_' . $this->id, $this->settings ), 'yes' );
	}

	/**
	 * ############# CRUD getters ###########################
	 */

	/**
	 * Get option name.
	 *
	 * @since 0.10
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_name( $context = 'view' ) {
		return $this->get_prop( 'name', $context );
	}

	/**
	 * Get option value.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context
	 * @return string
	 */
	public function get_value( $context = 'view' ) {
		return $this->get_prop( 'value', $context );
	}

	/**
	 * ############# CRUD setters ###########################
	 */

	 /**
	  * Set option name.
	  *
	  * @since 0.1.0
	  * @param string $name
	  */
	public function set_name( $name ) {
		return $this->set_prop( 'name', $name );
	}

	/**
	 * Set option value.
	*
	* @since 0.1.0
	* @param string $value
	*/
	public function set_value( $value ) {
		return $this->set_prop( 'value', $value );
	}

	/**
	 *
	 * ################ Non-CRUD getters and setters. ###########3
	 */

	/**
	 * Get plugin id.
	 *
	 * @since 0.1.0
	*
	* @return string
	*/
	public function get_plugin_id() {
		return $this->plugin_id;
	}

	/**
	 * Get setting group.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_group() {
		return $this->group;
	}

	/**
	 * Get errors.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_errors() {
		return $this->errors;
	}

	/**
	 * Set group name.
	 *
	 * @since 0.1.0
	 *
	 * @param string $group Group name.
	 * @return void
	 */
	public function set_group( $group ) {
		$group       = trim( $group );
		$this->group = $group;
	}

	/**
	 * Get option full name with group and plugin id..
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_full_name() {
		$full_name  = $this->get_plugin_id() . '.';
		$full_name .= empty( $this->get_group() ) ? '' : $this->get_group() . '.';
		$full_name .= $this->get_name();

		return $full_name;
	}
}
