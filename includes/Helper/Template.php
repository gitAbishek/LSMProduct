<?php
/**
 * Template functions.
 *
 * @since 0.1.0
 */

if ( ! function_exists( 'add_action' ) && function_exists( 'add_filter' ) ) {
	return;
}

/**
 * Handle redirects before content is output - hooked into template_redirect so is_page works.
 *
 * @since 0.1.0
 */
function masteriyo_template_redirect() {
	global $wp_query, $wp;

	// phpcs:disable WordPress.Security.NonceVerification.Recommended
	// When default permalinks are enabled, redirect courses list page to post type archive url.
	if ( ! empty( $_GET['page_id'] ) && '' === get_option( 'permalink_structure' ) && masteriyo_get_page_id( 'courses_list' ) === absint( $_GET['page_id'] ) && get_post_type_archive_link( 'course' ) ) {
		wp_safe_redirect( get_post_type_archive_link( 'course' ) );
		exit;
	}
	// phpcs:enable WordPress.Security.NonceVerification.Recommended

	// Redirect to the course page if we have a single course.
	if ( is_search() && is_post_type_archive( 'course' ) && apply_filters( 'masteriyo_redirect_single_search_result', true ) && 1 === absint( $wp_query->found_posts ) ) {
		$course = masteriyo_get_course( $wp_query->post );

		if ( $course && $course->is_visible() ) {
			wp_safe_redirect( $course->get_permalink(), 302 );
			exit;
		}
	}
}
function_exists( 'add_action' ) && add_action( 'template_redirect', 'masteriyo_template_redirect' );

/**
 * Should the Masteriyo loop be displayed?
 *
 * This will return true if we have posts (courses) or if we have subcats to display.
 *
 * @since 0.1.0
 * @return bool
 */
function masteriyo_course_loop() {
	return have_posts();
}

if ( ! function_exists( 'masteriyo_course_loop_start' ) ) {

	/**
	 * Output the start of a course loop. By default this is a UL.
	 *
	 * @since 0.1.0
	 *
	 * @param bool $echo Should echo?.
	 * @return stringe
	 */
	function masteriyo_course_loop_start( $echo = true ) {
		ob_start();

		masteriyo_set_loop_prop( 'loop', 0 );

		masteriyo_get_template( 'loop/loop-start.php' );

		$loop_start = apply_filters( 'masteriyo_course_loop_start', ob_get_clean() );

		if ( $echo ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $loop_start;
		} else {
			return $loop_start;
		}
	}
}

if ( ! function_exists( 'masteriyo_course_loop_end' ) ) {

	/**
	 * Output the end of a course loop. By default this is a UL.
	 *
	 * @since 0.1.0
	 *
	 * @param bool $echo Should echo?.
	 * @return string
	 */
	function masteriyo_course_loop_end( $echo = true ) {
		ob_start();

		masteriyo_get_template( 'loop/loop-end.php' );

		$loop_end = apply_filters( 'masteriyo_course_loop_end', ob_get_clean() );

		if ( $echo ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $loop_end;
		} else {
			return $loop_end;
		}
	}
}

if ( ! function_exists( 'masteriyo_page_title' ) ) {

	/**
	 * Page Title function.
	 *
	 * @since 0.1.0
	 *
	 * @param  bool $echo Should echo title.
	 * @return string
	 */
	function masteriyo_page_title( $echo = true ) {

		if ( is_search() ) {
			/* translators: %s: search query */
			$page_title = sprintf( __( 'Search results: &ldquo;%s&rdquo;', 'masteriyo' ), get_search_query() );

			if ( get_query_var( 'paged' ) ) {
				/* translators: %s: page number */
				$page_title .= sprintf( __( '&nbsp;&ndash; Page %s', 'masteriyo' ), get_query_var( 'paged' ) );
			}
		} elseif ( is_tax() ) {
			$page_title = single_term_title( '', false );
		} else {
			$courses_list_page_id = masteriyo_get_page_id( 'courses_list' );
			$page_title           = get_the_title( $courses_list_page_id );
		}

		$page_title = apply_filters( 'masteriyo_page_title', $page_title );

		if ( $echo ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $page_title;
		} else {
			return $page_title;
		}
	}
}

