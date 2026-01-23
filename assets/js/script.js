/**
 * Bunbukan Theme JavaScript
 */

(function() {
	'use strict';

	// Mobile menu toggle
	function initMobileMenu() {
		const menuToggle = document.getElementById('nav-toggle');
		const navigation = document.getElementById('site-navigation');
		
		if (menuToggle && navigation) {
			menuToggle.addEventListener('click', function() {
				const isExpanded = this.getAttribute('aria-expanded') === 'true';
				this.setAttribute('aria-expanded', !isExpanded);
				navigation.classList.toggle('menu-open');
			});
		}
	}

	// Smooth scroll for anchor links
	function initSmoothScroll() {
		document.querySelectorAll('a[href^="#"]').forEach(anchor => {
			anchor.addEventListener('click', function (e) {
				const href = this.getAttribute('href');
				if (href !== '#' && href.length > 1) {
					e.preventDefault();
					const target = document.querySelector(href);
					if (target) {
						const headerOffset = 80;
						const elementPosition = target.getBoundingClientRect().top;
						const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

						window.scrollTo({
							top: offsetPosition,
							behavior: 'smooth'
						});

						// Close mobile menu if open
						const navigation = document.getElementById('site-navigation');
						const menuToggle = document.getElementById('nav-toggle');
						if (navigation && navigation.classList.contains('menu-open')) {
							navigation.classList.remove('menu-open');
							if (menuToggle) {
								menuToggle.setAttribute('aria-expanded', 'false');
							}
						}
					}
				}
			});
		});
	}

	// Hero parallax (desktop only, respects reduced motion)
	function initHeroParallax() {
		const hero = document.querySelector('.bb-hero');
		if (!hero) return;

		const reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		if (reduceMotion) return;

		// Avoid heavy effects on small screens
		const isMobile = window.matchMedia && window.matchMedia('(max-width: 768px)').matches;
		if (isMobile) return;

		let ticking = false;
		function update() {
			ticking = false;
			const rect = hero.getBoundingClientRect();
			const viewportH = window.innerHeight || 1;
			// progress: 0 when hero top at viewport top, 1 when hero bottom at viewport top
			const progress = Math.min(1, Math.max(0, (0 - rect.top) / (rect.height || 1)));
			const offset = Math.round(progress * 80); // px
			hero.style.backgroundPosition = `center calc(50% + ${offset}px)`;
		}

		function onScroll() {
			if (ticking) return;
			ticking = true;
			window.requestAnimationFrame(update);
		}

		window.addEventListener('scroll', onScroll, { passive: true });
		window.addEventListener('resize', onScroll);
		update();
	}

	// Affiliations slider (continuous smooth scroll)
	function initAffiliationsSlider() {
		const root = document.querySelector('[data-bb-affiliations]');
		if (!root) return;

		const viewport = root.querySelector('[data-bb-affiliations-viewport]');
		const track = root.querySelector('.bb-affiliations__track');
		const prev = root.querySelector('[data-bb-affiliations-prev]');
		const next = root.querySelector('[data-bb-affiliations-next]');
		if (!viewport || !track) return;

		const reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

		// Continuous scroll variables
		let isScrolling = true;
		let scrollSpeed = reduceMotion ? 0.2 : 0.3; // pixels per frame (slower)
		let animationFrame = null;

		function getStep() {
			const first = track.querySelector('.bb-affiliation-slide');
			if (!first) return Math.max(200, viewport.clientWidth * 0.6);
			const gap = parseFloat(window.getComputedStyle(track).gap || '0') || 0;
			return first.getBoundingClientRect().width + gap;
		}

		function scrollByDir(dir) {
			const step = getStep();
			const maxScroll = viewport.scrollWidth - viewport.clientWidth;
			let target = viewport.scrollLeft + (dir === 'prev' ? -step : step);
			if (target < 0) target = maxScroll; // wrap
			if (target > maxScroll - 2) target = 0; // wrap

			viewport.scrollTo({ left: target, behavior: reduceMotion ? 'auto' : 'smooth' });
		}

		// Continuous smooth scrolling
		function continuousScroll() {
			if (!isScrolling) return;

			const maxScroll = viewport.scrollWidth - viewport.clientWidth;
			let newScrollLeft = viewport.scrollLeft + scrollSpeed;
			if (newScrollLeft >= maxScroll) {
				newScrollLeft = 0;
			}

			viewport.scrollLeft = newScrollLeft;
			animationFrame = window.requestAnimationFrame(continuousScroll);
		}

		const stop = () => {
			isScrolling = false;
			if (animationFrame) {
				window.cancelAnimationFrame(animationFrame);
				animationFrame = null;
			}
		};

		const start = () => {
			if (!isScrolling) {
				isScrolling = true;
				continuousScroll();
			}
		};

		// Button controls (temporarily stop auto-scroll)
		if (prev) {
			prev.addEventListener('click', () => {
				stop();
				scrollByDir('prev');
				setTimeout(start, 3000); // Resume after 3 seconds
			});
		}
		
		if (next) {
			next.addEventListener('click', () => {
				stop();
				scrollByDir('next');
				setTimeout(start, 3000); // Resume after 3 seconds
			});
		}

		// Pause on hover over any slide or the entire slider
		root.addEventListener('mouseenter', stop);
		root.addEventListener('mouseleave', start);
		viewport.addEventListener('focusin', stop);
		viewport.addEventListener('focusout', start);

		// Start the continuous scroll
		if (!reduceMotion) {
			start();
		}
	}

	// Scroll Reveal Animations
	function initScrollReveal() {
		const reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		if (reduceMotion) {
			// Show all elements immediately
			document.querySelectorAll('.bb-scroll-reveal').forEach(el => {
				el.classList.add('is-visible');
			});
			return;
		}

		const revealElements = document.querySelectorAll('.bb-scroll-reveal');
		if (!revealElements.length) return;

		const observer = new IntersectionObserver((entries) => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					entry.target.classList.add('is-visible');
					observer.unobserve(entry.target);
				}
			});
		}, {
			threshold: 0.15,
			rootMargin: '0px 0px -50px 0px'
		});

		revealElements.forEach(el => observer.observe(el));
	}

	// About stats counter
	function initStatCounters() {
		const counters = document.querySelectorAll('[data-count-target]');
		if (!counters.length) return;

		const reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

		function easeOutCubic(t) {
			return 1 - Math.pow(1 - t, 3);
		}

		function runCounter(el) {
			if (el.dataset.counted === 'true') return;
			const target = Number(el.dataset.countTarget);
			if (!Number.isFinite(target)) return;

			el.dataset.counted = 'true';

			if (reduceMotion) {
				el.textContent = Math.round(target).toString();
				return;
			}

			const duration = Number(el.dataset.countDuration) || 1200;
			const startTime = window.performance.now();

			function tick(now) {
				const progress = Math.min((now - startTime) / duration, 1);
				const eased = easeOutCubic(progress);
				const value = Math.round(target * eased);
				el.textContent = value.toString();
				if (progress < 1) {
					window.requestAnimationFrame(tick);
				}
			}

			window.requestAnimationFrame(tick);
		}

		if (reduceMotion) {
			counters.forEach(el => runCounter(el));
			return;
		}

		const observer = new IntersectionObserver((entries) => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					runCounter(entry.target);
					observer.unobserve(entry.target);
				}
			});
		}, {
			threshold: 0.6
		});

		counters.forEach(el => observer.observe(el));
	}

	// Flip Cards on Scroll
	function initFlipCards() {
		const reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		const flipCards = document.querySelectorAll('[data-flip-on-scroll]');
		if (!flipCards.length) return;

		if (reduceMotion) {
			// Just flip them immediately on reduced motion
			flipCards.forEach(card => card.classList.add('is-flipped'));
			return;
		}

		const observer = new IntersectionObserver((entries) => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					// Delay the flip slightly for dramatic effect
					setTimeout(() => {
						entry.target.classList.add('is-flipped');
					}, 700);
					observer.unobserve(entry.target);
				}
			});
		}, {
			threshold: 0.4,
			rootMargin: '0px 0px -100px 0px'
		});

		flipCards.forEach(card => observer.observe(card));

		// Also allow click to toggle flip
		flipCards.forEach(card => {
			card.addEventListener('click', () => {
				card.classList.toggle('is-flipped');
			});
		});
	}

	// Section headers fade-in
	function initSectionHeaders() {
		const reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		if (reduceMotion) return;

		const headers = document.querySelectorAll('.bb-section__header');
		if (!headers.length) return;

		headers.forEach(header => {
			header.style.opacity = '0';
			header.style.transform = 'translateY(30px)';
			header.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
		});

		const observer = new IntersectionObserver((entries) => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					entry.target.style.opacity = '1';
					entry.target.style.transform = 'translateY(0)';
					observer.unobserve(entry.target);
				}
			});
		}, {
			threshold: 0.2
		});

		headers.forEach(header => observer.observe(header));
	}

	// Initialize on DOM ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function() {
			initMobileMenu();
			initSmoothScroll();
			initHeroParallax();
			initAffiliationsSlider();
			initScrollReveal();
			initStatCounters();
			initFlipCards();
			initSectionHeaders();
		});
	} else {
		initMobileMenu();
		initSmoothScroll();
		initHeroParallax();
		initAffiliationsSlider();
		initScrollReveal();
		initStatCounters();
		initFlipCards();
		initSectionHeaders();
	}

})();
