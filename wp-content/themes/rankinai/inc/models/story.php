<?php
/**
 * Model: success stories. A post type at /success-stories/{slug}/, filled
 * from the flat build's success-stories-{slug}.php files and rendered by
 * single-rankinai_story.php (the flat story template, ported unchanged).
 *
 * TWO SHAPES, as in the flat build: Pine Tree Lane uses the long form
 * (business, challenge, found, strategy, changed, delivery, results,
 * closing); SweetRush and Studio Ubique the short one (overview, situation,
 * found as a list, moves, metrics). The template picks by whether 'business'
 * is set. 'found' is a group in the long form and a list in the short one, so
 * the field holds both and 'unpack' hands the template the shape it expects.
 *
 * THE FIRST HOUSE RULE. 'quote' left empty renders the honest empty state.
 * A headline or metric figure left empty renders "pending". The SweetRush and
 * Studio Ubique metrics were the flat build's PLACEHOLDER DATA until 8 Oct
 * 2026. They are pending now (inc/placeholder-sweep.php cleared them on every
 * copy of the site), and only the clients' real, signed-off numbers go in.
 * See CLAIMS.md.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$story_item = function ( $slug ) {
	return function () use ( $slug ) {
		$got = rankinai_read_flat_data( 'success-stories-' . $slug, 'STORY' );
		if ( ! $got || ! is_array( $got[0] ) ) { return null; }
		$S = $got[0];
		if ( isset( $S['found'] ) && ! isset( $S['found']['items'] ) ) {
			$S['found'] = array( 'list' => $S['found'] );
		}
		return array( $S, $got[1], $got[2] );
	};
};

return array(
	'name'      => 'story',
	'post_type' => 'rankinai_story',
	'label'     => 'Success story',
	'prefix'    => 'sty',
	'register'  => array(
		'labels'        => array( 'name' => 'Success stories', 'singular_name' => 'Success story', 'add_new_item' => 'Add success story', 'edit_item' => 'Edit success story', 'all_items' => 'All success stories', 'not_found' => 'No success stories found' ),
		'public'        => true,
		'has_archive'   => false,
		'supports'      => array( 'title', 'page-attributes' ),
		'menu_position' => 23,
		'menu_icon'     => 'dashicons-awards',
		'rewrite'       => array( 'slug' => 'success-stories', 'with_front' => false ),
	),
	'unpack'    => function ( $d ) {
		if ( isset( $d['found'] ) && ! isset( $d['found']['items'] ) && isset( $d['found']['list'] ) ) {
			$d['found'] = $d['found']['list'];
		}
		return $d;
	},
	'schema'    => array(
		'label'        => array( 'text', 'Eyebrow (sector)' ),
		'client'       => array( 'text', 'Client name', 'Only a real, signed-off client.' ),
		'meta'         => array( 'lines', 'Market and other tags (one per line)' ),
		'h1'           => array( 'text', 'Headline' ),
		'sub'          => array( 'area', 'Subhead' ),
		'lead'         => array( 'area', 'Second line' ),
		'heroPhoto'    => array( 'image', 'Hero photograph', 'The client\'s own photograph needs their written okay before launch. See CLAIMS.md.' ),
		'heroPhotoAlt' => array( 'text', 'Hero photograph description' ),
		'logo'         => array( 'image', 'Client logo' ),
		'headline'     => array( 'rows', 'Headline figures', array( 'From?', 'Now?', 'What it measures' ), 'Only real, sourced figures. An empty "Now" renders pending.' ),
		'headlineNote' => array( 'area', 'Note under the figures' ),
		'overview'     => array( 'area', 'Overview (short form)' ),
		'problemHead'  => array( 'text', 'Situation: heading (short form)' ),
		'situation'    => array( 'paras', 'Situation (short form)' ),
		'business'     => array( 'group', 'The business (long form)', array(
			'eyebrow' => array( 'text', 'Eyebrow' ),
			'title'   => array( 'text', 'Heading' ),
			'paras'   => array( 'paras', 'Paragraphs' ),
		) ),
		'challenge'    => array( 'group', 'The challenge (long form)', array(
			'eyebrow' => array( 'text', 'Eyebrow' ),
			'title'   => array( 'text', 'Heading' ),
			'paras'   => array( 'paras', 'Paragraphs' ),
			'quotes'  => array( 'lines', 'Questions (one per line)' ),
			'tail'    => array( 'area', 'Closing line' ),
		) ),
		'found'        => array( 'group', 'What we found', array(
			'eyebrow' => array( 'text', 'Eyebrow (long form)' ),
			'title'   => array( 'text', 'Heading (long form)' ),
			'lead'    => array( 'area', 'Lead (long form)' ),
			'items'   => array( 'rows', 'Findings (long form)', array( 'Name', 'Paragraphs' ) ),
			'list'    => array( 'lines', 'Findings (short form, one per line)' ),
		) ),
		'strategy'     => array( 'group', 'Strategy (long form)', array(
			'eyebrow'  => array( 'text', 'Eyebrow' ),
			'title'    => array( 'text', 'Heading' ),
			'paras'    => array( 'paras', 'Paragraphs' ),
			'listLead' => array( 'text', 'List label' ),
			'list'     => array( 'lines', 'List (one per line)' ),
			'tail'     => array( 'area', 'Closing line' ),
		) ),
		'changed'      => array( 'group', 'What we changed (long form)', array(
			'eyebrow' => array( 'text', 'Eyebrow' ),
			'title'   => array( 'text', 'Heading' ),
			'steps'   => array( 'blocks', 'Steps', array(
				'title' => array( 'text', 'Title' ),
				'paras' => array( 'paras', 'Paragraphs' ),
				'ba'    => array( 'rows', 'Before and after', array( 'Label', 'Text' ) ),
			) ),
		) ),
		'delivery'     => array( 'group', 'Delivery (long form)', array(
			'eyebrow' => array( 'text', 'Eyebrow' ),
			'title'   => array( 'text', 'Heading' ),
			'phases'  => array( 'rows', 'Phases', array( 'When', 'Title', 'Text' ) ),
		) ),
		'results'      => array( 'group', 'Results (long form)', array(
			'eyebrow'   => array( 'text', 'Eyebrow' ),
			'title'     => array( 'text', 'Heading' ),
			'paras'     => array( 'paras', 'Paragraphs' ),
			'measuring' => array( 'rows', 'What is measured', array( 'Measure', 'Source' ) ),
			'notes'     => array( 'rows', 'Notes', array( 'Name', 'Text' ) ),
			'tail'      => array( 'area', 'Closing line' ),
		) ),
		'closing'      => array( 'group', 'Closing (long form)', array(
			'eyebrow' => array( 'text', 'Eyebrow' ),
			'title'   => array( 'text', 'Heading' ),
			'paras'   => array( 'paras', 'Paragraphs' ),
		) ),
		'movesHead'    => array( 'lines', 'What we did: heading, then note (short form)' ),
		'moves'        => array( 'rows', 'What we did (short form)', array( 'Name', 'Text' ) ),
		'resultHead'   => array( 'lines', 'Results: heading (short form)' ),
		'metrics'      => array( 'rows', 'Results figures (short form)', array( 'Figure?', 'What', 'Period', 'Source' ), 'Only real, signed-off figures. Empty = pending.' ),
		'closeText'    => array( 'area', 'Closing text (short form)' ),
		'facts'        => array( 'rows', 'Facts', array( 'Label', 'Value' ) ),
		'services'     => array( 'rows', 'Services used', array( 'Name', 'Link' ) ),
		'quote'        => array( 'slots', 'Client quote', array( 'Quote (only the client\'s own words, signed off)', 'Name', 'Role, company' ) ),
		'more'         => array( 'group', 'Next story', array(
			'name'      => array( 'text', 'Client' ),
			'meta'      => array( 'lines', 'Tags (one per line)' ),
			'metric'    => array( 'text', 'Figure' ),
			'metricKey' => array( 'text', 'What the figure measures' ),
			'text'      => array( 'area', 'Summary' ),
			'photoSrc'  => array( 'image', 'Photograph' ),
			'photoAlt'  => array( 'text', 'Photograph description' ),
			'href'      => array( 'text', 'Link' ),
		) ),
	),
	'items'     => array(
		'pine-tree-lane' => array( 'title' => 'Pine Tree Lane', 'order' => 0, 'data' => $story_item( 'pine-tree-lane' ) ),
		'sweetrush'      => array( 'title' => 'SweetRush', 'order' => 1, 'data' => $story_item( 'sweetrush' ) ),
		'studio-ubique'  => array( 'title' => 'Studio Ubique', 'order' => 2, 'data' => $story_item( 'studio-ubique' ) ),
	),
);
