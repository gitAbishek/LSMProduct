/**
 * global masteriyo_data
 */
(function ($, masteriyo_data) {
	/**
	 * Login form submission handler.
	 */
	$(document.body).on('submit', 'form.masteriyo-login--form', function (e) {
		e.preventDefault();

		const $form = $(this);
		const userData = {
			username: $(this).find('#username-email-address').val(),
			password: $(this).find('#password').val(),
			remember: $(this).find('#remember_me').is(':checked') ? 'yes' : 'no',
		};

		$form
			.find('button[type=submit]')
			.text(masteriyo_data.labels.signing_in)
			.siblings('.masteriyo-notify-message')
			.first()
			.remove();

		$(this).find('#masteriyo-login-error-msg').hide();

		$.ajax({
			type: 'post',
			dataType: 'json',
			url: masteriyo_data.ajax_url,
			data: {
				action: 'masteriyo_login',
				nonce: masteriyo_data.nonce,
				payload: userData,
			},
			success: function (res) {
				if (res.success) {
					window.location.reload();
				} else {
					$('#masteriyo-login-error-msg').show().html(res.data.message);
				}
			},
			error: function (xhr, status, error) {
				var message = xhr.responseJSON.message
					? xhr.responseJSON.message
					: error;

				$('#masteriyo-login-error-msg').show().html(message);
			},
			complete: function () {
				$form.find('button[type=submit]').text(masteriyo_data.labels.sign_in);
			},
		});
	});
})(jQuery, window.masteriyo_data);
