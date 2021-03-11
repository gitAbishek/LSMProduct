<?php
/**
 * Core functions.
 *
 * @since 0.1.0
 */

use ThemeGrill\Masteriyo\DateTime;
use ThemeGrill\Masteriyo\Constants;
use ThemeGrill\Masteriyo\Models\Course;

/**
 * Get course.
 *
 * @since 0.1.0
 *
 * @param int|Course|WP_Post $course Course id or Course Model or Post.
 * @return Course|null
 */
function masteriyo_get_course( $course ) {
	$course_obj   = masteriyo( 'course' );
	$course_store = masteriyo( 'course.store' );

	if ( is_a( $course, 'ThemeGrill\Masteriyo\Models\Course' ) ) {
		$id = $course->get_id();
	} elseif ( is_a( $course, 'WP_Post' ) ) {
		$id = $course->ID;
	} else {
		$id = $course;
	}

	try {
		$id = absint( $id );
		$course_obj->set_id( $id );
		$course_store->read( $course_obj );
	} catch ( \Exception $e) {
		return null;
	}

	return apply_filters( 'masteriyo_get_course', $course_obj, $course );
}

/**
 * Get lesson.
 *
 * @since 0.1.0
 *
 * @param int|Lesson|WP_Post $lesson Lesson id or Lesson Model or Post.
 * @return Lesson|null
 */
function masteriyo_get_lesson( $lesson ) {
	$lesson_obj   = masteriyo( 'lesson' );
	$lesson_store = masteriyo( 'lesson.store' );

	if ( is_a( $lesson, 'ThemeGrill\Masteriyo\Models\Lesson' ) ) {
		$id = $lesson->get_id();
	} elseif ( is_a( $lesson, 'WP_Post' ) ) {
		$id = $lesson->ID;
	} else {
		$id = $lesson;
	}

	try {
		$id = absint( $id );
		$lesson_obj->set_id( $id );
		$lesson_store->read( $lesson_obj );
	} catch ( \Exception $e) {
		return null;
	}
	return apply_filters( 'masteriyo_get_lesson', $lesson_obj, $lesson );

}

/**
 * Get section.
 *
 * @since 0.1.0
 *
 * @param int|Section|WP_Post $section Section id or Section Model or Post.
 * @return Section|null
 */
function masteriyo_get_section( $section ) {
	$section_obj   = masteriyo( 'section' );
	$section_store = masteriyo( 'section.store' );

	if ( is_a( $section, 'ThemeGrill\Masteriyo\Models\Section' ) ) {
		$id = $section->get_id();
	} elseif ( is_a( $section, 'WP_Post' ) ) {
		$id = $section->ID;
	} else {
		$id = $section;
	}

	try {
		$id = absint( $id );
		$section_obj->set_id( $id );
		$section_store->read( $section_obj );
	} catch ( \Exception $e) {
		return null;
	}

	return apply_filters( 'masteriyo_get_section', $section_obj, $section );
}

/**
 * Get sections.
 *
 * @since 0.1.0
 *
 * @param array $args Query arguments.
 *
 * @return object|array[Section]
 */
function masteriyo_get_sections( $args = array() ) {
	$sections = masteriyo( 'query.sections' )->set_args( $args )->get_sections();

	return apply_filters( 'masteriyo_get_sections', $sections, $args );
}

/**
 * Get lessons.
 *
 * @since 0.1.0
 *
 * @param array $args Query arguments.
 *
 * @return object|array[Lesson]
 */
function masteriyo_get_lessons( $args = array() ) {
	$lessons = masteriyo( 'query.lessons' )->set_args( $args )->get_lessons();

	return apply_filters( 'masteriyo_get_lessons', $lessons, $args );
}

/**
 * Get quizes.
 *
 * @since 0.1.0
 *
 * @param array $args Query arguments.
 *
 * @return object|array[Quiz]
 */
function masteriyo_get_quizes( $args = array() ) {
	$quizes = masteriyo( 'query.quizes' )->set_args( $args )->get_quizes();

	return apply_filters( 'masteriyo_get_quizes', $quizes, $args );
}

/**
 * Get questions
 *
 * @since 0.1.0
 *
 * @param array $args Query arguments.
 *
 * @return object|array[Question]
 */
function masteriyo_get_questions( $args = array() ) {
	$questions = masteriyo( 'query.questions' )->set_args( $args )->get_questions();

	return apply_filters( 'masteriyo_get_questions', $questions, $args );
}

