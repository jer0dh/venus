<?php

$archive_category = get_field('archive_category', 'options');

if( $archive_category ) {
	if ( ! is_category( $archive_category ) ) {
		wp_redirect( get_category_link( $archive_category ) );
		exit();
	}
}



genesis();

