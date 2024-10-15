//-----------------------------------------------
// Set Cookie
//-----------------------------------------------

function setCookie(name, value, days) {
	var expires = "";
	if (days) {
		var date = new Date();
		date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
		expires = "; expires=" + date.toUTCString();
	}
	document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

//-----------------------------------------------
// Grid Overlay
//-----------------------------------------------

function initGridOverlay(cols) {
	const body = document.querySelector('body');
	if (body.classList.contains('logged-in')) {
		let cells = '';
		for ($i = 0; $i < cols; $i++) {
			cells += '<div class="cell small-1"><div></div></div>';
		}
		const grid = '<div class="grid-container"><div class="grid-x grid-padding-x">' + cells + '</div></div>';
		const gridOverlay = document.createElement('div');
		gridOverlay.classList.add('grid-overlay');
		gridOverlay.innerHTML = grid;
		const button = document.createElement('button');
		button.setAttribute('type', 'button');
		button.classList.add('grid-overlay-button');
		button.innerHTML = '<span class="show-for-sr">Toggle overlay</span>';
		button.addEventListener('click', () => {
			if (gridOverlay.classList.contains('active')) {
				gridOverlay.classList.remove('active');
			} else {
				gridOverlay.classList.add('active');
			}
		}, false);
		body.insertBefore(gridOverlay, body.firstChild);
		body.insertBefore(button, body.firstChild);
	}
}

initGridOverlay(14);

//-----------------------------------------------
// Lock scrolling
//-----------------------------------------------

let currentScrollPosition = window.pageYOffset;

function preventScrolling() {
	window.scrollTo(0, currentScrollPosition);
}

function lockWindowScroll() {
	currentScrollPosition = window.pageYOffset;
	window.addEventListener('scroll', preventScrolling);
}

function unlockWindowScroll() {
	window.removeEventListener('scroll', preventScrolling);
}

//-----------------------------------------------
// Trap Focus
//-----------------------------------------------

// https://hiddedevries.nl/en/blog/2017-01-29-using-javascript-to-trap-focus-in-an-element
function trapFocus(element) {
	element.classList.add('focus-trap-active');

	const focusableEls = element.querySelectorAll('a[href]:not([disabled]), button:not([disabled]), textarea:not([disabled]), input[type="text"]:not([disabled]), input[type="radio"]:not([disabled]), input[type="checkbox"]:not([disabled]), select:not([disabled])');
	const firstFocusableEl = focusableEls[0];  
	const lastFocusableEl = focusableEls[focusableEls.length - 1];
	const KEYCODE_TAB = 9;

	element.addEventListener('keydown', function(e) {
		const isTabPressed = (e.key === 'Tab' || e.keyCode === KEYCODE_TAB);

		if (!isTabPressed || !element.classList.contains('focus-trap-active')) return

		if (e.shiftKey) /* shift + tab */ {
			if (document.activeElement === firstFocusableEl) {
				lastFocusableEl.focus();
				e.preventDefault();
			}
		} else /* tab */ {
			if (document.activeElement === lastFocusableEl) {
				firstFocusableEl.focus();
				e.preventDefault();
			}
		}
	});
}

function removeTrapFocus(element) {
	element.classList.remove('focus-trap-active');
}

//-----------------------------------------------
// Element Outer Height
//-----------------------------------------------

/**
 * Returns the element height including margins
 * @param element - element
 * @returns {number}
 */
function outerHeight(element) {
	const height = element.offsetHeight,
	style = window.getComputedStyle(element)
	return ['top', 'bottom']
	.map(side => parseInt(style[`margin-${side}`]))
	.reduce((total, side) => total + side, height)
}

//-----------------------------------------------
// Match Element Heights
//-----------------------------------------------

/**
 * Apply same height to elements
 * @param array elements
 * @returns null
 */
function matchElementHeightsToTallest(elementsToMeasure, elements) {
	let matchedHeight = 0;
	elementsToMeasure.forEach(elementToMeasure => {
		if (elementToMeasure.offsetHeight > matchedHeight) {
			matchedHeight = elementToMeasure.offsetHeight;
		}
	});
	elements.forEach(element => {
		element.style.minHeight = matchedHeight + 'px';
	});
	return;
}

//-----------------------------------------------
// JVH javascript height fix
//-----------------------------------------------

function calculateJvh() {
	vh = window.innerHeight * 0.01;
	const vh_val = vh + 'px';
	document.documentElement.style.setProperty('--vh', vh_val);
}

const viewportHeightEls = document.querySelectorAll('.jvh');

if (viewportHeightEls.length > 0) {
	calculateJvh();

	let vhResizeTimer;
	let vw = window.innerWidth;

	window.addEventListener('resize', (e) => {
		clearTimeout(vhResizeTimer);
		let new_vw = window.innerWidth;

		if (new_vw !== vw) {
			vhResizeTimer = setTimeout(() => {
				calculateJvh();
				vw = window.innerWidth;
			}, 10);
		}
	});
}

//-----------------------------------------------
// Scroll here smoothly links handling
//-----------------------------------------------

function scrollToSmoothlySections() {
	const sections = document.querySelectorAll('.js-scroll-to-smoothly');
	if (sections.length < 1) return;
	sections.forEach(section => {
		const links = document.querySelectorAll('[href="#' + section.id + '"]');
		if (links.length > 0) {
			links.forEach(link => {
				link.addEventListener('click', e => {
					e.preventDefault();
					gsap.to(window, {duration: 1.4, scrollTo: section, ease: "power4.out"});
				});
			});
		}
	});
}

scrollToSmoothlySections();

//-----------------------------------------------
// Header
//-----------------------------------------------

function headerShowHide() {
	const header = document.querySelector('.header');
	if (!header) return;
	ScrollTrigger.create({
		start: "top center",
		markers: false,
		onUpdate: self => {
			if (self.direction === 1 && window.scrollY > 100) {
				header.classList.remove('active');
			} else {
				header.classList.add('active');
			}
		}
	});
	setTimeout(() => {
		const hash = window.location.hash.substr(1);
		if (hash === 'filters') {
			header.classList.add('active');
		}
	}, 30);
}

headerShowHide();

//-----------------------------------------------
// Fade elements in on scroll
//-----------------------------------------------

function fadeInOnScroll(selector) {
	const els = gsap.utils.toArray(selector);
	els.forEach(el => {
		gsap.to(el, {
			autoAlpha: 1,
			y: 0,
			duration: 1,
			scrollTrigger: {
				trigger: el,
				start: 'top 91%',
				markers: false,
			},
			onComplete: () => {
				el.classList.add('fade-in-up--complete')
			}
		});
	});
}

fadeInOnScroll('.fade-in-up');

//-----------------------------------------------
// Menu
//-----------------------------------------------

function menuToggle() {
	const menuControl = document.querySelector('[aria-controls="menu"]');
	if (!menuControl) return;
	const target = menuControl.getAttribute('aria-controls');
	const menu = document.querySelector('#' + target);
	if (!menu) return;
	const body = document.querySelector('body');

	// const menuItems = menu.querySelectorAll('.menu__list-item');
	// const menuItemTl = gsap.timeline({paused: true});
	// menuItemTl.from(menuItems, {autoAlpha: 0, x: '50px', stagger: .1, duration: .4, ease: "power4.out"});

	menuControl.addEventListener('click', e => {
		const isExpanded = menuControl.getAttribute('aria-expanded');

		if (isExpanded === 'false') {
			menuControl.classList.add('open');
			menuControl.setAttribute('aria-expanded', true);

			menu.classList.add('open');
			menu.setAttribute('aria-hidden', false);

			body.classList.add('menu-open');

			lockWindowScroll();
			trapFocus(menu);

			// setTimeout(() => menuItemTl.play(), 250);
		} else {
			// menuItemTl.reverse();

			// setTimeout(() => {
				menuControl.classList.remove('open');
				menuControl.setAttribute('aria-expanded', false);

				menu.classList.remove('open');
				menu.setAttribute('aria-hidden', true);

				body.classList.remove('menu-open');

				unlockWindowScroll();
				removeTrapFocus(menu);
			// }, 500);
		}
	});
}

menuToggle();

//-----------------------------------------------
// Search
//-----------------------------------------------

function searchToggle() {
	const controls = document.querySelectorAll('[aria-controls="search"]');
	if (controls.length < 0) return;
	const search = document.querySelector('#search');
	if (!search) return;
	const body = document.querySelector('body');
	const contentCover = document.querySelector('.content-cover');

	contentCover.addEventListener('click', e => {
		controls[0].click();
	});

	controls.forEach(control => {
		control.addEventListener('click', e => {
			const isExpanded = control.getAttribute('aria-expanded');

			if (isExpanded === 'false') {
				controls.forEach(control => {
					control.classList.add('open')
					control.setAttribute('aria-expanded', true);
				});

				search.classList.add('open');
				search.setAttribute('aria-hidden', false);

				body.classList.add('search-open');
				contentCover.classList.add('active');

				lockWindowScroll();
				trapFocus(search);
			} else {
				controls.forEach(control => {
					control.classList.remove('open');
					control.setAttribute('aria-expanded', false);
				});

				search.classList.remove('open');
				search.setAttribute('aria-hidden', true);

				body.classList.remove('search-open');
				contentCover.classList.remove('active');

				unlockWindowScroll();
				removeTrapFocus(search);
			}
		});
	});
}

searchToggle();

//-----------------------------------------------
// Podcast Player
//-----------------------------------------------

function formatPodcastTime(date, hours, minutes, seconds) {
	date.setHours(hours, minutes, seconds);
	return date.toTimeString().split(' ')[0];
}

function podcastPlayers() {
	const players = document.querySelectorAll('.podcast-player');
	players.forEach(player => {
		const postId = player.dataset.post;
		const audio = player.querySelector('.podcast-player__audio');
		audio.pause();
		const playPause = player.querySelector('.podcast-player__play-pause-toggle');
		const skipBack = player.querySelector('.podcast-player__controls-seek--reverse');
		const skipForward = player.querySelector('.podcast-player__controls-seek--forward');
		const progressBar = player.querySelector('.podcast-player__progress-bar');
		const progressDuration = player.querySelector('.podcast-player__controls-progress-duration');
		const progressCurrent = player.querySelector('.podcast-player__controls-progress-current');
		const loading = player.querySelector('.podcast-player__loading');
		const date = new Date;
		let audioDuration;
		let totalHours = 0;
		let totalMinutes = 0;
		let totalSeconds = 0;

		let updateLocalProgressTimer;

		audio.addEventListener("loadedmetadata", e => {
		// audio.addEventListener("loadeddata", e => {
			audioDuration = audio.duration;
			totalHours = Math.floor(audioDuration / 3600);
			totalMinutes = Math.floor(audioDuration / 60);
			totalSeconds = Math.floor(audioDuration % 60);
			progressDuration.innerHTML = formatPodcastTime(date, totalHours, totalMinutes, totalSeconds);
			gsap.to(loading, {autoAlpha: 0, duration: .8});
			const lastPlayed = localStorage.getItem('lastPlayed' + postId);
			if (lastPlayed !== null) {
				audio.currentTime = lastPlayed;
				progressBar.style.width = ((audio.currentTime / audioDuration) * 100) + '%';
				const progressHours = Math.floor(audio.currentTime / 3600);
				const progressMinutes = Math.floor(audio.currentTime / 60);
				const progressSeconds = Math.floor(audio.currentTime % 60);
				progressCurrent.innerHTML = formatPodcastTime(date, progressHours, progressMinutes, progressSeconds);
			}
		});

		playPause.addEventListener('click', e => {
			if (audio.paused) {
				if (playPause.classList.contains('reset')) {
					audio.currentTime = 0;
				}
				audio.play();
				playPause.classList.add('playing');

				updateLocalProgressTimer = window.setInterval(() => {
  					localStorage.setItem('lastPlayed' + postId, audio.currentTime);
				}, 2000);
			} else {
				audio.pause();
				playPause.classList.remove('playing');
				clearInterval(updateLocalProgressTimer);
			}
		});

		skipForward.addEventListener('click', e => {
			if (audio.currentTime + 30 > audioDuration) {
				return;
			}
			audio.currentTime = audio.currentTime + 30;
		});

		skipBack.addEventListener('click', e => {
			if (audio.currentTime - 30 < 0) {
				audio.currentTime = 0;
				return;
			}
			audio.currentTime = audio.currentTime - 30;
		});

		audio.addEventListener('timeupdate', e => {
			if (audio.currentTime > audioDuration) {
				audio.pause();
				playPause.classList.remove('playing');
				playPause.classList.add('reset');
				audio.currentTime = audioDuration;
				clearInterval(updateLocalProgressTimer);
  				localStorage.setItem('lastPlayed' + postId, 0);
			}
			const progressHours = Math.floor(audio.currentTime / 3600);
			const progressMinutes = Math.floor(audio.currentTime / 60);
			const progressSeconds = Math.floor(audio.currentTime % 60);
			progressCurrent.innerHTML = formatPodcastTime(date, progressHours, progressMinutes, progressSeconds);
			progressBar.style.width = ((audio.currentTime / audioDuration) * 100) + '%';
		});
	});
}

podcastPlayers();

//-----------------------------------------------
// Latest Posts Carousels
//-----------------------------------------------

function initLatestPostsCarousels() {
	const carousels = document.querySelectorAll('.latest-posts__swiper-container.swiper-container');
	if (carousels.length < 1) return;
	carousels.forEach(carousel => {
		const autoplaySpeed = carousel.dataset.autoplay;
		let autoplay = false;
		if (typeof autoplaySpeed !== 'undefined') {
			autoplay = {
				delay: autoplaySpeed,
			//	disableOnInteraction: false,
			//	pauseOnMouseEnter: false,
			}
		}
		const wrapper = carousel.parentNode;
		const block = wrapper.closest('.latest-posts');
		const previous = block.querySelector('.latest-posts__swiper-controls-control--prev');
		const next = block.querySelector('.latest-posts__swiper-controls-control--next');
		// const pagination = wrapper.querySelector('.latest-posts__swiper-pagination');
		const swiper = new Swiper(carousel, {
			// loop: true,
			speed: 900,
			autoplay: autoplay,
			spaceBetween: 20,
			navigation: {
				nextEl: next,
				prevEl: previous,
			},
			// Responsive breakpoints
			breakpoints: {
				// when window width is >= 600px
				600: {
					slidesPerView: 2,
					spaceBetween: 30
				},
				1024: {
					slidesPerView: 2.1,
					spaceBetween: 60
				},
				1440: {
					slidesPerView: 2.2,
					spaceBetween: 100
				},
				1920: {
					slidesPerView: 2.2,
					spaceBetween: 137
				},
			}
			// pagination: {
			// 	el: pagination,
			// 	clickable: true,
			// },
		});
	})
}

initLatestPostsCarousels();

//-----------------------------------------------
// Hot Topic Carousels
//-----------------------------------------------

function initHotTopicCarousels() {
	const carousels = document.querySelectorAll('.hot-topics__swiper-container.swiper-container');
	if (carousels.length < 1) return;
	carousels.forEach(carousel => {
		const autoplaySpeed = carousel.dataset.autoplay;
		let autoplay = false;
		if (typeof autoplaySpeed !== 'undefined') {
			autoplay = {
				delay: autoplaySpeed,
			//	disableOnInteraction: false,
			//	pauseOnMouseEnter: false,
			}
		}
		const wrapper = carousel.parentNode;
		const block = wrapper.closest('.hot-topics');
		const previous = block.querySelector('.hot-topics__swiper-controls-control--prev');
		const next = block.querySelector('.hot-topics__swiper-controls-control--next');
		const previousDesktop = block.querySelector('.hot-topics__swiper-controls-control-desktop--prev');
		const nextDesktop = block.querySelector('.hot-topics__swiper-controls-control-desktop--next');
		const pagination = wrapper.querySelector('.hot-topics__swiper-pagination');
		const swiper = new Swiper(carousel, {
			speed: 600,
			autoplay: autoplay,
			spaceBetween: 20,
			effect: 'fade',
			// fadeEffect: {
			// 	crossFade: true
			// },
			navigation: {
				nextEl: next,
				prevEl: previous,
			},
			pagination: {
				el: pagination,
				clickable: true,
			},
			// Responsive breakpoints
			breakpoints: {
				// when window width is >= 600px
				1024: {
					navigation: {
						nextEl: nextDesktop,
						prevEl: previousDesktop,
					},
				},
			},
		});
	})
}

initHotTopicCarousels();

//-----------------------------------------------
// People Carousels
//-----------------------------------------------

function initPeopleCarousels() {
	const carousels = document.querySelectorAll('.people-carousel__swiper-container.swiper-container');
	if (carousels.length < 1) return;
	carousels.forEach(carousel => {
		const autoplaySpeed = carousel.dataset.autoplay;
		let autoplay = false;
		if (typeof autoplaySpeed !== 'undefined') {
			autoplay = {
				delay: autoplaySpeed,
			//	disableOnInteraction: false,
			//	pauseOnMouseEnter: false,
			}
		}
		const wrapper = carousel.parentNode;
		const block = wrapper.closest('.people-carousel');
		const previous = block.querySelector('.people-carousel__swiper-controls-control--prev');
		const next = block.querySelector('.people-carousel__swiper-controls-control--next');
		const swiper = new Swiper(carousel, {
			duration: 600,
			autoplay: autoplay,
			spaceBetween: 20,
			effect: 'fade',
			// fadeEffect: {
			// 	crossFade: true
			// },
			navigation: {
				nextEl: next,
				prevEl: previous,
			},
			// Responsive breakpoints
			breakpoints: {
				// when window width is >= 600px
				1024: {
				},
			},
		});
	})
}

initPeopleCarousels();

//-----------------------------------------------
// Quotes Carousels
//-----------------------------------------------

function initQuotesCarousels() {
	const carousels = document.querySelectorAll('.quotes__swiper-container.swiper-container');
	if (carousels.length < 1) return;
	carousels.forEach(carousel => {
		const autoplaySpeed = carousel.dataset.autoplay;
		let autoplay = false;
		if (typeof autoplaySpeed !== 'undefined') {
			autoplay = {
				delay: autoplaySpeed,
			//	disableOnInteraction: false,
			//	pauseOnMouseEnter: false,
			}
		}
		const wrapper = carousel.parentNode;
		const block = wrapper.closest('.quotes');
		const previous = block.querySelector('.quotes__swiper-controls-control--prev');
		const next = block.querySelector('.quotes__swiper-controls-control--next');
		const pagination = block.querySelector('.quotes__swiper-pagination');
		const swiper = new Swiper(carousel, {
			duration: 800,
			autoplay: autoplay,
			autoHeight: true,
			spaceBetween: 50,
			navigation: {
				nextEl: next,
				prevEl: previous,
			},
			pagination: {
				el: pagination,
				clickable: true,
			},
		});
	})
}

initQuotesCarousels();

//-----------------------------------------------
// Scrolling Services Carousels
//-----------------------------------------------

function initScrollingServicesCarousels() {
	// const carousels = document.querySelectorAll('.scrolling-services__swiper-container.swiper-container');
	const blocks = document.querySelectorAll('.scrolling-services');
	if (blocks.length < 1) return;
	const destroyBreakpoint = window.matchMedia('(min-width:1024px)');
	const header = document.querySelector('.header');
	// carousels.forEach(carousel => {
	blocks.forEach(block => {
		const head = block.querySelector('.scrolling-services__head');
		const carousel = block.querySelector('.scrolling-services__swiper-container.swiper-container');
		let swiper;
		let sectionScrollTrigger = false;
		if (carousel) {
			const autoplaySpeed = carousel.dataset.autoplay;
			let autoplay = false;
			if (typeof autoplaySpeed !== 'undefined') {
				autoplay = {
					delay: autoplaySpeed,
				//	disableOnInteraction: false,
				//	pauseOnMouseEnter: false,
				}
			}
			const previous = block.querySelector('.scrolling-services__swiper-controls-control--prev');
			const next = block.querySelector('.scrolling-services__swiper-controls-control--next');
			const enableSwiper = () => {
				swiper = new Swiper(carousel, {
					duration: 800,
					autoplay: autoplay,
					spaceBetween: 20,
					slidesPerView: 1,
					navigation: {
						nextEl: next,
						prevEl: previous,
					},
					// Responsive breakpoints
					breakpoints: {
						// when window width is >= 600px
						600: {
							slidesPerView: 2,
							spaceBetween: 30,
						},
					},
				});
			};
		}
		// const wrapper = carousel.parentNode;
		// const block = wrapper.closest('.scrolling-services');
		const desktopChecker = () => {
			if (destroyBreakpoint.matches === true) {
				// clean up old instances and inline styles when available
				if (swiper !== undefined) swiper.destroy(true, true);
				setTimeout(() => {
					sectionScrollTrigger = ScrollTrigger.create({
						trigger: block,
						start: "top top",
						end: "bottom bottom",
						pin: head,
						pinSpacing: false,
						markers: false,
						onEnter: () => {
							head.classList.add('pinned');
						},
						onLeaveBack: () => {
							head.classList.remove('pinned');
						}
					});

					const spacers = block.querySelectorAll('.scrolling-services__scroller-spacer');
					spacers.forEach(spacer => {
						const scroller = block.querySelector('.scrolling-services__scroller[data-scroller="' + spacer.dataset.spacer + '"]');
						ScrollTrigger.create({
							trigger: spacer,
							start: "top 53%",
							end: "bottom 53%",
							markers: false,
							onEnter: () => {
								// console.log('enter');
								setTimeout(() => {scroller.classList.add('in')}, 0);
							},
							onLeave: () => {
								// console.log('leave');
								setTimeout(() => scroller.classList.add('out'), 0);
							},
							onEnterBack: () => {
								// console.log('enterBack');
								setTimeout(() => {scroller.classList.remove('out')}, 0);
							},
							onLeaveBack: () => {
								// console.log('leaveBack');
								scroller.classList.remove('in');
							}
						});
					});
				}, 200);
			} else if (destroyBreakpoint.matches === false) {
				if (carousel) {
					// fire small viewport version of swiper
					enableSwiper();
				}
				// Reset scrollers
				block.querySelectorAll('.scrolling-services__scroller').forEach(scroller => {
					scroller.classList.remove('in');
					scroller.classList.remove('out');
				});
				// Reset Head
				head.classList.remove('pinned');
				// Kill scrolltrigger
				if (sectionScrollTrigger) {
					sectionScrollTrigger.kill();
				}
			}
		};
		// keep an eye on viewport size changes
		destroyBreakpoint.addListener(desktopChecker);
		desktopChecker();
	});
}

initScrollingServicesCarousels();

//-----------------------------------------------
// Compressed Services Carousels
//-----------------------------------------------

function initCompressedServicesCarousels() {
	const carousels = document.querySelectorAll('.compressed-services__swiper-container.swiper-container');
	if (carousels.length < 1) return;
	const destroyBreakpoint = window.matchMedia('(min-width:1024px)');
	const header = document.querySelector('.header');
	carousels.forEach(carousel => {
		const autoplaySpeed = carousel.dataset.autoplay;
		let autoplay = false;
		if (typeof autoplaySpeed !== 'undefined') {
			autoplay = {
				delay: autoplaySpeed,
			//	disableOnInteraction: false,
			//	pauseOnMouseEnter: false,
			}
		}
		const wrapper = carousel.parentNode;
		const block = wrapper.closest('.compressed-services');
		const head = block.querySelector('.compressed-services__head');
		const previous = block.querySelector('.compressed-services__swiper-controls-control--prev');
		const next = block.querySelector('.compressed-services__swiper-controls-control--next');
		let swiper;
		const enableSwiper = () => {
			swiper = new Swiper(carousel, {
				duration: 800,
				autoplay: autoplay,
				spaceBetween: 20,
				slidesPerView: 1,
				navigation: {
					nextEl: next,
					prevEl: previous,
				},
				// Responsive breakpoints
				breakpoints: {
					// when window width is >= 600px
					600: {
						slidesPerView: 2,
						spaceBetween: 30,
					},
				},
			});
		};
		const desktopChecker = () => {
			if (destroyBreakpoint.matches === true) {
				// clean up old instances and inline styles when available
				if (swiper !== undefined) swiper.destroy(true, true);
			} else if (destroyBreakpoint.matches === false) {
				// fire small viewport version of swiper
				enableSwiper();
			}
		};
		// keep an eye on viewport size changes
		destroyBreakpoint.addListener(desktopChecker);
		desktopChecker();
	});
}

initCompressedServicesCarousels();

//-----------------------------------------------
// Scrolling Questions Carousels
//-----------------------------------------------

function initScrollingQuestionsCarousels() {
	const carousels = document.querySelectorAll('.scrolling-questions__swiper-container.swiper-container');
	if (carousels.length < 1) return;
	const destroyBreakpoint = window.matchMedia('(min-width:1024px)');
	const header = document.querySelector('.header');
	carousels.forEach(carousel => {
		const autoplaySpeed = carousel.dataset.autoplay;
		let autoplay = false;
		if (typeof autoplaySpeed !== 'undefined') {
			autoplay = {
				delay: autoplaySpeed,
			//	disableOnInteraction: false,
			//	pauseOnMouseEnter: false,
			}
		}
		const wrapper = carousel.parentNode;
		const block = wrapper.closest('.scrolling-questions');
		const head = block.querySelector('.scrolling-questions__head');
		const previous = block.querySelector('.scrolling-questions__swiper-controls-control--prev');
		const next = block.querySelector('.scrolling-questions__swiper-controls-control--next');
		let swiper;
		const enableSwiper = () => {
			swiper = new Swiper(carousel, {
				duration: 800,
				autoplay: autoplay,
				spaceBetween: 20,
				slidesPerView: 1,
				navigation: {
					nextEl: next,
					prevEl: previous,
				},
				// Responsive breakpoints
				breakpoints: {
					// when window width is >= 600px
					600: {
						slidesPerView: 2,
						spaceBetween: 30,
					},
				},
			});
		};
		const desktopChecker = () => {
			if (destroyBreakpoint.matches === true) {
				// clean up old instances and inline styles when available
				if (swiper !== undefined) swiper.destroy(true, true);
				setTimeout(() => {
					sectionScrollTrigger = ScrollTrigger.create({
						trigger: block,
						start: "top top",
						end: "bottom bottom",
						pin: head,
						pinSpacing: false,
						markers: false,
						onEnter: () => {
							head.classList.add('pinned');
						},
						onLeaveBack: () => {
							head.classList.remove('pinned');
						}
					});

					const spacers = block.querySelectorAll('.scrolling-questions__scroller-spacer');
					spacers.forEach(spacer => {
						const scroller = block.querySelector('.scrolling-questions__scroller[data-scroller="' + spacer.dataset.spacer + '"]');
						const scrollerTl = gsap.timeline({paused: true});
						ScrollTrigger.create({
							trigger: spacer,
							start: "top 53%",
							end: "bottom 53%",
							markers: false,
							onEnter: () => {
								// console.log('enter');
								setTimeout(() => {scroller.classList.add('in')}, 0);
							},
							onLeave: () => {
								// console.log('leave');
								setTimeout(() => scroller.classList.add('out'), 0);
							},
							onEnterBack: () => {
								// console.log('enterBack');
								setTimeout(() => {scroller.classList.remove('out')}, 0);
							},
							onLeaveBack: () => {
								// console.log('leaveBack');
								scroller.classList.remove('in');
							}
						});
					});
				}, 200);
			} else if (destroyBreakpoint.matches === false) {
				// fire small viewport version of swiper
				enableSwiper();
			}
		};
		// keep an eye on viewport size changes
		destroyBreakpoint.addListener(desktopChecker);
		desktopChecker();
	});
}

initScrollingQuestionsCarousels();

//-----------------------------------------------
// What's new Carousels
//-----------------------------------------------

function initWhatsNewCarousels() {
	const carousels = document.querySelectorAll('.whats-new__swiper-container.swiper-container');
	if (carousels.length < 1) return;
	const destroyBreakpoint = window.matchMedia('(min-width:1024px)');
	carousels.forEach(carousel => {
		const autoplaySpeed = carousel.dataset.autoplay;
		let autoplay = false;
		if (typeof autoplaySpeed !== 'undefined') {
			autoplay = {
				delay: autoplaySpeed,
			//	disableOnInteraction: false,
			//	pauseOnMouseEnter: false,
			}
		}
		const wrapper = carousel.parentNode;
		const block = wrapper.closest('.whats-new');
		const cellImagesWraps = block.querySelectorAll('.whats-new__card-cell .whats-new-card__image-wrap');
		const cellImagesWrappers = block.querySelectorAll('.whats-new__card-cell .whats-new-card__image-wrap-outer');
		const previous = block.querySelector('.whats-new__swiper-controls-control--prev');
		const next = block.querySelector('.whats-new__swiper-controls-control--next');
		let swiper;
		const enableSwiper = () => {
			swiper = new Swiper(carousel, {
				duration: 800,
				autoplay: autoplay,
				spaceBetween: 20,
				slidesPerView: 1,
				navigation: {
					nextEl: next,
					prevEl: previous,
				},
				// Responsive breakpoints
				// breakpoints: {
				// 	// when window width is >= 600px
				// 	600: {
				// 		slidesPerView: 2,
				// 		spaceBetween: 30,
				// 	},
				// },
			});
		};
		const desktopChecker = () => {
			if (destroyBreakpoint.matches === true) {
				// Desktop
				if (swiper !== undefined) swiper.destroy(true, true);
				matchElementHeightsToTallest(cellImagesWraps, cellImagesWrappers);
				window.addEventListener('resize', cardHeightHandler);
			} else if (destroyBreakpoint.matches === false) {
				// Mobile
				enableSwiper();
				window.removeEventListener('resize', cardHeightHandler);
			}
		};
		// keep an eye on viewport size changes
		destroyBreakpoint.addListener(desktopChecker);
		desktopChecker();

		let vhResizeTimer;
		let vw = window.innerWidth;
		function cardHeightHandler() {
			clearTimeout(vhResizeTimer);
			let new_vw = window.innerWidth;

			if (new_vw !== vw) {
				vhResizeTimer = setTimeout(() => {
					matchElementHeightsToTallest(cellImagesWraps, cellImagesWrappers);
					vw = window.innerWidth;
				}, 100);
			}
		}
	});
}

initWhatsNewCarousels();

//-----------------------------------------------
// Benefits carousel
//-----------------------------------------------

function initBenefitsCarousels() {
	const carousels = document.querySelectorAll('.benefits-carousel__swiper-container.swiper-container');
	if (carousels.length < 1) return;
	carousels.forEach(carousel => {
		const autoplaySpeed = carousel.dataset.autoplay;
		let autoplay = false;
		if (typeof autoplaySpeed !== 'undefined') {
			autoplay = {
				delay: autoplaySpeed,
			//	disableOnInteraction: false,
			//	pauseOnMouseEnter: false,
			}
		}
		const wrapper = carousel.parentNode;
		const block = wrapper.closest('.benefits-carousel');
		const previous = block.querySelector('.benefits-carousel__swiper-controls-control--prev');
		const next = block.querySelector('.benefits-carousel__swiper-controls-control--next');
		const swiper = new Swiper(carousel, {
			speed: 900,
			autoplay: autoplay,
			spaceBetween: 24,
			// effect: 'fade',
			navigation: {
				nextEl: next,
				prevEl: previous,
			},
			// Responsive breakpoints
			breakpoints: {
				// when window width is >= 600px
				600: {
					slidesPerView: 1.5,
					spaceBetween: 32,
				},
				// when window width is >= 1024px
				1024: {
					slidesPerView: 1.8,
					spaceBetween: 80,
				},
				// when window width is >= 1440px
				1440: {
					slidesPerView: 2,
					spaceBetween: 110,
				},
			},
		});
	})
}

initBenefitsCarousels();

//-----------------------------------------------
// Logo Marquee https://github.com/ezekielaquino/Marquee3000
//-----------------------------------------------

Marquee3k.init();

//-----------------------------------------------
// Careers Accordion 
//-----------------------------------------------

function careersAccordion() {
	const accordions = document.querySelectorAll('.careers-accordion-list');
	accordions.forEach((accordion) => {
		const controls = accordion.querySelectorAll('.careers-accordion-list__accordion-item.has-details .careers-accordion-list__accordion-item-head-control');

		controls.forEach((control) => {
			const detailId = control.getAttribute('aria-controls');
			const detail = accordion.querySelector('#' + detailId);

			control.addEventListener('click', (e) => {
				const isHidden = detail.getAttribute('aria-hidden');

				if (isHidden === 'false') {
					// Close
					gsap.to(detail, {duration: .5, autoAlpha: 0, height: 0, ease: Power1.easeOut});
					detail.setAttribute('aria-hidden', true);
					control.setAttribute('aria-expanded', false);
				} else {
					// Open
					gsap.set(detail, {autoAlpha: 1, height: 'auto'});
					gsap.from(detail, {duration: .5, autoAlpha: 0, height: 0, ease: Power1.easeOut});
					detail.setAttribute('aria-hidden', 'false');
					control.setAttribute('aria-expanded', 'true');
				}
			});
		});
	});
}

careersAccordion();

//-----------------------------------------------
// Meet Our People Images animation
//-----------------------------------------------

function meetOurPeopleImagesAnimations() {
	const rows = document.querySelectorAll('.meet-our-people__images');
	if (rows.length < 0) return;
	rows.forEach((row) => {
		const imageOne = row.querySelector('.meet-our-people__image-primary-wrap');
		const imageTwo = row.querySelector('.meet-our-people__image-secondary-wrap');
		const imageThree = row.querySelector('.meet-our-people__image-tertiary-wrap');
		const markers = false;

		// Beakpoint animations
		ScrollTrigger.matchMedia({
			// Small
			"(max-width: 499px)": () => {
				// ImageOne fade in up
				gsap.from(imageOne, {
					y: 140,
					scrollTrigger: {
						trigger: row,
						start: "top 100%",
						markers: markers,
						scrub: .4,
					}
				});
				// ImageTwo fade in up
				gsap.from(imageTwo, {
					y: 80,
					scrollTrigger: {
						trigger: row,
						start: "top 100%",
						markers: markers,
						scrub: .3,
					}
				});
				// ImageThree fade in up
				gsap.from(imageThree, {
					y: 100,
					scrollTrigger: {
						trigger: row,
						start: "top 100%",
						markers: markers,
						scrub: .2,
					}
				});
			},

			// Medium
			"(min-width: 500px) and (max-width: 999px)": () => {
				// ImageOne fade in up
				gsap.from(imageOne, {
					y: 200,
					scrollTrigger: {
						trigger: row,
						start: "top 100%",
						markers: markers,
						scrub: .4,
					}
				});
				// ImageTwo fade in up
				gsap.from(imageTwo, {
					y: 140,
					scrollTrigger: {
						trigger: row,
						start: "top 100%",
						markers: markers,
						scrub: .3,
					}
				});
				// ImageThree fade in up
				gsap.from(imageThree, {
					y: 160,
					scrollTrigger: {
						trigger: row,
						start: "top 100%",
						markers: markers,
						scrub: .2,
					}
				});
			},

			// Large
			"(min-width: 1000px)": () => {
				// ImageOne fade in up
				gsap.fromTo(imageOne, {
					y: 340,
				}, {
					y: -100,
					scrollTrigger: {
						trigger: row,
						start: "top 100%",
						markers: markers,
						scrub: .7,
					}
				});
				// ImageTwo fade in up
				gsap.fromTo(imageTwo, {
					y: 200,
				}, {
					y: -100,
					scrollTrigger: {
						trigger: row,
						start: "top 100%",
						markers: markers,
						scrub: .5,
					}
				});
				// ImageThree fade in up
				gsap.fromTo(imageThree, {
					y: 220,
				}, {
					y: -100,
					scrollTrigger: {
						trigger: row,
						start: "top 100%",
						markers: markers,
						scrub: .5,
					}
				});
			}
		});
	});
}

meetOurPeopleImagesAnimations();

//-----------------------------------------------
// Image Group Parallax animation
//-----------------------------------------------

function imageGroupParallaxAnimations() {
	const rows = document.querySelectorAll('.image-group');
	if (rows.length < 0) return;
	rows.forEach((row) => {
		const imageOne = row.querySelector('.image-group__image-primary');
		const imageTwo = row.querySelector('.image-group__image-secondary');
		const imageThree = row.querySelector('.image-group__image-tertiary');
		const markers = false;

		// Beakpoint animations
		ScrollTrigger.matchMedia({
			// Small
			"(max-width: 499px)": () => {
				// ImageOne fade in up
				gsap.from(imageOne, {
					y: 100,
					scrollTrigger: {
						trigger: row,
						start: "top 100%",
						markers: markers,
						scrub: .2,
					}
				});
				// ImageTwo fade in up
				gsap.from(imageTwo, {
					y: 50,
					scrollTrigger: {
						trigger: row,
						start: "top 100%",
						markers: markers,
						scrub: .2,
					}
				});
				// ImageThree fade in up
				gsap.from(imageThree, {
					y: 125,
					scrollTrigger: {
						trigger: row,
						start: "top 100%",
						markers: markers,
						scrub: .2,
					}
				});
			},

			// Medium
			"(min-width: 500px) and (max-width: 999px)": () => {
				// ImageOne fade in up
				gsap.from(imageOne, {
					y: 125,
					scrollTrigger: {
						trigger: row,
						start: "top 100%",
						markers: markers,
						scrub: .2,
					}
				});
				// ImageTwo fade in up
				gsap.from(imageTwo, {
					y: 75,
					scrollTrigger: {
						trigger: row,
						start: "top 100%",
						markers: markers,
						scrub: .2,
					}
				});
				// ImageThree fade in up
				gsap.from(imageThree, {
					y: 150,
					scrollTrigger: {
						trigger: row,
						start: "top 100%",
						markers: markers,
						scrub: .2,
					}
				});
			},

			// Large
			"(min-width: 1000px)": () => {
				// ImageOne fade in up
				gsap.fromTo(imageOne, {
					y: 100,
				}, {
					y: -200,
					scrollTrigger: {
						trigger: row,
						start: "top 100%",
						markers: markers,
						scrub: .4,
					}
				});
				// ImageTwo fade in up
				gsap.fromTo(imageTwo, {
					y: 100,
				}, {
					y: -100,
					scrollTrigger: {
						trigger: row,
						start: "top 100%",
						markers: markers,
						scrub: .4,
					}
				});
				// ImageThree fade in up
				gsap.fromTo(imageThree, {
					y: 150,
				}, {
					y: -250,
					scrollTrigger: {
						trigger: row,
						start: "top 100%",
						markers: markers,
						scrub: .4,
					}
				});
			}
		});
	});
}

imageGroupParallaxAnimations();

//-----------------------------------------------
// How can we help desktop right column parallax animation
//-----------------------------------------------

function howCanWeHelpColumnParallax() {
	const blocks = document.querySelectorAll('.how-can-we-help.how-can-we-help--dt-parallax');
	if (blocks.length < 0) return;
	blocks.forEach(block => {
		const column = block.querySelector('.how-can-we-help__content-cell--right');
		const contentBlocks = column.querySelectorAll('.how-can-we-help__content');
		let end = 0;
		contentBlocks.forEach(contentBlock => {
			end += parseInt(outerHeight(contentBlock));
		});
		const markers = false;
		// Beakpoint animations
		ScrollTrigger.matchMedia({
			// Large
			"(min-width: 1000px)": () => {
				gsap.to(column, {
					y: -300,
					scrollTrigger: {
						trigger: column,
						start: "top 50%",
						end: () => end + 'px 10%',
						markers: markers,
						scrub: true,
					}
				});
			}
		});
	});
}

howCanWeHelpColumnParallax();

//-----------------------------------------------
// How can we help highlight fade in on scroll
//-----------------------------------------------

function howCanWeHelpHighlightFadeInOnScroll() {
	const blocks = document.querySelectorAll('.how-can-we-help');
	if (blocks.length < 0) return;

	blocks.forEach(block => {
		const contents = block.querySelectorAll('.how-can-we-help__content');
		const colour = block.dataset.highlight;
		const markers = false;
		contents.forEach(content => {
			const title = content.querySelector('.how-can-we-help__content-title');
			const highlightText = title.querySelector('strong');
			const split = new SplitText(highlightText, {type: "words, chars", charsClass: "highlight-char"});
			const chars = split.chars;
	
			const highlightTl = gsap.timeline({
				scrollTrigger: {
					trigger: title,
					start: "top 70%",
					markers: markers,
					onLeaveBack: () => {
						highlightTl.reverse();
					}
				}
			});

			highlightTl.to(chars, {
				duration: .3,
				color: colour,
				stagger: .04
			});
		});
	});
}

howCanWeHelpHighlightFadeInOnScroll();

//-----------------------------------------------
// Insights Filters
//-----------------------------------------------

function insightsFilters() {
	const hero = document.querySelector('.insights-hero');
	if (!hero) return;
	const controls = hero.querySelectorAll('.insights-hero__filters-taxonomies-taxonomy-control');

	controls.forEach(control => {
		const targetId = control.getAttribute('aria-controls');
		const target = hero.querySelector('#' + targetId);

		control.addEventListener('click', e => {
			setTimeout(() => {
				if (control.getAttribute('aria-expanded') === 'true') {
					hero.classList.remove('open-tax-filter');
					control.setAttribute('aria-expanded', false);
					target.classList.remove('active');
					target.setAttribute('aria-hidden', true);
				} else if (!hero.classList.contains('open-tax-filter')) {
					hero.classList.add('open-tax-filter');
					control.setAttribute('aria-expanded', true);
					target.classList.add('active');
					target.setAttribute('aria-hidden', false);
				}
			}, 1);
		});
	});

	function getActiveFilterControl() {
		if (!hero.classList.contains('open-tax-filter')) return false;
		const activeControl = hero.querySelector('.insights-hero__filters-taxonomies-taxonomy-control[aria-expanded="true"]');
		if (activeControl) {
			return activeControl
		}
	}

	// Click outside event listener
	document.addEventListener('click', e => {
		if (e.target.closest('.insights-hero__filters-taxonomies-taxonomy-terms.active')) return;
		const activeControl = getActiveFilterControl();
		if (activeControl === e.target) return;
		if (activeControl) {
			activeControl.click();
		}
	});

	// Escape key event listener
	window.addEventListener('keydown', e => {
		if (e.key === 'Escape') {
			const activeControl = getActiveFilterControl();
			if (activeControl) {
				activeControl.click();
			}
		}
	});
}

insightsFilters();

//-----------------------------------------------
// Insights Load More
//-----------------------------------------------

function insightsLoadMore() {
	const blocks = document.querySelectorAll('.insights-footer');
	if (blocks.length < 1) return;
	blocks.forEach(block => {
		const control = block.querySelector('.insights-footer__more-control');
		if (!control) return;
		control.addEventListener('click', e => {
			control.disabled = true;
			control.classList.add('loading');
			const controlWrap = control.closest('.insights-footer__more');
			const offset = parseInt(control.dataset.offset);
			const total = parseInt(control.dataset.total);
			const perPage = parseInt(control.dataset.perPage);
			let data = 'action=' + encodeURIComponent('insights_load_more') + '&offset=' + encodeURIComponent(offset) + '&total=' + encodeURIComponent(total) + '&perPage=' + encodeURIComponent(perPage);
			if (control.dataset.type !== undefined) {
				const type = parseInt(control.dataset.type);
				data += '&type=' + encodeURIComponent(type);
			}
			if (control.dataset.category !== undefined) {
				const category = parseInt(control.dataset.category);
				data += '&category=' + encodeURIComponent(category);
			}
			if (control.dataset.search !== undefined) {
				const search = control.dataset.search;
				data += '&search=' + encodeURIComponent(search);
			}
			const request = new XMLHttpRequest();
			request.open('POST', php_data.custom_ajaxurl, true);
			request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
			request.onload = function() {
				if (request.status >= 200 && request.status < 400) {
					if (request.responseText) {
						const response = JSON.parse(request.responseText);
						block.insertAdjacentHTML('beforebegin', response);
						setTimeout(() => {
							const insertedResponse = document.querySelector('.insights-row.fade-in-up:not(.fade-in-up--complete)');
							if (insertedResponse) {
								gsap.to(insertedResponse, {
									autoAlpha: 1,
									y: 0,
									duration: 1,
									onComplete: () => {
										insertedResponse.classList.add('fade-in-up--complete');
									}
								});
							}
							const cardCount = document.querySelectorAll('.insights-row-card');
							const latestPostsCardCount = document.querySelectorAll('.latest-posts__slide');
							const newTotal = cardCount.length + latestPostsCardCount.length;
							const showing = block.querySelector('.insights-footer__showing-inner-count');
							showing.innerText = newTotal;
							if (newTotal === total) {
								const divider = block.querySelector('.insights-footer__divider');
								const more = block.querySelector('.insights-footer__more');
								divider.remove();
								more.remove();
							} else {
								control.dataset.offset = newTotal;
								control.classList.remove('loading');
								control.disabled = false;
							}
						}, 10);
					}
				}
			};
			request.send(data);
		});
	});
}

insightsLoadMore();

//-----------------------------------------------
// Map
//-----------------------------------------------

function initMap() {
	const blocks = document.querySelectorAll('.map');
	if (blocks.length < 1) return;
	blocks.forEach(block => {
		const styledMapType = new google.maps.StyledMapType(
			[
				{"featureType": "all", "elementType": "labels.text.fill", "stylers": [{"saturation": 36}, {"color": "#000000"}, {"lightness": 40}]},
				{"featureType": "all", "elementType": "labels.text.stroke", "stylers": [{"visibility": "on"}, {"color": "#000000"}, {"lightness": 16}]},
				{"featureType": "all", "elementType": "labels.icon", "stylers": [{"visibility": "off"}]},
				{"featureType": "administrative", "elementType": "geometry.fill", "stylers": [{"color": "#000000"}, {"lightness": 20}]},
				{"featureType": "administrative", "elementType": "geometry.stroke", "stylers": [{"color": "#000000"}, {"lightness": 17}, {"weight": 1.2}]},
				{"featureType": "administrative", "elementType": "labels", "stylers": [{"visibility": "off"}]},
				{"featureType": "administrative.country", "elementType": "all", "stylers": [{"visibility": "simplified"}]},
				{"featureType": "administrative.country", "elementType": "geometry", "stylers": [{"visibility": "simplified"}]},
				{"featureType": "administrative.country", "elementType": "labels.text", "stylers": [{"visibility": "simplified"}]},
				{"featureType": "administrative.province", "elementType": "all", "stylers": [{"visibility": "off"}]},
				{"featureType": "administrative.locality", "elementType": "all", "stylers": [{"visibility": "simplified"}, {"saturation": "-100"}, {"lightness": "30"}]},
				{"featureType": "administrative.neighborhood", "elementType": "all", "stylers": [{"visibility": "off"}]}, {"featureType": "administrative.land_parcel", "elementType": "all", "stylers": [{"visibility": "off"}]},
				{"featureType": "landscape", "elementType": "all", "stylers": [{"visibility": "simplified"}, {"gamma": "0.00"}, {"lightness": "74"}]},
				{"featureType": "landscape", "elementType": "geometry", "stylers": [{"color": "#000000"}, {"lightness": 20}]},
				{"featureType": "landscape.man_made", "elementType": "all", "stylers": [{"lightness": "3"}]},
				{"featureType": "poi", "elementType": "all", "stylers": [{"visibility": "off"}]},
				{"featureType": "poi", "elementType": "geometry", "stylers": [{"color": "#000000"}, {"lightness": 21}]},
				{"featureType": "road", "elementType": "geometry", "stylers": [{"visibility": "simplified"}]},
				{"featureType": "road.highway", "lementType": "geometry.fill", "stylers": [{"color": "#000000"}, {"lightness": 17}]},
				{"featureType": "road.highway", "elementType": "geometry.stroke", "stylers": [{"color": "#000000"}, {"lightness": 29}, {"weight": 0.2}]},
				{"featureType": "road.arterial","elementType": "geometry", "stylers": [{"color": "#000000"}, {"lightness": 18}]},
				{"featureType": "road.local", "elementType": "geometry", "stylers": [{"color": "#000000"}, {"lightness": 16}]},
				{"featureType": "transit", "elementType": "geometry", "stylers": [{"color": "#000000"}, {"lightness": 19}]},
				{"featureType": "water", "elementType": "geometry", "stylers": [{"color": "#000000"}, {"lightness": 17}]}
			]
		);

		const el = block.querySelector('.map__map-container');
		if (!el) return;

		// Default centre
		var lat = parseFloat(el.dataset.lat);
		var lng = parseFloat(el.dataset.lng);
		var zoom = parseInt(el.dataset.zoom);
		var latlng = {lat: lat, lng: lng};

		// Create map
		const map = new google.maps.Map(el, {
			mapTypeId: 'satellite, dark',
			disableDefaultUI: true,
			zoom: zoom,
			zoomControl: true,
			center: latlng
		});

		// Associate the styled map with the MapTypeId and set it to display
		map.mapTypes.set('dark', styledMapType);
		map.setMapTypeId('dark');

		var icon = {
			url: '/wp-content/themes/sourceglobalresearch/assets/images/marker.png',
			size: new google.maps.Size(30, 30),
			scaledSize: new google.maps.Size(22, 22), // scaled size
		}

		// Marker
		marker = new google.maps.Marker({
			position: new google.maps.LatLng(latlng),
			map: map,
			icon: icon,
		});
	});
}

initMap();

//-----------------------------------------------
// Cookie Notice
//-----------------------------------------------

// function closeCookieNotice(e) {
// 	const cookieNotice = document.querySelector('.cookie-notice');
// 	cookieNotice.classList.add('hidden');
// 	const cookieName = cookieNotice.dataset.name;
// 	setCookie(cookieName, 1, 365);

// 	// const cookieNoticeHeight = cookieNotice.getBoundingClientRect().height;
// 	// const footer = document.querySelector('.footer');
// 	// const footerMarginTween = gsap.to(footer, {
// 	// 	marginBottom: 0,
// 	// 	duration: .3,
// 	// });
// 	// const main = document.querySelector('#main');
// 	// const mainStyle = window.getComputedStyle ? getComputedStyle(main, null) : main.currentStyle;
// 	// const mainMarginBottomSize = parseInt(mainStyle.marginBottom.replace(/\D/g,''));
// 	// const newMarginBottomSize = mainMarginBottomSize - cookieNoticeHeight;
// 	// const mainMarginTween = gsap.to(main, {
// 	// 	marginBottom: newMarginBottomSize,
// 	// 	duration: .3,
// 	// });
// }

// const cookieNoticeClose = document.querySelector('.cookie-notice__close');
// if (cookieNoticeClose) {
// 	cookieNoticeClose.addEventListener('click', closeCookieNotice);
// }

//-----------------------------------------------
// Media Block CarouselsAccordion
//-----------------------------------------------

// function initMediaCarousels() {
// 	const carousels = document.querySelectorAll('.media__swiper-container.swiper-container');
// 	if (carousels.length < 1) return;

// 	let activeMediaEmbedId = false;

// 	carousels.forEach(carousel => {
// 		const autoplaySpeed = carousel.dataset.autoplay;
// 		let autoplay = false;
// 		if (typeof autoplaySpeed !== 'undefined') {
// 			autoplay = {
// 				delay: autoplaySpeed,
// 			//	disableOnInteraction: false,
// 			//	pauseOnMouseEnter: false,
// 			}
// 		}
// 		const wrapper = carousel.parentNode;
// 		const block = wrapper.parentNode;
// 		const pagination = wrapper.querySelector('.media__swiper-pagination');
// 		const swiper = new Swiper(carousel, {
// 			loop: true,
// 			duration: 800,
// 			autoplay: autoplay,
// 			pagination: {
// 				el: pagination,
// 				clickable: true,
// 			},
// 		});
// 	})
// }

// initMediaCarousels();

//-----------------------------------------------
// Media Third Party Video Embeds
//-----------------------------------------------

// const mediaVideoEmbeds = document.querySelectorAll(".media__slide-video-embed");
// const mediaVideos = {};

// if (mediaVideoEmbeds.length > 0) {
// 	// Nodelist to array to use forEach();
// 	const mediaVideosArray = [].slice.call(mediaVideoEmbeds);

// 	mediaVideosArray.forEach((e, i) => { // element, incrementor
// 		const block = e.closest('.media');
// 		const vidId = block.dataset.block + '-' + i;
// 		const swiperInstance = block.querySelector('.swiper-container').swiper;
// 		mediaVideos[vidId] = {}
// 		mediaVideos[vidId]['embed'] = e;
// 		mediaVideos[vidId]['slide'] = e.closest('.media__slide');
// 		mediaVideos[vidId]['block'] = e.closest('.media');
// 		mediaVideos[vidId]['blockId'] = block.dataset.block;
// 		mediaVideos[vidId]['videoId'] = e.dataset.id;
// 		mediaVideos[vidId]['service'] = e.dataset.type;
// 		mediaVideos[vidId]['cover'] = mediaVideos[vidId]['slide'].querySelector('.media__slide-video-cover');
// 		mediaVideos[vidId]['playControl'] = mediaVideos[vidId]['slide'].querySelector('.media__slide-video-play');

// 		mediaVideos[vidId].slide.dataset.embedId = vidId;

// 		if (mediaVideos[vidId].service === 'vimeo') {
// 			mediaVideos[vidId]['player'] = new Vimeo.Player(mediaVideos[vidId].embed, {
// 				id: mediaVideos[vidId].videoId,
// 				transparent: false
// 			});
// 			mediaVideos[vidId].player.on('ended', function(data) {
// 				resetMediaEmbedVideoContainer(vidId, swiperInstance, 1000);
// 			});
// 		} else if (mediaVideos[vidId].service === 'upload') {
// 			mediaVideos[vidId]['player'] = mediaVideos[vidId].slide.querySelector('.media__slide-video');
// 		}

// 		if (mediaVideos[vidId]['cover']) {
// 			mediaVideos[vidId].playControl.onclick = function(e) {
// 				e.preventDefault();
// 				block.dataset.activeMediaSlide = swiperInstance.slides[swiperInstance.activeIndex].dataset.embedId;

// 				if (swiperInstance.autoplay.running) {
// 					swiperInstance.autoplay.stop();
// 				}

// 				mediaVideos[vidId].cover.style.opacity = 0;
// 				mediaVideos[vidId].cover.style.pointerEvents = 'none';
// 				if (mediaVideos[vidId].service == 'vimeo') {
// 					mediaVideos[vidId].player.play();
// 				} else if (mediaVideos[vidId].service == 'youtube') {
// 					mediaVideos[vidId].player.playVideo();
// 				} else if (mediaVideos[vidId].service == 'upload') {
// 					mediaVideos[vidId].player.play();
// 					mediaVideos[vidId].player.addEventListener('ended', (event) => {
// 						resetMediaEmbedVideoContainer(vidId, swiperInstance, 1000);
// 					});
// 				}
// 			}
// 		}
// 	});
// }

// function resetMediaEmbedVideoContainer(key, swiperInstance, timeout) {
// 	setTimeout(() => {
// 		if (mediaVideos[key].cover) {
// 			mediaVideos[key].cover.removeAttribute('style');
// 		}
// 		if (mediaVideos[key].service == 'youtube') {
// 			mediaVideos[key].player.stopVideo();
// 		} else if (mediaVideos[key].service == 'vimeo') {
// 			mediaVideos[key].player.pause();
// 		}
// 		swiperInstance.el.closest('.media').dataset.activeMediaSlide = 'false';
// 	}, timeout);
// 	setTimeout(() => {
// 		if (typeof swiperInstance.el.dataset.autoplay !== 'undefined') {
// 			swiperInstance.autoplay.start();
// 		}
// 	}, timeout + 1000);
// }

//-----------------------------------------------
// Video Embeds
//-----------------------------------------------

var video_embeds = document.querySelectorAll(".video-embed");

if (video_embeds.length > 0) {
	var videos = {};
	
	// Nodelist to array to use forEach();
	var videos_array = [].slice.call(video_embeds);

	videos_array.forEach(function(e, i) { // element, incrementor
		videos[i] = {}
		videos[i]['parent'] = e;
		videos[i]['video_id'] = e.dataset.videoId;
		videos[i]['service'] = e.dataset.service;
		videos[i]['video_container'] = e.querySelector('.video-container');
		videos[i]['video_container_parent'] = e.querySelector('.video-container').parentNode;
		videos[i]['play_button'] = e.querySelector('.video-play-button');
		videos[i].play_button.onclick = function(e) {
			e.preventDefault();
			videos[i].video_container.style.opacity = 1;
			videos[i].video_container.style.pointerEvents = 'auto';
			if (videos[i].service == 'vimeo') {
				videos[i].player.play();
			} else if (videos[i].service == 'youtube') {
				videos[i].player.playVideo();
			} else if (videos[i].service == 'upload') {
				videos[i].video_file_el = videos[i].video_container.querySelector('.video-el');
				videos[i].video_file_el.play();
				videos[i].video_file_el.addEventListener('ended', (event) => {
					resetVideoContainer(i);
				});
			}
		}

		if (videos[i]['service'] == 'vimeo') {
			videos[i]['player'] = new Vimeo.Player(videos[i].video_container, {
				id: videos[i].video_id,
				transparent: false
			});
			videos[i].player.on('ended', function(data) {
				resetVideoContainer(i);
			});

			Promise.all([videos[i].player.getVideoWidth(), videos[i].player.getVideoHeight()]).then(function(dimensions) {
				var width = dimensions[0];
				var height = dimensions[1];
				var vid_container = videos[i].video_container_parent;

				var style = window.getComputedStyle ? getComputedStyle(vid_container, null) : vid_container.currentStyle;

				vid_container.style.paddingTop = `calc(${height} / ${width} * 100%)`;
			});
		}
	});
}

function onYouTubeIframeAPIReady() {
	if (typeof videos !== 'undefined') {
		Object.keys(videos).forEach(function(key) {
			if (videos[key].service == 'youtube') {
				videos[key]['player'] = new YT.Player(videos[key].video_container, {
					videoId: videos[key].video_id,
					events: {
						'onReady': function(event) {
							onPlayerReady(event, key)
						},
						'onStateChange': function(event) {
							onPlayerStateChange(event, key)
						}
					}
				});
			}
		});
	}
	// if (typeof mediaVideos !== 'undefined') {
	// 	Object.keys(mediaVideos).forEach(key => {
	// 		if (mediaVideos[key].service === 'youtube') {
	// 			mediaVideos[key]['player'] = new YT.Player(mediaVideos[key].embed, {
	// 				videoId: mediaVideos[key].videoId,
	// 				events: {
	// 					'onStateChange': function(event) {
	// 						const swiperEl = mediaVideos[key].block.querySelector('.swiper-container');
	// 						const swiperInstance = swiperEl.swiper;
	// 						const hasAutoplay = swiperEl.dataset.autoplay;
	// 						if (event.data === 1) {
	// 							mediaVideos[key].block.dataset.activeMediaSlide = swiperInstance.slides[swiperInstance.activeIndex].dataset.embedId;
	// 							if (hasAutoplay) {
	// 								mediaVideos[key].block.querySelector('.swiper-container').swiper.autoplay.stop();
	// 							}
	// 						}
	// 						if (event.data === 0) {
	// 							resetMediaEmbedVideoContainer(key, swiperInstance, 1000);
	// 						}
	// 					}
	// 				}
	// 			});
	// 		}
	// 	});
	// }
}

function onPlayerReady(event, key) {
	// Reset the container element
	// after the ready event has fired
	// and the div has changed to an iframe
	videos[key].video_container = videos[key].parent.querySelector('.video-container');
}

function onPlayerStateChange(event, key) {
	if (event.data === 0) {
		resetVideoContainer(key);
	}
}

function resetVideoContainer(key) {
	videos[key].video_container.style.opacity = 0;
	videos[key].video_container.style.pointerEvents = 'none';
	setTimeout(function() {
		if (videos[key].service == 'youtube') {
			videos[key].player.stopVideo();
		} else if (videos[key].service == 'vimeo') {
			videos[key].player.pause();
		}
	}, 1500);
}