<?php
/**
 * Setting Repository
 */

namespace ThemeGrill\Masteriyo\Repository;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Models\Setting;

class SettingRepository extends AbstractRepository implements RepositoryInterface {
	/**
	 * Create a setting in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $setting Setting object.
	 */
	public function create( Model &$setting ) {
		global $wpdb;

		$data = apply_filters(
			'masteriyo_new_setting_data',
			array(
				'name'  => $setting->get_full_name(),
				'value' => $setting->get_value(),
			),
			$setting
		);

		update_option( $data['name'], $data['value'], false );

		if ( $wpdb->insert_id && ! is_wp_error( $wpdb->insert_id ) ) {
			$setting->set_id( $wpdb->insert_id );
			$setting->apply_changes();

			do_action( 'masteriyo_new_setting', $wpdb->insert_id, $setting );
		}
	}

	/**
	 * Read a setting.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $setting Cource object.
	 * @param mixed $default Default value.
	 *
	 * @throws Exception If invalid setting.
	 */
	public function read( Model &$setting, $default = null ) {
		$value = get_option( $setting->get_name(), $default );

		if ( ! $setting->get_name() ) {
			throw new \Exception( __( 'Invalid setting.', 'masteriyo' ) );
		}

		$setting->set_props( array(
			'name' => $setting->get_name(),
			'value' => $value
		) );

		$setting->set_object_read( true );

		do_action( 'masteriyo_setting_read', $setting->get_id(), $setting );
	}

	/**
	 * Update a setting in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $setting Setting object.
	 *
	 * @return void
	 */
	public function update( Model &$setting ) {
		return new \WP_Error(
			'invalid-method',
			// translators: %s: Class method name.
			sprintf( __( "Method '%s' not implemented.", 'masteriyo' ), __METHOD__ ),
			array( 'status' => 405 )
		);
	}

	/**
	 * Delete a setting from the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $setting Setting object.
	 * @param array $args	Array of args to pass.alert-danger
	 */
	public function delete( Model &$setting, $args = array() ) {
		return new \WP_Error(
			'invalid-method',
			// translators: %s: Class method name.
			sprintf( __( "Method '%s' not implemented.", 'masteriyo' ), __METHOD__ ),
			array( 'status' => 405 )
		);
	}
}
