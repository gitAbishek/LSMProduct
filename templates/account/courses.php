<?php

/**
 * The template for displaying user's courses.
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'masteriyo_before_account_courses', $all_courses, $active_courses );
?>

<div class="masteriyo-mycourses">
	<h2 class="masteriyo-mycourses--title"><?php esc_html_e( 'Active Courses', 'masteriyo' ); ?></h2>
	<?php if ( count( $active_courses ) > 0 ) : ?>
		<div class="masteriyo-mycourses--list">
			<?php foreach ( $active_courses as $active_course ) : ?>
				<div class="masteriyo-mycourses--card">
					<a href="<?php echo esc_url( $active_course->get_permalink() ); ?>" title="<?php echo esc_attr( $active_course->get_name() ); ?>">
						<div class="masteriyo-mycourses--thumbnail">
							<?php echo wp_kses_post( $active_course->get_image( 'masteriyo_thumbnail' ) ); ?>
						</div>
					</a>

					<div class="masteriyo-mycourses--detail">
						<div class="masteriyo-mycourses--header">
							<div class="masteriyo-course--content__category">
								<?php foreach ( $active_course->get_categories() as $category ) : ?>
									<a href="<?php echo esc_url( $category->get_permalink() ); ?>" alt="<?php echo esc_attr( $category->get_name() ); ?>">
										<span class="masteriyo-badge masteriyo-mycourses--tag ">
											<?php echo esc_html( $category->get_name() ); ?>
										</span>
									</a>
								<?php endforeach; ?>
							</div>

							<h2 class="masteriyo-course--content__title">
								<a href="<?php echo esc_url( $active_course->get_permalink() ); ?>" title="<?php echo esc_attr( $active_course->get_name() ); ?>">
									<?php echo esc_html( $active_course->get_name() ); ?>
								</a>
							</h2>

							<span class="masteriyo-icon-svg masteriyo-rating">
								<?php masteriyo_render_stars( $active_course->get_average_rating() ); ?>
							</span>
						</div>

						<div class="masteriyo-mycourses--body">
							<div class="masteriyo-mycourses--body--duration masteriyo-flex masteriyo-flex--space-between">
								<div class="masteriyo-time-wrap">
									<span class="masteriyo-icon-svg">
										<?php masteriyo_get_svg( 'clock', true ); ?>
									</span>

									<time class="masteriyo-courses--body--time">
										<?php echo esc_html( masteriyo_minutes_to_time_length_string( $active_course->get_duration() ) ); ?>
									</time>
								</div>

								<div class="masteriyo-courses--body--status">
								<?php
									printf(
										/* translators: %s: course progress in percentage */
										esc_html__( '%s Completed', 'masteriyo' ),
										esc_html( $active_course->get_progress_status( true ) )
									);
								?>
								</div>
							</div>

							<div class="masteriyo-courses--body--pbar masteriyo-pbar">
								<div class="masteriyo-progressbar">
									<span class="masteriyo-bar" style="width:<?php echo esc_attr( $active_course->get_progress_status( true ) ); ?>;">
										<span class="masteriyo-progress">
										<?php echo esc_html( $active_course->get_progress_status( true ) ); ?>
										</span>
									</span>
								</div>
							</div>
						</div>

						<div class="masteriyo-mycourses--footer">
							<div class="masteriyo-time-btn">
								<time class="masteriyo-courses--body--time">
									<span>Started</span>
									<?php echo esc_html( masteriyo_format_datetime( $active_course->progress->get_started_at(), 'Y-m-d' ) ); ?>
								</time>

								<a href="<?php echo esc_url( $active_course->start_course_url() ); ?>" target="_blank" class="masteriyo-mycourses--btn masteriyo-btn masteriyo-btn-primary">
									<?php esc_html_e( 'Continue', 'masteriyo' ); ?>
								</a>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<div class="masteriyo-myachivement--notify-message masteriyo-alert masteriyo-info-msg">
			<span><?php esc_html_e( 'You haven\'t enrolled in any courses yet!', 'masteriyo' ); ?></span>
		</div>
	<?php endif; ?>

	<?php do_action( 'masteriyo_after_account_courses_enrolled_courses', $all_courses, $active_courses ); ?>

	<h2 class="masteriyo-mycourses--title"><?php esc_html_e( 'All Courses', 'masteriyo' ); ?></h2>
	<?php if ( count( $all_courses ) > 0 ) : ?>
		<div class="masteriyo-mycourses--list">
			<?php foreach ( $all_courses as $course ) : ?>
				<div class="masteriyo-mycourses--card">
					<a href="<?php echo esc_url( $course->get_permalink() ); ?>" title="<?php echo esc_attr( $course->get_name() ); ?>">
						<div class="masteriyo-mycourses--thumbnail">
							<?php echo wp_kses_post( $course->get_image( 'masteriyo_thumbnail' ) ); ?>
						</div>
						</a>
					<div class="masteriyo-mycourses--detail">
						<div class="masteriyo-mycourses--header">
							<div class="masteriyo-course--content__category">
								<?php foreach ( $course->get_categories() as $category ) : ?>
									<a href="<?php echo esc_url( $category->get_permalink() ); ?>" title="<?php echo esc_attr( $category->get_name() ); ?>">
									<span class="masteriyo-badge masteriyo-mycourses--tag ">
										<?php echo esc_html( $category->get_name() ); ?>
									</span>
								</a>
								<?php endforeach; ?>
							</div>

							<h3 class="masteriyo-mycourses--header--title">
								<a href="<?php echo esc_url( $course->get_permalink() ); ?>" title="<?php echo esc_attr( $course->get_name() ); ?>">
									<?php echo esc_html( $course->get_name() ); ?>
								</a>
							</h3>

							<div class="masteriyo-mycourses--rt">
								<span class="masteriyo-mycourses--rating masteriyo-icon-svg">
									<?php masteriyo_render_stars( $course->get_average_rating() ); ?>
								</span>
							</div>
						</div>

						<div class="masteriyo-mycourses--body">
							<div class="masteriyo-mycourses--body--duration masteriyo-flex masteriyo-flex--space-between ">
								<div class="masteriyo-time-wrap">
									<span class="masteriyo-icon-svg">
										<?php masteriyo_get_svg( 'clock', true ); ?>
									</span>

									<time class="masteriyo-courses--body--time">
										<?php echo esc_html( masteriyo_minutes_to_time_length_string( $course->get_duration() ) ); ?>
									</time>
								</div>
								<a href="<?php echo esc_url( $course->start_course_url() ); ?>" target="_blank" class="masteriyo-mycourses--btn masteriyo-btn masteriyo-btn-primary">
									<?php esc_html_e( 'Start Course', 'masteriyo' ); ?>
								</a>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<div class="masteriyo-myachivement--notify-message masteriyo-alert masteriyo-info-msg">
			<span><?php esc_html_e( 'No courses yet!', 'masteriyo' ); ?></span>
		</div>
	<?php endif; ?>
</div>

<?php
do_action( 'masteriyo_after_account_courses', $all_courses, $active_courses ); ?>
