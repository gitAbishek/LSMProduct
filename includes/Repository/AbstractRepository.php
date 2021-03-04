<?php
/**
 * Handle meta table functionality.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Classes
 */

namespace ThemeGrill\Masteriyo\Repository;

use ThemeGrill\Masteriyo\MetaData;
use ThemeGrill\Masteriyo\Helper\Utils;

defined( 'ABSPATH' ) || exit;

abstract class AbstractRepository {

	/**
	 * Meta type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $meta_type = 'post';

	/**
	 * This only needs set if you are using a custom metadata type (for example payment tokens.
	 * This should be the name of the field your table uses for associating meta with objects.
	 * For example, in payment_tokenmeta, this would be payment_token_id.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $object_id_field_for_meta = '';

	/**
	 * Data stored in meta keys, but not considered "meta" for an object.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $internal_meta_keys = array();

	/**
	 * Meta data which should exist in the DB, even if empty.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $must_exist_meta_keys = array();

	/**
	 * Data stored in separate lookup table.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $internal_lookup_keys = array();

	/**
	 * If we have already saved our extra data, don't do automatic / default handling.
	 *
	 * @since 0.1.0
	 *
	 * @var bool
	 */
	protected $extra_data_saved = false;

	/**
	 * Stores updated props.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $updated_props = array();

	/**
	 * Deletes a meta based on meta ID.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $model		Model object.
	 * @param MetaData $meta	MetaData object.
	 *
	 * @return void
	 */
	public function delete_meta( &$model, $meta ) {
		// TODO Abstract the delete_metadata_by_mid().
		delete_metadata_by_mid( $this->meta_type, $meta->id );
	}

	/**
	 * Add new piece of meta.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $model		Model object.
	 * @param MetaData $meta	MetaData object.
	 *
	 * @return int meta ID.
	 */
	public function add_meta( &$model, $meta  ) {
		return add_metadata( $this->meta_type, $model->get_id(), $meta->key, $meta->value, false );
	}

	/**
	 * Update meta.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $mode		Model object.
	 * @param MetaData $meta	MetaData object.
	 *
	 * @return void
	 */
	public function update_meta( &$model, $meta ) {
		update_metadata_by_mid( $this->meta_type, $meta->id, $meta->value, $meta->key );
	}