/**
 * Get quiz.
 *
 * @since 0.1.0
 *
 * @param int|Quiz|WP_Post $quiz Quiz id or Quiz Model or Post.
 * @return Quiz|null
 */
function masteriyo_get_quiz( $quiz ) {
	$quiz_obj   = masteriyo( 'quiz' );
	$quiz_store = masteriyo( 'quiz.store' );

	if ( is_a( $quiz, 'ThemeGrill\Masteriyo\Models\Quiz' ) ) {
		$id = $quiz->get_id();
	} elseif ( is_a( $quiz, 'WP_Post' ) ) {
		$id = $quiz->ID;
	} else {
		$id = $quiz;
	}

	try {
		$id = absint( $id );
		$quiz_obj->set_id( $id );
		$quiz_store->read( $quiz_obj );
	} catch ( \Exception $e) {
		return null;
	}

	return apply_filters( 'masteriyo_get_quiz', $quiz_obj, $quiz );
}

/**
 * Get order.
 *
 * @since 0.1.0
 *
 * @param int|Order|WP_Post $order Order id or Order Model or Post.
 * @return Order|null
 */
function masteriyo_get_order( $order ) {
	$order_obj   = masteriyo( 'order' );
	$order_store = masteriyo( 'order.store' );

	if ( is_a( $order, 'ThemeGrill\Masteriyo\Models\Order' ) ) {
		$id = $order->get_id();
	} elseif ( is_a( $order, 'WP_Post' ) ) {
		$id = $order->ID;
	} else {
		$id = $order;
	}

	try {
		$id = absint( $id );
		$order_obj->set_id( $id );
		$order_store->read( $order_obj );
	} catch ( \Exception $e) {
		return null;
	}
	return apply_filters( 'masteriyo_get_order', $order_obj, $order );
}

/**
 * Get question.
 *
 * @since 0.1.0
 *
 * @param int|Question|WP_Post $question Question id or Question Model or Post.
 * @return Question|null
 */
function masteriyo_get_question( $question ) {
	if ( is_int( $question ) ) {
		$id = $question;
	} else {
		$id = is_a( $question, '\WP_Post' ) ? $question->ID : $question->get_id();
	}
	$type     = get_post_meta( $id, '_type', true );
	$question_obj = masteriyo( "question.${type}" );
	$question_store = masteriyo( 'question.store' );

	if ( is_a( $question, 'ThemeGrill\Masteriyo\Models\Question' ) ) {
		$id = $question->get_id();
	} elseif ( is_a( $question, 'WP_Post' ) ) {
		$id = $question->ID;
	} else {
		$id = $question;
	}

	try {
		$id = absint( $id );
		$question_obj->set_id( $id );
		$question_store->read( $question_obj );
	} catch ( \Exception $e) {
		return null;
	}

	return apply_filters( 'masteriyo_get_question', $question_obj, $question );
}

/**
 * Get course category.
 *
 * @since 0.1.0
 *
 * @param int|CourseCategory|WP_Term $course_cat Course Category id or Course Category Model or Term.
 * @return CourseCategory|null
 */
function masteriyo_get_course_cat( $course_cat ) {
	$course_cat_obj   = masteriyo( 'course_cat' );
	$course_cat_store = masteriyo( 'course_cat.store' );

	if ( is_a( $course_cat, 'ThemeGrill\Masteriyo\Models\CourseCategory' ) ) {
		$id = $course_cat->get_id();
	} elseif ( is_a( $course_cat, 'WP_Term' ) ) {
		$id = $course_cat->term_id;
	} else {
		$id = $course_cat;
	}

	try {
		$id = absint( $id );
		$course_cat_obj->set_id( $id );
		$course_cat_store->read( $course_cat_obj );
	} catch ( \Exception $e) {
		return null;
	}

	return apply_filters( 'masteriyo_get_course_cat', $course_cat_obj, $course_cat );
}

/**
 * Get course tag.
 *
 * @since 0.1.0
 *
 * @param int|CourseTag|WP_Term $course_tag Course Tag id or Course Tag Model or Term.
 * @return CourseTag|null
 */
