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
		$attributes['src']      = 'data:image/svg+xml,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22/%3E'; //could add default small image or a base64 encoded small image here
	}
	if ( isset( $attributes['srcset'] ) ) {
		$attributes['data-srcset'] = $attributes['srcset'];
		$attributes['srcset']      = '';
	}

	$attributes['class'] .= ' lazy-image';

	return $attributes;

}


function venus_get_image_id( $id = null ) {
	$return = false;
	if( null !== $id ) {
		$return = get_post_thumbnail_id( $id );
		if( '' === $return ) {
			$return = venus_get_default_image_id( $id );
		}
	}
	return $return;
}

/**
 *
 * Returns an image id based on the post $id's categories
 *
 * @param null $id
 * @param string $size
 *
 * @return mixed|null|string
 */
function venus_get_default_image_id( $id = null ) {

	$return = false;

	if( null !== $id ) {
		$return           = false;
		$defaultImages = get_field( 'default_images', 'options' );

		$catDefaultImages = array();
		foreach($defaultImages as $item) {
			$catDefaultImages[$item['category']] = $item['image'];
		}
		//var_dump($catDefaultImages);

		/*array(
		'JavaScript' => 'javaScript.jpg',
		'PHP' => 'php.jpg',
		'CSS' => 'css.jpg',
		'Exchange' => 'exchangeDefault.jpg',
		'Wordpress' => 'wordpress.jpg',
		'Genesis' => 'genesis.jpg');*/

		$catDefault = array_keys($catDefaultImages);


		$objCategories = get_the_category();

		$categories = array();
		foreach ( $objCategories as $obj ) {
			$categories[] = $obj->term_id;
		}

		$intersect = array_intersect( $categories, $catDefault );

		foreach ( $intersect as $cat ) {
			$return = $catDefaultImages[ $cat ];
		}
		if ( false === $return ) {
			$return = get_field( 'default_image', 'options' ) ? get_field( 'default_image', 'options' ) : false;
		}
	}
	return $return;
}