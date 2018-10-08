<?php

class Venus_Portfolio_Cpt {

	protected static $instance = null;

	public function __construct() {

		add_action( 'after_setup_theme', [ $this, 'initialize'] );

	}

	public function initialize() {

		if( ! class_exists('acf') ) {
			// show message to dashboard that ACF plugin is required
			add_action( 'admin_notices', [$this, 'plugin_ACF_not_loaded'] );
		} else {
			add_action( 'after_setup_theme', [ $this, 'register_post_type' ], 20 );
			add_action( 'after_setup_theme', [ $this, 'register_taxonomies' ], 20 );

		}

	}

	public function plugin_ACF_not_loaded() {
		printf( '<div class="error"><p>%s</p></div>', __( 'This theme REQUIRES the Advanced Custom Fields plugin and it is not loaded' ) );
	}


public function register_post_type() {
		register_post_type( 'venus_portfolio', [
			'label'     => 'Portfolio',
			'public'    => true,
			'menu_icon' => 'dashicons-awards',
			'supports'  => [ 'title', 'thumbnail', 'editor' ],
			'rewrite'   => [
				'slug'       => 'portfolio',
				'with_front' => false,
			],
		] );
	}

	public function register_taxonomies() {
		register_taxonomy( 'venus_port_tax', [ 'venus_portfolio' ], [
			'label'             => 'Categories',
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'meta_box_cb'       => false,
		] );
	}

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}

// Initiate the class
$venus_portfolio_cpt = Venus_Portfolio_Cpt::get_instance();

