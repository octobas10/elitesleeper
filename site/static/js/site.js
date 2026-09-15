function initStickyHeader() {
	var header = document.getElementById('masthead');
	if (!header) return;

	function onScroll() {
		var scrolled = window.scrollY > 8;
		header.classList.toggle('hlm-header-scrolled', scrolled);
		if (header.classList.contains('hlm-site-header--transparent')) {
			header.style.position = scrolled ? 'sticky' : 'absolute';
		}
	}

	onScroll();
	window.addEventListener('scroll', onScroll, { passive: true });
}

function reveal(el) {
	el.classList.add('is-visible', 'visible');
}

function alreadySeen(el) {
	var rect = el.getBoundingClientRect();
	return rect.top < window.innerHeight * 0.92;
}

function initReveal() {
	var targets = document.querySelectorAll('.hlm-animate, .hlm-section-reveal, .hlm-stagger-grid');
	if (!targets.length) return;

	if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		targets.forEach(reveal);
		return;
	}

	if (!('IntersectionObserver' in window)) {
		targets.forEach(reveal);
		return;
	}

	var observer = new IntersectionObserver(
		function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) return;
				reveal(entry.target);
				observer.unobserve(entry.target);
			});
		},
		{ rootMargin: '0px 0px -8% 0px', threshold: 0.12 },
	);

	targets.forEach(function (el) {
		if (alreadySeen(el)) {
			reveal(el);
			return;
		}
		observer.observe(el);
	});

	document.querySelectorAll('.hlm-hero .hlm-animate').forEach(reveal);
}

function initSite() {
	initStickyHeader();
	initReveal();
}

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', initSite);
} else {
	initSite();
}
