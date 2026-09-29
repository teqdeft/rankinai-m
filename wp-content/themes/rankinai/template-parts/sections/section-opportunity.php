<?php
/**
 * Section: the opportunity. The flat build's index.php section 03: three
 * questions rather than three claims, because a reader can answer a question
 * about their own firm and cannot argue with it.
 */
$d     = rankinai_section_defaults( 'opportunity' );
$title = rf( 'title', $d['title'] );
$intro = rf( 'intro', $d['intro'] );
$lead  = rf( 'lead', $d['lead'] );
$items = rf_rows( 'items', $d['items'] );
$close = rf( 'close', $d['close'] );
?>
<section class="band band--light">
  <div class="container">

    <div class="questions__head">
      <div>
        <h2 class="questions__title"><?= wp_kses_post( $title ) ?></h2>
      </div>
      <div class="oppo__intro">
        <p><?= wp_kses_post( $intro ) ?></p>
      </div>
    </div>

    <p class="oppo__lead"><?= wp_kses_post( $lead ) ?></p>

    <div class="beliefs beliefs--icons beliefs--light beliefs--n<?= count( $items ) ?>">
<?php foreach ( $items as $it ) : ?>
      <div class="how">
        <p class="how__mark"><?= svc_icon_svg( (string) ( $it['icon'] ?? 'dot' ) ) ?></p>
        <p class="how__name"><?= wp_kses_post( $it['name'] ?? '' ) ?></p>
        <p class="how__text"><?= wp_kses_post( $it['text'] ?? '' ) ?></p>
      </div>
<?php endforeach; ?>
    </div>

    <p class="oppo__close"><?= wp_kses_post( $close ) ?></p>

  </div>
</section>
