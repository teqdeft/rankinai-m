<?php
/**
 * Section: the six services, pinned scroll. The flat build's index.php
 * section 04. The left column is pinned while the six services scroll past
 * it, and the verb swaps from "Get found." to "Get booked." at service four
 * (script.js, bound to data-band, data-pin, data-svc and data-img).
 *
 * Each picture appears twice: once in the pinned column (desktop) and once in
 * its row (below 900px). CSS shows one or the other. Both are decorative, so
 * alt is empty on purpose.
 */
$d        = rankinai_section_defaults( 'services_scroll' );
$title    = rf( 'title', $d['title'] );
$note     = rf( 'note', $d['note'] );
$services = rf_rows( 'services', $d['services'] );
$pic = function ( $s ) {
	return rf_img( $s['image'] ?? null, 'service-search.webp', '', 1040, 1040, 'aria-hidden="true" loading="lazy" decoding="async"' );
};
?>
<section class="services">
  <div class="container services__head">
    <h2 class="services__title"><?= wp_kses_post( $title ) ?></h2>
    <p class="services__note"><?= wp_kses_post( $note ) ?></p>
  </div>

  <div class="container services__band" data-band>
    <div class="svc-media" data-pin>
      <div class="svc-media__verbs">
        <span class="svc-media__verb" data-verb="0">Get found.</span>
        <span class="svc-media__verb" data-verb="1">Get booked.</span>
      </div>
      <div class="svc-media__frame">
<?php foreach ( $services as $i => $s ) : ?>
        <div class="svc-media__shot" data-img="<?= (int) $i ?>"><?= $pic( $s ) ?></div>
<?php endforeach; ?>
      </div>
    </div>
    <div class="services__list">
<?php foreach ( $services as $i => $s ) :
	list( $ll, $lh ) = rf_link( $s['link'] ?? null, $s['_link'] ?? array( 'Explore', '/' ) ); ?>
      <article class="svc" data-svc="<?= (int) $i ?>">
        <p class="svc__n"><?= sprintf( '%02d', $i + 1 ) ?></p>
        <div class="svc__body">
          <div class="svc__shot" aria-hidden="true"><?= $pic( $s ) ?></div>
          <p class="svc__kicker"><?= wp_kses_post( $s['kicker'] ?? '' ) ?></p>
          <h3 class="svc__title"><?= wp_kses_post( $s['title'] ?? '' ) ?></h3>
          <p class="svc__lead"><?= wp_kses_post( $s['lead'] ?? '' ) ?></p>
          <a class="link-quiet link-quiet--onforest svc__more" href="<?= esc_url( $lh ) ?>"><?= wp_kses_post( $ll ) ?></a>
        </div>
      </article>
<?php endforeach; ?>
    </div>
  </div>
</section>
