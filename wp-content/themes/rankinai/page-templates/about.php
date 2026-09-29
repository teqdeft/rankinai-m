<?php
/**
 * About. The markup is the flat build's about.php, with every piece of
 * content read from the page's fields (inc/models/page-about.php). Read the
 * flat file's header comment before changing what this page says: it records
 * the sections removed on purpose and the test for adding a person.
 *
 * The three collage photographs are positional: the first is the wide one
 * across the top, the third is the one set lower. Their classes and sizes
 * stay here, as on the flat build.
 */
global $SITE, $NAV, $FOOTER;
$D = rankinai_model_data();
get_header();

$hero    = $D['hero'] ?? array();
$why     = $D['why'] ?? array();
$think   = $D['think'] ?? array();
$working = $D['working'] ?? array();
$team    = $D['team'] ?? array();

/* A link field holds a site path (/call/), an anchor (#team) or a full URL. */
$ri_href = function ( $h ) {
	$h = (string) $h;
	return 0 === strpos( $h, '/' ) ? url( $h ) : $h;
};
?>

<section class="hero hero--about">
  <div class="hero__inner container">

    <div class="about-hero__copy">
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

    <div class="about-hero__collage">
<?php
$ri_shots = array(
	array( 'about-hero__shot about-hero__shot--wide', 1120, 600, ' fetchpriority="high"' ),
	array( 'about-hero__shot', 1120, 600, '' ),
	array( 'about-hero__shot about-hero__shot--low', 800, 600, '' ),
);
foreach ( $hero['shots'] ?? array() as $i => $shot ) :
	if ( '' === $shot[0] ) { continue; }
	$pos = $ri_shots[ $i ] ?? $ri_shots[1];
?>
      <figure class="<?= $pos[0] ?>">
        <img src="<?= e( rankinai_img_url( $shot[0] ) ) ?>" alt="<?= e( $shot[1] ) ?>" width="<?= $pos[1] ?>" height="<?= $pos[2] ?>"<?= $pos[3] ?> decoding="async">
      </figure>
<?php endforeach; ?>
      <p class="about-hero__place">
<?php if ( ! empty( $hero['place'] ) ) : ?>
        <span class="label label--clay"><?= $hero['place'] ?></span>
<?php endif; ?>
        <b><?= e( $SITE['offices'] ) ?></b>
      </p>
    </div>

  </div>
</section>


<?php if ( $why ) : ?>
<section class="band band--forest">
  <div class="container why">

    <div class="why__head">
<?php if ( ! empty( $why['eyebrow'] ) ) : ?>
      <p class="eyebrow eyebrow--light"><span><?= $why['eyebrow'] ?></span></p>
<?php endif; ?>
<?php if ( ! empty( $why['title'] ) ) : ?>
      <h2 class="why__title"><?= $why['title'] ?></h2>
<?php endif; ?>
<?php if ( ! empty( $why['sub'] ) ) : ?>
      <p class="why__sub"><?= $why['sub'] ?></p>
<?php endif; ?>
    </div>

    <div class="why__body">
<?php if ( ! empty( $why['lead'] ) ) : ?>
      <p class="why__lead"><?= $why['lead'] ?></p>
<?php endif; ?>
<?php foreach ( $why['paras'] ?? array() as $p ) : ?>
      <p><?= $p ?></p>
<?php endforeach; ?>
    </div>

  </div>
</section>
<?php endif; ?>


<section class="band band--forest photoband">

  <?php get_template_part( 'template-parts/team-marquee' ); ?>

<?php if ( ! empty( $D['mission'] ) ) : ?>
  <div class="container">
    <p class="vision vision--centre"><?= $D['mission'] ?></p>
  </div>
<?php endif; ?>
</section>


<?php if ( $think ) : ?>
<section class="band band--light">
  <div class="container">

    <div class="questions__head">
      <div>
<?php if ( ! empty( $think['eyebrow'] ) ) : ?>
        <p class="eyebrow"><span><?= $think['eyebrow'] ?></span></p>
<?php endif; ?>
<?php if ( ! empty( $think['title'] ) ) : ?>
        <h2 class="questions__title"><?= $think['title'] ?></h2>
<?php endif; ?>
<?php if ( ! empty( $think['note'] ) ) : ?>
        <p class="stories__note"><?= $think['note'] ?></p>
<?php endif; ?>
      </div>
    </div>

    <div class="think">

      <div class="think__say">
<?php if ( ! empty( $think['lead'] ) ) : ?>
        <p class="think__lead"><?= $think['lead'] ?></p>
<?php endif; ?>
<?php foreach ( $think['paras'] ?? array() as $p ) : ?>
        <p><?= $p ?></p>
<?php endforeach; ?>
      </div>

<?php if ( ! empty( $think['rules'] ) ) : ?>
      <div class="think__rules">
