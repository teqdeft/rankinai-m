<?php
/**
 * Template Name: Legal page
 *
 * Privacy policy and terms. The flat build's includes/legal-template.php,
 * reading its array from the page's fields (inc/models/legal.php). A contents
 * column on the left, numbered clauses on the right.
 *
 * THE DRAFT BANNER IS NOT DECORATION. Remove it only when a lawyer has signed
 * the document off, not when it reads well.
 */
global $SITE, $NAV, $FOOTER;
$L = rankinai_model_data();
get_header();
?>

<section class="hero hero--page">
  <div class="hero__inner container">
    <h1 class="hero__title hero__title--page"><?= $L['title'] ?? '' ?></h1>

    <div class="hero__meta hero__meta--page">
      <div class="legal__meta">
        <p class="legal__pair"><span class="label">Last updated</span><b><?= $L['updated'] ?? '' ?></b></p>
        <p class="legal__pair"><span class="label">Reading time</span><b><?= $L['reading'] ?? '' ?></b></p>
      </div>
    </div>
  </div>

<?php if (!empty($L['draft'])): ?>
  <div class="container">
    <div class="draftbar" role="note">
      <p class="label label--clay">Not yet reviewed</p>
      <p class="draftbar__text"><?= $L['draft'] ?></p>
    </div>
  </div>
<?php endif; ?>

  <div class="hero__proof legal__summary">
    <div>
      <p class="label">The short version</p>
      <ul class="ticks" role="list">
<?php foreach ($L['summary'] ?? [] as $s): ?>
        <li><?= $s ?></li>
<?php endforeach; ?>
      </ul>
      <p class="legal__caveat">The summary is here to be useful, not to replace what follows. Where the two differ, the full document applies.</p>
    </div>
  </div>
</section>


<section class="band band--light legalbody">
  <div class="container legal">

    <nav class="legal__toc" aria-label="Contents">
      <p class="label">Contents</p>
      <ol>
<?php foreach ($L['toc'] ?? [] as $i => [$id, $t]): ?>
        <li><a href="#<?= e($id) ?>"><span class="legal__n"><?= sprintf('%02d', $i + 1) ?></span><?= $t ?></a></li>
<?php endforeach; ?>
      </ol>
    </nav>

    <div class="legal__doc">
<?php foreach ($L['sections'] ?? [] as $sec): ?>
      <section class="clause" id="<?= e($sec['id'] ?? '') ?>">
        <p class="label label--clay"><?= $sec['num'] ?? '' ?></p>
        <h2 class="clause__h"><?= $sec['heading'] ?? '' ?></h2>
        <?= $sec['body'] ?? '' ?>
      </section>
<?php endforeach; ?>
    </div>

  </div>
</section>

<?php get_footer(); ?>
