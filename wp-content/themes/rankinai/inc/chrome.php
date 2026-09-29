<?php
/**
 * The header and footer, edited on Website settings
 * =============================================================================
 * Added 29 Sep 2026. Two field groups on the Website settings page, "Header"
 * and "Footer", described by a schema in the model engine's field kinds (see
 * inc/model-engine.php), so the fields, the seed and the array header.php and
 * footer.php read come from one definition.
 *
 *   Header  the menu (plain links, and drop-down panels with columns of links
 *           and an offer), the text link and the button on the right.
 *   Footer  the about text and strapline, the link columns, the contact
 *           column's labels, and the links after the copyright.
 *
 * The email, phone and office line in the footer are the site details above
 * them on the same page, as before.
 *
 * EMPTY FALLS BACK. A field left empty (or a list with no rows) shows the
 * original, as the site details do, so the header and footer never go blank.
 * The originals are rankinai_chrome_defaults(), copied from the flat build.
 *
 * $FOOTER in inc/site.php is NOT this. It stays as data because the service
 * and industry templates use it to group their pages ("Getting found",
 * "Design and construction"). Changing a footer column here changes the
 * footer only.
 *
 * The menu is a repeater rather than wp_nav_menu() for the reason given in
 * inc/site.php: a panel of columns with an offer is not something a WordPress
 * menu can hold.
 * =============================================================================
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function rankinai_chrome_schema() {
	$two = array( 'Label', 'Link' );
	return array(
		'header' => array(
			'nav'       => array( 'blocks', 'Menu', array(
				'label' => array( 'text', 'Label' ),
				'link'  => array( 'text', 'Link', 'For a plain link, e.g. /pricing/. Leave empty for a drop-down panel, and fill in its columns.' ),
				'cols'  => array( 'blocks', 'Drop-down columns', array(
					'label' => array( 'text', 'Column heading' ),
					'items' => array( 'rows', 'Links', array( 'Name', 'Link', 'Text' ) ),
				) ),
				'offer' => array( 'group', 'Drop-down offer (last column)', array(
					'label' => array( 'text', 'Heading' ),
					'text'  => array( 'area', 'Text' ),
					'cta'   => array( 'slots', 'Button', $two ),
				) ),
			) ),
			'secondary' => array( 'slots', 'Text link beside the button', $two ),
			'primary'   => array( 'slots', 'Button', $two ),
		),
		'footer' => array(
			'about'        => array( 'paras', 'About text' ),
			'strap'        => array( 'text', 'Strapline' ),
			'cols'         => array( 'blocks', 'Link columns', array(
				'label' => array( 'text', 'Heading' ),
				'quiet' => array( 'bool', 'Plain heading', 'On: the heading is grey, not clay, and the column is quieter (as Company is).' ),
				'links' => array( 'rows', 'Links', $two ),
			) ),
			'contactLabel' => array( 'text', 'Contact column heading', 'The email, phone and office line are the site details above.' ),
			'phoneLabel'   => array( 'text', 'Phone label' ),
			'officesLabel' => array( 'text', 'Offices label' ),
			'legal'        => array( 'rows', 'Links after the copyright', $two ),
		),
	);
}

/** The original header and footer, from the flat build. */
function rankinai_chrome_defaults() {
	global $NAV, $FOOTER;
	return array(
		'header' => array(
			'nav'       => $NAV,
			'secondary' => array( 'Book a 20-minute call', '/call/' ),
			'primary'   => array( 'Get your growth audit', '/growth-audit/' ),
		),
		'footer' => array(
			'about'        => array(
				'RankinAI helps design and construction firms, recruitment and HR businesses, and professional service firms attract relevant enquiries and turn more of them into opportunities.',
				'Search, content, paid advertising, websites, reputation and follow-up, connected around your business goals.',
			),
			'strap'        => 'Get found. Get booked.',
			'cols'         => array(
				array( 'label' => 'Getting found', 'links' => $FOOTER['found'] ),
				array( 'label' => 'Getting booked', 'links' => $FOOTER['booked'] ),
				array( 'label' => 'Company', 'quiet' => true, 'links' => $FOOTER['company'] ),
			),
			'contactLabel' => 'Talk to us',
			'phoneLabel'   => 'Phone',
			'officesLabel' => 'Offices',
			'legal'        => array( array( 'Privacy', '/privacy/' ), array( 'Terms', '/terms/' ) ),
		),
	);
}

/** 'header' or 'footer': the saved values over the originals, field by field. */
function rankinai_chrome( $part ) {
	static $cache = array();
	if ( isset( $cache[ $part ] ) ) { return $cache[ $part ]; }
	$schema   = rankinai_chrome_schema()[ $part ];
	$defaults = rankinai_chrome_defaults()[ $part ];
	$saved    = function_exists( 'get_field' ) ? get_field( 'site_' . $part, 'option' ) : null;
	$data     = rankinai_schema_to_data( $schema, is_array( $saved ) ? $saved : array() );
	return $cache[ $part ] = array_replace( $defaults, $data );
}

/** A link as stored: a root-relative path goes through url(), anything else
 *  (a full URL, mailto:, #) is used as it is. */
function rankinai_href( $href ) {
	$href = trim( (string) $href );
	if ( '' === $href ) { return url( '/' ); }
	return ( '/' === $href[0] && '/' !== ( $href[1] ?? '' ) ) ? url( $href ) : $href;
}

/* The two field groups, under the site details on Website settings. */
add_action( 'acf/init', function () {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) { return; }
	$labels = array( 'header' => 'Header', 'footer' => 'Footer' );
	$order  = 1;
	foreach ( rankinai_chrome_schema() as $part => $schema ) {
		acf_add_local_field_group( array(
			'key'        => 'group_ri_' . $part,
			'title'      => $labels[ $part ],
			'fields'     => rankinai_schema_fields( array(
				'site_' . $part => array( 'group', '', $schema ),
			), 'chrome' ),
			'location'   => array( array( array( 'param' => 'options_page', 'operator' => '==', 'value' => 'rankinai-settings' ) ) ),
			'menu_order' => $order++,
		) );
	}
} );

/** Fill the header and footer fields with the originals, if never saved (or
 *  always, when $force). */
function rankinai_seed_chrome( $force = false ) {
	if ( ! function_exists( 'update_field' ) ) { return 'header/footer: ACF is not active'; }
	$log      = array();
	$schema   = rankinai_chrome_schema();
	$defaults = rankinai_chrome_defaults();
	foreach ( array( 'header', 'footer' ) as $part ) {
		$saved = get_field( 'site_' . $part, 'option' );
		if ( ! $force && is_array( $saved ) && rankinai_schema_to_data( $schema[ $part ], $saved ) ) {
			$log[] = "$part: exists, left alone";
			continue;
		}
		update_field( 'field_ri_chrome_site_' . $part, rankinai_schema_to_values( $schema[ $part ], $defaults[ $part ] ), 'option' );
		$log[] = "$part: " . ( $force ? 'refilled' : 'filled' );
	}
	return implode( "\n", $log );
}
