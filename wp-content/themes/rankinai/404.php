<?php
/** Not found. The close is hidden here by footer.php. */
get_header();
?>
<section class="hero hero--centred">
  <div class="hero__inner container">
    <p class="eyebrow"><span>Page not found</span></p>
    <h1 class="hero__title">This page has moved, or never existed.</h1>
    <div class="hero__meta hero__meta--home">
      <p class="hero__sub">Try the home page, or tell us what you were looking for.</p>
      <div class="hero__actions">
        <a class="btn btn--primary" href="<?= url('/') ?>">Go to the home page <?= btn_arrow() ?></a>
        <a class="link-quiet" href="<?= url('/contact/') ?>">Contact us</a>
      </div>
    </div>
  </div>
</section>
<?php
get_footer();
