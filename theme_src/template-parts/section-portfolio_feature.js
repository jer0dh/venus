(function ($) {

    $(document).ready(function () {
        const container = '.portfolio-feature-screenshots',
            parent = 'div.portfolio-feature',
            slides = '.portfolio-feature-screenshots-container',
            mustacheTemplate = '#portfolio_features_template';

        $(container).on('click', 'button', function (e) {

            e.preventDefault();
            let $this = $(this);

            let $container = $this.closest(container);

            let $slides = $(slides);

            // do slides already exist
            if ($slides.length > 0) {
                $this.trigger('toggleCollapse'); //expand or collapse section see animateHeight.js
                return;
            }

            // Slides do not exist so load them

            // Get id of portfolio
            let $parent = $this.closest(parent);
            let id = $parent.data('id');

            // Get screenshots using REST

            $.ajax({
                url: wpLocal.restApi + 'portfolio/' + id,

                beforeSend: function() {
                    $this.addClass('ajax-loading');
                },

                success: function (data) {

                    // Apply Mustache template to data
                    let output = Mustache.render($(mustacheTemplate).text(),
                        data);

                    // add the rendered output and when images are finished loading expand section
                    $container.append(output).imagesLoaded().done(function () {
                        $this.trigger('toggleCollapse'); //initial markup has data-collapsed set to true so this will expand section see animateHeight.js
                    });
                },

                always: function() {
                    $this.removeClass('ajax-loading');
                },

                error: function(x, msg) {
                    console.log('Could not load from server:' + msg);
                }
            })

        });
    })
})(jQuery);