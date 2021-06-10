<?php
/**
 * Tab content - Reviews.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

global $course;

$review_count = $course->get_review_count();
$rating = $course->get_average_rating();
$course_reviews = masteriyo_get_course_reviews();

/**
 * masteriyo_before_single_course_reviews_content hook.
 */
do_action( 'masteriyo_before_single_course_reviews_content' );

?>

<div class="mto-stab--treviews">
	<div class="mto-stab-rs mto-flex mto-flex-ycenter">
		<span class="mto-icon-svg mto-flex mto-rstar">
			<?php masteriyo_render_stars( $rating, '' ); ?>
		</span>

		<span class="mto-rnumber">
			<?php echo esc_html( $rating ); ?> out of 5
		</span>
	</div>
</div>
<p class="mto-stab--turating">
	<?php /* translators: %s: Review Count */ ?>
	<span>
		<?php printf( esc_html__( '%s user ratings', 'masteriyo' ), esc_html( $review_count ) ); ?>
	</span>
</p>

<div class="mto-course-reviews-list">
	<?php foreach ( $course_reviews as $course_review ): ?>
	<div class="mto-course-review" data-id="<?php echo esc_attr( $course_review->get_id() ); ?>">
		<div class="title" data-value="<?php echo esc_attr( $course_review->get_title() ); ?>">
			Title: <?php echo esc_html( $course_review->get_title() ); ?>
		</div>
		<div class="content" data-value="<?php echo esc_attr( $course_review->get_content() ); ?>">
			Content: <?php echo esc_html( $course_review->get_content() ); ?>
		</div>
		<div class="author-name" data-value="<?php echo esc_attr( $course_review->get_author_name() ); ?>">
			Author: <?php echo esc_html( $course_review->get_author_name() ); ?>
		</div>
		<div class="rating" data-value="<?php echo esc_attr( $course_review->get_karma() ); ?>">
			Rating: <?php echo esc_html( $course_review->get_karma() ); ?>
		</div>
		<div class="date-created" data-value="<?php echo esc_attr( $course_review->get_date_created() ); ?>">
			Date: <?php echo esc_html( $course_review->get_date_created() ); ?>
		</div>
		<?php if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() || get_current_user_id() === $course_review->get_author_id() ): ?>
			<div class="mto-edit-course-review"><a href="#"><strong class="text">Edit</strong></a></div>
			<div class="mto-delete-course-review"><a href="#"><strong class="text">Delete</strong></a></div>
		<?php endif; ?>
	</div>
	<?php endforeach; ?>
</div>
<?php if ( is_user_logged_in() ): ?>
<div class="mto-submit-review-form-container">
	<form method="POST" class="mto-submit-review-form">
		<input type="hidden" name="id" value="">
		<div>
			<label>Title</label>
			<input type="text" name="title" required />
		</div>
		<div>
			<label>Rating</label>
			<input type="number" name="rating" />
		</div>
		<div>
			<label>Content</label>
			<input type="text" name="content" required />
		</div>
		<div>
			<button type="submit" name="masteriyo-submit-review" value="yes" class="mto-reset-btn mto-btn mto-primary">
				<?php echo esc_html__('Submit', 'masteriyo'); ?>
			</button>
		</div>
		<?php wp_nonce_field( 'masteriyo-submit-review' ); ?>
	</form>
</div>
<?php else: ?>
<div>You must be <a href="<?php echo esc_attr( masteriyo_get_page_permalink( 'myaccount' ) ); ?>">logged in</a> to
	submit a review</div>
<?php endif; ?>

<?php

/**
 * masteriyo_after_single_course_reviews_content hook.
 */
do_action( 'masteriyo_after_single_course_reviews_content' );
