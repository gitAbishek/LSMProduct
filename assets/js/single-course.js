(function ($, mto_data) {
	var masteriyo_api = {
		deleteCourseReview: function (id, options) {
			var url = mto_data.rootApiUrl + 'masteriyo/v1/courses/reviews/' + id;
			$.ajax({
				type: 'delete',
				headers: {
					'X-WP-Nonce': mto_data.nonce,
				},
				url: url,
				success: options.onSuccess,
				error: options.onError,
				complete: options.onComplete,
			});
		},
		createCourseReview: function (data, options) {
			var url = mto_data.rootApiUrl + 'masteriyo/v1/courses/reviews';
			$.ajax({
				type: 'post',
				headers: {
					'X-WP-Nonce': mto_data.nonce,
				},
				url: url,
				data: data,
				success: options.onSuccess,
				error: options.onError,
				complete: options.onComplete,
			});
		},
		updateCourseReview: function (id, data, options) {
			var url = mto_data.rootApiUrl + 'masteriyo/v1/courses/reviews/' + id;
			$.ajax({
				type: 'put',
				headers: {
					'X-WP-Nonce': mto_data.nonce,
				},
				url: url,
				data: data,
				success: options.onSuccess,
				error: options.onError,
				complete: options.onComplete,
			});
		},
	};
	var masteriyo_utils = {
		getErrorNotice: function (message) {
			return (
				'<div class="mto-notify-message mto-alert mto-danger-msg"><span>' +
				message +
				'</span></div>'
			);
		},
		getSuccessNotice: function (message) {
			return (
				'<div class="mto-notify-message mto-alert mto-success-msg"><span>' +
				message +
				'</span></div>'
			);
		},
	};
	var masteriyo_helper = {
		confirm: function () {
			var res = window.prompt('Type CONFIRM to proceed');

			if (null === res) return false;
			if ('CONFIRM' !== res) {
				alert('Try again');
				return false;
			}
			return true;
		},
		removeNotices: function ($element) {
			$element.find('.mto-notify-message').remove();
		},
	};
	var masteriyo = {
		$create_revew_form: $('.mto-submit-review-form'),

		init: function () {
			$(document).ready(function () {
				masteriyo.init_faqs_accordions_handler();
				masteriyo.init_curriculum_accordions_handler();
				masteriyo.init_create_reviews_handler();
				masteriyo.init_edit_reviews_handler();
				masteriyo.init_delete_reviews_handler();
			});
		},
		init_create_reviews_handler: function () {
			var isCreating = false;

			masteriyo.$create_revew_form.on('submit', function (e) {
				e.preventDefault();

				var $form = masteriyo.$create_revew_form;
				var $submit_button = $form.find('button[type="submit"]');
				var data = {
					title: $form.find('input[name="title"]').val(),
					karma: $form.find('input[name="rating"]').val(),
					content: $form.find('input[name="content"]').val(),
				};

				if (isCreating || 'yes' === $form.data('edit-mode')) return;

				isCreating = true;
				$submit_button.text('Submitting...');
				masteriyo_helper.removeNotices($form);
				masteriyo_api.createCourseReview(data, {
					onSuccess: function (res) {
						var message = 'Review submitted successfully';
						$form.append(masteriyo_utils.getSuccessNotice(message));
						window.location.reload();
					},
					onError: function (xhr, status, error) {
						var message = error;

						if (xhr.responseJSON && xhr.responseJSON.message) {
							message = xhr.responseJSON.message;
						}

						$form.append(masteriyo_utils.getErrorNotice(message));
						$submit_button.text('Submit');
					},
					onComplete: function () {
						isCreating = false;
					},
				});
			});
		},
		init_edit_reviews_handler: function () {
			$(document.body).on('click', '.mto-edit-course-review', function (e) {
				e.preventDefault();

				var $form = masteriyo.$create_revew_form;
				var $review = $(this).closest('.mto-course-review');
				var review_id = $review.data('id');
				var $submit_button = $form.find('button[type="submit"]');
				var title = $review.find('.title').data('value');
				var rating = $review.find('.rating').data('value');
				var content = $review.find('.content').data('value');

				$form.data('edit-mode', 'yes');
				$form.data('review-id', review_id);
				$form.find('input[name="title"]').val(title);
				$form.find('input[name="rating"]').val(rating);
				$form.find('input[name="content"]').val(content);
				$submit_button.text('Update');

				$('html, body').animate(
					{
						scrollTop: $form.offset().top,
					},
					500
				);
			});

			var isSubmitting = false;

			masteriyo.$create_revew_form.on('submit', function (e) {
				e.preventDefault();

				var $form = masteriyo.$create_revew_form;
				var review_id = $form.data('review-id');
				var $submit_button = $form.find('button[type="submit"]');
				var data = {
					title: $form.find('input[name="title"]').val(),
					karma: $form.find('input[name="rating"]').val(),
					content: $form.find('input[name="content"]').val(),
				};

				if (isSubmitting || 'yes' !== $form.data('edit-mode')) return;

				isSubmitting = true;
				$submit_button.text('Submitting...');
				masteriyo_helper.removeNotices($form);
				masteriyo_api.updateCourseReview(review_id, data, {
					onSuccess: function (res) {
						var message = 'Review updated successfully';
						$form.append(masteriyo_utils.getSuccessNotice(message));
						$submit_button.text('Update');
						window.location.reload();
					},
					onError: function (xhr, status, error) {
						var message = error;

						if (xhr.responseJSON && xhr.responseJSON.message) {
							message = xhr.responseJSON.message;
						}

						$form.append(masteriyo_utils.getErrorNotice(message));
						$submit_button.text('Update');
					},
					onComplete: function () {
						isSubmitting = false;
					},
				});
			});
		},
		init_delete_reviews_handler: function () {
			var isDeletingFlags = {};

			$(document.body).on('click', '.mto-delete-course-review', function (e) {
				e.preventDefault();

				var $review = $(this).closest('.mto-course-review');
				var $delete_button = $(this);
				var review_id = $review.data('id');

				if (isDeletingFlags[review_id] || !masteriyo_helper.confirm()) return;

				isDeletingFlags[review_id] = true;
				$delete_button.find('.text').text('Deleting...');
				masteriyo_api.deleteCourseReview(review_id, {
					onSuccess: function (res) {
						var message = 'Deleted review successfully';
						$review.after(masteriyo_utils.getSuccessNotice(message));
						$review.remove();
					},
					onError: function (xhr, status, error) {
						var message = error;

						if (xhr.responseJSON && xhr.responseJSON.message) {
							message = xhr.responseJSON.message;
						}

						$review.append(masteriyo_utils.getErrorNotice(message));
						$delete_button.find('.text').text('Delete');
					},
					onComplete: function () {
						isDeletingFlags[review_id] = false;
					},
				});
			});
		},
		init_faqs_accordions_handler: function () {
			// FAQs accordions handler.
			$(document.body).on('click', '.mto-faq--item-header', function () {
				$(this).siblings('.mto-faq--item-body').first().slideToggle('swing');
			});
		},
		init_curriculum_accordions_handler: function () {
			// Curriculam Tab
			$(document.body).on('click', '.mto-cheader', function () {
				$(this).parent('.mto-stab--citems').toggleClass('active');
				if (
					$('.mto-stab--citems').length === $('.mto-stab--citems.active').length
				) {
					expandAllSections();
				}
				if (
					$('.mto-stab--citems').length ===
					$('.mto-stab--citems').not('.active').length
				) {
					collapseAllSections();
				}
			});
			var isCollapsedAll = true;
			$(document.body).on('click', '.mto-expand-collape-all', function () {
				if (isCollapsedAll) {
					expandAllSections();
				} else {
					collapseAllSections();
				}
			});

			// Expand all
			function expandAllSections() {
				$('.mto-stab--citems').addClass('active');
				$('.mto-expand-collape-all').text('Collapse All');
				isCollapsedAll = false;
			}

			// Collapse all
			function collapseAllSections() {
				$('.mto-stab--citems').removeClass('active');
				$('.mto-expand-collape-all').text('Expand All');
				isCollapsedAll = true;
			}

			var $content_ref = $('.mto-scourse--main').get(0);

			if ($content_ref) {
				$(window).scroll(function () {
					var stickyHeight = $('.mto-sticky').height();
					var scroll_position = $(window).scrollTop();
					var content_y = $content_ref.offsetTop;
					var content_y2 = content_y + $content_ref.offsetHeight - stickyHeight;
					var isSticky = false;

					if (scroll_position > content_y && scroll_position < content_y2)
						isSticky = true;
					if (isSticky) {
						$('.mto-sticky').css({ position: 'fixed', top: '20px' });
					} else {
						$('.mto-sticky').css({ position: 'relative' });
					}
				});
			}
		},
	};

	masteriyo.init();
})(jQuery, window.masteriyo_data);

function masteriyo_select_single_course_page_tab(tabIndex) {
	var countTab;
	//Hide All Tabs
	for (countTab = 1; countTab <= 4; countTab++) {
		var tab_content = document.getElementById('tab' + countTab + 'Content');
		var tab_handle = document.getElementById('tab' + countTab);

		if (tab_content) {
			tab_content.style.display = 'none';
		}
		if (tab_handle) {
			tab_handle.classList.remove('active-tab');
		}
	}

	//Show the Selected Tab
	document.getElementById('tab' + tabIndex + 'Content').style.display = 'block';
	document
		.getElementById('tab' + tabIndex + 'Content')
		.classList.remove('mto-hidden');
	document.getElementById('tab' + tabIndex).classList.add('active-tab');
}