/**
 * Sets up the masteriyo_loop global from the passed args or from the main query.
 *
 * @since 0.1.0
 * @param array $args Args to pass into the global.
 */
function masteriyo_setup_loop( $args = array() ) {
	$default_args = array(
		'loop'         => 0,
		'columns'      => masteriyo_get_default_courses_per_row(),
		'name'         => '',
		'is_shortcode' => false,
		'is_paginated' => true,
		'is_search'    => false,
		'is_filtered'  => false,
		'total'        => 0,
		'total_pages'  => 0,
		'per_page'     => 0,
		'current_page' => 1,
	);

	// If this is a main MASTERIYO query, use global args as defaults.
	if ( $GLOBALS['wp_query']->get( 'masteriyo_query' ) ) {
		$default_args = array_merge(
			$default_args,
			array(
				'is_search'    => $GLOBALS['wp_query']->is_search(),
				'is_filtered'  => masteriyo_is_filtered(),
				'total'        => $GLOBALS['wp_query']->found_posts,
				'total_pages'  => $GLOBALS['wp_query']->max_num_pages,
				'per_page'     => $GLOBALS['wp_query']->get( 'posts_per_page' ),
				'current_page' => max( 1, $GLOBALS['wp_query']->get( 'paged', 1 ) ),
			)
		);
	}

	// Merge any existing values.
	if ( isset( $GLOBALS['masteriyo_loop'] ) ) {
		$default_args = array_merge( $default_args, $GLOBALS['masteriyo_loop'] );
	}

	$GLOBALS['masteriyo_loop'] = wp_parse_args( $args, $default_args );
}
function_exists( 'add_action' ) && add_action( 'masteriyo_before_shop_loop', 'masteriyo_setup_loop' );

/**
 * Get the default columns setting - this is how many courses will be shown per row in loops.
 *
 * @since 0
 * @return int
 */
function masteriyo_get_default_courses_per_row() {
	$columns     = get_option( 'masteriyo_catalog_columns', 4 );
	$course_grid = masteriyo_get_theme_support( 'course_grid' );
	$min_columns = isset( $course_grid['min_columns'] ) ? absint( $course_grid['min_columns'] ) : 0;
	$max_columns = isset( $course_grid['max_columns'] ) ? absint( $course_grid['max_columns'] ) : 0;

	if ( $min_columns && $columns < $min_columns ) {
		$columns = $min_columns;
		update_option( 'masteriyo_catalog_columns', $columns );
	} elseif ( $max_columns && $columns > $max_columns ) {
		$columns = $max_columns;
		update_option( 'masteriyo_catalog_columns', $columns );
	}

	$columns = max(1, absint( $columns ) );

	return apply_filters( 'masteriyo_loop_shop_columns', $columns );
}

/**
 * Get the default rows setting - this is how many course rows will be shown in loops.
 *
 * @since 3.3.0
 * @return int
 */
function masteriyo_get_default_course_rows_per_page() {
	$rows        = absint( get_option( 'masteriyo_catalog_rows', 4 ) );
	$course_grid = masteriyo_get_theme_support( 'course_grid' );
	$min_rows    = isset( $course_grid['min_rows'] ) ? absint( $course_grid['min_rows'] ) : 0;
	$max_rows    = isset( $course_grid['max_rows'] ) ? absint( $course_grid['max_rows'] ) : 0;

	if ( $min_rows && $rows < $min_rows ) {
		$rows = $min_rows;
		update_option( 'masteriyo_catalog_rows', $rows );
	} elseif ( $max_rows && $rows > $max_rows ) {
		$rows = $max_rows;
		update_option( 'masteriyo_catalog_rows', $rows );
	}

	return $rows;
}

/**
 * Sets a property in the masteriyo_loop global.
 *
 * @since 3.3.0
 * @param string $prop Prop to set.
 * @param string $value Value to set.
 */
