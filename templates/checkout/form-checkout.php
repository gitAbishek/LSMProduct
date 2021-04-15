<?php
use ThemeGrill\Masteriyo\Countries;
?>

<div class="checkout" id="masteriyo-checkout">
	<div class="checkout-wrapper">
		<div class="checkout-main">
			<h2 class="checkout--title">
				<?php esc_html_e( 'Payment Details', 'masteriyo' ); ?>
			</h2>

			<!-- <div class="checkout-alert-msg alert success-msg show">Successfully form submited</div> -->

			<form action="" class="checkout--form">
				<div class="checkout---fname-lname-wrapper">
					<div class="checkout----fname col-2">
						<label for="first-name">
							<?php esc_html_e( 'First Name', 'masteriyo' ); ?>
						</label>
						<input type="text" id="first-name" name="first-name" />
					</div>

					<div class="checkout----lname col-2">
						<label for="last-name">
							<?php esc_html_e( 'Last Name', 'masteriyo' ); ?>
						</label>
						<input type="text" id="last-name" name="last-name" />
					</div>
				</div>

				<div class="checkout---email-wrapper">
					<div class="checkout----email col-1">
						<label for="email">
							<?php esc_html_e( 'Email Address', 'masteriyo' ); ?>
						</label>
						<input type="text" id="email" name="email" />
					</div>
				</div>

				<div class="checkout---phone-wrapper">
					<div class="checkout----phone col-1">
						<label for="phone">
							<?php esc_html_e( 'Phone', 'masteriyo' ); ?>
						</label>
						<input type="tel" id="phone" name="phone" />
					</div>
				</div>

				<div class="checkout---company-name-wrapper">
					<div class="checkout----company-name col-1">
						<label for="company-name">
							<?php esc_html_e( 'Company Name', 'masteriyo'); ?>
						</label>
						<input type="text" id="company-name" name="company-name" />
					</div>
				</div>

				<div class="checkout---country-wrapper">
					<div class="checkout----country col-1">
						<label for="country">Country/Region</label>
						<div class="dropdown">
							<select name="country" id="country">
							<?php Countries::instance()->country_dropdown_options(); ?>
							</select>
						</div>

					</div>
				</div>

				<div class="checkout---street-wrapper">
					<div class="checkout----street col-1">
						<label for="street1">
							<?php esc_html_e( 'Street Address', 'masteriyo'); ?>
						</label>
						<input type="text" id="street1" name="street1" />
						<input type="text" id="street2" name="street2" />
					</div>
				</div>

				<div class="checkout---postcode-wrapper">
					<div class="checkout----postcode col-1">
						<label for="postcode">
							<?php esc_html_e( 'Postcode / ZIP code', 'masteriyo' ); ?>
						</label>
						<input class="danger-msg" type="text" id="postcode" name="postcode" />
					</div>
					<div class="error danger-msg">This field is required</div>
				</div>

			</form>
		</div>

	<?php if ( ! empty( $courses ) ) : ?>
		<div class="checkout-summary">
			<div class="checkout-summary-your-order">
				<h2 class="checkout-summary--title">Your Order</h2>
				<ul class="checkout-summary-order-details">
					<li class="h-border">
						<div>
							<strong><?php esc_html_e( 'Courses', 'masteriyo' ); ?></strong>
							<strong><?php esc_html_e( 'Subtotal', 'masteriyo' ); ?></strong>
						</div>
					</li>

				<?php foreach( $courses as $course ) : ?>
					<li>
						<div>
							<span><?php echo esc_html( $course->get_name() ); ?></span>
							<span><?php echo masteriyo_price( $course->get_price() ); ?></span>
						</div>
					</li>
				<?php endforeach; ?>
					<li>
						<div>
							<strong><?php esc_html_e( 'Subtotal', 'masteriyo' );?></strong>
							<span><?php echo masteriyo_price( $cart->get_subtotal() ); ?></span>
						</div>
					</li>
					<li>
						<div>
							<strong><?php esc_html_e( 'Total', 'masteriyo' ); ?></strong>
							<strong><?php echo masteriyo_price( $cart->get_totals() ) ; ?></strong>
						</div>
					</li>
				</ul>
				<div class="checkout-summary-couponcode">
					<label href="#" for="couponcode-input" class="checkout-summary-couponcode-link">
						<?php esc_html_e( 'Have a Coupon Code?', 'masteriyo' ); ?>
					</label>
					<input type="checkbox" class="checkout-summary-couponcode-input hide" id="couponcode-input" name="couponcode-input" />
					<form class="checkout-summary-couponcode-form hide" action="">
						<div class="checkout-summary-couponcode---wrapper">
							<div class="checkout-summary-couponcode----input full-w">
								<input type="text" />
							</div>
							<div class="checkout-summary-couponcode----button">
								<a href="#" class="btn-primary full-w">
									<?php esc_html_e( 'Apply', 'masteriyo' ); ?>
								</a>
							</div>
						</div>
					</form>
				</div>
			</div>

			<div class="checkout-summary-payment-method">
				<div class="checkout-summary-paypal">
					<img src="./img/paypal.png" class="checkout-summary-paypal--image w-25" alt="">
					<a href="#" class="primary-link">What is paypal?</a>
				</div>
				<div class="checkout-summary-info"><box-icon type="solid" name="lock-alt"></box-icon>Your transaction is secured with SSL encryption</div>
			</div>
			<a href="#" class="checkout-confirm-payment btn-primary full-w">
				<?php printf( esc_html( 'Confirm Payment: %s', 'masteriyo' ), masteriyo_price( $cart->get_totals() ) ); ?>
			</a>
		</div>
	<?php endif; ?>
	</div>
</div>
