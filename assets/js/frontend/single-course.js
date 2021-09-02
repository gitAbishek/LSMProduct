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
			var res = window.prompt(mto_data.labels.type_confirm);

			if (null === res) return false;
			if ('CONFIRM' !== res) {
				alert(mto_data.labels.try_again);
				return false;
			}
			return true;
		},
		removeNotices: function ($element) {
			$element.find('.mto-notify-message').remove();
		},
		get_rating_markup: function (rating) {
			rating = rating === '' ? 0 : rating;
			rating = parseFloat(rating);
			html = '';
			max_rating = mto_data.max_course_rating;
			rating = rating > max_rating ? max_rating : rating;
			rating = rating < 0 ? 0 : rating;
			stars = mto_data.rating_indicator_markup;

			rating_floor = Math.floor(rating);
			for (i = 1; i <= rating_floor; i++) {
				html += stars.full_star;
			}
			if (rating_floor < rating) {
				html += stars.half_star;
			}

			rating_ceil = Math.ceil(rating);
			for (i = rating_ceil; i < max_rating; i++) {
				html += stars.empty_star;
			}
			return html;
		},
	};
	var masteriyo_dialogs = {
		confirm_delete_course_review: function (options = {}) {
			$(document.body).append(
				$('.mto-confirm-delete-course-review-modal-content').html()
			);
			$('.mto-modal-confirm-delete-course-review .mto-cancel').on(
				'click',
				function () {
					$(this).closest('.masteriyo--modal').remove();
				}
			);
			$('.mto-modal-confirm-delete-course-review .mto-delete').on(
				'click',
				function () {
					var $modal = $(this).closest('.masteriyo--modal');

					$(this).text(mto_data.labels.deleting);

					if (typeof options.onConfirm === 'function') {
						options.onConfirm(function () {
							$modal.remove();
						});
					}
				}
			);
		},
	};
	var masteriyo = {
		$create_revew_form: $('.mto-submit-review-form'),
		create_review_form_class: '.mto-submit-review-form',

		init: function () {
			$(document).ready(function () {
				masteriyo.init_rating_widget();
				masteriyo.init_menu_toggler();
				masteriyo.init_faqs_accordions_handler();
				masteriyo.init_curriculum_accordions_handler();
				masteriyo.init_create_reviews_handler();
				masteriyo.init_edit_reviews_handler();
				masteriyo.init_delete_reviews_handler();
				masteriyo.init_reply_btn_handler();
			});
		},
		init_menu_toggler: function () {
			$(document.body).on('click', '.menu-toggler', function () {
				if ($(this).siblings('.menu').height() == 0) {
					$(this).siblings('.menu').height('auto');
					$(this).siblings('.menu').css('max-height', '999px');
					return;
				}
				$(this).siblings('.menu').height(0);
			});
		},
		init_rating_widget: function () {
			$(masteriyo.create_review_form_class).on(
				'click',
				'.mto-rating-input-icon',
				function () {
					var rating = $(this).index() + 1;

					masteriyo.$create_revew_form.find('input[name="rating"]').val(rating);
					$(this)
						.closest('.mto-rstar')
						.html(masteriyo_helper.get_rating_markup(rating));
				}
			);
		},
		init_create_reviews_handler: function () {
			var isCreating = false;

			masteriyo.$create_revew_form.on('submit', function (e) {
				e.preventDefault();

				var $form = masteriyo.$create_revew_form;
				var $submit_button = $form.find('button[type="submit"]');
				var data = {
					title: $form.find('input[name="title"]').val(),
					rating: $form.find('input[name="rating"]').val(),
					content: $form.find('[name="content"]').val(),
					parent: $form.find('[name="parent"]').val(),
					course_id: $form.find('[name="course_id"]').val(),
				};

				if (isCreating || 'yes' === $form.data('edit-mode')) return;

				isCreating = true;
				$submit_button.text(mto_data.labels.submitting);
				masteriyo_helper.removeNotices($form);
				masteriyo_api.createCourseReview(data, {
					onSuccess: function () {
						$form.append(
							masteriyo_utils.getSuccessNotice(mto_data.labels.submit_success)
						);
						window.location.reload();
					},
					onError: function (xhr, status, error) {
						var message = error;

						if (xhr.responseJSON && xhr.responseJSON.message) {
							message = xhr.responseJSON.message;
						}

						$form.append(masteriyo_utils.getErrorNotice(message));
						$submit_button.text(mto_data.labels.submit);
					},
					onComplete: function () {
						isCreating = false;
					},
				});
			});
		},
		init_reply_btn_handler: function () {
			$(document.body).on('click', '.mto-reply-course-review', function (e) {
				e.preventDefault();

				var $form = masteriyo.$create_revew_form;
				var $review = $(this).closest('.mto-course-review');
				var review_id = $review.data('id');
				var $submit_button = $form.find('button[type="submit"]');
				var title = $review.find('.title').data('value');

				$form.find('input[name="title"]').val('');
				$form.find('input[name="rating"]').val(0);
				$form.find('.mto-rstar').html(masteriyo_helper.get_rating_markup(0));
				$form.find('[name="content"]').val('');
				$form.find('[name="parent"]').val(review_id);
				$submit_button.text(mto_data.labels.submit);

				$('.mto-form-title').text(mto_data.labels.reply_to + ': ' + title);
				$form.find('.mto-title, .mto-rating').hide();
				$form.find('[name="content"]').focus();
				$('html, body').animate(
					{
						scrollTop: $form.offset().top,
					},
					500
				);
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
				var parent = $review.find('[name="parent"]').val();

				$form.data('edit-mode', 'yes');
				$form.data('review-id', review_id);
				$form.find('input[name="title"]').val(title);
				$form.find('input[name="rating"]').val(rating);
				$form
					.find('.mto-rstar')
					.html(masteriyo_helper.get_rating_markup(rating));
				$form.find('[name="content"]').val(content);
				$form.find('[name="parent"]').val(parent);
				$submit_button.text(mto_data.labels.update);

				if ($review.is('.is-course-review-reply')) {
					$('.mto-form-title').text(mto_data.labels.edit_reply);
					$form.find('.mto-title, .mto-rating').hide();
					$form.find('[name="content"]').focus();
					$('html, body').animate(
						{
							scrollTop: $form.offset().top,
						},
						500
					);
				} else {
					$('.mto-form-title').text(mto_data.labels.edit_review + ': ' + title);
					$form.find('.mto-title, .mto-rating').show();
					$form.find('input[name="title"]').focus();
					$('html, body').animate(
						{
							scrollTop: $form.offset().top,
						},
						500
					);
				}
			});

			var isSubmitting = false;

			masteriyo.$create_revew_form.on('submit', function (e) {
				e.preventDefault();

				var $form = masteriyo.$create_revew_form;
				var review_id = $form.data('review-id');
				var $submit_button = $form.find('button[type="submit"]');
				var data = {
					title: $form.find('input[name="title"]').val(),
					rating: $form.find('input[name="rating"]').val(),
					content: $form.find('[name="content"]').val(),
					parent: $form.find('[name="parent"]').val(),
					course_id: $form.find('[name="course_id"]').val(),
				};

				if (isSubmitting || 'yes' !== $form.data('edit-mode')) return;

				isSubmitting = true;
				$submit_button.text(mto_data.labels.submitting);
				masteriyo_helper.removeNotices($form);
				masteriyo_api.updateCourseReview(review_id, data, {
					onSuccess: function () {
						$form.append(
							masteriyo_utils.getSuccessNotice(mto_data.labels.update_success)
						);
						$submit_button.text(mto_data.labels.update);
						window.location.reload();
					},
					onError: function (xhr, status, error) {
						var message = error;

						if (xhr.responseJSON && xhr.responseJSON.message) {
							message = xhr.responseJSON.message;
						}

						$form.append(masteriyo_utils.getErrorNotice(message));
						$submit_button.text(mto_data.labels.update);
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

				if (isDeletingFlags[review_id]) return;

				masteriyo_dialogs.confirm_delete_course_review({
					onConfirm: function (closeModal) {
						isDeletingFlags[review_id] = true;

						masteriyo_api.deleteCourseReview(review_id, {
							onSuccess: function () {
								$review.after(
									masteriyo_utils.getSuccessNotice(
										mto_data.labels.delete_success
									)
								);
								$review.remove();
							},
							onError: function (xhr, status, error) {
								var message = error;

								if (xhr.responseJSON && xhr.responseJSON.message) {
									message = xhr.responseJSON.message;
								}

								$review.append(masteriyo_utils.getErrorNotice(message));
								$delete_button.find('.text').text(mto_data.labels.delete);
							},
							onComplete: function () {
								isDeletingFlags[review_id] = false;
								closeModal();
							},
						});
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
						$('.mto-sticky').css({ position: 'sticky', top: '0px' });
					} else {
						$('.mto-sticky').css({ position: 'relative' });
					}
				});
			}
		},
	};

	masteriyo.init();
})(jQuery, window.masteriyo_data);

function masteriyo_select_single_course_page_tab(e, tabContentSelector) {
	jQuery('.mto-tab').removeClass('active-tab');
	jQuery('.tab-content').addClass('masteriyo-hidden');

	jQuery(e.target).addClass('active-tab');
	jQuery(tabContentSelector).removeClass('masteriyo-hidden');
}
