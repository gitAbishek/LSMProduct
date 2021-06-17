<?php

/**
 * Tab content - Reviews.
 *
 * @version 0.1.0
 */

defined('ABSPATH') || exit; // Exit if accessed directly.

global $course;

$review_count        = $course->get_review_count();
$rating              = $course->get_average_rating();
$reviews_and_replies = masteriyo_get_course_reviews_and_replies($course);
$course_reviews      = $reviews_and_replies['reviews'];
$replies             = $reviews_and_replies['replies'];
$pp_placeholder      = masteriyo_get_course_review_author_pp_placeholder();

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
			<?php echo esc_html($rating); ?> <?php _e('out of', 'masteriyo'); ?> <?php echo esc_html(masteriyo_get_max_course_rating()); ?>
		</span>
	</div>
</div>
<p class="mto-stab--turating">
	<span>
		<?php /* translators: %s: Review Count */ ?>
		<?php printf(esc_html__('%s user ratings', 'masteriyo'), esc_html($review_count)); ?>
	</span>
</p>

<div class="mto-course-reviews-list">
	<?php foreach ($course_reviews as $course_review) : ?>
		<!-- Course Review -->
		<div class="mto-course-review" data-id="<?php echo esc_attr($course_review->get_id()); ?>">
			<input type="hidden" name="parent" value="<?php echo esc_attr($course_review->get_parent()); ?>">
			<div class="mto-flex mto-review">
				<div class="mto-avatar">
					<?php if (!$course_review->get_author()) : ?>
						<img src="<?php echo esc_attr($pp_placeholder); ?>" />
					<?php else :  ?>
						<img src="<?php echo esc_attr($course_review->get_author()->get_avatar_url()); ?>" />
					<?php endif; ?>
				</div>
				<div class="mto-flex mto-flex-column mto-right">
					<div class="mto-flex justify-content-between">
						<div class="rating" data-value="<?php echo esc_attr($course_review->get_rating()); ?>">
							<span class="mto-icon-svg mto-flex mto-rstar">
								<?php masteriyo_render_stars($course_review->get_rating()); ?>
							</span>
						</div>
						<?php if (masteriyo_current_user_can_edit_course_review($course_review)) : ?>
							<nav class="dropdown">
								<label class="menu-toggler">
									<span class='icon_box'>
										<svg width="5" height="16" viewBox="0 0 5 16" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M2.19629 6C1.09629 6 0.196289 6.9 0.196289 8C0.196289 9.1 1.09629 10 2.19629 10C3.29629 10 4.19629 9.1 4.19629 8C4.19629 6.9 3.29629 6 2.19629 6ZM2.19629 0C1.09629 0 0.196289 0.9 0.196289 2C0.196289 3.1 1.09629 4 2.19629 4C3.29629 4 4.19629 3.1 4.19629 2C4.19629 0.9 3.29629 0 2.19629 0ZM2.19629 12C1.09629 12 0.196289 12.9 0.196289 14C0.196289 15.1 1.09629 16 2.19629 16C3.29629 16 4.19629 15.1 4.19629 14C4.19629 12.9 3.29629 12 2.19629 12Z" fill="black" />
										</svg>
									</span>
								</label>
								<ul class="slide menu">
									<li class="mto-edit-course-review"><strong><?php _e('Edit', 'masteriyo'); ?></strong></li>
									<li class="mto-reply-course-review"><strong><?php _e('Reply', 'masteriyo'); ?></strong></li>
									<li class="mto-delete-course-review"><strong><?php _e('Delete', 'masteriyo'); ?></strong></li>
								</ul>
							</nav>
						<?php endif; ?>
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
				</div>
			</div>
		</div>

		<?php if (!empty($replies[$course_review->get_id()])) : ?>
			<div class="mto-course-review-replies">
				<div><strong><?php _e( 'Replies', 'masteriyo' ); ?>:</strong></div>
				<?php foreach ($replies[$course_review->get_id()] as $reply) : ?>
					<!-- Course Review (Reply) -->
					<div class="mto-course-review is-course-review-reply" data-id="<?php echo esc_attr($reply->get_id()); ?>">
						<input type="hidden" name="parent" value="<?php echo esc_attr($course_review->get_id()); ?>">
						<div class="rating" data-value="0"></div>
						<div class="mto-flex mto-review">
							<div class="mto-avatar">
								<?php if (!$reply->get_author()) : ?>
									<img src="<?php echo esc_attr($pp_placeholder); ?>" />
								<?php else :  ?>
									<img src="<?php echo esc_attr($reply->get_author()->get_avatar_url()); ?>" />
								<?php endif; ?>
							</div>
							<div class="mto-flex mto-flex-column mto-right">
								<div class="mto-flex">
									<div class="author-name" data-value="<?php echo esc_attr($reply->get_author_name()); ?>">
										<?php echo esc_html($reply->get_author_name()); ?>
									</div>
									<div class="date-created" data-value="<?php echo esc_attr($reply->get_date_created()); ?>">
										<?php echo esc_html($reply->get_date_created()); ?>
									</div>
								</div>
								<div class="content" data-value="<?php echo esc_attr($reply->get_content()); ?>">
									<?php echo esc_html($reply->get_content()); ?>
								</div>
							</div>
							<?php if (masteriyo_current_user_can_edit_course_review($reply)) : ?>
								<nav class="dropdown">
									<label class="menu-toggler">
										<span class='icon_box'>
											<svg width="5" height="16" viewBox="0 0 5 16" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M2.19629 6C1.09629 6 0.196289 6.9 0.196289 8C0.196289 9.1 1.09629 10 2.19629 10C3.29629 10 4.19629 9.1 4.19629 8C4.19629 6.9 3.29629 6 2.19629 6ZM2.19629 0C1.09629 0 0.196289 0.9 0.196289 2C0.196289 3.1 1.09629 4 2.19629 4C3.29629 4 4.19629 3.1 4.19629 2C4.19629 0.9 3.29629 0 2.19629 0ZM2.19629 12C1.09629 12 0.196289 12.9 0.196289 14C0.196289 15.1 1.09629 16 2.19629 16C3.29629 16 4.19629 15.1 4.19629 14C4.19629 12.9 3.29629 12 2.19629 12Z" fill="black" />
											</svg>
										</span>
									</label>
									<ul class="slide menu">
										<li class="mto-edit-course-review"><strong><?php _e('Edit', 'masteriyo'); ?></strong></li>
										<li class="mto-delete-course-review"><strong><?php _e('Delete', 'masteriyo'); ?></strong></li>
									</ul>
								</nav>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>
