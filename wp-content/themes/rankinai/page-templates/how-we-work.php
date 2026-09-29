<?php
/**
 * How we work. The markup is the flat build's how-we-work.php, with every
 * piece of content read from the page's fields
 * (inc/models/page-how-we-work.php). No prices on this page: they live on
 * /pricing/ only.
 *
 * The steps are one list, printed twice: short in the hero card, in full in
 * the process slider.
 */
global $SITE, $NAV, $FOOTER;
$D = rankinai_model_data();
get_header();

$hero    = $D['hero'] ?? array();
$begin   = $D['begin'] ?? array();
$process = $D['process'] ?? array();
$steps   = $D['steps'] ?? array();
$measure = $D['measure'] ?? array();
$faq     = $D['faq'] ?? array();

/* A link field holds a site path (/call/), an anchor or a full URL. */
$ri_href = function ( $h ) {
	$h = (string) $h;
	return 0 === strpos( $h, '/' ) ? url( $h ) : $h;
};

/* The hero photograph, hotlinked from Unsplash exactly as the flat build
   builds its URL. */
$hwP = $hero['photo']['photo'] ?? '';
$hwU = function ( int $w, int $h ) use ( $hwP ): string {
	return 'https://images.unsplash.com/' . $hwP . '?' . http_build_query( array(
		'ixlib' => 'rb-4.1.0', 'auto' => 'format', 'fit' => 'crop', 'w' => $w, 'h' => $h, 'q' => 70,
	) );
};
?>

<section class="hero hero--work">
  <div class="hero__inner container">

    <div class="work-hero__copy">
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

    <div class="work-hero__visual">
<?php if ( '' !== $hwP ) : ?>
      <figure class="work-hero__photo">
        <img src="<?= e( $hwU( 900, 1200 ) ) ?>" srcset="<?= e( $hwU( 600, 800 ) ) ?> 600w, <?= e( $hwU( 900, 1200 ) ) ?> 900w, <?= e( $hwU( 1200, 1600 ) ) ?> 1200w" sizes="(max-width: 900px) 100vw, 50vw" alt="<?= e( $hero['photo']['alt'] ?? '' ) ?>" width="900" height="1200" fetchpriority="high" decoding="async">
      </figure>
<?php endif; ?>
<?php if ( $steps ) : ?>
      <ol class="work-hero__steps" role="list">
<?php foreach ( $steps as $s ) : ?>
        <li><a href="#process"><span class="label label--clay"><?= $s['n'] ?? '' ?></span><b><?= $s['title'] ?? '' ?></b><span class="work-hero__lead"><?= $s['lead'] ?? '' ?></span></a></li>
<?php endforeach; ?>
      </ol>
<?php endif; ?>
    </div>

  </div>
</section>


<?php if ( $begin ) : ?>
<section class="band band--forest">
  <div class="container">

    <div class="stories__head">
      <div>
<?php if ( ! empty( $begin['eyebrow'] ) ) : ?>
        <p class="eyebrow eyebrow--light"><span><?= $begin['eyebrow'] ?></span></p>
<?php endif; ?>
<?php if ( ! empty( $begin['title'] ) ) : ?>
        <h2 class="stories__title"><?= $begin['title'] ?></h2>
<?php endif; ?>
<?php if ( ! empty( $begin['note'] ) ) : ?>
        <p class="packages__note"><?= $begin['note'] ?></p>
<?php endif; ?>
      </div>
    </div>

    <div class="begin">

      <div class="begin__ask">
<?php if ( ! empty( $begin['askLabel'] ) ) : ?>
        <p class="label label--clay"><?= $begin['askLabel'] ?></p>
<?php endif; ?>
<?php if ( ! empty( $begin['questions'] ) ) : ?>
        <ol class="qset" role="list">
<?php foreach ( $begin['questions'] as $i => $q ) : ?>
          <li><span class="qset__n"><?= sprintf( '%02d', $i + 1 ) ?></span><span class="qset__q"><?= $q ?></span></li>
<?php endforeach; ?>
        </ol>
<?php endif; ?>
      </div>

      <div class="begin__step">
<?php if ( ! empty( $begin['stepLabel'] ) ) : ?>
        <p class="label label--clay"><?= $begin['stepLabel'] ?></p>
<?php endif; ?>
<?php if ( ! empty( $begin['lead'] ) ) : ?>
        <p class="begin__lead"><?= $begin['lead'] ?></p>
<?php endif; ?>
<?php foreach ( $begin['paras'] ?? array() as $p ) : ?>
        <p class="begin__text"><?= $p ?></p>
<?php endforeach; ?>

        <div class="firststep__act">
<?php if ( ! empty( $begin['btn'][0] ) ) : ?>
          <a class="btn btn--primary" href="<?= e( $ri_href( $begin['btn'][1] ) ) ?>"><?= $begin['btn'][0] ?> <?= btn_arrow() ?></a>
<?php endif; ?>
<?php if ( ! empty( $begin['link'][0] ) ) : ?>
          <a class="link-quiet link-quiet--onforest" href="<?= e( $ri_href( $begin['link'][1] ) ) ?>"><?= $begin['link'][0] ?></a>
<?php endif; ?>
        </div>

