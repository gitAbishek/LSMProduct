<?php
/**
 * Utility functions.
 *
 * @since 0.1.0
 */

namespace ThemeGrill\Masteriyo\Helper;

class Utils {

	/**
	 * Wrapper for mb_strtoupper which see's if supported first.
	 *
	 * @since  0.1.0
	 * @param  string $string String to format.
	 * @return string
	 */
	public static function strtoupper( $string ) {
		return function_exists( 'mb_strtoupper' ) ? mb_strtoupper( $string ) : strtoupper( $string );
	}

	/**
	 * Make a string lowercase.
	 * Try to use mb_strtolower() when available.
	 *
	 * @since  0.1.0
	 * @param  string $string String to format.
	 * @return string
	 */
	public static function strtolower( $string ) {
		return function_exists( 'mb_strtolower' ) ? mb_strtolower( $string ) : strtolower( $string );
	}

	/**
	 * Converts a bool to a 'yes' or 'no'.
	 *
	 * @since 0.1.0
	 * @param bool $bool String to convert.
	 * @return string
	 */
	public static function bool_to_string( $bool ) {
		return true === $bool ? 'yes' : 'no';
	}

	/**
	 * Converts a string (e.g. 'yes' or 'no') to a bool.
	 *
	 * @since 0.1.0
	 * @param string $string String to convert.
	 * @return bool
	 */
	public static function string_to_bool( $string ) {
		return is_bool( $string ) ? $string : ( 'yes' === strtolower( $string ) || 1 === $string || 'true' === strtolower( $string ) || '1' === $string );
	}

	/**
	 * Helper to get cached object terms and filter by field using wp_list_pluck().
	 * Works as a cached alternative for wp_get_post_terms() and wp_get_object_terms().
	 *
	 * @since  0.1.0
	 *
	 * @param  int    $object_id Object ID.
	 * @param  string $taxonomy  Taxonomy slug.
	 * @param  string $field     Field name.
	 * @param  string $index_key Index key name.
	 * @return array
	 */
	public static function get_object_terms( $object_id, $taxonomy, $field = null, $index_key = null ) {
		// Test if terms exists. get_the_terms() return false when it finds no terms.
		$terms = get_the_terms( $object_id, $taxonomy );

		if ( ! $terms || is_wp_error( $terms ) ) {
			return array();
		}

		return is_null( $field ) ? $terms : wp_list_pluck( $terms, $field, $index_key );
	}
}
