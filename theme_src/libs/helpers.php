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