	/**
	 * Read meta.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $model Model object.
	 *
	 * @return MetaData[]
	 */
	public function read_meta( &$model ) {
		// TODO Abstract global $wpdb;
		global $wpdb;

		$meta_table_info = $this->get_meta_table_info();

		$raw_meta_data = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT {$meta_table_info['meta_id_field']} as  meta_id, meta_key, meta_value
				FROM {$meta_table_info['table']}
				WHERE {$meta_table_info['object_id_field']} = %d
				ORDER BY {$meta_table_info['meta_id_field']}",
				$model->get_id()
			)
		);

		$meta_data = array_map( function( $meta_data ) {
			return new MetaData( array(
				'id'    => $meta_data->meta_id,
				'key'   => $meta_data->meta_key,
				'value' => maybe_unserialize( $meta_data->meta_value )
			) );
		}, $raw_meta_data );

		$this->internal_meta_keys = array_merge( $model->get_data_keys(), $this->internal_meta_keys );
		return apply_filters( "masteriyo_repository_{$this->meta_type}_read_meta", $meta_data, $model, $this );
	}

	/**
	 * Update meta data in, or delete it from, the database.
	 *
	 * Avoids storing meta when it's either an empty string or empty array.
	 * Other empty values such as numeric 0 and null should still be stored.
	 * Data-stores can force meta to exist using `must_exist_meta_keys`.
	 *
	 * Note: WordPress `get_metadata` function returns an empty string when meta data does not exist.
	 *
	 * @since 0.1.0 Added to prevent empty meta being stored unless required.
	 *
	 * @param Model $object The WP_Data object (WC_Coupon for coupons, etc).
	 * @param string  $meta_key Meta key to update.
	 * @param mixed   $meta_value Value to save.
	 *
	 *
	 * @return bool True if updated/deleted.
	 */
	protected function update_or_delete_post_meta( $object, $meta_key, $meta_value ) {
		if ( in_array( $meta_value, array( array(), '' ), true ) && ! in_array( $meta_key, $this->get_must_exist_meta_keys(), true ) ) {
			$updated = delete_post_meta( $object->get_id(), $meta_key );
		} else {
			$updated = update_post_meta( $object->get_id(), $meta_key, $meta_value );
		}

		return (bool) $updated;
	}

	/**
	 * Update meta data in, or delete it from, the database.
	 *
	 * Avoids storing meta when it's either an empty string or empty array.
	 * Other empty values such as numeric 0 and null should still be stored.
	 * Data-stores can force meta to exist using `must_exist_meta_keys`.
	 *
	 * Note: WordPress `get_metadata` function returns an empty string when meta data does not exist.
	 *
	 * @since 0.1.0 Added to prevent empty meta being stored unless required.
	 *
	 * @param Model $object The WP_Data object (WC_Coupon for coupons, etc).
	 * @param string  $meta_key Meta key to update.
	 * @param mixed   $meta_value Value to save.
	 *
	 *
	 * @return bool True if updated/deleted.
	 */
	protected function update_or_delete_term_meta( $object, $meta_key, $meta_value ) {
		if ( in_array( $meta_value, array( array(), '' ), true ) && ! in_array( $meta_key, $this->get_must_exist_meta_keys(), true ) ) {
			$updated = delete_term_meta( $object->get_id(), $meta_key );
		} else {
			$updated = update_term_meta( $object->get_id(), $meta_key, $meta_value );
		}

		return (bool) $updated;
	}

	/**
	 * Returns meta table info.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	protected function get_meta_table_info() {
		// TODO Abstract wpdb class.
		global $wpdb;

		$meta_id_field = 'meta_id';
		$table         = $wpdb->prefix;

		// If we are dealing with a type of metadata that is not a core type, the table should be prefixed.
		if ( ! in_array( $this->meta_type, array( 'post', 'user', 'comment', 'term' ), true ) ) {
			$table .= 'masteriyo_';
		}

		$table          .= $this->meta_type . 'meta';
		$object_id_field = $this->meta_type . '_id';

		// Figure out our field names.
		if ( 'user' === $this->meta_type ) {
			$meta_id_field = 'umeta_id';
			$table         = $wpdb->usermeta;
		}

		if ( ! empty( $this->object_id_field_for_meta ) ) {
			$object_id_field = $this->object_id_field_for_meta;
		}

		return array(
			'table'           => $table,
			'object_id_field' => $object_id_field,
			'meta_id_field'   => $meta_id_field,
		);
	}

	/**
	 * Retrieve stopwords used when parsing search terms.
	 *
	 * @since 0.1.0
	 *
	 * @return array Stopwords.
	 */
	protected function get_search_stopwords() {
		// Translators: This is a comma-separated list of very common words that should be excluded from a search, like a, an, and the. These are usually called "stopwords". You should not simply translate these individual words into your language. Instead, look for and provide commonly accepted stopwords in your language.
		$stopwords = array_map(
			array( Utils::class, 'strtolower' ),
			array_map(
				'trim',
				explode(
					',',
					_x(
						'about,an,are,as,at,be,by,com,for,from,how,in,is,it,of,on,or,that,the,this,to,was,what,when,where,who,will,with,www',
						'Comma-separated list of search stopwords in your language',
						'masteriyo'
					)
				)
			)
		);

		return apply_filters( 'wp_search_stopwords', $stopwords );
	}

	/**
	 * Get and store terms from a taxonomy.
	 *
	 * @since  0.1.0
	 * @param  Model|integer $model Model model or model ID.
	 * @param  string          $taxonomy Taxonomy name e.g. model_cat.
	 * @return array of terms
	 */
	protected function get_term_ids( $model, $taxonomy ) {
		if ( is_numeric( $model ) ) {
			$model_id = $model;
		} else {
			$model_id = $model->get_id();
		}
		$terms = get_the_terms( $model_id, $taxonomy );
		if ( false === $terms || is_wp_error( $terms ) ) {
			return array();
		}
		return wp_list_pluck( $terms, 'term_id' );
	}

	/**
	 * Check if the terms are suitable for searching.
	 *
	 * Uses an array of stopwords (terms) that are excluded from the separate
	 * term matching when searching for posts. The list of English stopwords is
	 * the approximate search engines list, and is translatable.
	 *
	 * @since 0.1.0
	 *
	 * @param array $terms Terms to check.
	 *
	 * @return array Terms that are not stopwords.
	 */
	protected function get_valid_search_terms( $terms ) {
		$valid_terms = array();
		$stopwords   = $this->get_search_stopwords();

		foreach ( $terms as $term ) {
			// keep before/after spaces when term is for exact match, otherwise trim quotes and spaces.
			if ( preg_match( '/^".+"$/', $term ) ) {
				$term = trim( $term, "\"'" );
			} else {
				$term = trim( $term, "\"' " );
			}

			// Avoid single A-Z and single dashes.
			if ( empty( $term ) || ( 1 === strlen( $term ) && preg_match( '/^[a-z\-]$/i', $term ) ) ) {
				continue;
			}

			if ( in_array( Utils::strtolower( $term ), $stopwords, true ) ) {
				continue;
			}

			$valid_terms[] = $term;
		}

		return $valid_terms;
	}

	/**
	 * Internal meta keys we don't want exposed as part of meta_data. This is in
	 * addition to all data props with _ prefix.
	 *
	 * @since 0.1.0
	 *
	 * @param string $key Prefix to be added to meta keys.
	 *
	 * @return string
	 */
	protected function prefix_key( $key ) {
		return '_' === substr( $key, 0, 1 ) ? $key : '_' . $key;
	}

	/**
	 * Get data to save to a lookup table.
	 *
	 * @since 0.1.0
	 *
	 * @param int    $id ID of object to update.
	 * @param string $table Lookup table name.
	 *
	 * @return array
	 */
	protected function get_data_for_lookup_table( $id, $table ) {
		return array();
	}

	/**
	 * Get primary key name for lookup table.
	 *
	 * @since 0.1.0
	 *
	 * @param string $table Lookup table name.
	 *
	 * @return string
	 */
	protected function get_primary_key_for_lookup_table( $table ) {
		return '';
	}

	/**
	 * Gets a list of props and meta keys that need updated based on change state
	 * or if they are present in the database or not.
	 *
	 * @since 0.1.0
	 *
	 * @param  Model   $model               The Model model.
	 * @param  array   $meta_key_to_props   A mapping of meta keys => prop names.
	 * @param  string  $meta_type           The internal WP meta type (post, user, etc).
	 * @return array                        A mapping of meta keys => prop names, filtered by ones that should be updated.
	 */
	protected function get_props_to_update( $model, $meta_key_to_props, $meta_type = 'post' ) {
		$props_to_update = array();
		$changed_props   = $model->get_changes();

		// Props should be updated if they are a part of the $changed array or don't exist yet.
		foreach ( $meta_key_to_props as $meta_key => $prop ) {
			if ( array_key_exists( $prop, $changed_props )
				|| ! metadata_exists( $meta_type, $model->get_id(), $meta_key ) ) {
				$props_to_update[ $meta_key ] = $prop;
			}
		}

		return $props_to_update;
	}

	/**
	 * Update a lookup table for an object.
	 *
	 * @since 0.1.0
	 *
	 * @param int    $id ID of object to update.
	 * @param string $table Lookup table name.
	 *
	 * @return NULL
	 */
	protected function update_lookup_table( $id, $table ) {
		global $wpdb;

		$id    = absint( $id );
		$table = sanitize_key( $table );

		if ( empty( $id ) || empty( $table ) ) {
			return false;
		}

		$existing_data = wp_cache_get( 'lookup_table', 'model_' . $id );
		$update_data   = $this->get_data_for_lookup_table( $id, $table );

		if ( ! empty( $update_data ) && $update_data !== $existing_data ) {
			$wpdb->replace(
				$wpdb->$table,
				$update_data
			);
			wp_cache_set( 'lookup_table', $update_data, 'model_' . $id );
		}
	}

	/**
	 * Delete lookup table data for an ID.
	 *
	 * @since 0.1.0
	 *
	 * @param int    $id ID of model to update.
	 * @param string $table Lookup table name.
	 */
	public function delete_from_lookup_table( $id, $table ) {
		global $wpdb;

		$id    = absint( $id );
		$table = sanitize_key( $table );

		if ( empty( $id ) || empty( $table ) ) {
			return false;
		}

		$pk = $this->get_primary_key_for_lookup_table( $table );

		$wpdb->delete(
			$wpdb->$table,
			array(
				$pk => $id,
			)
		);

		wp_cache_delete( 'lookup_table', 'model_' . $id );
	}


	/**
	 * Helper method that updates all the post meta for a model based on it's settings in the Model class.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $model model object.
	 * @param bool  $force Force update. Used during create.
	 */
	protected function update_post_meta( &$model, $force = false ) {
		// Make sure to take extra data into account.
		$extra_data_keys = $model->get_extra_data_keys();

		foreach ( $extra_data_keys as $key ) {
			$meta_key_to_props[ '_' . $key ] = $key;
		}

		if ( $force ) {
			$props_to_update = $this->get_internal_meta_keys();
		} else {
			$props_to_update =  $this->get_props_to_update( $model, $this->get_internal_meta_keys() );
		}

		foreach ( $props_to_update as $prop => $meta_key ) {
			$value = $model->{"get_$prop"}( 'edit' );
			$value = is_string( $value ) ? wp_slash( $value ) : $value;
			switch ( $prop ) {
				case 'featured':
					$value = Utils::bool_to_string( $value );
					break;
			}

			$updated = $this->update_or_delete_post_meta( $model, $meta_key, $value );

			if ( $updated ) {
				$this->updated_props[] = $prop;
			}
		}

		// Update extra data associated with the model like button text or model URL for external models.
		if ( ! $this->extra_data_saved ) {
			foreach ( $extra_data_keys as $key ) {
				$meta_key = '_' . $key;
				$function = 'get_' . $key;
				if ( ! array_key_exists( $meta_key, $props_to_update ) ) {
					continue;
				}
				if ( is_callable( array( $model, $function ) ) ) {
					$value   = $model->{$function}( 'edit' );
					$value   = is_string( $value ) ? wp_slash( $value ) : $value;
					$updated = $this->update_or_delete_post_meta( $model, $meta_key, $value );

					if ( $updated ) {
						$this->updated_props[] = $key;
					}
				}
			}
		}
	}

	/**
	 * Helper method that updates all the post meta for a model based on it's settings in the Model class.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $model model object.
	 * @param bool  $force Force update. Used during create.
	 */
	protected function update_term_meta( &$model, $force = false ) {
		// Make sure to take extra data into account.
		$extra_data_keys = $model->get_extra_data_keys();

		foreach ( $extra_data_keys as $key ) {
			$meta_key_to_props[ '_' . $key ] = $key;
		}

		if ( $force ) {
			$props_to_update = $this->get_internal_meta_keys();
		} else {
			$props_to_update =  $this->get_props_to_update( $model, $this->get_internal_meta_keys() );
		}

		foreach ( $props_to_update as $meta_key => $prop ) {
			$value = $model->{"get_$prop"}( 'edit' );
			$value = is_string( $value ) ? wp_slash( $value ) : $value;
			switch ( $prop ) {
				case 'featured':
					$value = Utils::bool_to_string( $value );
					break;
			}

			$updated = $this->update_or_delete_term_meta( $model, $meta_key, $value );

			if ( $updated ) {
				$this->updated_props[] = $prop;
			}
		}

		// Update extra data associated with the model like button text or model URL for external models.
		if ( ! $this->extra_data_saved ) {
			foreach ( $extra_data_keys as $key ) {
				$meta_key = '_' . $key;
				$function = 'get_' . $key;
				if ( ! array_key_exists( $meta_key, $props_to_update ) ) {
					continue;
				}
				if ( is_callable( array( $model, $function ) ) ) {
					$value   = $model->{$function}( 'edit' );
					$value   = is_string( $value ) ? wp_slash( $value ) : $value;
					$updated = $this->update_or_delete_post_meta( $model, $meta_key, $value );

					if ( $updated ) {
						$this->updated_props[] = $key;
					}
				}
			}
		}
	}

	/**
	 * Get internal meta keys.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	protected function get_internal_meta_keys() {
		return $this->internal_meta_keys;
	}

	/**
	 * Get must exist meta keys.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	protected function get_must_exist_meta_keys() {
		return $this->must_exist_meta_keys;
	}

	/**
	 * Get lookup data keys.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	protected function get_internal_lookup_keys() {
		return $this->internal_lookup_keys;
	}
}
