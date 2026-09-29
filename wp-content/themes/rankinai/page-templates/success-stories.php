<?php
/**
 * Template Name: Success stories page
 *
 * The proof page. The flat build's success-stories.php, reading its words and
 * cards from the page's fields (inc/models/page-success-stories.php). A card
 * with no figure renders the pending state: never an adjective, never quietly
 * removed.
 */
global $SITE, $NAV, $FOOTER;
$D = rankinai_model_data();
get_header();
?>

<!-- 01 — HERO ============================================================ -->
<section class="hero hero--centred">
  <div class="hero__inner container">
    <p class="eyebrow"><span><?= $D['eyebrow'] ?? '' ?></span></p>
    <h1 class="hero__title"><?= $D['h1'] ?? '' ?></h1>
    <div class="hero__meta hero__meta--home">
      <p class="hero__sub"><?= $D['sub'] ?? '' ?><?php if (!empty($D['sub2'])): ?><span class="hero__sub2"><?= $D['sub2'] ?></span><?php endif; ?></p>
      <div class="hero__actions">
<?php if (!empty($D['btn'][0])): ?>
        <a class="btn btn--primary" href="<?= url($D['btn'][1] ?: '/growth-audit/') ?>"><?= $D['btn'][0] ?> <?= btn_arrow() ?></a>
<?php endif; ?>
<?php if (!empty($D['link'][0])): ?>
        <a class="link-quiet" href="<?= url($D['link'][1] ?: '/call/') ?>"><?= $D['link'][0] ?></a>
<?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- 02 — THE STORIES ===================================================== -->
<section class="band band--light band--tuck">
  <div class="container">
    <div class="questions__head">
      <div>
        <h2 class="questions__title"><?= $D['title'] ?? '' ?></h2>
      </div>
      <p class="stories__note"><?= $D['note'] ?? '' ?></p>
    </div>
    <div class="storylist">
<?php foreach ($D['cards'] ?? [] as $i => $c):
  $dark = !empty($c['dark']); ?>
      <article class="story<?= $dark ? ' story--dark' : '' ?>">
        <div class="story__copy">
          <div class="story__head">
            <span class="label label--clay"><?= sprintf('%02d', $i + 1) ?></span>
            <div>
              <h3 class="story__name"><?= $c['name'] ?? '' ?></h3>
              <p class="story__where"><?php foreach ($c['where'] ?? [] as $w): ?><span class="label"><?= $w ?></span><?php endforeach; ?></p>
            </div>
          </div>
<?php if (!empty($c['metric'])): ?>
          <div class="metric">
            <b class="metric__n"><?= $c['metric'] ?></b>
            <span class="metric__k"><?= $c['metricKey'] ?? '' ?></span>
          </div>
<?php else: ?>
          <div class="metric metric--pending">
            <b class="metric__n">&mdash;</b>
            <span class="metric__k">Figure with the client for sign-off</span>
          </div>
<?php endif; ?>
          <p class="story__text"><?= $c['text'] ?? '' ?></p>
<?php if (!empty($c['tags'])): ?>
          <ul class="tags" role="list">
<?php foreach ($c['tags'] as $t): ?>
            <li><?= $t ?></li>
<?php endforeach; ?>
          </ul>
<?php endif; ?>
<?php if (!empty($c['href'])): ?>
          <a class="link-quiet <?= $dark ? 'link-quiet--onforest' : 'link-quiet--bold' ?>" href="<?= url($c['href']) ?>"><?= $c['linkText'] ?? '' ?></a>
<?php endif; ?>
        </div>
<?php if (!empty($c['photo'])): ?>
        <div class="story__photo case__photo">
          <img src="<?= esc_url(rankinai_img_url($c['photo'])) ?>" alt="<?= e($c['photoAlt'] ?? '') ?>" width="1200" height="580" loading="lazy" decoding="async">
        </div>
<?php endif; ?>
      </article>
<?php endforeach; ?>
<?php if (!empty($D['more'][1])): ?>
      <div class="more">
        <p class="label label--clay"><?= $D['more'][0] ?? '' ?></p>
        <p class="more__title"><?= $D['more'][1] ?></p>
        <p class="more__text"><?= $D['more'][2] ?? '' ?></p>
      </div>
<?php endif; ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
