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
use ThemeGrill\Masteriyo\Models\User;
use ThemeGrill\Masteriyo\Models\CourseReview;

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
		$id = absint( $course );
	}

	try {
		$id = absint( $id );
		$course_obj->set_id( $id );
		$course_store->read( $course_obj );
	} catch ( \Exception $e ) {
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
	} catch ( \Exception $e ) {
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
	} catch ( \Exception $e ) {
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
	} catch ( \Exception $e ) {
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
	} catch ( \Exception $e ) {
		return null;
	}

	return apply_filters( 'masteriyo_get_quiz', $quiz_obj, $quiz );
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
	} catch ( \Exception $e ) {
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
	} catch ( \Exception $e ) {
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
	} catch ( \Exception $e ) {
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
	} catch ( \Exception $e ) {
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
	} catch ( \Exception $e ) {
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
	$page    = str_replace( '-', '_', $page );
	$page_id = get_option( 'masteriyo.pages.' . $page . '_page_id', -1 );
	$page_id = apply_filters( 'masteriyo_get_' . $page . '_page_id', $page_id );

	return $page_id ? absint( $page_id ) : -1;
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
 * Get image asset URL.
 *
 * @since 0.1.0
 *
 * @param string $file Image file name.
 *
 * @return string
 */
function masteriyo_img_url( $file ) {
	$plugin_dir = plugin_dir_url( Constants::get( 'MASTERIYO_PLUGIN_FILE' ) );

	return "{$plugin_dir}assets/img/{$file}";
}

/**
 * Get current logged in user.
 *
 * @since 0.1.0
 *
 * @return User
 */
function masteriyo_get_current_user() {
	if ( is_user_logged_in() ) {
		return masteriyo_get_user( get_current_user_id() );
	}

	return null;
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
	$stars      = apply_filters(
		'masteriyo_rating_indicators_html',
		array(
			'full_star'  =>
				"<svg class='mto-inline-block mto-fill-current {$classes}' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'>
				<path d='M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z'/>
			</svg>",
			'half_star'  =>
				"<svg class='mto-inline-block mto-fill-current {$classes}' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'>
				<path d='M5.025 20.775A.998.998 0 006 22a1 1 0 00.555-.168L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082a1 1 0 00-.59-1.74l-5.701-.454-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.214 4.107-1.491 6.452zM12 5.429l2.042 4.521.588.047h.001l3.972.315-3.271 2.944-.001.002-.463.416.171.597v.003l1.253 4.385L12 15.798V5.429z'/>
			</svg>",
			'empty_star' =>
				"<svg class='mto-inline-block mto-fill-current {$classes}' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'>
				<path d='M6.516 14.323l-1.49 6.452a.998.998 0 001.529 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082a1 1 0 00-.59-1.74l-5.701-.454-2.467-5.461a.998.998 0 00-1.822 0L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.214 4.107zm2.853-4.326a.998.998 0 00.832-.586L12 5.43l1.799 3.981a.998.998 0 00.832.586l3.972.315-3.271 2.944c-.284.256-.397.65-.293 1.018l1.253 4.385-3.736-2.491a.995.995 0 00-1.109 0l-3.904 2.603 1.05-4.546a1 1 0 00-.276-.94l-3.038-2.962 4.09-.326z'/>
			</svg>",
		),
		$rating,
		$max_rating
	);

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
	$max_related_posts = get_option( 'masteriyo_max_related_posts_count', 3 );
	$max_related_posts = apply_filters( 'masteriyo_max_related_posts_count', $max_related_posts );
	$max_related_posts = absint( $max_related_posts );
	$max_related_posts = max( $max_related_posts, 5 );

	/**
	 * Ref: https://www.wpbeginner.com/wp-tutorials/how-to-display-related-posts-in-wordpress/
	 */
	$args            = array(
		'tax_query'      => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'course_tag',
				'terms'    => $course->get_tag_ids(),
			),
		),
		'post__not_in'   => array( $course->get_id() ),
		'posts_per_page' => $max_related_posts,
		'post_type'      => 'course',
	);
	$query           = new WP_Query( $args );
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

	$lessons = masteriyo_get_lessons(
		array(
			'course_id' => $course->get_id(),
		)
	);

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

	$lessons = masteriyo_get_lessons(
		array(
			'course_id' => $course->get_id(),
		)
	);
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

	$lessons = masteriyo_get_lessons(
		array(
			'parent_id' => $section->get_id(),
		)
	);
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
	if ( is_null( $course ) ) {
		return array();
	}

	$sections = masteriyo_get_sections(
		array(
			'order'     => 'asc',
			'order_by'  => 'menu_order',
			'parent_id' => $course->get_id(),
		)
	);

	$lessons = masteriyo_get_lessons(
		array(
			'order'     => 'asc',
			'order_by'  => 'menu_order',
			'course_id' => $course->get_id(),
		)
	);

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
function masteriyo_get_currencies() {
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

	return $currencies;
}

/**
 * Get permalink settings for things like courses and taxonomies.
 *
 * @since  0.1.0
 *
 * @param string $id Permalink id.
 *
 * @return array
 */
