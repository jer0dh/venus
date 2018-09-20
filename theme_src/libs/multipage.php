<?php

//TODO add edit link to each page - edit_post_link()


function venus_multipage_custom_loop() {

	global $wp_query;

	// Get a list of pages to show from multipage menu
	$pages = wp_get_nav_menu_items( 'multipage' );

	if ( $pages ) {
		$ids = array_map( function ( $a ) {

			return $a->object_id;
		}, $pages );

		$wp_query = new WP_Query( array( 'post_type' => 'page', 'post__in' => $ids, 'orderby' => 'post__in' ) );

	}
	genesis_standard_loop();

	wp_reset_query();

}

add_filter( 'wp_nav_menu_objects', 'venus_multipage_menu_links', 10, 2 );
/**
 * This will check if on front page and multipage menu exists, if so, if we are setting up a menu with a theme_location set, go through the URLs in the menu and
 * change the urls matching the urls in multipage menu to just the #title for the link.
 *
 * @param $menus
 * @param $args
 *
 * @return mixed
 */
function venus_multipage_menu_links( $menus, $args ) {

	$multipages_menu = wp_get_nav_menu_items( 'multipage' );

	if ( is_front_page() && isset( $args ) && $multipages_menu ) {

		if ( isset( $args->theme_location ) || $args->theme_location !== '' ) {


			$multipages_urls = array_map( function ( $menu ) {
				return $menu->url;
			}, $multipages_menu );


			foreach ( $menus as &$menu ) {
				if ( in_array( $menu->url, $multipages_urls ) ) {
					$page = get_post( $menu->object_id );
					if ( $page !== null ) {

						$menu->url = '#' . sanitize_title_with_dashes( $page->post_title );

					}
				}
			}
		}

	}

	return $menus;
}