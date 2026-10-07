<?php
/**
 * ACF field groups, registered in code
 * =============================================================================
 * Never through the ACF admin. The definition lives here and moves with the
 * partials in git, as on Sokkies. A new section type is one entry in
 * rankinai_section_layouts() plus one partial in
 * template-parts/sections/section-{name}.php.
 *
 * EVERY FIELD FALLS BACK. A field left empty prints the flat build's copy, so
 * a section looks right the moment it is added. The instructions under each
 * field say so, and say what the default is where it is not obvious.
 *
 * THE FIRST HOUSE RULE STILL HOLDS HERE. A figure field left empty renders the
 * pending state, never a guess. A quote is never written on a client's behalf.
 * =============================================================================
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/* -----------------------------------------------------------------------------
 * Small builders, so each layout reads as a list of fields rather than a wall
 * of arrays. $k is the layout name: every key is field_ri_{layout}_{name}.
 * -------------------------------------------------------------------------- */
function ri_f( $k, $type, $name, $label, $extra = array() ) {
	return array_merge( array(
		'key'   => 'field_ri_' . $k . '_' . $name,
		'label' => $label,
		'name'  => $name,
		'type'  => $type,
	), $extra );
}
function ri_text( $k, $name, $label, $instr = 'Empty = the original copy.' ) {
	return ri_f( $k, 'text', $name, $label, array( 'instructions' => $instr ) );
}
function ri_area( $k, $name, $label, $instr = 'Empty = the original copy.', $rows = 3 ) {
	return ri_f( $k, 'textarea', $name, $label, array( 'instructions' => $instr, 'rows' => $rows, 'new_lines' => '' ) );
}
function ri_link( $k, $name, $label, $instr = 'Empty = the original link.' ) {
	return ri_f( $k, 'link', $name, $label, array( 'instructions' => $instr, 'return_format' => 'array' ) );
}
function ri_image( $k, $name, $label, $instr = 'Empty = the original image.' ) {
	return ri_f( $k, 'image', $name, $label, array( 'instructions' => $instr, 'return_format' => 'array', 'preview_size' => 'medium' ) );
}
function ri_rich( $k, $name, $label, $instr = 'Empty = the original copy.' ) {
	return ri_f( $k, 'wysiwyg', $name, $label, array( 'instructions' => $instr, 'tabs' => 'visual', 'toolbar' => 'basic', 'media_upload' => 0 ) );
}
function ri_icon( $k, $name = 'icon' ) {
	$keys = array( 'search', 'pin', 'wrench', 'page', 'spark', 'chat', 'link', 'chart', 'target', 'mail', 'calendar', 'star', 'camera', 'code', 'building', 'shield', 'people', 'refresh', 'split', 'eye', 'clipboard', 'upload', 'pencil', 'sliders', 'ear', 'dot' );
	return ri_f( $k, 'select', $name, 'Icon', array( 'choices' => array_combine( $keys, $keys ), 'default_value' => 'dot', 'ui' => 0 ) );
}
function ri_rows( $k, $name, $label, $sub, $instr = 'Empty = the original items.', $extra = array() ) {
	return ri_f( $k, 'repeater', $name, $label, array_merge( array(
		'instructions' => $instr,
		'layout'       => 'block',
		'button_label' => 'Add',
		'sub_fields'   => $sub,
	), $extra ) );
}
function ri_layout( $name, $label, $category, $sub ) {
	return array(
		'key'        => 'layout_ri_' . $name,
		'name'       => $name,
		'label'      => $label,
		'display'    => 'block',
		'acfe_flexible_category' => array( $category ),
		'sub_fields' => $sub,
	);
}

/* -----------------------------------------------------------------------------
 * The section layouts. Home page first; the other page types follow in later
 * passes, each as new layouts here and new partials.
 * -------------------------------------------------------------------------- */
