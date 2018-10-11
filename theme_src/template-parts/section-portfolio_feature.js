(function ($) {
    $(document).ready(function () {
        const container = '.portfolio-feature-screenshots',
            parent = 'div.portfolio-feature',
            slides = '.portfolio-feature-screenshots-container',
            mustacheTemplate = '#portfolio_features_template',
            triggerClass = 'closed-slides',
            buttonOpenMessage = 'More Screenshots',
            buttonCloseMessage = 'Close Screenshots';

        $(container).on('click', 'button', function (e) {

            e.preventDefault();
            let $this = $(this);

            let $container = $this.closest(container);

            // if the triggerClass isn't there, then the slides are already there and open
            // so add triggerClass to hide existing slides
            if (!$container.hasClass(triggerClass)) {
                $container.addClass(triggerClass);
                $container.find('button').text(buttonOpenMessage);
                return;
            }

            let $slides = $(slides);
            if ($slides.length !== 0) {  //slides are loaded so just need to show them
                $container.removeClass(triggerClass)
                    .find('button').text(buttonCloseMessage);
                return;
            }

            // Get id of portfolio
            let $parent = $this.closest(parent);
            let id = $parent.data('id');
            console.log(id);

            // Get screenshots using REST

            $.ajax({
                url: wpLocal.restApi + 'portfolio/' + id,

                success: function (data) {
                    let output = Mustache.render($(mustacheTemplate).text(),
                        data);

                    // add the rendered output and when images are finished loading
                    // remove button and remove .not-loaded
                    $container.append(output).imagesLoaded(function () {
                        $container.removeClass(triggerClass)
                            .find('button').text(buttonCloseMessage);

                        $(slides).removeClass('not-loaded');
                    });
                }
            })
            // Add to JS template and append to DOM
            // Initiate Slider
            // Remove button
            // Remove 'not-loaded' class
        });
    })
})(jQuery);