<?php

// 	add_filter( 'wp_get_attachment_image_attributes', 'venus_image_markup_responsive_background',10,2 );

function venus_image_markup_responsive_background( $attributes, $image = null ) {

	if ( isset( $attributes['src'] ) ) {
		$attributes['data-src'] = $attributes['src'];
		$attributes['src']      = ''; //could add default small image or a base64 encoded small image here
	}
	if ( isset( $attributes['srcset'] ) ) {
		$attributes['data-srcset'] = $attributes['srcset'];
		$attributes['srcset']      = '';
	}
	if ( $image ) {
		$attributes['data-fallback'] = wp_get_attachment_image_url( $image->ID, 'large' );
	}

	return $attributes;

}

// 	add_filter( 'wp_get_attachment_image_attributes', 'venus_image_markup_lazy',10,2 );

function venus_image_markup_lazy( $attributes, $image = null ) {

	if ( isset( $attributes['src'] ) ) {
		$attributes['data-src'] = $attributes['src'];
		$attributes['src']      = ''; //could add default small image or a base64 encoded small image here
	}
	if ( isset( $attributes['srcset'] ) ) {
		$attributes['data-srcset'] = $attributes['srcset'];
		$attributes['srcset']      = '';
	}

	$attributes['class'] .= ' lazy-image';

	return $attributes;

}
