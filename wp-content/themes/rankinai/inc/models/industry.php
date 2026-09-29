<?php
/**
 * Model: industries. Nine posts at /{slug}/, filled from the flat build's
 * data files (interior-design.php, ...), rendered by
 * single-rankinai_industry.php.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$slugs = array( 'interior-design', 'construction', 'architecture', 'law-firms', 'accounting', 'it-consulting', 'consulting', 'recruitment-agencies', 'hr-outsourcing' );
$items = array();
foreach ( $slugs as $i => $slug ) {
	$items[ $slug ] = array(
		'order' => $i,
		'data'  => function () use ( $slug ) { return rankinai_read_flat_data( $slug, 'INDUSTRY' ); },
	);
}

return array(
	'name'      => 'industry',
	'post_type' => 'rankinai_industry',
	'label'     => 'Industry page',
	'prefix'    => 'ind',
	'flat'      => true,
	'register'  => array(
		'labels'        => array( 'name' => 'Industries', 'singular_name' => 'Industry', 'add_new_item' => 'Add industry', 'edit_item' => 'Edit industry', 'all_items' => 'All industries', 'not_found' => 'No industries found' ),
		'public'        => true,
		'has_archive'   => false,
		'supports'      => array( 'title', 'page-attributes' ),
		'menu_position' => 22,
		'menu_icon'     => 'dashicons-building',
		'rewrite'       => array( 'slug' => 'industries', 'with_front' => false ),
	),
	'schema'    => rankinai_cpt_schema( 'rankinai_industry' ),
	'items'     => $items,
);
