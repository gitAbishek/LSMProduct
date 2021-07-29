<?php
/**
 * Admin cancelled order email
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/emails/course-enrolled.php.
 *
 * HOWEVER, on occasion masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package masteriyo\Templates\Emails
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'masteriyo_email_header', $email_heading, $email ); ?>

<p class="email-template--info">
	<?php esc_html_e( 'Hello', 'masteriyo' ); ?> <strong><?php echo esc_html( $user->get_display_name() ); ?></strong>,
</p>
<p class="email-template--info">
	<?php esc_html_e( 'You have enrolled in the following course:', 'masteriyo' ); ?>
</p>
<div>
	<div>
		<?php if ( empty( $course->get_featured_image_url() ) ) : ?>
			<img width="20px" src="https://via.placeholder.com/150" alt="<?php _e( 'Course Featured Image', 'masteriyo' ); ?>" />
		<?php else : ?>
			<img width="20px" src="<?php echo esc_attr( $course->get_featured_image_url() ); ?>" alt="<?php _e( 'Course Featured Image', 'masteriyo' ); ?>" />
		<?php endif; ?>
	</div>
	<div>
		<div>
			<div>
				<span>
					<?php masteriyo_render_stars( $course->get_average_rating() ); ?>
				</span>

				<?php foreach ( $course->get_categories() as $category ) : ?>
					<span><?php echo esc_html( $category->get_name() ); ?></span>
				<?php endforeach; ?>
			</div>
			<h4><?php echo esc_html( $course->get_name() ); ?></h4>
		</div>
		<div>
			<span>
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z" />
					<path d="M13 7h-2v6h6v-2h-4z" />
				</svg>
			</span>

			<time><?php echo esc_html( $course->get_human_readable_lecture_hours() ); ?></time>
		</div>
	</div>
</div>

<br>
<br>

<!-- Enrolled Courses -->
<h3 class="email-template--info">
	Your active courses:
</h3>
<?php foreach ( $enrolled_courses as $course ) : ?>
	<div>
		<div>
			<?php if ( empty( $course->get_featured_image_url() ) ) : ?>
				<img width="20px" src="https://via.placeholder.com/150" alt="<?php _e( 'Course Featured Image', 'masteriyo' ); ?>" />
			<?php else : ?>
				<img width="20px" src="<?php echo esc_attr( $course->get_featured_image_url() ); ?>" alt="<?php _e( 'Course Featured Image', 'masteriyo' ); ?>" />
			<?php endif; ?>
		</div>
		<div>
			<div>
				<div>
					<span>
						<?php masteriyo_render_stars( $course->get_average_rating() ); ?>
					</span>

					<?php foreach ( $course->get_categories() as $category ) : ?>
						<span><?php echo esc_html( $category->get_name() ); ?></span>
					<?php endforeach; ?>
				</div>
				<h4><?php echo esc_html( $course->get_name() ); ?></h4>
			</div>
			<div>
				<span>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
						<path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z" />
						<path d="M13 7h-2v6h6v-2h-4z" />
					</svg>
				</span>

				<time><?php echo esc_html( $course->get_human_readable_lecture_hours() ); ?></time>
			</div>
		</div>
	</div>
<?php endforeach; ?>

<!-- All Courses -->
<h3 class="email-template--info">
	Your all courses:
</h3>
<?php foreach ( $all_courses as $course ) : ?>
	<div>
		<div>
			<!-- Featured Image -->
			<?php if ( empty( $course->get_featured_image_url() ) ) : ?>
				<img width="20px" src="https://via.placeholder.com/150" alt="<?php _e( 'Course Featured Image', 'masteriyo' ); ?>" />
			<?php else : ?>
				<img width="20px" src="<?php echo esc_attr( $course->get_featured_image_url() ); ?>" alt="<?php _e( 'Course Featured Image', 'masteriyo' ); ?>" />
			<?php endif; ?>
		</div>
		<div>
			<div>
				<div>
					<span>
						<?php masteriyo_render_stars( $course->get_average_rating() ); ?>
					</span>

					<?php foreach ( $course->get_categories() as $category ) : ?>
						<span><?php echo esc_html( $category->get_name() ); ?></span>
					<?php endforeach; ?>
				</div>
				<h4><?php echo esc_html( $course->get_name() ); ?></h4>
			</div>
			<div>
				<span>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
						<path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z" />
						<path d="M13 7h-2v6h6v-2h-4z" />
					</svg>
				</span>

				<time><?php echo esc_html( $course->get_human_readable_lecture_hours() ); ?></time>
			</div>
		</div>
	</div>
	<?php
endforeach;

do_action( 'masteriyo_email_footer', $email );
