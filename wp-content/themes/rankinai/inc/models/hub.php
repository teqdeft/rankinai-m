<?php
/**
 * Model: the three category hubs (build-and-design, hr-and-recruitment,
 * professional-services). Pages using page-templates/hub.php, filled from the
 * flat build's hub files plus the copy its category template carried inline:
 * the two proof figures, the section headings and the note. Both figures are
 * sourced in CLAIMS.md.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$hub_item = function ( $slug ) {
	return function () use ( $slug ) {
		$got = rankinai_read_flat_data( $slug, 'CATEGORY' );
		if ( ! $got || ! is_array( $got[0] ) ) { return null; }
		$C = $got[0] + array(
			'stats'        => array( array( '2009', 'Working with businesses since' ), array( '200+', 'Clients served' ) ),
			'sectorsTitle' => 'Sectors we cover',
			'sectorsLink'  => 'How it works in this trade',
			'sharedTitle'  => 'One team across all three',
			'sharedNote'   => 'The trades feel nothing alike from the inside. The buying behaviour is close to identical, which is why the same order of work applies to all of them.',
		);
		return array( $C, $got[1], $got[2] );
	};
};

return array(
	'name'      => 'hub',
	'post_type' => 'page',
	'template'  => 'page-templates/hub.php',
	'label'     => 'Category hub page',
	'prefix'    => 'hub',
	'schema'    => array(
		'label'        => array( 'text', 'Label' ),
		'h1'           => array( 'text', 'Headline' ),
		'sub'          => array( 'area', 'Subhead' ),
		'ctaNote'      => array( 'area', 'Note under the button' ),
		'stats'        => array( 'rows', 'Proof figures', array( 'Figure', 'Label' ), 'Only real, sourced figures. See CLAIMS.md.' ),
		'sectorsTitle' => array( 'text', 'Sectors: heading' ),
		'sectorsLink'  => array( 'text', 'Sectors: link text' ),
		'industries'   => array( 'rows', 'Sectors', array( 'Name', 'Link', 'Text' ) ),
		'sharedTitle'  => array( 'text', 'Shared: heading' ),
		'sharedNote'   => array( 'area', 'Shared: note' ),
		'shared'       => array( 'rows', 'Shared: points', array( 'Name', 'Text' ) ),
	),
	'items'     => array(
		'build-and-design'      => array( 'title' => 'Build and design', 'data' => $hub_item( 'build-and-design' ) ),
		'hr-and-recruitment'    => array( 'title' => 'HR and recruitment', 'data' => $hub_item( 'hr-and-recruitment' ) ),
		'professional-services' => array( 'title' => 'Professional services', 'data' => $hub_item( 'professional-services' ) ),
	),
);
