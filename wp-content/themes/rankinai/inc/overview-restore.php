<?php
/**
 * One-off, 26 Nov 2026: the two overview sentences go back
 * =============================================================================
 * A pass deployed earlier today removed the last sentence of the SweetRush and
 * Studio Ubique overviews, and was reverted. It had already run on the live
 * database, so this puts the two sentences back where they were.
 *
 * It runs once, and adds a sentence only while the overview still ends where
 * that pass left it. An overview edited since is left alone. The option
 * rankinai_restore_20261126 records what it did.
 *
 * Temporary: this file and its line in functions.php come out as soon as it
 * has run on the live site.
 * =============================================================================
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'init', function () {
	if ( false !== get_option( 'rankinai_restore_20261126', false ) || ! function_exists( 'update_field' ) ) { return; }

	$restore = array(
		'sweetrush'     => array( 'getting them into the answers assistants give.', 'Inbound enquiries rose 3.2 times over five months.' ),
		'studio-ubique' => array( 'the visibility to go with it.', 'Qualified briefs rose 58% over four months.' ),
	);
	$log = array();

	foreach ( $restore as $slug => $r ) {
		list( $ending, $sentence ) = $r;
		$story = get_page_by_path( $slug, OBJECT, 'rankinai_story' );
		if ( ! $story ) { $log[] = "$slug: not found"; continue; }

		$overview = trim( (string) get_post_meta( $story->ID, 'overview', true ) );
		if ( false !== strpos( $overview, $sentence ) ) { $log[] = "$slug: already there"; continue; }
		if ( substr( $overview, -strlen( $ending ) ) !== $ending ) { $log[] = "$slug: overview edited since, left alone"; continue; }

		update_field( 'field_ri_sty_overview', $overview . ' ' . $sentence, $story->ID );
		$log[] = "$slug: sentence restored";
	}
	update_option( 'rankinai_restore_20261126', $log, false );
}, 22 );
