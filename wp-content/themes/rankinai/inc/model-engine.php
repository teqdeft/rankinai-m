<?php
/**
 * The model engine: post types and page templates whose content lives in ACF
 * fields described by a schema
 * =============================================================================
 * Built 29 Sep 2026, generalised from the services-and-industries model.
 *
 * A MODEL is one kind of page: services, industries, success stories, blog
 * articles, legal pages, hub pages, and each one-off page (about, pricing, ...).
 * Every model is one file in inc/models/, returning its definition:
 *
 *   'name'      unique, e.g. 'about'
 *   'post_type' 'page', 'post', or a custom type this model registers
 *   'template'  pages only: 'page-templates/about.php'. The model's fields
 *               show on a page using that template.
 *   'label'     the page template's name in the editor, and the field group
 *   'prefix'    field keys are field_ri_{prefix}_{path}. Never change it once
 *               content exists, or the content is orphaned.
 *   'register'  custom types only: labels and args for register_post_type()
 *   'flat'      true: the type's posts live at /{slug}/ (services, industries)
 *   'schema'    the fields, see FIELD KINDS below
 *   'unpack'    optional callable( $data ): reshape the array for the template,
 *               where one field has to serve two shapes (see story.php)
 *   'close'     optional: array( 'btn' => [label, href], 'link' => [label, href] )
 *               to change the shared close's links on this model's pages
 *   'items'     the posts to seed: slug => array(
 *                 'title'  => post title,
 *                 'data'   => the array the template reads (or a callable
 *                             returning array( data, seo title, seo desc )),
 *                 'seo'    => array( title, description ),
 *                 'parent' => parent page slug, optional,
 *                 'hide_close' => true to hide the shared close, optional,
 *                 'order'  => menu order, optional )
 *
 * Files in inc/models/ starting with "_" are helpers, loaded but not models.
 *
 * ONE SCHEMA DOES THREE JOBS: it generates the ACF fields, seeds them from the
 * flat build's content, and rebuilds the array the template reads. The three
 * cannot drift apart.
 *
 * FIELD KINDS
 *   text / area   one string (area is a textarea). May contain inline HTML.
 *   html          rich text (WYSIWYG)
 *   paras         paragraphs, separated by a blank line  → array of strings
 *   lines         a short list, one per line             → array of strings
 *   slots         fixed positions, each its own box      → array, blanks kept
 *   rows          a table, one tuple per row. A column named 'icon' is an icon
 *                 picker, 'image' an image, 'Text'/'Lead' a textarea,
 *                 'Paragraphs' a paragraph list (blank line between). A column
 *                 label ending in '?' is nullable: left empty it reads as null,
 *                 which the story templates use for "pending, not measured".
 *   group         a named set of fields                  → array
 *   blocks        a repeating group                      → list of arrays
 *   photo         an Unsplash photograph (hotlinked, per Unsplash's terms)
 *   image         one of the site's own images, in the Media Library → URL
 *   band          light / forest
 *   choice        a dropdown: array( 'choice', label, array( value => label ) )
 *   bool          a yes/no switch                        → true, or absent
 *   qs            questions and answers                  → [q, [paras]]
 *
 * EMPTY MEANS ABSENT: an empty field is dropped from the array, and the
 * templates guard every section, so an empty section does not render.
 *
 * IMAGES. The site's own images (the team's photographs, the case photographs,
 * the object renders) are imported into the Media Library by the seed, once
 * each, and held in image fields. A template prints them with
 * rankinai_img_url(), which takes either an imported image's URL or a flat
 * build file name. Unsplash photographs stay hotlinked, as their guidelines
 * require, and are held in 'photo' fields.
 * =============================================================================
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/* -----------------------------------------------------------------------------
 * The registry
 * -------------------------------------------------------------------------- */
function rankinai_models() {
	static $models = null;
	if ( null !== $models ) { return $models; }
	$models = array();
	$dir    = get_template_directory() . '/inc/models/';
	foreach ( glob( $dir . '_*.php' ) as $helper ) { require_once $helper; }
	foreach ( glob( $dir . '*.php' ) as $file ) {
		if ( '_' === basename( $file )[0] ) { continue; }
		$def = require $file;
		foreach ( isset( $def['name'] ) ? array( $def ) : (array) $def as $m ) {
			$models[ $m['name'] ] = $m;
		}
	}
	return $models;
}

