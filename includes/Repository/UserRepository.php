<?php
/**
 * User Repository class.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Repository;
 */

namespace ThemeGrill\Masteriyo\Repository;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Models\User;

/**
 * UserRepository class.
 */
class UserRepository extends AbstractRepository implements RepositoryInterface {

	/**
	 * Meta type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $meta_type = 'user';

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $internal_meta_keys = array(
		'nickname'             => 'nickname',
		'first_name'           => 'first_name',
		'last_name'            => 'last_name',
		'description'          => 'description',
		'rich_editing'         => 'rich_editing',
		'syntax_highlighting'  => 'syntax_highlighting',
		'comment_shortcuts'    => 'comment_shortcuts',
		'use_ssl'              => 'use_ssl',
		'show_admin_bar_front' => 'show_admin_bar_front',
		'locale'               => 'locale',
		'address'              => '_address',
		'city'                 => '_city',
		'state'                => '_state',
		'zip_code'             => '_zip_code',
		'country'              => '_country',
	);

	/**
	 * Create a user in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $user User object.
	 */
	public function create( Model &$user ) {
		$id = wp_insert_user(
			apply_filters(
				'masteriyo_new_user_data',
				array(
					'user_login'           => $user->get_user_login( 'edit' ),
					'user_pass'            => $user->get_user_pass( 'edit' ),
					'user_nicename'        => $user->get_user_nicename( 'edit' ),
					'user_email'           => $user->get_user_email( 'edit' ),
					'user_url'             => $user->get_user_url( 'edit' ),
					'user_activation_key'  => $user->get_user_activation_key( 'edit' ),
					'user_status'          => $user->get_user_status( 'edit' ),
					'display_name'         => $user->get_display_name( 'edit' ),
					'nickname'             => $user->get_nickname( 'edit' ),
					'first_name'           => $user->get_first_name( 'edit' ),
					'last_name'            => $user->get_last_name( 'edit' ),
					'description'          => $user->get_description( 'edit' ),
					'rich_editing'         => $user->get_rich_editing( 'edit' ),
					'syntax_highlighting'  => $user->get_syntax_highlighting( 'edit' ),
					'comment_shortcuts'    => $user->get_comment_shortcuts( 'edit' ),
					'use_ssl'              => $user->get_use_ssl( 'edit' ),
					'show_admin_bar_front' => $user->get_show_admin_bar_front( 'edit' ),
					'locale'               => $user->get_locale( 'edit' ),
					'role'                 => $user->get_roles( 'edit' ),
				),
				$user
			)
		);

		if ( $id && ! is_wp_error( $id ) ) {
			$user->set_id( $id );
			$user->apply_changes();

			do_action( 'masteriyo_new_user', $id, $user );
		}

	}

	/**
	 * Read a user.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $user User object.
	 *
	 * @throws \Exception If invalid user.
	 */
	public function read( Model &$user ) {
		$user_obj = get_user_by( 'id', $user->get_id() );

		if ( ! $user->get_id() || ! $user_obj ) {
			throw new \Exception( __( 'Invalid user.', 'masteriyo' ) );
		}

		$user->set_props(
			array(
				'user_login'          => $user_obj->data->user_login,
				'user_pass'           => $user_obj->data->user_pass,
				'user_nicename'       => $user_obj->data->user_nicename,
				'user_email'          => $user_obj->data->user_email,
				'user_url'            => $user_obj->data->user_url,
				'user_registered'     => $user_obj->data->user_registered,
				'user_activation_key' => $user_obj->data->user_activation_key,
				'user_status'         => $user_obj->data->user_status,
				'display_name'        => $user_obj->data->display_name,
				'roles'               => $user_obj->roles,
				'allcaps'             => $user_obj->allcaps,
			)
		);

		$this->read_user_data( $user );
		$this->read_extra_data( $user );
		$user->set_object_read( true );

		do_action( 'masteriyo_user_read', $user->get_id(), $user );
	}