function masteriyo_get_permalink_structure() {
	$get_slugs = array(
		'courses'            => get_option( 'masteriyo.courses.single_course_permalink' ),
		'courses_category'   => get_option( 'masteriyo.courses.category_base' ),
		'courses_tag'        => get_option( 'masteriyo.courses.tag_base' ),
		'courses_difficulty' => get_option( 'masteriyo.courses.difficulty_base' ),
		'lessons'            => get_option( 'masteriyo.courses.single_lesson_permalink' ),
		'quizzes'            => get_option( 'masteriyo.courses.single_quiz_permalink' ),
		'sections'           => get_option( 'masteriyo.courses.single_section_permalink' ),
	);

	$permalinks = array(
		'course_base'            => _x( 'course', 'slug', 'masteriyo' ),
		'course_category_base'   => _x( 'course-category', 'slug', 'masteriyo' ),
		'course_tag_base'        => _x( 'course-tag', 'slug', 'masteriyo' ),
		'course_difficulty_base' => _x( 'course-difficulty', 'slug', 'masteriyo' ),
		'lesson_base'            => _x( 'lesson', 'slug', 'masteriyo' ),
		'quiz_base'              => _x( 'quiz', 'slug', 'masteriyo' ),
		'section_base'           => _x( 'section', 'slug', 'masteriyo' ),
	);

	$permalinks['course_rewrite_slug']            = untrailingslashit( empty( $get_slugs['courses'] ) ? $permalinks['course_base'] : $get_slugs['courses'] );
	$permalinks['course_category_rewrite_slug']   = untrailingslashit( empty( $get_slugs['courses_category'] ) ? $permalinks['course_category_base'] : $get_slugs['courses_category'] );
	$permalinks['course_tag_rewrite_slug']        = untrailingslashit( empty( $get_slugs['courses_tag'] ) ? $permalinks['course_tag_base'] : $get_slugs['courses_tag'] );
	$permalinks['course_difficulty_rewrite_slug'] = untrailingslashit( empty( $get_slugs['courses_difficulty'] ) ? $permalinks['course_difficulty_base'] : $get_slugs['courses_difficulty'] );
	$permalinks['lesson_rewrite_slug']            = untrailingslashit( empty( $get_slugs['lessons'] ) ) ? $permalinks['lesson_base'] : $get_slugs['lessons'];
	$permalinks['quiz_rewrite_slug']              = untrailingslashit( empty( $get_slugs['quizzes'] ) ) ? $permalinks['quiz_base'] : $get_slugs['quizzes'];
	$permalinks['section_rewrite_slug']           = untrailingslashit( empty( $get_slugs['sections'] ) ) ? $permalinks['section_base'] : $get_slugs['sections'];

	return $permalinks;
}

/**
 * Check whether to flush rules or not after settings saved.
 *
 * @since 0.1.0
 */
function masteriyo_maybe_flush_rewrite() {

	if ( 'yes' === get_option( 'masteriyo_flush_rewrite_rules' ) ) {
		update_option( 'masteriyo_flush_rewrite_rules', 'no' );
		flush_rewrite_rules();
	}
}
function_exists( 'add_action' ) && add_action( 'masteriyo_after_register_post_type', 'masteriyo_maybe_flush_rewrite' );

/**
 * Filter to allow course_cat in the permalinks for course.
 *
 * @param  string  $permalink The existing permalink URL.
 * @param  WP_Post $post WP_Post object.
 * @return string
 */
function masteriyo_course_post_type_link( $permalink, $post ) {
	// Abort if post is not a course.
	if ( 'course' !== $post->post_type ) {
		return $permalink;
	}

	// Abort early if the placeholder rewrite tag isn't in the generated URL.
	if ( false === strpos( $permalink, '%' ) ) {
		return $permalink;
	}

	// Get the custom taxonomy terms in use by this post.
	$terms = get_the_terms( $post->ID, 'course_cat' );

	if ( ! empty( $terms ) ) {
		$terms           = wp_list_sort(
			$terms,
			array(
				'parent'  => 'DESC',
				'term_id' => 'ASC',
			)
		);
		$category_object = apply_filters( 'masteriyo_course_post_type_link_course_cat', $terms[0], $terms, $post );
		$course_cat      = $category_object->slug;

		if ( $category_object->parent ) {
			$ancestors = get_ancestors( $category_object->term_id, 'course_cat' );
			foreach ( $ancestors as $ancestor ) {
				$ancestor_object = get_term( $ancestor, 'course_cat' );
				if ( apply_filters( 'masteriyo_course_post_type_link_parent_category_only', false ) ) {
					$course_cat = $ancestor_object->slug;
				} else {
					$course_cat = $ancestor_object->slug . '/' . $course_cat;
				}
			}
		}
	} else {
		// If no terms are assigned to this post, use a string instead (can't leave the placeholder there).
		$course_cat = _x( 'uncategorized', 'slug', 'masteriyo' );
	}

	$find = array(
		'%year%',
		'%monthnum%',
		'%day%',
		'%hour%',
		'%minute%',
		'%second%',
		'%post_id%',
		'%category%',
		'%course_cat%',
	);

	$replace = array(
		date_i18n( 'Y', strtotime( $post->post_date ) ),
		date_i18n( 'm', strtotime( $post->post_date ) ),
		date_i18n( 'd', strtotime( $post->post_date ) ),
		date_i18n( 'H', strtotime( $post->post_date ) ),
		date_i18n( 'i', strtotime( $post->post_date ) ),
		date_i18n( 's', strtotime( $post->post_date ) ),
		$post->ID,
		$course_cat,
		$course_cat,
	);

	$permalink = str_replace( $find, $replace, $permalink );

	return $permalink;
}
function_exists( 'add_filter' ) && add_filter( 'post_type_link', 'masteriyo_course_post_type_link', 10, 2 );

/**
 * Switch Masteriyo to site language.
 *
 * @since 0.1.0
 */
function masteriyo_switch_to_site_locale() {
	if ( function_exists( 'switch_to_locale' ) ) {
		switch_to_locale( get_locale() );

		// Filter on plugin_locale so load_plugin_textdomain loads the correct locale.
		add_filter( 'plugin_locale', 'get_locale' );

		// Init masteriyo locale.
		masteriyo()->load_text_domain();
	}
}

/**
 * Switch Masteriyo language to original.
 *
 * @since 0.1.0
 */
function masteriyo_restore_locale() {
	if ( function_exists( 'restore_previous_locale' ) ) {
		restore_previous_locale();

		// Remove filter.
		remove_filter( 'plugin_locale', 'get_locale' );

		// Init masteriyo locale.
		masteriyo()->load_text_domain();
	}
}

/**
 * Define a constant if it is not already defined.
 *
 * @since 0.1.0
 * @param string $name  Constant name.
 * @param mixed  $value Value.
 */
