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
		$posted_setting = $setting->get_data();
		$setting_in_db  = get_option( 'masteriyo_settings', array() );

		// if courses permalink / slugs changed then update masteriyo_flush_rewrite_rules .
		$should_update_permalink = false;
		foreach ( $posted_setting['advance']['permalinks'] as $permalink => $value ) {
			if ( ! isset( $setting_in_db['advance']['permalinks'][ $permalink ] ) ) {
				$should_update_permalink = true;
				break;
			}

			if ( $value !== $setting_in_db['advance']['permalinks'][ $permalink ] ) {
				$should_update_permalink = true;
				break;
			}
		}

		if ( $should_update_permalink ) {
			update_option( 'masteriyo_flush_rewrite_rules', 'yes' );
		}

		$setting_in_db = wp_parse_args( $posted_setting, $setting_in_db );

		$setting->set_data( $setting_in_db );

		update_option( 'masteriyo_settings', $setting->get_data() );

		do_action( 'masteriyo_new_setting', $setting );
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
		global $wpdb;

		$setting_in_db = get_option( 'masteriyo_settings', array() );
		$setting_in_db = wp_parse_args( $setting_in_db, $setting->get_data() );

		$setting->set_data( $setting_in_db );

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
	 * @param array $args   Array of args to pass.alert-danger
	 */
	public function delete( Model &$setting, $args = array() ) {

		// Resetting to default data.
		foreach ( $setting->get_default_data( true ) as $setting_name => $setting_value ) {
			update_option( 'masteriyo.' . $setting_name, $setting_value, false );
		}

		$setting->apply_changes();

		do_action( 'masteriyo_reset_setting', $setting );
	}
}
