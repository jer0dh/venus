<?php

/**
 * Thinking that this will cause google to think we have many pages with the same information.  Maybe we use a redirect
 * so this will always be the home page.  Use the following to pass in a GET variable so we know what page the user was
 * trying for and scroll to that section:
 *
wp_redirect( esc_url( add_query_arg( 'variable_to_send', '1', home_url() ) ) );
exit;
}

 */
/**
 * Or maybe skip all this and just use ACF and sections on the front page
 */

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'venus_multipage_custom_loop' );
add_action( 'genesis_before_loop', 'venus_add_page_info' );
//add_action('wp_head', 'venus_add_multipage_js');


function venus_add_multipage_js() {

	wp_enqueue_script( 'multipage', get_stylesheet_directory_uri() . '/js/multipage.js', array('jquery'),null, true);
}

function venus_add_page_info() {
	global $venus_test;


	$var = get_queried_object();

	$id = sanitize_title_with_dashes( $var->post_title );

	wp_localize_script( 'multipage', 'wp_multipage', array( 'id' => $id ) );

	//var_dump($venus_test);
}


add_filter( 'genesis_attr_entry', 'custom_attributes_content' );

function custom_attributes_content( $attributes ) {

	$attributes['id'] = sanitize_title_with_dashes( get_the_title() );

	return $attributes;
}

genesis();