/**
 * HLM theme mode: light / dark / system.
 */
(function () {
	'use strict';

	var STORAGE_KEY = 'hlm-theme-preference';
	var MODES = ['system', 'light', 'dark'];
	var media = window.matchMedia('(prefers-color-scheme: dark)');

	function getPreference() {
		var stored = localStorage.getItem(STORAGE_KEY);
		return MODES.indexOf(stored) !== -1 ? stored : 'system';
	}

	function resolveTheme(preference) {
		if (preference === 'dark') {
			return 'dark';
		}
		if (preference === 'light') {
			return 'light';
		}
		return media.matches ? 'dark' : 'light';
	}

	function applyTheme(preference) {
		var resolved = resolveTheme(preference);
		var root = document.documentElement;

		root.setAttribute('data-hlm-theme-pref', preference);
		root.setAttribute('data-theme', resolved);
		root.style.colorScheme = resolved;

		document.querySelectorAll('[data-hlm-theme-toggle]').forEach(function (button) {
			var label = preference.charAt(0).toUpperCase() + preference.slice(1);
			button.setAttribute('aria-label', 'Theme: ' + label + '. Click to change.');
			button.setAttribute('data-current-mode', preference);
			button.setAttribute('title', 'Theme: ' + label);
		});
	}

	function cyclePreference() {
		var current = getPreference();
		var next = MODES[(MODES.indexOf(current) + 1) % MODES.length];
		localStorage.setItem(STORAGE_KEY, next);
		applyTheme(next);
	}

	function bindToggle() {
		document.querySelectorAll('[data-hlm-theme-toggle]').forEach(function (button) {
			if (button.dataset.hlmThemeBound) {
				return;
			}
			button.dataset.hlmThemeBound = '1';
			button.addEventListener('click', cyclePreference);
		});
	}

	function onSystemChange() {
		if (getPreference() === 'system') {
			applyTheme('system');
		}
	}

	function initHeaderScroll() {
		var header = document.getElementById('masthead');
		if (!header) {
			return;
		}

		var onScroll = function () {
			header.classList.toggle('hlm-header-scrolled', window.scrollY > 8);
		};

		onScroll();
		window.addEventListener('scroll', onScroll, { passive: true });
	}

	function initMobileNav() {
		var header = document.getElementById('masthead');
		if (!header) {
			return;
		}

		var toggle = header.querySelector('[data-hlm-nav-toggle]');
		var drawer = header.querySelector('[data-hlm-nav-drawer]');
		if (!toggle || !drawer) {
			return;
		}

		var closeButtons = drawer.querySelectorAll('[data-hlm-nav-close]');

		function setOpen(open) {
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
			toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
			drawer.hidden = !open;
			document.body.classList.toggle('hlm-nav-open', open);
		}

		toggle.addEventListener('click', function () {
			setOpen(toggle.getAttribute('aria-expanded') !== 'true');
		});

		closeButtons.forEach(function (button) {
			button.addEventListener('click', function () {
				setOpen(false);
			});
		});

		drawer.querySelectorAll('a').forEach(function (link) {
			link.addEventListener('click', function () {
				setOpen(false);
			});
		});

		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
				setOpen(false);
				toggle.focus();
			}
		});
	}

	applyTheme(getPreference());
	bindToggle();

	if (typeof media.addEventListener === 'function') {
		media.addEventListener('change', onSystemChange);
	} else if (typeof media.addListener === 'function') {
		media.addListener(onSystemChange);
	}

	document.addEventListener('DOMContentLoaded', function () {
		bindToggle();
		initHeaderScroll();
		initMobileNav();
	});

	initHeaderScroll();
	initMobileNav();
})();
