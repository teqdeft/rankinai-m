<?php
/**
 * Helpers, kept under the flat build's names
 * =============================================================================
 * url(), asset(), e(), is_current() and btn_arrow() do what they did in the
 * flat build's includes/config.php, rewritten for WordPress, so a partial can
 * be moved across without touching its markup. The icon functions below are
 * copied verbatim.
 *
 * rf() and rf_rows() are the Sokkies rule in two functions: a field left empty
 * falls back to the original copy.
 * =============================================================================
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! function_exists( 'url' ) ) {
	/** An internal link. Always call this rather than writing a path by hand.
	 *  A full URL comes back as it is, so a link field may hold one.
	 *
	 *  /call/ is the one path that can leave the site. Every "Book a 20-minute
	 *  call" link is written as /call/ (twelve page and header fields, eight
	 *  templates), and all of them pass through here. While the /call/ page
	 *  is not published they go to the booking link on Website settings
	 *  (Calendly since 29 Sep 2026). Publish the page and they go to it again. */
	function url( string $path = '/' ): string {
		if ( preg_match( '#^(https?:)?//#', $path ) ) { return $path; }
		if ( '/call' === untrailingslashit( '/' . ltrim( $path, '/' ) ) && ! rankinai_call_page_live() ) {
			return rankinai_site( 'call' );
		}
		return home_url( '/' . ltrim( $path, '/' ) );
	}
}

/** True when the /call/ page is published. */
function rankinai_call_page_live(): bool {
	static $live = null;
	if ( null === $live ) {
		$page = get_page_by_path( 'call', OBJECT, 'page' );
		$live = $page && 'publish' === $page->post_status;
	}
	return $live;
}

if ( ! function_exists( 'asset' ) ) {
	/** A theme asset URL, versioned by the theme stylesheet's modified time. */
	function asset( string $path ): string {
		static $v = null;
		if ( null === $v ) { $v = rankinai_asset_version( '/assets/css/style.css' ); }
		return get_template_directory_uri() . '/assets/' . ltrim( $path, '/' ) . '?v=' . $v;
	}
}

if ( ! function_exists( 'e' ) ) {
	/** Escape for output. Short name because it is used constantly. */
	function e( ?string $s ): string {
		return htmlspecialchars( (string) $s, ENT_QUOTES, 'UTF-8' );
	}
}

if ( ! function_exists( 'is_current' ) ) {
	/** True when $path is the page currently being viewed. */
	function is_current( string $path ): bool {
		$here  = untrailingslashit( (string) parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH ) );
		$there = untrailingslashit( (string) parse_url( url( $path ), PHP_URL_PATH ) );
		return $here === $there;
	}
}

if ( ! function_exists( 'btn_arrow' ) ) {
	/** The animated arrow inside a primary button. Decorative. */
	function btn_arrow(): string {
		return '<svg class="btn__arrow" viewBox="0 0 16 16" width="16" height="16" fill="none" aria-hidden="true" focusable="false">'
			. '<path class="btn__arrow-shaft" d="M1.5 8H12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>'
			. '<path class="btn__arrow-head" d="M8.6 4.6 12 8l-3.4 3.4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>'
			. '</svg>';
	}
}

/**
 * A sub field of the current section, or $default when it is empty. Use inside
 * a have_rows() loop, which is where every section partial runs.
 */
function rf( string $name, $default = '' ) {
	if ( ! function_exists( 'get_sub_field' ) ) { return $default; }
	$v = get_sub_field( $name );
	if ( is_string( $v ) ) { $v = trim( $v ); }
	return ( null === $v || '' === $v || false === $v || array() === $v ) ? $default : $v;
}

/**
 * A repeater sub field with empty rows dropped, or $default when nothing is
 * left. A row counts as empty when every value in it is empty, so a half-added
 * row never prints as a blank card.
 */
function rf_rows( string $name, array $default = array() ): array {
	if ( ! function_exists( 'get_sub_field' ) ) { return $default; }
	$rows = get_sub_field( $name );
	if ( ! is_array( $rows ) ) { return $default; }
	$rows = array_values( array_filter( $rows, function ( $row ) {
		foreach ( (array) $row as $v ) {
			if ( is_string( $v ) ? '' !== trim( $v ) : ! empty( $v ) ) { return true; }
		}
		return false;
	} ) );
	return $rows ? $rows : $default;
}

