/**
 * Highlight sidebar sub menu during page load and when the menu is clicked.
 */
(function ($) {
	var $topLevelMenu = $('#toplevel_page_masteriyo');

	function makeCurrentSubmenuActive() {
		if (!$topLevelMenu.length) {
			return;
		}

		$topLevelMenu.find('li').removeClass('current');

		$topLevelMenu
			.find('a[href$="' + window.location.hash + '"]')
			.parent('li')
			.addClass('current');
	}

	makeCurrentSubmenuActive();

	// Handle change of URL.
	window.addEventListener('popstate', makeCurrentSubmenuActive);
})(jQuery);
