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

<section class="mto-hidden md:mto-block mto-mt-10">
	<h3 class="mto-font-bold mto-text-2xl mto-mb-5">Related Post</h3>
	<div class="mto-flex mto-flex-wrap mto-flex-row">
	<?php foreach ( $related_courses as $course ) { ?>
		<div class="mto-group course-card mto-ml-0 mto-max-h-429 mto-overflow-hidden">
			<div class="mto-relative">
				<?php if ( is_numeric( $course->get_price() ) ): ?>
					<span class="price-tag">$<?php echo $course->get_price(); ?></span>
				<?php endif; ?>
				<img class="mto-w-full" src="<?php echo wp_get_attachment_url( $course->get_featured_image() ) ?>" alt="Course featured image" />
			</div>
			<!-- Hidden Card -->
			<div class="course-author-detail mto-opacity-0 group-hover:mto-opacity-100 mto-absolute group-hover:mto-top-0 group-hover:mto-bottom-0 mto-z-50 mto-flex-col mto-justify-between mto-bg-primary">
				<div class="mto-px-4 mto-py-4">
					<div class="mto-mt-4 mto-mb-3">
						<span class="mto-inline-block">
							<?php masteriyo_render_stars( $course->get_average_rating(), 'mto-text-white mto-w-4 mto-h-4' );?>
						</span>

						<?php
						if ( count( $course->get_category_ids() ) > 0 ) {
							$cat = masteriyo_get_course_cat( $course->get_category_ids()[0] );

							printf(
								'<a href="%s" class="mto-inline-block md:mto-block lg:mto-inline-block mto-bg-secondary mto-rounded-full mto-mb-3 lg:mto-mb-0 mto-px-4 mto-py-1 mto-text-xs mto-uppercase mto-font-medium mto-text-white mto-ml-2">%s</a>',
								$cat->get_permalink(),
								$cat->get_name()
							);
						}
						?>
					</div>

					<h2 class="mto-font-bold mto-text-white mto-text-base mto-capitalize mb-2"><?php echo $course->get_name(); ?></h2>
					<div class="course-author-detail">
						<div class="mto-flex mto-flex-row mto-items-center mto-mt-4">
							<img src="<?php echo get_avatar_url( $course->get_author_id() ); ?>" class="mto-rounded-full mto-border-2 mto-border-white mto-w-7 mto-h-7" alt="" />
							<span class="mto-ml-1.5 mto-font-medium mto-text-sm mto-text-white"><?php echo get_userdata( $course->get_author_id() )->display_name; ?></span>
						</div>

						<p class="mto-font-sm mto-text-white mto-mt-4">
							<?php echo $course->get_short_description(); ?>
						</p>

						<a href="<?php echo get_permalink( $course->get_id() ); ?>" class="btn course-author-readmore hover:mto-bg-white">Read More</a>
					</div>
				</div>
				<div class="course-time-share mto-absolute mto-bottom-0 mto-left-0 mto-w-full mto-border mto-border-opacity-10">
					<div class="course-readtime mto-w-full mto-flex mto-justify-center mto-py-2">
						<svg class="mto-inline-block mto-fill-current mto-text-white mto-w-4 mto-mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
							<path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"/>
							<path d="M13 7h-2v6h6v-2h-4z"/>
						</svg>

						<time class="mto-inline-block mto-text-sm mto-text-white">
							<?php echo masteriyo_get_lecture_hours( $course, '%H%h %M%m' ) ?>
						</time>
					</div>


					<div class="course-member mto-w-full mto-flex mto-border-r mto-border-l  mto-border-opacity-10 mto-justify-center mto-py-2">
						<a href="#">
							<svg class="mto-text-white mto-inline-block mto-fill-current mto-w-4 mto-mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M16.604 11.048a5.67 5.67 0 00.751-3.44c-.179-1.784-1.175-3.361-2.803-4.44l-1.105 1.666c1.119.742 1.8 1.799 1.918 2.974a3.693 3.693 0 01-1.072 2.986l-1.192 1.192 1.618.475C18.951 13.701 19 17.957 19 18h2c0-1.789-.956-5.285-4.396-6.952z"/>
								<path d="M9.5 12c2.206 0 4-1.794 4-4s-1.794-4-4-4-4 1.794-4 4 1.794 4 4 4zm0-6c1.103 0 2 .897 2 2s-.897 2-2 2-2-.897-2-2 .897-2 2-2zm1.5 7H8c-3.309 0-6 2.691-6 6v1h2v-1c0-2.206 1.794-4 4-4h3c2.206 0 4 1.794 4 4v1h2v-1c0-3.309-2.691-6-6-6z"/>
							</svg>

							<span class="mto-inline-block mto-text-sm mto-text-white">32</span>
						</a>
					</div>

					<div class="course-share mto-w-full mto-flex mto-justify-center mto-py-2">
						<a href="#">
							<svg class="mto-text-white mto-inline-block mto-fill-current mto-w-4 mto-mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<circle fill="none" cx="17.5" cy="18.5" r="1.5"/>
								<circle fill="none" cx="5.5" cy="11.5" r="1.5"/>
								<circle fill="none" cx="17.5" cy="5.5" r="1.5"/>
								<path d="M5.5 15c.91 0 1.733-.358 2.357-.93l6.26 3.577A3.483 3.483 0 0014 18.5c0 1.93 1.57 3.5 3.5 3.5s3.5-1.57 3.5-3.5-1.57-3.5-3.5-3.5c-.91 0-1.733.358-2.357.93l-6.26-3.577c.063-.247.103-.502.108-.768l6.151-3.515c.625.572 1.448.93 2.358.93C19.43 9 21 7.43 21 5.5S19.43 2 17.5 2 14 3.57 14 5.5c0 .296.048.578.117.853L8.433 9.602A3.496 3.496 0 005.5 8C3.57 8 2 9.57 2 11.5S3.57 15 5.5 15zm12 2c.827 0 1.5.673 1.5 1.5s-.673 1.5-1.5 1.5-1.5-.673-1.5-1.5.673-1.5 1.5-1.5zm0-13c.827 0 1.5.673 1.5 1.5S18.327 7 17.5 7 16 6.327 16 5.5 16.673 4 17.5 4zm-12 6c.827 0 1.5.673 1.5 1.5S6.327 13 5.5 13 4 12.327 4 11.5 4.673 10 5.5 10z"/>
							</svg>

							<span class="mto-inline-block mto-text-sm mto-text-white">Share</span>
						</a>

					</div>
				</div>
			</div>

			<div class="course-detail mto-bg-white mto-px-4 mto-py-4 mto-w-full">
				<div class="mto-mb-3">
					<span class="mto-inline-block">
						<?php masteriyo_render_stars( $course->get_average_rating(), 'mto-text-gray-800 mto-w-4 mto-h-4' );?>
					</span>

					<?php
					if ( count( $course->get_category_ids() ) > 0 ) {
						$cat = masteriyo_get_course_cat( $course->get_category_ids()[0] );

						printf(
							'<a href="%s" class="mto-inline-block md:mto-block lg:mto-inline-block mto-bg-secondary mto-rounded-full mto-mb-3 lg:mto-mb-0 mto-px-4 mto-py-1 mto-text-xs mto-uppercase mto-font-medium mto-text-white mto-ml-2">%s</a>',
							$cat->get_permalink(),
							$cat->get_name()
						);
					}
					?>
				</div>
				<h2 class="mto-font-bold mto-text-base mto-capitalize mb-2"><?php echo $course->get_name(); ?></h2>
				<div class="course-time-share mto-mt-4">
					<div class="course-readtime">
						<svg class="mto-inline-block mto-fill-current mto-text-gray-800 mto-w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
							<path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"/>
							<path d="M13 7h-2v6h6v-2h-4z"/>
						</svg>

						<time class="group-hover:mto-text-white mto-inline-block mto-text-sm">
							<?php echo masteriyo_get_lecture_hours( $course, '%H%h %M%m' ) ?>
						</time>
					</div>
					<div class="btn">
						<a href="#" class="hover:mto-bg-primary-700 mto-transition mto-delay-150 mto-duration-300 mto-ease-in-out focus:mto-outline-none">Enroll Now</a>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
</section>

<?php

do_action( 'masteriyo_after_related_posts_content' );

