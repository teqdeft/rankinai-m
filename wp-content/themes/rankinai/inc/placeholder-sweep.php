<?php
/**
 * One-off: the invented story figures come off, 8 Oct 2026
 * =============================================================================
 * The SweetRush and Studio Ubique story pages carried the flat build's
 * PLACEHOLDER DATA: three dummy results figures each, and a "Headline result"
 * fact quoting one of them (3.2x, 27, 64% and +58%, 19, 2.4x). None is a real
 * number. They had to come off before the site is indexed (CLAUDE.md, house
 * rule 1, and CLAIMS.md).
 *
 * The live database is edited in wp-admin and is not a copy of any local one,
 * so the change has to run where the data is. This does, once, on the first
 * request after it is deployed: each figure that still holds its invented
 * value is emptied, which the story template shows as pending ("Also being
 * measured"), and the "Headline result" fact quoting it is removed. Anything
 * edited since, a real figure typed in for instance, is left alone.
 *
 * The option rankinai_sweep_20261008 records what it did. Once it is set
 * everywhere this file can be deleted, with its line in functions.php.
 * =============================================================================
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'init', function () {
	if ( false !== get_option( 'rankinai_sweep_20261008', false ) || ! function_exists( 'update_field' ) ) { return; }

	$invented = array(
		'sweetrush'     => array( 'figures' => array( '3.2×', '27', '64%' ),  'headline' => '3.2× inbound enquiries in five months' ),
		'studio-ubique' => array( 'figures' => array( '+58%', '19', '2.4×' ), 'headline' => '+58% qualified briefs in four months' ),
	);
	$plain = function ( $s ) { return trim( html_entity_decode( (string) $s, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ); };
	$log   = array();

	foreach ( $invented as $slug => $inv ) {
		$story = get_page_by_path( $slug, OBJECT, 'rankinai_story' );
		if ( ! $story ) { $log[] = "$slug: not found"; continue; }

		$metrics = (array) get_field( 'metrics', $story->ID );
		$cleared = 0;
		foreach ( $metrics as $i => $row ) {
			if ( in_array( $plain( $row['c0'] ?? '' ), $inv['figures'], true ) ) { $metrics[ $i ]['c0'] = ''; $cleared++; }
		}
		if ( $cleared ) { update_field( 'field_ri_sty_metrics', $metrics, $story->ID ); }

		$facts = (array) get_field( 'facts', $story->ID );
		$kept  = array_values( array_filter( $facts, function ( $row ) use ( $inv, $plain ) {
			return ! ( 'Headline result' === $plain( $row['c0'] ?? '' ) && $inv['headline'] === $plain( $row['c1'] ?? '' ) );
		} ) );
		if ( count( $kept ) !== count( $facts ) ) { update_field( 'field_ri_sty_facts', $kept, $story->ID ); }

		$log[] = "$slug: $cleared figures pending, " . ( count( $facts ) - count( $kept ) ) . ' headline fact removed';
	}
	update_option( 'rankinai_sweep_20261008', $log, false );
}, 20 );
