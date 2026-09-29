<?php
/**
 * The blog: topics, the post list, and the flat build's post helpers
 * =============================================================================
 * The flat build keeps the post index in includes/posts.php as $POSTS, and its
 * detail and index templates read it through a handful of helpers
 * (post_by_slug(), post_featured(), posts_except(), posts_search(),
 * post_img(), post_date()). Here the posts are WordPress posts, and
 * rankinai_posts() builds the same $POSTS array from them, so the helpers and
 * both templates work unchanged. The helpers below are copied verbatim.
 *
 * DUMMY BODIES, REAL TITLES. Six of the seven article bodies are placeholders
 * (see CLAUDE.md, "What is not finished"). The titles, standfirsts and topics
 * are the client's copy.
 * =============================================================================
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* The filter row, in the order the client's copy lists them. The key is what
   a post's Topic field stores and what ?topic= carries in the URL. */
$GLOBALS['TOPICS'] = array(
	'search'      => 'AI and search visibility',
	'advertising' => 'Paid advertising',
	'content'     => 'Content',
	'website'     => 'Website and conversion',
	'reputation'  => 'Reviews and reputation',
	'crm'         => 'CRM and automation',
	'strategy'    => 'Marketing strategy',
);

/** Every published post as the flat build's $POSTS rows, newest first. */
function rankinai_posts() {
	$out = array();
	foreach ( get_posts( array( 'post_type' => 'post', 'post_status' => 'publish', 'numberposts' => -1, 'orderby' => 'date', 'order' => 'DESC' ) ) as $p ) {
		$d     = rankinai_model_data( $p->ID );
		$out[] = array(
			'slug'     => $p->post_name,
			'img'      => $d['img'] ?? null,
			'topic'    => $d['topic'] ?? 'strategy',
			'featured' => ! empty( $d['featured'] ),
			'title'    => $p->post_title,
			'stand'    => $d['stand'] ?? '',
			'date'     => get_the_date( 'Y-m-d', $p ),
			'mins'     => $d['mins'] ?? '',
		);
	}
	return $out;
}

/* $POSTS for the templates, filled once WordPress knows what is being shown. */
add_action( 'template_redirect', function () {
	$GLOBALS['POSTS'] = rankinai_posts();
} );

/* -----------------------------------------------------------------------------
 * The flat build's helpers, verbatim from includes/posts.php.
 * -------------------------------------------------------------------------- */

/** The post row for a slug, or null. */
function post_by_slug(string $slug): ?array {
    global $POSTS;
    foreach ((array) $POSTS as $p) {
        if ($p['slug'] === $slug) return $p;
    }
    return null;
}

/** The featured post, or the newest one if none is flagged. */
function post_featured(): ?array {
    global $POSTS;
    foreach ((array) $POSTS as $p) {
        if (!empty($p['featured'])) return $p;
    }
    return $POSTS[0] ?? null;
}

/** Every post except the one given, newest first, capped. */
function posts_except(string $slug, int $limit = 2): array {
    global $POSTS;
    $out = [];
    foreach ((array) $POSTS as $p) {
        if ($p['slug'] !== $slug) $out[] = $p;
    }
    return array_slice($out, 0, $limit);
}

/** A plural finds the singular. See the flat build's includes/posts.php. */
function post_stem(string $w): string {
    if (mb_substr($w, -3) === 'ies') return mb_substr($w, 0, -3) . 'y';
    if (mb_substr($w, -2) === 'es')  return mb_substr($w, 0, -2);
    if (mb_substr($w, -1) === 's')   return mb_substr($w, 0, -1);
    return $w;
}

function posts_search(array $posts, string $q): array {
    $needle = mb_strtolower(trim($q));
    if ($needle === '') return $posts;
    $single = post_stem($needle);
    $stem   = $single;
    $len    = min(mb_strlen($needle), mb_strlen($single));
    for ($i = 0; $i < $len; $i++) {
        if (mb_substr($needle, $i, 1) !== mb_substr($single, $i, 1)) {
            $stem = mb_substr($needle, 0, $i);
            break;
        }
        $stem = mb_substr($needle, 0, $i + 1);
    }
    if (mb_strlen($stem) < 4) $stem = $needle;
    return array_values(array_filter($posts, function ($p) use ($needle, $stem) {
        $hay = mb_strtolower(html_entity_decode($p['title'] . ' ' . $p['stand'], ENT_QUOTES, 'UTF-8'));
        return mb_strpos($hay, $needle) !== false || mb_strpos($hay, $stem) !== false;
    }));
}

/** A sized, cropped Unsplash URL for a post. Hotlinked, as Unsplash requires. */
function post_img(array $p, int $w = 1200, int $h = 0): string {
    if (empty($p['img'])) return '';
    $q = [
        'ixlib' => 'rb-4.1.0',
        'auto'  => 'format',
        'fit'   => 'crop',
        'w'     => $w,
        'q'     => 70,
    ];
    if ($h) $q['h'] = $h;
    return 'https://images.unsplash.com/' . $p['img']['photo'] . '?' . http_build_query($q);
}

