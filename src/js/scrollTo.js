(function($) {

    const fixedHeader = document.querySelector('.site-header');
    const offsetFromFixedHeader = 20;

    /**
     * Functions to help with smoothscrolling
     * Another library besides jQuery could be used here
     */

    const scrollToSection = function(el, offset = 0) {
        const $target = $(el);

        $('html, body').animate({
            scrollTop: $target.offset().top - (fixedHeader.offsetHeight + offsetFromFixedHeader + offset)

        }, 1000, function () {
            $target.focus();
            if ($target.is(":focus")) { // Checking if the target was focused
                return false;
            }
        });
    }

    /**
     * pass this function to other scripts
     */
    window.themeJs = themeJs || {};
    themeJs.scrollToSection = scrollToSection;

})(jQuery);