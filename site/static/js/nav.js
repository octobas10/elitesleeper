function openDrawer(toggle, drawer) {
	drawer.hidden = false;
	toggle.setAttribute('aria-expanded', 'true');
	toggle.setAttribute('aria-label', 'Close menu');
	document.body.classList.add('hlm-nav-open');
	document.body.style.overflow = 'hidden';
}

function closeDrawer(toggle, drawer) {
	drawer.hidden = true;
	toggle.setAttribute('aria-expanded', 'false');
	toggle.setAttribute('aria-label', 'Open menu');
	document.body.classList.remove('hlm-nav-open');
	document.body.style.overflow = '';
}

function initNav() {
	var toggle = document.querySelector('[data-hlm-nav-toggle]');
	var drawer = document.querySelector('[data-hlm-nav-drawer]');
	if (!toggle || !drawer) return;

	toggle.addEventListener('click', function () {
		if (drawer.hidden) {
			openDrawer(toggle, drawer);
		} else {
			closeDrawer(toggle, drawer);
		}
	});

	document.querySelectorAll('[data-hlm-nav-close]').forEach(function (el) {
		el.addEventListener('click', function () {
			closeDrawer(toggle, drawer);
		});
	});

	document.addEventListener('keydown', function (event) {
		if (event.key === 'Escape' && !drawer.hidden) {
			closeDrawer(toggle, drawer);
		}
	});
}

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', initNav);
} else {
	initNav();
}
