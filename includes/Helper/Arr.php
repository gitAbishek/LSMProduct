<?php
/**
 * Utility functions.
 *
 * @since 0.1.0
 */

namespace ThemeGrill\Masteriyo\Helper;

class Arr {
	/**
	 * Array merge and sum function.
	 *
	 * Source:  https://gist.github.com/Nickology/f700e319cbafab5eaedc
	 *
	 * @since 0.1.0
	 * @return array
	 */
	public static function merge_recursive_numeric() {
		$arrays = func_get_args();

		// If there's only one array, it's already merged.
		if ( 1 === count( $arrays ) ) {
			return $arrays[0];
		}

		// Remove any items in $arrays that are NOT arrays.
		foreach ( $arrays as $key => $array ) {
			if ( ! is_array( $array ) ) {
				unset( $arrays[ $key ] );
			}
		}

		// We start by setting the first array as our final array.
		// We will merge all other arrays with this one.
		$final = array_shift( $arrays );

		foreach ( $arrays as $b ) {
			foreach ( $final as $key => $value ) {
				// If $key does not exist in $b, then it is unique and can be safely merged.
				if ( ! isset( $b[ $key ] ) ) {
					$final[ $key ] = $value;
				} else {
					// If $key is present in $b, then we need to merge and sum numeric values in both.
					if ( is_numeric( $value ) && is_numeric( $b[ $key ] ) ) {
						// If both values for these keys are numeric, we sum them.
						$final[ $key ] = $value + $b[ $key ];
					} elseif ( is_array( $value ) && is_array( $b[ $key ] ) ) {
						// If both values are arrays, we recursively call ourself.
						$final[ $key ] = wc_array_merge_recursive_numeric( $value, $b[ $key ] );
					} else {
						// If both keys exist but differ in type, then we cannot merge them.
						// In this scenario, we will $b's value for $key is used.
						$final[ $key ] = $b[ $key ];
					}
				}
			}

			// Finally, we need to merge any keys that exist only in $b.
			foreach ( $b as $key => $value ) {
				if ( ! isset( $final[ $key ] ) ) {
					$final[ $key ] = $value;
				}
			}
		}

		return $final;
	}
}
