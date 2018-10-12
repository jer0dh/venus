(function ($) {

    window.themeJs = window.themeJs || {};

    const gridItem = '.portfolio-grid-item',
        gridThumbnail = '.portfolio-grid-feature',
        parent = '.portfolio-grid',
        slides = '.portfolio-grid-screenshots-container',
        gridMoreInfo = '.portfolio-grid-more-info',
        mustacheTemplate = '#portfolio_grid_template',
        expandedGridItem = '.expanded-grid-item',
        gridItemOpenClass = '.grid-item-open';


    /**
     * This looks for a right place to put the moreInfo section.  It will place it below the row of
     * the $gridItem.  Since different screens widths will cause the layout of the grid to be different
     * this looks at the gridItem offsets and looks for the next gridItem's offset that indicates it
     * is on the next row... or we've reached the bottom of the grid.
     * @param $gridItem
     */
    function openGridItem($gridItem) {
        let id = $gridItem.attr('data-id');

        $gridItem.addClass(gridItemOpenClass);

        const gridItemOffset = $gridItem.offset().top; //get the offset for this

        //get the hidden div containing the markup for the expanded section and clone it
        //add a data attribute pointing to the original article
        const $clonedMoreInfoSection = $gridItem.find(gridMoreInfo)
            .clone()
            .addClass('not-loaded')
            .css('height', '0')
            .data('id', id);
        //select all article after this article
        const $nextGridItems = $gridItem.nextAll(gridItem);
        let found = false;

        //Look through each gridItem and find the first one that is on the next row
        //and insert cloned expanded section right before
        $nextGridItems.each(function (i, article) {
            const $article = $(article);
            if (gridItemOffset < $article.offset().top){
                $article.before($clonedMoreInfoSection);
                found = true;
                placeDownSection($clonedMoreInfoSection);
                return false;
            }
        });
        if(!found) { //on last row, append after last article
            $(gridItem).last().after($clonedMoreInfoSection);
            placeDownSection($clonedMoreInfoSection);
        }
    }

    /**
     * After the $el is put in the DOM, this finalizes that by loading any lazy images and then
     * expanding section
     *
     * @param $el
     */
    function placeDownSection($el) {
        themeJs.loadSection($el, function() {
            themeJs.scrollToSection($el);
            themeJs.expandSection($el[0]);
        });
    }

    $(document).ready(function () {


// on click of More Portfolios
        // ajax request for next page
//-- pagination within the acf field is not going to work very well.  We are not going to know if the order in the JSON is the same
        // as original request here.  Also, if a page has multiple portfolio grids, there is no way to differentiate them
        // in the REST JSON

// on click of Portfolio-grid-feature -
        // determine location to put extra info
        // move portfolio-grid-more-info section to that location
            // create close button - changes toggleCollapse
            // listen for button on screenshots to load via ajax

        console.log('section-portfolio-grid script');

        $(parent).on('click', gridThumbnail, function (e) {
            console.log('click');
            e.preventDefault();
            let $this = $(this);

            // if expanded-grid-item id matches this id, close this gridItem, then done
            // If not, close existing expanded grid-item, then expand this one.

            //Expand this grid item
            let $gridItem = $this.closest(gridItem);


            openGridItem($gridItem);




        });
    })
})(jQuery);