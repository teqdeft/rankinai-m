<?php
/**
 * Model: blog articles. WordPress posts at /blog/{slug}/, filled from the flat
 * build's includes/posts.php (title, standfirst, topic, date, reading time,
 * photograph, featured) and blog-{slug}.php (the body), rendered by single.php
 * (the flat blog template, ported unchanged).
 *
 * The body is a list of blocks, each with a Type; see rankinai_body_pack() in
 * _blog.php for how the flat build's typed tuples map onto them.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$post_items = function () {
	$items = array();
	foreach ( rankinai_flat_posts() as $row ) {
		$slug = $row['slug'];
		$items[ $slug ] = array(
			'title'   => wp_strip_all_tags( html_entity_decode( $row['title'], ENT_QUOTES, 'UTF-8' ) ),
			'date'    => $row['date'] . ' 09:00:00',
			'excerpt' => wp_strip_all_tags( html_entity_decode( $row['stand'], ENT_QUOTES, 'UTF-8' ) ),
			'data'    => function () use ( $slug, $row ) {
				$got  = rankinai_read_flat_data( 'blog-' . $slug, 'POST' );
				$post = ( $got && is_array( $got[0] ) ) ? $got[0] : array();
				$data = array(
					'topic'    => $row['topic'],
					'featured' => ! empty( $row['featured'] ),
					'stand'    => $post['stand'] ?? $row['stand'],
					'mins'     => (string) $row['mins'],
					'img'      => $row['img'] ?? null,
					'author'   => $post['author'] ?? '',
					'body'     => rankinai_body_pack( (array) ( $post['body'] ?? array() ) ),
				);
				if ( empty( $data['img'] ) ) { unset( $data['img'] ); }
				return array( $data, $got[1] ?? '', $got[2] ?? '' );
			},
		);
	}
	return $items;
};

return array(
	'name'      => 'post',
	'post_type' => 'post',
	'label'     => 'Article',
	'prefix'    => 'blg',
	'unpack'    => function ( $d ) {
		$d['body'] = rankinai_body_unpack( $d['body'] ?? array() );
		return $d;
	},
	'schema'    => array(
		'topic'    => array( 'choice', 'Topic', $GLOBALS['TOPICS'] ),
		'featured' => array( 'bool', 'Featured on the blog page' ),
		'stand'    => array( 'area', 'Standfirst' ),
		'mins'     => array( 'text', 'Reading time (minutes)' ),
		'img'      => array( 'photo', 'Photograph (Unsplash)' ),
		'author'   => array( 'text', 'Author', 'Empty = RankinAI.' ),
		'body'     => array( 'blocks', 'Article', array(
			'kind'  => array( 'choice', 'Type', array(
				'p'     => 'Paragraph',
				'h2'    => 'Heading (in the contents list)',
				'h3'    => 'Subheading',
				'note'  => 'Note',
				'quote' => 'Pull quote',
				'list'  => 'Bulleted list',
				'olist' => 'Numbered list',
				'take'  => 'Takeaways box',
				'table' => 'Table',
				'img'   => 'Photograph (Unsplash)',
			) ),
			'text'  => array( 'area', 'Text (paragraph, heading, note, quote), or the title of a takeaways box, or a table\'s caption' ),
			'items' => array( 'lines', 'List items or takeaways (one per line)' ),
			'head'  => array( 'text', 'Table heading cells, separated by |' ),
			'cells' => array( 'lines', 'Table rows, one per line, cells separated by |' ),
			'photo' => array( 'photo', 'Photograph (Unsplash)' ),
		) ),
	),
	'items'     => $post_items,
);
