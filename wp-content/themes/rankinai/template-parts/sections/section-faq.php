<?php
/**
 * Section: questions, as an accordion. The flat build's index.php section 08.
 *
 * Native <details>, so it opens without JavaScript and every answer is in the
 * DOM. The shared name keeps one answer open at a time (see the note in
 * script.js). No FAQPage structured data here on purpose: on the home page
 * these are a subset of /questions/, which carries the schema.
 */
$d     = rankinai_section_defaults( 'faq' );
$title = rf( 'title', $d['title'] );
list( $ll, $lh ) = rf_link( rf( 'link', null ), array( $d['link']['title'], $d['link']['url'] ) );
$items = rf_rows( 'items', $d['items'] );
// One name per accordion on the page, so two FAQ sections do not close each other.
$GLOBALS['ri_faq_n'] = ( $GLOBALS['ri_faq_n'] ?? 0 ) + 1;
$ri_faq_n = $GLOBALS['ri_faq_n'];
?>
<section class="questions">
  <div class="container">
    <div class="questions__head">
      <div>
        <h2 class="questions__title"><?= wp_kses_post( $title ) ?></h2>
      </div>
      <a class="link-quiet link-quiet--bold" href="<?= esc_url( $lh ) ?>"><?= wp_kses_post( $ll ) ?></a>
    </div>

    <div class="qa qa--wide">
<?php foreach ( $items as $i => $it ) : ?>
      <details class="qi" name="faq-<?= (int) $ri_faq_n ?>">
        <summary class="qi__q">
          <span class="qi__n"><?= sprintf( '%02d', $i + 1 ) ?></span>
          <span class="qi__text"><?= wp_kses_post( $it['q'] ?? '' ) ?></span>
          <span class="qi__mark" aria-hidden="true"></span>
        </summary>
        <div class="qi__a"><?= wp_kses_post( $it['a'] ?? '' ) ?></div>
      </details>
<?php endforeach; ?>
    </div>
  </div>
</section>