function rankinai_section_layouts() {
	$L = array();

	$k = 'home_hero';
	$L[] = ri_layout( $k, 'Home: hero', 'Home', array(
		ri_text( $k, 'eyebrow', 'Eyebrow', 'The one line on the first screen that says what we are and who we serve. Empty = "Digital marketing for firms that sell expertise".' ),
		ri_text( $k, 'title', 'Headline', 'Empty = "Be the firm they find. And the one they choose."' ),
		ri_area( $k, 'sub', 'Subhead' ),
		ri_area( $k, 'sub2', 'Second line' ),
		ri_link( $k, 'button', 'Button', 'Empty = Get your growth audit, to /growth-audit/ (it opens the audit modal).' ),
		ri_link( $k, 'link', 'Quiet link beside the button', 'Empty = Book a 20-minute call, to /call/.' ),
	) );

	$k = 'stories';
	$L[] = ri_layout( $k, 'Success stories: three cases', 'Proof', array(
		ri_text( $k, 'title', 'Heading' ),
		ri_area( $k, 'note', 'Note under the heading' ),
		ri_rows( $k, 'cases', 'Cases', array(
			ri_image( $k, 'photo', 'Photograph', 'A client photograph needs the client\'s written okay before launch. See CLAIMS.md.' ),
			ri_text( $k, 'photo_alt', 'Photograph description', 'For screen readers. Say what is in the picture.' ),
			ri_text( $k, 'name', 'Client name', 'Only real, signed-off clients.' ),
			ri_text( $k, 'meta', 'Sector, country, relationship', 'Separate with " | ", e.g. "Digital agency | Netherlands | Our partner".' ),
			ri_text( $k, 'metric', 'Figure', 'Only a real, sourced figure. EMPTY = the pending state ("Figure with the client for sign-off"). Never a guess.' ),
			ri_text( $k, 'metric_label', 'What the figure measures', 'e.g. "organic traffic in three months".' ),
			ri_area( $k, 'text', 'Summary', '' ),
			ri_link( $k, 'link', 'Link', 'Empty = Read their story, to /success-stories/.' ),
		), 'Empty = the three cases on the home page today. The first case is the large one.', array( 'max' => 3 ) ),
		ri_link( $k, 'more', 'Link under the cases', 'Empty = Explore our success stories.' ),
	) );

	$k = 'opportunity';
	$L[] = ri_layout( $k, 'Home: the opportunity', 'Home', array(
		ri_text( $k, 'title', 'Heading' ),
		ri_area( $k, 'intro', 'Intro beside the heading' ),
		ri_text( $k, 'lead', 'Line above the three questions' ),
		ri_rows( $k, 'items', 'Questions', array(
			ri_icon( $k ),
			ri_text( $k, 'name', 'Question', '' ),
			ri_area( $k, 'text', 'Answer', '', 2 ),
		) ),
		ri_text( $k, 'close', 'Closing line' ),
	) );

	$k = 'services_scroll';
	$L[] = ri_layout( $k, 'Home: the six services, pinned scroll', 'Home', array(
		ri_text( $k, 'title', 'Heading' ),
		ri_area( $k, 'note', 'Note' ),
		ri_rows( $k, 'services', 'Services', array(
			ri_image( $k, 'image', 'Picture', 'Square. Decorative, so it has no description.' ),
			ri_text( $k, 'kicker', 'Service name', '' ),
			ri_text( $k, 'title', 'Title', '' ),
			ri_area( $k, 'lead', 'Text', '', 3 ),
			ri_link( $k, 'link', 'Link', '' ),
		), 'Empty = the six services. The pinned verb changes from "Get found." to "Get booked." at the fourth, so keep six, in this order.', array( 'max' => 6 ) ),
	) );

	$k = 'method';
	$L[] = ri_layout( $k, 'Home: how we start, four steps', 'Home', array(
		ri_text( $k, 'title', 'Heading' ),
		ri_rows( $k, 'steps', 'Steps', array(
			ri_text( $k, 'name', 'Step name (the tab)', '' ),
			ri_text( $k, 'lead', 'Bold opening of the text', '' ),
			ri_area( $k, 'text', 'Rest of the text', '', 3 ),
		), 'Empty = the four steps. Exactly four: each has its own animated drawing.', array( 'max' => 4 ) ),
		ri_rows( $k, 'marks', 'Three marks in the plan panel', array(
			ri_text( $k, 'mark', 'Mark', '' ),
		), 'Empty = One plan, Connected expertise, Clear priorities.', array( 'max' => 3, 'layout' => 'table' ) ),
		ri_rich( $k, 'plan', 'Plan panel text' ),
		ri_link( $k, 'button', 'Button', 'Empty = Explore our approach, to /how-we-work/.' ),
		ri_link( $k, 'button2', 'Second button', 'Empty = View packages, to /pricing/.' ),
	) );

	$k = 'team';
	$L[] = ri_layout( $k, 'Team: heading and photo marquee', 'Team', array(
		ri_text( $k, 'title', 'Heading', 'Empty = "You bring the expertise. We help it reach further."' ),
		ri_rich( $k, 'lead', 'Text beside the heading' ),
		ri_link( $k, 'link', 'Link under the text', 'Empty = Meet the people behind RankinAI, to /about/.' ),
	) );

	$k = 'commitments';
	$L[] = ri_layout( $k, 'Commitments: card slider', 'Proof', array(
		ri_text( $k, 'title', 'Heading' ),
		ri_rows( $k, 'items', 'Commitments', array(
			ri_icon( $k ),
			ri_text( $k, 'name', 'Commitment', '' ),
			ri_area( $k, 'text', 'Text', '', 3 ),
		), 'Empty = the four commitments. Each one must be something a client can hold us to.' ),
	) );

	$k = 'faq';
	$L[] = ri_layout( $k, 'Questions: accordion', 'Questions', array(
		ri_text( $k, 'title', 'Heading' ),
		ri_link( $k, 'link', 'Link beside the heading', 'Empty = Every question, answered in full, to /questions/.' ),
		ri_rows( $k, 'items', 'Questions', array(
			ri_text( $k, 'q', 'Question', '' ),
			ri_rich( $k, 'a', 'Answer', '' ),
		), 'Empty = the six home page questions. One answer opens at a time.' ),
	) );

	return $L;
}

