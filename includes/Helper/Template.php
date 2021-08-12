<?php

/**
 * Template functions.
 *
 * @since 0.1.0
 */

use ThemeGrill\Masteriyo\Query\UserCourseQuery;

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
	if ( ! empty( $_GET['page_id'] ) && '' === get_option( 'permalink_structure' )
		&& masteriyo_get_page_id( 'course-list' ) === absint( $_GET['page_id'] )
		&& get_post_type_archive_link( 'course' ) ) {
			wp_safe_redirect( get_post_type_archive_link( 'course' ) );
			exit;
	}
	// phpcs:enable WordPress.Security.NonceVerification.Recommended

	// When on the checkout with an empty cart, redirect to courses list page.
	if (
		is_page( masteriyo_get_page_id( 'checkout' ) ) && masteriyo_get_page_id( 'checkout' ) !== masteriyo_get_page_id( 'cart' )
		&& masteriyo( 'cart' )->is_empty() && empty( $wp->query_vars['order-pay'] ) && ! isset( $wp->query_vars['order-received'] )
		&& ! is_customize_preview() && apply_filters( 'masteriyo_checkout_redirect_empty_cart', true )
	) {
		wp_safe_redirect( masteriyo_get_course_list_url() );
		exit;
	}

	// Redirect to the course page if we have a single course.
	if (
		is_search() && is_post_type_archive( 'course' )
		&& apply_filters( 'masteriyo_redirect_single_search_result', true ) && 1 === absint( $wp_query->found_posts )
	) {
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
			$course_list_page_id = masteriyo_get_page_id( 'course-list' );
			$page_title          = get_the_title( $course_list_page_id );
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

	// If this is a main Masteriyo query, use global args as defaults.
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
function_exists( 'add_action' ) && add_action( 'masteriyo_before_course_list_loop', 'masteriyo_setup_loop' );

/**
 * Get the default columns setting - this is how many courses will be shown per row in loops.
 *
 * @since 0
 * @return int
 */
function masteriyo_get_default_courses_per_row() {
	$columns     = masteriyo_get_setting( 'course_archive.display.per_row' );
	$course_grid = masteriyo_get_theme_support( 'course_grid' );
	$min_columns = isset( $course_grid['min_columns'] ) ? absint( $course_grid['min_columns'] ) : 0;
	$max_columns = isset( $course_grid['max_columns'] ) ? absint( $course_grid['max_columns'] ) : 0;

	if ( $min_columns && $columns < $min_columns ) {
		$columns = $min_columns;
		update_option( 'masteriyo.courses.per_row', $columns );
	} elseif ( $max_columns && $columns > $max_columns ) {
		$columns = $max_columns;
		update_option( 'masteriyo.courses.per_row', $columns );
	}

	$columns = max( 1, absint( $columns ) );

	return apply_filters( 'masteriyo_loop_course_list_columns', $columns );
}

/**
 * Get the default rows setting - this is how many course rows will be shown in loops.
 *
 * @since 0.1.0
 * @return int
 */
function masteriyo_get_default_course_rows_per_page() {
	 $rows       = masteriyo_get_setting( 'course_archive.display.per_page' );
	$course_grid = masteriyo_get_theme_support( 'course_grid' );
	$min_rows    = isset( $course_grid['min_rows'] ) ? absint( $course_grid['min_rows'] ) : 0;
	$max_rows    = isset( $course_grid['max_rows'] ) ? absint( $course_grid['max_rows'] ) : 0;

	if ( $min_rows && $rows < $min_rows ) {
		$rows = $min_rows;
		update_option( 'masteriyo.courses.per_page', $rows );
	} elseif ( $max_rows && $rows > $max_rows ) {
		$rows = $max_rows;
		update_option( 'masteriyo.courses.per_page', $rows );
	}

	return $rows;
}

/**
 * Sets a property in the masteriyo_loop global.
 *
 * @since 0.1.0
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
 * @since 0.1.0
 * @param string $prop Prop to get.
 * @param string $default Default if the prop does not exist.
 * @return mixed
 */
function masteriyo_get_loop_prop( $prop, $default = '' ) {
	masteriyo_setup_loop(); // Ensure course_list loop is setup.

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
		$classes[] = 'masteriyo-course-list-page';
	} elseif ( isset( $_GET['masteriyo'] ) && 'interactive' === $_GET['masteriyo'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$classes[] = 'masteriyo-interactive-page';
	}

	return $classes;
}
function_exists( 'add_filter' ) && add_filter( 'body_class', 'masteriyo_add_body_class', 10, 2 );


if ( ! function_exists( 'masteriyo_template_enroll_button' ) ) {
	/**
	 * Show enroll now button.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_template_enroll_button( $course ) {
		masteriyo_get_template(
			'enroll-button.php',
			array(
				'course' => $course,
			)
		);
	}
}

if ( ! function_exists( 'masteriyo_myaccount_sidebar_content' ) ) {
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

if ( ! function_exists( 'masteriyo_account_courses_endpoint' ) ) {
	/**
	 * Show courses on myaccount page.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_account_courses_endpoint() {
		$query = new UserCourseQuery(
			array(
				'user_id' => get_current_user_id(),
				'limit'   => -1,
			)
		);

		$user_courses     = $query->get_user_courses();
		$enrolled_courses = array();
		$all_courses      = array();

		foreach ( $user_courses as $user_course ) {
			$course = masteriyo_get_course( $user_course->get_course_id() );

			if ( is_null( $course ) ) {
				continue;
			}

			$course->user_course = $user_course;

			if ( masteriyo_is_current_user_enrolled_in_course( $course ) ) {
				$enrolled_courses[] = $course;
			}
			$all_courses[] = $course;
		}

		masteriyo_get_template(
			'myaccount/courses.php',
			array(
				'enrolled_courses' => $enrolled_courses,
				'all_courses'      => $all_courses,
			)
		);
	}
}

if ( ! function_exists( 'masteriyo_account_edit_myaccount_endpoint' ) ) {
	/**
	 * Edit myaccount on myaccount page.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_account_edit_myaccount_endpoint() {
		$data = array(
			'user' => masteriyo_get_current_user(),
		);

		masteriyo_get_template( 'myaccount/edit-account.php', $data );
	}
}

if ( ! function_exists( 'masteriyo_account_view_myaccount_endpoint' ) ) {
	/**
	 * View profile on myaccount page.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_account_view_myaccount_endpoint() {
		$data = array(
			'user' => masteriyo_get_current_user(),
		);

		masteriyo_get_template( 'myaccount/view-myaccount.php', $data );
	}
}

if ( ! function_exists( 'masteriyo_account_order_history_endpoint' ) ) {
	/**
	 * Show order history on myaccount page.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_account_order_history_endpoint() {
		$orders = masteriyo_get_orders(
			array(
				'customer_id' => get_current_user_id(),
			)
		);

		masteriyo_get_template(
			'myaccount/my-order-history.php',
			array(
				'orders' => $orders,
			)
		);
	}
}

if ( ! function_exists( 'masteriyo_account_view_order_endpoint' ) ) {
	/**
	 * Show order detail on myaccount page.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_account_view_order_endpoint( $order_id ) {
		$order       = masteriyo_get_order( $order_id );
		$customer_id = $order->get_customer_id();

		if ( ! masteriyo_is_current_user_admin() && ! masteriyo_is_current_user_manager() && get_current_user_id() !== $customer_id ) {
			echo __( 'You are not allowed to view this content', 'masteriyo' );
			return;
		}

		$notes                 = $order->get_customer_order_notes();
		$order_items           = $order->get_items( 'course' );
		$show_purchase_note    = $order->has_status( apply_filters( 'masteriyo_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
		$show_customer_details = masteriyo_is_current_user_admin() || ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() );

		masteriyo_get_template(
			'myaccount/view-order.php',
			array(
				'order'                 => $order,
				'notes'                 => $notes,
				'order_items'           => $order_items,
				'show_purchase_note'    => $show_purchase_note,
				'show_customer_details' => $show_customer_details,
			)
		);
	}
}

if ( ! function_exists( 'masteriyo_myaccount_main_content' ) ) {
	/**
	 * Handle myaccount page's main content.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_myaccount_main_content() {
		$endpoint         = masteriyo_get_current_myaccount_endpoint();
		$current_endpoint = $endpoint['endpoint'];

		if ( has_action( 'masteriyo_account_' . $current_endpoint . '_endpoint' ) ) {
			do_action( 'masteriyo_account_' . $current_endpoint . '_endpoint', $endpoint['arg'] );
			return;
		}

		// No endpoint found? Default to dashboard.
		masteriyo_get_template( 'myaccount/dashboard.php', $endpoint );
	}
}

if ( ! function_exists( 'masteriyo_email_header' ) ) {
	/**
	 * Get the email header.
	 *
	 * @param mixed $email_heading Heading for the email.
	 */
	function masteriyo_email_header( $email_heading ) {
		masteriyo_get_template( 'emails/email-header.php', array( 'email_heading' => $email_heading ) );
	}
}

if ( ! function_exists( 'masteriyo_email_footer' ) ) {
	/**
	 * Get the email footer.
	 */
	function masteriyo_email_footer() {
		masteriyo_get_template( 'emails/email-footer.php' );
	}
}

if ( ! function_exists( 'masteriyo_single_course_featured_image' ) ) {
	/**
	 * Show course featured image.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_single_course_featured_image() {
		global $course;

		masteriyo_get_template(
			'single-course/featured-image.php',
			array(
				'course' => $course,
			)
		);
	}
}

if ( ! function_exists( 'masteriyo_single_course_categories' ) ) {
	/**
	 * Show course categories.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_single_course_categories() {
		global $course;

		masteriyo_get_template(
			'single-course/categories.php',
			array(
				'course' => $course,
			)
		);
	}
}

if ( ! function_exists( 'masteriyo_single_course_title' ) ) {
	/**
	 * Show course title.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_single_course_title() {
		global $course;

		masteriyo_get_template(
			'single-course/title.php',
			array(
				'course' => $course,
			)
		);
	}
}

if ( ! function_exists( 'masteriyo_single_course_author_and_rating' ) ) {
	/**
	 * Show course author and rating.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_single_course_author_and_rating() {
		global $course;

		masteriyo_get_template(
			'single-course/author-and-rating.php',
			array(
				'course' => $course,
				'author' => masteriyo_get_user( $course->get_author_id() ),
			)
		);
	}
}

if ( ! function_exists( 'masteriyo_single_course_price_and_enroll_button' ) ) {
	/**
	 * Show course price and enroll button.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_single_course_price_and_enroll_button() {
		global $course;

		masteriyo_get_template(
			'single-course/price-and-enroll-button.php',
			array(
				'course' => $course,
			)
		);
	}
}

if ( ! function_exists( 'masteriyo_single_course_stats' ) ) {
	/**
	 * Show course stats in single course page.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_single_course_stats() {
		global $course;

		$comments_count = masteriyo_count_course_comments( $course );

		masteriyo_get_template(
			'single-course/course-stats.php',
			array(
				'course'               => $course,
				'comments_count'       => $comments_count,
				'enrolled_users_count' => masteriyo_count_enrolled_users( $course->get_id() ),
			)
		);
	}
}

if ( ! function_exists( 'masteriyo_single_course_highlights' ) ) {
	/**
	 * Show course highlights in single course page.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_single_course_highlights() {
		global $course;

		masteriyo_get_template(
			'single-course/highlights.php',
			array(
				'course' => $course,
			)
		);
	}
}

if ( ! function_exists( 'masteriyo_single_course_main_content' ) ) {
	/**
	 * Show course main content in single course page.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_single_course_main_content() {
		global $course;

		masteriyo_get_template(
			'single-course/main-content.php',
			array(
				'course' => $course,
			)
		);
	}
}

if ( ! function_exists( 'masteriyo_single_course_tab_handles' ) ) {
	/**
	 * Show tab handles in single course page.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_single_course_tab_handles() {
		global $course;

		masteriyo_get_template(
			'single-course/tab-handles.php',
			array(
				'course' => $course,
			)
		);
	}
}

if ( ! function_exists( 'masteriyo_single_course_overview' ) ) {
	/**
	 * Show course overview in single course page.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_single_course_overview() {
		global $course;

		masteriyo_get_template(
			'single-course/overview.php',
			array(
				'course' => $course,
			)
		);
	}
}

if ( ! function_exists( 'masteriyo_single_course_curriculum' ) ) {
	/**
	 * Show course curriculum in single course page.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_single_course_curriculum() {
		global $course;

		if ( $course->get_show_curriculum() || masteriyo_can_start_course( $course ) ) {
			$dict = masteriyo_make_section_to_lessons_dictionary( $course );

			masteriyo_get_template(
				'single-course/curriculum.php',
				array(
					'course'     => $course,
					'sections'   => $dict['sections'],
					'lessons'    => $dict['lessons'],
					'dictionary' => $dict['lessons_dictionary'],
				)
			);
		}

	}
}

if ( ! function_exists( 'masteriyo_single_course_reviews' ) ) {
	/**
	 * Show course reviews in single course page.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_single_course_reviews() {
		global $course;

		if ( masteriyo_get_setting( 'single_course.display.enable_review' ) ) {

			$reviews_and_replies = masteriyo_get_course_reviews_and_replies( $course );

			masteriyo_get_template(
				'single-course/reviews.php',
				array(
					'course'         => $course,
					'course_reviews' => $reviews_and_replies['reviews'],
					'replies'        => $reviews_and_replies['replies'],
					'pp_placeholder' => masteriyo_get_course_review_author_pp_placeholder(),
				)
			);
		}
	}
}

if ( ! function_exists( 'masteriyo_single_course_review_form' ) ) {
	/**
	 * Show course review form in single course page.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_single_course_review_form() {
		global $course;

		masteriyo_get_template(
			'single-course/course-review-form.php',
			array(
				'course' => $course,
			)
		);
	}
}

if ( ! function_exists( 'masteriyo_checkout_order_summary' ) ) {
	/**
	 * Display billing form.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_checkout_order_summary() {
		$cart = masteriyo( 'cart' );
		if ( is_null( $cart ) ) {
			return;
		}

		$courses = array();

		if ( ! $cart->is_empty() ) {
			foreach ( $cart->get_cart_contents() as $cart_content ) {
				if ( ! isset( $cart_content['course_id'] ) ) {
					continue;
				}

				$course = masteriyo_get_course( $cart_content['course_id'] );
				if ( is_null( $course ) ) {
					continue;
				}

				$courses[] = $course;
			}
		}

		$data = array(
			'cart'    => $cart,
			'courses' => $courses,
		);

		masteriyo_get_template( 'checkout/order-summary.php', $data );
	}
}

if ( ! function_exists( 'masteriyo_checkout_payment' ) ) {
	/**
	 * Display checkout form payement.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function masteriyo_checkout_payment() {
		 $available_gateways = array();

		if ( masteriyo( 'cart' )->needs_payment() ) {
			$available_gateways = masteriyo( 'payment-gateways' )->get_available_payment_gateways();
		}

		// translators: Cart total order
		$order_button_text = apply_filters( 'masteriyo_order_button_text', __( 'Confirm Payment', 'masteriyo' ) );

		masteriyo_get_template(
			'checkout/payment.php',
			array(
				'checkout'           => masteriyo( 'checkout' ),
				'available_gateways' => $available_gateways,
				'order_button_text'  => $order_button_text,
			)
		);
	}
}

if ( ! function_exists( 'masteriyo_display_item_meta' ) ) {
	/**
	 * Display item meta data.
	 *
	 * @since  0.1.0
	 * @param  OrderItem $item Order Item.
	 * @param  array         $args Arguments.
	 * @return string|void
	 */
	function masteriyo_display_item_meta( $item, $args = array() ) {
		$strings = array();
		$html    = '';
		$args    = wp_parse_args(
			$args,
			array(
				'before'       => '<ul class="masteriyo-item-meta"><li>',
				'after'        => '</li></ul>',
				'separator'    => '</li><li>',
				'echo'         => true,
				'autop'        => false,
				'label_before' => '<strong class="masteriyo-item-meta-label">',
				'label_after'  => ':</strong> ',
			)
		);

		foreach ( $item->get_formatted_meta_data() as $meta_id => $meta ) {
			$value     = $args['autop'] ? wp_kses_post( $meta->display_value ) : wp_kses_post( make_clickable( trim( $meta->display_value ) ) );
			$strings[] = $args['label_before'] . wp_kses_post( $meta->display_key ) . $args['label_after'] . $value;
		}

		if ( $strings ) {
			$html = $args['before'] . implode( $args['separator'], $strings ) . $args['after'];
		}

		$html = apply_filters( 'masteriyo_display_item_meta', $html, $item, $args );

		if ( $args['echo'] ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $html;
		} else {
			return $html;
		}
	}
}

if ( ! function_exists( 'masteriyo_archive_navigation' ) ) {

	/**
	 * Display course archive navigation.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_archive_navigation() {
		masteriyo_get_template( 'course-pagination.php' );
	}
}
if ( ! function_exists( 'masteriyo_get_course_search_form' ) ) {

	/**
	 * Display course search form.
	 *
	 * Will first attempt to locate the course-searchform.php file in either the child or.
	 * the parent, then load it. If it doesn't exist, then the default search form.
	 * will be displayed.
	 *
	 * The default searchform uses html5.
	 *
	 * @since 0.1.0
	 * @param bool $echo (default: true).
	 * @return string
	 */
	function masteriyo_get_course_search_form( $echo = true ) {
		global $course_search_form_index;

		ob_start();

		if ( empty( $course_search_form_index ) ) {
			$course_search_form_index = 0;
		}

		do_action( 'before_masteriyo_get_course_search_form' );

		masteriyo_get_template(
			'course-searchform.php',
			array(
				'index' => $course_search_form_index++,
			)
		);

		$search_form = apply_filters( 'masteriyo_get_course_search_search_form', ob_get_clean() );

		if ( ! $echo ) {
			return $search_form;
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $search_form;
	}
}

if ( ! function_exists( 'masteriyo_course_search_form' ) ) {

	/**
	 * Course Search Form.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_course_search_form() {       ?>
		<div class="course-search main-search">
			<?php masteriyo_get_course_search_form(); ?>
		</div>
		<?php
	}
	function_exists( 'add_action' ) && add_action( 'masteriyo_before_main_content', 'masteriyo_course_search_form' );
}

if ( ! function_exists( 'masteriyo_email_order_details' ) ) {
	/**
	 * Show order details.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_email_order_details( $order, $email = null ) {
		masteriyo_get_template(
			'emails/order-details.php',
			array(
				'order' => $order,
				'email' => $email,
			)
		);
	}
}

if ( ! function_exists( 'masteriyo_email_order_meta' ) ) {
	/**
	 * Show order metas.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_email_order_meta( $order = false ) {
		$fields = apply_filters( 'masteriyo_email_order_meta_fields', array(), $order );

		/**
		 * Deprecated masteriyo_email_order_meta_keys filter.
		 *
		 * @since 2.3.0
		 */
		$_fields = apply_filters( 'masteriyo_email_order_meta_keys', array() );

		if ( $_fields ) {
			foreach ( $_fields as $key => $field ) {
				if ( is_numeric( $key ) ) {
					$key = $field;
				}

				$fields[ $key ] = array(
					'label' => wptexturize( $key ),
					'value' => wptexturize( get_post_meta( $order->get_id(), $field, true ) ),
				);
			}
		}

		if ( $fields ) {
			foreach ( $fields as $field ) {
				if ( isset( $field['label'] ) && isset( $field['value'] ) && $field['value'] ) {
					echo '<p><strong>' . $field['label'] . ':</strong> ' . $field['value'] . '</p>'; // WPCS: XSS ok.
				}
			}
		}
	}
}

if ( ! function_exists( 'masteriyo_email_customer_addresses' ) ) {
	/**
	 * Show order metas.
	 *
	 * @since 0.1.0
	 */
	function masteriyo_email_customer_addresses( $order, $email = null ) {
		masteriyo_get_template(
			'emails/customer-addresses.php',
			array(
				'order' => $order,
				'email' => $email,
			)
		);
	}
}

if ( ! function_exists( 'masteriyo_get_email_order_items' ) ) {
	/**
	 * Get HTML for the order items to be shown in emails.
	 *
	 * @param Order $order Order object.
	 * @param array $args Arguments.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	function masteriyo_get_email_order_items( $order, $args = array() ) {
		ob_start();

		$defaults = array(
			'show_sku'   => false,
			'show_image' => false,
			'image_size' => array( 32, 32 ),
		);

		$args = wp_parse_args( $args, $defaults );

		masteriyo_get_template(
			'emails/order-items.php',
			apply_filters(
				'masteriyo_email_order_items_args',
				array(
					'order'              => $order,
					'items'              => $order->get_items(),
					'show_sku'           => $args['show_sku'],
					'show_purchase_note' => $order->is_paid(),
					'show_image'         => $args['show_image'],
					'image_size'         => $args['image_size'],
				)
			)
		);

		return apply_filters( 'masteriyo_email_order_items_table', ob_get_clean(), $order );
	}
}

if ( ! function_exists( 'masteriyo_display_item_meta' ) ) {
	/**
	 * Display item meta data.
	 *
	 * @since  0.1.0
	 * @param  OrderItem $item Order Item.
	 * @param  array         $args Arguments.
	 * @return string|void
	 */
	function masteriyo_display_item_meta( $item, $args = array() ) {
		$strings = array();
		$html    = '';
		$args    = wp_parse_args(
			$args,
			array(
				'before'       => '<ul class="masteriyo-item-meta"><li>',
				'after'        => '</li></ul>',
				'separator'    => '</li><li>',
				'echo'         => true,
				'autop'        => false,
				'label_before' => '<strong class="masteriyo-item-meta-label">',
				'label_after'  => ':</strong> ',
			)
		);

		foreach ( $item->get_formatted_meta_data() as $meta_id => $meta ) {
			$value     = $args['autop'] ? wp_kses_post( $meta->display_value ) : wp_kses_post( make_clickable( trim( $meta->display_value ) ) );
			$strings[] = $args['label_before'] . wp_kses_post( $meta->display_key ) . $args['label_after'] . $value;
		}

		if ( $strings ) {
			$html = $args['before'] . implode( $args['separator'], $strings ) . $args['after'];
		}

		$html = apply_filters( 'masteriyo_display_item_meta', $html, $item, $args );

		if ( $args['echo'] ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $html;
		} else {
			return $html;
		}
	}
}
