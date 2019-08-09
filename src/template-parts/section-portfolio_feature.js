(function ($) {


    function setToggleText(t, isCollapsed){
        const openText = t.getAttribute('data-open-text');

        if(isCollapsed) {
            const closedText = t.getAttribute('data-closed-text');
            if( closedText && closedText !== '' ) {
                t.innerHTML = closedText;
            }
        } else {
            if( openText && openText !== '' ) {
                t.innerHTML = openText;
            }

        }
    }

    $(document).ready(function () {
        const slides = '.portfolio-feature-slides',
            mustacheTemplate = '#portfolio_features_template';

        $(slides).on('show.bs.collapse', function (e) {
            let $this = $(this);

            const buttonTarget = $this.attr('id');
            setToggleText( $( '[data-target="#' + buttonTarget+'"]')[0], false );

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

        $(slides).on('hide.bs.collapse', function() {
            const $this = $(this);
            const buttonTarget = $this.attr('id');
            setToggleText( $( '[data-target="#' + buttonTarget+'"]')[0], true )
        })
    })
})(jQuery);
