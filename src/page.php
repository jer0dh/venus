<?php

//* add wrap around page titles (entry-header)
add_action( 'genesis_entry_header', 'venus_add_wrap_open', 6 );
add_action( 'genesis_entry_header', 'venus_add_wrap_close', 14 );


remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
add_action( 'genesis_entry_content', 'venus_do_page_content' );




genesis();