/** The model a post belongs to: by page template for pages, by type otherwise. */
function rankinai_model_for( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$type    = get_post_type( $post_id );
	$tpl     = 'page' === $type ? get_page_template_slug( $post_id ) : '';
	foreach ( rankinai_models() as $m ) {
		if ( 'page' === $m['post_type'] ) {
			if ( 'page' === $type && $tpl === ( $m['template'] ?? '' ) ) { return $m; }
		} elseif ( $m['post_type'] === $type ) {
			return $m;
		}
	}
	return null;
}

/** The array a template reads, for the current post or $post_id. */
function rankinai_model_data( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$m       = rankinai_model_for( $post_id );
	if ( ! $m ) { return array(); }
	$values = array();
	foreach ( array_keys( $m['schema'] ) as $name ) {
		$values[ $name ] = get_field( $name, $post_id );
	}
	$data = rankinai_schema_to_data( $m['schema'], $values );
	// A model may reshape its array for its template (see 'unpack' above).
	return is_callable( $m['unpack'] ?? null ) ? call_user_func( $m['unpack'], $data ) : $data;
}
/** Kept for the service and industry templates. */
function rankinai_cpt_data( $post_id = null ) {
	return rankinai_model_data( $post_id );
}

/** A URL for an image reference: an imported image's URL, a full URL, or a
 *  flat build file name with or without its extension. */
function rankinai_img_url( $ref ) {
	$ref = trim( (string) $ref );
	if ( '' === $ref ) { return ''; }
	if ( preg_match( '#^(https?:)?//#', $ref ) || 0 === strpos( $ref, '/' ) ) { return $ref; }
	return asset( 'images/' . ( pathinfo( $ref, PATHINFO_EXTENSION ) ? $ref : $ref . '.webp' ) );
}

/* -----------------------------------------------------------------------------
 * Post types, page templates, field groups
 * -------------------------------------------------------------------------- */
add_action( 'init', function () {
	foreach ( rankinai_models() as $m ) {
		if ( ! empty( $m['register'] ) && ! post_type_exists( $m['post_type'] ) ) {
			register_post_type( $m['post_type'], $m['register'] );
		}
	}
} );

add_filter( 'theme_page_templates', function ( $templates ) {
	foreach ( rankinai_models() as $m ) {
		if ( 'page' === $m['post_type'] && ! empty( $m['template'] ) ) {
			$templates[ $m['template'] ] = $m['label'];
		}
	}
	return $templates;
} );

/* Rich-text fields hold exact markup (it is seeded from the flat build's own
   HTML), so ACF must not run wpautop over it on output. */
add_action( 'acf/init', function () {
	remove_filter( 'acf_the_content', 'wpautop' );
} );

add_action( 'acf/init', function () {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) { return; }
	foreach ( rankinai_models() as $m ) {
		$loc = 'page' === $m['post_type']
			? array( 'param' => 'page_template', 'operator' => '==', 'value' => $m['template'] )
			: array( 'param' => 'post_type', 'operator' => '==', 'value' => $m['post_type'] );
		acf_add_local_field_group( array(
			'key'            => 'group_ri_' . ( $m['group'] ?? $m['name'] ),
			'title'          => $m['label'],
			'fields'         => rankinai_schema_fields( $m['schema'], $m['prefix'] ),
			'location'       => array( array( $loc ) ),
			'hide_on_screen' => array( 'the_content' ),
			'menu_order'     => 2,
		) );
	}
} );

/* -----------------------------------------------------------------------------
 * Flat URLs for the types that want them (services, industries): /{slug}/
 * resolves to the post when no page has that slug, and /{base}/{slug}/ 301s
 * to /{slug}/. One URL per page, as on the flat build.
 * -------------------------------------------------------------------------- */
function rankinai_flat_types() {
	$t = array();
	foreach ( rankinai_models() as $m ) { if ( ! empty( $m['flat'] ) ) { $t[] = $m['post_type']; } }
	return $t;
}
function rankinai_cpt_types() { return rankinai_flat_types(); }

