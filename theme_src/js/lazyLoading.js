(function ($) {


    /**
     * https://stackoverflow.com/questions/487073/check-if-element-is-visible-after-scrolling
     * @constructor
     */
    function Utils() {

    }

    Utils.prototype = {
        constructor: Utils,
        isElementInView: function (element, fullyInView) {
            var pageTop = $(window).scrollTop();
            var pageBottom = pageTop + $(window).height();
            var elementTop = $(element).offset().top;
            var elementBottom = elementTop + $(element).height();

            if (fullyInView === true) {
                return ((pageTop < elementTop) && (pageBottom > elementBottom));
            } else {
                return ((elementTop <= pageBottom) && (elementBottom >= pageTop));
            }
        }
    };

    var Utils = new Utils();


    $(document).ready(function () {

        /**BEGIN: Section lazy loading
         *
         * Check if sections are in view.  If so, load images, and remove .not-loaded
         */

        const loadImg = function (el) {
            let $this = $(el);

            //Read in the data-srcset attributes of this img tag

            let dataSrcSet = $this.data('srcset');

            if (dataSrcSet) {
                $this.attr('srcset', dataSrcSet);

                // imagesLoaded will run the function once the image is fully loaded to the browser
                $this.imagesLoaded(function () {
                    $this.addClass('lazy-image-loaded');
                });

            }
        };


        let $sectionsToLoad = $('.not-loaded');

        /**
         * loadInView goes through each section.not-loaded and determines of it is in view.  If so, loads any responsive-background-images
         */

        const loadInView = function () {
            $sectionsToLoad.filter('.not-loaded').each(function (i, section) {
                let $section = $(section);
                if (Utils.isElementInView(section)) {
                    let $imgs = $section.find('.lazy-image');
                    if ($imgs.length > 0) {
                        $imgs.each(function () {
                            loadImg(this);
                        })
                    }
                    $section.imagesLoaded({background: true})  //once image is loaded into DOM, remove class .not-loaded.  Even if error.
                        .always(function () {
                            $section.removeClass('not-loaded');
                        });

                }
            });

        };

        //run Load in view on page load for all sections already in viewport
        loadInView();

        const onScrollLoadInView = new Debouncer(function () {
            loadInView();
        });
        $(window).on('scroll', onScrollLoadInView.handleEvent.bind(onScrollLoadInView));  //check if new .not-loaded has come into view on scroll event.

    });

})(jQuery);