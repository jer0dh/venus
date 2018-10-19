(function( $ ){

/* javascript code goes here.  Will run after page is loaded in the DOM */


    $('document').ready(function() {


        //removeIf(production)
        console.log( 'starting scripts.js');
        //endRemoveIf(production)

/*        $(document).on('click', '.close-button', function(e) {
            e.preventDefault();

            const $this = $(this);
            const $container = $this.closest('.close-button-container');

            // if this container is using the animateHeight code then use that to close it.
            if($container.attr('data-collapsed') === 'false') {

                const id = $container.attr('id');
                if(id) {
                    // if there is a button assigned to open and close this container, the trigger event on it
                    $button = $('[data-collapse="#' + id + '"]');
                    if($button.length > 0) {
                        $button.trigger('toggleCollapse');
                        return;
                    }
                }
                themeJs.collapseSection($container[0]);

            } else {
                $container.hide();
            }
        })*/

        /* Sticky NAV code
        --------------------------------------------------------------------


       $('.site-header').sticky({
            responsiveWidth: true,
            widthFromWrapper: true,
            zIndex: 999
        });

        var updateSticky = debounce(function () {
            $('.site-header').sticky('update');

        }, 250);

        window.addEventListener('resize', updateSticky);

         END Sticky NAV code
        -----------------------------------------------------------------------
         */

        /* Sticky NAV code - sticky-kit
        -------------------------------------------------------------------------
         */

/*        $('.site-header').stick_in_parent();
        $('.nav-primary').stick_in_parent();*/

        /* END Sticky NAV code - sticky-kit
        -------------------------------------------------------------------------
         */



    });

})(jQuery);
