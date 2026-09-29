<?php
/**
 * Template Name: Pricing page
 *
 * Pricing
 * -----------------------------------------------------------------------------
 * Ported 29 Sep 2026 from the flat build's pricing.php. The markup is the flat
 * build's, unchanged. The content comes from the page's ACF fields through
 * rankinai_model_data() (inc/models/page-pricing.php) instead of the arrays
 * the flat file carried inline ($PLANS, $FULL, $TERMS, $FAQ).
 *
 * EVERY FIGURE ON THIS PAGE IS A PRICE KULWANT SET. $750, $1,250, $1,750, from
 * $2,500, and $500 setup. Nothing else here is a number. See the flat file's
 * header for why the plans are rows, not columns, and why the full
 * deliverables open in place.
 */
global $SITE, $NAV, $FOOTER;
$D = rankinai_model_data();
get_header();
?>

<?php if (!empty($D['hero'])): $H = $D['hero']; ?>
<!-- ==========================================================================
     01 — HERO
     ========================================================================== -->
<section class="hero hero--centred">
  <div class="hero__inner container">

<?php if (!empty($H['eyebrow'])): ?>
    <p class="eyebrow"><span><?= $H['eyebrow'] ?></span></p>
<?php endif; ?>

<?php if (!empty($H['h1'])): ?>
    <h1 class="hero__title"><?= $H['h1'] ?></h1>
<?php endif; ?>

    <div class="hero__meta hero__meta--home">
<?php if (!empty($H['sub']) || !empty($H['sub2'])): ?>
      <p class="hero__sub"><?= $H['sub'] ?? '' ?><?php if (!empty($H['sub2'])): ?><span class="hero__sub2"><?= $H['sub2'] ?></span><?php endif; ?></p>
<?php endif; ?>
<?php if (!empty($H['btn']) || !empty($H['link'])): ?>
      <div class="hero__actions">
<?php if (!empty($H['btn'])): ?>
        <a class="btn btn--primary" href="<?= url($H['btn'][1]) ?>"><?= $H['btn'][0] ?> <?= btn_arrow() ?></a>
<?php endif; ?>
<?php if (!empty($H['link'])): ?>
        <a class="link-quiet" href="<?= url($H['link'][1]) ?>"><?= $H['link'][0] ?></a>
<?php endif; ?>
      </div>
<?php endif; ?>
    </div>

  </div>
</section>
<?php endif; ?>


<?php if (!empty($D['plans'])): $PH = $D['plansHead'] ?? array(); ?>
<!-- ==========================================================================
     02 — THE FOUR PLANS
     ========================================================================== -->
<section class="band band--forest">
  <div class="container">

    <div class="stories__head">
      <div>
<?php if (!empty($PH['eyebrow'])): ?>
        <p class="eyebrow eyebrow--light"><span><?= $PH['eyebrow'] ?></span></p>
<?php endif; ?>
<?php if (!empty($PH['heading'])): ?>
        <h2 class="stories__title"><?= $PH['heading'] ?></h2>
<?php endif; ?>
      </div>
    </div>

    <div class="plans">
<?php foreach ($D['plans'] as $p): ?>
      <article class="plan2">

        <div class="plan2__id">
          <h3 class="plan2__name"><?= $p['name'] ?? '' ?></h3>
          <p class="plan2__price">
<?php if (!empty($p['from'])): ?><span class="plan2__from"><?= $p['from'] ?></span><?php endif; ?>
            <b><?= $p['price'] ?? '' ?></b><?php if (!empty($PH['per'])): ?><span><?= $PH['per'] ?></span><?php endif; ?>
          </p>
<?php if (!empty($p['line'])): ?>
          <p class="plan2__line"><?= $p['line'] ?></p>
<?php endif; ?>
<?php if (!empty($p['who'])): ?>
          <p class="plan2__who"><?= $p['who'] ?></p>
<?php endif; ?>
<?php if (!empty($p['does'])): ?>
          <p class="plan2__does"><?= $p['does'] ?></p>
