<?php
/**
 * The template for displaying course content within loops
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/content-course.php.
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

global $course;

// Ensure visibility.
if ( empty( $course ) || ! $course->is_visible() ) {
	return;
}
?>
<div class="mto-course-item">
	<div class="mto-course--card">
		<div class="mto-course--img-wrap">
			<span class="mto-course--price-tag">
				<?php echo masteriyo_price( $course->get_price() ); ?>
			</span>

			<!-- Featured Image -->
			<?php //echo wp_get_attachment_image( $course->get_featured_image() ); ?>
			<img class="mto-course--img" src="https://via.placeholder.com/150" alt="You are your only limit">
		</div>

		<div class="mto-course--header">
			<div class="mto-rt">
				<span class="mto-icon-svg mto-flex mto-rating">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
						<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path>
					</svg>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
						<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path>
					</svg>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
						<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path>
					</svg>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
						<path d="M5.025 20.775A.998.998 0 006 22a1 1 0 00.555-.168L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082a1 1 0 00-.59-1.74l-5.701-.454-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.214 4.107-1.491 6.452zM12 5.429l2.042 4.521.588.047h.001l3.972.315-3.271 2.944-.001.002-.463.416.171.597v.003l1.253 4.385L12 15.798V5.429z"></path>
					</svg>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
						<path d="M6.516 14.323l-1.49 6.452a.998.998 0 001.529 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082a1 1 0 00-.59-1.74l-5.701-.454-2.467-5.461a.998.998 0 00-1.822 0L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.214 4.107zm2.853-4.326a.998.998 0 00.832-.586L12 5.43l1.799 3.981a.998.998 0 00.832.586l3.972.315-3.271 2.944c-.284.256-.397.65-.293 1.018l1.253 4.385-3.736-2.491a.995.995 0 00-1.109 0l-3.904 2.603 1.05-4.546a1 1 0 00-.276-.94l-3.038-2.962 4.09-.326z"/>
					</svg>
				</span>
				<?php foreach( $course->get_categories( 'name' ) as $category ): ?>
					<span class="mto-badge mto-badge-pink mto-tag">
						<?php echo esc_html( $category ); ?>
					</span>
				<?php endforeach; ?>
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

					<time class="mto-inline-block mto-text-sm">10:00 min</time>
				</span>
				<a href="#" class="mto-course--btn mto-btn mto-btn-primary">
					<?php esc_html_e( apply_filters( 'masteriyo_enroll_now', 'Enroll Now' ), 'masteriyo' ); ?>
				</a>
			</div>
		</div>
	</div>
</div>
