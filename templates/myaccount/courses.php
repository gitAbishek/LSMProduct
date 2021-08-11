<?php

/**
 * The template for displaying user's courses.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'masteriyo_before_account_courses', $all_courses, $enrolled_courses );

?>

<div class="mto-mycourses">
	<h2 class="mto-mycourses--title"><?php _e( 'Active Courses', 'masteriyo' ); ?></h2>
	<?php if ( count( $enrolled_courses ) > 0 ) : ?>
		<div class="mto-mycourses--list">
			<?php foreach ( $enrolled_courses as $course ) : ?>
				<div class="mto-mycourses--card">
					<div class="mto-mycourses--thumbnail">
						<img class="mto-mycourses--img" src="<?php echo esc_attr( $course->get_featured_image_url() ); ?>" alt="<?php _e( 'Course Featured Image', 'masteriyo' ); ?>" />
					</div>
					<div class="mto-mycourses--detail">
						<div class="mto-mycourses--header">
							<div class="mto-mycourses--rt">
								<span class="mto-mycourses--rating mto-icon-svg">
									<?php masteriyo_render_stars( $course->get_average_rating() ); ?>
								</span>

								<?php foreach ( $course->get_categories() as $category ) : ?>
									<span class="mto-badge mto-badge-pink mto-mycourses--tag "><?php echo esc_html( $category->get_name() ); ?></span>
								<?php endforeach; ?>
							</div>
							<h3 class="mto-mycourses--header--title"><?php echo esc_html( $course->get_name() ); ?></h3>
						</div>
						<div class="mto-mycourses--body">
							<div class="mto-mycourses--body--duration mto-flex mto-flex--space-between">
								<div class="mto-time-wrap">
									<span class="mto-icon-svg">
										<svg class="mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
											<path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z" />
											<path d="M13 7h-2v6h6v-2h-4z" />
										</svg>
									</span>

									<time class="mto-courses--body--time"><?php echo esc_html( $course->get_human_readable_lecture_hours() ); ?></time>
								</div>

								<div class="mto-courses--body--status">
									50% Completed
								</div>
							</div>

							<div class="mto-courses--body--pbar mto-pbar">
								<div class="mto-progressbar">
									<span class="mto-bar" style="width:50%;">
										<span class="mto-progress">50%</span>
									</span>
								</div>
							</div>
						</div>

						<div class="mto-mycourses--footer mto-flex mto-flex--space-between mto-no-flex-wrap">
							<div class="mto-mycourses--date"><?php _e( 'Started', 'masteriyo' ); ?> <?php echo esc_html( masteriyo_format_datetime( $course->user_course->get_date_start() ) ); ?></div>
							<a href="<?php echo esc_attr( $course->start_course_url() ); ?>" class="mto-mycourses--btn mto-btn mto-btn-primary"><?php _e( 'Continue', 'masteriyo' ); ?></a>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<div class="mto-myachivement--notify-message mto-alert mto-info-msg">
			<span><?php _e( 'You haven\'t enrolled in any courses yet!', 'masteriyo' ); ?></span>
		</div>
	<?php endif; ?>

	<?php do_action( 'masteriyo_after_account_courses_enrolled_courses', $all_courses, $enrolled_courses ); ?>

	<h2 class="mto-mycourses--title"><?php _e( 'All Courses', 'masteriyo' ); ?></h2>
	<?php if ( count( $all_courses ) > 0 ) : ?>
		<div class="mto-mycourses--list">
			<?php foreach ( $all_courses as $course ) : ?>
				<div class="mto-mycourses--card">
					<div class="mto-mycourses--thumbnail">
						<img class="mto-mycourses--img" src="<?php echo esc_attr( $course->get_featured_image_url() ); ?>" alt="<?php _e( 'Course Featured Image', 'masteriyo' ); ?>" />
					</div>
					<div class="mto-mycourses--detail">
						<div class="mto-mycourses--header">
							<div class="mto-mycourses--rt">
								<span class="mto-mycourses--rating mto-icon-svg">
									<?php masteriyo_render_stars( $course->get_average_rating() ); ?>
								</span>

								<?php foreach ( $course->get_categories() as $category ) : ?>
									<span class="mto-badge mto-badge-pink mto-mycourses--tag "><?php echo esc_html( $category->get_name() ); ?></span>
								<?php endforeach; ?>
							</div>
							<h3 class="mto-mycourses--header--title"><?php echo esc_html( $course->get_name() ); ?></h3>
						</div>
						<div class="mto-mycourses--body">
							<div class="mto-mycourses--body--duration mto-flex mto-flex--space-between ">
								<div class="mto-time-wrap">
									<span class="mto-icon-svg">
										<svg class="mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
											<path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z" />
											<path d="M13 7h-2v6h6v-2h-4z" />
										</svg>
									</span>

									<time class="mto-courses--body--time"><?php echo esc_html( $course->get_human_readable_lecture_hours() ); ?></time>
								</div>
								<a href="<?php echo esc_attr( $course->get_permalink() ); ?>" class="mto-mycourses--btn mto-btn mto-btn-primary"><?php _e( 'View', 'masteriyo' ); ?></a>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<div class="mto-myachivement--notify-message mto-alert mto-info-msg">
			<span><?php _e( 'No courses yet!', 'masteriyo' ); ?></span>
		</div>
	<?php endif; ?>
</div>

<?php

do_action( 'masteriyo_after_account_courses', $all_courses, $enrolled_courses ); ?>