/**
 * An image field (array return, or an attachment ID), or a theme image file
 * name, as an <img>. $fallback is a path under assets/images, used when
 * $image is empty. $attrs is the rest of the tag.
 */
function rf_img( $image, string $fallback, string $alt, int $w, int $h, string $attrs = 'loading="lazy" decoding="async"' ): string {
	if ( is_numeric( $image ) && (int) $image > 0 ) {
		$image = function_exists( 'acf_get_attachment' ) ? acf_get_attachment( (int) $image ) : array( 'url' => wp_get_attachment_url( (int) $image ) );
	}
	if ( is_string( $image ) && '' !== trim( $image ) ) {
		$fallback = trim( $image );
		$image    = null;
	}
	if ( is_array( $image ) && ! empty( $image['url'] ) ) {
		$src = $image['url'];
		$alt = '' !== (string) ( $image['alt'] ?? '' ) ? $image['alt'] : $alt;
		$w   = (int) ( $image['width'] ?? $w );
		$h   = (int) ( $image['height'] ?? $h );
	} else {
		$src = asset( 'images/' . $fallback );
	}
	return '<img src="' . esc_url( $src ) . '" alt="' . esc_attr( $alt ) . '" width="' . $w . '" height="' . $h . '" ' . $attrs . '>';
}

/** A link field (array return) or [label, path] fallback, as [label, href]. */
function rf_link( $link, array $fallback ): array {
	if ( is_array( $link ) && ! empty( $link['url'] ) ) {
		$href = (string) $link['url'];
		// A stored root-relative link goes through url(), as the flat markup did.
		if ( '/' === ( $href[0] ?? '' ) && '/' !== ( $href[1] ?? '' ) ) { $href = url( $href ); }
		return array( (string) ( $link['title'] ?: $fallback[0] ), $href );
	}
	return array( $fallback[0], url( $fallback[1] ) );
}

/* -----------------------------------------------------------------------------
 * Line icons for the service "what we actually do" lists.
 *
 * Drawn here rather than shipped as files: each one is a few hundred bytes,
 * inherits currentColor so it recolours with the brand, and stays sharp at any
 * size. 24x24 grid, 1.6 stroke, round caps — one drawing style throughout.
 *
 * svc_icon() picks by keyword from the item's own name, so the six data files
 * need no new columns. Anything unmatched falls through to a neutral dot,
 * which is the honest default: better a plain mark than a wrong picture.
 * -------------------------------------------------------------------------- */