function masteriyo_set_loop_prop( $prop, $value = '' ) {
	if ( ! isset( $GLOBALS['masteriyo_loop'] ) ) {
		masteriyo_setup_loop();
	}
	$GLOBALS['masteriyo_loop'][ $prop ] = $value;
}

/**
 * Gets a property from the masteriyo_loop global.
 *
 * @since 3.3.0
 * @param string $prop Prop to get.
 * @param string $default Default if the prop does not exist.
 * @return mixed
 */
function masteriyo_get_loop_prop( $prop, $default = '' ) {
	masteriyo_setup_loop(); // Ensure shop loop is setup.

	return isset( $GLOBALS['masteriyo_loop'], $GLOBALS['masteriyo_loop'][ $prop ] ) ? $GLOBALS['masteriyo_loop'][ $prop ] : $default;
}

/**
 * When the_post is called, put course data into a global.
 *
 * @param mixed $post Post Object.
 * @return Course
 */
function masteriyo_setup_course_data( $post ) {
	unset( $GLOBALS['course'] );

	if ( is_int( $post ) ) {
		$post = get_post( $post );
	}

	if ( empty( $post->post_type ) || ! in_array( $post->post_type, array( 'course' ), true ) ) {
		return;
	}

	$GLOBALS['course'] = masteriyo_get_course( $post );

	return $GLOBALS['course'];
}
function_exists( 'add_action' ) && add_action( 'the_post', 'masteriyo_setup_course_data' );

/**
 * Add class to the body tag.
 *
 * @since 0.1.0
 *
 * @param string[] $classes An array of body class names.
 * @param string[] $class   An array of additional class names added to the body.
 * @return string[]
 */
function masteriyo_add_body_class( $classes, $class ) {
	if ( masteriyo_is_archive_course_page() ) {
		$classes[] = 'masteriyo-courses-list-page';
	}

	return $classes;
}
function_exists( 'add_filter' ) && add_filter( 'body_class', 'masteriyo_add_body_class', 10, 2 );


if( ! function_exists( 'masteriyo_template_sidebar_enroll_now_button' ) ) {
	/**
	 * Show enroll now button
	 *
	 * @since 0.1.0
	 */
	function masteriyo_template_sidebar_enroll_now_button() {
		global $course;

		$data = array(
			'link'       => untrailingslashit( $course->get_permalink() ) . '?masteriyo-enroll-now=' . $course->get_id(),
			'enroll_now' => apply_filters( 'masteriyo_enroll_now_text', __( 'Enroll Now', 'masteriyo' ) )
		);

		masteriyo_get_template( 'single-course/enroll-now-button.php', $data );
	}
}

if( ! function_exists( 'masteriyo_template_sidebar_row_reviews' ) ) {
	/**
	 * Show row reviews.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_template_sidebar_row_reviews() {
		global $course;

		$data = array(
			'review_count' => $course->get_review_count(),
			'rating'       => $course->get_average_rating(),
			'review_text'  => apply_filters( 'masteriyo_reviews_txt', __( 'reviews', 'masteriyo' ) )
		);

		masteriyo_get_template( 'single-course/sidebar-row-reviews.php', $data );
	}
}

if( ! function_exists( 'masteriyo_template_sidebar_row_categories' ) ) {
	/**
	 * Show course categories.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_template_sidebar_row_categories() {
		global $course;

		$categories = $course->get_categories();

		if ( empty( $categories ) ) {
			return;
		}

		$data = array(
			'categories' => $categories
		);

		masteriyo_get_template( 'single-course/sidebar-row-categories.php', $data );
	}
}


if( ! function_exists( 'masteriyo_template_sidebar_row_enrolled_students' ) ) {
	/**
	 * Show enrolled students.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_template_sidebar_row_enrolled_students() {
		global $course;

		masteriyo_get_template( 'single-course/sidebar-row-enrolled-students.php' );
	}
}

if( ! function_exists( 'masteriyo_template_sidebar_row_hours' ) ) {
	/**
	 * Show course hours.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_template_sidebar_row_hours() {
		global $course;

		$hours = masteriyo_get_lecture_hours( $course );
		if ( empty( $hours ) ) {
			return;
		}

		$data = array(
			'hours' => $hours
		);

		masteriyo_get_template( 'single-course/sidebar-row-hours.php', $data );
	}
}

if( ! function_exists( 'masteriyo_template_sidebar_row_lectures' ) ) {
	/**
	 * Show course lectures.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_template_sidebar_row_lectures() {
		global $course;

		$data = array(
			'lessons_count' => masteriyo_get_lessons_count( $course )
		);

		masteriyo_get_template( 'single-course/sidebar-row-lectures.php', $data );
	}
}


if( ! function_exists( 'masteriyo_template_sidebar_row_difficulty' ) ) {
	/**
	 * Show course difficulty
	 *
	 * @since 0.1.0
	 */
	function masteriyo_template_sidebar_row_difficulty() {
		global $course;

		// Bail early if difficulty is not set.
		$difficulty = $course->get_difficulty()->get_name();
		if( empty( $difficulty ) ) {
			return;
		}

		$data = array(
			'difficulty' => $difficulty
		);

		masteriyo_get_template( 'single-course/sidebar-row-difficulty.php', $data );

	}
}

