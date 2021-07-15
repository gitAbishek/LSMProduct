<?php
/**
 * Template for displaying related posts/courses.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

$related_courses = masteriyo_get_related_courses( $GLOBALS['course'] );

do_action( 'masteriyo_before_related_posts_content' );

?>
<div class="mto-related-post">
	<h3 class="mto-related-post--title">Related Post</h3>

	<div class="mto-scourse mto-item--wrap">
		<?php foreach ( $related_courses as $course ) { ?>
			<div class="mto-course-item">
				<div class="mto-course--card">
					<div class="mto-course--img-wrap">
						<?php if ( is_numeric( $course->get_price() ) ) : ?>
							<span class="price-tag">$<?php echo esc_html( $course->get_price() ); ?></span>
						<?php endif; ?>

						<!-- Featured Image -->
						<?php if ( empty( $course->get_featured_image_url() ) ) : ?>
							<img class="mto-w-full" src="https://via.placeholder.com/150" alt="Course featured image">
						<?php else : ?>
							<img
								class="mto-w-full"
								src="<?php echo $course->get_featured_image_url(); ?>"
								alt="Course featured image"
							>
						<?php endif; ?>
					</div>

					<div class="mto-course--header">
						<div class="mto-rt">
							<span class="mto-icon-svg mto-flex mto-rating">
							<?php masteriyo_render_stars( $course->get_average_rating(), '' ); ?>
							</span>

							<?php
							if ( count( $course->get_category_ids() ) > 0 ) {
								$cat = masteriyo_get_course_cat( $course->get_category_ids()[0] );

								printf(
									'<a href="%s" class="mto-badge mto-badge-pink mto-tag">%s</a>',
									$cat->get_permalink(),
									$cat->get_name()
								);
							}
							?>
						</div>


						<h2 class="mto-title">
						<?php echo esc_html( $course->get_name() ); ?>
						</h2>
						<div class="mto-time-btn">
							<span class="mto-duration">
								<span class="mto-icon-svg">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
										<path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"></path>
										<path d="M13 7h-2v6h6v-2h-4z"></path>
									</svg>
								</span>

								<time class="mto-inline-block mto-text-sm">
									<?php echo masteriyo_get_lecture_hours( $course, '%H%h %M%m' ); ?>
								</time>
							</span>
							<a href="#" class="mto-course--btn mto-btn mto-btn-primary">
								Enroll Now
							</a>
						</div>
					</div>
				</div>
		</div>
		<?php } ?>
	</div>
</div>

<?php

do_action( 'masteriyo_after_related_posts_content' );

