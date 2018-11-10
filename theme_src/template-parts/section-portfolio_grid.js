(function ($) {

    window.themeJs = window.themeJs || {};

    const gridItem = '.portfolio-grid-item',
        parent = '.portfolio-grid',
        slides = '.portfolio-grid-screenshots-container',
        expandableSection = '.portfolio-grid-more-info',
        mustacheTemplate = '#portfolio_grid_template',
        gridItemOpenClass = '.grid-item-open',
        closeButton = '.close-button';


    function onClickGridItem() {

        const $item = $(this);
        const id = $item.attr('data-id');
        const $expandedItem = $item.closest(parent).find(expandableSection + '[data-id="' + id + '"]');

        if ($item.hasClass(gridItemOpenClass)) {

            $item.trigger('closeMe');

            if ($expandedItem.length > 0) {
                $expandedItem.trigger('closeMe');
            }

        } else {

            $item.trigger('openMe');

            if ($expandedItem.length > 0) {
                $expandedItem.trigger('openMe');
            }
        }
    }

    function onOpenMeGridItem() {
        $(this).addClass(gridItemOpenClass);
    }

    function onCloseMeGridItem() {
        $(this).removeClass(gridItemOpenClass);
    }

    function onClickExpItemCloseButton() {

        const $item = $(this);
        const $expanded = $item.closest(expandableSection);
        const id = $expanded.attr('data-id');
        const $gridItem = $item.closest(parent).find(gridItem + '[data-id="' + id + '"]');

        $gridItem.trigger('closeMe');
        $expanded.trigger('closeMe');
    }

    function onOpenMeExpItem() {

        const $item = $(this);
        const id = $item.attr('data-id');
        const $gridItem = $item.closest(parent).find(gridItem + '[data-id="' + id + '"]');

        //find location to move it
        const $rowEnd = getGridItemAtRowEnd($gridItem);

        //Add classes and height
        $item.addClass('not-loaded')
            .css('height', '0');

        //move expanded section
        $rowEnd.after($item);

        //Show it
        themeJs.loadSection($item, function() {
            themeJs.scrollToSection($item);
            themeJs.expandSection($item[0]);
        });
    }

    function onCloseMeExpItem() {

        const $item = $(this);
        const id = $item.attr('data-id');
        const $gridItem = $item.closest(parent).find(gridItem + '[data-id="' + id + '"]');

        themeJs.collapseSection($item[0], function() {
            $item.appendTo($gridItem);
        });
    }

    function getGridItemAtRowEnd($currentGridItem) {

        const gridItemOffset = $currentGridItem.offset().top; //the offset for current gridItem

        //select all gridItems after this gridItem
        const $nextGridItems = $currentGridItem.nextAll(gridItem);

        //Look through each gridItem and find the first one that is on the next row
        //and insert cloned expanded section right before
        let found = false;
        $nextGridItems.each(function (i, item) {
            const $item = $(item);
            if (gridItemOffset < $item.offset().top) {
                found = (i !== 0) ? $($nextGridItems[i-1]) : $currentGridItem ; //previous
                return false;  //break out
            }
        });
        if( found !== false) {
            return found;
        }
        // if got to here, on last row, return last item
        return $(gridItem).last();
    }

    function onClickSlideButton() {

            const $this = $(this);

            let $container = $this.closest(expandableSection);
            console.log($container);

            let $slides = $container.find(slides);

            // do slides already exist
            if ($slides.length > 0) {
                console.log('feature - slides already exist');
                $this.trigger('toggleCollapse'); //expand or collapse section see animateHeight.js
                return;
            }

            // Slides do not exist so load them

            // Get id of portfolio
            let id = $this.data('id');
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
            });

            return false;


    }

    $(document).ready(function () {

        $(parent).on('click', gridItem, onClickGridItem )

        .on('openMe', gridItem, onOpenMeGridItem )

        .on('closeMe', gridItem, onCloseMeGridItem )

        .on('openMe', expandableSection, onOpenMeExpItem )

        .on('closeMe', expandableSection, onCloseMeExpItem )

        .on('click', closeButton, onClickExpItemCloseButton )

        .on('click', expandableSection + ' button', onClickSlideButton);


        // Clicking More slides button
      /*  $(expandableSection).on('click', 'button', function (e) {

            e.preventDefault();
            let $this = $(this);

            let $expandableSection = $this.closest(expandableSection);

            let $slides = $expandableSection.find(slides);

            // do slides already exist
            if ($slides.length > 0) {
                console.log('slides already exist');
                $this.trigger('toggleCollapse'); //expand or collapse section see animateHeight.js
                return;
            }

            // Slides do not exist so load them

            // Get id of portfolio
            let $parent = $this.closest(expandableSection);
            console.log($parent);
            let id = $this.data('id');
            console.log(id);

            // Get screenshots using REST

            $.ajax({
                url: wpLocal.restApi + 'portfolio/' + id,

                beforeSend: function () {
                    $this.addClass('ajax-loading');
                },

                success: function (data) {

                    // Apply Mustache template to data
                    let output = Mustache.render($(mustacheTemplate).text(),
                        data);

                    // add the rendered output and when images are finished loading expand section
                    $parent.append(output).imagesLoaded().done(function () {
                        $this.trigger('toggleCollapse'); //initial markup has data-collapsed set to true so this will expand section see animateHeight.js
                    });
                },

                always: function () {
                    $this.removeClass('ajax-loading');
                },

                error: function (x, msg) {
                    console.log('Could not load from server:' + msg);
                }
            })

        });*/
    })
})(jQuery);