add_action( 'acf/init', function () {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) { return; }

	/* Website settings: the site details every page reads. */
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page( array(
			'page_title' => 'Website settings',
			'menu_title' => 'Website settings',
			'menu_slug'  => 'rankinai-settings',
			'capability' => 'edit_theme_options',
			'icon_url'   => 'dashicons-admin-site-alt3',
			'position'   => 59,
			'redirect'   => false,
		) );
	}
	/* One group in three tabs: the header, the site details, the footer. The
	   header and footer fields come from inc/chrome.php, and were groups of
	   their own until 7 Oct 2026. Tabs only change the layout: every field
	   keeps its key and name, so saved values are unchanged. */
	$tab = function ( $name, $label ) {
		return ri_f( 'site', 'tab', 'tab_' . $name, $label, array( 'name' => '', 'placement' => 'top', 'endpoint' => 0 ) );
	};
	acf_add_local_field_group( array(
		'key'      => 'group_ri_settings',
		'title'    => 'Website settings',
		'style'    => 'seamless',
		'fields'   => array_merge(
			array( $tab( 'header', 'Header' ) ),
			function_exists( 'rankinai_chrome_fields' ) ? rankinai_chrome_fields( 'header' ) : array(),
			array( $tab( 'details', 'Site details' ) ),
			array(
				ri_text( 'site', 'site_name', 'Business name', 'Empty = RankinAI.' ),
				ri_text( 'site', 'site_tagline', 'Tagline', 'Empty = Digital marketing for firms that sell expertise. Also the fallback meta description.' ),
				ri_text( 'site', 'site_email', 'Email', 'Empty = hello@rankinai.com.' ),
				ri_text( 'site', 'site_phone', 'Phone', 'Empty = +91 906 9710 000.' ),
				ri_text( 'site', 'site_offices', 'Offices (short line)', 'Empty = Zirakpur, Punjab, India. Shown in the footer.' ),
				ri_text( 'site', 'site_address', 'Full address', 'Empty = the Tricity Plaza address. Shown on the contact page.' ),
				ri_text( 'site', 'site_hours', 'Office hours', 'Empty = Monday to Friday, 9am–6pm IST.' ),
				ri_f( 'site', 'text', 'site_whatsapp', 'WhatsApp number', array( 'default_value' => '+91 90697 10000', 'instructions' => 'The green button at the bottom right of every page opens a WhatsApp chat with this number. With the country code. Empty = +91 90697 10000.' ) ),
				ri_text( 'site', 'site_call', 'Call booking link', 'While the /call/ page is not published, every link to /call/ goes here instead: each "Book a 20-minute call", the header, the closing section, the two pricing buttons. Empty = https://calendly.com/kulwant-saini/rankinai?month=2026-09. Publish the /call/ page again and the links go back to it.' ),
				ri_f( 'site', 'post_object', 'site_form_audit', 'Growth audit form', array( 'post_type' => array( 'wpcf7_contact_form' ), 'return_format' => 'id', 'allow_null' => 1, 'instructions' => 'The Contact Form 7 form behind every "Get your growth audit" (the modal and /growth-audit/). Edit its fields and email under Contact.' ) ),
				ri_f( 'site', 'post_object', 'site_form_contact', 'Contact form', array( 'post_type' => array( 'wpcf7_contact_form' ), 'return_format' => 'id', 'allow_null' => 1, 'instructions' => 'The Contact Form 7 form on /contact/.' ) ),
			),
			array( $tab( 'footer', 'Footer' ) ),
			function_exists( 'rankinai_chrome_fields' ) ? rankinai_chrome_fields( 'footer' ) : array()
		),
		'location' => array( array( array( 'param' => 'options_page', 'operator' => '==', 'value' => 'rankinai-settings' ) ) ),
	) );

	/* Page settings: SEO and the close, on every page. */
	acf_add_local_field_group( array(
		'key'        => 'group_ri_page',
		'title'      => 'Page settings',
		'position'   => 'side',
		'fields'     => array(
			ri_text( 'page', 'seo_title', 'SEO title', 'Empty = the page title.' ),
			ri_area( 'page', 'seo_description', 'Meta description', 'Empty = the site tagline.', 3 ),
			ri_f( 'page', 'true_false', 'hide_close', 'Hide the closing section', array(
				'instructions' => 'Only when the page already makes the close\'s two asks itself, as the contact page does.',
				'ui'           => 1,
			) ),
		),
		'location'   => array(
			array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'page' ) ),
			array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'rankinai_service' ) ),
			array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'rankinai_industry' ) ),
		),
		'menu_order' => 0,
	) );

	/* The page builder. */
	acf_add_local_field_group( array(
		'key'      => 'group_ri_sections',
		'title'    => 'Sections',
		'fields'   => array(
			array(
				'key'          => 'field_ri_sections',
				'label'        => 'Sections',
				'name'         => 'sections',
				'type'         => 'flexible_content',
				'instructions' => 'Build the page from sections. Drag to reorder. Every field left empty shows the original copy.',
				'button_label' => 'Add section',
				// ACF Extended, when active: a picker with categories.
				'acfe_flexible_advanced'        => 1,
				'acfe_flexible_stylised_button' => 1,
				'acfe_flexible_modal'           => array(
					'acfe_flexible_modal_enabled'    => '1',
					'acfe_flexible_modal_title'      => 'Choose a section',
					'acfe_flexible_modal_size'       => 'large',
					'acfe_flexible_modal_col'        => '3',
					'acfe_flexible_modal_categories' => '1',
				),
				'layouts'      => rankinai_section_layouts(),
			),
		),
		// Pages on the default template only. A page using a model template
		// (About, Pricing, ...) gets that model's fields instead.
		'location'   => array( array(
			array( 'param' => 'post_type', 'operator' => '==', 'value' => 'page' ),
			array( 'param' => 'page_template', 'operator' => '==', 'value' => 'default' ),
		) ),
		'menu_order' => 1,
	) );
} );
