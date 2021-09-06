/**
 * global masteriyo_data
 */
(function ($, mto_data) {
	let isSaving = false;

	/**
	 * Tabs handler.
	 */
	$(document.body).on('click', '.masteriyo-tab', function () {
		$(this).siblings('.masteriyo-tab').removeClass('mto-active-tab');
		$(this).addClass('mto-active-tab');
		$('.masteriyo-tab-content').addClass('masteriyo-hidden');
		$('#' + $(this).data('tab')).removeClass('masteriyo-hidden');
	});

	/**
	 * Edit profile form submission handler.
	 */
	$(document.body).on('submit', 'form#mto-edit-profile-form', function (e) {
		e.preventDefault();

		if (isSaving) return;

		isSaving = true;

		const userData = {
			display_name: $('#mto-edit-profile-form #username').val().trim(),
			first_name: $('#mto-edit-profile-form #user-first-name').val(),
			last_name: $('#mto-edit-profile-form #user-last-name').val(),
			user_email: $('#mto-edit-profile-form #user-email').val(),
			address: $('#mto-edit-profile-form #user-address').val(),
			city: $('#mto-edit-profile-form #user-city').val(),
			state: $('#mto-edit-profile-form #user-state').val(),
			zip_code: $('#mto-edit-profile-form #user-zip-code').val(),
			country: $('#mto-edit-profile-form #user-country').val(),
		};

		// Show saving process indicator.
		$('#mto-btn-submit-edit-profile-form')
			.text(mto_data.labels.saving)
			.siblings('.mto-notify-message')
			.remove();

		$.ajax({
			type: 'POST',
			dataType: 'json',
			headers: {
				'Content-Type': 'application/json',
				'X-WP-Nonce': mto_data.nonce,
			},
			url:
				mto_data.rootApiUrl + 'masteriyo/v1/users/' + mto_data.current_user_id,
			data: JSON.stringify(userData),
			success: function (res) {
				// Update username on the sidebar.
				$('#label-username').text(res.display_name);

				// Show success message.
				$('#mto-btn-submit-edit-profile-form').after(
					'<div class="mto-notify-message mto-success-msg"><span>' +
						mto_data.labels.profile_update_success +
						'</span></div>'
				);
			},
			error: function (xhr) {
				// Show failure message.
				$('#mto-btn-submit-edit-profile-form').after(
					'<div class="mto-notify-message mto-error-msg mto-text-red-700 mto-bg-red-100 mto-border-red-300"><span>' +
						xhr.responseJSON.message +
						'</span></div>'
				);
			},
			complete: function () {
				isSaving = false;

				// Remove saving process indicator.
				$('#mto-btn-submit-edit-profile-form').text(mto_data.labels.save);
			},
		});
	});
})(jQuery, window.masteriyo_data);