function masteriyo_maybe_define_constant( $name, $value ) {
	if ( ! defined( $name ) ) {
		define( $name, $value );
	}
}


/**
 * Wrapper for nocache_headers which also disables page caching.
 *
 * @since 0.1.0
 */
function masteriyo_nocache_headers() {
	masteriyo_set_nocache_constants();
	nocache_headers();
}


/**
 * Set constants to prevent caching by some plugins.
 *
 * @since 0.1.0
 *
 * @param  mixed $return Value to return. Previously hooked into a filter.
 * @return mixed
 */
function masteriyo_set_nocache_constants( $return = true ) {
	masteriyo_maybe_define_constant( 'DONOTCACHEPAGE', true );
	masteriyo_maybe_define_constant( 'DONOTCACHEOBJECT', true );
	masteriyo_maybe_define_constant( 'DONOTCACHEDB', true );
	return $return;
}

/**
 * Gets the url to the cart page.
 *
 * @since  0.1.0
 *
 * @return string Url to cart page
 */
function masteriyo_get_cart_url() {
	return apply_filters( 'masteriyo_get_cart_url', masteriyo_get_page_permalink( 'cart' ) );
}

/**
 * Gets the url to the checkout page.
 *
 * @since  0.1.0
 *
 * @return string Url to checkout page
 */
function masteriyo_get_checkout_url() {
	return apply_filters( 'masteriyo_get_checkout_url', masteriyo_get_page_permalink( 'checkout' ) );
}

/**
 * Gets the url to the course list page.
 *
 * @since  0.1.0
 *
 * @return string Url to course list page
 */
function masteriyo_get_course_list_url() {
	return apply_filters( 'masteriyo_get_course_list_url', masteriyo_get_page_permalink( 'course-list' ) );
}

/**
 * Get current endpoint in the myaccount page.
 *
 * @since 0.1.0
 *
 * @return string
 */
function masteriyo_get_current_myaccount_endpoint() {
	global $wp;

	$slugs = masteriyo_get_myaccount_endpoints();

	if ( ! empty( $wp->query_vars ) ) {
		foreach ( $wp->query_vars as $key => $value ) {
			// Ignore pagename param.
			if ( 'pagename' === $key ) {
				continue;
			}

			if ( in_array( $key, $slugs, true ) ) {
				return $key;
			}
		}
	}

	// No endpoint found? Default to dashboard.
	return 'dashboard';
}

/**
 * Get my account endpoints' slugs.
 *
 * @since 0.1.0
 *
 * @return array
 */
function masteriyo_get_myaccount_endpoints() {
	return apply_filters(
		'masteriyo_myaccount_endpoints',
		array(
			'view-myaccount' => get_option( 'masteriyo_myaccount_view-myaccount_endpoint', 'view-myaccount' ),
			'edit-myaccount' => get_option( 'masteriyo_myaccount_edit-myaccount_endpoint', 'edit-myaccount' ),
			'dashboard'      => get_option( 'masteriyo_myaccount_dashboard_endpoint', 'dashboard' ),
			'courses'        => get_option( 'masteriyo_myaccount_courses_endpoint', 'courses' ),
			'grades'         => get_option( 'masteriyo_myaccount_grades_endpoint', 'grades' ),
			'memberships'    => get_option( 'masteriyo_myaccount_memberships_endpoint', 'memberships' ),
			'certificates'   => get_option( 'masteriyo_myaccount_certificates_endpoint', 'certificates' ),
			'order-history'  => get_option( 'masteriyo_myaccount_order-history_endpoint', 'order-history' ),
			'reset-password' => get_option( 'masteriyo_myaccount_reset-password_endpoint', 'reset-password' ),
			'signup'         => get_option( 'masteriyo_myaccount_signup_endpoint', 'signup' ),
			'user-logout'    => get_option( 'masteriyo_logout_endpoint', 'user-logout' ),
		)
	);
}

/**
 * Get account endpoint URL.
 *
 * @since 0.1.0
 *
 * @param string $endpoint Endpoint.
 *
 * @return string
 */
function masteriyo_get_account_endpoint_url( $endpoint ) {
	if ( 'dashboard' === $endpoint ) {
		return masteriyo_get_page_permalink( 'myaccount' );
	}

	if ( 'user-logout' === $endpoint ) {
		return masteriyo_logout_url();
	}

	return masteriyo_get_endpoint_url( $endpoint, '', masteriyo_get_page_permalink( 'myaccount' ) );
}


/**
 * Get endpoint URL.
 *
 * Gets the URL for an endpoint, which varies depending on permalink settings.
 *
 * @since 0.1.0
 *
 * @param  string $endpoint  Endpoint slug.
 * @param  string $value     Query param value.
 * @param  string $permalink Permalink.
 *
 * @return string
 */
function masteriyo_get_endpoint_url( $endpoint, $value = '', $permalink = '' ) {
	if ( ! $permalink ) {
		$permalink = get_permalink();
	}

	// Map endpoint to options.
	$query_vars = masteriyo()->query->get_query_vars();
	$endpoint   = ! empty( $query_vars[ $endpoint ] ) ? $query_vars[ $endpoint ] : $endpoint;

	if ( get_option( 'permalink_structure' ) ) {
		if ( strstr( $permalink, '?' ) ) {
			$query_string = '?' . wp_parse_url( $permalink, PHP_URL_QUERY );
			$permalink    = current( explode( '?', $permalink ) );
		} else {
			$query_string = '';
		}
		$url = trailingslashit( $permalink );

		if ( $value ) {
			$url .= trailingslashit( $endpoint ) . user_trailingslashit( $value );
		} else {
			$url .= user_trailingslashit( $endpoint );
		}

		$url .= $query_string;
	} else {
		$url = add_query_arg( $endpoint, $value, $permalink );
	}

	return apply_filters( 'masteriyo_get_endpoint_url', $url, $endpoint, $value, $permalink );
}

/**
 * Get logout endpoint.
 *
 * @since 0.1.0
 *
 * @param string $redirect Redirect URL.
 *
 * @return string
 */
