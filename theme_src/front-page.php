<?php


/* We want full page content, not excerpt */
remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
add_action( 'genesis_entry_content', 'venus_do_page_content' );
function venus_do_page_content() {
	the_content();
}

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'venus_multipage_custom_loop' );


/**
 * Add the id to the article tag
 */

add_filter( 'genesis_attr_entry', 'custom_attributes_content' );
function custom_attributes_content( $attributes ) {

	$attributes['id'] = sanitize_title_with_dashes( get_the_title() );

	return $attributes;
}

genesis();