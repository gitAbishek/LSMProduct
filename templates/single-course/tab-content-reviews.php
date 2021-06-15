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
					<div class="mto-flex justify-content-between">
						<div class="rating" data-value="<?php echo esc_attr($course_review->get_rating()); ?>">
							<?php echo esc_html($course_review->get_rating()); ?>
						</div>
						<nav class="dropdown">
							<label for="touch"><span class='icon_box'>
									<svg width="5" height="16" viewBox="0 0 5 16" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M2.19629 6C1.09629 6 0.196289 6.9 0.196289 8C0.196289 9.1 1.09629 10 2.19629 10C3.29629 10 4.19629 9.1 4.19629 8C4.19629 6.9 3.29629 6 2.19629 6ZM2.19629 0C1.09629 0 0.196289 0.9 0.196289 2C0.196289 3.1 1.09629 4 2.19629 4C3.29629 4 4.19629 3.1 4.19629 2C4.19629 0.9 3.29629 0 2.19629 0ZM2.19629 12C1.09629 12 0.196289 12.9 0.196289 14C0.196289 15.1 1.09629 16 2.19629 16C3.29629 16 4.19629 15.1 4.19629 14C4.19629 12.9 3.29629 12 2.19629 12Z" fill="black" />
									</svg>
								</span></label>
							<input type="checkbox" id="touch">
							<?php if (masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() || get_current_user_id() === $course_review->get_author_id()) : ?>
								<ul class="slide">
									<li>
										<div class="mto-edit-course-review"><a href="#" class=""><strong class="text">Edit</strong></a></div>
									</li>
									<li>
										<div class="mto-delete-course-review"><a href="#" class=""><strong class="text">Delete</strong></a></div>
									</li>
								</ul>
							<?php endif; ?>
						</nav>

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
	<div class="mto-login-msg">You must be <a href="<?php echo esc_attr(masteriyo_get_page_permalink('myaccount')); ?>" class="mto-link-primary">logged in</a> to
		submit a review</div>
<?php endif; ?>

<?php

/**
 * masteriyo_after_single_course_reviews_content hook.
 */
do_action('masteriyo_after_single_course_reviews_content');