function masteriyo_logout_url( $redirect = '' ) {
	$redirect = $redirect ? $redirect : apply_filters( 'masteriyo_logout_default_redirect_url', masteriyo_get_page_permalink( 'myaccount' ) );

	if ( get_option( 'masteriyo_logout_endpoint' ) ) {
		return wp_nonce_url( masteriyo_get_endpoint_url( 'user-logout', '', $redirect ), 'user-logout' );
	}

	return wp_logout_url( $redirect );
}

/**
 * Get a svg file contents.
 *
 * @since 0.1.0
 *
 * @param string $name SVG filename.
 * @param boolean $echo Whether to echo the contents or not.
 *
 * @return void|string
 */
function masteriyo_get_svg( $name, $echo = false ) {
	global $wp_filesystem;

	$credentials = request_filesystem_credentials( '', 'direct' );

	// Bail early if the credentials is wrong.
	if ( ! $credentials ) {
		return;
	}

	\WP_Filesystem( $credentials );

	$file_name     = Constants::get( 'MASTERIYO_ASSETS' ) . "/svg/{$name}.svg";
	$file_contents = '';

	if ( file_exists( $file_name ) && is_readable( $file_name ) ) {
		$file_contents = $wp_filesystem->get_contents( $file_name );
	}

	$file_contents = apply_filters( 'masteriyo_svg_file', $file_contents, $name );

	if ( $echo ) {
		echo $file_contents;
	} else {
		return $file_contents;
	}

}

/**
 * Get My Account menu items.
 *
 * @since 0.1.0
 *
 * @return array
 */
function masteriyo_get_account_menu_items() {
	$endpoints = masteriyo_get_myaccount_endpoints();
	$items     = array(
		'dashboard'     => array(
			'label' => __( 'Dashboard', 'masteriyo' ),
			'icon'  => masteriyo_get_svg( 'dashboard' ),
		),
		'courses'       => array(
			'label' => __( 'My Courses', 'masteriyo' ),
			'icon'  => masteriyo_get_svg( 'courses' ),
		),
		'grades'        => array(
			'label' => __( 'My Grades', 'masteriyo' ),
			'icon'  => masteriyo_get_svg( 'grades' ),
		),
		'memberships'   => array(
			'label' => __( 'My Memberships', 'masteriyo' ),
			'icon'  => masteriyo_get_svg( 'memberships' ),
		),
		'certificates'  => array(
			'label' => __( 'My Certificaties', 'masteriyo' ),
			'icon'  => masteriyo_get_svg( 'certificates' ),
		),
		'order-history' => array(
			'label' => __( 'My Order History', 'masteriyo' ),
			'icon'  => masteriyo_get_svg( 'order-history' ),
		),
		'user-logout'   => array(
			'label' => __( 'Logout', 'masteriyo' ),
			'icon'  => masteriyo_get_svg( 'user-logout' ),
		),
	);

	// Remove missing endpoints.
	foreach ( $endpoints as $endpoint_id => $endpoint ) {
		if ( empty( $endpoint ) ) {
			unset( $items[ $endpoint_id ] );
		}
	}

	return apply_filters( 'masteriyo_account_menu_items', $items, $endpoints );
}

/**
 * Echo a string if value of the first argument is truish.
 *
 * @since 0.1.0
 *
 * @param boolean $bool
 * @param string $str
 */
function masteriyo_echo_if( $bool, $str = '' ) {
	if ( ! ! $bool ) {
		echo $str;
	}
}

/**
 * Check if the current page is the myaccount page.
 *
 * @since 0.1.0
 *
 * @return boolean
 */
function masteriyo_is_myaccount_page() {
	global $post;

	if ( $post instanceof \WP_Post ) {
		$page_id = masteriyo_get_page_id( 'myaccount' );

		return $post->ID === $page_id;
	}
	return false;
}

/**
 * Check if the current page is password reset page.
 *
 * @since 0.1.0
 *
 * @return boolean
 */
function masteriyo_is_lost_password_page() {
	return masteriyo_is_myaccount_page() && isset( $GLOBALS['wp']->query_vars['reset-password'] );
}

/**
 * Check if the current page is signup page.
 *
 * @since 0.1.0
 *
 * @return boolean
 */
function masteriyo_is_signup_page() {
	return masteriyo_is_myaccount_page() && isset( $GLOBALS['wp']->query_vars['signup'] );
}

/**
 * Check if the current page is view myaccount page.
 *
 * @since 0.1.0
 *
 * @return boolean
 */
function masteriyo_is_view_myaccount_page() {
	return masteriyo_is_myaccount_page() && isset( $GLOBALS['wp']->query_vars['view-myaccount'] );
}

/**
 * Check if the current page is edit myaccount page.
 *
 * @since 0.1.0
 *
 * @return boolean
 */
function masteriyo_is_edit_myaccount_page() {
	return masteriyo_is_myaccount_page() && isset( $GLOBALS['wp']->query_vars['edit-myaccount'] );
}

/**
 * Get value of an option.
 *
 * @since 0.1.0
 *
 * @param string $setting_name
 * @param mixed $default
 *
 * @return mixed
 */
function masteriyo_get_setting_value( $setting_name, $default = null ) {
	$setting = masteriyo( 'setting' );

	if ( is_null( $setting ) ) {
		return $default;
	}

	masteriyo( 'setting.store' )->read( $setting, $default );

	$value        = $default;
	$setting_name = str_replace( '.', '_', $setting_name );

	if ( is_callable( array( $setting, "get_{$setting_name}" ) ) ) {
		$value = call_user_func_array( array( $setting, "get_{$setting_name}" ), array() );
	}

	return apply_filters( "masteriyo_setting_{$setting_name}_value", $value );
}

/**
 * See if a course has FAQs.
 *
 * @param integer $course_id
 *
 * @return boolean
 */
