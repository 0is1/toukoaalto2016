/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 * ======================================================================== */

(function($) {

    // Use this variable to set up the common and page specific functions. If you
    // rename this variable, you will also need to rename the namespace below.
    var Sage = {
        // All pages
        'common': {
            init: function() {
                // JavaScript to be fired on all pages
                echo.init({offset: 1500, throttle: 250, unload: false});

                // MENUS
                $('a[data-action="toggle-main-menu"]').click(function(e){
                    e.preventDefault();
                    $('body, html').toggleClass('no-scroll');
                    $(this).parent('.js-menu-container').toggleClass('open');
                });
                $('.js-main-menu .current-menu-item').each(function() {
                    var hasParentMenuItem = $(this).parents('.menu-item-has-children');
                    if (hasParentMenuItem.length == 1) {
                        $(hasParentMenuItem[0]).addClass('current-menu-item');
                    }
                });
                var scroll = function() {
                    var scrollTop = $(document).scrollTop();
                    var treshold = parseInt(($(window).height() / 2) * 0.85, 10);
                    var className = 'scrolling';
                    if(!$('body').hasClass(className) && scrollTop >= treshold){
                        $('body').addClass(className);
                    } else if($('body').hasClass(className) && scrollTop < treshold){
                        $('body').removeClass(className);
                    }
                };
                var waiting = false;
                // Check position on load too
                scroll();
                $(window).scroll(function() {
                    if (waiting) {
                        return;
                    }
                    waiting = true;

                    scroll();

                    setTimeout(function() {
                        waiting = false;
                    }, 25);
                });

                objectFitImages('img.js-object-fit');

                var doc = document.documentElement;
                doc.setAttribute('data-useragent', navigator.userAgent);
            },
            finalize: function() {
                // JavaScript to be fired on all pages, after page specific JS is fired
            }
        },
        // Home page
        'home': {
            init: function() {
                // JavaScript to be fired on the home page
            },
            finalize: function() {
                // JavaScript to be fired on the home page, after the init JS
            }
        },
        // About us page, note the change from about-us to about_us.
        'about_us': {
            init: function() {
                // JavaScript to be fired on the about us page
            }
        }
    };

    // The routing fires all common scripts, followed by the page specific scripts.
    // Add additional events for more control over timing e.g. a finalize event
    var UTIL = {
        fire: function(func, funcname, args) {
            var fire;
            var namespace = Sage;
            funcname = (funcname === undefined)
                ? 'init'
                : funcname;
            fire = func !== '';
            fire = fire && namespace[func];
            fire = fire && typeof namespace[func][funcname] === 'function';

            if (fire) {
                namespace[func][funcname](args);
            }
        },
        loadEvents: function() {
            // Fire common init JS
            UTIL.fire('common');

            // Fire page-specific init JS, and then finalize JS
            $.each(document.body.className.replace(/-/g, '_').split(/\s+/), function(i, classnm) {
                UTIL.fire(classnm);
                UTIL.fire(classnm, 'finalize');
            });

            // Fire common finalize JS
            UTIL.fire('common', 'finalize');
        }
    };

    // Load Events
    $(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.