	/**
	 * Update a user in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $user User object.
	 *
	 * @return void
	 */
	public function update( Model &$user ) {
		$changes        = $user->get_changes();
		$user_data_keys = array(
			'user_login',
			'user_pass',
			'user_nicename',
			'user_email',
			'user_url',
			'user_activation_key',
			'user_status',
			'display_name',
			'nickname',
			'first_name',
			'last_name',
			'description',
			'rich_editing',
			'syntax_highlighting',
			'comment_shortcuts',
			'use_ssl',
			'show_admin_bar_front',
			'locale',
			'roles',
		);

		// Only update the user when the user data changes.
		if ( array_intersect( $user_data_keys, array_keys( $changes ) ) ) {
			$user_data = array(
				'user_login'           => $user->get_user_login( 'edit' ),
				'user_pass'            => $user->get_user_pass( 'edit' ),
				'user_nicename'        => $user->get_user_nicename( 'edit' ),
				'user_email'           => $user->get_user_email( 'edit' ),
				'user_url'             => $user->get_user_url( 'edit' ),
				'user_activation_key'  => $user->get_user_activation_key( 'edit' ),
				'user_status'          => $user->get_user_status( 'edit' ),
				'display_name'         => $user->get_display_name( 'edit' ),
				'nickname'             => $user->get_nickname( 'edit' ),
				'first_name'           => $user->get_first_name( 'edit' ),
				'last_name'            => $user->get_last_name( 'edit' ),
				'description'          => $user->get_description( 'edit' ),
				'rich_editing'         => $user->get_rich_editing( 'edit' ),
				'syntax_highlighting'  => $user->get_syntax_highlighting( 'edit' ),
				'comment_shortcuts'    => $user->get_comment_shortcuts( 'edit' ),
				'use_ssl'              => $user->get_use_ssl( 'edit' ),
				'show_admin_bar_front' => $user->get_show_admin_bar_front( 'edit' ),
				'locale'               => $user->get_locale( 'edit' ),
			);

			wp_update_user( array_merge( array( 'ID' => $user->get_id() ), $user_data ) );
		}

		$this->update_user_meta( $user );
		$user->apply_changes();

		do_action( 'masteriyo_update_user', $user->get_id(), $user );
	}

	/**
	 * Delete a user from the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $user User object.
	 * @param array $args Array of args to pass.alert-danger.
	 */
	public function delete( Model &$user, $args = array() ) {
		$id          = $user->get_id();
		$object_type = $user->get_object_type();
		$args        = array_merge(
			array(
				'reassign' => null,
			),
			$args
		);

		if ( ! $id ) {
			return;
		}

		do_action( 'masteriyo_before_delete_' . $object_type, $id, $user );
		wp_delete_user( $id, $args['reassign'] );

		$user->set_id( 0 );

		do_action( 'masteriyo_after_delete_' . $object_type, $id, $user );
	}

	/**
	 * Read user data. Can be overridden by child classes to load other props.
	 *
	 * @since 0.1.0
	 *
	 * @param User $user User object.
	 */
	protected function read_user_data( &$user ) {
		$id          = $user->get_id();
		$meta_values = $this->read_meta( $user );

		$set_props = array();

		$meta_values = array_reduce( $meta_values, function( $result, $meta_value ) {
			$result[ $meta_value->key ][] = $meta_value->value;
			return $result;
		}, array() );

		foreach ( $this->internal_meta_keys as $prop => $meta_key ) {
			$meta_value         = isset( $meta_values[ $meta_key ][0] ) ? $meta_values[ $meta_key ][0] : null;
			$set_props[ $prop ] = maybe_unserialize( $meta_value ); // get_post_meta only unserializes single values.
		}

		$user->set_props( $set_props );
	}

	/**
	 * Read extra data associated with the user.
	 *
	 * @since 0.1.0
	 *
	 * @param User $user User object.
	 */
	protected function read_extra_data( &$user ) {
		$meta_values = $this->read_meta( $user );

		foreach ( $user->get_extra_data_keys() as $key ) {
			$function = 'set_' . $key;
			if ( is_callable( array( $user, $function ) )
				&& isset( $meta_values[ '_' . $key ] ) ) {
				$user->{$function}( $meta_values[ '_' . $key ] );
			}
		}
	}
}
