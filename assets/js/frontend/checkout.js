// global mtoCheckoutParams
jQuery(function ($) {
	// Bail if the global checkout paramaters doesn't exits.
	if (typeof mto_checkout_params === 'undefined') {
		return false;
	}

	var checkout_form = {
		$checkout_form: $('form.masteriyo-checkout'),
		init: function () {
			// Payment methods
			this.$checkout_form.on(
				'click',
				'input[name="payment_method"]',
				this.payment_method_selected
			);

			// Prevent HTML5 validation which can conflict.
			this.$checkout_form.attr('novalidate', 'novalidate');

			// Form submission
			this.$checkout_form.on('submit', this.submit);

			this.init_payment_methods();

			// Update on page load
			if (true === mto_checkout_params.is_checkout) {
				$(document.body).trigger('init_checkout');
			}
		},
		init_payment_methods: function () {
			var $payment_methods = this.$checkout_form.find(
				'input[name="payment_method"]'
			);

			// If there is one method, we can hide the radio input
			if (1 === $payment_methods.length) {
				$payment_methods.eq(0).hide();
			}

			// If there was a previously selected method, check that one.
			if (checkout_form.selected_payment_method) {
				$('#' + checkout_form.selected_payment_method).prop('checked', true);
			}

			// If there are none selected, select the first.
			if (0 === $payment_methods.filter(':checked').length) {
				$payment_methods.eq(0).prop('checked', true);
			}

			// Get name of new selected method.
			var checked_payment_method = $payment_methods
				.filter(':checked')
				.eq(0)
				.prop('id');

			if ($payment_methods.length > 1) {
				// Hide open descriptions.
				$('div.payment-box:not(".' + checked_payment_method + '")')
					.filter(':visible')
					.slideUp(0);
			}

			// Trigger click event for selected method
			$payment_methods.filter(':checked').eq(0).trigger('click');
		},
		get_payment_method: function () {
			return this.$checkout_form
				.find('input[name="payment_method"]:checked')
				.val();
		},

		payment_method_selected: function (e) {
			e.stopPropagation();

			if ($('.payment-methods input.input-radio').length > 1) {
				var target_payment_box = $('div.payment-box.' + $(this).attr('ID')),
					is_checked = $(this).is(':checked');

				if (is_checked && !target_payment_box.is(':visible')) {
					$('div.payment-box').filter(':visible').slideUp(230);

					if (is_checked) {
						target_payment_box.slideDown(230);
					}
				}
			} else {
				$('div.payment-box').show();
			}

			if ($(this).data('order_button_text')) {
				$('#masteriyo-place-order').text($(this).data('order_button_text'));
			} else {
				$('#masteriyo-place-order').text(
					$('#masteriyo-place-order').data('value')
				);
			}

			var selected_payment_method = $(
				'.masteriyo-checkout input[name="payment_method"]:checked'
			).attr('id');

			if (selected_payment_method !== this.selected_payment_method) {
				$(document.body).trigger('payment_method_selected');
			}

			this.selected_payment_method = selected_payment_method;
		},
		init_checkout: function () {
			$(document.body).trigger('update_checkout');
		},
		reset_update_checkout_timer: function () {
			clearTimeout(checkout_form.updateTimer);
		},
		is_valid_json: function (raw_json) {
			try {
				var json = JSON.parse(raw_json);

				return json && 'object' === typeof json;
			} catch (e) {
				return false;
			}
		},
		update_checkout: function (event, args) {
			// Small timeout to prevent multiple requests when several fields update at the same time
			checkout_form.reset_update_checkout_timer();
			checkout_form.updateTimer = setTimeout(
				checkout_form.update_checkout_action,
				'5',
				args
			);
		},
	};

	checkout_form.init();
});
