<?php
/**
 * The Template for displaying related courses in single course page
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/single-course/content-related-posts.php.
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

defined( 'ABSPATH' ) || exit;

$related_courses = masteriyo_get_related_courses( $GLOBALS['course'] );

if ( empty( $related_courses ) ) {
	do_action( 'masteriyo_no_related_posts' );
	return;
}

do_action( 'masteriyo_before_related_posts_content' );

?>
<div class="mto-related-post">
	<h3 class="mto-related-post__title"><?php esc_html_e( 'Related Courses', 'masteriyo' ); ?></h3>

	<div class="mto-item--wrap masteriyo-w-100">
		<?php
		foreach ( $related_courses as $course ) {
			$author         = masteriyo_get_user( $course->get_author_id() );
			$comments_count = masteriyo_count_course_comments( $course );
			$difficulty     = $course->get_difficulty();
			?>
	  <div class="masteriyo-col-4">
			<div class="mto-course--card">
				<a href="<?php echo esc_url( $course->get_permalink() ); ?>" title="<?php esc_attr( $course->get_name() ); ?>">
					<div class="mto-course--img-wrap">
						<!-- Diffculty Badge -->
						<?php if ( $difficulty ) : ?>
							<div class="difficulty-badge">
								<span class="mto-badge <?php echo esc_attr( masteriyo_get_difficulty_badge_css_class( $difficulty['slug'] ) ); ?>"><?php echo esc_html( $difficulty['name'] ); ?></span>
							</div>
						<?php endif; ?>

						<!-- Featured Image -->
						<?php echo wp_kses_post( $course->get_image( 'masteriyo_thumbnail' ) ); ?>
					</div>
				</a>

				<div class="mto-course--content">
					<!-- Course category -->
					<div class="mto-course--content__category">
						<?php foreach ( $course->get_categories( 'name' ) as $category ) : ?>
							<span class="mto-course--content__category-items mto-tag">
								<?php echo esc_html( $category->get_name() ); ?>
							</span>
						<?php endforeach; ?>
					</div>
					<!-- Title of the course -->
					<h2 class="mto-course--content__title">
						<?php
						printf(
							'<a href="%s" title="%s">%s</a>',
							esc_url( $course->get_permalink() ),
							esc_html( $course->get_title() ),
							esc_html( $course->get_title() )
						);
						?>
					</h2>
					<!-- Course author and course rating -->
					<div class="mto-course--content__rt">
						<div class="mto-course-author">
							<?php if ( $author ) : ?>
								<img src="<?php echo esc_attr( $author->get_avatar_url() ); ?>" alt="" srcset="">
								<span class="mto-course-author--name"><?php echo esc_attr( $author->get_display_name() ); ?></span>
							<?php endif; ?>
						</div>
						<span class="mto-icon-svg mto-rating">
							<?php masteriyo_format_rating( $course->get_average_rating(), true ); ?> <?php echo esc_html( masteriyo_format_decimal( $course->get_average_rating(), 1, true ) ); ?> (<?php echo esc_html( $course->get_rating_count() ); ?>)
						</span>
					</div>
					<!-- Course description -->
					<div class="mto-course--content__description">
						<!-- <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Saepe dignissimos debitis facilis quisquam libero, explicabo molestias. Quibusdam illo iusto nulla dignissimos corrupti voluptatum officiis asperiores nobis. Obcaecati autem doloremque, libero, quod vel dolore delectus maxime magni eveniet iusto commodi? Adipisci?</p> -->
						<?php echo wp_kses_post( masteriyo_trim_course_highlights( $course->get_highlights() ) ); ?>
					</div>
					<!-- Four Column (Course duration, comments, student enrolled and curriculum) -->
					<div class="mto-course--content__stats">
						<div class="mto-course-stats-duration">
							<?php masteriyo_get_svg( 'time', true ); ?> <span><?php echo esc_html( masteriyo_minutes_to_time_length_string( $course->get_duration() ) ); ?></span>
						</div>
						<div class="mto-course-stats-comments">
							<?php masteriyo_get_svg( 'comment', true ); ?><span><?php echo esc_html( $comments_count ); ?></span>
						</div>
						<div class="mto-course-stats-students">
							<?php masteriyo_get_svg( 'group', true ); ?> <span><?php echo esc_html( masteriyo_count_enrolled_users( $course->get_id() ) ); ?></span>
						</div>
						<div class="mto-course-stats-curriculum">
							<?php masteriyo_get_svg( 'book', true ); ?> <span><?php echo esc_html( masteriyo_get_lessons_count( $course ) ); ?></span>
						</div>
					</div>
					<hr class="masteriyo-border">
					<!-- Price and Enroll Now Button -->
					<div class="mto-time-btn">
						<div class="mto-course-price">
							<span class="current-amount"><?php echo wp_kses_post( masteriyo_price( $course->get_price() ) ); ?></span>
						</div>
						<?php do_action( 'masteriyo_template_enroll_button', $course ); ?>
					</div>
				</div>
			</div>
	  </div>
		<?php } ?>
</div>
</div>

<?php

do_action( 'masteriyo_after_related_posts_content' );

