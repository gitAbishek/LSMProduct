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
<div class="course-card md:flex-grow mto-min-h-429">
	<div class="mto-group course-image mto-relative mto-overflow-hidden">
		<span class="price-tag">
			<?php echo masteriyo_price( $course->get_price() ); ?>
		</span>
		<img class="mto-absoulte mto-w-full mto-h-full mto-object-cover" src="https://via.placeholder.com/150" alt="You are your only limit">
		<!-- Hidden Card -->
		<div class="mto-hidden group-hover:mto-block course-overlay">
			<div class="mto-p-6">
				<h2 class="mto-font-bold mto-text-white mto-text-base mto-capitalize mb-2">
					<?php echo esc_html( $course->get_name() ); ?>
				</h2>
				<div class="course-author-detail">
					<div class="mto-flex mto-flex-row mto-items-center mto-mt-4">
						<img src="./img/author-pic.jpg" class="mto-rounded-full mto-border-2 mto-border-white mto-w-7 mto-h-7" alt="">
						<span class="mto-ml-1.5 mto-font-medium mto-text-sm mto-text-white">Morris Perera</span>
					</div>

					<div class="mto-font-sm mto-text-white mto-my-4 mto-line-clamp-3">
						<?php echo $course->get_description(); ?>
					</div>

					<a href="#" class="btn course-author-readmore hover:mto-bg-white">Read More</a>
				</div>
			</div>
			<div class="course-time-share mto-bg-primary mto-absolute mto-bottom-0 mto-right-0 mto-left-0 mto-border mto-border-opacity-10">
				<div class="course-readtime mto-w-full mto-flex mto-justify-center mto-py-2">
					<svg class="mto-inline-block mto-fill-current mto-text-white mto-w-4 mto-mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
						<path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"></path>
						<path d="M13 7h-2v6h6v-2h-4z"></path>
					</svg>

					<time class="mto-inline-block mto-text-sm mto-text-white">10 hrs</time>
				</div>


				<div class="course-member mto-w-full mto-flex mto-border-r mto-border-l  mto-border-opacity-10 mto-justify-center mto-py-2">
					<svg class="mto-text-white mto-inline-block mto-fill-current mto-w-4 mto-mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
						<path d="M16.604 11.048a5.67 5.67 0 00.751-3.44c-.179-1.784-1.175-3.361-2.803-4.44l-1.105 1.666c1.119.742 1.8 1.799 1.918 2.974a3.693 3.693 0 01-1.072 2.986l-1.192 1.192 1.618.475C18.951 13.701 19 17.957 19 18h2c0-1.789-.956-5.285-4.396-6.952z"></path>
						<path d="M9.5 12c2.206 0 4-1.794 4-4s-1.794-4-4-4-4 1.794-4 4 1.794 4 4 4zm0-6c1.103 0 2 .897 2 2s-.897 2-2 2-2-.897-2-2 .897-2 2-2zm1.5 7H8c-3.309 0-6 2.691-6 6v1h2v-1c0-2.206 1.794-4 4-4h3c2.206 0 4 1.794 4 4v1h2v-1c0-3.309-2.691-6-6-6z"></path>
					</svg>

					<span class="mto-inline-block mto-text-sm mto-text-white">32</span>
				</div>

				<div class="course-share mto-w-full mto-flex mto-justify-center mto-py-2">
					<a href="#" class="hover-effect">
						<svg class="mto-text-white mto-inline-block mto-fill-current mto-w-4 mto-h-4 mto-mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
							<circle fill="none" cx="17.5" cy="18.5" r="1.5"></circle>
							<circle fill="none" cx="5.5" cy="11.5" r="1.5"></circle>
							<circle fill="none" cx="17.5" cy="5.5" r="1.5"></circle>
							<path d="M5.5 15c.91 0 1.733-.358 2.357-.93l6.26 3.577A3.483 3.483 0 0014 18.5c0 1.93 1.57 3.5 3.5 3.5s3.5-1.57 3.5-3.5-1.57-3.5-3.5-3.5c-.91 0-1.733.358-2.357.93l-6.26-3.577c.063-.247.103-.502.108-.768l6.151-3.515c.625.572 1.448.93 2.358.93C19.43 9 21 7.43 21 5.5S19.43 2 17.5 2 14 3.57 14 5.5c0 .296.048.578.117.853L8.433 9.602A3.496 3.496 0 005.5 8C3.57 8 2 9.57 2 11.5S3.57 15 5.5 15zm12 2c.827 0 1.5.673 1.5 1.5s-.673 1.5-1.5 1.5-1.5-.673-1.5-1.5.673-1.5 1.5-1.5zm0-13c.827 0 1.5.673 1.5 1.5S18.327 7 17.5 7 16 6.327 16 5.5 16.673 4 17.5 4zm-12 6c.827 0 1.5.673 1.5 1.5S6.327 13 5.5 13 4 12.327 4 11.5 4.673 10 5.5 10z"></path>
						</svg>

						<span class="mto-inline-block mto-text-sm mto-text-white">Share</span>
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="course-detail mto-bg-white mto-px-4 mto-py-4 mto-w-full">
		<div class="mto-mb-3">
			<span class="mto-inline-block">
				<svg class="mto-inline-block mto-fill-current mto-text-gray-800 mto-w-4 mto-h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path>
				</svg>
				<svg class="mto-inline-block mto-fill-current mto-text-gray-800 mto-w-4 mto-h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path>
				</svg>
				<svg class="mto-inline-block mto-fill-current mto-text-gray-800 mto-w-4 mto-h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path>
				</svg>
				<svg class="mto-inline-block mto-fill-current mto-text-gray-800 mto-w-4 mto-h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M5.025 20.775A.998.998 0 006 22a1 1 0 00.555-.168L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082a1 1 0 00-.59-1.74l-5.701-.454-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.214 4.107-1.491 6.452zM12 5.429l2.042 4.521.588.047h.001l3.972.315-3.271 2.944-.001.002-.463.416.171.597v.003l1.253 4.385L12 15.798V5.429z"></path>
				</svg>
				<svg class="mto-inline-block mto-fill-current mto-text-gray-800 mto-w-4 mto-h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M6.516 14.323l-1.49 6.452a.998.998 0 001.529 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082a1 1 0 00-.59-1.74l-5.701-.454-2.467-5.461a.998.998 0 00-1.822 0L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.214 4.107zm2.853-4.326a.998.998 0 00.832-.586L12 5.43l1.799 3.981a.998.998 0 00.832.586l3.972.315-3.271 2.944c-.284.256-.397.65-.293 1.018l1.253 4.385-3.736-2.491a.995.995 0 00-1.109 0l-3.904 2.603 1.05-4.546a1 1 0 00-.276-.94l-3.038-2.962 4.09-.326z"/>
				</svg>
			</span>

			<span class="mto-inline-block mto-bg-secondary mto-rounded-full mto-px-4 mto-py-1 mto-text-xs mto-uppercase mto-font-medium mto-text-white mto-ml-2">Book</span>
		</div>
		<h2 class="mto-font-bold mto-text-base mto-capitalize mb-2 mto-h-12 mto-line-clamp-2">
			<?php echo esc_html( $course->get_name() ); ?>
		</h2>
		<div class="course-time-share mto-mt-4">
			<div class="course-readtime">
				<svg class="mto-inline-block mto-fill-current mto-text-gray-800 mto-w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"></path>
					<path d="M13 7h-2v6h6v-2h-4z"></path>
				</svg>

				<time class="mto-inline-block mto-text-sm">10:00 min</time>
			</div>
			<div class="btn">
				<a href="#" class="mto-text-white">
					<?php esc_html_e( 'Enroll Now', 'masteriyo' ); ?>
				</a>
			</div>
		</div>
	</div>
</div>
