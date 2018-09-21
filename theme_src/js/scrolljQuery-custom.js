(function ($) {

    const fixedHeader = document.querySelector('.site-header');

    $(document).ready(function () {

        // Check to see if this page called externally with a hash/bookmark in the url.  If so, scroll to bottom of fixed header
        const loadedHash = window.location.hash;
        if(loadedHash) {
            let hashTop = document.querySelector(loadedHash);
            $('html, body').animate({
                scrollTop: hashTop.offsetTop - fixedHeader.offsetHeight

            }, 1000);
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

                        });
                    }
                }
            });

    });

})(jQuery);