function masteriyo_course_has_faqs( $course_id ) {
	$faqs = masteriyo_get_faqs(
		array(
			'parent_id' => $course_id,
		)
	);

	return count( $faqs ) > 0;
}

if ( ! function_exists( 'masteriyo_create_new_user_username' ) ) {
	/**
	 * Create a unique username for a new customer.
	 *
	 * @since 0.1.0
	 *
	 * @param string $email New customer email address.
	 * @param array  $new_user_args Array of new user args, maybe including first and last names.
	 * @param string $suffix Append string to username to make it unique.
	 *
	 * @return string Generated username.
	 */
	function masteriyo_create_new_user_username( $email, $new_user_args = array(), $suffix = '' ) {
		$username_parts = array();

		if ( isset( $new_user_args['first_name'] ) ) {
			$username_parts[] = sanitize_user( $new_user_args['first_name'], true );
		}

		if ( isset( $new_user_args['last_name'] ) ) {
			$username_parts[] = sanitize_user( $new_user_args['last_name'], true );
		}

		// Remove empty parts.
		$username_parts = array_filter( $username_parts );

		// If there are no parts, e.g. name had unicode chars, or was not provided, fallback to email.
		if ( empty( $username_parts ) ) {
			$email_parts    = explode( '@', $email );
			$email_username = $email_parts[0];

			// Exclude common prefixes.
			if ( in_array(
				$email_username,
				array(
					'sales',
					'hello',
					'mail',
					'contact',
					'info',
				),
				true
			) ) {
				// Get the domain part.
				$email_username = $email_parts[1];
			}

			$username_parts[] = sanitize_user( $email_username, true );
		}

		$username = masteriyo_strtolower( implode( '.', $username_parts ) );

		if ( $suffix ) {
			$username .= $suffix;
		}

		/**
		 * WordPress 4.4 - filters the list of blocked usernames.
		 *
		 * @since 0.1.0
		 *
		 * @param array $usernames Array of blocked usernames.
		 */
		$illegal_logins = (array) apply_filters( 'illegal_user_logins', array() );

		// Stop illegal logins and generate a new random username.
		if ( in_array( strtolower( $username ), array_map( 'strtolower', $illegal_logins ), true ) ) {
			$new_args = array();

			/**
			 * Filter generated username.
			 *
			 * @since 0.1.0
			 *
			 * @param string $username      Generated username.
			 * @param string $email         New user email address.
			 * @param array  $new_user_args Array of new user args, maybe including first and last names.
			 * @param string $suffix        Append string to username to make it unique.
			 */
			$new_args['first_name'] = apply_filters(
				'masteriyo_generated_username',
				'masteriyo_user_' . zeroise( wp_rand( 0, 9999 ), 4 ),
				$email,
				$new_user_args,
				$suffix
			);

			return masteriyo_create_new_user_username( $email, $new_args, $suffix );
		}

		if ( username_exists( $username ) ) {
			// Generate something unique to append to the username in case of a conflict with another user.
			$suffix = '-' . zeroise( wp_rand( 0, 9999 ), 4 );
			return masteriyo_create_new_user_username( $email, $new_user_args, $suffix );
		}

		/**
		 * Filter new customer username.
		 *
		 * @since 0.1.0
		 *
		 * @param string $username      Customer username.
		 * @param string $email         New customer email address.
		 * @param array  $new_user_args Array of new user args, maybe including first and last names.
		 * @param string $suffix        Append string to username to make it unique.
		 */
		return apply_filters( 'masteriyo_new_user_username', $username, $email, $new_user_args, $suffix );
	}
}

if ( ! function_exists( 'masteriyo_create_new_user' ) ) {
	/**
	 * Create a new customer.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $email    Customer email.
	 * @param  string $username Customer username.
	 * @param  string $password Customer password.
	 * @param  array  $args     List of other arguments.
	 *
	 * @return int|User|WP_Error Returns WP_Error on failure, Int (user ID) on success.
	 */
	function masteriyo_create_new_user( $email, $username = '', $password = '', $args = array() ) {
		if ( empty( $email ) || ! is_email( $email ) ) {
			return new \WP_Error( 'registration-error-invalid-email', __( 'Please provide a valid email address.', 'masteriyo' ) );
		}

		if ( email_exists( $email ) ) {
			$message = apply_filters( 'masteriyo_registration_error_email_exists', __( 'An account is already registered with your email address.', 'masteriyo' ), $email );
			return new \WP_Error( 'registration-error-email-exists', $message );
		}

		if ( masteriyo_registration_is_generate_username() && empty( $username ) ) {
			$username = masteriyo_create_new_user_username( $email, $args );
		}

		$username = sanitize_user( $username );

		if ( empty( $username ) || ! validate_username( $username ) ) {
			return new \WP_Error( 'registration-error-invalid-username', __( 'Please enter a valid account username.', 'masteriyo' ) );
		}

		if ( username_exists( $username ) ) {
			return new \WP_Error( 'registration-error-username-exists', __( 'An account is already registered with that username. Please choose another.', 'masteriyo' ) );
		}

		// Handle password creation.
		$password_generated = false;
		if ( masteriyo_registration_is_generate_password() ) {
			$password           = wp_generate_password();
			$password_generated = true;
		}

		if ( empty( $password ) ) {
			return new \WP_Error( 'registration-error-missing-password', __( 'Please enter an account password.', 'masteriyo' ) );
		}

		// Use WP_Error to handle registration errors.
		$errors = new \WP_Error();

		do_action( 'masteriyo_register_post', $username, $email, $errors );

		$errors = apply_filters( 'masteriyo_registration_errors', $errors, $username, $email );

		if ( $errors->get_error_code() ) {
			return $errors;
		}

		$user = masteriyo( 'user' );
		$user->set_props( (array) $args );
		$user->set_username( $username );
		$user->set_password( $password );
		$user->set_email( $email );
		$user->set_roles( masteriyo_get_setting_value( 'masteriyo_registration_default_role', 'subscriber' ) );

		$user = apply_filters( 'masteriyo_new_user_data', $user );

		masteriyo( 'user.store' )->create( $user );

		if ( ! $user->get_id() ) {
			return new \WP_Error( 'registration-failure', __( 'Registration failed.', 'masteriyo' ) );
		}

		do_action( 'masteriyo_created_customer', $user, $password_generated );

		return $user;
	}
}

