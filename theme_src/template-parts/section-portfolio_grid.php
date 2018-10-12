<?php

// Gotta keep track how many times this appears on a single page.
// Don't add scripts and js templates if more than once

global $venus_portfolio_grid_instance;

if ( ! isset( $venus_portfolio_grid_instance ) ) {
	$venus_portfolio_grid_instance = 1;
}

$id = get_sub_field( 'id' );

$classes = get_sub_field( 'additional_classes' );

$classes = ( $classes ) ? $classes : '';

$classes .= ' banner section-portfolio-grid';

$attributes = '';
if ( $id ) {
	$attributes .= ' id="' . esc_attr( $id ) . '" ';
}

$attributes .= ' class="' . esc_attr( $classes ) . '"';

$portfolios = get_sub_field( 'portfolios' );

add_filter( 'wp_get_attachment_image_attributes', 'venus_image_markup_lazy', 10, 2 );

if ( ! function_exists( 'venus_get_categories' ) ) {
	function venus_get_categories( $categories = false, $sep = ', ' ) {
		$return = '';
		if ( false !== $categories && is_array( $categories ) ) {
			foreach ( $categories as $category ) {

				$return .= $category->name . $sep;
			}
		}

		return rtrim( $return, $sep );
	}
}
?>

    <section <?php echo $attributes; ?>>
        <div class="wrap">
            <div class="portfolio-grid row">

				<?php

				foreach (
					$portfolios

					as $portfolio
				):

					$id = $portfolio->ID;
					$date_published = get_field( 'date_published', $id );
					$category = get_field( 'category', $id );
					$company = get_field( 'company', $id );
					$external_url = get_field( 'external_url', $id );
					$screenshots = get_field( 'screenshots', $id );

					$content = $portfolio->post_content;
					$content = apply_filters( 'the_content', $content );
					$content = str_replace( ']]>', ']]&gt;', $content );
					?>

                    <div class="portfolio-grid-item col-md-4" data-id="<?php echo intval( $id ); ?>">
                        <div class="portfolio-grid-feature not-loaded">
                            <noscript>
                                <img src="<?php echo wp_get_attachment_image_url( get_post_thumbnail_id( $id ), 'medium' ); ?>"
                                     alt/>
                            </noscript>
							<?php echo wp_get_attachment_image( get_post_thumbnail_id( $id ), 'portfolio_grid' ); ?>
                            <div class="portfolio-grid-title">
                                <h3><?php echo get_the_title( $id ); ?></h3>
                            </div>
                        </div>
                        <div class="portfolio-grid-more-info">
							<?php echo wp_get_attachment_image( get_post_thumbnail_id( $id ), 'portfolio_grid' ); ?>
                            <div class="portfolio-grid-meta">
								<?php if ( $category ): ?>
                                    <div>
                                        Type: <?php echo wp_kses_post( venus_get_categories( $category ) ); ?>
                                    </div>
								<?php endif; ?>
								<?php if ( $external_url ): ?>
                                    <div>
                                        URL:
                                        <a href="<?php echo esc_url( $external_url ); ?>"><?php echo esc_url( $external_url ); ?></a>
                                    </div>
								<?php endif; ?>
								<?php if ( $company ): ?>
                                    <div>
                                        Company: <?php echo wp_kses_post( $company ); ?>
                                    </div>
								<? endif; ?>
                            </div>
                            <div class="portfolio-grid-content">
								<?php echo wp_kses_post( $content ); ?>
                            </div>
                            <div class="row portfolio-grid-screenshots">
								<?php if ( $screenshots ):  // Using animateHeight.js to collapse screenshot container?>
                                    <button class="btn btn-primary"
                                            data-collapse="#portfolio_grid_<?php echo intval( $id ); ?>"
                                            data-open-text="Close Screenshots" data-closed-text="More Screenshots">More
                                        Screenshots
                                    </button>
								<?php endif; ?>
                            </div>
                        </div>

                    </div>

				<?php endforeach; ?>
            </div><!-- row -->
        </div>
    </section>

<?php
remove_filter( 'wp_get_attachment_image_attributes', 'venus_image_markup_lazy', 10, 2 );

// We only want the script to appear once on the page
if ( $venus_portfolio_grid_instance === 1 ) {
	echo '<script type="application/javascript">';
	include_once( get_stylesheet_directory() . '/template-parts/section-portfolio_grid' . VENUS_JS_SUFFIX . '.js' );
	echo '</script>';

	echo '<script type="text/template" id="portfolio_grid_template">';
//	include( get_stylesheet_directory() . '/template-parts/section-portfolio_grid.mustache' );
	echo '</script>';


}
$venus_portfolio_grid_instance += 1;

/*

get_sub_field will run wpautop on the wysiwyg field.  Using custom function this
wpautop is not run.

With wpautop and shortcodes in content, finding wp is adding empty <p></p> where it shouldn't.
If there was an unordered list there were <p></p> before and after the <ul></ul>.

Do_shortcode is also run by get_sub_field.

Still want wpautop for those that use visual tab.  So running it after.

Finding the results of the shortcode going through the wpautop will also add p tags between
certain tags.  If I used div for the icon as opposed to a span.  With the div an extra p
tag was added.  Using h3 instead of span also caused extra p tag.

*/