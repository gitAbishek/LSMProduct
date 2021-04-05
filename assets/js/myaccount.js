/**
 * global masteriyo_data
 */
(function( $, mto_data ) {
	let isSaving = false;

	/**
	 * Tabs handler.
	 */
	$( document.body ).on( 'click', '.mto-tab', function() {
		$(this).siblings('.mto-tab').removeClass('active-tab');
		$(this).addClass('active-tab');
		$('.tab-content').addClass('mto-hidden');
		$(`#${ $(this).data('tab') }`).removeClass('mto-hidden');
	});

	/**
	 * Edit profile form submission handler.
	 */
	$( document.body ).on( 'submit', 'form#mto-edit-profile-form', function(e) {
		e.preventDefault();

		if ( isSaving ) return;

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
		.text('Saving...')
		.siblings('.mto-notify-message').remove();

		fetch( `${mto_data.rootApiUrl}masteriyo/v1/users/${mto_data.current_user_id}`, {
			method: 'post',
			headers: new Headers({
				'Content-Type': 'application/json',
				'X-WP-Nonce': mto_data.nonce,
			}),
			body: JSON.stringify(userData),
		})
		.then(async res => {
			if ( ! res.ok ) {
				throw (await res.json()).message;
			}
			res = await res.json();

			// Update username on the sidebar.
			$('#label-username').text( res.display_name );

			// Show success message.
			$('#mto-btn-submit-edit-profile-form')
			.after(`<div class="mto-notify-message mto-success-msg"><span>${mto_data.labels.profile_update_success}</span></div>`);
		})
		.catch(reason => {
			// Show failure message.
			$('#mto-btn-submit-edit-profile-form')
			.after(`<div class="mto-notify-message mto-warning-msg mto-text-red-700 mto-bg-red-100 mto-border-red-300"><span>${reason}</span></div>`);
		})
		.finally(() => {
			isSaving = false;

			// Remove saving process indicator.
			$('#mto-btn-submit-edit-profile-form').text('Save');
		});
	});

	/**
	 * Mobile view sidebar menu toggler.
	 */
	$('.menu-open').click(function(){
		$('#vertical-menu').removeClass('mto--ml-9999');
	});
	$('.menu-close').click(function(){
		$('#vertical-menu').addClass('mto--ml-9999');
	});
})(jQuery, window.masteriyo_data);
