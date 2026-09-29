<?php
/**
 * Model: team members. A post type with no pages of its own: each member is
 * one card in "Meet the team" on /about/, which lists them all in their Order
 * (page-templates/about.php). The post title is the person's name. Until
 * 29 Sep 2026 they were a table on the about page itself.
 *
 * WHO GOES ON HERE. Read the note at the top of the flat build's about.php
 * before adding anyone: their own LinkedIn headline has to say RankinAI.
 *
 * THE FIGURES. Kulwant's and Reena's years and project counts come from
 * teqdeft.com/about. Abhishek Thakur's and Rahul Verma's are placeholder
 * values Kulwant asked for, logged in CLAIMS.md, and seeded with "Placeholder
 * figures" switched on so they show up as such in the admin. Neither may go
 * live as it stands. A LinkedIn URL is only ever the person's real profile.
 *
 * PORTRAITS. A member with no portrait renders a name plate. The seed names
 * team-kulwant and team-reena, which it imports once the files land in
 * assets/images, as on the flat build.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* The title box asks for a name. The order is 'sortable' below: drag the
   rows in the admin list, and a new member starts at the end. */
add_filter( 'enter_title_here', function ( $text, $post ) {
	return 'rankinai_team' === $post->post_type ? 'Name' : $text;
}, 10, 2 );

$member = function ( $order, $name, $role, $photo, $years, $work, $li, $draft = false ) {
	return array(
		'title' => $name,
		'order' => $order,
		'data'  => array(
			'role'  => $role,
			'photo' => $photo,
			'years' => $years,
			'work'  => $work,
			'li'    => $li,
			'draft' => $draft,
		),
	);
};

return array(
	'name'      => 'team',
	'post_type' => 'rankinai_team',
	'label'     => 'Team member',
	'prefix'    => 'team',
	'sortable'  => true,
	'register'  => array(
		'labels'        => array( 'name' => 'Team', 'singular_name' => 'Team member', 'add_new_item' => 'Add team member', 'edit_item' => 'Edit team member', 'all_items' => 'All team members', 'not_found' => 'No team members found' ),
		'public'        => false,
		'show_ui'       => true,
		'show_in_rest'  => true,
		'supports'      => array( 'title', 'page-attributes' ),
		'menu_position' => 24,
		'menu_icon'     => 'dashicons-groups',
	),
	'schema'    => array(
		'role'  => array( 'text', 'Role' ),
		'photo' => array( 'image', 'Portrait', 'Square, 560px or larger. Empty shows a name plate.' ),
		'years' => array( 'text', 'Experience', 'For example "16 years". A sourced figure only. Empty hides the line.' ),
		'work'  => array( 'text', 'Delivered', 'For example "240+ projects". A sourced figure only. Empty hides the line.' ),
		'li'    => array( 'text', 'LinkedIn URL', 'Their own profile, never a guess. Empty hides the link.' ),
		'draft' => array( 'bool', 'Placeholder figures', 'On while Experience and Delivered are placeholder values (see CLAIMS.md). Replace or clear them before launch, then switch this off. Not shown on the site.' ),
	),
	'items'     => array(
		'kulwant-singh'   => $member( 1, 'Kulwant Singh', 'Founder', 'team-kulwant', '16 years', '240+ projects', 'https://www.linkedin.com/in/kulwant-singh-59338717a' ),
		'reena-devi'      => $member( 2, 'Reena Devi', 'Co-founder', 'team-reena', '14 years', '220+ projects', 'https://www.linkedin.com/in/reena-devi-2k10' ),
		'abhishek-thakur' => $member( 3, 'Abhishek Thakur', 'Senior SEO executive', '', '6 years', '80+ projects', '', true ),
		'rahul-verma'     => $member( 4, 'Rahul Verma', 'SEO specialist', '', '4 years', '50+ projects', '', true ),
	),
);
