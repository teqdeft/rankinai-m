<?php
/**
 * Book a 20-minute call, the softer of the two conversion endpoints. The
 * markup is the flat build's call.php, with every piece of content read from
 * the page's fields (inc/models/page-call.php).
 *
 * The email address, phone number, hours and offices stay site-wide settings
 * in $SITE. The fields carry {email}, {phone}, {hours} and {offices} where the
 * copy prints them, and this template fills them in.
 */
global $SITE, $NAV, $FOOTER;
$D = rankinai_model_data();
get_header();

$hero   = $D['hero'] ?? array();
$booker = $D['booker'] ?? array();
$cover  = $D['cover'] ?? array();
$who    = $D['who'] ?? array();
$faq    = $D['faq'] ?? array();

$call_fill = function ( $s ) use ( $SITE ) {
	$email = e( $SITE['email'] ?? '' );
	$phone = e( $SITE['phone'] ?? '' );
	return strtr( (string) $s, array(
		'{email}'   => '<a href="mailto:' . $email . '">' . $email . '</a>',
		'{phone}'   => '<a href="tel:' . e( preg_replace( '/\s+/', '', $SITE['phone'] ?? '' ) ) . '">' . $phone . '</a>',
		'{hours}'   => e( $SITE['hours'] ?? '' ),
		'{offices}' => e( $SITE['offices'] ?? '' ),
	) );
};
?>

<section class="hero hero--page hero--audit">
  <div class="hero__inner container audit-lede">

    <div class="audit-lede__copy">

<?php if ( ! empty( $hero['title'] ) ) : ?>
      <h1 class="hero__title hero__title--audit"><?= $hero['title'] ?></h1>
<?php endif; ?>

<?php if ( ! empty( $hero['sub'] ) ) : ?>
      <p class="hero__sub audit-lede__sub"><?= $hero['sub'] ?></p>
<?php endif; ?>
    </div>

<?php if ( ! empty( $hero['ticks'] ) ) : ?>
    <ul class="ticks" role="list">
<?php foreach ( $hero['ticks'] as $tick ) : ?>
      <li><?= $tick ?></li>
<?php endforeach; ?>
    </ul>
<?php endif; ?>

    <div class="booker" id="book">
<?php if ( ! empty( $booker['label'] ) ) : ?>
      <p class="label"><?= $booker['label'] ?></p>
<?php endif; ?>

      <!-- Scheduler GOES HERE.
           Drop the Cal.com or Calendly embed inside this div and delete the
           placeholder. Everything below it is the fallback and should stay —
           embeds are blocked by more corporate networks than people expect,
           and this page is where that costs the most. -->
      <div class="booker__embed" data-scheduler>
<?php if ( ! empty( $booker['embed'] ) ) : ?>
        <p class="calendar__note"><?= $booker['embed'] ?></p>
<?php endif; ?>
      </div>

<?php if ( ! empty( $booker['alt_title'] ) || ! empty( $booker['alt_text'] ) ) : ?>
      <div class="booker__alt">
<?php if ( ! empty( $booker['alt_title'] ) ) : ?>
        <p class="booker__alt-title"><?= $booker['alt_title'] ?></p>
<?php endif; ?>
<?php if ( ! empty( $booker['alt_text'] ) ) : ?>
        <p class="booker__alt-text"><?= $call_fill( $booker['alt_text'] ) ?></p>
<?php endif; ?>
      </div>
<?php endif; ?>

<?php if ( ! empty( $booker['note'] ) ) : ?>
      <p class="auditform__note"><?= $call_fill( $booker['note'] ) ?></p>
<?php endif; ?>
    </div>

  </div>

<?php if ( ! empty( $hero['proof'] ) ) : ?>
  <div class="hero__proof">
<?php foreach ( $hero['proof'] as $stat ) : ?>
    <div class="stat">
      <span class="stat__n"><?= $stat[0] ?></span>
      <span class="stat__k"><?= $stat[1] ?></span>
    </div>
<?php endforeach; ?>
  </div>
<?php endif; ?>
</section>


