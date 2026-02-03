/**
 * Bunbukan Theme JavaScript
 */

(function () {
	'use strict';

	// Mobile menu toggle
	function initMobileMenu() {
		const menuToggle = document.getElementById('nav-toggle');
		const navigation = document.getElementById('site-navigation');

		if (menuToggle && navigation) {
			menuToggle.addEventListener('click', function () {
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

	// Affiliations slider (Simplified: CSS handles animation, JS handles pausing if needed)
	function initAffiliationsSlider() {
		const root = document.querySelector('[data-bb-affiliations]');
		if (!root) return;

		const track = root.querySelector('.bb-affiliations__track');
		if (!track) return;

		// We use CSS for animation now. 
		// JS can be used for extra features if needed.
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

	// Disciplines background logos parallax effect
	function initDisciplinesParallax() {
		const reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		if (reduceMotion) return;

		// Avoid heavy effects on small screens
		const isMobile = window.matchMedia && window.matchMedia('(max-width: 768px)').matches;
		if (isMobile) return;

		const disciplineCards = document.querySelectorAll('.bb-discipline-card');
		if (!disciplineCards.length) return;

		const bgLogos = document.querySelectorAll('.bb-discipline-card__bg-logo');
		if (!bgLogos.length) return;

		let ticking = false;

		function update() {
			ticking = false;
			
			bgLogos.forEach(logo => {
				const card = logo.closest('.bb-discipline-card');
				if (!card) return;

				const rect = card.getBoundingClientRect();
				const viewportH = window.innerHeight || 1;
				const cardCenter = rect.top + rect.height / 2;
				const viewportCenter = viewportH / 2;
				
				// Calculate distance from viewport center
				const distance = cardCenter - viewportCenter;
				// Normalize: -1 when card is above viewport, 0 when centered, 1 when below
				const normalizedDistance = distance / viewportH;
				
				// Parallax offset: more pronounced movement (max 60px)
				const parallaxOffset = normalizedDistance * 60;
				
				// Apply transform with smooth transition
				logo.style.transform = `translate(-50%, calc(-50% + ${parallaxOffset}px))`;
			});
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

	// Language dropdown in footer (opens upward)
	function initLanguageDropdown() {
		const dropdowns = document.querySelectorAll('[data-dropdown]');
		dropdowns.forEach(function (dropdown) {
			const toggle = dropdown.querySelector('.language-dropdown__toggle');
			if (!toggle) return;
			toggle.addEventListener('click', function (e) {
				e.stopPropagation();
				const isOpen = dropdown.hasAttribute('data-open');
				dropdowns.forEach(function (d) {
					d.removeAttribute('data-open');
					var t = d.querySelector('.language-dropdown__toggle');
					if (t) t.setAttribute('aria-expanded', 'false');
				});
				if (!isOpen) {
					dropdown.setAttribute('data-open', '');
					toggle.setAttribute('aria-expanded', 'true');
				}
			});
		});
		document.addEventListener('click', function () {
			dropdowns.forEach(function (d) {
				d.removeAttribute('data-open');
				var t = d.querySelector('.language-dropdown__toggle');
				if (t) t.setAttribute('aria-expanded', 'false');
			});
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape') {
				dropdowns.forEach(function (d) {
					d.removeAttribute('data-open');
					var t = d.querySelector('.language-dropdown__toggle');
					if (t) t.setAttribute('aria-expanded', 'false');
				});
			}
		});
	}

	// Initialize on DOM ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () {
			initMobileMenu();
			initSmoothScroll();
			initHeroParallax();
			initAffiliationsSlider();
			initScrollReveal();
			initStatCounters();
			initFlipCards();
			initSectionHeaders();
			initDisciplinesParallax();
			initLanguageDropdown();
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
		initDisciplinesParallax();
		initLanguageDropdown();
	}

})();
