(function ($) {

    const fixedHeader = document.querySelector('.site-header');
    const offsetFromFixedHeader = 20;
    const menuItem = 'menu-item';
    const currentMenuItemClass = 'current-menu-item';

    $(document).ready(function () {

        // Check to see if this page called externally with a hash/bookmark in the url.  If so, scroll to bottom of fixed header
        const loadedHash = window.location.hash;
        if(loadedHash) {
            let hashTop = document.querySelector(loadedHash);
            $('html, body').animate({
                scrollTop: hashTop.offsetTop - (fixedHeader.offsetHeight + offsetFromFixedHeader)

            }, 1000);

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
                    var target = $(this.hash);
                    target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                    // Does a scroll target exist?
                    if (target.length) {
                        // Only prevent default if animation is actually gonna happen
                        event.preventDefault();

                        //Set current-menu-item
                        const $currentMenuItem = $(this).closest('.' + menuItem);
                        $currentMenuItem.siblings().removeClass(currentMenuItemClass);
                        $currentMenuItem.addClass(currentMenuItemClass);

                        //Adding timeout to help make sure menu closing is done so fixedHeader height is correct
                        setTimeout(function() {
                            $('html, body').animate({
                                scrollTop: target.offset().top - fixedHeader.offsetHeight

                            }, 1000, function () {
                                //  history.replaceState({}, "", target.selector);
                                // Callback after animation
                                // Must change focus!
                                var $target = $(target);
                                $target.focus();
                                if ($target.is(":focus")) { // Checking if the target was focused
                                    return false;
                                }
                        })}, 200);

                    }
                }
            });

    });

})(jQuery);