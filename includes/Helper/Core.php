<?php
/**
 * Core functions.
 *
 * @since 0.1.0
 */

use ThemeGrill\Masteriyo\DateTime;
use ThemeGrill\Masteriyo\Constants;
use ThemeGrill\Masteriyo\Models\Course;
use ThemeGrill\Masteriyo\Models\Section;
use ThemeGrill\Masteriyo\Models\Faq;

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
 * Get Faq.
 *
 * @since 0.1.0
 *
 * @param int|Faq|WP_Post $faq Faq id or Faq Model or Post.
 *
 * @return Faq|null
 */
function masteriyo_get_faq( $faq ) {
	$faq_obj   = masteriyo( 'faq' );
	$faq_store = masteriyo( 'faq.store' );

	if ( is_a( $faq, 'ThemeGrill\Masteriyo\Models\Faq' ) ) {
		$id = $faq->get_id();
	} elseif ( is_a( $faq, 'WP_Post' ) ) {
		$id = $faq->ID;
	} else {
		$id = $faq;
	}

	try {
		$id = absint( $id );
		$faq_obj->set_id( $id );
		$faq_store->read( $faq_obj );
	} catch ( \Exception $e) {
		return null;
	}

	return apply_filters( 'masteriyo_get_faq', $faq_obj, $faq );
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
 * Get Faqs
 *
 * @since 0.1.0
 *
 * @param array $args Query arguments.
 *
 * @return object|array[Faq]
 */
function masteriyo_get_faqs( $args = array() ) {
	$faqs = masteriyo( 'query.faqs' )->set_args( $args )->get_faqs();

	return apply_filters( 'masteriyo_get_faqs', $faqs, $args );
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
	$type           = get_post_meta( $id, '_type', true );
	$question_obj   = masteriyo( "question.${type}" );
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
 * @since 0.1.0
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
function masteriyo_is_single_course_page() {
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
function masteriyo_img_url( $file ) {
	$plugin_dir = plugin_dir_url( Constants::get('MASTERIYO_PLUGIN_FILE') );

	return "{$plugin_dir}assets/img/{$file}";
}

/**
 * Put course data into a global.
 *
 * @since 0.1.0
 *
 * @param int|Course|WP_Post $course_id Course id or Course object or course wp post.
 *
 * @return Course
 */
function masteriyo_setup_course_data( $course_id ) {
	$GLOBALS['course'] =  masteriyo_get_course( $course_id );

	return $GLOBALS['course'];
}

/**
 * Render stars based on rating.
 *
 * @since 0.1.0
 *
 * @param int|float $rating Given rating.
 * @param string $classes Extra classes to add to the svgs.
 * @param string $echo Whether to echo or return the html.
 *
 * @return void|string
 */
function masteriyo_render_stars( $rating, $classes = '', $echo = true ) {
	$rating     = (float) $rating;
	$html       = '';
	$max_rating = apply_filters( 'masteriyo_max_course_rating', 5 );
	$stars      = apply_filters( 'masteriyo_rating_indicators_html', array(
		'full_star' =>
			"<svg class='mto-inline-block mto-fill-current {$classes}' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'>
				<path d='M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z'/>
			</svg>",
		'half_star' =>
			"<svg class='mto-inline-block mto-fill-current {$classes}' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'>
				<path d='M5.025 20.775A.998.998 0 006 22a1 1 0 00.555-.168L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082a1 1 0 00-.59-1.74l-5.701-.454-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.214 4.107-1.491 6.452zM12 5.429l2.042 4.521.588.047h.001l3.972.315-3.271 2.944-.001.002-.463.416.171.597v.003l1.253 4.385L12 15.798V5.429z'/>
			</svg>",
		'empty_star' =>
			"<svg class='mto-inline-block mto-fill-current {$classes}' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'>
				<path d='M6.516 14.323l-1.49 6.452a.998.998 0 001.529 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082a1 1 0 00-.59-1.74l-5.701-.454-2.467-5.461a.998.998 0 00-1.822 0L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.214 4.107zm2.853-4.326a.998.998 0 00.832-.586L12 5.43l1.799 3.981a.998.998 0 00.832.586l3.972.315-3.271 2.944c-.284.256-.397.65-.293 1.018l1.253 4.385-3.736-2.491a.995.995 0 00-1.109 0l-3.904 2.603 1.05-4.546a1 1 0 00-.276-.94l-3.038-2.962 4.09-.326z'/>
			</svg>",
	), $rating, $max_rating );

	$rating_floor = floor( $rating );
	for ( $i = 1; $i <= $rating_floor; $i++ ) {
		$html .= $stars['full_star'];
	}
	if ( $rating_floor !== $rating ) {
		$html .= $stars['half_star'];
	}

	$rating_ceil = ceil( $rating );
	for ( $i = $rating_ceil; $i < $max_rating; $i++ ) {
		$html .= $stars['empty_star'];
	}

	if ( true === $echo ) {
		echo $html; // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
	} else {
		return $html;
	}
}

/**
 * Get related courses.
 *
 * @since 0.1.0
 *
 * @param Course $course
 *
 * @return array[Course]
 */
function masteriyo_get_related_courses( $course ) {
	$setting = masteriyo( 'setting' );

	$setting->set_name( 'masteriyo_max_related_posts_count' );

	masteriyo( 'setting.store' )->read( $setting );

	$max_related_posts = apply_filters( 'masteriyo_max_related_posts_count', $setting->get_value() );
	$max_related_posts = absint( $max_related_posts );
	$max_related_posts = max( $max_related_posts, 5 );

	/**
	 * Ref: https://www.wpbeginner.com/wp-tutorials/how-to-display-related-posts-in-wordpress/
	 */
	$args            = array(
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'course_tag',
				'terms'    => $course->get_tag_ids(),
			),
		),
		'post__not_in' => array( $course->get_id() ),
		'posts_per_page' => $max_related_posts,
		'post_type' => 'course',
	);
	$query           = new WP_Query($args);
	$related_courses = array_map( 'masteriyo_get_course', $query->posts );

	return apply_filters( 'masteriyo_get_related_courses', $related_courses, $query );
}

/**
 * Get lessons count for a course.
 *
 * @since 0.1.0
 *
 * @param int|Course|WP_Post $course
 *
 * @return integer
 */
function masteriyo_get_lessons_count( $course ) {
	$course = masteriyo_get_course( $course );

	// Bail early if the course is null.
	if ( is_null( $course ) ) {
		return 0;
	}

	$lessons = masteriyo_get_lessons(array(
		'course_id' => $course->get_id(),
	));

	return count( $lessons );
}

/**
 * Convert minutes to time length string to display on screen.
 *
 * @since 0.1.0
 *
 * @param int $minutes Total length in minutes.
 * @param string $format Required format. Example: "%H% : %M%". '%H%' for placing hours and '%M%' for minutes.
 *
 * @return string
 */
function masteriyo_minutes_to_time_length_string( $minutes, $format = null ) {
	$minutes = absint( $minutes );
	$hours   = absint( $minutes / 60 );
	$mins    = $minutes - $hours * 60;
	$str     = '';

	if ( is_string( $format ) ) {
		$str = str_replace( '%H%', $hours, $format );
		$str = str_replace( '%M%', $mins, $str );
	} else {
		$str .= $hours > 0 ? sprintf( '%d %s ', $hours, _nx( 'hour', 'hours', $hours, 'hour', 'masteriyo' ) ) : '';
		$str .= $mins > 0 ? sprintf( ' %d %s', $mins, _nx( 'min', 'mins', $mins, 'minutes', 'masteriyo' ) ) : '';
		$str  = $minutes > 0 ? $str : __( '0 mins', 'masteriyo' );
	}

	return $str;
}

/**
 * Get lecture hours for a course as string to display on screen.
 *
 * @since 0.1.0
 *
 * @param int|Course|WP_Post $course
 * @param string $format Required format. Example: "%H% : %M%". '%H%' for placing hours and '%M%' for minutes.
 *
 * @return string
 */
function masteriyo_get_lecture_hours( $course, $format = null ) {
	$course = masteriyo_get_course( $course );

	// Bail early if the course is null.
	if ( is_null( $course ) ) {
		return '';
	}

	$lessons = masteriyo_get_lessons(array(
		'course_id' => $course->get_id(),
	));
	$mins    = 0;

	foreach ( $lessons as $lesson ) {
		$mins += $lesson->get_video_playback_time();
	}

	return masteriyo_minutes_to_time_length_string( $mins, $format );
}

/**
 * Get lecture hours for a section as string to display on screen.
 *
 * @since 0.1.0
 *
 * @param int|Section|WP_Post $course
 * @param string $format Required format. Example: "%H% : %M%". '%H%' for placing hours and '%M%' for minutes.
 *
 * @return string
 */
function masteriyo_get_lecture_hours_of_section( $section, $format = null ) {
	$section = masteriyo_get_section( $section );

	// Bail early if the section is null.
	if ( is_null( $section ) ) {
		return '';
	}

	$lessons = masteriyo_get_lessons(array(
		'parent_id' => $section->get_id(),
	));
	$mins    = 0;

	foreach ( $lessons as $lesson ) {
		$mins += $lesson->get_video_playback_time();
	}

	return masteriyo_minutes_to_time_length_string( $mins, $format );
}

/**
 * Make a dictionary with section id as key and its lessons as value from a course.
 *
 * @since 0.1.0
 *
 * @param int|Course|WP_Post $course
 *
 * @return array
 */
function masteriyo_make_section_to_lessons_dictionary( $course ) {
	$course = masteriyo_get_course( $course );

	// Bail early if the course is null.
	if ( is_null( $course) ) {
		return array();
	}

	$sections = masteriyo_get_sections( array(
		'order'     => 'asc',
		'order_by'  => 'menu_order',
		'parent_id' => $course->get_id(),
	) );

	$lessons = masteriyo_get_lessons(array(
		'order'     => 'asc',
		'order_by'  => 'menu_order',
		'course_id' => $course->get_id(),
	));

	$lessons_dictionary = array();

	foreach ( $lessons as $lesson ) {
		$section_id = $lesson->get_parent_id();

		if ( ! isset( $lessons_dictionary[ $section_id ] ) ) {
			$lessons_dictionary[ $section_id ] = array();
		}

		$lessons_dictionary[ $section_id ][] = $lesson;
	}

	foreach ( $sections as $section ) {
		if ( ! isset( $lessons_dictionary[ $section->get_id() ] ) ) {
			$lessons_dictionary[ $section->get_id() ] = array();
		}
	}

	return array( $sections, $lessons, $lessons_dictionary );
}

/** Return "theme support" values from the current theme, if set.
 *
 * @since  0.1.0
 * @param  string $prop Name of prop (or key::subkey for arrays of props) if you want a specific value. Leave blank to get all props as an array.
 * @param  mixed  $default Optional value to return if the theme does not declare support for a prop.
 * @return mixed  Value of prop(s).
 */
function masteriyo_get_theme_support( $prop = '', $default = null ) {
	$theme_support = get_theme_support( 'masteriyo' );
	$theme_support = is_array( $theme_support ) ? $theme_support[0] : false;

	if ( ! $theme_support ) {
		return $default;
	}

	if ( $prop ) {
		$prop_stack = explode( '::', $prop );
		$prop_key   = array_shift( $prop_stack );

		if ( isset( $theme_support[ $prop_key ] ) ) {
			$value = $theme_support[ $prop_key ];

			if ( count( $prop_stack ) ) {
				foreach ( $prop_stack as $prop_key ) {
					if ( is_array( $value ) && isset( $value[ $prop_key ] ) ) {
						$value = $value[ $prop_key ];
					} else {
						$value = $default;
						break;
					}
				}
			}
		} else {
			$value = $default;
		}

		return $value;
	}

	return $theme_support;
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
 * Get Currency symbol.
 *
 * Currency symbols and names should follow the Unicode CLDR recommendation (http://cldr.unicode.org/translation/currency-names)
 *
 * @param string $currency Currency. (default: '').
 * @return string
 */
function masteriyo_get_currency_symbol( $currency = '' ) {
	if ( ! $currency ) {
		$currency = masteriyo_get_currency();
	}

	$symbols = masteriyo_get_currency_symbols();

	$currency_symbol = isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : '';

	return apply_filters( 'masteriyo_currency_symbol', $currency_symbol, $currency );
}

/**
 * Get Base Currency Code.
 *
 * @return string
 */
function masteriyo_get_currency() {
	return apply_filters( 'masteriyo_currency', get_option( 'masteriyo_currency', 'USD' ) );
}

/**
 * Get all available Currency symbols.
 *
 * Currency symbols and names should follow the Unicode CLDR recommendation (http://cldr.unicode.org/translation/currency-names)
 *
 * @since 4.1.0
 * @return array
 */
function masteriyo_get_currency_symbols() {

	$symbols = apply_filters(
		'masteriyo_currency_symbols',
		array(
			'AED' => '&#x62f;.&#x625;',
			'AFN' => '&#x60b;',
			'ALL' => 'L',
			'AMD' => 'AMD',
			'ANG' => '&fnof;',
			'AOA' => 'Kz',
			'ARS' => '&#36;',
			'AUD' => '&#36;',
			'AWG' => 'Afl.',
			'AZN' => 'AZN',
			'BAM' => 'KM',
			'BBD' => '&#36;',
			'BDT' => '&#2547;&nbsp;',
			'BGN' => '&#1083;&#1074;.',
			'BHD' => '.&#x62f;.&#x628;',
			'BIF' => 'Fr',
			'BMD' => '&#36;',
			'BND' => '&#36;',
			'BOB' => 'Bs.',
			'BRL' => '&#82;&#36;',
			'BSD' => '&#36;',
			'BTC' => '&#3647;',
			'BTN' => 'Nu.',
			'BWP' => 'P',
			'BYR' => 'Br',
			'BYN' => 'Br',
			'BZD' => '&#36;',
			'CAD' => '&#36;',
			'CDF' => 'Fr',
			'CHF' => '&#67;&#72;&#70;',
			'CLP' => '&#36;',
			'CNY' => '&yen;',
			'COP' => '&#36;',
			'CRC' => '&#x20a1;',
			'CUC' => '&#36;',
			'CUP' => '&#36;',
			'CVE' => '&#36;',
			'CZK' => '&#75;&#269;',
			'DJF' => 'Fr',
			'DKK' => 'DKK',
			'DOP' => 'RD&#36;',
			'DZD' => '&#x62f;.&#x62c;',
			'EGP' => 'EGP',
			'ERN' => 'Nfk',
			'ETB' => 'Br',
			'EUR' => '&euro;',
			'FJD' => '&#36;',
			'FKP' => '&pound;',
			'GBP' => '&pound;',
			'GEL' => '&#x20be;',
			'GGP' => '&pound;',
			'GHS' => '&#x20b5;',
			'GIP' => '&pound;',
			'GMD' => 'D',
			'GNF' => 'Fr',
			'GTQ' => 'Q',
			'GYD' => '&#36;',
			'HKD' => '&#36;',
			'HNL' => 'L',
			'HRK' => 'kn',
			'HTG' => 'G',
			'HUF' => '&#70;&#116;',
			'IDR' => 'Rp',
			'ILS' => '&#8362;',
			'IMP' => '&pound;',
			'INR' => '&#8377;',
			'IQD' => '&#x639;.&#x62f;',
			'IRR' => '&#xfdfc;',
			'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;',
			'ISK' => 'kr.',
			'JEP' => '&pound;',
			'JMD' => '&#36;',
			'JOD' => '&#x62f;.&#x627;',
			'JPY' => '&yen;',
			'KES' => 'KSh',
			'KGS' => '&#x441;&#x43e;&#x43c;',
			'KHR' => '&#x17db;',
			'KMF' => 'Fr',
			'KPW' => '&#x20a9;',
			'KRW' => '&#8361;',
			'KWD' => '&#x62f;.&#x643;',
			'KYD' => '&#36;',
			'KZT' => '&#8376;',
			'LAK' => '&#8365;',
			'LBP' => '&#x644;.&#x644;',
			'LKR' => '&#xdbb;&#xdd4;',
			'LRD' => '&#36;',
			'LSL' => 'L',
			'LYD' => '&#x644;.&#x62f;',
			'MAD' => '&#x62f;.&#x645;.',
			'MDL' => 'MDL',
			'MGA' => 'Ar',
			'MKD' => '&#x434;&#x435;&#x43d;',
			'MMK' => 'Ks',
			'MNT' => '&#x20ae;',
			'MOP' => 'P',
			'MRU' => 'UM',
			'MUR' => '&#x20a8;',
			'MVR' => '.&#x783;',
			'MWK' => 'MK',
			'MXN' => '&#36;',
			'MYR' => '&#82;&#77;',
			'MZN' => 'MT',
			'NAD' => 'N&#36;',
			'NGN' => '&#8358;',
			'NIO' => 'C&#36;',
			'NOK' => '&#107;&#114;',
			'NPR' => '&#8360;',
			'NZD' => '&#36;',
			'OMR' => '&#x631;.&#x639;.',
			'PAB' => 'B/.',
			'PEN' => 'S/',
			'PGK' => 'K',
			'PHP' => '&#8369;',
			'PKR' => '&#8360;',
			'PLN' => '&#122;&#322;',
			'PRB' => '&#x440;.',
			'PYG' => '&#8370;',
			'QAR' => '&#x631;.&#x642;',
			'RMB' => '&yen;',
			'RON' => 'lei',
			'RSD' => '&#1088;&#1089;&#1076;',
			'RUB' => '&#8381;',
			'RWF' => 'Fr',
			'SAR' => '&#x631;.&#x633;',
			'SBD' => '&#36;',
			'SCR' => '&#x20a8;',
			'SDG' => '&#x62c;.&#x633;.',
			'SEK' => '&#107;&#114;',
			'SGD' => '&#36;',
			'SHP' => '&pound;',
			'SLL' => 'Le',
			'SOS' => 'Sh',
			'SRD' => '&#36;',
			'SSP' => '&pound;',
			'STN' => 'Db',
			'SYP' => '&#x644;.&#x633;',
			'SZL' => 'L',
			'THB' => '&#3647;',
			'TJS' => '&#x405;&#x41c;',
			'TMT' => 'm',
			'TND' => '&#x62f;.&#x62a;',
			'TOP' => 'T&#36;',
			'TRY' => '&#8378;',
			'TTD' => '&#36;',
			'TWD' => '&#78;&#84;&#36;',
			'TZS' => 'Sh',
			'UAH' => '&#8372;',
			'UGX' => 'UGX',
			'USD' => '&#36;',
			'UYU' => '&#36;',
			'UZS' => 'UZS',
			'VEF' => 'Bs F',
			'VES' => 'Bs.S',
			'VND' => '&#8363;',
			'VUV' => 'Vt',
			'WST' => 'T',
			'XAF' => 'CFA',
			'XCD' => '&#36;',
			'XOF' => 'CFA',
			'XPF' => 'Fr',
			'YER' => '&#xfdfc;',
			'ZAR' => '&#82;',
			'ZMW' => 'ZK',
		)
	);

	return $symbols;
}

/**
 * Get full list of currency codes.
 *
 * Currency symbols and names should follow the Unicode CLDR recommendation (http://cldr.unicode.org/translation/currency-names)
 *
 * @return array
 */
function get_masteriyo_currencies() {
	static $currencies;

	if ( ! isset( $currencies ) ) {
		$currencies = array_unique(
			apply_filters(
				'masteriyo_currencies',
				array(
					'AED' => __( 'United Arab Emirates dirham', 'masteriyo' ),
					'AFN' => __( 'Afghan afghani', 'masteriyo' ),
					'ALL' => __( 'Albanian lek', 'masteriyo' ),
					'AMD' => __( 'Armenian dram', 'masteriyo' ),
					'ANG' => __( 'Netherlands Antillean guilder', 'masteriyo' ),
					'AOA' => __( 'Angolan kwanza', 'masteriyo' ),
					'ARS' => __( 'Argentine peso', 'masteriyo' ),
					'AUD' => __( 'Australian dollar', 'masteriyo' ),
					'AWG' => __( 'Aruban florin', 'masteriyo' ),
					'AZN' => __( 'Azerbaijani manat', 'masteriyo' ),
					'BAM' => __( 'Bosnia and Herzegovina convertible mark', 'masteriyo' ),
					'BBD' => __( 'Barbadian dollar', 'masteriyo' ),
					'BDT' => __( 'Bangladeshi taka', 'masteriyo' ),
					'BGN' => __( 'Bulgarian lev', 'masteriyo' ),
					'BHD' => __( 'Bahraini dinar', 'masteriyo' ),
					'BIF' => __( 'Burundian franc', 'masteriyo' ),
					'BMD' => __( 'Bermudian dollar', 'masteriyo' ),
					'BND' => __( 'Brunei dollar', 'masteriyo' ),
					'BOB' => __( 'Bolivian boliviano', 'masteriyo' ),
					'BRL' => __( 'Brazilian real', 'masteriyo' ),
					'BSD' => __( 'Bahamian dollar', 'masteriyo' ),
					'BTC' => __( 'Bitcoin', 'masteriyo' ),
					'BTN' => __( 'Bhutanese ngultrum', 'masteriyo' ),
					'BWP' => __( 'Botswana pula', 'masteriyo' ),
					'BYR' => __( 'Belarusian ruble (old)', 'masteriyo' ),
					'BYN' => __( 'Belarusian ruble', 'masteriyo' ),
					'BZD' => __( 'Belize dollar', 'masteriyo' ),
					'CAD' => __( 'Canadian dollar', 'masteriyo' ),
					'CDF' => __( 'Congolese franc', 'masteriyo' ),
					'CHF' => __( 'Swiss franc', 'masteriyo' ),
					'CLP' => __( 'Chilean peso', 'masteriyo' ),
					'CNY' => __( 'Chinese yuan', 'masteriyo' ),
					'COP' => __( 'Colombian peso', 'masteriyo' ),
					'CRC' => __( 'Costa Rican col&oacute;n', 'masteriyo' ),
					'CUC' => __( 'Cuban convertible peso', 'masteriyo' ),
					'CUP' => __( 'Cuban peso', 'masteriyo' ),
					'CVE' => __( 'Cape Verdean escudo', 'masteriyo' ),
					'CZK' => __( 'Czech koruna', 'masteriyo' ),
					'DJF' => __( 'Djiboutian franc', 'masteriyo' ),
					'DKK' => __( 'Danish krone', 'masteriyo' ),
					'DOP' => __( 'Dominican peso', 'masteriyo' ),
					'DZD' => __( 'Algerian dinar', 'masteriyo' ),
					'EGP' => __( 'Egyptian pound', 'masteriyo' ),
					'ERN' => __( 'Eritrean nakfa', 'masteriyo' ),
					'ETB' => __( 'Ethiopian birr', 'masteriyo' ),
					'EUR' => __( 'Euro', 'masteriyo' ),
					'FJD' => __( 'Fijian dollar', 'masteriyo' ),
					'FKP' => __( 'Falkland Islands pound', 'masteriyo' ),
					'GBP' => __( 'Pound sterling', 'masteriyo' ),
					'GEL' => __( 'Georgian lari', 'masteriyo' ),
					'GGP' => __( 'Guernsey pound', 'masteriyo' ),
					'GHS' => __( 'Ghana cedi', 'masteriyo' ),
					'GIP' => __( 'Gibraltar pound', 'masteriyo' ),
					'GMD' => __( 'Gambian dalasi', 'masteriyo' ),
					'GNF' => __( 'Guinean franc', 'masteriyo' ),
					'GTQ' => __( 'Guatemalan quetzal', 'masteriyo' ),
					'GYD' => __( 'Guyanese dollar', 'masteriyo' ),
					'HKD' => __( 'Hong Kong dollar', 'masteriyo' ),
					'HNL' => __( 'Honduran lempira', 'masteriyo' ),
					'HRK' => __( 'Croatian kuna', 'masteriyo' ),
					'HTG' => __( 'Haitian gourde', 'masteriyo' ),
					'HUF' => __( 'Hungarian forint', 'masteriyo' ),
					'IDR' => __( 'Indonesian rupiah', 'masteriyo' ),
					'ILS' => __( 'Israeli new shekel', 'masteriyo' ),
					'IMP' => __( 'Manx pound', 'masteriyo' ),
					'INR' => __( 'Indian rupee', 'masteriyo' ),
					'IQD' => __( 'Iraqi dinar', 'masteriyo' ),
					'IRR' => __( 'Iranian rial', 'masteriyo' ),
					'IRT' => __( 'Iranian toman', 'masteriyo' ),
					'ISK' => __( 'Icelandic kr&oacute;na', 'masteriyo' ),
					'JEP' => __( 'Jersey pound', 'masteriyo' ),
					'JMD' => __( 'Jamaican dollar', 'masteriyo' ),
					'JOD' => __( 'Jordanian dinar', 'masteriyo' ),
					'JPY' => __( 'Japanese yen', 'masteriyo' ),
					'KES' => __( 'Kenyan shilling', 'masteriyo' ),
					'KGS' => __( 'Kyrgyzstani som', 'masteriyo' ),
					'KHR' => __( 'Cambodian riel', 'masteriyo' ),
					'KMF' => __( 'Comorian franc', 'masteriyo' ),
					'KPW' => __( 'North Korean won', 'masteriyo' ),
					'KRW' => __( 'South Korean won', 'masteriyo' ),
					'KWD' => __( 'Kuwaiti dinar', 'masteriyo' ),
					'KYD' => __( 'Cayman Islands dollar', 'masteriyo' ),
					'KZT' => __( 'Kazakhstani tenge', 'masteriyo' ),
					'LAK' => __( 'Lao kip', 'masteriyo' ),
					'LBP' => __( 'Lebanese pound', 'masteriyo' ),
					'LKR' => __( 'Sri Lankan rupee', 'masteriyo' ),
					'LRD' => __( 'Liberian dollar', 'masteriyo' ),
					'LSL' => __( 'Lesotho loti', 'masteriyo' ),
					'LYD' => __( 'Libyan dinar', 'masteriyo' ),
					'MAD' => __( 'Moroccan dirham', 'masteriyo' ),
					'MDL' => __( 'Moldovan leu', 'masteriyo' ),
					'MGA' => __( 'Malagasy ariary', 'masteriyo' ),
					'MKD' => __( 'Macedonian denar', 'masteriyo' ),
					'MMK' => __( 'Burmese kyat', 'masteriyo' ),
					'MNT' => __( 'Mongolian t&ouml;gr&ouml;g', 'masteriyo' ),
					'MOP' => __( 'Macanese pataca', 'masteriyo' ),
					'MRU' => __( 'Mauritanian ouguiya', 'masteriyo' ),
					'MUR' => __( 'Mauritian rupee', 'masteriyo' ),
					'MVR' => __( 'Maldivian rufiyaa', 'masteriyo' ),
					'MWK' => __( 'Malawian kwacha', 'masteriyo' ),
					'MXN' => __( 'Mexican peso', 'masteriyo' ),
					'MYR' => __( 'Malaysian ringgit', 'masteriyo' ),
					'MZN' => __( 'Mozambican metical', 'masteriyo' ),
					'NAD' => __( 'Namibian dollar', 'masteriyo' ),
					'NGN' => __( 'Nigerian naira', 'masteriyo' ),
					'NIO' => __( 'Nicaraguan c&oacute;rdoba', 'masteriyo' ),
					'NOK' => __( 'Norwegian krone', 'masteriyo' ),
					'NPR' => __( 'Nepalese rupee', 'masteriyo' ),
					'NZD' => __( 'New Zealand dollar', 'masteriyo' ),
					'OMR' => __( 'Omani rial', 'masteriyo' ),
					'PAB' => __( 'Panamanian balboa', 'masteriyo' ),
					'PEN' => __( 'Sol', 'masteriyo' ),
					'PGK' => __( 'Papua New Guinean kina', 'masteriyo' ),
					'PHP' => __( 'Philippine peso', 'masteriyo' ),
					'PKR' => __( 'Pakistani rupee', 'masteriyo' ),
					'PLN' => __( 'Polish z&#x142;oty', 'masteriyo' ),
					'PRB' => __( 'Transnistrian ruble', 'masteriyo' ),
					'PYG' => __( 'Paraguayan guaran&iacute;', 'masteriyo' ),
					'QAR' => __( 'Qatari riyal', 'masteriyo' ),
					'RON' => __( 'Romanian leu', 'masteriyo' ),
					'RSD' => __( 'Serbian dinar', 'masteriyo' ),
					'RUB' => __( 'Russian ruble', 'masteriyo' ),
					'RWF' => __( 'Rwandan franc', 'masteriyo' ),
					'SAR' => __( 'Saudi riyal', 'masteriyo' ),
					'SBD' => __( 'Solomon Islands dollar', 'masteriyo' ),
					'SCR' => __( 'Seychellois rupee', 'masteriyo' ),
					'SDG' => __( 'Sudanese pound', 'masteriyo' ),
					'SEK' => __( 'Swedish krona', 'masteriyo' ),
					'SGD' => __( 'Singapore dollar', 'masteriyo' ),
					'SHP' => __( 'Saint Helena pound', 'masteriyo' ),
					'SLL' => __( 'Sierra Leonean leone', 'masteriyo' ),
					'SOS' => __( 'Somali shilling', 'masteriyo' ),
					'SRD' => __( 'Surinamese dollar', 'masteriyo' ),
					'SSP' => __( 'South Sudanese pound', 'masteriyo' ),
					'STN' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra', 'masteriyo' ),
					'SYP' => __( 'Syrian pound', 'masteriyo' ),
					'SZL' => __( 'Swazi lilangeni', 'masteriyo' ),
					'THB' => __( 'Thai baht', 'masteriyo' ),
					'TJS' => __( 'Tajikistani somoni', 'masteriyo' ),
					'TMT' => __( 'Turkmenistan manat', 'masteriyo' ),
					'TND' => __( 'Tunisian dinar', 'masteriyo' ),
					'TOP' => __( 'Tongan pa&#x2bb;anga', 'masteriyo' ),
					'TRY' => __( 'Turkish lira', 'masteriyo' ),
					'TTD' => __( 'Trinidad and Tobago dollar', 'masteriyo' ),
					'TWD' => __( 'New Taiwan dollar', 'masteriyo' ),
					'TZS' => __( 'Tanzanian shilling', 'masteriyo' ),
					'UAH' => __( 'Ukrainian hryvnia', 'masteriyo' ),
					'UGX' => __( 'Ugandan shilling', 'masteriyo' ),
					'USD' => __( 'United States (US) dollar', 'masteriyo' ),
					'UYU' => __( 'Uruguayan peso', 'masteriyo' ),
					'UZS' => __( 'Uzbekistani som', 'masteriyo' ),
					'VEF' => __( 'Venezuelan bol&iacute;var', 'masteriyo' ),
					'VES' => __( 'Bol&iacute;var soberano', 'masteriyo' ),
					'VND' => __( 'Vietnamese &#x111;&#x1ed3;ng', 'masteriyo' ),
					'VUV' => __( 'Vanuatu vatu', 'masteriyo' ),
					'WST' => __( 'Samoan t&#x101;l&#x101;', 'masteriyo' ),
					'XAF' => __( 'Central African CFA franc', 'masteriyo' ),
					'XCD' => __( 'East Caribbean dollar', 'masteriyo' ),
					'XOF' => __( 'West African CFA franc', 'masteriyo' ),
					'XPF' => __( 'CFP franc', 'masteriyo' ),
					'YER' => __( 'Yemeni rial', 'masteriyo' ),
					'ZAR' => __( 'South African rand', 'masteriyo' ),
					'ZMW' => __( 'Zambian kwacha', 'masteriyo' ),
				)
			)
		);
	}

	return $currencies;
}
