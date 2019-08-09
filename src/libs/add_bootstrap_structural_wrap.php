<?php
if( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'init', 'venus_init_structural_wrap');

/**
 * Defines where structural wraps will occur in theme and adds filter to each to change markup to bootstrap wrap
 *
 *
 */
function venus_init_structural_wrap() {

	$structural_wraps = array(
		'header',
		'menu-secondary',
		'footer-widgets',
		'footer'
	);

	$structural_wraps_with_col12 = array(
		'header'
	);

	add_theme_support( 'genesis-structural-wraps', $structural_wraps );

	foreach( $structural_wraps as $wrap ) {
		add_filter(  "genesis_structural_wrap-{$wrap}", 'venus_structural_wrap', 15, 2 );
	}

	foreach( $structural_wraps_with_col12 as $wrap ) {
		add_filter( "genesis_structural_wrap-{$wrap}", 'venus_structural_wrap_col12', 16, 2);
	}
}

/**
 * Adds opening or closing bootstrap wrap depending on $original_output
 *
 * @param $output
 * @param $original_output
 *
 * @return string
 */
function venus_structural_wrap( $output, $original_output ) {

	switch ( $original_output ) {
		case 'open':
			$output = '<div class="container"><div class="row">';
			break;
		case 'close':
			$output = '</div></div>';
			break;
	}

	return $output;
}
/**
 * Adds col12 bootstrap wrap depending on $original_output
 *
 * @param $output
 * @param $original_output
 *
 * @return string
 */
function venus_structural_wrap_col12( $output, $original_output ) {

	switch ( $original_output ) {
		case 'open':
			$output .= '<div class="col-12">';
			break;
		case 'close':
			$output .= '</div>';
			break;
	}

	return $output;
}

/**
 * For action hooks
 */

function venus_add_structural_wrap_open() {
	echo venus_structural_wrap('', 'open');
}

function venus_add_structural_wrap_close() {
	echo venus_structural_wrap('', 'close');
}

function venus_add_structural_wrap_col12_open() {
	venus_add_structural_wrap_open();
	echo '<div class="col-12">';
}

function venus_add_structural_wrap_col12_close() {
	echo '</div>';
	venus_add_structural_wrap_close();
}
