<?php
/**
 * The template for displaying user dashboard.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="welcome-note mto-bg-primary mto-rounded mto-p-8 mto-relative mto-text-white">
	<span class="close mto-absolute hover:mto-bg-white hover:mto-bg-opacity-20 mto-rounded-full mto-top-4 mto-right-4 mto-cursor-pointer mto-transform hover:mto-scale-125 mto-transition-all">
		<svg class="mto-icon mto-fill-current mto-text-white"xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
			<path d="M16.192 6.344l-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"/>
		</svg>
	</span>

	<h2 class="mto-font-semibold mto-text-white mto-text-2xl"><?php echo esc_html__( 'Hello', 'masteriyo' ); ?>, <span class="mto-font-normal">Jamie</span></h2>

	<p class="mto-text-white mto-text-base mto-mt-4"><?php echo esc_html__( 'Welcome to your dashboard here you can view your overview and your stats', 'masteriyo' ); ?></p>

	<a
		class="view-myaccount btn mto-px-6 mto-py-3 mto-bg-white mto-inline-flex mto-justify-center mto-items-center mto-text-sm mto-uppercase hover:mto-bg-white"
		href="<?php echo esc_url( masteriyo_get_account_endpoint_url( 'view-myaccount' ) ); ?>"
	>
		<span class="mto-text-pColor"><?php echo esc_html__( 'View Profile', 'masteriyo' ); ?></span>
		<svg class="mto-icon mto-fill-current mto-text-pColor mto--mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
			<path d="M10.707 17.707L16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"/>
		</svg>
	</a>
</div>

<div class="user-counter mto-card-wrapper">
	<div class="user-inprogress mto-card hover:mto-shadow-lg">
		<div class="mto-flex mto-items-center mto-space-x-4">
			<span class="mto-bg-green-50 mto-rounded-full mto-p-4">
				<svg class="mto-fill-current mto-text-green-500 mto-w-8 mto-h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M22.8 7.6l-9.7-3.2c-.7-.2-1.4-.2-2.2 0L1.2 7.6C.5 7.9 0 8.6 0 9.4s.5 1.5 1.2 1.8l.8.3c-.1.2-.2.4-.2.7-.4.2-.7.6-.7 1.2 0 .4.2.8.5 1L.6 19c-.1.4.2.8.6.8h2.1c.4 0 .7-.4.6-.8l-1-4.6c.3-.2.5-.6.5-1s-.2-.8-.5-1c0-.1.1-.3.2-.4l2.1.7-.5 4.6c0 1.4 3.2 2.6 7.2 2.6s7.2-1.2 7.2-2.6l-.5-4.6 4.1-1.4c.7-.2 1.2-1 1.2-1.8.1-.9-.4-1.6-1.1-1.9zm-5.5 9.2c-2.2 1.4-8.5 1.4-10.7 0l.4-3.6 3.8 1.3c.4.1 1.2.3 2.2 0l3.8-1.3.5 3.6zm-4.8-4.2c-.4.1-.7.1-1.1 0l-5.7-1.9L12 9.4c.3-.1.5-.4.5-.8-.1-.4-.4-.6-.7-.5L4.2 9.7c-.2 0-.4.1-.7.2L2 9.4l9.5-3.1c.4-.1.7-.1 1.1 0L22 9.4l-9.5 3.2z"/>
				</svg>
			</span>
			<h3 class="mto-font-bold mto-text-base mto-text-textColor"><?php echo esc_html__( 'In Progress', 'masteriyo' ); ?></h3>
		</div>
		<span class="mto-inline-block mto-text-textColor mto-text-4xl mto-font-bold">2</span>
		<div class="mto-text-gray-700"><?php echo esc_html__( 'Courses', 'masteriyo' ); ?></div>
	</div>

	<div class="user-scores mto-card hover:mto-shadow-lg">
		<div class="mto-flex mto-items-center mto-space-x-4">
			<span class="mto-bg-secondary-50 mto-rounded-full mto-p-3">
				<svg class="mto-fill-current mto-text-secondary mto-w-8 mto-h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M21 4h-3V3a1 1 0 00-1-1H7a1 1 0 00-1 1v1H3a1 1 0 00-1 1v3c0 4.31 1.799 6.91 4.819 7.012A6.001 6.001 0 0011 17.91V20H9v2h6v-2h-2v-2.09a6.01 6.01 0 004.181-2.898C20.201 14.91 22 12.31 22 8V5a1 1 0 00-1-1zM4 8V6h2v6.83C4.216 12.078 4 9.299 4 8zm8 8c-2.206 0-4-1.794-4-4V4h8v8c0 2.206-1.794 4-4 4zm6-3.17V6h2v2c0 1.299-.216 4.078-2 4.83z"/>
					</svg>
			</span>
			<h3 class="mto-font-bold mto-text-base mto-text-textColor"><?php echo esc_html__( 'Scores', 'masteriyo' ); ?></h3>
		</div>
		<span class="mto-inline-block mto-text-textColor mto-text-4xl mto-font-bold">20</span>
		<div class="mto-text-gray-700"><?php echo esc_html__( 'Reward Points', 'masteriyo' ); ?></div>
	</div>

	<div class="user-certificates mto-card hover:mto-shadow-lg">
		<div class="mto-flex mto-items-center mto-space-x-4">
			<span class="mto-bg-primary-50 mto-rounded-full mto-p-3">
				<svg class="mto-fill-current mto-text-primary mto-w-8 mto-h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M12 22c3.859 0 7-3.141 7-7s-3.141-7-7-7c-3.86 0-7 3.141-7 7s3.14 7 7 7zm0-12c2.757 0 5 2.243 5 5s-2.243 5-5 5-5-2.243-5-5 2.243-5 5-5zm-1-8H7v5.518a8.957 8.957 0 014-1.459V2zm6 0h-4v4.059a8.957 8.957 0 014 1.459V2z"/>
					<path d="M10.019 15.811l-.468 2.726L12 17.25l2.449 1.287-.468-2.726 1.982-1.932-2.738-.398L12 11l-1.225 2.481-2.738.398z"/>
				</svg>
			</span>
			<h3 class="mto-font-bold mto-text-base mto-text-textColor"><?php echo esc_html__( 'Certificates', 'masteriyo' ); ?></h3>
		</div>
		<span class="mto-inline-block mto-text-textColor mto-text-4xl mto-font-bold">2</span>
		<div class="mto-text-gray-700"><?php echo esc_html__( 'Courses', 'masteriyo' ); ?></div>
	</div>
</div>

<div class="mto-card-wrapper">
	<div class="user-statistic mto-card mto-full md:mto-w-3/4 hover:mto-shadow-lg">
		<div class="card-header">
			<span class="mto-block mto-text-textColor mto-text-4xl mto-font-bold">24.33</span>
			<span class="mto-block mto-font-bold mto-text-textColor mto-text-base"><?php echo esc_html__( 'Hours spend last week', 'masteriyo' ); ?></span>
		</div>
		<div>
			<img class="mto-w-full" src="./img/chart.png" alt="">
		</div>
	</div>

	<div class="db-popular-course-list mto-card hover:mto-shadow-lg mto-space-y-10">
		<div class="mto-card-header">
			<h4 class="mto-card-header"><?php echo esc_html__( 'Popular Course', 'masteriyo' ); ?></h4>
		</div>
		<ul class="mto-space-y-4">
			<li >
				<a class="mto-flex mto-space-x-4 mto-text-textColor hover:mto-text-primary" href="#">
					<img src="./img/pcourse1.jpg" alt="Building Javascript Calendar" />
					<span class="mto-text-sm mto-font-bold ">Building Javascript Calendar</span>
				</a>
			</li>
			<li>
				<a class="mto-flex mto-space-x-4 mto-text-textColor hover:mto-text-primary" href="#">
					<img src="./img/pcourse2.jpg" alt="Building Javascript Calendar" />
					<span class="mto-text-sm mto-font-bold">Building Javascript Calendar</span>
				</a>
			</li>

			<li>
				<a class="mto-flex mto-space-x-4 mto-text-textColor hover:mto-text-primary" href="#">
					<img src="./img/pcourse3.jpg" alt="Building Javascript Calendar" />
					<span class="mto-text-sm mto-font-bold">Building Javascript Calendar</span>
				</a>
			</li>

			<a class="mto-flex mto-space-x-4 mto-text-textColor hover:mto-text-primary" href="#">
				<img src="./img/pcourse2.jpg" alt="Building Javascript Calendar" />
				<span class="mto-text-sm mto-font-bold">Building Javascript Calendar</span>
			</a>
		</li>
		</ul>
		<a class="mto-block mto-items-center mto-font-semibold mto-text-sm mto-text-pColor mto-uppercase hover:mto-text-primary" href="#">
			<span class="mto-inline-flex"><?php echo esc_html__( 'Show All', 'masteriyo' ); ?></span>
			<svg class="mto-inline-flex mto-icon mto-fill-current mto--mt-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
				<path d="M10.707 17.707L16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"/>
			</svg>
		</a>
	</div>
</div>

<div class="study-wrapper md:mto-mt-0 mto-space-y-8">
	<div class="study-header mto-flex mto-flex-row mto-mt-16 md:mto-justify-between">
		<h2 class="mto-font-bold mto-text-2xl mto-text-textColor mto-mb-0"><?php echo esc_html__( 'Continue Studying', 'masteriyo' ); ?></h2>
		<a class="btn mto-font-semibold mto-text-sm mto-text-pColor hover:mto-text-white mto-bg-white mto-border hover:mto-border-0" href="#">
			<span class="mto-inline-flex"><?php echo esc_html__( 'Show All', 'masteriyo' ); ?></span>
			<svg class="mto-inline-flex mto-icon mto-fill-current mto--mt-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
				<path d="M10.707 17.707L16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"/>
			</svg>
		</a>
	</div>
	<div class="study-list">
		<ul class="mto-space-y-6">
			<li>
				<div class="mto-card mto-flex md:mto-flex-row mto-items-center md:mto-space-x-4">
					<img class="mto-w-20" src="./img/cstudy1.jpg" alt="">
					<div class="mto-space-y-1 mto-flex-row md:mto-flex-none">
						<span class="mto-inline-block">
							<svg class="mto-inline-block mto-fill-current mto-text-gray-700 mto-w-4 mto-h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"/>
							</svg>
							<svg class=" mto-inline-block mto-fill-current mto-text-gray-700 mto-w-4 mto-h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"/>
							</svg>
							<svg class=" mto-inline-block mto-fill-current mto-text-gray-700 mto-w-4 mto-h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"/>
							</svg>
							<svg class=" mto-inline-block mto-fill-current mto-text-gray-700 mto-w-4 mto-h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"/>
							</svg>
							<svg class=" mto-inline-block mto-fill-current mto-text-gray-700 mto-w-4 mto-h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M5.025 20.775A.998.998 0 006 22a1 1 0 00.555-.168L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082a1 1 0 00-.59-1.74l-5.701-.454-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.214 4.107-1.491 6.452zM12 5.429l2.042 4.521.588.047h.001l3.972.315-3.271 2.944-.001.002-.463.416.171.597v.003l1.253 4.385L12 15.798V5.429z"/>
							</svg>
						</span>

						<h3 class="mto-font-bold mto-text-textColor mto-text-base">Swift Couses</h3>

						<div class="mto-w-full mto-flex mto-space-x-1">
							<svg class="mto-inline-block mto-fill-current mto-text-textColor mto-w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"/>
								<path d="M13 7h-2v6h6v-2h-4z"/>
							</svg>

							<time class="mto-inline-block mto-text-sm mto-text-textColor">10 hrs</time>
						</div>

					</div>
					<div>
						<span class="md:mto-inline-block mto-bg-secondary mto-rounded-full mto-px-4 mto-py-1 mto-text-xs mto-uppercase mto-font-medium mto-text-white mto-ml-2">Book</span>
					</div>
					<div class="mto-block mto-flex-grow mto-space-y-2 mto-px-4">
						<div class="mto-shadow mto-w-full mto-bg-primary-100 mto-rounded">
							<div class="mto-bg-primary mto-text-xs mto-leading-none mto-py-1 mto-text-center mto-text-white mto-rounded" style="width: 40%"></div>
						</div>
						<div class="mto-block mto-text-xs mto-text-pColor">
							Started Jan 5, 2020
						</div>
					</div>
					<div class="mto-font-semibold mto-text-textColor mto-text-xs">15% Complete</div>
					<div>
						<a class="btn mto-uppercase" href="#">
							<span class="mto-text-white mto-inline-flex"><?php echo esc_html__( 'Continue', 'masteriyo' ); ?></span>
						</a>
					</div>
				</div>
			</li>

			<li>
				<div class="mto-card mto-flex md:mto-flex-row mto-items-center md:mto-space-x-4">
					<img class="mto-w-20" src="./img/cstudy2.jpg" alt="">
					<div class="mto-space-y-1 mto-flex-row md:mto-flex-none">
						<span class="mto-inline-block">
							<svg class="mto-inline-block mto-fill-current mto-text-gray-700 mto-w-4 mto-h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"/>
							</svg>
							<svg class=" mto-inline-block mto-fill-current mto-text-gray-700 mto-w-4 mto-h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"/>
							</svg>
							<svg class=" mto-inline-block mto-fill-current mto-text-gray-700 mto-w-4 mto-h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"/>
							</svg>
							<svg class=" mto-inline-block mto-fill-current mto-text-gray-700 mto-w-4 mto-h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"/>
							</svg>
							<svg class=" mto-inline-block mto-fill-current mto-text-gray-700 mto-w-4 mto-h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M5.025 20.775A.998.998 0 006 22a1 1 0 00.555-.168L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082a1 1 0 00-.59-1.74l-5.701-.454-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.214 4.107-1.491 6.452zM12 5.429l2.042 4.521.588.047h.001l3.972.315-3.271 2.944-.001.002-.463.416.171.597v.003l1.253 4.385L12 15.798V5.429z"/>
							</svg>
						</span>

						<h3 class="mto-font-bold mto-text-textColor mto-text-base">Swift Couses</h3>

						<div class="mto-w-full mto-flex mto-space-x-1">
							<svg class="mto-inline-block mto-fill-current mto-text-textColor mto-w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"/>
								<path d="M13 7h-2v6h6v-2h-4z"/>
							</svg>

							<time class="mto-inline-block mto-text-sm mto-text-textColor">10 hrs</time>
						</div>

					</div>
					<div>
						<span class="md:mto-inline-block mto-bg-secondary mto-rounded-full mto-px-4 mto-py-1 mto-text-xs mto-uppercase mto-font-medium mto-text-white mto-ml-2">Book</span>
					</div>
					<div class="mto-block mto-flex-grow mto-space-y-2 mto-px-4">
						<div class="mto-shadow mto-w-full mto-bg-primary-100 mto-rounded">
							<div class="mto-bg-primary mto-text-xs mto-leading-none mto-py-1 mto-text-center mto-text-white mto-rounded" style="width: 40%"></div>
						</div>
						<div class="mto-block mto-text-xs mto-text-pColor">
							Started Jan 5, 2020
						</div>
					</div>
					<div class="mto-font-semibold mto-text-textColor mto-text-xs">15% Complete</div>
					<div>
						<a class="btn mto-uppercase" href="#">
							<span class="mto-text-white mto-inline-flex">Continue</span>
						</a>
					</div>
				</div>
			</li>

			<li>
				<div class="mto-card mto-flex md:mto-flex-row mto-items-center md:mto-space-x-4">
					<img class="mto-w-20" src="./img/cstudy3.jpg" alt="">
					<div class="mto-space-y-1 mto-flex-row md:mto-flex-none">
						<span class="mto-inline-block">
							<svg class="mto-inline-block mto-fill-current mto-text-gray-700 mto-w-4 mto-h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"/>
							</svg>
							<svg class=" mto-inline-block mto-fill-current mto-text-gray-700 mto-w-4 mto-h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"/>
							</svg>
							<svg class=" mto-inline-block mto-fill-current mto-text-gray-700 mto-w-4 mto-h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"/>
							</svg>
							<svg class=" mto-inline-block mto-fill-current mto-text-gray-700 mto-w-4 mto-h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M21.947 9.179a1.001 1.001 0 00-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 001.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"/>
							</svg>
							<svg class=" mto-inline-block mto-fill-current mto-text-gray-700 mto-w-4 mto-h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M5.025 20.775A.998.998 0 006 22a1 1 0 00.555-.168L12 18.202l5.445 3.63a1.001 1.001 0 001.517-1.106l-1.829-6.4 4.536-4.082a1 1 0 00-.59-1.74l-5.701-.454-2.467-5.461a.998.998 0 00-1.822-.001L8.622 8.05l-5.701.453a1 1 0 00-.619 1.713l4.214 4.107-1.491 6.452zM12 5.429l2.042 4.521.588.047h.001l3.972.315-3.271 2.944-.001.002-.463.416.171.597v.003l1.253 4.385L12 15.798V5.429z"/>
							</svg>
						</span>

						<h3 class="mto-font-bold mto-text-textColor mto-text-base">Swift Couses</h3>

						<div class="mto-w-full mto-flex mto-space-x-1">
							<svg class="mto-inline-block mto-fill-current mto-text-textColor mto-w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"/>
								<path d="M13 7h-2v6h6v-2h-4z"/>
							</svg>

							<time class="mto-inline-block mto-text-sm mto-text-textColor">10 hrs</time>
						</div>

					</div>
					<div>
						<span class="md:mto-inline-block mto-bg-secondary mto-rounded-full mto-px-4 mto-py-1 mto-text-xs mto-uppercase mto-font-medium mto-text-white mto-ml-2">Book</span>
					</div>
					<div class="mto-block mto-flex-grow mto-space-y-2 mto-px-4">
						<div class="mto-shadow mto-w-full mto-bg-primary-100 mto-rounded">
							<div class="mto-bg-primary mto-text-xs mto-leading-none mto-py-1 mto-text-center mto-text-white mto-rounded" style="width: 40%"></div>
						</div>
						<div class="mto-block mto-text-xs mto-text-pColor">
							Started Jan 5, 2020
						</div>
					</div>
					<div class="mto-font-semibold mto-text-textColor mto-text-xs">15% Complete</div>
					<div>
						<a class="btn mto-uppercase" href="#">
							<span class="mto-text-white mto-inline-flex">Continue</span>
						</a>
					</div>
				</div>
			</li>
		</ul>
	</div>
</div>

<?php