function svc_icon_svg(string $key): string {
    $p = [
        'search'   => '<circle cx="11" cy="11" r="6"/><path d="M15.5 15.5 21 21"/>',
        'pin'      => '<path d="M12 21s7-5.6 7-11a7 7 0 1 0-14 0c0 5.4 7 11 7 11Z"/><circle cx="12" cy="10" r="2.5"/>',
        /* A ring spanner, not a blob. The hole in the head is what makes it
           read as a tool at 24px; without it the shape is a lollipop. */
        'wrench'   => '<path d="M15.5 3a5.5 5.5 0 0 0-5 7.7L3 18.2 5.8 21l7.5-7.5A5.5 5.5 0 1 0 15.5 3Z"/><circle cx="15.5" cy="8.5" r="2.1"/>',
        'page'     => '<path d="M6 3h8l4 4v14H6Z"/><path d="M14 3v4h4"/><path d="M9 12h6M9 16h6"/>',
        'spark'    => '<path d="M12 3v5M12 16v5M3 12h5M16 12h5"/><path d="m6.5 6.5 3 3M14.5 14.5l3 3M17.5 6.5l-3 3M9.5 14.5l-3 3"/>',
        'chat'     => '<path d="M4 5h16v11H9l-5 4Z"/><path d="M9 10h6"/>',
        'link'     => '<path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 1 0-5.7-5.7L11.5 6.8"/><path d="M14 10a4 4 0 0 0-5.7 0l-3 3A4 4 0 0 0 11 18.7l1.5-1.5"/>',
        'chart'    => '<path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/>',
        'target'   => '<circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="3.5"/>',
        'mail'     => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3.5 6.5 8.5 6 8.5-6"/>',
        'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>',
        'star'     => '<path d="m12 3.5 2.6 5.5 6 .8-4.4 4.2 1.1 6-5.3-2.9L6.7 20l1.1-6L3.4 9.8l6-.8Z"/>',
        'camera'   => '<path d="M3 8h4l1.5-2h7L17 8h4v11H3Z"/><circle cx="12" cy="13" r="3.5"/>',
        'code'     => '<path d="m9 8-5 4 5 4M15 8l5 4-5 4"/>',
        'building' => '<path d="M4 21V6l7-3 7 3v15"/><path d="M9 21v-5h6v5M8 9h2M14 9h2M8 13h2M14 13h2"/>',
        'shield'   => '<path d="M12 3 5 6v6c0 4.4 3 7.6 7 9 4-1.4 7-4.6 7-9V6Z"/><path d="m9 12 2 2 4-4"/>',
        'people'   => '<circle cx="9" cy="9" r="3"/><path d="M3 20a6 6 0 0 1 12 0"/><path d="M16 7a3 3 0 0 1 0 6M17 20a6 6 0 0 0-2-4.5"/>',
        'refresh'  => '<path d="M20 12a8 8 0 1 1-2.3-5.6"/><path d="M20 4v5h-5"/>',
        'split'    => '<path d="M4 12h5l3-5h8M9 12l3 5h8"/><path d="m17 4 3 3-3 3M17 14l3 3-3 3"/>',
        'eye'      => '<path d="M2 12s3.6-6 10-6 10 6 10 6-3.6 6-10 6-10-6-10-6Z"/><circle cx="12" cy="12" r="2.5"/>',
        'clipboard'=> '<rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3h6v1"/><path d="m9 12 2 2 4-4"/>',
        'upload'   => '<path d="M12 16V4"/><path d="m7.5 8.5 4.5-4.5 4.5 4.5"/><path d="M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/>',
        'pencil'   => '<path d="M4 20h4L20 8a2.8 2.8 0 0 0-4-4L4 16Z"/><path d="m14 6 4 4"/>',
        'sliders'  => '<path d="M5 20V14M5 10V4M12 20v-9M12 7V4M19 20v-5M19 11V4"/><path d="M3 12h4M10 7h4M17 13h4"/>',
        'ear'      => '<path d="M8 19a3 3 0 0 0 3-3c0-2 3-2.5 3-5a3 3 0 1 0-6 0"/><path d="M6 10a6 6 0 1 1 8.5 5.5"/>',
        'dot'      => '<circle cx="12" cy="12" r="3.5"/>',
        /* Drawn in the same 24px stroked frame as the rest, rather than the
           filled brand glyph, so it sits with them instead of shouting. */
        'linkedin' => '<rect x="3" y="3" width="18" height="18" rx="3"/><path d="M7.5 10.5V17"/><circle cx="7.5" cy="7.4" r="0.9"/><path d="M11.5 17v-3.6a2.2 2.2 0 0 1 4.4 0V17"/><path d="M11.5 10.5V17"/>',
    ];
    $d = $p[$key] ?? $p['dot'];
    return '<svg class="how__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" '
         . 'stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $d . '</svg>';
}

/**
 * Map a process step to an icon. The steps are short verbs rather than the
 * noun phrases svc_icon() reads, so they get their own map: "Fix" and "Build"
 * mean different things here than in a list of services.
 */
function step_icon(string $name): string {
    $map = [
        'audit'    => 'clipboard',
        'watch'    => 'eye',
        'read'     => 'eye',
        'trace'    => 'search',
        'listen'   => 'ear',
        'fix'      => 'wrench',
        'correct'  => 'wrench',
        'rebuild'  => 'wrench',
        'design'   => 'pencil',
        'plan'     => 'pencil',
        'make'     => 'pencil',
        'build'    => 'code',
        'publish'  => 'upload',
        'ask'      => 'chat',
        'answer'   => 'chat',
        'test'     => 'split',
        'tune'     => 'sliders',
        'scale'    => 'chart',
        'measure'  => 'chart',
        'report'   => 'chart',
    ];
    $n = mb_strtolower(trim(strip_tags($name), " .\t\n"));
    foreach ($map as $needle => $icon) {
        if (strpos($n, $needle) !== false) return $icon;
    }
    return 'dot';
}