add_filter( 'post_type_link', function ( $link, $post ) {
	if ( in_array( $post->post_type, rankinai_flat_types(), true ) && 'publish' === $post->post_status ) {
		return home_url( '/' . $post->post_name . '/' );
	}
	return $link;
}, 10, 2 );

add_filter( 'request', function ( $qv ) {
	$slug = $qv['pagename'] ?? ( $qv['name'] ?? '' );
	// With posts at /blog/{slug}/, WordPress has no rule for /{slug}/ unless a
	// page has that slug, and marks it a 404 before this filter runs. Read the
	// slug from the path in that case.
	if ( '' === $slug && '404' === (string) ( $qv['error'] ?? '' ) ) {
		$path = trim( (string) parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
		if ( preg_match( '#^[a-z0-9-]+$#', $path ) ) { $slug = $path; }
	}
	if ( '' === $slug || false !== strpos( $slug, '/' ) || isset( $qv['post_type'] ) ) { return $qv; }
	if ( get_page_by_path( $slug, OBJECT, 'page' ) ) { return $qv; }
	foreach ( rankinai_flat_types() as $type ) {
		if ( get_page_by_path( $slug, OBJECT, $type ) ) {
			return array( 'post_type' => $type, $type => $slug, 'name' => $slug );
		}
	}
	return $qv;
} );

add_action( 'template_redirect', function () {
	if ( ! is_singular( rankinai_flat_types() ) ) { return; }
	$here  = untrailingslashit( (string) parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ) );
	$there = untrailingslashit( (string) parse_url( get_permalink(), PHP_URL_PATH ) );
	if ( $here !== $there ) {
		wp_safe_redirect( get_permalink(), 301 );
		exit;
	}
} );

/* -----------------------------------------------------------------------------
 * Images: import one of the site's own images into the Media Library, once.
 * Returns the attachment ID, or '' when there is nothing to import. A second
 * call for the same file returns the same attachment.
 * -------------------------------------------------------------------------- */
function rankinai_import_image( $ref ) {
	$ref = trim( (string) $ref );
	if ( '' === $ref ) { return ''; }
	if ( is_numeric( $ref ) ) { return (int) $ref; }
	if ( preg_match( '#^(https?:)?//#', $ref ) ) { return ''; }
	$base = get_template_directory() . '/assets/images/';
	$name = basename( parse_url( $ref, PHP_URL_PATH ) );
	$file = $base . $name;
	if ( ! is_file( $file ) && is_file( $base . $name . '.webp' ) ) { $name .= '.webp'; $file = $base . $name; }
	if ( ! is_file( $file ) ) { return ''; }

	$found = get_posts( array(
		'post_type'   => 'attachment',
		'post_status' => 'inherit',
		'meta_key'    => '_ri_source',
		'meta_value'  => $name,
		'fields'      => 'ids',
		'numberposts' => 1,
	) );
	if ( $found ) { return (int) $found[0]; }

	require_once ABSPATH . 'wp-admin/includes/image.php';
	$up = wp_upload_bits( $name, null, file_get_contents( $file ) );
	if ( ! empty( $up['error'] ) ) { return ''; }
	$type = wp_check_filetype( $up['file'] );
	$id   = wp_insert_attachment( array(
		'post_mime_type' => $type['type'] ?: 'image/webp',
		'post_title'     => preg_replace( '/\.[^.]+$/', '', $name ),
		'post_status'    => 'inherit',
	), $up['file'] );
	if ( is_wp_error( $id ) || ! $id ) { return ''; }
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $up['file'] ) );
	update_post_meta( $id, '_ri_source', $name );
	return (int) $id;
}

/* -----------------------------------------------------------------------------
 * Reading the flat build's data files. They are plain arrays; their requires
 * (the config and the template) are removed before they are evaluated. Used
 * by the models whose content already lives in a flat data file.
 * -------------------------------------------------------------------------- */
