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

<?php /* WhatsApp, fixed at the bottom right of every page (added 7 Oct 2026).
         A plain link to a chat with the number on Website settings, so it
         needs no script and loads nothing from WhatsApp until it is used. */
$rankinai_wa = preg_replace( '/\D+/', '', rankinai_site( 'whatsapp' ) );
if ( '' !== $rankinai_wa ) : ?>
<a class="wafloat" href="https://wa.me/<?= e( $rankinai_wa ) ?>" target="_blank" rel="noopener" aria-label="Chat with us on WhatsApp, <?= e( rankinai_site( 'whatsapp' ) ) ?>" title="Chat with us on WhatsApp">
  <svg viewBox="0 0 24 24" width="32" height="32" fill="currentColor" aria-hidden="true" focusable="false"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
</a>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
