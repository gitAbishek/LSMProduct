/**
 * global masteriyo_data
 */
(function( $, mto_data ) {
	/**
	 * API Urls.
	 */
	const urls = {
		...mto_data.urls,
		get: function( api_name, endpoint, params = {} ) {
			if ( mto_data.urls[api_name][endpoint] ) {
				let url = mto_data.urls[api_name][endpoint];

				if ( typeof params === 'object' ) {
					Object.entries(params).forEach(([key, value]) => {
						url = url.replace( `:${key}`, value );
					});
				}
				return url;
			}
			return '';
		},
	};

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

		const userData = {
			first_name: $('#mto-edit-profile-form #user-first-name').val(),
			last_name: $('#mto-edit-profile-form #user-last-name').val(),
			user_email: $('#mto-edit-profile-form #user-email').val(),
			address: $('#mto-edit-profile-form #user-address').val(),
			city: $('#mto-edit-profile-form #user-city').val(),
			state: $('#mto-edit-profile-form #user-state').val(),
			zip_code: $('#mto-edit-profile-form #user-zip-code').val(),
			country: $('#mto-edit-profile-form #user-country').val(),
		};

		$('#mto-btn-submit-edit-profile-form')
		.text('Saving...')
		.siblings('.mto-notify-message').first().remove();

		fetch( urls.get( 'users', 'update_item', { id: 1 }), {
			method: 'post',
			headers: new Headers({
				'Content-Type': 'application/json',
				'X-WP-Nonce': mto_data.nonce,
			}),
			body: JSON.stringify(userData),
		})
		.finally( () => {
			$('#mto-btn-submit-edit-profile-form')
			.text('Save')
			.after(`<div class="mto-notify-message"><span>Successfully updated user profile.</span></div>`)
		});
	});
	$('.menu-open').click(function(){
		$('#vertical-menu').removeClass('mto--ml-96');
	});
	$('.menu-close').click(function(){
		$('#vertical-menu').addClass('mto--ml-96');
	});
})(jQuery, window.masteriyo_data);
