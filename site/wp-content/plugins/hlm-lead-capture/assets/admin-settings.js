/**
 * Admin settings tabs / accordion.
 */
(function () {
	'use strict';

	var root = document.querySelector('.hlm-lc-settings');
	if (!root) {
		return;
	}

	var tabs = Array.from(root.querySelectorAll('[role="tab"]'));
	var panels = Array.from(root.querySelectorAll('[role="tabpanel"]'));
	var validIds = tabs.map(function (t) { return t.dataset.tab; });

	function activate(tabId, updateHash) {
		if (validIds.indexOf(tabId) === -1) {
			tabId = validIds[0];
		}

		tabs.forEach(function (tab) {
			var isActive = tab.dataset.tab === tabId;
			tab.classList.toggle('is-active', isActive);
			tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
			tab.tabIndex = isActive ? 0 : -1;
		});

		panels.forEach(function (panel) {
			var isActive = panel.dataset.tab === tabId;
			panel.classList.toggle('is-active', isActive);
			panel.hidden = !isActive;
		});

		if (updateHash !== false && window.history && window.history.replaceState) {
			var url = new URL(window.location.href);
			url.hash = 'tab-' + tabId;
			window.history.replaceState(null, '', url.toString());
		}
	}

	function tabFromHash() {
		var params = new URL(window.location.href).searchParams;
		if (params.get('tab') && validIds.indexOf(params.get('tab')) !== -1) {
			return params.get('tab');
		}

		var hash = window.location.hash.replace('#', '');
		if (hash.indexOf('tab-') === 0) {
			return hash.slice(4);
		}
		return validIds[0];
	}

	tabs.forEach(function (tab, index) {
		tab.addEventListener('click', function () {
			activate(tab.dataset.tab);
		});

		tab.addEventListener('keydown', function (event) {
			var next = index;

			if (event.key === 'ArrowRight') {
				next = (index + 1) % tabs.length;
			} else if (event.key === 'ArrowLeft') {
				next = (index - 1 + tabs.length) % tabs.length;
			} else if (event.key === 'Home') {
				next = 0;
			} else if (event.key === 'End') {
				next = tabs.length - 1;
			} else {
				return;
			}

			event.preventDefault();
			tabs[next].focus();
			activate(tabs[next].dataset.tab);
		});
	});

	window.addEventListener('hashchange', function () {
		activate(tabFromHash(), false);
	});

	activate(tabFromHash(), false);
})();
