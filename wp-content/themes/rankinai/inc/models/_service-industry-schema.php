<?php
/**
 * The schema shared by the service and industry models (they differ in their
 * middle sections). Moved here from inc/content-model.php on 29 Sep 2026.
 * The story photograph is an image field: the case photographs live in the
 * Media Library.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

function rankinai_cpt_schema( $type ) {
	$photo  = array( 'photo', 'Photograph (Unsplash)' );
	$story  = array( 'group', 'Success story', array(
		'title'     => array( 'text', 'Heading' ),
		'name'      => array( 'text', 'Client name', 'Only a real, signed-off client. Empty = the pending block.' ),
		'meta'      => array( 'lines', 'Sector, country (one per line)' ),
		'metric'    => array( 'text', 'Figure', 'Only a real, sourced figure. Never a guess. See CLAIMS.md.' ),
		'metricKey' => array( 'text', 'What the figure measures' ),
		'text'      => array( 'area', 'Summary' ),
		'photo'     => array( 'text', 'Photograph file (in the theme\'s assets/images)' ),
		'photoSrc'  => array( 'image', 'Photograph' ),
		'photoAlt'  => array( 'text', 'Photograph description' ),
		'href'      => array( 'text', 'Link' ),
		'linkText'  => array( 'text', 'Link text' ),
	) );
	$qs     = array( 'qs', 'Questions' );
	$two    = array( 'Name', 'Text' );

	if ( 'rankinai_service' === $type ) {
		return array(
			'label'       => array( 'text', 'Eyebrow' ),
			'h1'          => array( 'text', 'Headline' ),
			'heroImage'   => $photo,
			'sub'         => array( 'area', 'Subhead' ),
			'sub2'        => array( 'area', 'Second line' ),
			'opportunity' => array( 'group', 'The opportunity', array(
				'eyebrow' => array( 'text', 'Eyebrow' ),
				'title'   => array( 'text', 'Heading' ),
				'quotes'  => array( 'lines', 'Buyer questions (one per line)' ),
				'paras'   => array( 'paras', 'Paragraphs' ),
				'items'   => array( 'rows', 'Points', $two ),
			) ),
			'approach'    => array( 'group', 'Our approach', array(
				'eyebrow'  => array( 'text', 'Eyebrow' ),
				'title'    => array( 'text', 'Heading' ),
				'paras'    => array( 'paras', 'Paragraphs' ),
				'items'    => array( 'rows', 'Points', $two ),
				'listLead' => array( 'text', 'List label' ),
				'list'     => array( 'lines', 'List (one per line)' ),
				'tail'     => array( 'area', 'Closing line' ),
			) ),
			'channels'    => array( 'group', 'Channels', array(
				'eyebrow' => array( 'text', 'Eyebrow' ),
				'title'   => array( 'text', 'Heading' ),
				'items'   => array( 'rows', 'Channels', array( 'icon', 'Name', 'Lead', 'Text' ) ),
				'tail'    => array( 'area', 'Closing line' ),
			) ),
			'doHead'      => array( 'slots', 'What we do: heading', array( 'Eyebrow (empty = "What the service covers")', 'Heading', 'Note' ) ),
			'do'          => array( 'rows', 'What we do', array( 'icon', 'Name', 'Lead', 'Text' ) ),
			'compare'     => array( 'group', 'Two ways of searching', array(
				'eyebrow'   => array( 'text', 'Eyebrow' ),
				'photo'     => $photo,
				'title'     => array( 'text', 'Heading' ),
				'paras'     => array( 'paras', 'Paragraphs' ),
				'quotes'    => array( 'rows', 'The two searches', array( 'icon', 'Label', 'What they type' ) ),
				'listTitle' => array( 'text', 'List label' ),
				'list'      => array( 'lines', 'List (one per line)' ),
				'tail'      => array( 'area', 'Closing line' ),
			) ),
			'substance'   => array( 'blocks', 'Substance blocks', array(
				'band'    => array( 'band', 'Ground' ),
				'eyebrow' => array( 'text', 'Eyebrow' ),
				'title'   => array( 'text', 'Heading' ),
				'paras'   => array( 'paras', 'Paragraphs' ),
				'items'   => array( 'rows', 'Points', $two ),
				'tail'    => array( 'area', 'Closing line' ),
			) ),
			'routes'      => array( 'group', 'Routes', array(
				'eyebrow' => array( 'text', 'Eyebrow' ),
				'title'   => array( 'text', 'Heading' ),
				'items'   => array( 'rows', 'Routes', $two ),
				'tail'    => array( 'area', 'Closing line' ),
			) ),
			'movesEyebrow' => array( 'text', 'How we start: eyebrow' ),
			'movesTitle'   => array( 'text', 'How we start: heading' ),
			'moves'        => array( 'rows', 'How we start: steps', $two ),
			'movesTail'    => array( 'area', 'How we start: closing line' ),
			'story'        => $story,
			'reportEyebrow' => array( 'text', 'Reporting: eyebrow' ),
			'reportTitle'   => array( 'text', 'Reporting: heading' ),
			'reportNote'    => array( 'area', 'Reporting: note' ),
			'reportGrid'    => array( 'text', 'Reporting: grid modifier' ),
			'report'        => array( 'rows', 'Reporting: measures', $two ),
			'reportTail'    => array( 'area', 'Reporting: closing line' ),
			'qs'            => $qs,
		);
	}

	return array(
		'label'       => array( 'text', 'Eyebrow' ),
		'h1'          => array( 'text', 'Headline' ),
		'heroPhoto'   => $photo,
		'sub'         => array( 'area', 'Subhead' ),
		'sub2'        => array( 'area', 'Second line' ),
		'opportunity' => array( 'group', 'The opportunity', array(
			'eyebrow' => array( 'text', 'Eyebrow' ),
			'title'   => array( 'text', 'Heading' ),
			'quotes'  => array( 'lines', 'Buyer questions (one per line)' ),
			'paras'   => array( 'paras', 'Paragraphs' ),
		) ),
		'approach'    => array( 'group', 'Start with the right work', array(
			'eyebrow'  => array( 'text', 'Eyebrow' ),
			'title'    => array( 'text', 'Heading' ),
			'paras'    => array( 'paras', 'Paragraphs' ),
			'listLead' => array( 'text', 'List label' ),
			'list'     => array( 'lines', 'List (one per line)' ),
			'tail'     => array( 'area', 'Closing line' ),
		) ),
		'journey'     => array( 'group', 'The client journey', array(
			'eyebrow' => array( 'text', 'Eyebrow' ),
			'title'   => array( 'text', 'Heading' ),
			'paras'   => array( 'paras', 'Paragraphs' ),
			'items'   => array( 'rows', 'Steps', $two ),
			'tail'    => array( 'area', 'Closing line' ),
		) ),
		'services'    => array( 'group', 'What we do', array(
			'eyebrow' => array( 'text', 'Eyebrow' ),
			'title'   => array( 'text', 'Heading' ),
			'items'   => array( 'rows', 'Services', array( 'icon', 'Name', 'Link', 'Lead', 'Text' ) ),
		) ),
		'blocks'      => array( 'blocks', 'Argument blocks', array(
			'photo'    => $photo,
			'band'     => array( 'band', 'Ground' ),
			'eyebrow'  => array( 'text', 'Eyebrow' ),
			'title'    => array( 'text', 'Heading' ),
			'lead'     => array( 'area', 'Line before the questions' ),
			'quotes'   => array( 'lines', 'Questions (one per line)' ),
			'paras'    => array( 'paras', 'Paragraphs' ),
			'items'    => array( 'rows', 'Points', $two ),
			'listLead' => array( 'text', 'List label' ),
			'list'     => array( 'lines', 'List (one per line)' ),
			'tail'     => array( 'area', 'Closing line' ),
		) ),
		'movesPhoto'   => $photo,
		'movesEyebrow' => array( 'text', 'How we start: eyebrow' ),
		'movesTitle'   => array( 'text', 'How we start: heading' ),
		'moves'        => array( 'rows', 'How we start: steps', $two ),
		'story'        => $story,
		'reportEyebrow' => array( 'text', 'Measuring progress: eyebrow' ),
		'reportTitle'   => array( 'text', 'Measuring progress: heading' ),
		'reportNote'    => array( 'area', 'Measuring progress: note' ),
		'report'        => array( 'rows', 'Measuring progress: measures', $two ),
		'reportTail'    => array( 'area', 'Measuring progress: closing line' ),
		'qs'            => $qs,
	);
}
