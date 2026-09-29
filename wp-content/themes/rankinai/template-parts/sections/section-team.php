<?php
/**
 * Section: the team, a heading, a paragraph and the photo marquee. The flat
 * build's includes/team.php, as the home page used it (index.php section 06).
 * The photographs are the team's own; the list lives in
 * template-parts/team-marquee.php.
 */
$d     = rankinai_section_defaults( 'team' );
$title = rf( 'title', $d['title'] );
$lead  = rf( 'lead', $d['lead'] );
list( $ll, $lh ) = rf_link( rf( 'link', null ), array( $d['link']['title'], $d['link']['url'] ) );
$lead = wp_kses_post( $lead ) . '<p class="team__more"><a class="link-quiet link-quiet--onforest" href="' . esc_url( $lh ) . '">' . wp_kses_post( $ll ) . '</a></p>';
get_template_part( 'template-parts/team', null, array( 'title' => wp_kses_post( $title ), 'lead' => $lead ) );
