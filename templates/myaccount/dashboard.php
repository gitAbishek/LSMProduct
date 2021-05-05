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
		<svg class="mto-icon-svg"xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
			<path d="M16.192 6.344l-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"/>
		</svg>
	</a>

	<h3 class="mto-title"><?php echo esc_html__( 'Hello', 'masteriyo' ); ?>, <span class="mto-profile-name">Jamie</span></h3>

	<p class="mto-welcome-msg"><?php echo esc_html__( 'Welcome to your dashboard here you can view your overview and your stats', 'masteriyo' ); ?></p>

	<a
		class="mto-view-myaccount mto-btn mto-btn-default"
		href="<?php echo esc_url( masteriyo_get_account_endpoint_url( 'view-myaccount' ) ); ?>"
	>
		<span class="mto-text-pColor"><?php echo esc_html__( 'View Profile', 'masteriyo' ); ?></span>
		<svg class="mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
			<path d="M10.707 17.707L16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"/>
		</svg>
	</a>
</div>

<div class="mto-counter">
	<div class="mto-counter--inprogress mto-db-card">
		<div class="mto-icon-title">
			<span class="mto-icon">
				<svg class="mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M22.8 7.6l-9.7-3.2c-.7-.2-1.4-.2-2.2 0L1.2 7.6C.5 7.9 0 8.6 0 9.4s.5 1.5 1.2 1.8l.8.3c-.1.2-.2.4-.2.7-.4.2-.7.6-.7 1.2 0 .4.2.8.5 1L.6 19c-.1.4.2.8.6.8h2.1c.4 0 .7-.4.6-.8l-1-4.6c.3-.2.5-.6.5-1s-.2-.8-.5-1c0-.1.1-.3.2-.4l2.1.7-.5 4.6c0 1.4 3.2 2.6 7.2 2.6s7.2-1.2 7.2-2.6l-.5-4.6 4.1-1.4c.7-.2 1.2-1 1.2-1.8.1-.9-.4-1.6-1.1-1.9zm-5.5 9.2c-2.2 1.4-8.5 1.4-10.7 0l.4-3.6 3.8 1.3c.4.1 1.2.3 2.2 0l3.8-1.3.5 3.6zm-4.8-4.2c-.4.1-.7.1-1.1 0l-5.7-1.9L12 9.4c.3-.1.5-.4.5-.8-.1-.4-.4-.6-.7-.5L4.2 9.7c-.2 0-.4.1-.7.2L2 9.4l9.5-3.1c.4-.1.7-.1 1.1 0L22 9.4l-9.5 3.2z"/>
				</svg>
			</span>
			<h3 class="mto-title"><?php echo esc_html__( 'In Progress', 'masteriyo' ); ?></h3>
		</div>
		<span class="mto-number">2</span>
		<div class="mto-subtitle"><?php echo esc_html__( 'Courses', 'masteriyo' ); ?></div>
	</div>

	<div class="mto-counter--scores mto-db-card">
		<div class="mto-icon-title">
			<span class="mto-icon">
				<svg class="mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
				<path d="M21 4h-3V3a1 1 0 00-1-1H7a1 1 0 00-1 1v1H3a1 1 0 00-1 1v3c0 4.31 1.799 6.91 4.819 7.012A6.001 6.001 0 0011 17.91V20H9v2h6v-2h-2v-2.09a6.01 6.01 0 004.181-2.898C20.201 14.91 22 12.31 22 8V5a1 1 0 00-1-1zM4 8V6h2v6.83C4.216 12.078 4 9.299 4 8zm8 8c-2.206 0-4-1.794-4-4V4h8v8c0 2.206-1.794 4-4 4zm6-3.17V6h2v2c0 1.299-.216 4.078-2 4.83z"/>
				</svg>
			</span>
			<h3 class="mto-title"><?php echo esc_html__( 'Scores', 'masteriyo' ); ?></h3>
		</div>
		<span class="mto-number">20</span>
		<div class="mto-subtitle"><?php echo esc_html__( 'Reward Points', 'masteriyo' ); ?></div>
	</div>

	<div class="mto-counter--certificates mto-db-card">
		<div class="mto-icon-title">
			<span class="mto-icon">
				<svg class="mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M12 22c3.859 0 7-3.141 7-7s-3.141-7-7-7c-3.86 0-7 3.141-7 7s3.14 7 7 7zm0-12c2.757 0 5 2.243 5 5s-2.243 5-5 5-5-2.243-5-5 2.243-5 5-5zm-1-8H7v5.518a8.957 8.957 0 014-1.459V2zm6 0h-4v4.059a8.957 8.957 0 014 1.459V2z"/>
					<path d="M10.019 15.811l-.468 2.726L12 17.25l2.449 1.287-.468-2.726 1.982-1.932-2.738-.398L12 11l-1.225 2.481-2.738.398z"/>
			</svg>
			</span>
			<h3 class="mto-title"><?php echo esc_html__( 'Certificates', 'masteriyo' ); ?></h3>
		</div>
		<span class="mto-number">2</span>
		<div class="mto-subtitle"><?php echo esc_html__( 'Courses', 'masteriyo' ); ?></div>
	</div>
	
</div>

