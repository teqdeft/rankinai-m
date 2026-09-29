<?php
/**
 * Model: the blog index (/blog/). Page template page-templates/blog.php, the
 * flat build's blog.php. The articles themselves are WordPress posts (see
 * post.php); this page holds only its own words.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

return array(
	'name'      => 'blog',
	'post_type' => 'page',
	'template'  => 'page-templates/blog.php',
	'label'     => 'Blog page',
	'prefix'    => 'pg_blog',
	'schema'    => array(
		'eyebrow'     => array( 'text', 'Eyebrow' ),
		'h1'          => array( 'text', 'Headline' ),
		'browse'      => array( 'text', 'Topic filter label' ),
		'all'         => array( 'text', 'All-articles filter' ),
		'featured'    => array( 'text', 'Featured label' ),
		'read'        => array( 'text', 'Read link' ),
		'listEyebrow' => array( 'text', 'Listing eyebrow' ),
		'listTitle'   => array( 'text', 'Listing heading' ),
		'credit'      => array( 'text', 'Photograph credit line (before the Unsplash link)' ),
		'more'        => array( 'text', 'Load more button' ),
		'back'        => array( 'text', 'Back to the first page link' ),
		'noneTitle'   => array( 'text', 'No results: heading' ),
		'noneText'    => array( 'area', 'No results: text' ),
		'noneButton'  => array( 'text', 'No results: button' ),
	),
	'items'     => array(
		'blog' => array(
			'title' => 'Blog',
			'seo'   => array( 'The RankinAI blog | RankinAI', 'Practical guides and perspectives on getting found, getting chosen and turning interest into new business.' ),
			'data'  => array(
				'eyebrow'     => 'Get found. Get booked.',
				'h1'          => 'Blogs',
				'browse'      => 'Browse by topic',
				'all'         => 'All articles',
				'featured'    => 'Featured',
				'read'        => 'Read article',
				'listEyebrow' => 'Article listing',
				'listTitle'   => 'Explore the latest thinking.',
				'credit'      => 'Photographs by the photographers credited on each article, via',
				'more'        => 'Load more articles',
				'back'        => 'Back to the first page',
				'noneTitle'   => 'Nothing here for that search yet.',
				'noneText'    => 'Try a broader term, such as &ldquo;enquiries&rdquo;, &ldquo;search&rdquo; or &ldquo;website&rdquo;, or browse the topics above.',
				'noneButton'  => 'Clear search',
			),
		),
	),
);
