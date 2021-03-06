<?php

// Gotta keep track how many times this appears on a single page.
// Don't add scripts and js templates if more than once

global $venus_portfolio_feature_instance;

if ( ! isset( $venus_portfolio_feature_instance ) ) {
	$venus_portfolio_feature_instance = 1;
}

$id = get_sub_field( 'id' );

$classes = get_sub_field( 'additional_classes' );

$classes = ( $classes ) ? $classes : '';

$classes .= ' banner not-loaded section-portfolio-feature';

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
			<?php

			foreach ( $portfolios as $portfolio ):

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
                <div class="portfolio-feature" data-id="<?php echo intval( $id ); ?>">
                    <div class="row">
                        <div class="portfolio-feature-feature col-md-4 order-0">
                            <noscript>
                                <img src="<?php echo wp_get_attachment_image_url( get_post_thumbnail_id( $id ), 'medium' ); ?>"
                                     alt/>
                            </noscript>
							<?php echo wp_get_attachment_image( get_post_thumbnail_id( $id ), 'full' ); ?>

                        </div>
                        <div class="portfolio-feature-desc col-md-5 order-3 order-md-1">
                            <h3><?php echo get_the_title( $id ); ?></h3>
                            <div>
								<?php echo $content; ?>
                            </div>

                        </div>

                        <div class="portfolio-feature-meta col-md-3 order-1 order-md-2 mb-4">
                            <div>
								<?php if ( $category ): ?>
                                    <div>
                                        <strong>Type:</strong> <?php echo wp_kses_post( venus_get_categories( $category ) ); ?>
                                    </div>
								<?php endif; ?>
								<?php if ( $external_url ): ?>
                                    <div>
                                        <strong>URL:</strong>
                                        <a href="<?php echo esc_url( $external_url ); ?>"><?php echo esc_url( $external_url ); ?></a>
                                    </div>
								<?php endif; ?>
								<?php if ( $company ): ?>
                                    <div>
                                        <strong>Company:</strong> <?php echo wp_kses_post( $company ); ?>
                                    </div>
								<?php endif; ?>
                            </div>
                        </div>

                    </div><!-- row -->
	                <?php if ( $screenshots ):  // Using animateHeight.js to collapse screenshot container?>

                    <div class="row justify-content-center portfolio-feature-screenshots">
                        <div class="col-12 mb-3 text-center">
                            <button class="btn btn-primary"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#portfolio_feature_<?php echo intval( $id ); ?>"
                                    data-open-text="Close Screenshots" data-closed-text="More Screenshots"
                                    aria-expanded="false" aria-controls="portfolio_feature_<?php echo intval( $id ); ?>">More
                                Screenshots
                            </button>
                        </div>
                        <div class="col-12">
                            <div class="portfolio-feature-slides collapse slides-not-loaded" id="portfolio_feature_<?php echo intval( $id );?>"
                            data-id="<?php echo intval( $id ); ?>"></div>
                        </div>
                    </div>
	                <?php endif; ?>
                </div>
			<?php endforeach; ?>

        </div>
    </section>

<?php
remove_filter( 'wp_get_attachment_image_attributes', 'venus_image_markup_lazy', 10, 2 );

// We only want the script to appear once on the page
if ( $venus_portfolio_feature_instance === 1 ) {
	echo '<script type="application/javascript">';
	include_once( get_stylesheet_directory() . '/template-parts/section-portfolio_feature' . VENUS_JS_SUFFIX . '.js' );
	echo '</script>';

	echo '<script type="text/template" id="portfolio_features_template">';
	include( get_stylesheet_directory() . '/template-parts/section-portfolio_feature.mustache' );
	echo '</script>';


}
$venus_portfolio_feature_instance += 1;

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
