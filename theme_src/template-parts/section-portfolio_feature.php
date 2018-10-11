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
                <div class="row">
                    <div class="portfolio-feature-feature col-md-4 order-0">
                        <noscript>
                            <img src="<?php echo wp_get_attachment_image_url( get_post_thumbnail_id( $id ), 'medium' ); ?>"
                                 alt/>
                        </noscript>
						<?php echo wp_get_attachment_image( get_post_thumbnail_id( $id ), 'full' ); ?>

                    </div>
                    <div class="portfolio-feature-desc col-md-4 order-3 order-md-1">
                        <h3><?php echo get_the_title( $id ); ?></h3>
                        <div>
							<?php echo $content; ?>
                        </div>

                    </div>

                    <div class="portfolio-feature-meta col-md-4 order-1 order-md-2">
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

                </div><!-- row -->
                <div class="row portfolio-feature-screenshots">
					<?php if ( $screenshots ): ?>
                        <button class="btn btn-primary">More Screenshots</button>
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