<?php


add_action( 'genesis_before_loop', 'venus_add_wrap_open', 6 );
add_action( 'genesis_after_loop', 'venus_add_wrap_close', 20 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

add_action( 'genesis_entry_footer', 'venus_add_categories');

genesis();