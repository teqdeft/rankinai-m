<?php
/**
 * The home page.
 *
 * The same sections loop as page.php. If the home page has no sections yet
 * (a fresh install, before the seed has run) it prints the eight home
 * sections with every field empty, which is the original home page.
 */
get_header();

if ( function_exists( 'have_rows' ) && have_rows( 'sections' ) ) {
	while ( have_rows( 'sections' ) ) {
		the_row();
		get_template_part( 'template-parts/sections/section', get_row_layout() );
	}
} else {
	foreach ( rankinai_seed_pages()['home']['sections'] as $layout ) {
		get_template_part( 'template-parts/sections/section', $layout );
	}
}

get_footer();
