//include debounce
//include smoothScroll

/* Setup button that scrolls page to the top
        ------------------------------------------------------------------------
         */

document.addEventListener('DOMContentLoaded', function() {
    const newDiv = document.createElement('div');
    newDiv.classList.add('goToTop');
    document.body.appendChild(newDiv);

    newDiv.addEventListener( 'click', function() {
        let body = document.querySelector('body');
        let scroll = new SmoothScroll();
        scroll.animateScroll(body);
    }, false);

});

/**
 * Determines when the button is active based on scroll position
 */
function scrollFunction() {
    if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
        document.querySelector(".goToTop").classList.add('active');
        document.querySelector(".goToTop").classList.add('bounceInRight');
        document.querySelector(".goToTop").classList.remove('bounceOutRight');
    } else {
        document.querySelector(".goToTop").classList.remove('bounceInRight');
        document.querySelector(".goToTop").classList.add('bounceOutRight');
    }
}

let debouncedScroll = debounce(scrollFunction,250);

window.addEventListener('scroll', debouncedScroll, false);

/* END Setup button to go to top
------------------------------------------------------------------------
 */