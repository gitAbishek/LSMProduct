<?php
/**
 * Masteriyo Course Functions
 *
 * Functions for course specific things.
 *
 * @package Masteriyo\Functions
 * @version 1.0.0
 */

use Masteriyo\Activation;
use Masteriyo\Enums\CourseAccessMode;
use Masteriyo\Enums\OrderStatus;
use Masteriyo\Query\UserCourseQuery;

/**
 * For a given course, and optionally price/qty, work out the price with tax excluded, based on store settings.
 *
 * @since  1.0.0
 * @param  Course $course MASTERIYO_Course object.
 * @param  array      $args Optional arguments to pass course quantity and price.
 * @return float|string Price with tax excluded, or an empty string if price calculation failed.
 */
function masteriyo_get_price_excluding_tax( $course, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'qty'   => '',
			'price' => '',
		)
	);

	$price = '' !== $args['price'] ? max( 0.0, (float) $args['price'] ) : $course->get_price();
	$qty   = '' !== $args['qty'] ? max( 0.0, (float) $args['qty'] ) : 1;

	if ( '' === $price ) {
		return '';
	} elseif ( empty( $qty ) ) {
		return 0.0;
	}

	$line_price   = $price * $qty;
	$return_price = $line_price;

	/**
	 * Filters the course price with tax excluded, based on store settings.
	 *
	 * @since 1.0.0
	 *
	 * @param float $price The course price with tax excluded, based on store settings.
	 * @param integer $qty Item quantity.
	 * @param Masteriyo\Models\Course $course Course object.
	 */
	return apply_filters( 'masteriyo_get_price_excluding_tax', $return_price, $qty, $course );
}

/**
 * Check whether the current user can start taking the course.
 *
 * @since 1.0.0
 *
 * @param int|Masteriyo\Models\Course $course Course object or Course ID.
 * @param int|Masteriyo\Models\User $user User object or User ID.
 *
 * @return bool
 */
function masteriyo_can_start_course( $course, $user = null ) {
	$can_start_course = false;
	$user             = is_null( $user ) ? masteriyo_get_current_user() : $user;
	$course_id        = is_a( $course, 'Masteriyo\Models\Course' ) ? $course->get_id() : $course;
	$user_id          = is_a( $user, 'Masteriyo\Models\User' ) ? $user->get_id() : $user;
	$course           = masteriyo_get_course( $course_id );

	if ( $user_id ) {
		$query = new UserCourseQuery(
			array(
				'course_id' => $course_id,
				'user_id'   => $user_id,
				'per_page'  => 1,
			)
		);

		if ( $course && ! in_array( $course->get_access_mode(), array( CourseAccessMode::OPEN, CourseAccessMode::NEED_REGISTRATION ), true ) ) {
			$user_course = current( $query->get_user_courses() );

			if ( $user_course ) {
				$order            = $user_course->get_order();
				$can_start_course = $order ? OrderStatus::COMPLETED === $order->get_status() : false;
			}
		}
	}

	if ( $course ) {
		if ( CourseAccessMode::OPEN === $course->get_access_mode() ) {
			$can_start_course = true;
		} elseif ( is_user_logged_in() && CourseAccessMode::NEED_REGISTRATION === $course->get_access_mode() ) {
			$can_start_course = true;
		}
	}

	/**
	 * Filters boolean: true if given user can start the given course.
	 *
	 * @since 1.0.0
	 *
	 * @param boolean $can_start_course true if given user can start the given course.
	 * @param Masteriyo\Models\Course $course Course object.
	 * @param Masteriyo\Models\User $user User object.
	 */
	return apply_filters( 'masteriyo_can_start_course', $can_start_course, $course, $user );
}

/**
 * Get the placeholder image.
 *
 * Uses wp_get_attachment_image if using an attachment ID handle responsiveness.
 *
 * @since 1.0.0
 *
 * @param string       $size Image size.
 * @param string|array $attr Optional. Attributes for the image markup. Default empty.
 * @return string
 */
function masteriyo_placeholder_img( $size = 'masteriyo_thumbnail', $attr = '' ) {
	$dimensions        = masteriyo_get_image_size( $size );
	$placeholder_image = get_option( 'masteriyo_placeholder_image', 0 );

	$default_attr = array(
		'class' => 'masteriyo-placeholder wp-post-image',
		'alt'   => __( 'Placeholder', 'masteriyo' ),
	);

	$attr = wp_parse_args( $attr, $default_attr );

	if ( ! wp_attachment_is_image( $placeholder_image ) ) {
		Activation::attach_placeholder_image();
	}

	$image_html = wp_get_attachment_image(
		$placeholder_image,
		$size,
		false,
		$attr
	);

	/**
	 * Filters placeholder image html.
	 *
	 * @since 1.0.0
	 *
	 * @param string $image_html The placeholder image html.
	 * @param string $size Image size.
	 * @param array $dimensions Image dimensions.
	 */
	return apply_filters( 'masteriyo_placeholder_img', $image_html, $size, $dimensions );
}


