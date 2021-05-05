<?php
/**
 * The template for displaying user's courses.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="mto-mycourses">
	<h2 class="mto-mycourses--title">My Courses</h2>
	<div class="mto-mycourses--list">

		<div class="mto-mycourses--card">
			<div class="mto-mycourses--thumbnail">
				<img class="mto-mycourses--img" src="<?php echo esc_html__( masteriyo_img_url('db-course-thumbnail.jpg'));?>" alt="You are your only limit" />
			</div>
			<div class="mto-mycourses--detail">
				<div class="mto-mycourses--header">
					<div class="mto-mycourses--rt">
						<span class="mto-mycourses--rating">
							<svg class="mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"/>
							</svg>
							<svg class=" mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"/>
							</svg>
							<svg class=" mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"/>
							</svg>
							<svg class=" mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"/>
							</svg>
							<svg class=" mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M5.025 20.775A.998.998 0 006 22a1 1 0 00.555-.168L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082a1 1 0 00-.59-1.74l-5.701-.454-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.214 4.107-1.491 6.452zM12 5.429l2.042 4.521.588.047h.001l3.972.315-3.271 2.944-.001.002-.463.416.171.597v.003l1.253 4.385L12 15.798V5.429z"/>
							</svg>
						</span>

						<span class="mto-mycourses--tag mto-btn mto-btn-accent">Book</span>
					</div>
					<h3 class="mto-mycourses--header--title">Jango Courses</h3>
				</div>
				<div class="mto-mycourses--body">
						<div class="mto-mycourses--body--duration mto-flex mto-flex--space-between">
							<div class="mto-time-wrap">
								<svg class="mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
									<path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"/>
									<path d="M13 7h-2v6h6v-2h-4z"/>
								</svg>

								<time class="mto-courses--body--time">10 hrs</time>
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

				<div class="mto-mycourses--footer mto-flex mto-flex--space-between">
					<div class="mto-mycourses--date">Started Jan 5, 2021</div>
					<a href="#" class="mto-mycourses--btn mto-btn mto-btn-primary">Continue</a>
				</div>
			</div>
		</div>

		<div class="mto-mycourses--card">
			<div class="mto-mycourses--thumbnail">
				<img class="mto-mycourses--img" src="<?php echo esc_html__( masteriyo_img_url('db-course-thumbnail.jpg'));?>" alt="You are your only limit" />
			</div>
			<div class="mto-mycourses--detail">
				<div class="mto-mycourses--header">
					<div class="mto-mycourses--rt">
						<span class="mto-mycourses--rating">
							<svg class="mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"/>
							</svg>
							<svg class=" mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"/>
							</svg>
							<svg class=" mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"/>
							</svg>
							<svg class=" mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"/>
							</svg>
							<svg class=" mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M5.025 20.775A.998.998 0 006 22a1 1 0 00.555-.168L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082a1 1 0 00-.59-1.74l-5.701-.454-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.214 4.107-1.491 6.452zM12 5.429l2.042 4.521.588.047h.001l3.972.315-3.271 2.944-.001.002-.463.416.171.597v.003l1.253 4.385L12 15.798V5.429z"/>
							</svg>
						</span>

						<span class="mto-mycourses--tag mto-btn mto-btn-accent">Book</span>
					</div>
					<h3 class="mto-mycourses--header--title">Jango Courses</h3>
				</div>
				<div class="mto-mycourses--body">
						<div class="mto-mycourses--body--duration mto-flex mto-flex--space-between">
							<div class="mto-time-wrap">
								<svg class="mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
									<path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"/>
									<path d="M13 7h-2v6h6v-2h-4z"/>
								</svg>

								<time class="mto-courses--body--time">10 hrs</time>
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

				<div class="mto-mycourses--footer mto-flex mto-flex--space-between">
					<div class="mto-mycourses--date">Started Jan 5, 2021</div>
					<a href="#" class="mto-mycourses--btn mto-btn mto-btn-primary">Continue</a>
				</div>
			</div>
		</div>

		<div class="mto-mycourses--card">
			<div class="mto-mycourses--thumbnail">
				<img class="mto-mycourses--img" src="<?php echo esc_html__( masteriyo_img_url('db-course-thumbnail.jpg'));?>" alt="You are your only limit" />
			</div>
			<div class="mto-mycourses--detail">
				<div class="mto-mycourses--header">
					<div class="mto-mycourses--rt">
						<span class="mto-mycourses--rating">
							<svg class="mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"/>
							</svg>
							<svg class=" mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"/>
							</svg>
							<svg class=" mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"/>
							</svg>
							<svg class=" mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"/>
							</svg>
							<svg class=" mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M5.025 20.775A.998.998 0 006 22a1 1 0 00.555-.168L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082a1 1 0 00-.59-1.74l-5.701-.454-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.214 4.107-1.491 6.452zM12 5.429l2.042 4.521.588.047h.001l3.972.315-3.271 2.944-.001.002-.463.416.171.597v.003l1.253 4.385L12 15.798V5.429z"/>
							</svg>
						</span>

						<span class="mto-mycourses--tag mto-btn mto-btn-accent">Book</span>
					</div>
					<h3 class="mto-mycourses--header--title">Jango Courses</h3>
				</div>
				<div class="mto-mycourses--body">
						<div class="mto-mycourses--body--duration mto-flex mto-flex--space-between">
							<div class="mto-time-wrap">
								<svg class="mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
									<path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"/>
									<path d="M13 7h-2v6h6v-2h-4z"/>
								</svg>

								<time class="mto-courses--body--time">10 hrs</time>
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

				<div class="mto-mycourses--footer mto-flex mto-flex--space-between">
					<div class="mto-mycourses--date">Started Jan 5, 2021</div>
					<a href="#" class="mto-mycourses--btn mto-btn mto-btn-primary">Continue</a>
				</div>
			</div>
		</div>

	</div>

	<a class="mto-mycourses--btn mto-btn mto-btn-default mto-inline-flex" href="#">
		<span>Show All Courses</span>
		<svg class="mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
			<path d="M10.707 17.707L16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"/>
		</svg>
	</a>

</div>

<div class="mto-myachivement">
	<h2 class="mto-myachivement--title">My Achivements</h2>
	<div class="mto-myachivement--notify-message mto-alert mto-info-msg">
		<span>You have no achievements yet. Enroll in course to get an achievements</span>
	</div>
</div>

<div class="mto-mycertificates">
	<h2 class="mto-mycertificates--title">My Certificates</h2>
	<div class="mto-certificate--list">
		<ul class="mto-mycertificates mto-mycertificates--list-wrap">
			<li>
				<div class="mto-header">
					<span>Course</span>
					<span>Certificates</span>
				</div>
			</li>
			<li>
				<div class="mto-flex">
					<div class="mto-inline-flex">
						<img class="mto-c-img" src="./img/certificate.jpg" alt="">
						<h3 class="mto-c-title">Building a Better Software</h3>
					</div>
					<div>
						<a class="mto-c-btn mto-btn mto-btn-primary" href="#">
							<span>Download</span>
						</a>
					</div>
				</div>
			</li>

		</ul>
	</div>
	<a class="btn mto-font-semibold mto-text-sm mto-text-pColor hover:mto-text-white mto-bg-white mto-border hover:mto-border-0" href="#">
		<span class="mto-inline-flex">Show All Certificates</span>
		<svg class="mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
			<path d="M10.707 17.707L16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"/>
		</svg>
	</a>
</div>

<?php
