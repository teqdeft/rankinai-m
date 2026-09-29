<?php
/**
 * Template Name: Category hub page
 *
 * The three "who we work with" category pages. The flat build's
 * includes/category-template.php, reading its array from the page's fields
 * (inc/models/hub.php). Deliberately short: a hub that restates each industry
 * page in miniature gives the reader two places to read the same thing.
 */
global $SITE, $NAV, $FOOTER;
$C = rankinai_model_data();
get_header();
?>

<!-- 01 — HERO ============================================================ -->
<section class="hero hero--page">
  <div class="hero__inner container">
    <h1 class="hero__title hero__title--page"><?= $C['h1'] ?? '' ?></h1>

    <div class="hero__meta hero__meta--page">
      <p class="hero__sub"><?= $C['sub'] ?? '' ?></p>
      <div class="hero__actions">
        <a class="btn btn--primary" href="<?= url('/growth-audit/') ?>">Get your growth audit</a>
        <p class="hero__note"><?= $C['ctaNote'] ?? '' ?></p>
      </div>
    </div>
  </div>

<?php if (!empty($C['stats'])): ?>
  <div class="hero__proof">
<?php foreach ($C['stats'] as [$n, $k]): ?>
    <div class="stat">
      <span class="stat__n"><?= $n ?></span>
      <span class="stat__k"><?= $k ?></span>
    </div>
<?php endforeach; ?>
  </div>
<?php endif; ?>
</section>


<!-- 02 — PICK THE TRADE ================================================== -->
<?php if (!empty($C['industries'])): ?>
<section class="band band--forest band--tuck">
  <div class="container">
    <div class="stories__head">
      <div>
        <h2 class="stories__title"><?= $C['sectorsTitle'] ?? '' ?></h2>
      </div>
    </div>

    <div class="hows hows--<?= ['two', 'two', 'three', 'four'][min(count($C['industries']), 4) - 1] ?> hows--onforest">
<?php foreach ($C['industries'] as [$name, $href, $text]): ?>
      <div class="how">
        <p class="how__name"><a href="<?= url($href) ?>"><?= $name ?></a></p>
        <p class="how__text"><?= $text ?></p>
        <a class="link-quiet link-quiet--onforest" href="<?= url($href) ?>"><?= $C['sectorsLink'] ?? '' ?></a>
      </div>
<?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>


<!-- 03 — WHAT THEY SHARE ================================================= -->
<?php if (!empty($C['shared'])): ?>
<section class="band band--light">
  <div class="container">
    <div class="questions__head">
      <div>
        <h2 class="questions__title"><?= $C['sharedTitle'] ?? '' ?></h2>
      </div>
      <p class="stories__note"><?= $C['sharedNote'] ?? '' ?></p>
    </div>

    <div class="hows hows--three">
<?php foreach ($C['shared'] as $i => [$name, $text]): ?>
      <div class="how">
        <p class="label label--clay"><?= sprintf('%02d', $i + 1) ?></p>
        <p class="how__name"><?= $name ?></p>
        <p class="how__text"><?= $text ?></p>
      </div>
<?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
