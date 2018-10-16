(function ($) {

    const fixedHeader = document.querySelector('.site-header');
    const offsetFromFixedHeader = 20;
    const menuItem = 'menu-item';
    const currentMenuItemClass = 'current-menu-item';

    const animateScrollToTop = function animateScrollToTop(target,time = 1000, callback = null) {
        $('html, body').animate({
            scrollTop: target.offsetTop - (fixedHeader.offsetHeight + offsetFromFixedHeader)

        }, time, callback);

    }

    $(document).ready(function () {

        // Check to see if this page called externally with a hash/bookmark in the url.  If so, scroll to bottom of fixed header
        const loadedHash = window.location.hash;
        if(loadedHash) {
            const hashTop = document.querySelector(loadedHash);
            const targetTop = hashTop.offsetTop;
            animateScrollToTop(hashTop, 1000, function () {
               // history.replaceState({}, "", hashTop.selector);
                // Callback after animation
                // Must change focus!
                const $target = $(hashTop);
                $target.focus();
                if ($target.is(":focus")) { // Checking if the target was focused
                    return false;
                }
                let newTargetTop = hashTop.offsetTop;
                if(newTargetTop !== targetTop) {  //some elements may have expanded page while scrolling
                    animateScrollToTop($target[0]);
                }

            });

            // Look to see if menu contains a link to this hash, if so add currentMenuItemClass to it
            const newCurrentMenuItem = $('.'+menuItem+' a[href=' + loadedHash + ']');
            if(newCurrentMenuItem.length > 0) {

                $('.' + menuItem).removeClass(currentMenuItemClass);
                newCurrentMenuItem.closest('.' + menuItem).addClass(currentMenuItemClass);
            }

        }


        // Select all links with hashes
        $('a[href*="#"]')
        // Remove links that don't actually link to anything
            .not('[href="#"]')
            .not('[href="#0"]')
            .click(function (event) {
                // On-page links
                if (
                    location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '')
                    &&
                    location.hostname == this.hostname
                ) {
                    // Figure out element to scroll to
                    let $target = $(this.hash);
                    $target = $target.length ? $target : $('[name=' + this.hash.slice(1) + ']');
                    // Does a scroll target exist?
                    if ($target.length) {
                        // Only prevent default if animation is actually gonna happen
                        event.preventDefault();

                        //Set current-menu-item
                        const $currentMenuItem = $(this).closest('.' + menuItem);
                        $currentMenuItem.siblings().removeClass(currentMenuItemClass);
                        $currentMenuItem.addClass(currentMenuItemClass);

                        let targetTop = $target.offset().top;

                        //Adding timeout to help make sure menu closing is done so fixedHeader height is correct
                        setTimeout(function() {
                            animateScrollToTop($target[0],1000, function () {
                                   history.replaceState({}, "", '#' + $target.attr('id'));
                                // Callback after animation
                                // Must change focus!
                                $target.focus();
                                if ($target.is(":focus")) { // Checking if the target was focused
                                    return false;
                                }
                                let newTargetTop = $target.offset().top;
                                if(newTargetTop !== targetTop) {  //some elements may have expanded page while scrolling
                                    animateScrollToTop($target[0]);
                                }

                        })}, 200);

                    }
                }
            });

    });

})(jQuery);