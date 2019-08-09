<?php

$id = get_sub_field( 'id' );

$content = get_sub_field( 'content', false );

$classes = get_sub_field( 'additional_classes' );

$classes = ( $classes ) ? $classes : '';

$classes .= ' banner not-loaded section-kbase-excerpts-cat';

$attributes = '';
if ( $id ) {
	$attributes .= ' id="' . esc_attr( $id ) . '" ';
}

$attributes .= ' class="' . esc_attr( $classes ) . '"';

$title    = get_sub_field( 'title' );
$category = get_sub_field( 'category' );
$page = 1;
$maximum_posts = get_sub_field('maximum_posts') ? get_sub_field('maximum_posts') : 5;

add_filter( 'wp_get_attachment_image_attributes', 'venus_image_markup_lazy', 10, 2 );

$args = [
        'posts_per_page' => -1,
];
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

			if ( $cquery->have_posts() ) :

				while ( $cquery->have_posts() ) :
					$cquery->the_post();
					?>

            <div class="row ">
                <div class="col-md-3">
                    <a href="<?php the_permalink(); ?>">
                    <?php echo wp_get_attachment_image( venus_get_image_id(get_the_ID()), 'full')?>
                    </a>
                </div>
                <div class="col-md-9">
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div><?php the_excerpt(); ?></div>
                </div>
            </div>
				<?php endwhile; ?>
			<?php endif;

			wp_reset_postdata(); ?>

			<?php echo do_shortcode( wpautop( wp_kses_post( $content ) ) ); ?>
        </div>
    </section>

<?php


remove_filter( 'wp_get_attachment_image_attributes', 'venus_image_markup_lazy', 10, 2 );

