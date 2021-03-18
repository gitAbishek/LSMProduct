<?php
/**
 * Masteriyo formatting functions.
 *
 * @since 0.1.0
 */

use ThemeGrill\Masteriyo\Constants;

defined( 'ABSPATH' ) || exit;

/**
 * Converts a string (e.g. 'yes' or 'no') to a bool.
 *
 * @since 0.1.0
 * @param string|bool $string String to convert. If a bool is passed it will be returned as-is.
 * @return bool
 */
function masteriyo_string_to_bool( $string ) {
	if ( is_bool( $string ) ) {
		return $string;
	}

	$string = strtolower( $string );

	return ( 'yes' === $string || 1 === $string || 'true' === $string || '1' === $string );
}

/**
 * Converts a bool to a 'yes' or 'no'.
 *
 * @since 0.1.0
 * @param bool|string $bool Bool to convert. If a string is passed it will first be converted to a bool.
 * @return string
 */
function masteriyo_bool_to_string( $bool ) {
	if ( ! is_bool( $bool ) ) {
		$bool = masteriyo_string_to_bool( $bool );
	}
	return true === $bool ? 'yes' : 'no';
}

/**
 * Convert a date string to a DateTime.
 *
 * @since  0.1.0
 * @param  string $time_string Time string.
 * @return ThemeGrill\Masteriyo\DateTime
 */
function masteriyo_string_to_datetime( $time_string ) {
	// Strings are defined in local WP timezone. Convert to UTC.
	if ( 1 === preg_match( '/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})(Z|((-|\+)\d{2}:\d{2}))$/', $time_string, $date_bits ) ) {
		$offset    = ! empty( $date_bits[7] ) ? iso8601_timezone_to_offset( $date_bits[7] ) : masteriyo_timezone_offset();
		$timestamp = gmmktime( $date_bits[4], $date_bits[5], $date_bits[6], $date_bits[2], $date_bits[3], $date_bits[1] ) - $offset;
	} else {
		$timestamp = masteriyo_string_to_timestamp( get_gmt_from_date( gmdate( 'Y-m-d H:i:s', masteriyo_string_to_timestamp( $time_string ) ) ) );
	}
	$datetime = new DateTime( "@{$timestamp}", new DateTimeZone( 'UTC' ) );

	// Set local timezone or offset.
	if ( get_option( 'timezone_string' ) ) {
		$datetime->setTimezone( new DateTimeZone( masteriyo_timezone_string() ) );
	} else {
		$datetime->set_utc_offset( masteriyo_timezone_offset() );
	}

	return $datetime;
}

/**
 * Get timezone offset in seconds.
 *
 * @since  0.1.0
 * @return float
 */
function masteriyo_timezone_offset() {
	$timezone = get_option( 'timezone_string' );

	if ( $timezone ) {
		$timezone_object = new DateTimeZone( $timezone );
		return $timezone_object->getOffset( new DateTime( 'now' ) );
	} else {
		return floatval( get_option( 'gmt_offset', 0 ) ) * HOUR_IN_SECONDS;
	}
}

/**
 * Convert mysql datetime to PHP timestamp, forcing UTC. Wrapper for strtotime.
 *
 * Based on masteriyos_strtotime_dark_knight() from MASTERIYO Subscriptions by Prospress.
 *
 * @since  0.1.0
 * @param  string   $time_string    Time string.
 * @param  int|null $from_timestamp Timestamp to convert from.
 * @return int
 */
function masteriyo_string_to_timestamp( $time_string, $from_timestamp = null ) {
	$original_timezone = date_default_timezone_get();

	// @codingStandardsIgnoreStart
	date_default_timezone_set( 'UTC' );

	if ( null === $from_timestamp ) {
		$next_timestamp = strtotime( $time_string );
	} else {
		$next_timestamp = strtotime( $time_string, $from_timestamp );
	}

	date_default_timezone_set( $original_timezone );
	// @codingStandardsIgnoreEnd

	return $next_timestamp;
}


/**
 * Masteriyo Timezone - helper to retrieve the timezone string for a site until.
 * a WP core method exists (see https://core.trac.wordpress.org/ticket/24730).
 *
 * Adapted from https://secure.php.net/manual/en/function.timezone-name-from-abbr.php#89155.
 *
 * @since 0.1.0
 * @return string PHP timezone string for the site
 */