function masteriyo_get_course_tag( $course_tag ) {
	$course_tag_obj   = masteriyo( 'course_tag' );
	$course_tag_store = masteriyo( 'course_tag.store' );

	if ( is_a( $course_tag, 'ThemeGrill\Masteriyo\Models\CourseTag' ) ) {
		$id = $course_tag->get_id();
	} elseif ( is_a( $course_tag, 'WP_Term' ) ) {
		$id = $course_tag->term_id;
	} else {
		$id = $course_tag;
	}

	try {
		$id = absint( $id );
		$course_tag_obj->set_id( $id );
		$course_tag_store->read( $course_tag_obj );
	} catch ( \Exception $e) {
		return null;
	}

	return apply_filters( 'masteriyo_get_course_tag', $course_tag_obj, $course_tag );
}

/**
 * Get course difficulty.
 *
 * @since 0.1.0
 *
 * @param int|CourseDifficulty|WP_Term $course_difficulty Course Difficulty id or Course Difficulty Model or Term.
 * @return CourseDifficulty|null
 */
function masteriyo_get_course_difficulty( $course_difficulty ) {
	$course_difficulty_obj   = masteriyo( 'course_difficulty' );
	$course_difficulty_store = masteriyo( 'course_difficulty.store' );

	if ( is_a( $course_difficulty, 'ThemeGrill\Masteriyo\Models\CourseDifficulty' ) ) {
		$id = $course_difficulty->get_id();
	} elseif ( is_a( $course_difficulty, 'WP_Term' ) ) {
		$id = $course_difficulty->term_id;
	} else {
		$id = $course_difficulty;
	}

	try {
		$id = absint( $id );
		$course_difficulty_obj->set_id( $id );
		$course_difficulty_store->read( $course_difficulty_obj );
	} catch ( \Exception $e) {
		return null;
	}

	return apply_filters( 'masteriyo_get_course_difficulty', $course_difficulty_obj, $course_difficulty );
}

/**
 * Get user.
 *
 * @since 0.1.0
 *
 * @param int|User|WP_User $user User  id or User Model or WP+User.
 * @return User|null
 */
function masteriyo_get_user( $user ) {
	$user_obj   = masteriyo( 'user' );
	$user_store = masteriyo( 'user.store' );

	if ( is_a( $user, 'ThemeGrill\Masteriyo\Models\User' ) ) {
		$id = $user->get_id();
	} elseif ( is_a( $user, 'WP_User' ) ) {
		$id = $user->ID;
	} else {
		$id = $user;
	}

	try {
		$id = absint( $id );
		$user_obj->set_id( $id );
		$user_store->read( $user_obj );
	} catch ( \Exception $e) {
		return null;
	}

	return apply_filters( 'masteriyo_get_user', $user_obj, $user );
}

/**
 * Get template part.
 *
 * MASTERIYO_TEMPLATE_DEBUG_MODE will prevent overrides in themes from taking priority.
 *
 * @since 0.1.0
 *
 * @param mixed  $slug Template slug.
 * @param string $name Template name (default: '').
 */
function masteriyo_get_template_part( $slug, $name = '' ) {
	return masteriyo( 'template' )->get_part( $slug, $name );
}

/**
 * Get other templates and include the file.
 *
 * @since 0.1.0
 *
 * @param string $template_name Template name.
 * @param array  $args          Arguments. (default: array).
 * @param string $template_path Template path. (default: '').
 * @param string $default_path  Default path. (default: '').
 */
function masteriyo_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	return masteriyo( 'template' )->get( $template_name, $args, $template_path, $default_path );
}

/**
 * Like get_template, but returns the HTML instead of outputting.
 *
 * @since 0.1.0
 *
 * @see get_template
 *
 * @param string $template_name Template name.
 * @param array  $args          Arguments. (default: array).
 * @param string $template_path Template path. (default: '').
 * @param string $default_path  Default path. (default: '').
 *
 * @return string
 */
function masteriyo_get_template_html( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	return masteriyo( 'template' )->get_html( $template_name, $args, $template_path, $default_path );
}

/**
 * Add a template to the template cache.
 *
 * @since 0.1.0
 *
 * @param string $cache_key Object cache key.
 * @param string $template Located template.
 */
function masteriyo_set_template_cache( $cache_key, $template ) {
	return masteriyo( 'template' )->set_cache( $cache_key, $template );
}

/**
 * Get template cache.
 *
 * @since 0.1.0
 *
 * @param string $cache_key Object cache key.
 *
 * @return string
 */
