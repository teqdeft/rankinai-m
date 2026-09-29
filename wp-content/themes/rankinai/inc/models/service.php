<?php
/**
 * Model: services. Six posts at /{slug}/, filled from the flat build's data
 * files (ai-visibility.php, ...), rendered by single-rankinai_service.php.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$slugs = array( 'ai-visibility', 'paid-advertising', 'content', 'website-conversion', 'reputation', 'crm' );
$items = array();
foreach ( $slugs as $i => $slug ) {
	$items[ $slug ] = array(
		'order' => $i,
		'data'  => function () use ( $slug ) { return rankinai_read_flat_data( $slug, 'SERVICE' ); },
	);
}

return array(
	'name'      => 'service',
	'post_type' => 'rankinai_service',
	'label'     => 'Service page',
	'prefix'    => 'svc',
	'flat'      => true,
	'register'  => array(
		'labels'        => array( 'name' => 'Services', 'singular_name' => 'Service', 'add_new_item' => 'Add service', 'edit_item' => 'Edit service', 'all_items' => 'All services', 'not_found' => 'No services found' ),
		'public'        => true,
		'has_archive'   => false,
		'supports'      => array( 'title', 'page-attributes' ),
		'menu_position' => 21,
		'menu_icon'     => 'dashicons-megaphone',
		'rewrite'       => array( 'slug' => 'services', 'with_front' => false ),
	),
	'schema'    => rankinai_cpt_schema( 'rankinai_service' ),
	'items'     => $items,
);
