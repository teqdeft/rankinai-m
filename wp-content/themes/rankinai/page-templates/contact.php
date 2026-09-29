<?php
/**
 * Template Name: Contact page
 *
 * Contact
 * -----------------------------------------------------------------------------
 * Ported 29 Sep 2026 from the flat build's contact.php. The markup is the flat
 * build's, unchanged. The content comes from the page's ACF fields through
 * rankinai_model_data() (inc/models/page-contact.php). The email, phone and
 * address stay in $SITE: they are site-wide settings, not this page's content.
 *
 * THE FORM POSTS NOWHERE YET. action="#" and the script shows the confirmation
 * in place, worded by data-sent-label and data-sent-text. Its field names and
 * attributes are the flat build's. Wiring it to a mailbox has not been done.
 *
 * No shared close on this page: the seed sets hide_close, since its two asks
 * are the two route cards this page opens with.
 */
global $SITE, $NAV, $FOOTER;
$D = rankinai_model_data();
get_header();
?>

<?php if (!empty($D['hero'])): $H = $D['hero']; ?>
<!-- ==========================================================================
     01 — HERO
     ========================================================================== -->
<section class="hero hero--centred hero--compact">
  <div class="hero__inner container">

<?php if (!empty($H['eyebrow'])): ?>
    <p class="eyebrow"><span><?= $H['eyebrow'] ?></span></p>
<?php endif; ?>

<?php if (!empty($H['h1'])): ?>
    <h1 class="hero__title"><?= $H['h1'] ?></h1>
<?php endif; ?>

<?php if (!empty($H['sub']) || !empty($H['sub2'])): ?>
    <div class="hero__meta hero__meta--home">
      <p class="hero__sub"><?= $H['sub'] ?? '' ?><?php if (!empty($H['sub2'])): ?><span class="hero__sub2"><?= $H['sub2'] ?></span><?php endif; ?></p>
    </div>
<?php endif; ?>

  </div>
</section>
<?php endif; ?>


<?php if (!empty($D['routes'])): $RH = $D['routesHead'] ?? array(); ?>
<!-- ==========================================================================
     02 — CHOOSE YOUR NEXT STEP
     ========================================================================== -->
<section class="band band--forest">
  <div class="container">

    <div class="stories__head">
      <div>
<?php if (!empty($RH['eyebrow'])): ?>
        <p class="eyebrow eyebrow--light"><span><?= $RH['eyebrow'] ?></span></p>
<?php endif; ?>
<?php if (!empty($RH['heading'])): ?>
        <h2 class="stories__title"><?= $RH['heading'] ?></h2>
<?php endif; ?>
      </div>
    </div>

    <div class="routes">

<?php foreach ($D['routes'] as $r): ?>
      <article class="route">
<?php if (!empty($r['image'])): ?>
        <figure class="route__img"><img src="<?= e(rankinai_img_url($r['image'])) ?>" alt="<?= $r['alt'] ?? '' ?>"<?php if (!empty($r['width'])): ?> width="<?= e($r['width']) ?>"<?php endif; ?><?php if (!empty($r['height'])): ?> height="<?= e($r['height']) ?>"<?php endif; ?> loading="lazy" decoding="async"></figure>
<?php endif; ?>
<?php if (!empty($r['said'])): ?>
        <p class="route__said"><?= $r['said'] ?></p>
<?php endif; ?>
<?php if (!empty($r['name'])): ?>
        <h3 class="route__name"><?= $r['name'] ?></h3>
<?php endif; ?>
<?php if (!empty($r['text'])): ?>
        <p class="route__text"><?= $r['text'] ?></p>
<?php endif; ?>
<?php if (!empty($r['note'])): ?>
        <p class="route__note"><?= $r['note'] ?></p>
<?php endif; ?>
<?php if (!empty($r['btn'])): ?>
        <a class="btn btn--cream" href="<?= url($r['btn'][1]) ?>"><?= $r['btn'][0] ?> <?= btn_arrow() ?></a>
<?php endif; ?>
      </article>

<?php endforeach; ?>
    </div>

  </div>
</section>
<?php endif; ?>


<?php if (!empty($D['message'])): $M = $D['message']; ?>
<!-- ==========================================================================
     03 — SEND US A MESSAGE
     ========================================================================== -->