<?php if ( ! empty( $cover ) ) : ?>
<section class="band band--forest band--tuck">
  <div class="container">

<?php if ( ! empty( $cover['title'] ) || ! empty( $cover['note'] ) ) : ?>
    <div class="stories__head">
      <div>
<?php if ( ! empty( $cover['title'] ) ) : ?>
        <h2 class="stories__title"><?= $cover['title'] ?></h2>
<?php endif; ?>
<?php if ( ! empty( $cover['note'] ) ) : ?>
        <p class="packages__note"><?= $cover['note'] ?></p>
<?php endif; ?>
      </div>
    </div>
<?php endif; ?>

<?php if ( ! empty( $cover['terms'] ) ) : ?>
    <div class="terms__grid">
<?php foreach ( $cover['terms'] as $term ) : ?>
      <div class="term">
        <p class="term__name"><?= $term[0] ?></p>
        <p class="term__text"><?= $term[1] ?></p>
      </div>
<?php endforeach; ?>
    </div>
<?php endif; ?>

  </div>
</section>
<?php endif; ?>


<?php if ( ! empty( $who ) ) : ?>
<section class="band band--light">
  <div class="container">

<?php if ( ! empty( $who['title'] ) ) : ?>
    <div class="questions__head">
      <div>
        <h2 class="questions__title"><?= $who['title'] ?></h2>
      </div>
    </div>
<?php endif; ?>

    <div class="who">
      <div class="who__photo case__photo">
<?php if ( ! empty( $who['photo'] ) ) : ?>
        <span><?= $who['photo'] ?></span>
<?php endif; ?>
      </div>

      <div class="who__copy">
<?php if ( ! empty( $who['lead'] ) ) : ?>
        <p class="who__lead"><?= $who['lead'] ?></p>
<?php endif; ?>

<?php if ( ! empty( $who['text'] ) ) : ?>
        <p class="who__text"><?= $who['text'] ?></p>
<?php endif; ?>

<?php if ( ! empty( $who['facts'] ) ) : ?>
        <div class="who__facts">
<?php foreach ( $who['facts'] as $fact ) : ?>
          <p class="who__fact"><span class="label"><?= $fact[0] ?></span><b><?= $call_fill( $fact[1] ) ?></b></p>
<?php endforeach; ?>
        </div>
<?php endif; ?>
      </div>
    </div>

  </div>
</section>
<?php endif; ?>


<?php if ( ! empty( $faq['questions'] ) ) : ?>
<section class="questions">
  <div class="container">
    <div class="questions__head">
      <div>
<?php if ( ! empty( $faq['title'] ) ) : ?>
        <h2 class="questions__title"><?= $faq['title'] ?></h2>
<?php endif; ?>
      </div>
<?php if ( ! empty( $faq['more'][0] ) && ! empty( $faq['more'][1] ) ) : ?>
      <a class="link-quiet link-quiet--bold" href="<?= url( $faq['more'][1] ) ?>"><?= $faq['more'][0] ?></a>
<?php endif; ?>
    </div>

    <div class="questions__body">
      <div class="fqs">
<?php foreach ( $faq['questions'] as $i => $qa ) : ?>
        <button class="fq" type="button" data-fq="<?= $i ?>" aria-selected="<?= 0 === $i ? 'true' : 'false' ?>">
          <span class="fq__n"><?= sprintf( '%02d', $i + 1 ) ?></span>
          <span class="fq__q"><?= $qa['q'] ?? '' ?></span>
        </button>
<?php endforeach; ?>
      </div>
      <div class="answers">
<?php foreach ( $faq['questions'] as $i => $qa ) : ?>
        <div class="answer" data-fa="<?= $i ?>"><?php if ( ! empty( $qa['fact'] ) ) : ?><p class="answer__fact"><span class="label"><?= $qa['fact'][0] ?></span><b><?= $qa['fact'][1] ?></b></p><?php endif; ?><?php foreach ( $qa['a'] ?? array() as $para ) : ?><p><?= $para ?></p><?php endforeach; ?></div>
<?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