<?php endif; ?>
        </div>

        <div class="plan2__get">
<?php if (!empty($p['inherits'])): ?>
          <p class="label label--clay"><?= $p['inherits'] ?></p>
<?php endif; ?>
<?php if (!empty($p['items'])): ?>
          <ul class="deliv__list" role="list">
<?php foreach ($p['items'] as $item): ?>
            <li><?= $item ?></li>
<?php endforeach; ?>
          </ul>
<?php endif; ?>
<?php if (!empty($p['foot'])): ?>
          <p class="plan2__foot"><?= $p['foot'] ?></p>
<?php endif; ?>
        </div>

        <?php /* The button first and the deliverables link beside it, on one
                 line across the foot of the card. The <details> is
                 display: contents, see the flat build's pricing.php. */ ?>
        <div class="plan2__act">
<?php if (!empty($p['cta'])): ?>
          <a class="btn btn--primary" href="<?= url($p['cta'][1]) ?>"><?= $p['cta'][0] ?> <?= btn_arrow() ?></a>
<?php endif; ?>

<?php if (!empty($p['full'])): ?>
          <details class="plist">
            <summary class="plist__toggle">
              <span class="plist__mark" aria-hidden="true"></span>
              <span class="plist__label plist__label--more"><?= $PH['more'] ?? '' ?></span>
              <span class="plist__label plist__label--less"><?= $PH['less'] ?? '' ?></span>
            </summary>
            <div class="plist__body">
              <div class="deliv__grid deliv__grid--plan">
<?php foreach ($p['full'] as $g): ?>
                <div class="deliv">
                  <p class="deliv__head"><?= $g['head'] ?? '' ?></p>
<?php if (!empty($g['lines'])): ?>
                  <ul class="deliv__list" role="list">
<?php foreach ($g['lines'] as $line): ?>
                    <li><?= $line ?></li>
<?php endforeach; ?>
                  </ul>
<?php endif; ?>
                </div>
<?php endforeach; ?>
              </div>
            </div>
          </details>
<?php endif; ?>
        </div>

      </article>
<?php endforeach; ?>
    </div>

  </div>
</section>
<?php endif; ?>


<?php if (!empty($D['terms']['items'])): $T = $D['terms']; ?>
<!-- ==========================================================================
     03 — WHAT THE DELIVERABLES MEAN
     ========================================================================== -->
<section class="band band--forest">
  <div class="container">

    <div class="stories__head">
      <div>
<?php if (!empty($T['eyebrow'])): ?>
        <p class="eyebrow eyebrow--light"><span><?= $T['eyebrow'] ?></span></p>
<?php endif; ?>
<?php if (!empty($T['heading'])): ?>
        <h2 class="stories__title"><?= $T['heading'] ?></h2>
<?php endif; ?>
      </div>
    </div>

    <div class="wcards wcards--three">
<?php foreach ($T['items'] as $i => [$icon, $name, $text]): ?>
      <div class="wcard">
        <span class="wcard__icon"><?= svc_icon_svg($icon) ?></span>
        <span class="wcard__n"><?= sprintf('%02d', $i + 1) ?></span>
        <h3 class="wcard__name"><?= $name ?></h3>
        <p class="wcard__text"><?= $text ?></p>
      </div>
<?php endforeach; ?>
    </div>

  </div>
</section>
<?php endif; ?>


<?php if (!empty($D['setup'])): $U = $D['setup']; ?>
<!-- ==========================================================================
     04 — GETTING STARTED
     ========================================================================== -->
<section class="band band--light">
  <div class="container">

    <div class="questions__head">
      <div>
<?php if (!empty($U['heading'])): ?>
        <h2 class="questions__title"><?= $U['heading'] ?></h2>
<?php endif; ?>
      </div>
    </div>

    <div class="think">

      <div class="think__say">
<?php if (!empty($U['label'])): ?>
        <p class="label label--clay"><?= $U['label'] ?></p>