function rankinai_read_flat_data( $slug, $var ) {
	$file = dirname( ABSPATH ) . '/' . $slug . '.php';
	if ( ! is_readable( $file ) ) { return null; }
	$code = file_get_contents( $file );
	$code = preg_replace( "#require(_once)? __DIR__ \\. '/includes/[a-z-]+\\.php';#", '', $code );
	$code = preg_replace( '/^<\?php/', '', $code );
	$fn   = function () use ( $code, $var ) {
		// Some data files build strings with $SITE and url(). Give them the site's
		// details, and keep their links relative (below) so no local address is
		// stored in the content.
		$SITE = array();
		foreach ( array( 'name', 'tagline', 'email', 'phone', 'offices', 'address', 'hours', 'year' ) as $k ) { $SITE[ $k ] = rankinai_site( $k ); }
		$page_title = '';
		$page_desc  = '';
		eval( $code ); // phpcs:ignore -- the repo's own data files, read by the seed
		return array( $$var ?? null, $page_title, $page_desc );
	};
	return rankinai_relative_links( $fn() );
}

/** Strip this site's own address from every string, so links stored in the
 *  content are root-relative and survive a move to another domain. */
function rankinai_relative_links( $v ) {
	$home = untrailingslashit( home_url() );
	if ( is_string( $v ) ) { return str_replace( $home . '/', '/', $v ); }
	if ( is_array( $v ) ) { return array_map( 'rankinai_relative_links', $v ); }
	return $v;
}

/* -----------------------------------------------------------------------------
 * The seed. For every model, every item: create the post if it is missing (or
 * refill it when $force), set its template, fill its fields, and its SEO.
 * -------------------------------------------------------------------------- */
function rankinai_seed_models( $force = false, $only = null ) {
	$log = array();
	foreach ( rankinai_models() as $m ) {
		if ( $only && ! in_array( $m['name'], (array) $only, true ) ) { continue; }
		$items = is_callable( $m['items'] ?? null ) ? call_user_func( $m['items'] ) : ( $m['items'] ?? array() );
		foreach ( $items as $slug => $item ) {
			$path     = ! empty( $item['parent'] ) ? $item['parent'] . '/' . $slug : $slug;
			$existing = get_page_by_path( $path, OBJECT, $m['post_type'] );
			if ( $existing && ! $force ) { $log[] = "{$m['name']}/$slug: exists, left alone"; continue; }

			$data = $item['data'] ?? array();
			$seo  = $item['seo'] ?? array( '', '' );
			if ( is_callable( $data ) ) {
				$got = call_user_func( $data );
				if ( ! $got || ! is_array( $got[0] ) ) { $log[] = "{$m['name']}/$slug: no data"; continue; }
				list( $data, $st, $sd ) = $got;
				$seo = array( $st, $sd );
			}

			$args = array(
				'post_type'   => $m['post_type'],
				'post_status' => 'publish',
				'post_title'  => $item['title'] ?? $slug,
				'post_name'   => $slug,
				'menu_order'  => (int) ( $item['order'] ?? 0 ),
			);
			if ( ! empty( $item['date'] ) ) { $args['post_date'] = $item['date']; }
			if ( ! empty( $item['excerpt'] ) ) { $args['post_excerpt'] = $item['excerpt']; }
			if ( ! empty( $item['parent'] ) ) {
				$parent = get_page_by_path( $item['parent'], OBJECT, $m['post_type'] );
				if ( $parent ) { $args['post_parent'] = $parent->ID; }
			}
			if ( $existing ) {
				$args['ID'] = $existing->ID;
				$id = wp_update_post( $args );
			} else {
				$id = wp_insert_post( $args );
			}
			if ( ! $id || is_wp_error( $id ) ) { $log[] = "{$m['name']}/$slug: could not save"; continue; }
			if ( 'page' === $m['post_type'] && ! empty( $m['template'] ) ) {
				update_post_meta( $id, '_wp_page_template', $m['template'] );
			}
			if ( ! empty( $item['terms'] ) ) {
				foreach ( $item['terms'] as $tax => $terms ) { wp_set_object_terms( $id, $terms, $tax ); }
			}
			foreach ( rankinai_schema_to_values( $m['schema'], $data ) as $name => $value ) {
				update_field( 'field_ri_' . $m['prefix'] . '_' . $name, $value, $id );
			}
			if ( ! empty( $item['hide_close'] ) ) { update_field( 'field_ri_page_hide_close', 1, $id ); }
			if ( '' !== (string) ( $seo[0] ?? '' ) ) { update_field( 'field_ri_page_seo_title', $seo[0], $id ); }
			if ( '' !== (string) ( $seo[1] ?? '' ) ) { update_field( 'field_ri_page_seo_description', $seo[1], $id ); }
			$log[] = "{$m['name']}/$slug: " . ( $existing ? 'refilled' : 'created' );
		}
	}
	return implode( "\n", $log );
}
/** Kept for the seed script. */
function rankinai_seed_cpts( $force = false ) {
	return rankinai_seed_models( $force );
}

