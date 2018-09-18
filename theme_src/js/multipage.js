(function($){

    $(document).ready( function() {

        if(typeof wp_multipage !== 'undefined') {

            console.log(wp_multipage);

            document.getElementById('#' + wp_multipage.id).scrollIntoView();


        }

    });

})(jQuery);