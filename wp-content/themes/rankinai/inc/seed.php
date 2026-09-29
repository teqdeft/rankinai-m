<?php
/**
 * Seeding
 * =============================================================================
 * Creates the pages built from sections (the home page), with each section
 * filled with its original copy from inc/section-defaults.php, images imported
 * into the Media Library. Safe to run more than once: a page that exists is
 * left alone, unless $force, which refills it (overwriting edits).
 *
 * Run it from the command line with the repo's .claude/wp-seed.php, or call
 * rankinai_seed() from anywhere WordPress and ACF are loaded.
 * =============================================================================
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/** The pages, in the order they get built. Home is the only one in this pass. */
function rankinai_seed_pages() {
	return array(
		'home' => array(
			'title'    => 'Home',
			'front'    => true,
			'sections' => array( 'home_hero', 'stories', 'opportunity', 'services_scroll', 'method', 'team', 'commitments', 'faq' ),
		),
	);
}

function rankinai_seed( $force = false ) {
	if ( ! function_exists( 'update_field' ) ) {
		return 'ACF is not active.';
	}
	$log = array();
	foreach ( rankinai_seed_pages() as $slug => $p ) {
		$page = get_page_by_path( $slug, OBJECT, 'page' );
		$rows = array_map( function ( $layout ) { return array( 'acf_fc_layout' => $layout ) + rankinai_section_values( $layout ); }, $p['sections'] );
		if ( $page && ! $force ) {
			$log[] = "$slug: exists, left alone";
			$id = $page->ID;
		} elseif ( $page ) {
			$id = $page->ID;
			update_field( 'field_ri_sections', $rows, $id );
			$log[] = "$slug: refilled " . count( $rows ) . ' sections';
		} else {
			$id = wp_insert_post( array(
				'post_type'   => 'page',
				'post_status' => 'publish',
				'post_title'  => $p['title'],
				'post_name'   => $slug,
			) );
			update_field( 'field_ri_sections', $rows, $id );
			$log[] = "$slug: created with " . count( $rows ) . ' sections';
		}
		if ( ! empty( $p['front'] ) ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $id );
		}
	}
	return implode( "\n", $log );
}
