<?php
/**
 * RankinAI — site footer
 * =============================================================================
 * The flat build's includes/footer.php. The scripts moved into functions.php
 * as enqueues and print from wp_footer(). Its words and links come from
 * Website settings → Footer (inc/chrome.php), falling back to the original.
 * Cream, never forest: the close section above it is the page's one dark
 * band and two stacked would swallow it.
 * =============================================================================
 */
global $SITE;
$FOOT = rankinai_chrome( 'footer' );

/* The close is part of the page, not the footer, so it goes inside <main>. A
   page opts out with the "Hide the closing section" switch in its page
   settings (the flat build's $no_close), for the same reason the contact page
   does: when the page already makes the close's two asks itself. */
$rankinai_no_close = is_singular() && function_exists( 'get_field' ) && get_field( 'hide_close' );
if ( ! $rankinai_no_close && ! is_404() ) {
	/* A model may change the close's two links, for the same reason the flat
	   build's growth-audit and call pages did: the standard link would point
	   at the page the reader is already on. See 'close' in inc/models/. */
	$rankinai_model = is_singular() && function_exists( 'rankinai_model_for' ) ? rankinai_model_for() : null;
	get_template_part( 'template-parts/close', null, $rankinai_model['close'] ?? array() );
}
?>
</main>

<!-- =============================================================================
     FOOTER
     Services and company pages. Industries are reached from the header menu.
     ============================================================================= -->
<footer class="site-footer">
  <div class="container">

    <div class="footer__cols footer__cols--row">
      <div class="footer__about">
        <a class="footer__mark" href="<?= url('/') ?>">
          <img src="<?= asset('images/logo-forest.svg') ?>" alt="<?= e($SITE['name']) ?>" width="1713" height="356">
        </a>
<?php foreach ($FOOT['about'] as $para): ?>
        <p><?= $para ?></p>
<?php endforeach; ?>
<?php if (!empty($FOOT['strap'])): ?>
        <p class="footer__strap"><?= $FOOT['strap'] ?></p>
<?php endif; ?>
      </div>
<?php foreach ($FOOT['cols'] as $col): ?>

      <div class="footer__col<?= !empty($col['quiet']) ? ' footer__col--quiet' : '' ?>">
        <p class="label<?= empty($col['quiet']) ? ' label--clay' : '' ?>"><?= $col['label'] ?? '' ?></p>
        <ul role="list">
<?php foreach ($col['links'] ?? [] as [$label, $href]): ?>
          <li><a href="<?= rankinai_href($href) ?>"><?= $label ?></a></li>
<?php endforeach; ?>
        </ul>
      </div>
<?php endforeach; ?>

      <div class="footer__col footer__contact">
        <p class="label"><?= $FOOT['contactLabel'] ?></p>
        <a class="link-quiet link-quiet--bold" href="mailto:<?= e($SITE['email']) ?>"><?= e($SITE['email']) ?></a>
        <p class="footer__pair"><span class="label"><?= $FOOT['phoneLabel'] ?></span><b><?= e($SITE['phone']) ?></b></p>
        <p class="footer__pair"><span class="label"><?= $FOOT['officesLabel'] ?></span><b><?= e($SITE['offices']) ?></b></p>
      </div>

    </div>

    <div class="footer__base">
      <p class="footer__legal">
        &copy; <?= e($SITE['name']) ?> <?= e($SITE['year']) ?><?php foreach ($FOOT['legal'] as [$label, $href]): ?> &middot;
        <a href="<?= rankinai_href($href) ?>"><?= $label ?></a><?php endforeach; ?>

      </p>
    </div>

  </div>
</footer>

<?php /* The growth audit modal. One per page, before the scripts that drive
         it. Every "get your growth audit" link opens this instead of loading
         the page; without the script the link still loads the page. */ ?>
<?php get_template_part( 'template-parts/modal' ); ?>

<?php wp_footer(); ?>
</body>
</html>
