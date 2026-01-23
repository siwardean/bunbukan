/**
 * Ken Burns Carousel Effect
 * Cinematic image transitions for About section
 * 
 * @package Bunbukan
 * @since 1.0.0
 */

class KenBurnsCarousel {
	constructor(container, options = {}) {
		this.container = container;
		this.slides = container.querySelectorAll('.bb-about__ken-burns-slide');
		this.currentIndex = 0;
		this.interval = options.interval || 7000; // 7 seconds per image
		this.autoplay = options.autoplay !== false;
		this.timer = null;
		
		// Only initialize if we have multiple slides
		if (this.slides.length > 1) {
			this.init();
		}
	}
	
	init() {
		// Respect user's motion preferences
		const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		
		if (prefersReducedMotion) {
			// Reduce interval for users who prefer reduced motion
			this.interval = 10000; // 10 seconds instead of 7
		}
		
		// Start autoplay if enabled
		if (this.autoplay) {
			this.start();
		}
		
		// Optional: Pause on hover for better UX
		this.container.addEventListener('mouseenter', () => this.pause());
		this.container.addEventListener('mouseleave', () => this.resume());
		
		// Pause when page is not visible (performance optimization)
		document.addEventListener('visibilitychange', () => {
			if (document.hidden) {
				this.pause();
			} else if (this.autoplay) {
				this.resume();
			}
		});
	}
	
	start() {
		this.timer = setInterval(() => {
			this.next();
		}, this.interval);
	}
	
	pause() {
		if (this.timer) {
			clearInterval(this.timer);
			this.timer = null;
		}
	}
	
	resume() {
		if (!this.timer && this.autoplay) {
			this.start();
		}
	}
	
	next() {
		const current = this.slides[this.currentIndex];
		this.currentIndex = (this.currentIndex + 1) % this.slides.length;
		const next = this.slides[this.currentIndex];
		
		// Remove active class from current slide
		current.classList.remove('active');
		
		// Add active class to next slide (triggers animation and crossfade)
		next.classList.add('active');
	}
	
	goTo(index) {
		if (index >= 0 && index < this.slides.length && index !== this.currentIndex) {
			const current = this.slides[this.currentIndex];
			this.currentIndex = index;
			const next = this.slides[this.currentIndex];
			
			current.classList.remove('active');
			next.classList.add('active');
			
			// Reset timer
			if (this.autoplay) {
				this.pause();
				this.start();
			}
		}
	}
	
	destroy() {
		this.pause();
		this.container.removeEventListener('mouseenter', () => this.pause());
		this.container.removeEventListener('mouseleave', () => this.resume());
	}
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
	const kenBurnsContainer = document.querySelector('.bb-about__ken-burns');
	
	if (kenBurnsContainer) {
		// Create carousel instance
		const carousel = new KenBurnsCarousel(kenBurnsContainer, {
			interval: 7000,  // 7 seconds per image
			autoplay: true
		});
		
		// Make available globally for debugging (optional)
		if (window.bunbukan === undefined) {
			window.bunbukan = {};
		}
		window.bunbukan.kenBurnsCarousel = carousel;
	}
});