/**
 * Get the placeholder image URL either from media, or use the fallback image.
 *
 * @since 1.0.0
 *
 * @param string $size Thumbnail size to use.
 * @return string
 */
function masteriyo_placeholder_img_src( $size = 'masteriyo_thumbnail' ) {
	$src               = masteriyo_get_plugin_url() . '/assets/img/placeholder.jpg';
	$placeholder_image = get_option( 'masteriyo_placeholder_image', 0 );

	if ( ! empty( $placeholder_image ) && is_numeric( $placeholder_image ) ) {
		$src = wp_get_attachment_image_url( $placeholder_image, $size );
	}

	/**
	 * Filters placeholder image URL.
	 *
	 * @since 1.0.0
	 *
	 * @param string $src The placeholder image URL.
	 */
	return apply_filters( 'masteriyo_placeholder_img_src', $src );
}

/**
 * Count comments on a course.
 *
 * @since 1.0.0
 *
 * @param mixed $course
 *
 * @return integer
 */
function masteriyo_count_course_comments( $course ) {
	$course = masteriyo_get_course( $course );

	if ( is_null( $course ) ) {
		return 0;
	}

	global $wpdb;

	$comments_count = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(comment_ID)
			FROM {$wpdb->comments}
			WHERE comment_approved = '1'
				AND comment_type = 'mto_course_review'
				AND comment_post_ID = %d
			",
			$course->get_id()
		)
	);

	return absint( $comments_count );
}

/**
 * Get CSS class for course difficulty badge.
 *
 * @since 1.0.0
 *
 * @param string $difficulty
 *
 * @return string
 */
function masteriyo_get_difficulty_badge_css_class( $difficulty ) {
	$classes     = array(
		'beginner'     => 'masteriyo-badge-green',
		'intermediate' => 'masteriyo-badge-yellow',
		'expert'       => 'masteriyo-badge-pink',
	);
	$badge_class = 'masteriyo-badge-green';

	if ( isset( $classes[ $difficulty ] ) ) {
		$badge_class = $classes[ $difficulty ];
	}

	/**
	 * Filters course difficulty badge CSS class.
	 *
	 * @since 1.0.0
	 *
	 * @param string $badge_class CSS class for the badge.
	 * @param string $difficulty Course difficulty.
	 */
	return apply_filters( 'masteriyo_difficulty_badge_css_class', $badge_class, $difficulty );
}

/**
 * Trim course highlights. Selects only the first given number of items.
 *
 * @since 1.0.0
 *
 * @param string $highlights
 * @param integer $limit
 *
 * @return string
 */
function masteriyo_trim_course_highlights( $highlights, $limit = 3 ) {
	/**
	 * Filters maximum number of course highlights limit.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $limit Highlights limit.
	 * @param string $highlights Course highlights.
	 */
	$limit = apply_filters( 'masteriyo_course_highlights_limit', $limit, $highlights );

	// Reference: https://www.regextester.com/27540
	$regex       = '/(<\s*li[^>]*>.*?<\s*\/\s*li>){1,' . $limit . '}/m';
	$result      = preg_match( $regex, $highlights, $matches );
	$matched_str = '';

	if ( $result && ! empty( $matches ) ) {
		$matched_str = $matches[0];
	}
	$trimmed_highlights = '';

	if ( ! empty( $matched_str ) ) {
		$trimmed_highlights = '<ul>' . $matched_str . '</ul>';
	}

	/**
	 * Filters the trimmed (with max number limit) course highlights.
	 *
	 * @since 1.0.0
	 *
	 * @param string $trimmed_highlights Trimmed course highlights.
	 * @param integer $limit Highlights limit.
	 * @param string $highlights Full list of course highlights.
	 */
	return apply_filters( 'masteriyo_trimmed_course_highlights', $trimmed_highlights, $limit, $highlights );
}

/**
 * Get course contents.
 *
 * @since 1.0.0
 *
 * @param integer $course_id
 *
 * @return array
 */
function masteriyo_get_course_contents( $course_id ) {
	$sections_query = new \WP_Query(
		array(
			'post_parent'    => $course_id,
			'post_type'      => 'mto-section',
			'posts_per_page' => -1,

		)
	);
	$sections = array_map( 'masteriyo_get_section', $sections_query->posts );
	$objects  = array_merge( array(), $sections );

	foreach ( $sections as $section ) {
		$section_content_query = new \WP_Query(
			array(
				'post_parent' => $section->get_id(),
				'post_type'   => array( 'mto-lesson', 'mto-quiz' ),
				'post_status' => 'any',

			)
		);
		$contents = array_map(
			function ( $post ) {
				if ( 'mto-lesson' === $post->post_type ) {
					return masteriyo_get_lesson( $post );
				}
				if ( 'mto-quiz' === $post->post_type ) {
					return masteriyo_get_quiz( $post );
				}
				return $post;
			},
			$section_content_query->posts
		);

		if ( 0 < count( $contents ) ) {
			$objects = array_merge( $objects, $contents );
		}
	}

	/**
	 * Filters course contents objects.
	 *
	 * @since 1.0.0
	 *
	 * @param array $objects Course contents objects.
	 * @param integer $course_id Course ID.
	 */
	return apply_filters( 'masteriyo_course_contents', $objects, $course_id );
}

