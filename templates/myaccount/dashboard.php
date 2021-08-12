<?php
/**
 * The template for displaying user dashboard.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="mto-welcome-notify">
	<a class="mto-close" href="#">
		<span class="mto-icon-svg">
			<?php masteriyo_get_svg( 'cross', true ); ?>
		</span>
	</a>

	<h3 class="mto-title">
		<?php esc_html_e( 'Hello', 'masteriyo' ); ?>, <span class="mto-profile-name"><?php echo esc_html( $user->get_display_name() ); ?></span>
	</h3>

	<p class="mto-welcome-msg"><?php esc_html_e( 'Welcome to your dashboard here you can view your overview and your stats', 'masteriyo' ); ?></p>

	<a
		class="mto-view-myaccount mto-btn mto-btn-default"
		href="<?php echo esc_url( masteriyo_get_account_endpoint_url( 'view-myaccount' ) ); ?>"
	>
		<span class="mto-text-pColor"><?php esc_html_e( 'View Profile', 'masteriyo' ); ?></span>
		<span class="mto-icon-svg">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
				<path d="M10.707 17.707L16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"/>
			</svg>
		</span>
	</a>
</div>

<div class="mto-counter">
	<div class="mto-counter--inprogress mto-db-card">
		<div class="mto-icon-title">
			<span class="mto-icon mto-icon-svg">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M22.8 7.6l-9.7-3.2c-.7-.2-1.4-.2-2.2 0L1.2 7.6C.5 7.9 0 8.6 0 9.4s.5 1.5 1.2 1.8l.8.3c-.1.2-.2.4-.2.7-.4.2-.7.6-.7 1.2 0 .4.2.8.5 1L.6 19c-.1.4.2.8.6.8h2.1c.4 0 .7-.4.6-.8l-1-4.6c.3-.2.5-.6.5-1s-.2-.8-.5-1c0-.1.1-.3.2-.4l2.1.7-.5 4.6c0 1.4 3.2 2.6 7.2 2.6s7.2-1.2 7.2-2.6l-.5-4.6 4.1-1.4c.7-.2 1.2-1 1.2-1.8.1-.9-.4-1.6-1.1-1.9zm-5.5 9.2c-2.2 1.4-8.5 1.4-10.7 0l.4-3.6 3.8 1.3c.4.1 1.2.3 2.2 0l3.8-1.3.5 3.6zm-4.8-4.2c-.4.1-.7.1-1.1 0l-5.7-1.9L12 9.4c.3-.1.5-.4.5-.8-.1-.4-.4-.6-.7-.5L4.2 9.7c-.2 0-.4.1-.7.2L2 9.4l9.5-3.1c.4-.1.7-.1 1.1 0L22 9.4l-9.5 3.2z"/>
				</svg>
			</span>
			<h3 class="mto-title"><?php esc_html_e( 'In Progress', 'masteriyo' ); ?></h3>
		</div>
		<span class="mto-number">
			<?php echo absint( masteriyo_get_active_courses_count( $user ) ); ?>
		</span>
		<div class="mto-subtitle"><?php esc_html_e( 'Courses', 'masteriyo' ); ?></div>
	</div>
</div>

<div class="mto-cstudy">
	<div class="mto-cstudy--header">
		<h2 class="mto-cstudy--title"><?php echo esc_html__( 'Continue Studying', 'masteriyo' ); ?></h2>
		<a class="mto-cstudy--btn mto-btn mto-btn-default" href="<?php echo esc_url( masteriyo_get_account_endpoint_url( 'courses' ) ); ?>">
			<span><?php esc_html_e( 'Show All', 'masteriyo' ); ?></span>

			<span class="mto-icon-svg">
				<svg  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M10.707 17.707L16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"/>
				</svg>
			</span>

		</a>
	</div>

	<div class="mto-cstudy--body">
	<?php if ( ! empty( $active_courses ) ) : ?>
		<ul>
		<?php foreach ( $active_courses as $active_course ) : ?>
			<li>
				<div class="mto-cstudy--body--wrap mto-flex mto-flex-ycenter mto-db-card">
					<div class="mto-cstudy--body--img-title">
						<a href="<?php echo esc_url( $active_course->get_permalink() ); ?>" title="<?php echo esc_attr( $active_course->get_name() ); ?>">
							<?php echo $active_course->get_image( 'thumbnail' ); ?>
						</a>
						<div class="mto-cstudy--body--header">
							<span class="mto-cstudy--body--rating mto-icon-svg">
								<?php masteriyo_render_stars( $active_course->get_average_rating() ); ?>
							</span>

							<a href="<?php echo esc_url( $active_course->get_permalink() ); ?>" title="<?php echo esc_attr( $active_course->get_name() ); ?>">
								<h3 class="mto-cstudy--body--title">
									<?php echo esc_html( $active_course->get_name() ); ?>
								</h3>
							</a>

							<div class="mto-cstudy--body--duration">
								<span class="mto-icon-svg">
									<?php masteriyo_get_svg( 'clock', true ); ?>
								</span>
								<time class="mto-cstudy--body--time">
									<?php echo esc_html( masteriyo_minutes_to_time_length_string( $active_course->get_duration() ) ); ?>
								</time>
							</div>
						</div>
					</div>

					<div class="mto-cstudy--body--tag">
					<?php foreach ( $active_course->get_categories() as $category ) : ?>
						<a href="<?php echo esc_url( $category->get_permalink() ); ?>" title="<?php echo esc_attr( $category->get_name() ); ?>">
							<span class="mto-badge mto-badge-pink mto-mycourses--tag">
								<?php echo esc_html( $category->get_name() ); ?>
							</span>
						</a>
					<?php endforeach; ?>
					</div>

					<div class="mto-cstudy--body--pbar mto-pbar">
						<div class="mto-progressbar">
							<span class="mto-bar" style="width:<?php echo esc_attr( $active_course->get_progress_status( true ) ); ?>;">
								<span class="mto-progress">
									<?php echo esc_html( $active_course->get_progress_status( true ) ); ?>
								</span>
							</span>
						</div>
						<div class="mto-cstudy--body--caption">
							<?php esc_html_e( 'Started on', 'masteriyo' ); ?> <?php echo esc_html( masteriyo_format_datetime( $active_course->progress->get_started_at(), 'Y-m-d' ) ); ?>
						</div>
					</div>
					<div class="mto-cstudy--body--pstatus">
					<?php
						printf(
							/* translators: %s: course progress in percentage */
							esc_html__( '%s Completed', 'masteriyo' ),
							esc_html( $active_course->get_progress_status( true ) )
						);
					?>
					</div>
					<div>
						<a href="<?php echo esc_url( $active_course->start_course_url() ); ?>" target="_blank" class="mto-cstudy--body--btn mto-btn mto-btn-primary ">
							<span><?php echo esc_html__( 'Continue', 'masteriyo' ); ?></span>
						</a>
					</div>
				</div>
			</li>
		<?php endforeach; ?>
		</ul>
	<?php else : ?>
		<?php esc_html_e( 'No active courses.', 'masteriyo' ); ?>
	<?php endif; ?>
	</div>
</div>

<?php