/**
 * Login a customer (set auth cookie and set global user object).
 *
 * @since 0.1.0
 *
 * @param int $user_id Customer ID.
 */
function masteriyo_set_customer_auth_cookie( $user_id ) {
	wp_set_current_user( $user_id );
	wp_set_auth_cookie( $user_id, true );

	// Update session.
	masteriyo( 'session' )->init_session_cookie();
}

/**
 * Get course review.
 *
 * @since 0.1.0
 *
 * @param  int|WP_Comment|Model $course_review Object ID or WP_Comment or Model.
 * @return CourseReview|null
 */
function masteriyo_get_course_review( $course_review ) {
	$course_review_obj   = masteriyo( 'course_review' );
	$course_review_store = masteriyo( 'course_review.store' );

	if ( is_a( $course_review, 'ThemeGrill\Masteriyo\Models\CourseReview' ) ) {
		$id = $course_review->get_id();
	} elseif ( is_a( $course_review, 'WP_Comment' ) ) {
		$id = $course_review->comment_ID;
	} else {
		$id = $course_review;
	}

	try {
		$id = absint( $id );
		$course_review_obj->set_id( $id );
		$course_review_store->read( $course_review_obj );
	} catch ( \Exception $e ) {
		return null;
	}

	return apply_filters( 'masteriyo_get_course_review', $course_review_obj, $course_review );
}

/**
 * Set password reset cookie.
 *
 * @since 0.1.0
 *
 * @param string $value Cookie value.
 */
