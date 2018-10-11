// Original code came from:
// https://css-tricks.com/using-css-transitions-auto-dimensions/

//Originally was only Vanilla js, but needed to be able to apply custom events so we can keep this collapse functionality separate.  For compatibility, it was easier to just use
// jQUery: https://blog.garstasio.com/you-dont-need-jquery/events/#sending-custom-events

// so to do this another script must assign the collapse toggle element a click event and do what ever else needs to be done and then trigger the 'toggleCollapse' custom event
// This is the important part!

(function($) {

    document.addEventListener('DOMContentLoaded', function () {

        function collapseSection(element) {
            // get the height of the element's inner content, regardless of its actual size
            let sectionHeight = element.scrollHeight;

            // temporarily disable all css transitions
            let elementTransition = element.style.transition;
            element.style.transition = '';

            // on the next frame (as soon as the previous style change has taken effect),
            // explicitly set the element's height to its current pixel height, so we
            // aren't transitioning out of 'auto'
            requestAnimationFrame(function () {
                element.style.height = sectionHeight + 'px';
                element.style.transition = elementTransition;

                // on the next frame (as soon as the previous style change has taken effect),
                // have the element transition to height: 0
                requestAnimationFrame(function () {
                    element.style.height = 0 + 'px';
                });
            });

            // mark the section as "currently collapsed"
            element.setAttribute('data-collapsed', 'true');
        }

        function expandSection(element) {
            // get the height of the element's inner content, regardless of its actual size
            let sectionHeight = element.scrollHeight;

            // have the element transition to the height of its inner content
            element.style.height = sectionHeight + 'px';

            // when the next css transition finishes (which should be the one we just triggered)
            element.addEventListener('transitionend', function (e) {
                // remove this event listener so it only gets triggered once
                element.removeEventListener('transitionend', arguments.callee);

                // remove "height" from the element's inline styles, so it can return to its initial value
                element.style.height = null;
            });

            // mark the section as "currently not collapsed"
            element.setAttribute('data-collapsed', 'false');
        }

        //document.querySelector('[data-collapse]').addEventListener('collapse', function (e) {
        $('[data-collapse]').on( 'toggleCollapse', function() {
            let sectionToCollapse = this.getAttribute('data-collapse');
            let section = document.querySelector(sectionToCollapse);
            if (section) {
                let isCollapsed = section.getAttribute('data-collapsed') === 'true';

                if (isCollapsed) {
                    expandSection(section);
                    section.setAttribute('data-collapsed', 'false')
                } else {
                    collapseSection(section)
                }
            }
        });

    });

})(jQuery);