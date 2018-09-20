<?php
/*
 * Template Name: Multipage
 */

//include get_stylesheet_directory() . '/front-page.php';

$var = get_queried_object();

wp_redirect( esc_url( home_url() . '#' . sanitize_title_with_dashes($var->post_title) ) );
exit;

//add_query_arg( 'location', sanitize_title_with_dashes($var->post_title), home_url() )