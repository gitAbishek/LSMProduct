document.addEventListener('DOMContentLoaded', () => {
	/* home slider js */
	(function setupHomeSlider() {
		'use strict';

		var sliderSwiper = new Swiper('.home-slider .swiper-container', {
			effect: 'fade',
			loop: true,
			autoplay: {
				delay: 5000,
			},
			slidesPerView: 'auto',
			a11y: true,
			keyboardControl: true,
			grabCursor: true,

			// navigation arrows
			navigation: {
				nextEl: '#slider-prev',
				prevEl: '#slider-next',
			},
		});
	})();

	// /* Product carousel js */
	(function setUpProductCarousel() {
		'use strict';

		const bookSwiper = new Swiper('.product-section .swiper-container', {
			loop: true,
			slidesPerView: 'auto',
			spaceBetween: 30,
			a11y: true,
			keyboardControl: true,
			grabCursor: true,
			autoplay: {
				delay: 3000,
			},
			breakpoints: {
				640: {
					slidesPerView: 1,
				},
				768: {
					slidesPerView: 2,
				},
				1024: {
					slidesPerView: 3,
				},
				1200: {
					slidesPerView: 4,
				},
			},

			// navigation arrows
			navigation: {
				nextEl: '#sw-prev',
				prevEl: '#sw-next',
			},
		});
	})();
	// /* author carousel js */
	(function setUpAuthorCarousel() {
		'use strict';

		var mySwiper = new Swiper('.author-section .swiper-container', {
			loop: true,
			autoplay: {
				delay: 3000,
			},
			slidesPerView: 'auto',
			spaceBetween: 30,
			a11y: true,
			keyboardControl: true,
			grabCursor: true,
			breakpoints: {
				640: {
					slidesPerView: 1,
				},
				768: {
					slidesPerView: 2,
				},
				1024: {
					slidesPerView: 3,
				},
				1200: {
					slidesPerView: 4,
				},
			},

			// navigation arrows
			navigation: {
				prevEl: '#js-prev1',
				nextEl: '#js-next1',
			},
		});
	})(); /* IIFE end */

	/* tab js */
	(function setUpTabCarousel() {
		var tabs = document.querySelectorAll('[data-tab-target]');
		var tabContents = document.querySelectorAll('[data-tab-content]');

		tabs.forEach((tab) => {
			tab.addEventListener('click', () => {
				var target = document.querySelector(tab.dataset.tabTarget);
				tabContents.forEach((tabContent) => {
					tabContent.classList.remove('active');
				});
				tabs.forEach((tab) => {
					tab.classList.remove('active');
				});
				tab.classList.add('active');
				target.classList.add('active');
			});
		});
	})();
	(function setUpSubMenu() {
		let x = document.body.querySelectorAll('.mobile-menu .sub-menu');
		let index = 0;
		for (index = 0; index < x.length; index++) {
			var navArrow = document.createElement('span');
			navArrow.className = 'sub-toggle';
			navArrow.innerHTML = '<i class="fas fa-angle-down"></i>';
			x[index].parentNode.insertBefore(navArrow, x[index].nextSibling);
		}
	})();
	/**
	 * Setup mobile menu
	 */
	(function setupMobileMenu() {
		var toggleButton = document.querySelector('.mobile-menu-toggle'),
			mobileMenu = document.querySelector('.mobile-navigation'),
			main = document.getElementById('page');

		function closeMobileMenu() {
			if (toggleButton) {
				toggleButton.classList.remove('mobile-menu-toggle--opened');
			}

			if (mobileMenu) {
				mobileMenu.classList.remove('mobile-navigation--opened');
			}
		}

		function openMobileMenu() {
			if (toggleButton) {
				toggleButton.classList.add('mobile-menu-toggle--opened');
			}

			if (mobileMenu) {
				mobileMenu.classList.add('mobile-navigation--opened');
			}
		}

		(function closeOnBody() {
			if (main) {
				main.addEventListener('touchstart', function (event) {
					// Return if user is clicking on mobile menu toggle.
					var target = event.target;
					if (
						toggleButton &&
						(target == toggleButton || toggleButton.contains(target))
					) {
						return;
					}

					if (
						mobileMenu &&
						(target == mobileMenu || mobileMenu.contains(target))
					) {
						return;
					}
					closeMobileMenu();
				});
			}
		})();

		// Toggle menu and button class.
		if (toggleButton && mobileMenu) {
			toggleButton.addEventListener('click', function () {
				this.classList.toggle('mobile-menu-toggle--opened');
				mobileMenu.classList.toggle('mobile-navigation--opened');
			});
		}

		mobileMenuLinks = mobileMenu.querySelectorAll(
			'.menu-item-has-children > a'
		);

		if (mobileMenuLinks) {
			mobileMenuLinks.forEach(function (mobileMenuLink) {
				mobileMenuLink.addEventListener('click', function (e) {
					if (this.parentElement.classList.contains('focus')) {
						this.parentElement.classList.remove('focus');
					} else {
						e.preventDefault();
						this.parentElement.classList.add('focus');
					}

					console.log('clicked');
				});
			});
		}
	})();
	(function setupPop() {
		var openEls = document.querySelectorAll('[data-open]');
		var closeEls = document.querySelectorAll('[data-close]');
		var isVisible = 'is-visible';

		for (var el of openEls) {
			el.addEventListener('click', function () {
				var modalId = this.dataset.open;
				document.getElementById(modalId).classList.add(isVisible);
			});
		}

		for (var el of closeEls) {
			el.addEventListener('click', function () {
				this.parentElement.parentElement.parentElement.classList.remove(
					isVisible
				);
			});
		}

		document.addEventListener('click', (e) => {
			if (
				e.target ===
				document.querySelector(
					'.search-wrapper.is-visible,.enquiry-popout.is-visible'
				)
			) {
				document
					.querySelector(
						'.search-wrapper.is-visible,.enquiry-popout.is-visible'
					)
					.classList.remove(isVisible);
			}
		});

		document.addEventListener('keyup', (e) => {
			// if we press the ESC
			if (
				e.key === 'Escape' &&
				document.querySelector(
					'.search-wrapper.is-visible,.enquiry-popout.is-visible'
				)
			) {
				document
					.querySelector(
						'.search-wrapper.is-visible,.enquiry-popout.is-visible'
					)
					.classList.remove(isVisible);
			}
		});
	})();
});
