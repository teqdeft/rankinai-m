<?php
/**
 * Model: legal pages (privacy, terms). Pages using page-templates/legal.php,
 * filled from the flat build's privacy.php and terms.php.
 *
 * Each clause's body is one rich-text field, because a legal document is
 * edited as a document. The seed writes it as exactly the HTML the flat
 * build's legal template printed (paragraphs, tick lists, tables).
 *
 * The draft banner ('draft') is not decoration: remove it only when a lawyer
 * has signed the document off. See the flat build's includes/legal-template.php.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** One clause's blocks as the HTML the legal template prints. */
function rankinai_legal_body( array $blocks ) {
	$h = '';
	foreach ( $blocks as $b ) {
		list( $kind, $content ) = array_pad( (array) $b, 2, '' );
		if ( 'p' === $kind ) {
			$h .= '<p class="clause__p">' . $content . "</p>\n";
		} elseif ( 'ul' === $kind ) {
			$h .= "<ul class=\"ticks\" role=\"list\">\n";
			foreach ( (array) $content as $li ) { $h .= '<li>' . $li . "</li>\n"; }
			$h .= "</ul>\n";
		} elseif ( 'table' === $kind ) {
			$h .= "<div class=\"clause__table\">\n";
			foreach ( (array) $content as $r => $row ) {
				$h .= '<div class="clause__row' . ( 0 === $r ? ' clause__row--head' : '' ) . '">';
				foreach ( (array) $row as $cell ) { $h .= '<span>' . $cell . '</span>'; }
				$h .= "</div>\n";
			}
			$h .= "</div>\n";
		}
	}
	return $h;
}

$legal_item = function ( $slug ) {
	return function () use ( $slug ) {
		$got = rankinai_read_flat_data( $slug, 'LEGAL' );
		if ( ! $got || ! is_array( $got[0] ) ) { return null; }
		$L = $got[0];
		$L['sections'] = array_map( function ( $s ) {
			list( $id, $num, $heading, $blocks ) = $s;
			return array( 'id' => $id, 'num' => $num, 'heading' => $heading, 'body' => rankinai_legal_body( (array) $blocks ) );
		}, (array) ( $L['sections'] ?? array() ) );
		return array( $L, $got[1], $got[2] );
	};
};

return array(
	'name'      => 'legal',
	'post_type' => 'page',
	'template'  => 'page-templates/legal.php',
	'label'     => 'Legal page',
	'prefix'    => 'legal',
	'schema'    => array(
		'label'    => array( 'text', 'Label' ),
		'title'    => array( 'text', 'Title' ),
		'updated'  => array( 'text', 'Last updated' ),
		'reading'  => array( 'text', 'Reading time' ),
		'draft'    => array( 'area', 'Draft banner', 'Remove only when a lawyer has signed the document off.' ),
		'summary'  => array( 'lines', 'The short version (one per line)' ),
		'toc'      => array( 'rows', 'Contents', array( 'Anchor', 'Title' ) ),
		'sections' => array( 'blocks', 'Clauses', array(
			'id'      => array( 'text', 'Anchor (matches the contents)' ),
			'num'     => array( 'text', 'Number' ),
			'heading' => array( 'text', 'Heading' ),
			'body'    => array( 'html', 'Text' ),
		) ),
	),
	'items'     => array(
		'privacy' => array( 'title' => 'Privacy policy', 'data' => $legal_item( 'privacy' ) ),
		'terms'   => array( 'title' => 'Terms', 'data' => $legal_item( 'terms' ) ),
	),
);
