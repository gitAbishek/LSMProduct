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
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * masteriyo_before_single_course_reviews hook.
 */
do_action( 'masteriyo_before_single_course_reviews' );

?>
<div class="tab-content course-reviews masteriyo-hidden">
	<div class="masteriyo-stab--treviews">
		<div class="masteriyo-stab-rs">
			<span class="masteriyo-icon-svg masteriyo-flex masteriyo-rstar">
				<?php masteriyo_render_stars( $course->get_average_rating() ); ?>
			</span>

			<span class="masteriyo-rnumber">
				<?php echo esc_html( masteriyo_round( $course->get_average_rating(), 1 ) ); ?> <?php esc_html_e( 'out of', 'masteriyo' ); ?> <?php echo esc_html( masteriyo_get_max_course_rating() ); ?>
			</span>
		</div>
	</div>
	<p class="masteriyo-stab--turating">
		<span>
			<?php
				printf(
					/* translators: %d: Course comments count */
					esc_html( _nx( '%s user rating', '%s user ratings', $course->get_review_count(), 'Course reviews', 'masteriyo' ) ),
					esc_html( number_format_i18n( $course->get_review_count() ) )
				);
				?>
		</span>
	</p>

	<div class="masteriyo-course-reviews-list">
		<?php foreach ( $course_reviews as $course_review ) : ?>
			<!-- Course Review -->
			<div class="masteriyo-course-review" data-id="<?php echo esc_attr( $course_review->get_id() ); ?>">
				<input type="hidden" name="parent" value="<?php echo esc_attr( $course_review->get_parent() ); ?>">
				<div class="masteriyo-flex masteriyo-review masteriyo-border-none masteriyo-course-review__content">
					<div class="masteriyo-avatar">
						<?php if ( ! $course_review->get_author() ) : ?>
							<img src="<?php echo esc_attr( $pp_placeholder ); ?>" />
						<?php else : ?>
							<img src="<?php echo esc_attr( $course_review->get_author()->get_avatar_url() ); ?>" />
						<?php endif; ?>
					</div>
					<div class="masteriyo-right">
						<div class="masteriyo-right__rating">
							<div class="rating" data-value="<?php echo esc_attr( $course_review->get_rating() ); ?>">
								<span class="masteriyo-icon-svg masteriyo-flex masteriyo-rstar">
									<?php masteriyo_render_stars( $course_review->get_rating() ); ?>
								</span>
							</div>
							<?php if ( masteriyo_current_user_can_edit_course_review( $course_review ) ) : ?>
								<nav class="masteriyo-dropdown">
									<label class="menu-toggler">
										<span class='icon_box'>
											<?php masteriyo_get_svg( 'small-hamburger', true ); ?>
										</span>
									</label>
									<ul class="slide menu">
										<li class="masteriyo-edit-course-review"><strong><?php esc_html_e( 'Edit', 'masteriyo' ); ?></strong></li>
										<li class="masteriyo-delete-course-review"><strong><?php esc_html_e( 'Delete', 'masteriyo' ); ?></strong></li>
									</ul>
								</nav>
							<?php endif; ?>
						</div>
						<div class="masteriyo-flex">
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
						<span class="masteriyo-reply-course-review"><?php esc_html_e( 'Reply', 'masteriyo' ); ?></span>
					</div>
				</div>
			</div>

			<?php if ( ! empty( $replies[ $course_review->get_id() ] ) ) : ?>
				<div class="masteriyo-course-review-replies">
					<?php foreach ( $replies[ $course_review->get_id() ] as $reply ) : ?>
						<!-- Course Review (Reply) -->
						<div class="masteriyo-course-review is-course-review-reply" data-id="<?php echo esc_attr( $reply->get_id() ); ?>">
							<input type="hidden" name="parent" value="<?php echo esc_attr( $course_review->get_id() ); ?>">
							<div class="rating" data-value="0"></div>
							<div class="masteriyo-review masteriyo-flex masteriyo-replies masteriyo-border-none">
								<div class="masteriyo-avatar">
									<?php if ( ! $reply->get_author() ) : ?>
										<img src="<?php echo esc_attr( $pp_placeholder ); ?>" />
									<?php else : ?>
										<img src="<?php echo esc_attr( $reply->get_author()->get_avatar_url() ); ?>" />
									<?php endif; ?>
								</div>
								<div class="masteriyo-flex  justify-content-between masteriyo-reply-replies">
									<div class="masteriyo-right">
										<div class="masteriyo-reply-replies--title">
					  <div class="masteriyo-flex">
						<div class="author-name" data-value="<?php echo esc_attr( $reply->get_author_name() ); ?>">
						  <?php echo esc_html( $reply->get_author_name() ); ?>
						</div>
						<div class="date-created" data-value="<?php echo esc_attr( $reply->get_date_created() ); ?>">
						  <?php echo esc_html( $reply->get_date_created() ); ?>
						</div>
					  </div>
								 <?php if ( masteriyo_current_user_can_edit_course_review( $reply ) ) : ?>
										<nav class="masteriyo-dropdown">
											<label class="menu-toggler">
												<span class='icon_box'>
													<?php masteriyo_get_svg( 'small-hamburger', true ); ?>
												</span>
											</label>
											<ul class="slide menu">
												<li class="masteriyo-edit-course-review"><strong><?php esc_html_e( 'Edit', 'masteriyo' ); ?></strong></li>
												<li class="masteriyo-delete-course-review"><strong><?php esc_html_e( 'Delete', 'masteriyo' ); ?></strong></li>
											</ul>
										</nav>
									<?php endif; ?>

										</div>
										<div class="content" data-value="<?php echo esc_attr( $reply->get_content() ); ?>">
											<?php echo esc_html( $reply->get_content() ); ?>
										</div>
									</div>

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