/** The photographer's profile, with the referral parameters Unsplash asks for. */
function post_img_by_url(array $p): string {
    return 'https://unsplash.com/@' . $p['img']['user'] . '?utm_source=RankinAI&utm_medium=referral';
}

/** Unsplash itself, same parameters. */
function unsplash_url(): string {
    return 'https://unsplash.com/?utm_source=RankinAI&utm_medium=referral';
}

/** 18 September 2026. */
function post_date(string $iso): string {
    $t = strtotime($iso);
    return $t ? date('j F Y', $t) : '';
}

/* -----------------------------------------------------------------------------
 * Article bodies. The flat build writes a body as typed tuples:
 *   ['p', text] ['h2', text] ['h3', text] ['note', text] ['quote', text]
 *   ['list', [items]] ['olist', [items]] ['take', [title, [items]]]
 *   ['table', [caption, [head], [[row], ...]]]
 *   ['img', [photo id, alt, photographer, username, page slug]]
 * In WordPress each is a block with a Type and the fields that type uses.
 * These two functions convert between the shapes.
 * -------------------------------------------------------------------------- */
function rankinai_body_pack( array $tuples ) {
	$out = array();
	foreach ( $tuples as $t ) {
		$kind = $t[0] ?? 'p';
		$val  = $t[1] ?? '';
		$b    = array( 'kind' => $kind );
		switch ( $kind ) {
			case 'list':
			case 'olist':
				$b['items'] = (array) $val;
				break;
			case 'take':
				$b['text']  = $val[0] ?? '';
				$b['items'] = (array) ( $val[1] ?? array() );
				break;
			case 'table':
				$b['text']  = $val[0] ?? '';
				$b['head']  = implode( ' | ', (array) ( $val[1] ?? array() ) );
				$b['cells'] = array_map( function ( $r ) { return implode( ' | ', (array) $r ); }, (array) ( $val[2] ?? array() ) );
				break;
			case 'img':
				$b['photo'] = array( 'photo' => $val[0] ?? '', 'alt' => $val[1] ?? '', 'by' => $val[2] ?? '', 'user' => $val[3] ?? '', 'html' => $val[4] ?? '' );
				break;
			default:
				$b['text'] = (string) $val;
		}
		$out[] = $b;
	}
	return $out;
}

function rankinai_body_unpack( array $blocks ) {
	$cells = function ( $s ) { return array_map( 'trim', explode( '|', (string) $s ) ); };
	$out   = array();
	foreach ( $blocks as $b ) {
		$kind = $b['kind'] ?? 'p';
		switch ( $kind ) {
			case 'list':
			case 'olist':
				$out[] = array( $kind, $b['items'] ?? array() );
				break;
			case 'take':
				$out[] = array( 'take', array( $b['text'] ?? '', $b['items'] ?? array() ) );
				break;
			case 'table':
				$out[] = array( 'table', array( $b['text'] ?? '', $cells( $b['head'] ?? '' ), array_map( $cells, $b['cells'] ?? array() ) ) );
				break;
			case 'img':
				$ph    = $b['photo'] ?? array();
				$out[] = array( 'img', array( $ph['photo'] ?? '', $ph['alt'] ?? '', $ph['by'] ?? '', $ph['user'] ?? '', $ph['html'] ?? '' ) );
				break;
			default:
				$out[] = array( $kind, $b['text'] ?? '' );
		}
	}
	return $out;
}

/** The flat build's $POSTS rows, read from includes/posts.php for the seed.
 *  Only the array is evaluated: the file also defines the helpers above. */
function rankinai_flat_posts() {
	$code = (string) file_get_contents( dirname( ABSPATH ) . '/includes/posts.php' );
	$a    = strpos( $code, '$POSTS = [' );
	$b    = strpos( $code, "\n];", $a );
	if ( false === $a || false === $b ) { return array(); }
	$POSTS = array();
	eval( substr( $code, $a, $b - $a + 3 ) ); // phpcs:ignore -- the repo's own data
	return $POSTS;
}

/* The blog index pages with ?page=2, as the flat build did. WordPress reads
   'page' on a page as "page 2 of a split post" and 301s it away, which would
   break "Load more articles". The index opts out of that redirect. */
add_filter( 'redirect_canonical', function ( $redirect ) {
	$path = untrailingslashit( (string) parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ) );
	if ( isset( $_GET['page'] ) && ( is_page_template( 'page-templates/blog.php' ) || '/blog' === $path ) ) {
		return false;
	}
	return $redirect;
} );
/* ...and WordPress must not treat that ?page= as its own pagination, or it
   looks for a second page of the index's content and answers 404. The
   template reads ?page= from the URL itself. */
add_filter( 'request', function ( $qv ) {
	if ( isset( $qv['page'] ) && 'blog' === ( $qv['pagename'] ?? '' ) ) {
		unset( $qv['page'] );
	}
	return $qv;
} );
