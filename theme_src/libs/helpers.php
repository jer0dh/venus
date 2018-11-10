<?php


// Used to load Sections into page
function venus_do_sections() {

	get_template_part( 'template-parts/sections' );
}

function venus_do_page_content() {

	venus_do_sections();
	echo '<section class="banner not-loaded">';
	venus_add_wrap_open();
	the_content();
	venus_add_wrap_close();
	echo '</section>';
}

function venus_add_wrap_open() {
	?>
    <div class="wrap">
	<?php
}

function venus_add_wrap_close() {
	?>
    </div><!-- wrap -->
	<?php
}

//Remove default 'You are here' prefix and 'Archives for'
add_filter( 'genesis_breadcrumb_args', 'venus_breadcrumbs_args' );
function venus_breadcrumbs_args( $args ) {


	$args['labels']['prefix']   = '';
	$args['labels']['category'] = '';

	return $args;
}

// Change category crumbs to always be the default category link
add_filter( 'genesis_post_crumb', 'venus_post_crumb' );
function venus_post_crumb( $crumb ) {
	$archive_category = get_field( 'archive_category', 'options' );
	if ( $archive_category ) {
		$term = get_term( $archive_category );
		if ( ! is_wp_error( $term ) ) {
			$crumb = sprintf( '<a href="%s">%s</a>', esc_url( get_category_link( $term->term_id ) ), esc_textarea( $term->name ) );
		}
	}

	return $crumb;
}

/**
 * prints out a list of categories the post belongs to
 */
function venus_add_categories() {
	$cats = get_the_category();
	?>
    <ul class="post-categories">
		<?php foreach ( $cats as $cat ) : ?>
            <li class="btn btn-info"><?php echo $cat->name; ?></li>

		<?php endforeach; ?>
    </ul>
	<?php
}

//* Customize search form input box text
add_filter( 'genesis_search_text', 'jhts_search_text' );
function jhts_search_text( $text ) {
	return esc_attr( 'Search this knowledgebase...' );
}

//* Tell search to only search in default archive category
add_action( 'pre_get_posts', 'venus_set_archive_category' );

function venus_set_archive_category($query) {

	if( !is_admin() && is_search() && $query->is_main_query() ) {
		$cat = get_field('archive_category', 'options');
		if($cat){
			$query->set('cat', intval($cat));
		}
	}
	return $query;
}


function venus_remove_all_post_actions() {
	remove_action( 'genesis_entry_header', 'genesis_do_post_format_image', 4 );
	remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
	remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
	remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
	remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

	remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
	remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
	remove_action( 'genesis_entry_content', 'genesis_do_post_content_nav', 12 );
	remove_action( 'genesis_entry_content', 'genesis_do_post_permalink', 14 );

	remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
	remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );
	remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

	remove_action( 'genesis_after_entry', 'genesis_do_author_box_single', 8 );
	remove_action( 'genesis_after_entry', 'genesis_adjacent_entry_nav' );
	remove_action( 'genesis_after_entry', 'genesis_get_comments_template' );
}