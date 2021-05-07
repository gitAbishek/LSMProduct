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

		$changes = $setting->get_changes();

		$courses_slugs = array(
			'courses.lessons_slug',
			'courses.quizzes_slug',
			'courses.sections_slug',
			'courses.single_course_permalink',
			'courses.category_base',
			'courses.tag_base',
			'courses.difficulty_base',
		);

		$setting_data = array();
		foreach ( $setting->get_data_keys() as $setting_key ) {
			$callable_setting_key = str_replace( '.', '_', $setting_key );
			if ( is_callable( array( $setting, "get_{$callable_setting_key}" ) ) ) {
				$setting_data[ $setting_key ] = call_user_func( array( $setting, "get_{$callable_setting_key}" ) );
			}
		}

		$data = apply_filters( 'masteriyo_new_setting_data', $setting_data, $setting );

		// Only update the post when the post data changes.
		if ( array_intersect( $setting->get_data_keys(), array_keys( $changes ) ) ) {
			foreach ( $data as $setting_name => $setting_value ) {
				update_option( 'masteriyo.' . $setting_name, $setting_value, false );
			}
		}

		// If courses permalink/slugs changed then update masteriyo_flush_rewrite_rules.
		if ( array_intersect( $courses_slugs, array_keys( $changes ) ) ) {
			update_option( 'masteriyo_flush_rewrite_rules', 'yes' );
		}

		$setting->apply_changes();

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

		$options = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}options WHERE option_name LIKE %s",
				esc_sql( 'masteriyo.%' )
			),
			ARRAY_A
		);

		$setting_data = array();
		foreach ( $options as $option ) {
			$option_arr = explode( '.', $option['option_name'] );
			$group      = count( $option_arr ) > 2 ? $option_arr[1] : '';
			$setting_data[ $group . '_' . $option_arr[2] ] = $option['option_value'];
		}

		$setting->set_props( $setting_data );

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
		foreach ( $setting->get_default_datas() as $setting_name => $setting_value ) {
			update_option( 'masteriyo.' . $setting_name, $setting_value, false );
		}

		$setting->apply_changes();

		do_action( 'masteriyo_reset_setting', $setting );
	}
}
