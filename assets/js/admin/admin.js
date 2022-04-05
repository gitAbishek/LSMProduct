/**
 * Highlight sidebar sub menu during page load and when the menu is clicked.
 */
(function ($) {
	var $topLevelMenu = $('#toplevel_page_masteriyo');
	var hash = window.location.hash;

	if (!$topLevelMenu) {
		return;
	}

	var $activeLi = $topLevelMenu.find('a[href$="' + hash + '"]').parent('li');

	$activeLi.addClass('current');

	// Handle change of menus.
	$topLevelMenu.on('click', '.wp-submenu li', function (e) {
		$activeLi.removeClass('current');

		$activeLi = $(this);

		$(this).addClass('current');
	});
})(jQuery);
