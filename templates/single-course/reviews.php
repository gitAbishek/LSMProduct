<?php
/**
 * The Template for displaying course reviews in single course page
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/single-course/reviews.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * masteriyo_before_single_course_reviews hook.
 */
do_action( 'masteriyo_before_single_course_reviews' );

?>
<div class="tab-content course-reviews mto-hidden">
	<div class="mto-stab--treviews plr-32">
		<div class="mto-stab-rs mto-flex mto-flex-ycenter">
			<span class="mto-icon-svg mto-flex mto-rstar">
				<?php masteriyo_render_stars( $course->get_average_rating() ); ?>
			</span>

			<span class="mto-rnumber">
				<?php echo esc_html( masteriyo_round( $course->get_average_rating(), 1 ) ); ?> <?php esc_html_e( 'out of', 'masteriyo' ); ?> <?php echo esc_html( masteriyo_get_max_course_rating() ); ?>
			</span>
		</div>
	</div>
	<p class="mto-stab--turating plr-32">
		<span>
			<?php /* translators: %s: Review Count */ ?>
			<?php printf( esc_html__( '%s user ratings', 'masteriyo' ), esc_html( $course->get_review_count() ) ); ?>
		</span>
	</p>

	<div class="mto-course-reviews-list plr-32">
		<?php foreach ( $course_reviews as $course_review ) : ?>
			<!-- Course Review -->
			<div class="mto-course-review" data-id="<?php echo esc_attr( $course_review->get_id() ); ?>">
				<input type="hidden" name="parent" value="<?php echo esc_attr( $course_review->get_parent() ); ?>">
				<div class="mto-flex mto-review mto-border-none">
					<div class="mto-avatar">
						<?php if ( ! $course_review->get_author() ) : ?>
							<img src="<?php echo esc_attr( $pp_placeholder ); ?>" />
						<?php else : ?>
							<img src="<?php echo esc_attr( $course_review->get_author()->get_avatar_url() ); ?>" />
						<?php endif; ?>
					</div>
					<div class="mto-flex mto-flex-column mto-right">
						<div class="mto-flex justify-content-between">
							<div class="rating" data-value="<?php echo esc_attr( $course_review->get_rating() ); ?>">
								<span class="mto-icon-svg mto-flex mto-rstar">
									<?php masteriyo_render_stars( $course_review->get_rating() ); ?>
								</span>
							</div>
							<?php if ( masteriyo_current_user_can_edit_course_review( $course_review ) ) : ?>
								<nav class="dropdown">
									<label class="menu-toggler">
										<span class='icon_box'>
											<?php masteriyo_get_svg( 'small-hamburger', true ); ?>
										</span>
									</label>
									<ul class="slide menu">
										<li class="mto-edit-course-review"><strong><?php esc_html_e( 'Edit', 'masteriyo' ); ?></strong></li>
										<li class="mto-delete-course-review"><strong><?php esc_html_e( 'Delete', 'masteriyo' ); ?></strong></li>
									</ul>
								</nav>
							<?php endif; ?>
						</div>
						<div class="mto-flex">
							<div class="author-name" data-value="<?php echo esc_attr( $course_review->get_author_name() ); ?>">
								<?php echo esc_html( $course_review->get_author_name() ); ?>
							</div>
							<div class="date-created" data-value="<?php echo esc_attr( $course_review->get_date_created() ); ?>">
								<?php echo esc_html( $course_review->get_date_created() ); ?>
							</div>
						</div>
						<div class="title" data-value="<?php echo esc_attr( $course_review->get_title() ); ?>">
							<?php echo esc_html( $course_review->get_title() ); ?>
						</div>
						<div class="content" data-value="<?php echo esc_attr( $course_review->get_content() ); ?>">
							<?php echo esc_html( $course_review->get_content() ); ?>
						</div>
						<span class="mto-reply-course-review"><?php esc_html_e( 'Reply', 'masteriyo' ); ?></span>
					</div>
				</div>
			</div>

			<?php if ( ! empty( $replies[ $course_review->get_id() ] ) ) : ?>
				<div class="mto-course-review-replies">
					<?php foreach ( $replies[ $course_review->get_id() ] as $reply ) : ?>
						<!-- Course Review (Reply) -->
						<div class="mto-course-review is-course-review-reply" data-id="<?php echo esc_attr( $reply->get_id() ); ?>">
							<input type="hidden" name="parent" value="<?php echo esc_attr( $course_review->get_id() ); ?>">
							<div class="rating" data-value="0"></div>
							<div class="mto-review mto-flex mto-replies mto-border-none">
								<div class="mto-avatar">
									<?php if ( ! $reply->get_author() ) : ?>
										<img src="<?php echo esc_attr( $pp_placeholder ); ?>" />
									<?php else : ?>
										<img src="<?php echo esc_attr( $reply->get_author()->get_avatar_url() ); ?>" />
									<?php endif; ?>
								</div>
								<div class="mto-flex  justify-content-between mto-reply-replies">
									<div class="mto-flex mto-flex-column mto-right">
										<div class="mto-flex">
											<div class="author-name" data-value="<?php echo esc_attr( $reply->get_author_name() ); ?>">
												<?php echo esc_html( $reply->get_author_name() ); ?>
											</div>
											<div class="date-created" data-value="<?php echo esc_attr( $reply->get_date_created() ); ?>">
												<?php echo esc_html( $reply->get_date_created() ); ?>
											</div>
										</div>
										<div class="content" data-value="<?php echo esc_attr( $reply->get_content() ); ?>">
											<?php echo esc_html( $reply->get_content() ); ?>
										</div>
									</div>
									<?php if ( masteriyo_current_user_can_edit_course_review( $reply ) ) : ?>
										<nav class="dropdown">
											<label class="menu-toggler">
												<span class='icon_box'>
													<?php masteriyo_get_svg( 'small-hamburger', true ); ?>
												</span>
											</label>
											<ul class="slide menu">
												<li class="mto-edit-course-review"><strong><?php esc_html_e( 'Edit', 'masteriyo' ); ?></strong></li>
												<li class="mto-delete-course-review"><strong><?php esc_html_e( 'Delete', 'masteriyo' ); ?></strong></li>
											</ul>
										</nav>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>

	<?php do_action( 'masteriyo_single_course_review_form' ); ?>
</div>
<?php

/**
 * masteriyo_after_single_course_reviews hook.
 */
do_action( 'masteriyo_after_single_course_reviews' );
