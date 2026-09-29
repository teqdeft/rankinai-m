<?php
/**
 * Section: commitments, as a card slider. The flat build's index.php
 * section 07. The test for each: could it be broken, and would the client
 * know? Anything that fails both is a sentiment and belongs somewhere else.
 *
 * A slider rather than a grid: four cards wrapped to three and one.
 */
$d     = rankinai_section_defaults( 'commitments' );
$title = rf( 'title', $d['title'] );
$items = rf_rows( 'items', $d['items'] );
?>
<section class="band band--light">
  <div class="container">

    <div class="questions__head">
      <div>
        <h2 class="questions__title"><?= wp_kses_post( $title ) ?></h2>
      </div>
    </div>

    <div class="cardslider" data-slider>
      <ul class="cardtrack" role="list" data-slider-track>
<?php foreach ( $items as $it ) : ?>
        <li class="how how--card">
          <p class="how__mark"><?= svc_icon_svg( (string) ( $it['icon'] ?? 'dot' ) ) ?></p>
          <p class="how__name"><?= wp_kses_post( $it['name'] ?? '' ) ?></p>
          <p class="how__text"><?= wp_kses_post( $it['text'] ?? '' ) ?></p>
        </li>
<?php endforeach; ?>
      </ul>
      <div class="teamnav">
        <button class="teamnav__btn" type="button" data-slider-prev aria-label="Previous">
          <svg viewBox="0 0 16 16" width="16" height="16" fill="none" aria-hidden="true" focusable="false">
            <path d="M14.5 8H4M7.4 4.6 4 8l3.4 3.4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
        <button class="teamnav__btn" type="button" data-slider-next aria-label="Next">
          <svg viewBox="0 0 16 16" width="16" height="16" fill="none" aria-hidden="true" focusable="false">
            <path d="M1.5 8H12M8.6 4.6 12 8l-3.4 3.4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
      </div>
    </div>

  </div>
</section>