function masteriyo_get_template_cache( $cache_key ) {
	return masteriyo( 'template' )->get_cache( $cache_key );
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 * yourtheme/$template_path/$template_name
 * yourtheme/$template_name
 * $default_path/$template_name
 *
 * @since 0.1.0
 *
 * @param string $template_name Template name.
 * @param string $template_path Template path. (default: '').
 * @param string $default_path  Default path. (default: '').
 *
 * @return string
 */
function masteriyo_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	return masteriyo( 'template' )->locate( $template_name, $template_path, $default_path );
}

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
 * Based on wcs_strtotime_dark_knight() from WC Subscriptions by Prospress.
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
 * Retrieve page ids - used for pages like course list. returns -1 if no page is found.
 *
 * @since 0.1.0
 *
 * @param string $page Page slug.
 *
 * @return int
 */
function masteriyo_get_page_id( $page ) {
	$setting = masteriyo( 'setting' );

	$setting->set_name( 'masteriyo_' . $page . '_page_id' );

	masteriyo( 'setting.store' )->read( $setting );

	$page = apply_filters( 'masteriyo_get_' . $page . '_page_id', $setting->get_value() );

	return $page ? absint( $page ) : -1;
}

/**
 * Retrieve page permalink.
 *
 * @param string      $page page slug.
 * @param string|bool $fallback Fallback URL if page is not set. Defaults to home URL.
 *
 * @return string
 */
function masteriyo_get_page_permalink( $page, $fallback = null ) {
	$page_id   = masteriyo_get_page_id( $page );
	$permalink = 0 < $page_id ? get_permalink( $page_id ) : '';

	if ( ! $permalink ) {
		$permalink = is_null( $fallback ) ? get_home_url() : $fallback;
	}

	return apply_filters( 'masteriyo_get_' . $page . '_page_permalink', $permalink );
}

/**
 * Check if the current page is a single course page.
 *
 * @since 0.1.0
 *
 * @return boolean
 */
function mto_is_single_course_page() {
	return is_singular( 'course' );
}

/**
 * Get image asset URL.
 *
 * @since 0.1.0
 *
 * @param string $file Image file name.
 *
 * @return string
 */
function mto_img_url( $file ) {
	$plugin_dir = plugin_dir_url( Constants::get('MASTERIYO_PLUGIN_FILE') );

	return "{$plugin_dir}assets/img/{$file}";
}

/**
 * Put course data into a global.
 *
 * @param int|Course|WP_Post $course_id Course id or Course object or course wp post.
 *
 * @return Course
 */
function masteriyo_setup_course_data( $course_id = null ) {
	$course = masteriyo_get_course( $course_id );
	$cats = array_map( 'masteriyo_get_course_cat', $course->get_category_ids() );
	$tags = array_map( 'masteriyo_get_course_tag', $course->get_tag_ids() );
	$difficulties = array_map( 'masteriyo_get_course_difficulty', $course->get_difficulty_ids() );
	$course_featured_image_url = wp_get_attachment_url( $course->get_featured_image() );

	$GLOBALS['course'] = $course;
	$GLOBALS['course_categories'] = $cats;
	$GLOBALS['course_tags'] = $tags;
	$GLOBALS['course_difficulties'] = $difficulties;
	$GLOBALS['course_featured_image_url'] = $course_featured_image_url;

	return $GLOBALS['course'];
}

/**
 * Render stars based on rating.
 *
 * @param int|float $rating Given rating.
 * @param int $out_of Max allowed rating.
 * @param string $full_star Full star icon html.
 * @param string $half_star Half star icon html.
 * @param string $no_star Empty star html.
 *
 * @return void|string
 */
function masteriyo_render_stars( $rating, $out_of, $full_star, $half_star, $no_star, $return = false ) {
	$rating = (float) $rating;

	ob_start();

	for ( $i = 1; $i <= floor($rating); $i++ ) {
		echo $full_star;
	}
	if ( floor( $rating ) != $rating ) {
		echo $half_star;
	}
	for ( $i = ceil( $rating ); $i < $out_of; $i++ ) {
		echo $no_star;
	}

	if ( $return === true ) {
		return ob_get_clean();
	} else {
		echo ob_get_clean();
	}
}

/**
 * Get related courses.
 *
 * @param Course $course
 *
 * @return array[Course]
 */
function masteriyo_get_related_courses( $course ) {
	/**
	 * Ref: https://www.wpbeginner.com/wp-tutorials/how-to-display-related-posts-in-wordpress/
	 */
	$args = array(
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'course_tag',
				'terms'    => $course->get_tag_ids(),
			),
		),
		'post__not_in' => array( $course->get_id() ),
		'posts_per_page' => 5,
		'post_type' => 'course',
	);
	$query = new WP_Query($args);

	return array_map( 'masteriyo_get_course', $query->posts );
}