<?php if ( ! empty( $think['rulesLabel'] ) ) : ?>
        <p class="label label--clay"><?= $think['rulesLabel'] ?></p>
<?php endif; ?>
        <ol class="rules" role="list">
<?php foreach ( $think['rules'] as $i => $rule ) : ?>
          <li class="rule">
            <span class="rule__n"><?= sprintf( '%02d', $i + 1 ) ?></span>
            <div class="rule__body"><h3 class="rule__name"><?= $rule[0] ?></h3> <span class="rule__text"><?= $rule[1] ?></span></div>
          </li>
<?php endforeach; ?>
        </ol>
      </div>
<?php endif; ?>

    </div>

  </div>
</section>
<?php endif; ?>


<?php if ( $working ) : ?>
<section class="band band--forest">
  <div class="container">

    <div class="stories__head">
      <div>
<?php if ( ! empty( $working['eyebrow'] ) ) : ?>
        <p class="eyebrow eyebrow--light"><span><?= $working['eyebrow'] ?></span></p>
<?php endif; ?>
<?php if ( ! empty( $working['title'] ) ) : ?>
        <h2 class="stories__title"><?= $working['title'] ?></h2>
<?php endif; ?>
<?php if ( ! empty( $working['note'] ) ) : ?>
        <p class="packages__note"><?= $working['note'] ?></p>
<?php endif; ?>
      </div>
    </div>

<?php if ( ! empty( $working['cards'] ) ) : ?>
    <div class="wcards">
<?php foreach ( $working['cards'] as $i => $card ) : ?>
      <div class="wcard">
        <span class="wcard__icon"><?= svc_icon_svg( $card[0] ) ?></span>
        <span class="wcard__n"><?= sprintf( '%02d', $i + 1 ) ?></span>
        <h3 class="wcard__name"><?= $card[1] ?></h3>
        <p class="wcard__text"><?= $card[2] ?></p>
      </div>
<?php endforeach; ?>
    </div>
<?php endif; ?>

  </div>
</section>
<?php endif; ?>


<?php if ( $team ) :
	$labels = $team['labels'] ?? array( '', '', '' );
?>
<section class="band band--light" id="team">
  <div class="container">

    <div class="questions__head">
      <div>
<?php if ( ! empty( $team['title'] ) ) : ?>
        <h2 class="questions__title"><?= $team['title'] ?></h2>
<?php endif; ?>
<?php if ( ! empty( $team['note'] ) ) : ?>
        <p class="stories__note"><?= $team['note'] ?></p>
<?php endif; ?>
      </div>
    </div>

<?php if ( ! empty( $team['people'] ) ) : ?>
    <div class="teamslider" data-teamslider>

      <ul class="people" role="list" data-team-track>
<?php foreach ( $team['people'] as $m ) :
		list( $name, $role, $photo, $years, $work, $li ) = $m;
?>
        <li class="person">
          <div class="person__photo case__photo">
<?php if ( '' !== $photo ) : ?>
            <img src="<?= e( rankinai_img_url( $photo ) ) ?>" alt="<?= e( $name ) ?>" width="560" height="560" loading="lazy" decoding="async">
<?php else : ?>
            <span><?= $name ?></span>
<?php endif; ?>
          </div>

          <p class="person__name"><?= $name ?></p>
          <p class="label"><?= $role ?></p>

<?php if ( $years || $work ) : ?>
          <dl class="person__facts">
<?php if ( $years ) : ?>
            <div><dt><?= $labels[0] ?></dt><dd><?= $years ?></dd></div>
<?php endif; ?>
<?php if ( $work ) : ?>
            <div><dt><?= $labels[1] ?></dt><dd><?= $work ?></dd></div>
<?php endif; ?>
          </dl>
<?php endif; ?>

<?php if ( $li ) : ?>
          <a class="person__li" href="<?= e( $li ) ?>" target="_blank" rel="noopener">
            <?= svc_icon_svg( 'linkedin' ) ?><span><?= $labels[2] ?></span>
          </a>
<?php endif; ?>
        </li>
<?php endforeach; ?>
      </ul>

      <div class="teamnav">
        <button class="teamnav__btn" type="button" data-team-prev aria-label="Previous team members">
          <svg viewBox="0 0 16 16" width="16" height="16" fill="none" aria-hidden="true" focusable="false">
            <path d="M14.5 8H4M7.4 4.6 4 8l3.4 3.4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
        <button class="teamnav__btn" type="button" data-team-next aria-label="More team members">
          <svg viewBox="0 0 16 16" width="16" height="16" fill="none" aria-hidden="true" focusable="false">
            <path d="M1.5 8H12M8.6 4.6 12 8l-3.4 3.4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
      </div>

    </div>
<?php endif; ?>

  </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
