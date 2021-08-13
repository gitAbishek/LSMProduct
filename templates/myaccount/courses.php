<?php

/**
 * The template for displaying user's courses.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'masteriyo_before_account_courses', $all_courses, $active_courses );
?>

<div class="mto-mycourses">
	<h2 class="mto-mycourses--title"><?php esc_html_e( 'Active Courses', 'masteriyo' ); ?></h2>
	<?php if ( count( $active_courses ) > 0 ) : ?>
		<div class="mto-mycourses--list">
			<?php foreach ( $active_courses as $active_course ) : ?>
				<div class="mto-mycourses--card">
					<a href="<?php echo esc_url( $active_course->get_permalink() ); ?>" title="<?php echo esc_attr( $active_course->get_name() ); ?>">
						<div class="mto-mycourses--thumbnail">
							<?php echo $active_course->get_image( 'masteriyo_thumbnail' ); ?>
						</div>
					</a>
					<div class="mto-mycourses--detail">
						<div class="mto-mycourses--header">
							<div class="mto-mycourses--rt">
								<span class="mto-mycourses--rating mto-icon-svg">
									<?php masteriyo_render_stars( $active_course->get_average_rating() ); ?>
								</span>

								<?php foreach ( $active_course->get_categories() as $category ) : ?>
									<a href="<?php echo esc_url( $category->get_permalink() ); ?>" alt="<?php echo esc_attr( $category->get_name() ); ?>">
										<span class="mto-badge mto-badge-pink mto-mycourses--tag ">
											<?php echo esc_html( $category->get_name() ); ?>
										</span>
									</a>
								<?php endforeach; ?>
							</div>
							<a href="<?php echo esc_url( $active_course->get_permalink() ); ?>" title="<?php echo esc_attr( $active_course->get_name() ); ?>">
								<h3 class="mto-mycourses--header--title">
									<?php echo esc_html( $active_course->get_name() ); ?>
								</h3>
							</a>
						</div>
						<div class="mto-mycourses--body">
							<div class="mto-mycourses--body--duration mto-flex mto-flex--space-between">
								<div class="mto-time-wrap">
									<span class="mto-icon-svg">
										<?php masteriyo_get_svg( 'clock', true ); ?>
									</span>

									<time class="mto-courses--body--time">
										<?php echo esc_html( masteriyo_minutes_to_time_length_string( $active_course->get_duration() ) ); ?>
									</time>
								</div>

								<div class="mto-courses--body--status">
								<?php
									printf(
										/* translators: %s: course progress in percentage */
										esc_html__( '%s Completed', 'masteriyo' ),
										esc_html( $active_course->get_progress_status( true ) )
									);
								?>
								</div>
							</div>

							<div class="mto-courses--body--pbar mto-pbar">
								<div class="mto-progressbar">
									<span class="mto-bar" style="width:<?php echo esc_attr( $active_course->get_progress_status( true ) ); ?>;">
										<span class="mto-progress">
										<?php echo esc_html( $active_course->get_progress_status( true ) ); ?>
										</span>
									</span>
								</div>
							</div>
						</div>

						<div class="mto-mycourses--footer mto-flex mto-flex--space-between mto-no-flex-wrap">
							<div class="mto-time-wrap">
								<span class="mto-icon-svg"><?php masteriyo_get_svg( 'start-clock', true ); ?></span>
								<time class="mto-courses--body--time">
									<?php echo esc_html( masteriyo_format_datetime( $active_course->progress->get_started_at(), 'Y-m-d' ) ); ?>
								</time>
							</div>
							<a href="<?php echo esc_url( $active_course->start_course_url() ); ?>" target="_blank" class="mto-mycourses--btn mto-btn mto-btn-primary">
								<?php esc_html_e( 'Continue', 'masteriyo' ); ?>
							</a>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<div class="mto-myachivement--notify-message mto-alert mto-info-msg">
			<span><?php esc_html_e( 'You haven\'t enrolled in any courses yet!', 'masteriyo' ); ?></span>
		</div>
	<?php endif; ?>

	<?php do_action( 'masteriyo_after_account_courses_enrolled_courses', $all_courses, $active_courses ); ?>

	<h2 class="mto-mycourses--title"><?php esc_html_e( 'All Courses', 'masteriyo' ); ?></h2>
	<?php if ( count( $all_courses ) > 0 ) : ?>
		<div class="mto-mycourses--list">
			<?php foreach ( $all_courses as $course ) : ?>
				<div class="mto-mycourses--card">
					<a href="<?php echo esc_url( $course->get_permalink() ); ?>" title="<?php echo esc_attr( $course->get_name() ); ?>">
						<div class="mto-mycourses--thumbnail">
							<?php echo $course->get_image( 'masteriyo_thumbnail' ); ?>
						</div>
						</a>
					<div class="mto-mycourses--detail">
						<div class="mto-mycourses--header">
							<div class="mto-mycourses--rt">
								<span class="mto-mycourses--rating mto-icon-svg">
									<?php masteriyo_render_stars( $course->get_average_rating() ); ?>
								</span>

								<?php foreach ( $course->get_categories() as $category ) : ?>
								<a href="<?php echo esc_url( $category->get_permalink() ); ?>" title="<?php echo esc_attr( $category->get_name() ); ?>">
									<span class="mto-badge mto-badge-pink mto-mycourses--tag ">
										<?php echo esc_html( $category->get_name() ); ?>
									</span>
								</a>
								<?php endforeach; ?>
							</div>
							<a href="<?php echo esc_url( $course->get_permalink() ); ?>" title="<?php echo esc_attr( $course->get_name() ); ?>">
								<h3 class="mto-mycourses--header--title">
									<?php echo esc_html( $course->get_name() ); ?>
								</h3>
							</a>
						</div>
						<div class="mto-mycourses--body">
							<div class="mto-mycourses--body--duration mto-flex mto-flex--space-between ">
								<div class="mto-time-wrap">
									<span class="mto-icon-svg">
										<?php masteriyo_get_svg( 'clock', true ); ?>
									</span>

									<time class="mto-courses--body--time">
										<?php echo esc_html( masteriyo_minutes_to_time_length_string( $course->get_duration() ) ); ?>
									</time>
								</div>
								<a href="<?php echo esc_url( $course->start_course_url() ); ?>" target="_blank" class="mto-mycourses--btn mto-btn mto-btn-primary">
									<?php esc_html_e( 'Start Course', 'masteriyo' ); ?>
								</a>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<div class="mto-myachivement--notify-message mto-alert mto-info-msg">
			<span><?php esc_html_e( 'No courses yet!', 'masteriyo' ); ?></span>
		</div>
	<?php endif; ?>
</div>

<?php
do_action( 'masteriyo_after_account_courses', $all_courses, $active_courses ); ?>