<?php if (is_user_logged_in()) : ?>
	<div class="mto-submit-review-form-container">
		<h3 class="mto-maintitle mto-form-title"><?php _e('Create a new review', 'masteriyo'); ?></h3>
		<form method="POST" class="mto-submit-review-form">
			<input type="hidden" name="id" value="">
			<input type="hidden" name="course_id" value="<?php echo esc_attr($course->get_id()); ?>">
			<input type="hidden" name="parent" value="0">
			<div class="mto-title">
				<label class="mto-label"><?php _e('Title', 'masteriyo'); ?></label>
				<input type="text" name="title" class="mto-input" />
			</div>
			<div class="mto-rating">
				<label class="mto-label"><?php _e('Rating', 'masteriyo'); ?></label>
				<input type="hidden" name="rating" value="0" />
				<div class="mto-stab-rs boxshadow-none ">
					<span class="mto-icon-svg mto-flex mto-rstar">
						<?php masteriyo_render_stars($rating, 'mto-rating-input-icon'); ?>
					</span>
				</div>
			</div>
			<div class="mto-message">
				<label class="mto-label"><?php _e('Content', 'masteriyo'); ?></label>
				<textarea type="text" name="content" class="mto-input" required column="10"></textarea>
			</div>
			<div>
				<button type="submit" name="masteriyo-submit-review" value="yes" class="mto-btn-primary">
					<?php esc_html_e('Submit', 'masteriyo'); ?>
				</button>
			</div>
			<?php wp_nonce_field('masteriyo-submit-review'); ?>
		</form>
	</div>
<?php else : ?>
	<div class="mto-login-msg">
		<?php
		printf(
			/* translators: %s: Achor tag html with text "logged in" */
			esc_html__('You must be %s to submit a review', 'masteriyo'),
			sprintf(
				'<a href="%s" class="mto-link-primary">%s</a>',
				masteriyo_get_page_permalink('myaccount'),
				__('logged in', 'masteriyo')
			)
		);
		?>
	</div>
<?php endif; ?>

<?php

/**
 * masteriyo_after_single_course_reviews_content hook.
 */
do_action('masteriyo_after_single_course_reviews_content');
