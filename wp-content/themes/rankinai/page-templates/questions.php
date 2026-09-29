<?php
/**
 * Questions. The markup is the flat build's questions.php, with every piece
 * of content read from the page's fields (inc/models/page-questions.php).
 * One light band of answers, groups separated by their own headings, and a
 * jump list in the hero built from the same groups.
 *
 * THE PRICES HERE MUST MATCH /pricing/. See the model's header comment.
 */
global $SITE, $NAV, $FOOTER;
$D = rankinai_model_data();
get_header();

$hero   = $D['hero'] ?? array();
$groups = $D['groups'] ?? array();
$ask    = $D['ask'] ?? array();

/* A link field holds a site path (/call/), an anchor or a full URL. */
$ri_href = function ( $h ) {
	$h = (string) $h;
	return 0 === strpos( $h, '/' ) ? url( $h ) : $h;
};

/* A paragraph whose every line starts with "- " is a list: its items, or
   null for an ordinary paragraph. */
$ri_list = function ( $part ) {
	$lines = preg_split( "/\r?\n/", (string) $part );
	$items = array();
	foreach ( $lines as $line ) {
		if ( ! preg_match( '/^\s*-\s+(.*)$/', $line, $m ) ) { return null; }
		$items[] = rtrim( $m[1] );
	}
	return $items;
};
?>

<section class="hero hero--centred">
  <div class="hero__inner container">

<?php if ( ! empty( $hero['eyebrow'] ) ) : ?>
    <p class="eyebrow"><span><?= $hero['eyebrow'] ?></span></p>
<?php endif; ?>

<?php if ( ! empty( $hero['title'] ) ) : ?>
    <h1 class="hero__title"><?= $hero['title'] ?></h1>
<?php endif; ?>

    <div class="hero__meta hero__meta--home">
<?php if ( ! empty( $hero['sub'] ) || ! empty( $hero['sub2'] ) ) : ?>
      <p class="hero__sub"><?= $hero['sub'] ?? '' ?><?php if ( ! empty( $hero['sub2'] ) ) : ?><span class="hero__sub2"><?= $hero['sub2'] ?></span><?php endif; ?></p>
<?php endif; ?>
      <div class="hero__actions">
<?php if ( ! empty( $hero['btn'][0] ) ) : ?>
        <a class="btn btn--primary" href="<?= e( $ri_href( $hero['btn'][1] ) ) ?>"><?= $hero['btn'][0] ?> <?= btn_arrow() ?></a>
<?php endif; ?>
<?php if ( ! empty( $hero['link'][0] ) ) : ?>
        <a class="link-quiet" href="<?= e( $ri_href( $hero['link'][1] ) ) ?>"><?= $hero['link'][0] ?></a>
<?php endif; ?>
      </div>
    </div>
  </div>

<?php if ( $groups ) : ?>
  <nav class="jumpto" aria-label="Jump to a section">
<?php if ( ! empty( $hero['jump'] ) ) : ?>
    <p class="label"><?= $hero['jump'] ?></p>
<?php endif; ?>
    <ul role="list">
<?php foreach ( $groups as $g ) : ?>
      <li><a href="#<?= e( $g['id'] ?? '' ) ?>"><?= $g['label'] ?? '' ?></a></li>
<?php endforeach; ?>
    </ul>
  </nav>
<?php endif; ?>
</section>


<section class="band band--light qbody">
  <div class="container">

<?php foreach ( $groups as $g ) :
	$gid = $g['id'] ?? '';
?>
    <div class="qgroup" id="<?= e( $gid ) ?>">
      <div class="qgroup__head">
<?php if ( ! empty( $g['eyebrow'] ) ) : ?>
        <p class="eyebrow"><span><?= $g['eyebrow'] ?></span></p>
<?php endif; ?>
<?php if ( ! empty( $g['title'] ) ) : ?>
        <h2 class="qgroup__title"><?= $g['title'] ?></h2>
<?php endif; ?>
      </div>

      <div class="qa">
<?php foreach ( $g['questions'] ?? array() as $i => $qa ) : ?>
        <details class="qi" name="faq-<?= e( $gid ) ?>">
          <summary class="qi__q">
            <span class="qi__n"><?= sprintf( '%02d', $i + 1 ) ?></span>
            <span class="qi__text"><?= $qa[0] ?></span>
            <span class="qi__mark" aria-hidden="true"></span>
          </summary>
          <div class="qi__a">
<?php foreach ( $qa[1] as $part ) :
		$list = $ri_list( $part );
?>
<?php   if ( null !== $list ) : ?>
            <ul class="deliv__list" role="list">
<?php     foreach ( $list as $line ) : ?>
              <li><?= $line ?></li>
<?php     endforeach; ?>
            </ul>
<?php   else : ?>
            <p><?= $part ?></p>
<?php   endif; ?>
<?php endforeach; ?>
          </div>
        </details>
<?php endforeach; ?>
      </div>
    </div>
<?php endforeach; ?>

<?php if ( $ask ) : ?>
    <div class="notthis askit">
<?php if ( ! empty( $ask['label'] ) ) : ?>
      <p class="label label--clay"><?= $ask['label'] ?></p>
<?php endif; ?>
<?php if ( ! empty( $ask['text'] ) ) : ?>
      <p class="notthis__text"><?= $ask['text'][0] ?> <a href="mailto:<?= e( $SITE['email'] ) ?>"><?= e( $SITE['email'] ) ?></a> <?= $ask['text'][1] ?></p>
<?php endif; ?>
    </div>
<?php endif; ?>

  </div>
</section>

<?php get_footer(); ?>
