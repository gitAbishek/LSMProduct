function selectTab(tabIndex) {
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
	document.getElementById('tab' + tabIndex).classList.add('active-tab');
}

(function ($) {
	// FAQ Tab
	$(document.body).on('click', '.mto-faq--item-header', function () {
		$(this).siblings('.mto-faq--item-body').first().slideToggle('swing');
	});

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

	$(document).ready(function () {
		var content_ref = $('.mto-scourse--main').get(0);
		if (content_ref) {
			$(window).scroll(function () {
				var stickyHeight = $('.mto-sticky').height();
				var scroll_position = $(window).scrollTop();
				var content_y = content_ref.offsetTop;
				var content_y2 = content_y + content_ref.offsetHeight - stickyHeight;
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
	});
})(jQuery);