function masteriyo_timezone_string() {
	// Added in WordPress 5.3 Ref https://developer.wordpress.org/reference/functions/wp_timezone_string/.
	if ( function_exists( 'wp_timezone_string' ) ) {
		return wp_timezone_string();
	}

	// If site timezone string exists, return it.
	$timezone = get_option( 'timezone_string' );
	if ( $timezone ) {
		return $timezone;
	}

	// Get UTC offset, if it isn't set then return UTC.
	$utc_offset = floatval( get_option( 'gmt_offset', 0 ) );
	if ( ! is_numeric( $utc_offset ) || 0.0 === $utc_offset ) {
		return 'UTC';
	}

	// Adjust UTC offset from hours to seconds.
	$utc_offset = (int) ( $utc_offset * 3600 );

	// Attempt to guess the timezone string from the UTC offset.
	$timezone = timezone_name_from_abbr( '', $utc_offset );
	if ( $timezone ) {
		return $timezone;
	}

	// Last try, guess timezone string manually.
	foreach ( timezone_abbreviations_list() as $abbr ) {
		foreach ( $abbr as $city ) {
			// WordPress restrict the use of date(), since it's affected by timezone settings, but in this case is just what we need to guess the correct timezone.
			if ( (bool) date( 'I' ) === (bool) $city['dst'] && $city['timezone_id'] && intval( $city['offset'] ) === $utc_offset ) { // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
				return $city['timezone_id'];
			}
		}
	}

	// Fallback to UTC.
	return 'UTC';
}

/**
 * Format decimal numbers ready for DB storage.
 *
 * Sanitize, remove decimals, and optionally round + trim off zeros.
 *
 * This function does not remove thousands - this should be done before passing a value to the function.
 *
 * @since 0.1.0
 *
 * @param  float|string $number     Expects either a float or a string with a decimal separator only (no thousands).
 * @param  mixed        $dp number  Number of decimal points to use, blank to use masteriyo_price_num_decimals, or false to avoid all rounding.
 * @param  bool         $trim_zeros From end of string.
 * @return string
 */
function masteriyo_format_decimal( $number, $dp = false, $trim_zeros = false ) {
	$locale   = localeconv();
	$decimals = array( masteriyo_get_price_decimal_separator(), $locale['decimal_point'], $locale['mon_decimal_point'] );

	// Remove locale from string.
	if ( ! is_float( $number ) ) {
		$number = str_replace( $decimals, '.', $number );

		// Convert multiple dots to just one.
		$number = preg_replace( '/\.(?![^.]+$)|[^0-9.-]/', '', masteriyo_clean( $number ) );
	}

	if ( false !== $dp ) {
		$dp     = intval( '' === $dp ? masteriyo_get_price_decimals() : $dp );
		$number = number_format( floatval( $number ), $dp, '.', '' );
	} elseif ( is_float( $number ) ) {
		// DP is false - don't use number format, just return a string using whatever is given. Remove scientific notation using sprintf.
		$number = str_replace( $decimals, '.', sprintf( '%.' . masteriyo_get_rounding_precision() . 'f', $number ) );
		// We already had a float, so trailing zeros are not needed.
		$trim_zeros = true;
	}

	if ( $trim_zeros && strstr( $number, '.' ) ) {
		$number = rtrim( rtrim( $number, '0' ), '.' );
	}

	return $number;
}

/**
 * Return the thousand separator for prices.
 *
 * @since  0.1.0
 * @return string
 */
function masteriyo_get_price_thousand_separator() {
	$separator = get_option( 'masteriyo_price_thousand_sep' );
	$separator = apply_filters( 'masteriyo_get_price_thousand_separator', $separator );
	return stripslashes( $separator );
}

/**
 * Return the decimal separator for prices.
 *
 * @since  0.1.0
 * @return string
 */
function masteriyo_get_price_decimal_separator() {
	$separator = get_option( 'masteriyo_price_decimal_sep' );
	$separator = apply_filters( 'masteriyo_get_price_decimal_separator', $separator );
	return $separator ? stripslashes( $separator ) : '.';
}

/**
 * Return the number of decimals after the decimal point.
 *
 * @since  0.1.0
 * @return int
 */
function masteriyo_get_price_decimals() {
	$num_decimals = get_option( 'masteriyo_price_num_decimals', 2 );
	$num_decimals = apply_filters( 'masteriyo_get_price_decimals', $num_decimals );
	return absint( $num_decimals );
}

/**
 * Format the price with a currency symbol.
 *
 * @since 0.1.0
 *
 * @param  float $price Raw price.
 * @param  array $args  Arguments to format a price {
 *     Array of arguments.
 *     Defaults to empty array.
 *
 *     @type bool   $ex_tax_label       Adds exclude tax label.
 *                                      Defaults to false.
 *     @type string $currency           Currency code.
 *                                      Defaults to empty string (Use the result from get_masteriyo_currency()).
 *     @type string $decimal_separator  Decimal separator.
 *                                      Defaults the result of get_price_decimal_separator().
 *     @type string $thousand_separator Thousand separator.
 *                                      Defaults the result of get_price_thousand_separator().
 *     @type string $decimals           Number of decimals.
 *                                      Defaults the result of get_price_decimals().
 *     @type string $price_format       Price format depending on the currency position.
 *                                      Defaults the result of get_masteriyo_price_format().
 * }
 * @return string
 */
