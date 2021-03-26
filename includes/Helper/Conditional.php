<?php
/**
 * Conditional functions.
 *
 * @since 0.1.0
 */

if ( ! function_exists( 'masteriyo_is_filtered' ) ) {
	/**
	 * masteriyo_Is_filtered - Returns true when filtering products using layered nav or price sliders.
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	function masteriyo_is_filtered() {
		return apply_filters( 'masteriyo__is_filtered',  false);
	}
}
