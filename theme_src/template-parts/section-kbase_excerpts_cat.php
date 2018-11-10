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

$title    = get_field( 'title' );
$category = get_field( 'category' );

add_filter( 'wp_get_attachment_image_attributes', 'venus_image_markup_lazy', 10, 2 );

$args = [
        'posts_per_page' => -1,
];
?>

    <section <?php echo $attributes; ?>>
        <div class="wrap">
            <h2><?php echo wp_kses_post($title); ?></h2>

            <?php echo venus_get_kbase( $category ); ?>

			<?php echo do_shortcode( wpautop( wp_kses_post( $content ) ) ); ?>
        </div>
    </section>

<?php


	remove_filter( 'wp_get_attachment_image_attributes', 'venus_image_markup_lazy', 10, 2 );

	function venus_get_kbase( $cat=false ) {
	    $return = '';
	    if($cat) {


        }

	    return $return;
    }
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