<div class="mto-sc mto-flex mto-flex--space-between">
	<div class="mto-sc--stat mto-db-card">
		<div class="mto-sc--stat--header">
			<h3 class="mto-sc--stat--hour">24.33</h3>
			<span class="mto-sc--stat--hourdetail"><?php echo esc_html__( 'Hours spend last week', 'masteriyo' ); ?></span>
		</div>
		<img class="mto-sc-stat--img" src="<?php echo esc_html__( masteriyo_img_url('chart.png'));?>" alt="">
	</div>

	<div class="mto-sc--pcourse mto-db-card">
		<div class="mto-sc--pcourse--header">
			<h3 class="mto-sc--pcourse--title"><?php echo esc_html__( 'Popular Course', 'masteriyo' ); ?></h3>
		</div>
		<ul class="mto-sc--pcourse--list mto-flex">
			<li>
				<a class="mto-sc--pcourse--course" href="#">
					<img src="<?php echo esc_html__( masteriyo_img_url('dummyimg.jpg'));?>" class="mto-sc--pcourse--img" alt="Building Javascript Calendar" />
					<span class="mto-sc--pcourse--title ">Building Javascript Calendar</span>
				</a>
			</li>
			<li>
				<a class="mto-sc--pcourse--course" href="#">
					<img src="<?php echo esc_html__( masteriyo_img_url('dummyimg.jpg'));?>" class="mto-sc--pcourse--img" alt="Building Javascript Calendar" />
					<span class="mto-sc--pcourse--title ">Building Javascript Calendar</span>
				</a>
			</li>
			<li>
				<a class="mto-sc--pcourse--course" href="#">
					<img src="<?php echo esc_html__( masteriyo_img_url('dummyimg.jpg'));?>" class="mto-sc--pcourse--img" alt="Building Javascript Calendar" />
					<span class="mto-sc--pcourse--title ">Building Javascript Calendar</span>
				</a>
			</li>
			<li>
				<a class="mto-sc--pcourse--course" href="#">
					<img src="<?php echo esc_html__( masteriyo_img_url('dummyimg.jpg'));?>" class="mto-sc--pcourse--img" alt="Building Javascript Calendar" />
					<span class="mto-sc--pcourse--title ">Building Javascript Calendar</span>
				</a>
			</li>
			
		</ul>
		<a class="mto-sc--pcourse--btn mto-link-primary" href="#">
			<span class="mto-inline-flex"><?php echo esc_html__( 'Show All', 'masteriyo' ); ?></span>
			<svg class="mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
				<path d="M10.707 17.707L16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"/>
			</svg>
		</a>
	</div>
</div>

<div class="mto-cstudy">
	<div class="mto-cstudy--header">
		<h3 class="mto-cstudy--title"><?php echo esc_html__( 'Continue Studying', 'masteriyo' ); ?></h3>
		<a class="mto-cstudy--btn mto-btn mto-btn-default" href="#">
			<span><?php echo esc_html__( 'Show All', 'masteriyo' ); ?></span>
			<svg class="mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
				<path d="M10.707 17.707L16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"/>
			</svg>
		</a>
	</div>

	<div class="mto-cstudy--body">
		<ul>
			<li>
				<div class="mto-cstudy--body--wrap mto-flex mto-flex-ycenter mto-db-card">
					<div class="mto-cstudy--body--img-title">
						<img class="mto-cstudy--body--img" src="<?php echo esc_html__( masteriyo_img_url('dummyimg.jpg'));?>" alt="">
						<div class="mto-cstudy--body--header">
							<span class="mto-cstudy--body--rating">
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

							<h3 class="mto-cstudy--body--title">Swift Couses</h3>

							<div class="mto-cstudy--body--duration">
								<svg class="mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
									<path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"/>
									<path d="M13 7h-2v6h6v-2h-4z"/>
								</svg>

								<time class="mto-cstudy--body--time">10 hrs</time>
							</div>
						</div>
					</div>

					<div class="mto-cstudy--body--tag">
						<span class="mto-btn mto-btn-accent">Book</span>
					</div>

					<div class="mto-cstudy--body--pbar mto-pbar">
						<div class="mto-progressbar">
							<span class="mto-bar" style="width:50%;">
								<span class="mto-progress">50%</span>
							</span>
						</div>
						<div class="mto-cstudy--body--caption">
							Started Jan 5, 2020
						</div>
					</div>
					<div class="mto-cstudy--body--pstatus">50% Complete</div>
					<div>
						<a class="mto-cstudy--body--btn mto-btn mto-btn-primary " href="#">
							<span><?php echo esc_html__( 'Continue', 'masteriyo' ); ?></span>
						</a>
					</div>
				</div>
			</li>

			<li>
				<div class="mto-cstudy--body--wrap mto-flex mto-flex-ycenter mto-db-card">
					<div class="mto-cstudy--body--img-title">
						<img class="mto-cstudy--body--img" src="<?php echo esc_html__( masteriyo_img_url('dummyimg.jpg'));?>" alt="">
						<div class="mto-cstudy--body--header">
							<span class="mto-cstudy--body--rating">
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

							<h3 class="mto-cstudy--body--title">Swift Couses</h3>

							<div class="mto-cstudy--body--duration">
								<svg class="mto-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
									<path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"/>
									<path d="M13 7h-2v6h6v-2h-4z"/>
								</svg>

								<time class="mto-cstudy--body--time">10 hrs</time>
							</div>
						</div>
					</div>

					<div class="mto-cstudy--body--tag">
						<span class="mto-btn mto-btn-accent">Book</span>
					</div>

					<div class="mto-cstudy--body--pbar mto-pbar">
						<div class="mto-progressbar">
							<span class="mto-bar" style="width:25%;">
								<span class="mto-progress">25%</span>
							</span>
						</div>
						<div class="mto-cstudy--body--caption">
							Started Jan 5, 2020
						</div>
					</div>
					<div class="mto-cstudy--body--pstatus">25% Complete</div>
					<div>
						<a class="mto-cstudy--body--btn mto-btn mto-btn-primary " href="#">
							<span><?php echo esc_html__( 'Continue', 'masteriyo' ); ?></span>
						</a>
					</div>
				</div>
			</li>
		</ul>
	</div>
</div>

<?php
