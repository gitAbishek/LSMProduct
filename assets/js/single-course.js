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

	// Sticky
	var stickyTop = $('.mto-sticky').offset().top;

	$(window).scroll(function () {
		var windowTop = $(window).scrollTop();

		if (stickyTop < windowTop) {
			$('.mto-sticky').css({ position: 'fixed', top: '20px' });
		} else {
			$('.mto-sticky').css('position', 'relative');
		}
	});
})(jQuery);
