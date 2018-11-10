<?php


add_action( 'genesis_before_loop', 'venus_add_wrap_open', 6 );
add_action( 'genesis_after_loop', 'venus_add_wrap_close', 20 );


genesis();