<?php endif; ?>
<?php if (!empty($U['lead'])): ?>
        <p class="think__lead"><?= $U['lead'] ?></p>
<?php endif; ?>
<?php foreach ($U['paras'] ?? array() as $para): ?>
        <p><?= $para ?></p>
<?php endforeach; ?>
      </div>

      <div class="think__rules">
<?php if (!empty($U['listLabel'])): ?>
        <p class="label label--clay"><?= $U['listLabel'] ?></p>
<?php endif; ?>
<?php if (!empty($U['list'])): ?>
        <ul class="deliv__list" role="list">
<?php foreach ($U['list'] as $li): ?>
          <li><?= $li ?></li>
<?php endforeach; ?>
        </ul>
<?php endif; ?>
      </div>

    </div>

<?php if (!empty($U['starts'])): ?>
    <div class="starts">
<?php foreach ($U['starts'] as [$flag, $name, $text]): ?>
      <div class="start<?= '' !== $flag ? ' start--flag' : '' ?>">
<?php if ('' !== $flag): ?>
        <p class="label label--clay"><?= $flag ?></p>
<?php endif; ?>
        <h3 class="start__name"><?= $name ?></h3>
        <p class="start__text"><?= $text ?></p>
      </div>
<?php endforeach; ?>
    </div>
<?php endif; ?>

  </div>
</section>
<?php endif; ?>


<?php if (!empty($D['why'])): $W = $D['why']; ?>
<!-- ==========================================================================
     05 — WHAT YOU'RE PAYING FOR
     ========================================================================== -->
<section class="band band--forest">
  <div class="container why">

    <div class="why__head">
<?php if (!empty($W['eyebrow'])): ?>
      <p class="eyebrow eyebrow--light"><span><?= $W['eyebrow'] ?></span></p>
<?php endif; ?>
<?php if (!empty($W['heading'])): ?>
      <h2 class="why__title"><?= $W['heading'] ?></h2>
<?php endif; ?>
<?php if (!empty($W['sub'])): ?>
      <p class="why__sub"><?= $W['sub'] ?></p>
<?php endif; ?>
    </div>

    <div class="why__body">
<?php if (!empty($W['lead'])): ?>
      <p class="why__lead"><?= $W['lead'] ?></p>
<?php endif; ?>
<?php if (!empty($W['ticks'])): ?>
      <ul class="ticks" role="list">
<?php foreach ($W['ticks'] as $li): ?>
        <li><?= $li ?></li>
<?php endforeach; ?>
      </ul>
<?php endif; ?>
<?php if (!empty($W['tail'])): ?>
      <p><?= $W['tail'] ?></p>
<?php endif; ?>
    </div>

  </div>
</section>
<?php endif; ?>


<?php if (!empty($D['faq']['qs'])): $F = $D['faq']; ?>
<!-- ==========================================================================
     06 — QUESTIONS ABOUT PRICE
     ========================================================================== -->
<section class="band band--light qbody">
  <div class="container">

    <div class="qgroup">
      <div class="qgroup__head">
<?php if (!empty($F['eyebrow'])): ?>
        <p class="eyebrow"><span><?= $F['eyebrow'] ?></span></p>
<?php endif; ?>
<?php if (!empty($F['heading'])): ?>
        <h2 class="qgroup__title"><?= $F['heading'] ?></h2>
<?php endif; ?>
<?php if (!empty($F['note'])): ?>
        <p class="qgroup__note"><?= $F['note'] ?></p>
<?php endif; ?>
      </div>

      <div class="qa">
<?php foreach ($F['qs'] as $i => [$q, $a]): ?>
        <details class="qi" name="faq">
          <summary class="qi__q">
            <span class="qi__n"><?= sprintf('%02d', $i + 1) ?></span>
            <span class="qi__text"><?= $q ?></span>
            <span class="qi__mark" aria-hidden="true"></span>
          </summary>
          <div class="qi__a">
<?php foreach ((array) $a as $para): ?>
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
