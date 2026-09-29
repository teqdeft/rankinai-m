<?php
/**
 * RankinAI — document head, and the site header
 * =============================================================================
 * The flat build's includes/header.php. What moved: <title>, the meta
 * description, robots, fonts, styles and the favicon now come from wp_head()
 * (see functions.php). The header markup is unchanged. The menu, the text link
 * and the button come from Website settings → Header (inc/chrome.php), falling
 * back to the flat build's $NAV.
 * =============================================================================
 */
global $SITE;
$HEADER = rankinai_chrome( 'header' );
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main">Skip to content</a>

<!-- =============================================================================
     HEADER
     Six nav items, one green button. No phone number, no login, no search.
     Three of the six open panels; the rest link straight through.
     ============================================================================= -->
<header class="site-header 1" data-header>
  <div class="site-header__panel">

    <a class="wordmark" href="<?= url('/') ?>" aria-label="<?= e($SITE['name']) ?> home">Rankin<em>AI</em><i>.</i></a>

    <button class="nav-toggle" type="button" data-nav-toggle aria-expanded="false" aria-controls="primary-nav">
      <span class="nav-toggle__bars" aria-hidden="true"></span>
      <span class="nav-toggle__label">Menu</span>
    </button>

    <nav class="nav" id="primary-nav" data-nav data-lenis-prevent aria-label="Primary">
      <ul class="nav__list" role="list">
<?php foreach ($HEADER['nav'] as $item): if (empty($item['label'])) continue; ?>
<?php if (empty($item['cols'])): ?>
        <li class="nav__item">
          <a class="nav__link<?= is_current($item['link'] ?? '/') ? ' is-current' : '' ?>" href="<?= rankinai_href($item['link'] ?? '/') ?>"><?= $item['label'] ?></a>
        </li>
<?php else: ?>
        <li class="nav__item">
          <button class="nav__link" type="button" data-panel-trigger aria-expanded="false" aria-controls="<?= e($item['id'] = 'panel-' . sanitize_title(wp_strip_all_tags($item['label']))) ?>">
            <?= $item['label'] ?> <span class="nav__chev" aria-hidden="true"></span>
          </button>

          <div class="mega" id="<?= e($item['id']) ?>" data-panel>
            <div class="mega__inner">
<?php foreach ($item['cols'] as $col): ?>
              <div class="mega__col">
                <p class="label"><?= $col['label'] ?? '' ?></p>
                <ul class="mega__list" role="list">
<?php foreach ($col['items'] ?? [] as [$name, $href, $desc]): ?>
                  <li><a href="<?= rankinai_href($href) ?>"><strong><?= $name ?></strong><span><?= $desc ?></span></a></li>
<?php endforeach; ?>
                </ul>
              </div>
<?php endforeach; ?>
<?php if (!empty($item['offer']['label']) || !empty($item['offer']['text'])): ?>
              <div class="mega__col mega__offer">
                <p class="label label--clay"><?= $item['offer']['label'] ?? '' ?></p>
                <p><?= $item['offer']['text'] ?? '' ?></p>
<?php if (!empty($item['offer']['cta'][0])): ?>
                <a class="btn btn--primary" href="<?= rankinai_href($item['offer']['cta'][1]) ?>"><?= $item['offer']['cta'][0] ?></a>
<?php endif; ?>
              </div>
<?php endif; ?>
            </div>
          </div>
        </li>
<?php endif; ?>
<?php endforeach; ?>
      </ul>

      <div class="nav__actions">
        <a class="nav__secondary" href="<?= rankinai_href($HEADER['secondary'][1]) ?>"><?= $HEADER['secondary'][0] ?></a>
        <a class="btn btn--green" href="<?= rankinai_href($HEADER['primary'][1]) ?>"><?= $HEADER['primary'][0] ?> <?= btn_arrow() ?></a>
      </div>
    </nav>

  </div>
</header>

<main id="main">
