<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


//* Start the Genesis engine
include_once( get_template_directory() . '/lib/init.php' );

// Child theme definitions
define( 'CHILD_THEME_NAME', '<%= pkg.templateName %>' );
define( 'CHILD_THEME_URL', '<%= pkg.templateUri %>' );
define( 'CHILD_THEME_VERSION', '<%= pkg.version %>' );


// Remove genesis style.css - only theme info
remove_action( 'genesis_meta', 'genesis_load_stylesheet' );

// ENQUEUE Scripts and Styles used throughout.  REGISTER Scripts and Styles that MIGHT be loaded depending on page template.
// The page template will enqueue registered scripts only if they are needed
add_action( 'wp_enqueue_scripts', 'gtl_enqueue_scripts_styles' );

define( 'GS_MAIN_SCRIPT', 'gs_scripts' );

define( 'VENUS_JS_SUFFIX', ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '<% if(production){%>.min<% } %>');

function gtl_enqueue_scripts_styles() {

	$version = wp_get_theme()->Version;

	wp_enqueue_style( 'dashicons' );


	wp_enqueue_style( 'gtl_css', get_stylesheet_directory_uri() . '<% if(production){%>/css/style.min.css<% } else { %>/css/style.css<% } %>', array(), $version );

	wp_enqueue_style( 'google-font', '//fonts.googleapis.com/css?family=Inconsolata:400,700|Muli:400,700&display=swap', array() );

	//$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '<% if(production){%>.min<% } %>';  //Gulptask in dev environment will put in '.min' if dev is set for production
	$suffix = VENUS_JS_SUFFIX;
	wp_enqueue_script( GS_MAIN_SCRIPT, get_stylesheet_directory_uri() . "/js/scripts{$suffix}.js", array( 'jquery' ), $version, true );


	wp_enqueue_script( 'genesis-responsive-menu', get_stylesheet_directory_uri() . "/js/responsive-menu{$suffix}.js", array( 'jquery' ), $version, true );
	wp_localize_script(
		'genesis-responsive-menu',
		'genesis_responsive_menu',
		genesis_sample_responsive_menu_settings()
	);

}

// Update CSS within in Admin
function gtl_admin_style() {
	$version = wp_get_theme()->Version;
	wp_enqueue_style( 'admin-styles', get_stylesheet_directory_uri() . '/css/admin-style.min.css', array(), $version );
}

//add_action( 'admin_enqueue_scripts', 'gtl_admin_style' );

// Define our responsive menu settings.
function genesis_sample_responsive_menu_settings() {

	$settings = array(
		'mainMenu'          => __( '', 'genesis-sample' ),
		'menuIconClass'     => 'dashicons-before dashicons-menu',
		'subMenu'           => __( 'Submenu', 'genesis-sample' ),
		'subMenuIconsClass' => 'dashicons-before dashicons-arrow-down-alt2',
		'menuClasses'       => array(
			'combine' => array(
				'.nav-primary'
			),
			'others'  => array( '.nav-secondary' ),
		),
	);

	return $settings;
}

// Define a wp_local javascript variable.  Add right before the wp_print_footer_scripts is run

add_action( 'wp_footer', 'venus_add_localized_js_data', 19);
/**
 * Creates a local variable to contain data for javascripts
 *
 * Contains Filter 'venus_localized_js_data' that can be accessed elsewhere to add data to this array
 *
 */
function venus_add_localized_js_data() {
	$wp_local = [
		'restApi'  => esc_url(get_rest_url(null, '/wp/v2/'))
		];

	$wp_local = apply_filters( 'venus_localized_js_data', $wp_local );

	wp_localize_script(
		GS_MAIN_SCRIPT,
		'wpLocal',
		$wp_local
	);

}

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

//* Add Accessibility support
add_theme_support( 'genesis-accessibility', array(
	'headings',
	'drop-down-menu',
	'search-form',
	'skip-links',
	'rems'
) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

add_theme_support( 'header-image' );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for post-thumbnails
add_theme_support( 'post-thumbnails' );

//* Gutenberg wide images support - still need to add css to support

add_theme_support( 'align-wide' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );


// change default footer and add ACF fields that should be configured in theme settings

 # Footer
remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', 'gtl_do_footer' );

function gtl_do_footer(){

/*	$before_year = get_the_field_without_wpautop( 'footer_before', 'options');
	$after_year = get_the_field_without_wpautop( 'footer_after', 'options');
	$login_url = get_home_url() . '/login';*/

	echo '<div class="footer-copyright"><div class="wrap">' . do_shortcode('[footer_copyright ] ') . '</div></div>';

}



add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'menu-primary',
	'menu-secondary',
	//'site-inner',
	'footer-widgets',
	'footer',
) );

/*
/* add sidebar
genesis_register_sidebar( array(
	'id'        => 'sidebar-name',
	'name'      => 'Sidebar Name',
	'description'   => 'This is a sidebar that will appear ...',
) );
 */

/* Remove genesis layouts.  Leave full-width-content and content-sidebar.
*/
//* Remove sidebar/content layout
genesis_unregister_layout( 'sidebar-content' );

//* Remove content/sidebar/sidebar layout
genesis_unregister_layout( 'content-sidebar-sidebar' );

//* Remove sidebar/sidebar/content layout
genesis_unregister_layout( 'sidebar-sidebar-content' );

//* Remove sidebar/content/sidebar layout
genesis_unregister_layout( 'sidebar-content-sidebar' );

//* Set full-width content as the default layout
genesis_set_default_layout( 'full-width-content' );

//* Moving Nav to header
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav', 11 );

//Images
add_image_size('portfolio_grid', 450, 350, ['top', 'center']);

//* Add acf
include_once get_stylesheet_directory() . '/libs/acf.php';

//* Add helpers
include_once get_stylesheet_directory() . '/libs/multipage.php';


//* Add Portfolio CPT
include_once get_stylesheet_directory() . '/libs/venus_portfolio_cpt.php';


//* Add Helper Functions
include_once get_stylesheet_directory() . '/libs/helpers.php';


//* Add Image helper Functions
include_once get_stylesheet_directory() . '/libs/image_functions.php';

//* Add Bootstrap structural wrap
include_once get_stylesheet_directory() . '/libs/add_bootstrap_structural_wrap.php';


//add_action('genesis_before_loop', 'venus_test');
function venus_test() {
	global $wp_query;
		var_dump($wp_query);
}

add_action( 'wp_head', 'studiopress_code');
function studiopress_code() {
	echo '<!-- ' . 'B4E9769D-B932-4C55-BA24-4F66EAA5CF62' . ' -->';
}
