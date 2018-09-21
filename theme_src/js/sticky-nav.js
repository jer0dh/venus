//requires debounce.js


document.addEventListener('DOMContentLoaded', function() {

    const nav = document.querySelector('.site-header');
    const navTop = nav.offsetTop;
  //  const originalBodyPadding = document.body.style.paddingTop;

    const stickyNav = new Debouncer( function() {
        if(window.scrollY > navTop) {
            document.body.style.paddingTop = nav.offsetHeight + 'px';
            document.body.classList.add('fixed-nav');
        } else {
            document.body.classList.remove('fixed-nav');
       //     document.body.style.paddingTop = originalBodyPadding;
            document.body.style.paddingTop = 0;
        }
    });

    window.addEventListener('scroll', stickyNav.handleEvent.bind(stickyNav));

});
