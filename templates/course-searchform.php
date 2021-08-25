<?php

/**
 * The template for displaying course search form
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/course-searchform.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

?>
<form role="search" method="get" class="masteriyo-course-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="masteriyo-course-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>">
		<?php esc_html_e( 'Search for:', 'masteriyo' ); ?>
	</label>
  <span class="icon">
	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 512 512"><path d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128c0-70.7 57.2-128 128-128c70.7 0 128 57.2 128 128c0 70.7-57.2 128-128 128z" fill="currentColor"/></svg>
  </span>
	<input type="search" id="masteriyo-course-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>" class="search-field mto-input" placeholder="<?php echo esc_attr__( 'Search courses&hellip;', 'masteriyo' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	<button type="submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'masteriyo' ); ?>">
		<?php echo esc_html_x( 'Search', 'submit button', 'masteriyo' ); ?>
	</button>
	<input type="hidden" name="post_type" value="course" />
</form>
