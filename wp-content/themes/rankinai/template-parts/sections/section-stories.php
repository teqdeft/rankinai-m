<?php
/**
 * Section: success stories, three cases. The flat build's index.php
 * section 02.
 *
 * THE FIGURE IS THE ONE FIELD THAT MUST NOT BE GUESSED. Empty = the pending
 * state, "Figure with the client for sign-off". The 3.2x (SweetRush) and +58%
 * (Studio Ubique) that once sat here were invented during copywriting and came
 * off on 25 Sep 2026. See CLAIMS.md.
 */
$d     = rankinai_section_defaults( 'stories' );
$title = rf( 'title', $d['title'] );
$note  = rf( 'note', $d['note'] );
$cases = rf_rows( 'cases', $d['cases'] );
list( $more, $more_href ) = rf_link( rf( 'more', null ), array( $d['more']['title'], $d['more']['url'] ) );
?>
<section class="stories">
  <div class="container">
    <div class="stories__head">
      <div>
        <h2 class="stories__title"><?= wp_kses_post( $title ) ?></h2>
        <p class="stories__note"><?= wp_kses_post( $note ) ?></p>
      </div>
    </div>
    <div class="stories__grid">
<?php foreach ( $cases as $i => $c ) :
	list( $cl, $ch ) = rf_link( $c['link'] ?? null, array( 'Read their story', '/success-stories/' ) );
	$metric = trim( (string) ( $c['metric'] ?? '' ) );
	$meta   = array_filter( array_map( 'trim', explode( '|', (string) ( $c['meta'] ?? '' ) ) ) ); ?>
      <article class="case<?= 0 === $i ? ' case--lead' : '' ?>">
<?php /* An edited case with no photograph shows none, rather than borrowing
         another client's picture. */ ?>
<?php if ( ! empty( $c['photo'] ) || ! empty( $c['_img'] ) ) : ?>
        <div class="case__photo"><?= rf_img( $c['photo'] ?? null, (string) ( $c['_img'] ?? '' ), (string) ( $c['photo_alt'] ?? '' ), 1200, 580 ) ?></div>
<?php endif; ?>
        <div class="case__head">
          <h3 class="case__name"><?= wp_kses_post( $c['name'] ?? '' ) ?></h3>
          <p class="case__meta"><?php foreach ( $meta as $m ) : ?><span><?= wp_kses_post( $m ) ?></span><?php endforeach; ?></p>
        </div>
        <div class="case__body">
<?php if ( '' !== $metric ) : ?>
          <p class="case__metric"><b><?= wp_kses_post( $metric ) ?></b><span><?= wp_kses_post( $c['metric_label'] ?? '' ) ?></span></p>
<?php else : ?>
          <p class="case__metric case__metric--pending"><b>&mdash;</b><span>Figure with the client for sign-off</span></p>
<?php endif; ?>
          <p class="case__text"><?= wp_kses_post( $c['text'] ?? '' ) ?></p>
          <a class="link-quiet link-quiet--bold" href="<?= esc_url( $ch ) ?>"><?= wp_kses_post( $cl ) ?></a>
        </div>
      </article>
<?php endforeach; ?>
    </div>

    <p class="stories__more">
      <a class="link-quiet link-quiet--onforest" href="<?= esc_url( $more_href ) ?>"><?= wp_kses_post( $more ) ?></a>
    </p>
  </div>
</section>
