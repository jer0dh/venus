<?php

$id = get_sub_field( 'id' );

$content = get_sub_field( 'content', false );

$classes = get_sub_field( 'additional_classes' );

$classes = ( $classes ) ? $classes : '';

$classes .= ' banner not-loaded section-kbase-grid-cat';

$attributes = '';
if ( $id ) {
	$attributes .= ' id="' . esc_attr( $id ) . '" ';
}

$attributes .= ' class="' . esc_attr( $classes ) . '"';

$title         = get_sub_field( 'title' );
$category      = get_sub_field( 'category' );
$page          = 1;
$maximum_posts = get_sub_field( 'maximum_posts' ) ? get_sub_field( 'maximum_posts' ) : get_option( 'posts_per_page' );

add_filter( 'wp_get_attachment_image_attributes', 'venus_image_markup_lazy', 10, 2 );

?>

    <section <?php echo $attributes; ?>>
        <div class="wrap">
            <h2><?php echo wp_kses_post( $title ); ?></h2>

			<?php
			$tax = array();
			if ( ! empty( $category ) ) {
				foreach ( $category as $item ) {
					$tax[] = array(
						'taxonomy' => 'category',
						'field'    => 'term_id',
						'terms'    => $item
					);
				}
			}

			$args = [ 'post_type' => 'post', 'paged' => 1, 'posts_per_page' => $maximum_posts, 'tax_query' => $tax ];

			$cquery = new WP_Query( $args );

			if ( $cquery->have_posts() ) : ?>
                <div class="row ">
					<?php while ( $cquery->have_posts() ) :
						$cquery->the_post();
						?>

                        <div class="col-sm-6 col-md-3 mb-4">
                            <a href="<?php the_permalink(); ?>">
								<?php echo wp_get_attachment_image( venus_get_image_id( get_the_ID() ), 'full' ) ?>
                            </a>
                            <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>

                        </div>


					<?php endwhile; ?>
                </div>
			<?php endif;

			wp_reset_postdata(); ?>

			<?php //echo do_shortcode( wpautop( wp_kses_post( $content ) ) ); ?>
        </div>
    </section>

<?php


remove_filter( 'wp_get_attachment_image_attributes', 'venus_image_markup_lazy', 10, 2 );

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