/**
 * The tick or cross badged onto a channel chip on the industry pages.
 *
 * Deliberately not red. The palette has no red in it, and introducing one for
 * a single mark would read as an error state rather than "you are not doing
 * this". Clay is the site's accent for a thing that needs attention, and it
 * sits beside the green without either shouting.
 */
function flag_svg(bool $doing): string {
    $d = $doing
        ? '<path d="M3.5 7.2 6 9.7l6.5-6.5" stroke-width="2"/>'
        : '<path d="M4 4l8 8M12 4l-8 8" stroke-width="2"/>';
    return '<svg class="chan__flag" viewBox="0 0 16 16" width="16" height="16" fill="none"'
         . ' stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"'
         . ' aria-hidden="true" focusable="false">' . $d . '</svg>';
}

/** Map a piece-of-work name to an icon key. First match wins, so order matters. */
function svc_icon(string $name): string {
    $map = [
        /* Order matters: the first needle found in the name wins, so the
           specific phrases have to sit above the words they contain.
           "Link building" must beat "build"; "LinkedIn" must beat "link". */
        'link building'    => 'link',
        'digital pr'       => 'link',
        'linkedin'         => 'target',

        /* Above 'local' and 'build', both of which appear inside these. */
        'industry pages'   => 'split',
        'service pages'    => 'split',

        'local'            => 'pin',
        'google business'  => 'pin',
        'map'              => 'pin',
        'directory'        => 'building',
        'listing'          => 'building',

        'technical'        => 'wrench',
        'speed'            => 'wrench',
        'mobile'           => 'wrench',
        'migration'        => 'wrench',
        'setup'            => 'wrench',
        'build'            => 'wrench',
        'design and build' => 'wrench',

        'generative'       => 'spark',
        'answer engine'    => 'spark',
        'assistant'        => 'spark',
        'ai '              => 'spark',

        'on-page'          => 'page',
        'landing page'     => 'page',
        'website cop'      => 'page',
        'copywriting'      => 'page',
        'content'          => 'page',
        'success stor'     => 'page',
        'case stud'        => 'page',   /* kept: older copy may still use it */
        'white paper'      => 'page',
        /* 'editorial' and 'newsletter' keys stay in the map even though
           nothing uses them now. They cost nothing and the map is the one
           place to look when a service comes back. */
        'editorial'        => 'page',
        'form'             => 'page',

        'search'           => 'search',
        'seo'              => 'search',
        'keyword'          => 'search',
        'link'             => 'link',

        'test'             => 'split',
        'accessib'         => 'people',
        'crm'              => 'people',
        'lead capture'     => 'people',
        'routing'          => 'people',

        'ads'              => 'target',
        'advert'           => 'target',
        'paid'             => 'target',
        'conversion'       => 'target',

        'retarget'         => 'refresh',
        'remarketing'      => 'refresh',
        'follow-up'        => 'refresh',
        'automation'       => 'refresh',
        'automated'        => 'refresh',
        'sequence'         => 'refresh',

        'review'           => 'star',
        'reputation'       => 'star',
        'rating'           => 'star',
        'proof'            => 'star',

        'repl'             => 'chat',
        'testimonial'      => 'chat',
        'respond'          => 'chat',
        'moderation'       => 'chat',

        'monitor'          => 'eye',
        'report'           => 'chart',
        'tracking'         => 'chart',
        'analytic'         => 'chart',
        'measur'           => 'chart',
        'dashboard'        => 'chart',

        'photograph'       => 'camera',
        'photo'            => 'camera',
        'video'            => 'camera',

        'email'            => 'mail',
        'sms'              => 'mail',
        'newsletter'       => 'mail',

        'appointment'      => 'calendar',
        'booking'          => 'calendar',
        'reminder'         => 'calendar',
        'no-show'          => 'calendar',
        'enquiry flow'     => 'calendar',
        'calendar'         => 'calendar',
        'quote'            => 'calendar',

        'hosting'          => 'shield',
        'security'         => 'shield',
        'maintenance'      => 'shield',
        'basics'           => 'shield',

        'profile'          => 'building',
        'schema'           => 'code',
        'structured'       => 'code',
        'development'      => 'code',
    ];
    $n = mb_strtolower(html_entity_decode(strip_tags($name), ENT_QUOTES, 'UTF-8'));
    foreach ($map as $needle => $icon) {
        if (strpos($n, $needle) !== false) return $icon;
    }
    return 'dot';
}
