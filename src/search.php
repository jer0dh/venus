<?php

add_filter( 'genesis_link_post_title', '__return_true');

add_action( 'genesis_before_loop', 'venus_add_wrap_open', 6 );
add_action( 'genesis_after_loop', 'venus_add_wrap_close', 20 );
add_filter( 'genesis_link_post_title', '__return_true',20);

genesis();