function masteriyo_set_password_reset_cookie( $value = '' ) {
	$rp_cookie = 'wp-resetpass-' . COOKIEHASH;
	$rp_path   = isset( $_SERVER['REQUEST_URI'] ) ? current( explode( '?', wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) : ''; // WPCS: input var ok, sanitization ok.

	if ( $value ) {
		setcookie( $rp_cookie, $value, 0, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
	} else {
		setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
	}
}

/**
 * Get password reset link.
 *
 * @since 0.1.0
 *
 * @param string $reset_key
 * @param int $user_id
 */
function masteriyo_get_password_reset_link( $reset_key, $user_id ) {
	return add_query_arg(
		array(
			'key' => $reset_key,
			'id'  => $user_id,
		),
		masteriyo_get_account_endpoint_url( 'reset-password' )
	);
}

/*
 * Create a page and store the ID in an option.
 *
 * @since 0.1.0
 *
 * @param mixed  $slug Slug for the new page.
 * @param string $setting_name Setting name to store the page's ID.
 * @param string $page_title (default: '') Title for the new page.
 * @param string $page_content (default: '') Content for the new page.
 * @param int    $post_parent (default: 0) Parent for the new page.
 * @return int page ID.
 */
function masteriyo_create_page( $slug, $setting_name = '', $page_title = '', $page_content = '', $post_parent = 0 ) {
	global $wpdb;

	$setting        = masteriyo_get_settings();
	$previous_value = 0;

	if ( method_exists( $setting, "get_pages_{$setting_name}" ) ) {
		$previous_value = call_user_func_array( array( $setting, "get_pages_{$setting_name}" ), array() );
	}

	if ( $previous_value > 0 ) {
		$page_object = get_post( $previous_value );

		if ( $page_object && 'page' === $page_object->post_type && ! in_array( $page_object->post_status, array( 'pending', 'trash', 'future', 'auto-draft' ), true ) ) {
			// Valid page is already in place.
			if ( strlen( $page_content ) > 0 ) {
				// Search for an existing page with the specified page content (typically a shortcode).
				$valid_page_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' ) AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
			} else {
				// Search for an existing page with the specified page slug.
				$valid_page_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' )  AND post_name = %s LIMIT 1;", $slug ) );
			}

			$valid_page_id = apply_filters( 'masteriyo_create_page_id', $valid_page_id, $slug, $page_content );

			if ( $valid_page_id ) {
				return $valid_page_id;
			}
		}
	}

	// Search for a matching valid trashed page.
	if ( strlen( $page_content ) > 0 ) {
		// Search for an existing page with the specified page content (typically a shortcode).
		$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
	} else {
		// Search for an existing page with the specified page slug.
		$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_name = %s LIMIT 1;", $slug ) );
	}

	if ( $trashed_page_found ) {
		$page_id   = $trashed_page_found;
		$page_data = array(
			'ID'          => $page_id,
			'post_status' => 'publish',
		);
		wp_update_post( $page_data );
	} else {
		$page_data = array(
			'post_status'    => 'publish',
			'post_type'      => 'page',
			'post_author'    => 1,
			'post_name'      => $slug,
			'post_title'     => $page_title,
			'post_content'   => $page_content,
			'post_parent'    => $post_parent,
			'comment_status' => 'closed',
		);
		$page_id   = wp_insert_post( $page_data );
	}
	return $page_id;
}

/**
 * Add a post display state for special masteriyo pages in the page list table.
 *
 * @since 0.1.0
 *
 * @param array   $post_states An array of post display states.
 * @param WP_Post $post        The current post object.
 */
function masteriyo_add_post_state( $post_states, $post ) {

	if ( masteriyo_get_page_id( 'course-list' ) === $post->ID ) {
		$post_states['masteriyo_course_list_page'] = __( 'Masteriyo Course List Page', 'masteriyo' );
	}
	if ( masteriyo_get_page_id( 'myaccount' ) === $post->ID ) {
		$post_states['masteriyo_myaccount_page'] = __( 'Masteriyo My Account Page', 'masteriyo' );
	}
	if ( masteriyo_get_page_id( 'masteriyo-checkout' ) === $post->ID ) {
		$post_states['masteriyo_checkout_page'] = __( 'Masteriyo Checkout Page', 'masteriyo' );
	}

	return $post_states;
}
function_exists( 'add_filter' ) && add_filter( 'display_post_states', 'masteriyo_add_post_state', 10, 2 );

function masteriyo_asort_by_locale( &$data, $locale = '' ) {
	// Use Collator if PHP Internationalization Functions (php-intl) is available.
	if ( class_exists( 'Collator' ) ) {
		$locale   = $locale ? $locale : get_locale();
		$collator = new Collator( $locale );
		$collator->asort( $data, Collator::SORT_STRING );
		return $data;
	}

	$raw_data = $data;

	array_walk(
		$data,
		function ( &$value ) {
			$value = remove_accents( html_entity_decode( $value ) );
		}
	);

	uasort( $data, 'strcmp' );

	foreach ( $data as $key => $val ) {
		$data[ $key ] = $raw_data[ $key ];
	}

	return $data;
}

/**
 * Get the store's base location.
 *
 * @since 0.1.0
 * @return array
 */
function masteriyo_get_base_location() {
	$default = apply_filters( 'masteriyo_get_base_location', get_option( 'masteriyo_default_country', 'US:CA' ) );

	return masteriyo_format_country_state_string( $default );
}

/**
 * Formats a string in the format COUNTRY:STATE into an array.
 *
 * @since 2.3.0
 * @param  string $country_string Country string.
 * @return array
 */
function masteriyo_format_country_state_string( $country_string ) {
	if ( strstr( $country_string, ':' ) ) {
		list( $country, $state ) = explode( ':', $country_string );
	} else {
		$country = $country_string;
		$state   = '';
	}
	return array(
		'country' => $country,
		'state'   => $state,
	);
}

/**
 * Check whetheter redirect to cart after course is added.
 *
 * @since 0.1.0
 *
 * @return bool
 */
function masteriyo_cart_redirect_after_add() {
	$redirect_after_add = get_option( 'masteriyo_cart_redirect_after_add', true );
	$redirect_after_add = masteriyo_string_to_bool( $redirect_after_add );
	return apply_filters( 'masteriyo_cart_redirect_after_add', $redirect_after_add );
}

/**
 * Add precision to a number and return a number.
 *
 * @since  0.1.0
 * @param  float $value Number to add precision to.
 * @param  bool  $round If should round after adding precision.
 * @return int|float
 */
function masteriyo_add_number_precision( $value, $round = true ) {
	$cent_precision = pow( 10, masteriyo_get_price_decimals() );
	$value          = $value * $cent_precision;
	return $round ? masteriyo_round( $value, masteriyo_get_rounding_precision() - masteriyo_get_price_decimals() ) : $value;
}

/**
 * Add precision to an array of number and return an array of int.
 *
 * @since  0.1.0
 * @param  array $value Number to add precision to.
 * @param  bool  $round Should we round after adding precision?.
 * @return int|array
 */
function masteriyo_add_number_precision_deep( $value, $round = true ) {
	if ( ! is_array( $value ) ) {
		return masteriyo_add_number_precision( $value, $round );
	}

	foreach ( $value as $key => $sub_value ) {
		$value[ $key ] = masteriyo_add_number_precision_deep( $sub_value, $round );
	}

	return $value;
}

/**
 * Remove precision from a number and return a float.
 *
 * @since  0.1.0
 * @param  float $value Number to add precision to.
 * @return float
 */
function masteriyo_remove_number_precision( $value ) {
	$cent_precision = pow( 10, masteriyo_get_price_decimals() );
	return $value / $cent_precision;
}

/**
 * Remove precision from an array of number and return an array of int.
 *
 * @since  0.1.0
 * @param  array $value Number to add precision to.
 * @return int|array
 */
function masteriyo_remove_number_precision_deep( $value ) {
	if ( ! is_array( $value ) ) {
		return masteriyo_remove_number_precision( $value );
	}

	foreach ( $value as $key => $sub_value ) {
		$value[ $key ] = masteriyo_remove_number_precision_deep( $sub_value );
	}

	return $value;
}

/**
 * Wrapper for set_time_limit to see if it is enabled.
 *
 * @since 0.1.0.
 *
 * @param int $limit Time limit.
 */
function masteriyo_set_time_limit( $limit = 0 ) {
	if ( ! function_exists( 'set_time_limit' ) ) {
		return;
	}

	if ( true === strpos( ini_get( 'disable_functions' ), 'set_time_limit' ) ) {
		return;
	}

	if ( ini_get( 'safe_mode' ) ) {
		return;
	}

	@set_time_limit( $limit );
}

/**
 * Get data if set, otherwise return a default value or null. Prevents notices when data is not set.
 *
 * @since  0.1.0
 * @param  mixed  $var     Variable.
 * @param  string $default Default value.
 * @return mixed
 */
function masteriyo_get_var( &$var, $default = null ) {
	return isset( $var ) ? $var : $default;
}

/**
 * User to sort checkout fields based on priority with uasort.
 *
 * @since 0.1.0
 * @param array $a First field to compare.
 * @param array $b Second field to compare.
 * @return int
 */
function masteriyo_checkout_fields_uasort_comparison( $a, $b ) {
	/*
	 * We are not guaranteed to get a priority
	 * setting. So don't compare if they don't
	 * exist.
	 */
	if ( ! isset( $a['priority'], $b['priority'] ) ) {
		return 0;
	}

	return masteriyo_uasort_comparison( $a['priority'], $b['priority'] );
}

/**
 * User to sort two values with ausort.
 *
 * @since 0.1.0
 * @param int $a First value to compare.
 * @param int $b Second value to compare.
 * @return int
 */
function masteriyo_uasort_comparison( $a, $b ) {
	if ( $a === $b ) {
		return 0;
	}
	return ( $a < $b ) ? -1 : 1;
}

/**
 * Get user agent string.
 *
 * @since  0.1.0
 * @return string
 */
function masteriyo_get_user_agent() {
	return isset( $_SERVER['HTTP_USER_AGENT'] ) ? masteriyo_clean( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '';
}

/**
 * Get WordPress user roles.
 *
 * @since 0.1.0
 * @return string[]
 */
function masteriyo_get_wp_roles() {
	$roles = wp_roles();
	return array_keys( $roles->role_names );
}

/**
 * Get currency code.
 *
 * @since 0.1.0
 *
 * @return string[]
 */
function masteriyo_get_currency_codes() {
	return array_keys( masteriyo_get_currencies() );
}

/**
 * Wrapper for _doing_it_wrong().
 *
 * @since  0.1.0
 * @param string $function Function used.
 * @param string $message Message to log.
 * @param string $version Version the message was added in.
 */
function masteriyo_doing_it_wrong( $function, $message, $version ) {
	// phpcs: disable
	$message .= ' Backtrace: ' . wp_debug_backtrace_summary();

	if ( masteriyo_is_ajax() || masteriyo_is_rest_api_request() ) {
		do_action( 'doing_it_wrong_run', $function, $message, $version );
		error_log( "{$function} was called incorrectly. {$message}. This message was added in version {$version}." );
	} else {
		_doing_it_wrong( $function, $message, $version );
	}
	// phpcs: enable
}

/**
 * Return the Nasteriyo API URL for a given request.
 *
 * @since 0.1.0
 *
 * @param string    $request Requested endpoint.
 * @param bool|null $ssl     If should use SSL, null if should auto detect. Default: null.
 * @return string
 */
function masteriyo_api_request_url( $request, $ssl = null ) {
	if ( is_null( $ssl ) ) {
		$scheme = wp_parse_url( home_url(), PHP_URL_SCHEME );
	} elseif ( $ssl ) {
		$scheme = 'https';
	} else {
		$scheme = 'http';
	}

	if ( strstr( get_option( 'permalink_structure' ), '/index.php/' ) ) {
		$api_request_url = trailingslashit( home_url( '/index.php/masteriyo-api/' . $request, $scheme ) );
	} elseif ( get_option( 'permalink_structure' ) ) {
		$api_request_url = trailingslashit( home_url( '/masteriyo-api/' . $request, $scheme ) );
	} else {
		$api_request_url = add_query_arg( 'masteriyo-api', $request, trailingslashit( home_url( '', $scheme ) ) );
	}

	return esc_url_raw( apply_filters( 'masteriyo_api_request_url', $api_request_url, $request, $ssl ) );
}

/**
 * Prints human-readable information about a variable.
 *
 * Some server environments block some debugging functions. This function provides a safe way to
 * turn an expression into a printable, readable form without calling blocked functions.
 *
 * @since 0.1.0
 *
 * @param mixed $expression The expression to be printed.
 * @param bool  $return     Optional. Default false. Set to true to return the human-readable string.
 * @return string|bool False if expression could not be printed. True if the expression was printed.
 *     If $return is true, a string representation will be returned.
 */
function masteriyo_print_r( $expression, $return = false ) {
	$alternatives = array(
		array(
			'func' => 'print_r',
			'args' => array( $expression, true ),
		),
		array(
			'func' => 'var_export',
			'args' => array( $expression, true ),
		),
		array(
			'func' => 'json_encode',
			'args' => array( $expression ),
		),
		array(
			'func' => 'serialize',
			'args' => array( $expression ),
		),
	);

	$alternatives = apply_filters( 'masteriyo_print_r_alternatives', $alternatives, $expression );

	foreach ( $alternatives as $alternative ) {
		if ( function_exists( $alternative['func'] ) ) {
			$res = $alternative['func']( ...$alternative['args'] );
			if ( $return ) {
				return $res; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			echo $res; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return true;
		}
	}

	return false;
}

/**
 * Get Masteriyo version.
 *
 * @since 0.1.0
 *
 * @return string
 */
function masteriyo_get_version() {
	return Constants::get( 'MASTERIYO_VERSION' );
}

/**
 * Get Masteriyo plugin url.
 *
 * @since 0.1.0
 *
 * @return string
 */
function masteriyo_get_plugin_url() {
	return untrailingslashit( plugin_dir_url( Constants::get( 'MASTERIYO_PLUGIN_FILE' ) ) );
}

/**
 * Get available lesson video sources.
 *
 * @since 0.1.0
 *
 * @return array
 */
function masteriyo_get_lesson_video_sources() {
	$sources = array(
		'self-hosted' => __( 'Self Hosted', 'masteriyo' ),
		'youtube'     => __( 'YouTube', 'masteriyo' ),
	);
	return apply_filters( 'masteriyo_lesson_video_sources', $sources );
}

/**
 * Generate URL for a self hosted lesson video file.
 *
 * @since 0.1.0
 *
 * @param integer|string $lesson_id
 *
 * @return string
 */
function masteriyo_generate_self_hosted_lesson_video_url( $lesson_id ) {
	$lesson = masteriyo_get_lesson( $lesson_id );

	if ( is_null( $lesson ) ) {
		return '';
	}
	$url = add_query_arg(
		array(
			'masteriyo_lesson_vid' => 'yes',
			'course_id'            => $lesson->get_course_id(),
			'lesson_id'            => $lesson->get_id(),
		),
		home_url( '/' )
	);
	return apply_filters( 'masteriyo_self_hosted_lesson_video_url', $url, $lesson );
}

/**
 * Get setting object containg all the masteriyo settings.
 *
 * @since 0.1.0
 *
 * @return Setting
 */
function masteriyo_get_settings() {
	$setting      = masteriyo( 'setting' );
	$setting_repo = masteriyo( 'setting.store' );
	$setting_repo->read( $setting );

	return $setting;
}

/**
 * Get user acitivity statuses.
 *
 * @since 0.1.0
 *
 * @return array
 */
function masteriyo_get_user_activity_statuses() {
	return apply_filters(
		'masteriyo_user_activity_statuses',
		array(
			'any',
			'begin',
			'progress',
			'complete',
		)
	);
}