<?php if ( ! empty( $begin['footnote'] ) ) : ?>
        <p class="begin__note"><?= $begin['footnote'] ?></p>
<?php endif; ?>
      </div>

    </div>

  </div>
</section>
<?php endif; ?>



<?php if ( $steps ) : ?>
<section class="band band--light pslider" id="process">
  <div class="container">

    <div class="questions__head">
      <div>
<?php if ( ! empty( $process['eyebrow'] ) ) : ?>
        <p class="eyebrow"><span><?= $process['eyebrow'] ?></span></p>
<?php endif; ?>
<?php if ( ! empty( $process['title'] ) ) : ?>
        <h2 class="questions__title"><?= $process['title'] ?></h2>
<?php endif; ?>
      </div>
    </div>

  </div>

  <div class="pslider__rail" data-slider>
    <ul class="ptrack" role="list" data-slider-track>
<?php foreach ( $steps as $s ) : ?>
      <li class="pcard">
        <div class="pcard__top">
          <span class="pcard__icon"><?= svc_icon_svg( $s['icon'] ?? 'dot' ) ?></span>
          <span class="pcard__n"><?= $s['n'] ?? '' ?></span>
        </div>
        <h3 class="pcard__name"><?= $s['title'] ?? '' ?></h3>
        <p class="pcard__lead"><?= $s['lead'] ?? '' ?></p>
<?php foreach ( $s['paras'] ?? array() as $p ) : ?>
        <p class="pcard__text"><?= $p ?></p>
<?php endforeach; ?>
<?php if ( ! empty( $s['label'] ) || ! empty( $s['list'] ) ) : ?>
        <div class="pcard__gets">
          <p class="label label--clay"><?= $s['label'] ?? '' ?></p>
          <ul class="deliv__list" role="list">
<?php foreach ( $s['list'] ?? array() as $g ) : ?>
            <li><?= $g ?></li>
<?php endforeach; ?>
          </ul>
        </div>
<?php endif; ?>
      </li>
<?php endforeach; ?>
    </ul>

    <div class="container">
      <div class="teamnav">
        <button class="teamnav__btn" type="button" data-slider-prev aria-label="Previous step">
          <svg viewBox="0 0 16 16" width="16" height="16" fill="none" aria-hidden="true" focusable="false">
            <path d="M14.5 8H4M7.4 4.6 4 8l3.4 3.4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
        <button class="teamnav__btn" type="button" data-slider-next aria-label="Next step">
          <svg viewBox="0 0 16 16" width="16" height="16" fill="none" aria-hidden="true" focusable="false">
            <path d="M1.5 8H12M8.6 4.6 12 8l-3.4 3.4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>



<?php if ( $measure ) : ?>
<section class="band band--forest">
  <div class="container">

    <div class="stories__head">
      <div>
<?php if ( ! empty( $measure['eyebrow'] ) ) : ?>
        <p class="eyebrow eyebrow--light"><span><?= $measure['eyebrow'] ?></span></p>
<?php endif; ?>
<?php if ( ! empty( $measure['title'] ) ) : ?>
        <h2 class="stories__title"><?= $measure['title'] ?></h2>
<?php endif; ?>
      </div>
    </div>

<?php if ( ! empty( $measure['cards'] ) ) : ?>
    <div class="wcards wcards--journey">
<?php foreach ( $measure['cards'] as $i => $card ) : ?>
      <div class="wcard">
        <span class="wcard__icon"><?= svc_icon_svg( $card[0] ) ?></span>
        <span class="wcard__n"><?= sprintf( '%02d', $i + 1 ) ?></span>
        <h3 class="wcard__name"><?= $card[1] ?></h3>
        <p class="wcard__text"><?= $card[2] ?></p>
      </div>
<?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if ( ! empty( $measure['tail'] ) ) : ?>
    <p class="aftercards aftercards--onforest"><?= $measure['tail'] ?></p>
<?php endif; ?>

  </div>
</section>
<?php endif; ?>



<?php if ( $faq ) : ?>
<section class="band band--light qbody">
  <div class="container">

    <div class="qgroup">
      <div class="qgroup__head">
<?php if ( ! empty( $faq['eyebrow'] ) ) : ?>
        <p class="eyebrow"><span><?= $faq['eyebrow'] ?></span></p>
<?php endif; ?>
<?php if ( ! empty( $faq['title'] ) ) : ?>
        <h2 class="qgroup__title"><?= $faq['title'] ?></h2>
<?php endif; ?>
      </div>

<?php if ( ! empty( $faq['questions'] ) ) : ?>
      <div class="qa qa--wide">
<?php foreach ( $faq['questions'] as $i => $qa ) : ?>
        <details class="qi" name="faq">
          <summary class="qi__q">
            <span class="qi__n"><?= sprintf( '%02d', $i + 1 ) ?></span>
            <span class="qi__text"><?= $qa[0] ?></span>
            <span class="qi__mark" aria-hidden="true"></span>
          </summary>
          <div class="qi__a">
<?php foreach ( $qa[1] as $p ) : ?>
            <p><?= $p ?></p>
<?php endforeach; ?>
          </div>
        </details>
<?php endforeach; ?>
      </div>
<?php endif; ?>
    </div>

  </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
