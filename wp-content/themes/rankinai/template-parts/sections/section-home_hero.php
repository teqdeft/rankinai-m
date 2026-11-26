<?php
/**
 * Section: home hero. The flat build's index.php section 01, which carries
 * the rules for these slots in its header comment. The short version: the
 * eyebrow says what we are and who for, one button with a quiet link beside
 * it, and no AI on the first screen.
 */
$d       = rankinai_section_defaults( 'home_hero' );
$eyebrow = rf( 'eyebrow', $d['eyebrow'] );
$title   = rf( 'title', $d['title'] );
$sub     = rf( 'sub', $d['sub'] );
$sub2    = rf( 'sub2', $d['sub2'] );
list( $btn, $btn_href )   = rf_link( rf( 'button', null ), array( $d['button']['title'], $d['button']['url'] ) );
list( $link, $link_href ) = rf_link( rf( 'link', null ), array( $d['link']['title'], $d['link']['url'] ) );
?>
<section class="hero hero--centred">
  <div class="hero__inner container">

    <p class="eyebrow"><span><?= wp_kses_post( $eyebrow ) ?></span></p>

    <h1 class="hero__title"><?= wp_kses_post( $title ) ?></h1>

    <div class="hero__meta hero__meta--home">
      <p class="hero__sub"><?= wp_kses_post( $sub ) ?><?php if ( $sub2 ) : ?><span class="hero__sub2"><?= wp_kses_post( $sub2 ) ?></span><?php endif; ?></p>
      <div class="hero__actions">
        <a class="btn btn--primary" href="<?= esc_url( $btn_href ) ?>"><?= wp_kses_post( $btn ) ?> <?= btn_arrow() ?></a>
        <a class="link-quiet" href="<?= esc_url( $link_href ) ?>"><?= wp_kses_post( $link ) ?></a>
      </div>
    </div>

  </div>
</section>
