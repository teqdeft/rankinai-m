<?php
/**
 * RankinAI theme
 * =============================================================================
 * Built 29 Sep 2026 on the Sokkies pattern:
 *
 *   · every page is a stack of sections, one ACF Flexible Content field
 *     ('sections'), one layout per section type, one partial per layout in
 *     template-parts/sections/section-{layout}.php
 *   · every field falls back to the original copy when it is left empty, so a
 *     page looks right the moment a section is added, before anything is typed
 *   · all ACF fields are registered in code (inc/acf-fields.php), never in the
 *     ACF admin, so the definition travels with the partials in git
 *   · site-wide details live on one options page, "Website settings"
 *
 * The markup is the flat-file build's, moved across unchanged where it could
 * be. The helpers the flat build used (url(), asset(), e(), btn_arrow(),
 * svc_icon_svg()) keep their names in inc/helpers.php so the partials read the
 * same in both places.
 *
 * The house rules in the repo's CLAUDE.md apply here in full, the first most of
 * all: no figure, name or quote goes on a page unless it is real and sourced.
 * =============================================================================
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

function rankinai_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
}
add_action( 'after_setup_theme', 'rankinai_setup' );

/** A cache-busting version for one asset: its own modified time. Replaces the
 *  hand-bumped ASSET_VERSION of the flat build, which was easy to forget. */
function rankinai_asset_version( $path ) {
	$file = get_template_directory() . $path;
	return file_exists( $file ) ? (string) filemtime( $file ) : '1';
}

function rankinai_assets() {
	wp_enqueue_style( 'rankinai-fonts', 'https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;1,6..72,400&family=Public+Sans:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap', array(), null );
	// reset → style → responsive. The order is load-bearing: responsive.css
	// relies on coming last at equal specificity.
	wp_enqueue_style( 'rankinai-reset', get_template_directory_uri() . '/assets/css/reset.css', array(), rankinai_asset_version( '/assets/css/reset.css' ) );
	wp_enqueue_style( 'rankinai-style', get_template_directory_uri() . '/assets/css/style.css', array( 'rankinai-reset' ), rankinai_asset_version( '/assets/css/style.css' ) );
	wp_enqueue_style( 'rankinai-responsive', get_template_directory_uri() . '/assets/css/responsive.css', array( 'rankinai-style' ), rankinai_asset_version( '/assets/css/responsive.css' ) );

	wp_enqueue_script( 'gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js', array(), '3.12.5', true );
	wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js', array( 'gsap' ), '3.12.5', true );
	wp_enqueue_script( 'lenis', 'https://cdn.jsdelivr.net/npm/lenis@1.3.26/dist/lenis.min.js', array(), '1.3.26', true );
	wp_enqueue_script( 'rankinai-script', get_template_directory_uri() . '/assets/js/script.js', array( 'gsap', 'gsap-scrolltrigger', 'lenis' ), rankinai_asset_version( '/assets/js/script.js' ), true );
}
add_action( 'wp_enqueue_scripts', 'rankinai_assets' );

/** The fonts stylesheet is cross-origin: preconnect before it, as the flat
 *  build's <head> did. */
add_filter( 'wp_resource_hints', function ( $urls, $relation ) {
	if ( 'preconnect' === $relation ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $urls;
}, 10, 2 );

/** Pre-hide the first screen only, and only if motion is welcome. Printed
 *  first in <head> so nothing flashes before it. A failsafe in script.js
 *  removes the class if GSAP has not started, so a blocked CDN degrades to a
 *  static page rather than an empty one. */
add_action( 'wp_head', function () {
	echo "<script>(function(){if(!window.matchMedia('(prefers-reduced-motion: reduce)').matches){document.documentElement.classList.add('anim');}})();</script>\n";
}, 1 );

/** Favicon and touch icon, from the theme's own images. */
add_action( 'wp_head', function () {
	echo '<link rel="icon" href="' . esc_url( asset( 'images/favicon.png' ) ) . '" sizes="any">' . "\n";
	echo '<link rel="apple-touch-icon" href="' . esc_url( asset( 'images/tile-forest-512.png' ) ) . '">' . "\n";
} );

/** Keep search engines out of every copy that is not the real site. The same
 *  switch as SITE_NOINDEX in the flat build, set per environment in
 *  wp-config.php as RANKINAI_NOINDEX. Missing counts as on. */
add_filter( 'wp_robots', function ( $robots ) {
	if ( ! defined( 'RANKINAI_NOINDEX' ) || RANKINAI_NOINDEX ) {
		$robots['noindex']  = true;
		$robots['nofollow'] = true;
	}
	return $robots;
} );

/** The meta description: the page's own field if set, then the excerpt, then
 *  the site tagline. */
add_action( 'wp_head', function () {
	$desc = '';
	if ( is_singular() && function_exists( 'get_field' ) ) {
		$desc = (string) get_field( 'seo_description' );
	}
	if ( '' === $desc && is_singular() && has_excerpt() ) {
		$desc = get_the_excerpt();
	}
	if ( '' === $desc ) {
		$desc = rankinai_site( 'tagline' );
	}
	echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $desc ) ) . '">' . "\n";
}, 2 );

/** The document title: the page's own SEO title if set. */
add_filter( 'pre_get_document_title', function ( $title ) {
	if ( is_singular() && function_exists( 'get_field' ) ) {
		$own = (string) get_field( 'seo_title' );
		if ( '' !== $own ) { return $own; }
	}
	if ( is_front_page() ) {
		return rankinai_site( 'name' ) . ' — ' . rankinai_site( 'tagline' );
	}
	return $title;
} );

/** Pages are built with sections, not the block editor. As on Sokkies. */
add_filter( 'use_block_editor_for_post_type', function ( $use, $post_type ) {
	return 'page' === $post_type ? false : $use;
}, 10, 2 );
add_action( 'init', function () {
	remove_post_type_support( 'page', 'editor' );
} );

require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/site.php';
require_once get_template_directory() . '/inc/acf-fields.php';
require_once get_template_directory() . '/inc/section-defaults.php';
require_once get_template_directory() . '/inc/seed.php';
require_once get_template_directory() . '/inc/model-engine.php';
require_once get_template_directory() . '/inc/forms.php';
require_once get_template_directory() . '/inc/chrome.php';
