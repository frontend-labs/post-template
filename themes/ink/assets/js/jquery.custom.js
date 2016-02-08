(function (window, $) {
	'use strict';

	// Cache document for fast access.
	var document = window.document;

	var Stag = function () {
		/**
		 * Hold reusable elements.
		 *
		 * @type {Object}
		 */
		var cache = {};

		function init() {
			// Cache the reusable elements
			cacheElements();

			// Bind events
			bindEvents();
		}

		/**
		 * Caches elements that are used in this scope.
		 *
		 * @return void
		 */
		function cacheElements() {
			cache.$window       = $(window);
			cache.$document     = $(document);

			// Test for iPod and Safari
			cache.isiPod        = isiPod();
			cache.isSafari      = isSafari();

			cache.$navToggle    = $('#site-navigation-toggle');

			cache.$body         = $('body');
			cache.$isHomepage   = cache.$body.hasClass('home');
			cache.$isSingle     = cache.$body.hasClass('single');
			cache.$isWidgetized = cache.$body.hasClass('page-template-widgetized-php');
			cache.$entryContent = cache.$body.find('.entry-content');
			cache.$fullImages   = cache.$entryContent.find('.alignnone');
			cache.$windowWidth  = cache.$window.width();

			cache.$others       = [];
			cache.$page         = 1;

			cache.windowHeight  = (true === cache.isiPod && true === cache.isSafari) ? window.screen.availHeight : cache.$window.height();
		}

		/**
		 * Setup event binding.
		 *
		 * @return void
		 */
		function bindEvents() {
			// Enable the mobile menu
			cache.$document.on('ready', function() {
				setupRetinaCookie();
				setupMenu();
				staticContentBackground();
				resetHeights();
				setupFitVids();
				setupFullWidthImages();
			});

			cache.$window.on('resize', function() {
				setupFullWidthImages();
			});

			var lazyResize = debounce(resetHeights, 200, false);
			cache.$window.resize(lazyResize);

			$('#scroll-comment-form').on('click', function(e){
				e.preventDefault();
				$('html,body').animate({
					scrollTop: $('#respond').offset().top
		        }, 200);
			});

			$('#load-more-posts').on('click', function(e){
				e.preventDefault();
				var data = $(this);
				infinitePosts(data);
			});

			$('#scroll-to-content').on('click', function(e){
				e.preventDefault();

				$('html,body').animate({
					scrollTop: $('#main').offset().top-80
		        }, 600);
			});
		}

		/**
		 * Activate the mobile menu.
		 *
		 * @return void
		 */
		function setupMenu(e) {
			cache.$navToggle.on('click', function(e){
				e.preventDefault();
				var openClass = 'site-nav-transition site-nav-drawer-open';
				cache.$body.toggleClass(openClass);
			});

			$('.site-nav-overlay, .site-nav .close-nav').on('click', function(e){
				e.preventDefault();
				var openClass = 'site-nav-transition site-nav-drawer-open';
				cache.$body.toggleClass(openClass);
			});
		}

		function resetHeights() {
			setDivHeight( '.article-cover', cache.$others );
		}

		function setupFitVids() {
			// FitVids is only loaded on the pages and single post pages. Check for it before doing anything.
			if (!$.fn.fitVids) {
				return;
			}

			// Get the selectors
			var selectors;
			if ('object' === typeof StagFitvidsCustomSelectors) {
				selectors = StagFitvidsCustomSelectors.customSelector;
			}

			$('.entry-content').fitVids({ customSelector: selectors });

			// Fix padding issue with Blip.tv issues; note that this *must* happen after Fitvids runs
			// The selector finds the Blip.tv iFrame then grabs the .fluid-width-video-wrapper div sibling
			$('.fluid-width-video-wrapper:nth-child(2)', '.video-container').css({ 'paddingTop': 0 });
		}

		function setupFullWidthImages() {
			cache.$fullImages.each(function(){
				var _this = $(this);

				if( _this.hasClass('wp-caption') ) {
					_this.css( { 'margin-left': ( ( cache.$entryContent.width() / 2 ) - ( cache.$window.width() / 2 ) ), 'max-width': 'none' });
					_this.add(_this.find('img')).css( 'width', cache.$window.width());
				}else{
					_this.css( { 'margin-left': ( ( cache.$entryContent.width() / 2 ) - ( cache.$window.width() / 2 ) ), 'max-width': 'none', 'width': cache.$window.width() });
				}
			});
		}

		function infinitePosts(el) {
			el.parent().addClass('loading');

			el.spin('medium', '#000');

			cache.$page++;

			var jqxhr = $.post( postSettings.ajaxurl, {
				action: 'stag_inifinite_scroll',
				nonce: postSettings.nonce,
				search: postSettings.search,
				archive: el.data('archive'),
				page: cache.$page,
			}, function( data ) {
				// Remove load more button if no pages are left
				if(cache.$page >= data.pages) el.parent().fadeOut();

				//Append the content
				$('#main').append(data.content);
			}, 'json' );

			jqxhr.done(function(){
				el.parent().removeClass('loading');
				el.spin(false);
			});
		}

		/**
		 * Setup backgrounds and colors for static content sections on widgetized templates.
		 *
		 * @return void
		 */
		function staticContentBackground() {
			if( !cache.$isWidgetized )
				return;

			$('.page-template-widgetized-php').find('.stag_widget_static_content, .stag_widget_recent_posts').each(function(){
				var _this = $(this),
					bgColor = _this.find('.hentry').data('bg-color'),
					bgImage = _this.find('.hentry').data('bg-image'),
					bgOpacity = parseInt(_this.find('.hentry').data('bg-opacity'), 10),
					textColor = _this.find('.hentry').data('text-color'),
					linkColor = _this.find('.hentry').data('link-color');

				_this.prepend('<div class="static-content-cover" />');
				_this.find('.static-content-cover').css({ 'background-image' : 'url('+bgImage+')', 'opacity' : bgOpacity/100, '-ms-filter': '"alpha(opacity='+bgOpacity+')"' });

				_this.css({ 'background-color': bgColor, 'color' : textColor });
				_this.find('a').css('color', linkColor);
				_this.find('a').css('border-color', linkColor);
				_this.find('h1, h2, h3, h4, h5, h6').css('color', textColor);
			});
		}

		/**
		 * Set 'retina' cookie if on a retina device.
		 *
		 * @return void
		 */
		function setupRetinaCookie() {
			if( document.cookie.indexOf('retina') === -1 && 'devicePixelRatio' in window && window.devicePixelRatio === 2 ){
				document.cookie = 'retina=' + window.devicePixelRatio + ';';
			}
		}

		/**
		 * Check if device is an iPhone or iPod
		 *
		 * @returns {boolean}
		 */
		function isiPod() {
			return (/(iPhone|iPod)/g).test(navigator.userAgent);
		}

		/**
		 * Check if browser is Safari
		 *
		 * @returns {boolean}
		 */
		function isSafari() {
			return (-1 !== navigator.userAgent.indexOf('Safari') && -1 === navigator.userAgent.indexOf('Chrome'));
		}

		/**
		 * Calculate and set the new height of an element
		 *
		 * @param string element   The div to set the height on
		 * @param array  others    An array of other elements to use to calculate the new height
		 *
		 * @return void
		 */
		function setDivHeight(element, others) {
			// iOS devices return an incorrect value with height() so availHeight is used instead.
			var windowHeight = (true === cache.isiPod && true === cache.isSafari) ? window.screen.availHeight : cache.$window.height();
			var offsetHeight = 0;

			// Add up the heights of other elements
			for (var i = 0; i < others.length; i++) {
				offsetHeight += $(others[i]).outerHeight();
			}

			var newHeight = windowHeight - offsetHeight - parseInt( $('html').css('margin-top') );
			// Only set the height if the new height is greater than the original
			if (newHeight > 0) {
				$(element).outerHeight(newHeight);
			}
		}

		/**
		 * Throttles an action.
		 *
		 * Taken from Underscore.js.
		 *
		 * @link    http://underscorejs.org/#debounce
		 *
		 * @param   func
		 * @param   wait
		 * @param   immediate
		 * @returns {Function}
		 */
		function debounce (func, wait, immediate) {
			var timeout, args, context, timestamp, result;
			return function() {
				context = this;
				args = arguments;
				timestamp = new Date();
				var later = function() {
					var last = (new Date()) - timestamp;
					if (last < wait) {
						timeout = setTimeout(later, wait - last);
					} else {
						timeout = null;
						if (!immediate) {
							result = func.apply(context, args);
						}
					}
				};
				var callNow = immediate && !timeout;
				if (!timeout) {
					timeout = setTimeout(later, wait);
				}
				if (callNow) {
					result = func.apply(context, args);
				}
				return result;
			};
		}

		// Initiate the actions.
		init();
	};

	// Instantiate the "class".
	window.Stag = new Stag();
})(window, jQuery);
