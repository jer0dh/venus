<?php


function venus_multipage_custom_loop() {

	global $wp_query;

// Get a list of pages to show
	$pages = wp_get_nav_menu_items( 'multipage' );

	$ids = array_map( function ( $a ) {

		return $a->object_id;
	}, $pages );

	$wp_query = new WP_Query( array( 'post_type' => 'page', 'post__in' => $ids, 'orderby' => 'post__in' ) );

	genesis_standard_loop();

	wp_reset_query();
}
