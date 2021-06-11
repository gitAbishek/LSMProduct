<?php

/**
 * Tab content - Reviews.
 *
 * @version 0.1.0
 */

defined('ABSPATH') || exit; // Exit if accessed directly.

global $course;

$review_count = $course->get_review_count();
$rating = $course->get_average_rating();
$course_reviews = masteriyo_get_course_reviews();

/**
 * masteriyo_before_single_course_reviews_content hook.
 */
do_action('masteriyo_before_single_course_reviews_content');

?>

<div class="mto-stab--treviews">
	<div class="mto-stab-rs mto-flex mto-flex-ycenter">
		<span class="mto-icon-svg mto-flex mto-rstar">
			<?php masteriyo_render_stars($rating, ''); ?>
		</span>

		<span class="mto-rnumber">
			<?php echo esc_html($rating); ?> out of 5
		</span>
	</div>
</div>
<p class="mto-stab--turating">
	<?php /* translators: %s: Review Count */ ?>
	<span>
		<?php printf(esc_html__('%s user ratings', 'masteriyo'), esc_html($review_count)); ?>
	</span>
</p>

<div class="mto-course-reviews-list">
	<?php foreach ($course_reviews as $course_review) : ?>
		<div class="mto-course-review" data-id="<?php echo esc_attr($course_review->get_id()); ?>">
			<div class="mto-flex mto-review">
				<div class="mto-avatar">
					<img src="https://images.unsplash.com/photo-1552234994-66ba234fd567?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1534&q=80" />
				</div>
				<div class="mto-flex mto-flex-column mto-right">
					<div class="rating" data-value="<?php echo esc_attr($course_review->get_rating()); ?>">
						<?php echo esc_html($course_review->get_rating()); ?>
					</div>
					<div class="mto-flex">
						<div class="author-name" data-value="<?php echo esc_attr($course_review->get_author_name()); ?>">
							<?php echo esc_html($course_review->get_author_name()); ?>
						</div>
						<div class="date-created" data-value="<?php echo esc_attr($course_review->get_date_created()); ?>">
							<?php echo esc_html($course_review->get_date_created()); ?>
						</div>
					</div>
					<div class="title" data-value="<?php echo esc_attr($course_review->get_title()); ?>">
						<?php echo esc_html($course_review->get_title()); ?>
					</div>
					<div class="content" data-value="<?php echo esc_attr($course_review->get_content()); ?>">
						<?php echo esc_html($course_review->get_content()); ?>
					</div>
					<?php if (masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() || get_current_user_id() === $course_review->get_author_id()) : ?>
						<div class="mto-flex mto-btngroup">
							<div class="mto-edit-course-review"><a href="#" class="mto-link-primary"><strong class="text">Edit</strong></a></div>
							<div class="mto-delete-course-review"><a href="#" class="mto-link-primary"><strong class="text">Delete</strong></a></div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
<?php if (is_user_logged_in()) : ?>
	<div class="mto-submit-review-form-container">
		<h3 class="mto-maintitle">Create a new review</h3>
		<form method="POST" class="mto-submit-review-form">
			<input type="hidden" name="id" value="">
			<div class="mto-title">
				<label class="mto-label">Title test</label>
				<input type="text" name="title" class="mto-input" required />
			</div>
			<div class="mto-rating">
				<label class="mto-label">Rating</label>
				<!-- <input type="number" name="rating" /> -->
				<div class="mto-stab-rs boxshadow-none ">
					<span class="mto-icon-svg mto-flex mto-rstar">
						<?php masteriyo_render_stars($rating, ''); ?>
					</span>
				</div>
			</div>
			<div class="mto-message">
				<label class="mto-label">Content</label>
				<!-- <textarea class="mto-input"> </textarea> -->
				<input type="text" name="content" class="mto-input" required column="10" />
			</div>
			<div>
				<button type="submit" name="masteriyo-submit-review" value="yes" class="mto-btn-primary">
					<?php echo esc_html__('Submit', 'masteriyo'); ?>
				</button>
			</div>
			<?php wp_nonce_field('masteriyo-submit-review'); ?>
		</form>
	</div>
<?php else : ?>
	<div>You must be <a href="<?php echo esc_attr(masteriyo_get_page_permalink('myaccount')); ?>">logged in</a> to
		submit a review</div>
<?php endif; ?>

<?php

/**
 * masteriyo_after_single_course_reviews_content hook.
 */
do_action('masteriyo_after_single_course_reviews_content');