if( ! function_exists( 'masteriyo_myaccount_sidebar_content' ) ) {
	/**
	 * Show sidebar on myaccount page.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_myaccount_sidebar_content() {
		$data = array(
			'menu_items'       => masteriyo_get_account_menu_items(),
			'user'             => masteriyo_get_current_user(),
			'current_endpoint' => masteriyo_get_current_myaccount_endpoint(),
		);

		masteriyo_get_template( 'myaccount/sidebar-content.php', $data );
	}
}

if( ! function_exists( 'masteriyo_account_courses_endpoint' ) ) {
	/**
	 * Show sidebar on myaccount page.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_account_courses_endpoint() {
		masteriyo_get_template( 'myaccount/my-courses.php' );
	}
}

if( ! function_exists( 'masteriyo_account_edit_profile_endpoint' ) ) {
	/**
	 * Show sidebar on myaccount page.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_account_edit_profile_endpoint() {
		$data = array(
			'user' => masteriyo_get_current_user(),
		);

		masteriyo_get_template( 'myaccount/edit-profile.php', $data );
	}
}

if( ! function_exists( 'masteriyo_account_view_profile_endpoint' ) ) {
	/**
	 * Show sidebar on myaccount page.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_account_view_profile_endpoint() {
		$data = array(
			'user' => masteriyo_get_current_user(),
		);

		masteriyo_get_template( 'myaccount/view-profile.php', $data );
	}
}

if( ! function_exists( 'masteriyo_account_grades_endpoint' ) ) {
	/**
	 * Show sidebar on myaccount page.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_account_grades_endpoint() {
		masteriyo_get_template( 'myaccount/my-grades.php' );
	}
}

if( ! function_exists( 'masteriyo_account_memberships_endpoint' ) ) {
	/**
	 * Show sidebar on myaccount page.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_account_memberships_endpoint() {
		masteriyo_get_template( 'myaccount/my-memberships.php' );
	}
}

if( ! function_exists( 'masteriyo_account_certificates_endpoint' ) ) {
	/**
	 * Show sidebar on myaccount page.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_account_certificates_endpoint() {
		masteriyo_get_template( 'myaccount/my-certificates.php' );
	}
}

if( ! function_exists( 'masteriyo_account_order_history_endpoint' ) ) {
	/**
	 * Show sidebar on myaccount page.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_account_order_history_endpoint() {
		masteriyo_get_template( 'myaccount/my-order-history.php' );
	}
}

if( ! function_exists( 'masteriyo_myaccount_main_content' ) ) {
	/**
	 * Show sidebar on myaccount page.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_myaccount_main_content() {
		$current_endpoint = masteriyo_get_current_myaccount_endpoint();

		if ( has_action( 'masteriyo_account_' . $current_endpoint . '_endpoint' ) ) {
			do_action( 'masteriyo_account_' . $current_endpoint . '_endpoint', $current_endpoint );
			return;
		}

		// No endpoint found? Default to dashboard.
		masteriyo_get_template( 'myaccount/dashboard.php' );
	}
}

