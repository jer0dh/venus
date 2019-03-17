<?php


$archive_category = get_field( 'archive_category', 'options' );

if ( $archive_category ) {
	if ( ! is_category( $archive_category ) ) {
		wp_redirect( get_category_link( $archive_category ) );
		exit();
	}
}

add_action( 'genesis_before_loop', 'venus_add_wrap_open', 6 );
add_action( 'genesis_after_loop', 'venus_add_wrap_close', 20 );

venus_remove_all_post_actions();

add_action( 'genesis_entry_header', 'venus_do_post_image', 2 );
add_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
add_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
add_action( 'genesis_entry_header', 'venus_do_post_title' );
add_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
add_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

function venus_do_post_image() {
	add_filter( 'wp_get_attachment_image_attributes', 'venus_image_markup_lazy', 10, 2 );

	echo '<a class="not-loaded" href="' . get_the_permalink() . '">';
	echo wp_get_attachment_image( venus_get_image_id( get_the_ID() ), 'full' );
	echo '</a>';
	remove_filter( 'wp_get_attachment_image_attributes', 'venus_image_markup_lazy', 10, 2 );

}

add_action( 'genesis_entry_footer', 'venus_add_categories' );

add_action( 'genesis_before_while', 'venus_archive_row_open');
function venus_archive_row_open(){
    echo '<div class="row">';
}
add_action( 'genesis_after_endwhile', 'venus_archive_row_close', 2);
function venus_archive_row_close(){
    echo '</div><!-- row -->';
}
add_filter( 'genesis_attr_entry', 'venus_add_article_class');
function venus_add_article_class($att) {
    $att['class'] .= ' col-md-3';
    return $att;
}

function venus_do_post_title() {

	$title = apply_filters( 'genesis_post_title_text', get_the_title() );

	if ( '' === trim( $title ) ) {
		return;
	}

	// Link it, if necessary.

		$title = genesis_markup( array(
			'open'    => '<a %s>',
			'close'   => '</a>',
			'content' => $title,
			'context' => 'entry-title-link',
			'echo'    => false,
		) );

	// Wrap in H1 on singular pages.
	$wrap = is_singular() ? 'h1' : 'h2';

	// Also, if HTML5 with semantic headings, wrap in H1.
	$wrap = genesis_html5() && genesis_get_seo_option( 'semantic_headings' ) ? 'h1' : $wrap;

	/**
	 * Entry title wrapping element.
	 *
	 * The wrapping element for the entry title.
	 *
	 * @since 2.2.3
	 *
	 * @param string $wrap The wrapping element (h1, h2, p, etc.).
	 */
	$wrap = apply_filters( 'genesis_entry_title_wrap', $wrap );

	// Build the output.
	$output = genesis_markup( array(
		'open'    => "<{$wrap} %s>",
		'close'   => "</{$wrap}>",
		'content' => $title,
		'context' => 'entry-title',
		'params'  => array(
			'wrap' => $wrap,
		),
		'echo'    => false,
	) );

	echo apply_filters( 'genesis_post_title_output', $output, $wrap, $title ) . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

}

genesis();

