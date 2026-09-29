<?php
/**
 * Growth audit, the conversion endpoint. The markup is the flat build's
 * growth-audit.php, with every piece of content read from the page's fields
 * (inc/models/page-growth-audit.php). The form is template-parts/auditform.php.
 */
global $SITE, $NAV, $FOOTER;
$D = rankinai_model_data();
get_header();

$hero = $D['hero'] ?? array();
$what = $D['what'] ?? array();
$faq  = $D['faq'] ?? array();
?>

<section class="hero hero--page hero--audit">
  <div class="hero__inner container audit-lede">

    <div class="audit-lede__copy">

<?php if ( ! empty( $hero['title'] ) ) : ?>
      <h1 class="hero__title hero__title--audit"><?= $hero['title'] ?></h1>
<?php endif; ?>

<?php if ( ! empty( $hero['sub'] ) ) : ?>
      <p class="hero__sub audit-lede__sub"><?= $hero['sub'] ?></p>
<?php endif; ?>
    </div>

<?php if ( ! empty( $hero['ticks'] ) ) : ?>
    <ul class="ticks" role="list">
<?php foreach ( $hero['ticks'] as $tick ) : ?>
      <li><?= $tick ?></li>
<?php endforeach; ?>
    </ul>
<?php endif; ?>

    <?php get_template_part( 'template-parts/auditform', null, array( 'panel' => true ) ); ?>

  </div>

<?php if ( ! empty( $hero['proof'] ) ) : ?>
  <div class="hero__proof">
<?php foreach ( $hero['proof'] as $stat ) : ?>
    <div class="stat">
      <span class="stat__n"><?= $stat[0] ?></span>
      <span class="stat__k"><?= $stat[1] ?></span>
    </div>
<?php endforeach; ?>
  </div>
<?php endif; ?>
</section>


<?php if ( ! empty( $what ) ) : ?>
<section class="audit-what">
  <div class="container">

<?php if ( ! empty( $what['title'] ) || ! empty( $what['note'] ) ) : ?>
    <div class="stories__head">
      <div>
<?php if ( ! empty( $what['title'] ) ) : ?>
        <h2 class="stories__title"><?= $what['title'] ?></h2>
<?php endif; ?>
<?php if ( ! empty( $what['note'] ) ) : ?>
        <p class="packages__note"><?= $what['note'] ?></p>
<?php endif; ?>
      </div>
    </div>
<?php endif; ?>

<?php if ( ! empty( $what['cols'] ) ) : ?>
    <div class="deliv__grid deliv__grid--onforest">
<?php foreach ( $what['cols'] as $col ) : ?>
      <div class="deliv">
<?php if ( ! empty( $col['title'] ) ) : ?>
        <p class="deliv__head"><?= $col['title'] ?></p>
<?php endif; ?>
<?php if ( ! empty( $col['items'] ) ) : ?>
        <ul class="deliv__list" role="list">
<?php foreach ( $col['items'] as $item ) : ?>
          <li><?= $item ?></li>
<?php endforeach; ?>
        </ul>
<?php endif; ?>
      </div>
<?php endforeach; ?>
    </div>
<?php endif; ?>

  </div>
</section>
<?php endif; ?>


<?php if ( ! empty( $faq['questions'] ) ) : ?>
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
<?php if ( ! empty( $faq['more'][0] ) && ! empty( $faq['more'][1] ) ) : ?>
        <p class="qgroup__more"><a class="link-quiet link-quiet--bold" href="<?= url( $faq['more'][1] ) ?>"><?= $faq['more'][0] ?></a></p>
<?php endif; ?>
      </div>

      <div class="qa">
<?php foreach ( $faq['questions'] as $i => $qa ) : ?>
        <details class="qi" name="faq">
          <summary class="qi__q">
            <span class="qi__n"><?= sprintf( '%02d', $i + 1 ) ?></span>
            <span class="qi__text"><?= $qa[0] ?></span>
            <span class="qi__mark" aria-hidden="true"></span>
          </summary>
          <div class="qi__a">
<?php foreach ( $qa[1] as $para ) : ?>
            <p><?= $para ?></p>
<?php endforeach; ?>
          </div>
        </details>
<?php endforeach; ?>
      </div>
    </div>

  </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
