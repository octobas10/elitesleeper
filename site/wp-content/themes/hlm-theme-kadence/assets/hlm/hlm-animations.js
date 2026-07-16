/**
 * HLM Animations — scroll reveals, stagger grids, counters, hero parallax.
 */
(function () {
	'use strict';

	var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var staggerSelector =
		'.hlm-unis-grid, .hlm-programs-grid, .hlm-process-grid, .hlm-testimonials-grid, .hlm-blog-grid, .hlm-affiliation-benefits-grid, .hlm-single-service-related__grid, .hlm-grid-2, .hlm-grid-3, .hlm-grid-4, .hlm-why-us__values-grid';

	function markVisible(el) {
		el.classList.add('is-visible', 'visible');
	}

	function initScrollReveal() {
		var singles = document.querySelectorAll('.hlm-animate:not(.hlm-stagger-item)');

		if (prefersReducedMotion) {
			singles.forEach(markVisible);
			return;
		}

		var observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						markVisible(entry.target);
						observer.unobserve(entry.target);
					}
				});
			},
			{ threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
		);

		singles.forEach(function (el) {
			observer.observe(el);
		});
	}

	function initSectionReveal() {
		var sections = document.querySelectorAll(
			'.hlm-section:not(.hlm-hero):not(.hlm-page-hero), .hlm-stats, .hlm-cta'
		);

		sections.forEach(function (section) {
			section.classList.add('hlm-section-reveal');
		});

		if (prefersReducedMotion) {
			sections.forEach(markVisible);
			return;
		}

		var observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						markVisible(entry.target);
						observer.unobserve(entry.target);
					}
				});
			},
			{ threshold: 0.08, rootMargin: '0px 0px -60px 0px' }
		);

		sections.forEach(function (section) {
			observer.observe(section);
		});
	}

	function initStaggerGrids() {
		var grids = document.querySelectorAll(staggerSelector);

		grids.forEach(function (grid) {
			grid.classList.add('hlm-stagger-grid');

			var items = grid.children;
			Array.prototype.forEach.call(items, function (item, index) {
				item.classList.add('hlm-stagger-item');
				item.style.setProperty('--hlm-stagger-i', String(index));
			});
		});

		if (prefersReducedMotion) {
			grids.forEach(markVisible);
			return;
		}

		var observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						markVisible(entry.target);
						observer.unobserve(entry.target);
					}
				});
			},
			{ threshold: 0.15, rootMargin: '0px 0px -40px 0px' }
		);

		grids.forEach(function (grid) {
			observer.observe(grid);
		});
	}

	function animateCounter(el) {
		var target = parseInt(el.getAttribute('data-count'), 10);
		var suffix = el.getAttribute('data-suffix') || '';
		var prefix = el.getAttribute('data-prefix') || '';
		var duration = 2000;
		var start = 0;
		var startTime = null;

		if (isNaN(target)) {
			el.textContent = prefix + el.textContent + suffix;
			return;
		}

		function step(timestamp) {
			if (!startTime) {
				startTime = timestamp;
			}
			var progress = Math.min((timestamp - startTime) / duration, 1);
			var eased = 1 - Math.pow(1 - progress, 3);
			var current = Math.floor(start + (target - start) * eased);
			el.textContent = prefix + current.toLocaleString() + suffix;
			if (progress < 1) {
				requestAnimationFrame(step);
			} else {
				el.classList.add('is-counted');
			}
		}

		requestAnimationFrame(step);
	}

	function initCounters() {
		var counters = document.querySelectorAll('[data-count]');

		if (!counters.length) {
			return;
		}

		if (prefersReducedMotion) {
			counters.forEach(function (el) {
				var target = el.getAttribute('data-count');
				var suffix = el.getAttribute('data-suffix') || '';
				var prefix = el.getAttribute('data-prefix') || '';
				el.textContent = prefix + parseInt(target, 10).toLocaleString() + suffix;
				el.classList.add('is-counted');
			});
			return;
		}

		var observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						animateCounter(entry.target);
						observer.unobserve(entry.target);
					}
				});
			},
			{ threshold: 0.5 }
		);

		counters.forEach(function (el) {
			observer.observe(el);
		});
	}

	function initHeroParallax() {
		if (prefersReducedMotion) {
			return;
		}

		var layers = document.querySelectorAll(
			'.hlm-hero-media__placeholder, .hlm-page-hero__media-placeholder'
		);

		if (!layers.length) {
			return;
		}

		var ticking = false;

		function update() {
			var scrollY = window.scrollY;
			layers.forEach(function (layer) {
				var section = layer.closest('.hlm-hero, .hlm-page-hero');
				if (!section) {
					return;
				}
				var rect = section.getBoundingClientRect();
				if (rect.bottom < 0 || rect.top > window.innerHeight) {
					return;
				}
				var offset = Math.min(scrollY * 0.08, 48);
				layer.style.transform = 'translate3d(0, ' + offset + 'px, 0) scale(1.04)';
			});
			ticking = false;
		}

		window.addEventListener(
			'scroll',
			function () {
				if (!ticking) {
					ticking = true;
					requestAnimationFrame(update);
				}
			},
			{ passive: true }
		);

		update();
	}

	function initAll() {
		initStaggerGrids();
		initScrollReveal();
		initSectionReveal();
		initCounters();
		initHeroParallax();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initAll);
	} else {
		initAll();
	}
})();
