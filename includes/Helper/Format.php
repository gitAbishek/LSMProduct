<?php
/**
 * Masteriyo formatting functions.
 *
 * @since 0.1.0
 */

namespace ThemeGrill\Masteriyo\Helper;

use ThemeGrill\Masteriyo\Constants;

defined( 'ABSPATH' ) || exit;

class Format {

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
	public static function decimal( $number, $dp = false, $trim_zeros = false ) {
		$locale   = localeconv();
		$decimals = array( self::get_price_decimal_separator(), $locale['decimal_point'], $locale['mon_decimal_point'] );

		// Remove locale from string.
		if ( ! is_float( $number ) ) {
			$number = str_replace( $decimals, '.', $number );

			// Convert multiple dots to just one.
			$number = preg_replace( '/\.(?![^.]+$)|[^0-9.-]/', '', Utils::clean( $number ) );
		}

		if ( false !== $dp ) {
			$dp     = intval( '' === $dp ? self::get_price_decimals() : $dp );
			$number = number_format( floatval( $number ), $dp, '.', '' );
		} elseif ( is_float( $number ) ) {
			// DP is false - don't use number format, just return a string using whatever is given. Remove scientific notation using sprintf.
			$number = str_replace( $decimals, '.', sprintf( '%.' . self::get_rounding_precision() . 'f', $number ) );
			// We already had a float, so trailing zeros are not needed.
			$trim_zeros = true;
		}

		if ( $trim_zeros && strstr( $number, '.' ) ) {
			$number = rtrim( rtrim( $number, '0' ), '.' );
		}

		return $number;
	}

	/**
	 * Return the decimal separator for prices.
	 *
	 * @since  0.1.0
	 * @return string
	 */
	public static function get_price_decimal_separator() {
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
	public static function get_price_decimals() {
		$num_decimals = get_option( 'masteriyo_price_num_decimals', 2 );
		$num_decimals = apply_filters( 'masteriyo_get_price_decimals', $num_decimals );
		return absint( $num_decimals );
	}

	/**
	 * Format the price with a currency symbol.
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
	public static function price( $price, $args = array() ) {
		$args = apply_filters(
			'masteriyo_price_args',
			wp_parse_args(
				$args,
				array(
					'ex_tax_label'       => false,
					'currency'           => '',
					'decimal_separator'  => self::get_price_decimal_separator(),
					'thousand_separator' => self::get_price_thousand_separator(),
					'decimals'           => self::get_price_decimals(),
					'price_format'       => get_masteriyo_price_format(),
				)
			)
		);

		$unformatted_price = $price;
		$price             = apply_filters( 'raw_masteriyo_price', abs( $price) );
		$price             = number_format( $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] );
		$price             = apply_filters( 'formatted_masteriyo_price', $price, $unformatted_price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] );

		if ( apply_filters( 'masteriyo_price_trim_zeros', false ) && $args['decimals'] > 0 ) {
			$price = wc_trim_zeros( $price );
		}

		$formatted_price = ( $unformatted_price < 0 ? '-' : '' ) . sprintf( $args['price_format'], '<span class="masteriyo-Price-currencySymbol">' . get_masteriyo_currency_symbol( $args['currency'] ) . '</span>', $price );
		$html            = '<span class="masteriyo-Price-amount amount"><bdi>' . $formatted_price . '</bdi></span>';

		if ( $args['ex_tax_label'] && wc_tax_enabled() ) {
			$html .= ' <small class="masteriyo-Price-taxLabel tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
		}

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
	public static function get_price_format() {
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
	 * Get rounding precision for internal WC calculations.
	 *
	 * @since 0.1.0
	 * @return int
	 */
	public static function get_rounding_precision() {
		$precision          = self::get_price_decimals() + 2;
		$rounding_precision = absint( Constants::get( 'MASTERIYO_ROUNDING_PRECISION' ) );

		$precision = ( $rounding_precision > $precision ) ? $rounding_precision : $precision;

		return $precision;
	}
}
