(function ($) {

    $(document).ready(function () {
        const slides = '.portfolio-feature-slides',
            mustacheTemplate = '#portfolio_features_template';

        $(slides).on('show.bs.collapse', function (e) {

            console.log(e);
            let $this = $(this);
            if( $this.hasClass('slides-not-loaded') ) {

                const id = $this.data('id');

                // Get screenshots using REST

                $.ajax({
                    url: wpLocal.restApi + 'portfolio/' + id,

                    beforeSend: function() {
                        $this.addClass('ajax-loading');
                    },

                    success: function (data) {

                        $this.removeClass('slides-not-loaded');
                        // Apply Mustache template to data
                        const output = Mustache.render($(mustacheTemplate).text(),
                            data);

                        // add the rendered output and when images are finished loading expand section
                        $this.append(output).imagesLoaded().done(function () {
                            // $this.trigger('toggleCollapse'); //initial markup has data-collapsed set to true so this will expand section see animateHeight.js
                        });
                    },

                    always: function() {
                        $this.removeClass('ajax-loading');
                    },

                    error: function(x, msg) {
                        console.log('Could not load from server:' + msg);
                    }
                })
            }



        });
    })
})(jQuery);
