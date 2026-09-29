<?php
/**
 * Section: how we start, four steps. The flat build's index.php section 05.
 *
 * The four drawings (the questions, the sweep, the order, the loop) are fixed
 * markup: each is drawn for its step and animated by script.js, so the section
 * always has exactly four steps. Their names and copy are editable. Without
 * script each drawing shows its finished state.
 *
 * The plan panel replaced the old Foundation Fix / Ongoing pair when the
 * pricing page moved to four packages.
 */
$d     = rankinai_section_defaults( 'method' );
$title = rf( 'title', $d['title'] );
$default_steps = $d['steps'];
$steps = rf_rows( 'steps', $default_steps );
// Always four: pad an edited list with the defaults, so each drawing keeps a step.
$steps = array_slice( array_replace( $default_steps, $steps ), 0, 4 );
$marks = array_map( function ( $r ) { return $r['mark'] ?? ''; }, rf_rows( 'marks', $d['marks'] ) );
$plan  = rf( 'plan', $d['plan'] );
list( $b1, $b1h ) = rf_link( rf( 'button', null ), array( $d['button']['title'], $d['button']['url'] ) );
list( $b2, $b2h ) = rf_link( rf( 'button2', null ), array( $d['button2']['title'], $d['button2']['url'] ) );
?>
<section class="method">
  <div class="container">
    <div class="method__head">
      <h2 class="method__title"><?= wp_kses_post( $title ) ?></h2>
    </div>

    <div class="method__body">
      <div class="steps">
<?php foreach ( $steps as $i => $s ) : ?>
        <button class="step" type="button" data-step="<?= (int) $i ?>" aria-selected="<?= 0 === $i ? 'true' : 'false' ?>">
          <span class="step__n"><?= sprintf( '%02d', $i + 1 ) ?></span>
          <span class="step__name"><?= wp_kses_post( $s['name'] ?? '' ) ?></span>
          <span class="step__bar" aria-hidden="true"></span>
        </button>
<?php endforeach; ?>
      </div>

      <div class="method__panel">
        <div class="schematic">
          <div class="sch sch--ask" data-panel="0" data-ask>
            <p class="ask"><span class="ask__q">Which services you want to grow</span><i class="ask__dot"></i><span class="ask__line"></span></p>
            <p class="ask"><span class="ask__q">What makes an enquiry worth pursuing</span><i class="ask__dot"></i><span class="ask__line"></span></p>
            <p class="ask"><span class="ask__q">How you win that work today</span><i class="ask__dot"></i><span class="ask__line"></span></p>
            <p class="ask"><span class="ask__q">How much more you could take on</span><i class="ask__dot"></i><span class="ask__line"></span></p>
          </div>
          <div class="sch sch--sweep" data-panel="1" data-sweep aria-hidden="true">
            <div class="sweep__grid"><i class="is-ok" data-r="ok"></i><i class="is-ok" data-r="ok"></i><i class="is-ok" data-r="ok"></i><i class="is-bad" data-r="bad"></i><i class="is-ok" data-r="ok"></i><i class="is-ok" data-r="ok"></i><i class="is-ok" data-r="ok"></i><i class="is-ok" data-r="ok"></i><i class="is-ok" data-r="ok"></i><i class="is-bad" data-r="bad"></i><i class="is-ok" data-r="ok"></i><i class="is-ok" data-r="ok"></i><i class="is-ok" data-r="ok"></i><i class="is-ok" data-r="ok"></i><i class="is-bad" data-r="bad"></i><i class="is-ok" data-r="ok"></i><i class="is-ok" data-r="ok"></i><i class="is-bad" data-r="bad"></i><i class="is-ok" data-r="ok"></i><i class="is-ok" data-r="ok"></i><i class="is-ok" data-r="ok"></i><i class="is-ok" data-r="ok"></i><i class="is-bad" data-r="bad"></i><i class="is-ok" data-r="ok"></i></div>
            <span class="sweep__line"></span>
          </div>
          <div class="sch sch--order" data-panel="2" data-order>
            <div class="order">
              <span class="order__rail"></span>
              <div class="order__node" style="--h:36px"><i class="order__block"></i><b class="order__dot"></b><span class="order__n">01</span></div>
              <div class="order__node" style="--h:50px"><i class="order__block"></i><b class="order__dot"></b><span class="order__n">02</span></div>
              <div class="order__node" style="--h:62px"><i class="order__block"></i><b class="order__dot"></b><span class="order__n">03</span></div>
              <div class="order__node order__node--clay" style="--h:74px"><i class="order__block"></i><b class="order__dot"></b><span class="order__n">04</span></div>
            </div>
          </div>
          <div class="sch sch--loop" data-panel="3" data-loop>
            <svg class="loop" viewBox="0 0 420 200" aria-hidden="true">
              <circle class="loop__ring" cx="100" cy="100" r="68"/>
              <circle class="loop__arc"  cx="100" cy="100" r="68" transform="rotate(-90 100 100)"/>
              <circle class="loop__node is-on" cx="100.0" cy="32.0" r="5" data-i="0"/>
              <circle class="loop__node" cx="158.9" cy="134.0" r="5" data-i="1"/>
              <circle class="loop__node" cx="41.1" cy="134.0" r="5" data-i="2"/>
              <g class="loop__arm" data-arm><circle class="loop__marker" cx="100.0" cy="32.0" r="7"/></g>
              <text class="loop__label is-on" x="222" y="66"  data-i="0">Run</text>
              <text class="loop__label"       x="222" y="108" data-i="1">Measure</text>
              <text class="loop__label"       x="222" y="150" data-i="2">Revise</text>
              <text class="loop__every" x="222" y="182">every month</text>
            </svg>
          </div>
        </div>

        <div class="method__copy">
<?php foreach ( $steps as $i => $s ) : ?>
          <p class="stepcopy" data-copy="<?= (int) $i ?>"><b><?= wp_kses_post( $s['lead'] ?? '' ) ?></b> <?= wp_kses_post( $s['text'] ?? '' ) ?></p>
<?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="plan">
      <ul class="plan__marks" role="list">
<?php foreach ( array_filter( $marks ) as $m ) : ?>
        <li><?= wp_kses_post( $m ) ?></li>
<?php endforeach; ?>
      </ul>

      <div class="plan__body">
        <?= wp_kses_post( $plan ) ?>
      </div>

      <div class="plan__actions">
        <a class="btn btn--primary" href="<?= esc_url( $b1h ) ?>"><?= wp_kses_post( $b1 ) ?> <?= btn_arrow() ?></a>
        <a class="btn btn--outline" href="<?= esc_url( $b2h ) ?>"><?= wp_kses_post( $b2 ) ?></a>
      </div>
    </div>
  </div>
</section>