function masteriyo_price( $price, $args = array() ) {
	$args = apply_filters(
		'masteriyo_price_args',
		wp_parse_args(
			$args,
			array(
				'currency'           => '',
				'decimal_separator'  => masteriyo_get_price_decimal_separator(),
				'thousand_separator' => masteriyo_get_price_thousand_separator(),
				'decimals'           => masteriyo_get_price_decimals(),
				'price_format'       => masteriyo_get_price_format(),
			)
		)
	);

	$unformatted_price = $price;
	$price             = apply_filters( 'masteriyo_raw_price', abs( $price) );
	$price             = number_format( $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] );
	$price             = apply_filters( 'masteriyo_formatted_price', $price, $unformatted_price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] );

	if ( apply_filters( 'masteriyo_price_trim_zeros', false ) && $args['decimals'] > 0 ) {
		$price = masteriyo_trim_zeros( $price );
	}

	$formatted_price = ( $unformatted_price < 0 ? '-' : '' ) . sprintf( $args['price_format'], '<span class="masteriyo-price-currencySymbol">' . masteriyo_get_currency_symbol( $args['currency'] ) . '</span>', $price );
	$html            = '<span class="masteriyo-price-amount amount"><bdi>' . $formatted_price . '</bdi></span>';


	/**
	 * Filters the string of price markup.
	 *
	 * @since 0.1.0
	 *
	 * @param string $html              Price HTML markup.
	 * @param string $price             Formatted price.
	 * @param array  $args              Pass on the args.
	 * @param float  $unformatted_price Price as float to allow plugins custom formatting.
	 */
	return apply_filters( 'masteriyo_price', $html, $price, $args, $unformatted_price );
}

/**
 * Get the price format depending on the currency position.
 *
 * @since 0.1.0
 *
 * @return string
 */
function masteriyo_get_price_format() {
	$currency_pos = get_option( 'masteriyo_currency_pos' );
	$format       = '%1$s%2$s';

	switch ( $currency_pos ) {
		case 'left':
			$format = '%1$s%2$s';
			break;
		case 'right':
			$format = '%2$s%1$s';
			break;
		case 'left_space':
			$format = '%1$s&nbsp;%2$s';
			break;
		case 'right_space':
			$format = '%2$s&nbsp;%1$s';
			break;
	}

	return apply_filters( 'masteriyo_price_format', $format, $currency_pos );
}

/**
 * Get rounding precision for internal MASTERIYO calculations.
 *
 * @since 0.1.0
 * @return int
 */
function masteriyo_get_rounding_precision() {
	$precision          = masteriyo_get_price_decimals() + 2;
	$rounding_precision = absint( Constants::get( 'MASTERIYO_ROUNDING_PRECISION' ) );

	$precision = ( $rounding_precision > $precision ) ? $rounding_precision : $precision;

	return $precision;
}

/**
 * Trim trailing zeros off prices.
 *
 * @since 0.1.0
 *
 * @param string|float|int $price Price.
 * @return string
 */
function masteriyo_trim_zeros( $price ) {
	return preg_replace( '/' . preg_quote( masteriyo_get_price_decimal_separator(), '/' ) . '0++$/', '', $price );
}

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 *
 * @since 0.1.0
 *
 * @param string|array $var Data to sanitize.
 * @return string|array
 */
function masteriyo_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'masteriyo_clean', $var );
	} else {
		return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
	}
}


/**
 * Masteriyo Date Format - Allows to change date format for everything Masteriyo.
 *
 * @since 0.1.0
 *
 * @return string
 */
function masteriyo_date_format() {
	$date_format = get_option( 'date_format' );
	if ( empty( $date_format ) ) {
		// Return default date format if the option is empty.
		$date_format = 'F j, Y';
	}
	return apply_filters( 'masteriyo_date_format', $date_format );
}

/**
 * Masteriyo Time Format - Allows to change time format for everything Masteriyo.
 *
 * @since 0.1.0
 *
 * @return string
 */
function masteriyo_time_format() {
	$time_format = get_option( 'time_format' );
	if ( empty( $time_format ) ) {
		// Return default time format if the option is empty.
		$time_format = 'g:i a';
	}
	return apply_filters( 'masteriyo_time_format', $time_format );
}

/**
 * Sanitize permalink values before insertion into DB.
 *
 * Cannot use masteriyo_clean because it sometimes strips % chars and breaks the user's setting.
 *
 * @since  0.1.0
 * @param  string $value Permalink.
 * @return string
 */
function masteriyo_sanitize_permalink( $value ) {
	global $wpdb;

	$value = $wpdb->strip_invalid_text_for_column( $wpdb->options, 'option_value', $value );

	if ( is_wp_error( $value ) ) {
		$value = '';
	}

	$value = esc_url_raw( trim( $value ) );
	$value = str_replace( 'http://', '', $value );
	return untrailingslashit( $value );
}
