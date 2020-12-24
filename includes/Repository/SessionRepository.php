<?php
/**
 * Session Repository
 *
 * @since 0.1.0
 * @class Session
 * @package ThemeGrill\Masteriyo\Session
 */

namespace ThemeGrill\Masteriyo\Repository;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\MetaData;
use ThemeGrill\Masteriyo\Repository\RepositoryInterface;

defined( 'ABSPATH' ) || exit;

class SessionRepository implements RepositoryInterface {
	/**
	 * Create a session in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $session session object.
	 */
	public function create( Model &$session ) {
		global $wpdb;

		$session_table = $session->get_table();
		$wpdb->insert(
			"{$session_table}",
			array(
				'data' => maybe_serialize( $session->all() ),
				'expiry' => $session->get_expiry()
			),
			array( '%s', '%d' )
		);
	}

	/**
	 * Delete a session from the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $session Session object.
	 * @param array $args	Array of args to pass.alert-danger
	 */
	public function delete( Model &$session, $args = array() ) {
		global $wpdb;

		if ( $session->get_id() ) {
			$session_table = $session->get_table();
			$wpdb->delte( $session, array( 'id' => $session->get_id() ) );
		}
	}

	/**
	 * Read a session.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $session Cource object.
	 * @throws Exception If invalid session.
	 */
	public function read( Model &$session ) {
		global $wpdb;

		if ( $session->get_id() ) {
			$session_table = $session->get_table();
			$query         = $wpdb->prepare( 'SELECT * FROM %s WHERE id = %s', $session_table, $session->get_id() );
			$result        = $wpdb->get_row( $query, OBJECT ); // phpcs:disable WordPress.DB.PreparedSQL.NotPrepared

			$session->set_props( array(
				'data' => maybe_unserialize( $result->data ),
				'expiry' => $result->expiry
			) );
		}
	}

	/**
	 * Update a session in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $session Session object.
	 *
	 * @return void
	 */
	public function update( Model &$session ) {
		global $wpdb;

		$session_table = $session->get_table();
		$wpdb->update(
			"{$session_table}",
			array(
				'data'   => maybe_serialize( $session->all() ),
				'expiry' => $session->get_expiry()
			),
			array( 'id' => $session->get_id() ),
			array( '%s', '%d' ),
			array( '%s' )
		);
	}

	/**
	 * Returns an array of meta for an object.
	 *
	 * @since 0.1.0
	 *
	 * @param  Model  $model a Model object.
	 * @return MetaData[]
	 */
	public function read_meta( Model &$model ) {

	}

	/**
	 * Deletes meta based on meta ID.
	 *
	 * @since 0.1.0
	 *
	 * @param  Model  $model a Model object.
	 * @param  MetaData  $meta Meta object (containing at least ->id).
	 * @return array
	 */
	public function delete_meta( Model &$model, MetaData $meta ) {

	}

	/**
	 * Add new piece of meta.
	 *
	 * @since 0.1.0
	 *
	 * @param  Model  $model a Model object.
	 * @param  MetaData  $meta Meta object (containing ->key and ->value).
	 * @return int meta ID
	 */
	public function add_meta( Model &$model, MetaData $meta ) {

	}

	/**
	 * Update meta.
	 *
	 * @since 0.1.0
	 *
	 * @param  Model  $model a Model object.
	 * @param  MetaData  $meta Meta object (containing ->id, ->key and ->value).
	 */
	public function update_meta( Model &$model, MetaData $meta ) {

	}
}