/* -----------------------------------------------------------------------------
 * Field kinds: schema → ACF fields, content → ACF values, ACF values → the
 * template's array. Shared by every model.
 * -------------------------------------------------------------------------- */
function rankinai_icon_choices() {
	$keys = array( 'search', 'pin', 'wrench', 'page', 'spark', 'chat', 'link', 'chart', 'target', 'mail', 'calendar', 'star', 'camera', 'code', 'building', 'shield', 'people', 'refresh', 'split', 'eye', 'clipboard', 'upload', 'pencil', 'sliders', 'ear', 'dot' );
	return array_combine( $keys, $keys );
}

function rankinai_schema_fields( array $schema, string $prefix ) {
	$out = array();
	foreach ( $schema as $name => $def ) {
		$kind  = $def[0];
		$label = $def[1];
		$key   = 'field_ri_' . $prefix . '_' . $name;
		$f     = array( 'key' => $key, 'label' => $label, 'name' => $name );
		if ( ! empty( $def[2] ) && is_string( $def[2] ) ) { $f['instructions'] = $def[2]; }
		switch ( $kind ) {
			case 'text':
				$f['type'] = 'text';
				break;
			case 'area':
				$f += array( 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' );
				break;
			case 'paras':
				$f += array( 'type' => 'textarea', 'rows' => 6, 'new_lines' => '', 'instructions' => 'One paragraph per block, with an empty line between paragraphs.' );
				break;
			case 'lines':
				$f += array( 'type' => 'textarea', 'rows' => 4, 'new_lines' => '', 'instructions' => $f['instructions'] ?? 'One item per line.' );
				break;
			case 'slots':
				$sub = array();
				foreach ( $def[2] as $i => $lab ) { $sub[] = array( 'key' => $key . '_c' . $i, 'label' => $lab, 'name' => 'c' . $i, 'type' => 'text' ); }
				$f += array( 'type' => 'group', 'layout' => 'block', 'sub_fields' => $sub );
				break;
			case 'image':
				$f += array( 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium', 'library' => 'all' );
				break;
			case 'choice':
				$f += array( 'type' => 'select', 'choices' => (array) $def[2], 'allow_null' => 1, 'ui' => 0 );
				unset( $f['instructions'] );
				break;
			case 'bool':
				$f += array( 'type' => 'true_false', 'ui' => 1 );
				break;
			case 'html':
				$f += array( 'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'basic', 'media_upload' => 0 );
				break;
			case 'band':
				$f += array( 'type' => 'button_group', 'choices' => array( '' => 'Default', 'light' => 'Light', 'forest' => 'Forest' ), 'default_value' => '' );
				break;
			case 'photo':
				$f += array( 'type' => 'group', 'layout' => 'block', 'instructions' => 'An Unsplash photograph: free licence, photographer has accepted Unsplash\'s terms, no people, laptops or meeting rooms. Record it in CLAIMS.md.', 'sub_fields' => array(
					array( 'key' => $key . '_photo', 'label' => 'Unsplash photo id (photo-…)', 'name' => 'photo', 'type' => 'text' ),
					array( 'key' => $key . '_user', 'label' => 'Photographer username', 'name' => 'user', 'type' => 'text' ),
					array( 'key' => $key . '_by', 'label' => 'Photographer name', 'name' => 'by', 'type' => 'text' ),
					array( 'key' => $key . '_alt', 'label' => 'Description', 'name' => 'alt', 'type' => 'text' ),
					array( 'key' => $key . '_html', 'label' => 'Unsplash page slug (optional, for reference)', 'name' => 'html', 'type' => 'text' ),
				) );
				break;
			case 'rows':
				$sub = array();
				foreach ( $def[2] as $i => $col ) {
					$sk = $key . '_c' . $i;
					if ( 'image' === $col ) {
						$sub[] = array( 'key' => $sk, 'label' => 'Image', 'name' => 'c' . $i, 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'thumbnail' );
					} elseif ( 'icon' === $col ) {
						$sub[] = array( 'key' => $sk, 'label' => 'Icon', 'name' => 'c' . $i, 'type' => 'select', 'choices' => rankinai_icon_choices(), 'default_value' => 'dot' );
					} elseif ( in_array( $col, array( 'Text', 'Lead' ), true ) ) {
						$sub[] = array( 'key' => $sk, 'label' => $col, 'name' => 'c' . $i, 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' );
					} elseif ( 'Paragraphs' === $col ) {
						$sub[] = array( 'key' => $sk, 'label' => $col, 'name' => 'c' . $i, 'type' => 'textarea', 'rows' => 4, 'new_lines' => '', 'instructions' => 'Blank line between paragraphs.' );
					} else {
						$nullable = '?' === substr( $col, -1 );
						$sub[] = array( 'key' => $sk, 'label' => rtrim( $col, '?' ), 'name' => 'c' . $i, 'type' => 'text', 'instructions' => $nullable ? 'Empty = pending (not measured yet).' : '' );
					}
				}
				$f += array( 'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Add row', 'sub_fields' => $sub );
				break;
			case 'group':
				$f += array( 'type' => 'group', 'layout' => 'block', 'sub_fields' => rankinai_schema_fields( $def[2], $prefix . '_' . $name ) );
				break;
			case 'blocks':
				$f += array( 'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Add block', 'collapsed' => $key . '_title', 'sub_fields' => rankinai_schema_fields( $def[2], $prefix . '_' . $name ) );
				break;
			case 'qs':
				$f += array( 'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Add question', 'sub_fields' => array(
					array( 'key' => $key . '_q', 'label' => 'Question', 'name' => 'q', 'type' => 'text' ),
					array( 'key' => $key . '_a', 'label' => 'Answer', 'name' => 'a', 'type' => 'textarea', 'rows' => 4, 'new_lines' => '', 'instructions' => 'One paragraph per block, with an empty line between paragraphs.' ),
				) );
				break;
		}
		$out[] = $f;
	}
	return $out;
}

/* -----------------------------------------------------------------------------
 * Flat data array → ACF values (keyed by field name), for the seed.
 * -------------------------------------------------------------------------- */
function rankinai_schema_to_values( array $schema, array $data ) {
	$out = array();
	foreach ( $schema as $name => $def ) {
		if ( ! array_key_exists( $name, $data ) ) { continue; }
		$v = $data[ $name ];
		switch ( $def[0] ) {
			case 'paras':
				$out[ $name ] = implode( "\n\n", (array) $v );
				break;
			case 'lines':
				$out[ $name ] = implode( "\n", (array) $v );
				break;
			case 'slots':
				$row = array();
				foreach ( array_values( (array) $v ) as $i => $cell ) { $row[ 'c' . $i ] = (string) $cell; }
				$out[ $name ] = $row;
				break;
			case 'image':
				$out[ $name ] = rankinai_import_image( (string) $v );
				break;
			case 'bool':
				$out[ $name ] = $v ? 1 : 0;
				break;
			case 'rows':
				$rows = array();
				foreach ( (array) $v as $tuple ) {
					$row = array();
					foreach ( array_values( (array) $tuple ) as $i => $cell ) { $row[ 'c' . $i ] = ( 'image' === ( $def[2][ $i ] ?? '' ) ) ? rankinai_import_image( (string) $cell ) : ( is_array( $cell ) ? implode( "

", $cell ) : (string) $cell ); }
					$rows[] = $row;
				}
				$out[ $name ] = $rows;
				break;
			case 'group':
				$out[ $name ] = rankinai_schema_to_values( $def[2], (array) $v );
				break;
			case 'blocks':
				$list = isset( $v['title'] ) || isset( $v['eyebrow'] ) ? array( $v ) : (array) $v;
				$out[ $name ] = array_map( function ( $b ) use ( $def ) { return rankinai_schema_to_values( $def[2], (array) $b ); }, $list );
				break;
			case 'qs':
				$out[ $name ] = array_map( function ( $q ) {
					return array( 'q' => (string) $q[0], 'a' => implode( "\n\n", (array) $q[1] ) );
				}, (array) $v );
				break;
			default: // text, area, band, photo
				$out[ $name ] = $v;
		}
	}
	return $out;
}

/* -----------------------------------------------------------------------------
 * ACF values → the flat data array the template reads. Empty parts are
 * dropped so the template's guards skip them, exactly as a data file that
 * never set the key.
 * -------------------------------------------------------------------------- */
function rankinai_split_paras( $s ) {
	$s = trim( str_replace( "\r\n", "\n", (string) $s ) );
	return '' === $s ? array() : array_values( array_filter( array_map( 'trim', preg_split( "/\n\s*\n/", $s ) ), 'strlen' ) );
}
function rankinai_split_lines( $s ) {
	$s = trim( str_replace( "\r\n", "\n", (string) $s ) );
	return '' === $s ? array() : array_values( array_filter( array_map( 'trim', explode( "\n", $s ) ), 'strlen' ) );
}

function rankinai_schema_to_data( array $schema, $values ) {
	$values = is_array( $values ) ? $values : array();
	$out    = array();
	foreach ( $schema as $name => $def ) {
		$v = $values[ $name ] ?? null;
		switch ( $def[0] ) {
			case 'image':
				if ( is_numeric( $v ) ) { $v = wp_get_attachment_url( (int) $v ); }
				if ( is_array( $v ) ) { $v = $v['url'] ?? ''; }
				$v = trim( (string) $v );
				if ( '' !== $v ) { $out[ $name ] = $v; }
				break;
			case 'bool':
				if ( ! empty( $v ) ) { $out[ $name ] = true; }
				break;
			case 'text':
			case 'area':
			case 'html':
			case 'choice':
			case 'band':
				$v = trim( (string) $v );
				if ( '' !== $v ) { $out[ $name ] = $v; }
				break;
			case 'paras':
				$p = rankinai_split_paras( $v );
				if ( $p ) { $out[ $name ] = $p; }
				break;
			case 'lines':
				$l = rankinai_split_lines( $v );
				if ( $l ) { $out[ $name ] = $l; }
				break;
			case 'slots':
				$cells = array();
				for ( $i = 0; $i < count( $def[2] ); $i++ ) { $cells[] = trim( (string) ( $v[ 'c' . $i ] ?? '' ) ); }
				if ( implode( '', $cells ) !== '' ) { $out[ $name ] = $cells; }
				break;
			case 'photo':
				if ( is_array( $v ) && '' !== trim( (string) ( $v['photo'] ?? '' ) ) ) {
					$out[ $name ] = array_map( 'trim', array_map( 'strval', $v ) );
				}
				break;
			case 'rows':
				$rows = array();
				foreach ( (array) $v as $row ) {
					$cells = array();
					for ( $i = 0; $i < count( $def[2] ); $i++ ) {
						$cell = $row[ 'c' . $i ] ?? '';
						if ( is_array( $cell ) ) { $cell = $cell['url'] ?? ''; }
						if ( 'image' === $def[2][ $i ] && is_numeric( $cell ) ) { $cell = wp_get_attachment_url( (int) $cell ); }
						$cells[] = trim( (string) $cell );
					}
					if ( implode( '', $cells ) !== '' ) {
						foreach ( $def[2] as $i => $col ) {
							if ( 'Paragraphs' === $col ) { $cells[ $i ] = rankinai_split_paras( $cells[ $i ] ); }
							elseif ( '?' === substr( $col, -1 ) && '' === $cells[ $i ] ) { $cells[ $i ] = null; }
						}
						$rows[] = $cells;
					}
				}
				if ( $rows ) { $out[ $name ] = $rows; }
				break;
			case 'group':
				$g = rankinai_schema_to_data( $def[2], $v );
				if ( $g ) { $out[ $name ] = $g; }
				break;
			case 'blocks':
				$list = array();
				foreach ( (array) $v as $b ) {
					$d = rankinai_schema_to_data( $def[2], $b );
					if ( $d ) { $list[] = $d; }
				}
				if ( $list ) { $out[ $name ] = $list; }
				break;
			case 'qs':
				$list = array();
				foreach ( (array) $v as $q ) {
					$question = trim( (string) ( $q['q'] ?? '' ) );
					if ( '' !== $question ) { $list[] = array( $question, rankinai_split_paras( $q['a'] ?? '' ) ); }
				}
				if ( $list ) { $out[ $name ] = $list; }
				break;
		}
	}
	return $out;
}