<section class="band band--light" id="message">
  <div class="container">

    <div class="msg">
    <div class="msg__side">
    <div class="questions__head">
      <div>
<?php if (!empty($M['eyebrow'])): ?>
        <p class="eyebrow"><span><?= $M['eyebrow'] ?></span></p>
<?php endif; ?>
<?php if (!empty($M['heading'])): ?>
        <h2 class="questions__title"><?= $M['heading'] ?></h2>
<?php endif; ?>
<?php if (!empty($M['note'])): ?>
        <p class="stories__note"><?= $M['note'] ?></p>
<?php endif; ?>
      </div>
    </div>
<?php if (!empty($M['image'])): ?>
    <figure class="msg__photo"><img src="<?= e(rankinai_img_url($M['image'])) ?>" alt="<?= $M['alt'] ?? '' ?>"<?php if (!empty($M['width'])): ?> width="<?= e($M['width']) ?>"<?php endif; ?><?php if (!empty($M['height'])): ?> height="<?= e($M['height']) ?>"<?php endif; ?> loading="lazy" decoding="async"></figure>
<?php endif; ?>
    </div>

    <?php /* The form is Contact Form 7 (inc/forms.php): its fields, button,
             note and confirmation are edited under Contact. */ ?>
    <?= rankinai_form( 'contact', '', 'auditform auditform--light' ) ?>
    </div>

  </div>
</section>
<?php endif; ?>


<?php if (!empty($D['reach'])): $R = $D['reach']; ?>
<!-- ==========================================================================
     04 — OTHER REASONS TO GET IN TOUCH
     The email, phone and address are site settings ($SITE), not page fields.
     ========================================================================== -->
<section class="band band--forest">
  <div class="container">

    <div class="stories__head">
      <div>
<?php if (!empty($R['eyebrow'])): ?>
        <p class="eyebrow eyebrow--light"><span><?= $R['eyebrow'] ?></span></p>
<?php endif; ?>
<?php if (!empty($R['heading'])): ?>
        <h2 class="stories__title"><?= $R['heading'] ?></h2>
<?php endif; ?>
      </div>
    </div>

    <div class="reach">
    <div class="direct">
<?php if (!empty($SITE['email'])): ?>
      <div class="direct__item">
        <p class="label label--clay"><?= $R['emailLabel'] ?? '' ?></p>
        <a class="link-quiet link-quiet--onforest link-quiet--bold" href="mailto:<?= e($SITE['email']) ?>"><?= e($SITE['email']) ?></a>
      </div>
<?php endif; ?>
<?php if (!empty($SITE['phone'])): ?>
      <div class="direct__item">
        <p class="label label--clay"><?= $R['phoneLabel'] ?? '' ?></p>
        <a class="link-quiet link-quiet--onforest link-quiet--bold" href="tel:<?= e(preg_replace('/\s+/', '', $SITE['phone'])) ?>"><?= e($SITE['phone']) ?></a>
      </div>
<?php endif; ?>
<?php if (!empty($SITE['address'])): ?>
      <div class="direct__item">
        <p class="label label--clay"><?= $R['officeLabel'] ?? '' ?></p>
        <p class="direct__addr"><?= e($SITE['address']) ?></p>
      </div>
<?php endif; ?>
    </div>
<?php if (!empty($R['image'])): ?>
    <figure class="reach__photo"><img src="<?= e(rankinai_img_url($R['image'])) ?>" alt="<?= $R['alt'] ?? '' ?>"<?php if (!empty($R['width'])): ?> width="<?= e($R['width']) ?>"<?php endif; ?><?php if (!empty($R['height'])): ?> height="<?= e($R['height']) ?>"<?php endif; ?> loading="lazy" decoding="async"></figure>
<?php endif; ?>
    </div>

<?php if (!empty($R['reasons'])): ?>
    <div class="wcards wcards--three">
<?php foreach ($R['reasons'] as $i => [$icon, $name, $text]): ?>
      <div class="wcard">
        <span class="wcard__icon"><?= svc_icon_svg($icon) ?></span>
        <span class="wcard__n"><?= sprintf('%02d', $i + 1) ?></span>
        <h3 class="wcard__name"><?= $name ?></h3>
        <p class="wcard__text"><?= $text ?></p>
      </div>
<?php endforeach; ?>
    </div>
<?php endif; ?>

  </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
