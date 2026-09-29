<?php
/**
 * A page: its sections, in order.
 *
 * Every page on the site is built this way. A page with no sections yet shows
 * its title and a line saying where to add them, as on Sokkies.
 */
get_header();

if ( function_exists( 'have_rows' ) && have_rows( 'sections' ) ) {
	while ( have_rows( 'sections' ) ) {
		the_row();
		get_template_part( 'template-parts/sections/section', get_row_layout() );
	}
} else {
	echo '<section class="band band--light"><div class="container">';
	echo '<h1 class="questions__title">' . esc_html( get_the_title() ) . '</h1>';
	echo '<p class="stories__note">Add sections in the page editor, under Sections.</p>';
	echo '</div></section>';
}

get_footer();