/**
 * Get course structure.
 *
 * @since 1.0.0
 *
 * @param integer $course_id
 *
 * @return array
 */
function masteriyo_get_course_structure( $course_id ) {
	$objects = masteriyo_get_course_contents( $course_id );

	$objects = array_map(
		function( $object ) {
			return array(
				'id'          => $object->get_id(),
				'name'        => $object->get_name( 'edit' ),
				'description' => $object->get_description( 'edit' ),
				'permalink'   => $object->get_permalink( 'edit' ),
				'type'        => $object->get_object_type(),
				'menu_order'  => $object->get_menu_order( 'edit' ),
				'parent_id'   => $object->get_parent_id( 'edit' ),
				'has_video'   => 'lesson' === $object->get_object_type() && ! empty( $object->get_video_source_url() ),
			);
		},
		$objects
	);

	$contents = array_values(
		array_filter(
			$objects,
			function( $object ) {
				return isset( $object['type'] ) && 'section' !== $object['type'];
			}
		)
	);

	// Sort section contents (lessons ane quizzes) by menu order in ascending order.
	usort(
		$contents,
		function( $a, $b ) {
			if ( $a['menu_order'] === $b['menu_order'] ) {
				return 0;
			}

			return $a['menu_order'] > $b['menu_order'] ? 1 : -1;
		}
	);

	$sections = array_values(
		array_filter(
			$objects,
			function( $object ) {
				return isset( $object['type'] ) && 'section' === $object['type'];
			}
		)
	);

	// Sort sections by menu order in ascending order.
	usort(
		$sections,
		function( $a, $b ) {
			if ( $a['menu_order'] === $b['menu_order'] ) {
				return 0;
			}

			return $a['menu_order'] > $b['menu_order'] ? 1 : -1;
		}
	);

	$ordered_sections = array();

	foreach ( $sections as $section ) {
		$section_contents = array();
		$lessons_count    = 0;
		$quiz_count       = 0;

		foreach ( $contents as $content ) {
			if ( $content['parent_id'] !== $section['id'] ) {
				continue;
			}

			if ( 'lesson' === $content['type'] ) {
				$lessons_count++;
			}

			if ( 'quiz' === $content['type'] ) {
				$quiz_count++;
			}

			$section_contents[] = $content;
		}

		$section['contents']      = $section_contents;
		$section['lessons_count'] = $lessons_count;
		$section['quiz_count']    = $quiz_count;
		$ordered_sections[]       = $section;
	}

	/**
	 * Filter course structure.
	 *
	 * @since 1.0.0
	 * @param array $ordered_sections Ordered sections.
	 * @param int $course_id Course ID.
	 */
	return apply_filters( 'masteriyo_course_structure', $ordered_sections, $course_id );
}

/**
 * Get count of a course review's replies.
 *
 * @since 1.0.5
 *
 * @param integer $course_review_id
 *
 * @return integer
 */
function masteriyo_get_course_review_replies_count( $course_review_id ) {
	global $wpdb;

	$replies_count = (int) $wpdb->get_var(
		$wpdb->prepare(
			"
			SELECT COUNT(*) FROM $wpdb->comments
			WHERE comment_parent = %d
			AND comment_approved = '1'
			AND comment_type = 'mto_course_review'
			",
			absint( $course_review_id )
		)
	);

	/**
	 * Filters replies count for a course review.
	 *
	 * @since 1.5.0
	 *
	 * @param integer $replies_count
	 * @param integer $course_review_id
	 */
	return apply_filters( 'masteriyo_get_course_review_replies_count', absint( $replies_count ), $course_review_id );
}

/**
 * Get post counts of post author.
 *
 * @since 1.5.0
 *
 * @param string $type Post type.
 * @param int $user_id User ID.
 * @return stdClass
 */
function masteriyo_count_posts( $type, $user_id ) {
	global $wpdb;

	if ( ! post_type_exists( $type ) ) {
		return new stdClass();
	}

	$results = (array) $wpdb->get_results(
		$wpdb->prepare(
			"SELECT post_status, COUNT( * ) AS num_posts FROM {$wpdb->posts} WHERE post_type = %s AND post_author = %d GROUP BY post_status",
			$type,
			$user_id
		),
		ARRAY_A
	);
	$counts  = array_fill_keys( get_post_stati(), 0 );

	foreach ( $results as $row ) {
		$counts[ $row['post_status'] ] = $row['num_posts'];
	}

	$counts = (object) $counts;

	/**
	 * Modify returned post counts by status for the current post type.
	 *
	 * @since 1.5.0
	 *
	 * @param stdClass $counts An object containing the current post_type's post
	 *                         counts by status.
	 * @param string   $type   Post type.
	 * @param string   $user_id User ID.
	 */
	return apply_filters( 'masteriyo_count_posts', $counts, $type, $user_id );
}
