<?php

//TODO add edit link to each page - edit_post_link()

remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
add_action( 'genesis_entry_content', 'venus_do_page_content' );

function venus_do_page_content() {
	the_content();
}
function venus_multipage_custom_loop() {

	global $wp_query;

// Get a list of pages to show
	$pages = wp_get_nav_menu_items( 'multipage' );

	if( $pages ) {
		$ids = array_map( function ( $a ) {

			return $a->object_id;
		}, $pages );

		$wp_query = new WP_Query( array( 'post_type' => 'page', 'post__in' => $ids, 'orderby' => 'post__in' ) );

	}
		genesis_standard_loop();

		wp_reset_query();

}

add_filter('wp_nav_menu_objects', 'venus_multipage_menu_links', 10, 2);
global $venus_test;
function venus_multipage_menu_links( $menus, $args ) {
	global $venus_test;

	$multipages_menu = wp_get_nav_menu_items( 'multipage' );

	if ( isset( $args ) && $multipages_menu ) {

		if ( isset( $args->theme_location ) || $args->theme_location !== '' ) {


			$multipages_urls = array_map( function ( $menu ) {
				return $menu->url;
			}, $multipages_menu );


			foreach ( $menus as &$menu ) {
				if ( in_array( $menu->url, $multipages_urls ) ) {
					$page = get_post( $menu->object_id );
					if ( $page !== null ) {
						if ( is_front_page() ) {
							$menu->url = '#' . sanitize_title_with_dashes( $page->post_title );
/*						} else {
							$menu->url = home_url('#' . sanitize_title_with_dashes( $page->post_title )); */
						}
					}
				}
			}
		}

	}

	return $menus;
}