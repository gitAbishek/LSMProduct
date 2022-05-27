<?php
/**
 * The Template for displaying course reviews list in single course page
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/single-course/reviews-list.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates
 * @version 1.0.5
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<div class="masteriyo-course-reviews-list">
	<?php foreach ( $course_reviews as $course_review ) : ?>
		<?php
		/**
		 * Fires before rendering a course review in reviews list section in single course page.
		 *
		 * @since 1.0.5
		 *
		 * @param \Masteriyo\Models\CourseReview $course_review The course review object.
		 */
		do_action( 'masteriyo_template_course_review', $course_review );
		?>

		<?php if ( ! empty( $replies[ $course_review->get_id() ] ) ) : ?>
			<div class="masteriyo-course-review-replies">
				<?php
				foreach ( $replies[ $course_review->get_id() ] as $reply ) {
					/**
					 * Action hook for rendering course review reply template.
					 *
					 * @since 1.0.5
					 *
					 * @param array $args Template args.
					 */
					do_action(
						'masteriyo_template_course_review_reply',
						array(
							'course_review' => $course_review,
							'reply'         => $reply,
						)
					);
				}
				?>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>
<?php
