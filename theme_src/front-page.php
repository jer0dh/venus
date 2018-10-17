<?php



remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'venus_multipage_custom_loop' );



genesis();