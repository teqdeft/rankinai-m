<?php
/**
 * Success story template
 * =============================================================================
 * Rebuilt 22 Sep 2026 to Kulwant's section order:
 *
 *   01  HERO            logo + client name, then the headline. No subhead, no
 *                       audit button. The hero's job is to say who this is
 *                       about and what happened, nothing else.
 *   02  PROJECT OVERVIEW  one-paragraph answer, then a facts table
 *   03  PROBLEM STATEMENT
 *   04  SOLUTIONS AND PROCESS
 *   05  RESULTS
 *   06  REVIEW
 *   07  ANOTHER SUCCESS STORY (one, full width)
 *   08  TEAM
 *   09  CTA
 *
 * WRITTEN FOR ANSWER ENGINES AS WELL AS PEOPLE
 * An assistant asked "what did RankinAI do for X" should be able to lift the
 * answer without guessing. So:
 *  · section headings are plain and literal, not clever
 *  · 02 opens with a single self-contained paragraph that answers the question
 *    on its own, with the client, the sector, the work and the outcome in it
 *  · the facts sit in a real <dl>, which parses cleanly
 *  · results are a <table> with a figure, a period and a source on every row
 *  · JSON-LD at the foot of the page describes the article and the client
 * None of that changes what is claimed. The honesty rules below still hold.
 *
 * TWO SLOTS ARE DELIBERATELY WITHHOLDABLE
 *   metrics[n][0] => null   renders the awaiting-sign-off state, not a figure
 *   'quote'       => null   renders a marked slot, not a testimonial
 * A fabricated testimonial attributed to a named person at a real company is a
 * false statement about that person. Do not fill that slot with anything except
 * words the client actually said and signed off. See CLAIMS.md.
 *
 * WordPress: single-story.php with a `story` post type.
 *
 * REQUIRED KEYS
 *   label, h1, client, meta[], services[]
 *   logo                      optional; falls back to the client name set as a
 *                             wordmark. No client logos exist yet.
 *   heroPhoto, heroPhotoAlt   optional; the wide photo under the headline
 *   overview                  one self-contained paragraph, the AEO answer
 *   facts[]                   [label, value] rows for the <dl>
 *   problemHead, situation[], found[]
 *   movesHead[2], moves[]
 *   resultHead[], metrics[]   [figure|null, what it measures, period, source]
 *   quote | null              [text, name, role]
 *   more{}                    one story: name, meta[], metric|null, metricKey,\n *                             text, photoSrc, photoAlt, href
 *   closeText
 * =============================================================================
 */
/* WORDPRESS: ported 29 Sep 2026 from the flat build's includes/story-template.php.
   The array comes from the post's fields (inc/models/story.php). */
global $SITE, $NAV, $FOOTER;
$STORY = rankinai_model_data();
if (!isset($STORY)) {
    exit('story-template.php requires $STORY.');
}
$S = $STORY;

/* NEW SHAPE, 25 Sep 2026, to Kulwant's structure. A story that sets 'business'
   gets it; one that does not keeps the 22 Sep shape. Every section below is
   guarded on one or the other, so a story file can move over when its copy is
   ready rather than all at once.

   NEW KEYS
     label, meta[], h1, sub, lead, client
     headline[]   [from|null, now|null, key]  — null 'now' renders pending
     headlineNote
     business   [eyebrow, title, paras[]]
     challenge  [eyebrow, title, paras[], quotes[], tail]
     found      [eyebrow, title, lead, items[[name, paras[]]]]
     strategy   [eyebrow, title, paras[], listLead, list[], tail]
     changed    [eyebrow, steps[[title, paras[], ba[[label,text],[label,text]]]]]
     delivery   [eyebrow, title, phases[[label, text]]]
     results    [eyebrow, title, paras[], head[], rows[], notes[[name,text]], tail]
     closing    [eyebrow, title, paras[]]
   facts, services, quote and more are shared by both shapes. */
$newStory = !empty($S['business']);

get_header();
?>

<article class="story-detail">

<?php if ($newStory): ?>

<!-- 01 — HERO ============================================================
     The eyebrow carries the sector and market, so a reader landing from a
     search knows in one line whose story this is and where. Then the claim,
     the sentence that explains it, and the figures.

     A figure with no published number renders its pending state rather than
     dropping out, because the shape of what is being measured is itself
     useful and a missing card would read as an oversight.
     ====================================================================== -->
<section class="hero hero--centred hero--story">
  <div class="hero__inner container">

    <p class="eyebrow"><span><?= implode(' &middot; ', array_merge([$S['label']], $S['meta'])) ?></span></p>

    <h1 class="hero__title"><?= $S['h1'] ?></h1>

    <div class="hero__meta hero__meta--home">
      <p class="hero__sub"><?= $S['sub'] ?><?php if (!empty($S['lead'])): ?><span class="hero__sub2"><?= $S['lead'] ?></span><?php endif; ?></p>
    </div>

<?php if (!empty($S['headline'])): ?>
    <p class="storyclient"><?= $S['client'] ?></p>

    <div class="figs">
<?php foreach ($S['headline'] as [$from, $now, $key]): ?>
      <figure class="fig3<?= $now === null ? ' fig3--pending' : '' ?>">
<?php if ($from !== null && $now !== null): ?>
        <p class="fig3__move"><span class="fig3__was"><?= $from ?></span><span class="fig3__arrow" aria-hidden="true">&rarr;</span><span class="fig3__vis">rising to</span></p>
<?php else: ?>
        <p class="fig3__move fig3__move--flat"><?= $now === null ? 'with the client' : 'measured' ?></p>
<?php endif; ?>
        <p class="fig3__n"><?= $now === null ? '&mdash;' : $now ?></p>
        <p class="fig3__text"><?= $key ?></p>
      </figure>
<?php endforeach; ?>
    </div>

<?php if (!empty($S['headlineNote'])): ?>
    <p class="storynote"><?= $S['headlineNote'] ?></p>
<?php endif; ?>
<?php endif; ?>

  </div>

<?php if (!empty($S['heroPhoto'])): ?>
  <figure class="storyhero">
    <div class="container">
      <img src="<?= rankinai_img_url($S['heroPhoto']) ?>" alt="<?= e($S['heroPhotoAlt'] ?? '') ?>" width="1200" height="580" fetchpriority="high" decoding="async">
    </div>
  </figure>
<?php endif; ?>
</section>


<!-- 02 — THE BUSINESS ====================================================
     The self-contained paragraph an assistant can lift, then the facts as a
     real <dl> so they parse cleanly.
     ====================================================================== -->
<section class="band band--light" id="overview">
  <div class="container">

    <div class="questions__head">
      <div>
        <p class="eyebrow"><span><?= $S['business']['eyebrow'] ?></span></p>
        <h2 class="questions__title"><?= $S['business']['title'] ?></h2>
      </div>
    </div>

    <div class="prose">
<?php foreach ($S['business']['paras'] as $i => $para): ?>
      <p<?= $i === 0 ? ' class="prose__lead"' : '' ?>><?= $para ?></p>
<?php endforeach; ?>
    </div>

    <dl class="facts facts--after">
<?php foreach ($S['facts'] as [$k, $v]): ?>
      <div class="facts__row">
        <dt><?= $k ?></dt>
        <dd><?= $v ?></dd>
      </div>
<?php endforeach; ?>
      <div class="facts__row">
        <dt>Services used</dt>
        <dd>
          <ul class="tags" role="list">
<?php foreach ($S['services'] as [$name, $href]): ?>
            <li><a href="<?= url($href) ?>"><?= $name ?></a></li>
<?php endforeach; ?>
          </ul>
        </dd>
      </div>
    </dl>

  </div>
</section>


<!-- 03 — THE CHALLENGE =================================================== -->
<?php if (!empty($S['challenge'])): ?>
<section class="band band--forest" id="problem">
  <div class="container">

    <div class="stories__head">
      <div>
        <p class="eyebrow eyebrow--light"><span><?= $S['challenge']['eyebrow'] ?></span></p>
        <h2 class="stories__title"><?= $S['challenge']['title'] ?></h2>
      </div>
    </div>

    <div class="prose">
<?php foreach ($S['challenge']['paras'] as $i => $para): ?>
      <p<?= $i === 0 ? ' class="prose__lead"' : '' ?>><?= $para ?></p>
<?php endforeach; ?>
    </div>

<?php if (!empty($S['challenge']['quotes'])): ?>
    <ol class="qset qset--wide" role="list">
<?php foreach ($S['challenge']['quotes'] as $qi => $q): ?>
      <li><span class="qset__n"><?= sprintf('%02d', $qi + 1) ?></span><span class="qset__q"><?= $q ?></span></li>
<?php endforeach; ?>
    </ol>
<?php endif; ?>

<?php if (!empty($S['challenge']['tail'])): ?>
    <p class="aftercards aftercards--onforest"><?= $S['challenge']['tail'] ?></p>
<?php endif; ?>

  </div>
</section>
<?php endif; ?>


<!-- 04 — WHAT WE FOUND ================================================== -->
<?php if (!empty($S['found']) && isset($S['found']['items'])): ?>
<section class="band band--light" id="found">
  <div class="container">

    <div class="questions__head">
      <div>
        <p class="eyebrow"><span><?= $S['found']['eyebrow'] ?></span></p>
        <h2 class="questions__title"><?= $S['found']['title'] ?></h2>
      </div>
    </div>

<?php if (!empty($S['found']['lead'])): ?>
    <div class="prose">
      <p class="prose__lead"><?= $S['found']['lead'] ?></p>
    </div>
<?php endif; ?>

    <ol class="flow" role="list">
<?php foreach ($S['found']['items'] as $fi => [$name, $paras]): ?>
      <li class="flow__item">
        <span class="flow__n"><?= sprintf('%02d', $fi + 1) ?></span>
        <div class="flow__body">
          <h3 class="flow__name"><?= $name ?></h3>
<?php foreach ((array) $paras as $para): ?>
          <p class="flow__text"><?= $para ?></p>
<?php endforeach; ?>
        </div>
      </li>
<?php endforeach; ?>
    </ol>

  </div>
</section>
<?php endif; ?>


<!-- 05 — THE STRATEGY =================================================== -->
<?php if (!empty($S['strategy'])): ?>
<section class="band band--forest" id="strategy">
  <div class="container">

    <div class="stories__head">
      <div>
        <p class="eyebrow eyebrow--light"><span><?= $S['strategy']['eyebrow'] ?></span></p>
        <h2 class="stories__title"><?= $S['strategy']['title'] ?></h2>
      </div>
    </div>

    <div class="prose">
<?php foreach ($S['strategy']['paras'] as $i => $para): ?>
      <p<?= $i === 0 ? ' class="prose__lead"' : '' ?>><?= $para ?></p>
<?php endforeach; ?>
    </div>

<?php if (!empty($S['strategy']['list'])): ?>
    <div class="needs">
      <p class="label label--clay"><?= $S['strategy']['listLead'] ?></p>
      <ol class="needs__list<?= count($S['strategy']['list']) === 4 ? ' needs__list--four' : '' ?>" role="list">
<?php foreach ($S['strategy']['list'] as $li => $item): ?>
        <li><span class="needs__n"><?= sprintf('%02d', $li + 1) ?></span><span class="needs__t"><?= $item ?></span></li>
<?php endforeach; ?>
      </ol>
    </div>
<?php endif; ?>

<?php if (!empty($S['strategy']['tail'])): ?>
    <p class="aftercards aftercards--onforest"><?= $S['strategy']['tail'] ?></p>
<?php endif; ?>

  </div>
</section>
<?php endif; ?>


<!-- 06 — WHAT WE CHANGED ================================================
     Four moves, each with its own heading, so a long section reads as four
     short ones. The before-and-after pair is the only place on the site where
     we print a client's old copy beside the new, and it needs the client's
     agreement like anything else on the page.
     ====================================================================== -->
<?php if (!empty($S['changed'])): ?>
<section class="band band--light" id="changed">
  <div class="container">

    <div class="questions__head">
      <div>
        <p class="eyebrow"><span><?= $S['changed']['eyebrow'] ?></span></p>
        <h2 class="questions__title"><?= $S['changed']['title'] ?></h2>
      </div>
    </div>

    <div class="moveset">
<?php foreach ($S['changed']['steps'] as $si => $step): ?>
      <section class="moveset__item">
        <p class="label label--clay"><?= sprintf('%02d', $si + 1) ?></p>
        <h3 class="moveset__name"><?= $step['title'] ?></h3>
        <div class="moveset__body">
<?php foreach ($step['paras'] as $para): ?>
          <p><?= $para ?></p>
<?php endforeach; ?>
        </div>
<?php if (!empty($step['ba'])): ?>
        <div class="ba">
<?php foreach ($step['ba'] as [$baLabel, $baText]): ?>
          <figure class="ba__item">
            <figcaption class="label label--clay"><?= $baLabel ?></figcaption>
            <blockquote class="ba__q"><p><?= $baText ?></p></blockquote>
          </figure>
<?php endforeach; ?>
        </div>
<?php endif; ?>
      </section>
<?php endforeach; ?>
    </div>

  </div>
</section>
<?php endif; ?>


<!-- 07 — THE DELIVERY =================================================== -->
<?php if (!empty($S['delivery'])): ?>
<section class="band band--forest" id="delivery">
  <div class="container">

    <div class="stories__head">
      <div>
        <p class="eyebrow eyebrow--light"><span><?= $S['delivery']['eyebrow'] ?></span></p>
        <h2 class="stories__title"><?= $S['delivery']['title'] ?></h2>
      </div>
    </div>

    <div class="steps4">
<?php foreach ($S['delivery']['phases'] as [$when, $name, $text]): ?>
      <article class="step4">
        <p class="label label--clay"><?= $when ?></p>
        <h3 class="step4__name"><?= $name ?></h3>
        <p class="step4__text"><?= $text ?></p>
      </article>
<?php endforeach; ?>
    </div>

<?php if (!empty($S['delivery']['tail'])): ?>
    <p class="aftercards aftercards--onforest"><?= $S['delivery']['tail'] ?></p>
<?php endif; ?>

  </div>
</section>
<?php endif; ?>


<!-- 08 — THE RESULTS ====================================================
     The comparison table only renders when there are real rows to put in it.
     A table of illustrative numbers on a page that names a real client is the
     single most damaging thing this site could publish, so the slot shows what
     is being measured and says the figures are with the client instead.
     See CLAIMS.md.
     ====================================================================== -->
<?php if (!empty($S['results'])): ?>
<section class="band band--light" id="results">
  <div class="container">

    <div class="questions__head">
      <div>
        <p class="eyebrow"><span><?= $S['results']['eyebrow'] ?></span></p>
        <h2 class="questions__title"><?= $S['results']['title'] ?></h2>
      </div>
    </div>

<?php if (!empty($S['results']['paras'])): ?>
    <div class="prose">
<?php foreach ($S['results']['paras'] as $i => $para): ?>
      <p<?= $i === 0 ? ' class="prose__lead"' : '' ?>><?= $para ?></p>
<?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (!empty($S['results']['rows'])): ?>
    <div class="ctable-wrap">
      <table class="ctable">
        <thead>
          <tr>
<?php foreach ($S['results']['head'] as $hi => $h): ?>
            <th scope="col"<?= $hi ? ' class="ctable__num"' : '' ?>><?= $h ?></th>
<?php endforeach; ?>
          </tr>
        </thead>
        <tbody>
<?php foreach ($S['results']['rows'] as $row): ?>
          <tr>
<?php foreach ($row as $ci => $cell): ?>
<?php if ($ci === 0): ?>
            <th scope="row"><?= $cell ?></th>
<?php else: ?>
            <td class="ctable__num"><?= $cell ?></td>
<?php endif; ?>
<?php endforeach; ?>
          </tr>
<?php endforeach; ?>
        </tbody>
      </table>
    </div>
<?php else: ?>
    <div class="pend pend--light pend--after">
      <p class="pend__t">With the client for sign-off</p>
      <p class="pend__x">The comparison is measured and reported monthly. It appears here once the client releases the figures, with the periods and the source attached.</p>
<?php if (!empty($S['results']['measuring'])): ?>
      <ul class="pend__list" role="list">
<?php foreach ($S['results']['measuring'] as [$k, $src]): ?>
        <li><span class="pend__k"><?= $k ?></span><span class="pend__s"><?= $src ?></span></li>
<?php endforeach; ?>
      </ul>
<?php endif; ?>
    </div>
<?php endif; ?>

<?php if (!empty($S['results']['notes'])): ?>
    <div class="hows hows--two">
<?php foreach ($S['results']['notes'] as [$n, $t]): ?>
      <div class="how">
        <p class="how__name"><?= $n ?></p>
        <p class="how__text"><?= $t ?></p>
      </div>
<?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (!empty($S['results']['tail'])): ?>
    <p class="aftercards"><?= $S['results']['tail'] ?></p>
<?php endif; ?>

  </div>
</section>
<?php endif; ?>


<!-- 09 — THE CLIENT'S PERSPECTIVE ======================================= -->
<section class="band band--forest" id="review">
  <div class="container">

    <div class="stories__head">
      <div>
        <p class="eyebrow eyebrow--light"><span>The client&rsquo;s perspective</span></p>
        <h2 class="stories__title">What felt different inside the business?</h2>
      </div>
    </div>

<?php if (!empty($S['quote'])): ?>
    <figure class="review review--onforest">
      <span class="review__mark" aria-hidden="true">&ldquo;</span>
      <blockquote class="review__q"><p><?= $S['quote'][0] ?></p></blockquote>
      <figcaption class="review__by">
        <span class="review__rule" aria-hidden="true"></span>
        <b class="review__name"><?= $S['quote'][1] ?></b>
        <span class="review__role"><?= $S['quote'][2] ?></span>
      </figcaption>
    </figure>
<?php else: ?>
    <p class="aftercards aftercards--onforest">We do not write a client&rsquo;s words for them. When <?= $S['client'] ?> send something they are happy to put their name to, it appears here.</p>
<?php endif; ?>

  </div>
</section>


<!-- 10 — WHAT THIS PROJECT SHOWS ======================================== -->
<?php if (!empty($S['closing'])): ?>
<section class="band band--light" id="shows">
  <div class="container">

    <div class="questions__head">
      <div>
        <p class="eyebrow"><span><?= $S['closing']['eyebrow'] ?></span></p>
        <h2 class="questions__title"><?= $S['closing']['title'] ?></h2>
      </div>
    </div>

    <div class="prose">
<?php foreach ($S['closing']['paras'] as $i => $para): ?>
      <p<?= $i === 0 ? ' class="prose__lead"' : '' ?>><?= $para ?></p>
<?php endforeach; ?>
    </div>

  </div>
</section>
<?php endif; ?>

<?php else: ?>

<!-- 01 — HERO ============================================================
     Logo where we have one, the client name set as a wordmark where we do
     not. The name is a <p> rather than a heading so the h1 below it stays
     the page's only first-level heading.
     ====================================================================== -->
<section class="hero hero--page hero--story">
  <div class="hero__inner container">

    <div class="storyid">
<?php if (!empty($S['logo'])): ?>
      <img class="storyid__logo" src="<?= rankinai_img_url($S['logo']) ?>" alt="<?= e($S['client']) ?>" height="48" loading="eager" decoding="async">
<?php else: ?>
      <p class="storyid__name"><?= $S['client'] ?></p>
<?php endif; ?>
    </div>

    <h1 class="hero__title hero__title--page"><?= $S['h1'] ?></h1>
  </div>

<?php /* Full container width, not full viewport bleed: the case photos are
         1200px wide and a true bleed would upscale them past the point where
         they hold up. This keeps them sharp and still reads as the big
         picture the page opens on. */ ?>
<?php if (!empty($S['heroPhoto'])): ?>
  <figure class="storyhero">
    <div class="container">
      <img src="<?= rankinai_img_url($S['heroPhoto']) ?>" alt="<?= e($S['heroPhotoAlt'] ?? '') ?>" width="1200" height="580" fetchpriority="high" decoding="async">
    </div>
  </figure>
<?php endif; ?>
</section>


<!-- 02 — PROJECT OVERVIEW ================================================
     The paragraph is written to stand on its own if it is lifted out of the
     page. Client, sector, what we did and what happened, in one place.
     ====================================================================== -->
<section class="band band--light band--tuck" id="overview">
  <div class="container">
    <div class="questions__head">
      <div>
        <h2 class="questions__title">Project overview</h2>
      </div>
<?php /* The heading carries this section on its own. A note only appears if a
         story sets 'overviewNote' explicitly. */ ?>
<?php if (!empty($S['overviewNote'])): ?>
      <p class="stories__note"><?= $S['overviewNote'] ?></p>
<?php endif; ?>
    </div>

    <div class="overview">
      <p class="overview__lead"><?= $S['overview'] ?></p>

      <dl class="facts">
<?php foreach ($S['facts'] as [$k, $v]): ?>
        <div class="facts__row">
          <dt><?= $k ?></dt>
          <dd><?= $v ?></dd>
        </div>
<?php endforeach; ?>
        <div class="facts__row">
          <dt>Services used</dt>
          <dd>
            <ul class="tags" role="list">
<?php foreach ($S['services'] as [$name, $href]): ?>
              <li><a href="<?= url($href) ?>"><?= $name ?></a></li>
<?php endforeach; ?>
            </ul>
          </dd>
        </div>
      </dl>
    </div>
  </div>
</section>


<!-- 03 — PROBLEM STATEMENT =============================================== -->
<section class="band band--forest" id="problem">
  <div class="container">
    <div class="stories__head">
      <div>
        <h2 class="stories__title"><?= $S['problemHead'] ?></h2>
      </div>
    </div>

    <div class="situation">
      <div class="situation__copy">
<?php foreach ($S['situation'] as $para): ?>
        <p class="situation__p"><?= $para ?></p>
<?php endforeach; ?>
      </div>

      <div class="situation__found">
        <p class="label label--clay">What the audit found</p>
        <ul class="ticks ticks--no ticks--onforest" role="list">
<?php foreach ($S['found'] as $f): ?>
          <li><?= $f ?></li>
<?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
</section>


<!-- 04 — SOLUTIONS AND PROCESS =========================================== -->
<section class="band band--light" id="process">
  <div class="container">
    <div class="questions__head">
      <div>
        <h2 class="questions__title"><?= $S['movesHead'][0] ?></h2>
      </div>
      <p class="stories__note"><?= $S['movesHead'][1] ?></p>
    </div>

    <ol class="flow" role="list">
<?php foreach ($S['moves'] as $i => [$name, $text]): ?>
      <li class="flow__item">
        <span class="flow__n"><?= sprintf('%02d', $i + 1) ?></span>
        <div class="flow__body">
          <h3 class="flow__name"><?= $name ?></h3>
          <p class="flow__text"><?= $text ?></p>
        </div>
      </li>
<?php endforeach; ?>
    </ol>
  </div>
</section>


<!-- 05 — RESULTS ========================================================
     Figures set large on forest, each with the period it covers and where it
     was measured. This is the payoff section, which is why it takes the dark
     band rather than a third light one in a row.

     A metric with null in the first slot drops out of the grid and into the
     .pend list underneath instead, for figures that are being tracked but
     are not ready to publish. See CLAIMS.md.
     ====================================================================== -->
<?php
$published = array_values(array_filter($S['metrics'], function ($m) { return $m[0] !== null; }));
$pending    = array_values(array_filter($S['metrics'], function ($m) { return $m[0] === null; }));
?>
<section class="band band--forest" id="results">
  <div class="container">
    <div class="stories__head">
      <div>
        <h2 class="stories__title"><?= $S['resultHead'][0] ?></h2>
        <p class="packages__note">Every figure below carries the period it covers and where it was measured.</p>
      </div>
    </div>

<?php if ($published): ?>
    <div class="results results--<?= count($published) ?>">
<?php foreach ($published as [$fig, $key, $period, $source]): ?>
      <div class="result">
        <b class="result__n"><?= $fig ?></b>
        <span class="result__k"><?= $key ?><?= $period ? ', ' . strtolower($period) : '' ?></span>
        <span class="result__sub"><?= $source ?></span>
      </div>
<?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if ($pending): ?>
    <div class="pend<?= $published ? ' pend--after' : '' ?>">
      <p class="pend__t">Also being measured</p>
      <p class="pend__x">Reported monthly, and added here once the period is long enough to mean anything.</p>
      <ul class="pend__list" role="list">
<?php foreach ($pending as [$fig, $key, $period, $source]): ?>
        <li><span class="pend__k"><?= $key ?></span><span class="pend__s"><?= $source ?></span></li>
<?php endforeach; ?>
      </ul>
    </div>
<?php endif; ?>
  </div>
</section>


<!-- 06 — REVIEW =========================================================
     'quote' => null renders the empty state. Do NOT fill that slot with a
     plausible sentence and a plausible name: a testimonial invented on a
     client's behalf is a false statement about a real person. See CLAIMS.md.

     'quote' => ['text', 'Name', 'Role, Company'] renders the card.

     The quote currently on the Pine Tree Lane page is layout copy. These are
     HTML pages, not the live site: the real words, name and role come in
     through the CMS. Nothing here goes live without the client's written
     sign-off. See CLAIMS.md.
     ====================================================================== -->
<section class="band band--light" id="review">
  <div class="container">
    <div class="questions__head">
      <div>
        <h2 class="questions__title">What the client said</h2>
      </div>
    </div>

<?php if (!empty($S['quote'])): ?>
    <figure class="review">
      <span class="review__mark" aria-hidden="true">&ldquo;</span>
      <blockquote class="review__q"><p><?= $S['quote'][0] ?></p></blockquote>
      <figcaption class="review__by">
        <span class="review__rule" aria-hidden="true"></span>
        <b class="review__name"><?= $S['quote'][1] ?></b>
        <span class="review__role"><?= $S['quote'][2] ?></span>
      </figcaption>
    </figure>
<?php else: ?>
    <div class="notthis">
      <p class="notthis__text">Coming shortly.</p>
    </div>
<?php endif; ?>
  </div>
</section>


<?php endif; /* old-shape sections */ ?>


<!-- 11 — ANOTHER SUCCESS STORY ==========================================
     Was two narrow cards, which read as a thin afterthought. Now one story
     at full width, using the same .story block the service and industry
     pages use, so a reader who has seen it elsewhere recognises it.

     One, not a grid: at the bottom of a long page the useful thing is a
     single clear next read, not a choice. The link to all stories is in the
     head for anyone who wants the rest.
     ====================================================================== -->
<section class="band band--light" id="more">
  <div class="container">
    <div class="questions__head">
      <div>
        <h2 class="questions__title">Another success story</h2>
      </div>
      <a class="link-quiet link-quiet--bold" href="<?= url('/success-stories/') ?>">All success stories</a>
    </div>

    <article class="story storylist">
      <div class="story__copy">
        <div class="story__head">
          <div>
            <h3 class="story__name"><?= $S['more']['name'] ?></h3>
            <p class="story__where"><?php foreach ($S['more']['meta'] as $m): ?><span class="label"><?= $m ?></span><?php endforeach; ?></p>
          </div>
        </div>
<?php /* Same rule as everywhere else: a figure only appears once the client
         has signed it off. See CLAIMS.md. */ ?>
<?php if (!empty($S['more']['metric'])): ?>
        <div class="metric">
          <b class="metric__n"><?= $S['more']['metric'] ?></b>
          <span class="metric__k"><?= $S['more']['metricKey'] ?></span>
        </div>
<?php else: ?>
        <div class="metric metric--pending">
          <b class="metric__n">&mdash;</b>
          <span class="metric__k">Figure with the client for sign-off</span>
        </div>
<?php endif; ?>
        <p class="story__text"><?= $S['more']['text'] ?></p>
        <a class="link-quiet link-quiet--bold" href="<?= url($S['more']['href']) ?>">Read the <?= $S['more']['name'] ?> story</a>
      </div>
      <div class="story__photo case__photo">
        <img src="<?= rankinai_img_url($S['more']['photoSrc']) ?>" alt="<?= e($S['more']['photoAlt']) ?>" width="1200" height="580" loading="lazy" decoding="async">
      </div>
    </article>
  </div>
</section>


<!-- 12 — TEAM ============================================================
     Old shape only. The new structure ends on the close, like every other
     rebuilt page on the site. -->
<?php if (!$newStory) { get_template_part( 'template-parts/team' ); } ?>


<!-- 09 — CTA ============================================================= -->

</article>

<?php
/* Structured data. Describes the page and the client, so an assistant
   summarising this story has the facts rather than a guess. Nothing in here
   is asserted that is not already visible on the page, and no aggregate
   rating is emitted, because we do not have one. */
$ld = [
    '@context' => 'https://schema.org',
    '@type'    => 'Article',
    'headline' => html_entity_decode(strip_tags(str_replace('<br>', ' ', $S['h1'])), ENT_QUOTES, 'UTF-8'),
    'about'    => array_filter([
        '@type' => 'Organization',
        'name'  => html_entity_decode(strip_tags($S['client']), ENT_QUOTES, 'UTF-8'),
    ]),
    'abstract' => html_entity_decode(strip_tags(
        $newStory ? implode(' ', array_slice($S['business']['paras'], 0, 2)) : $S['overview']
    ), ENT_QUOTES, 'UTF-8'),
    'author'   => ['@type' => 'Organization', 'name' => 'RankinAI'],
    'publisher' => ['@type' => 'Organization', 'name' => 'RankinAI'],
];
if (!empty($S['quote'])) {
    $ld['citation'] = html_entity_decode(strip_tags($S['quote'][0]), ENT_QUOTES, 'UTF-8');
}
?>
<script type="application/ld+json"><?= json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>

<?php get_footer(); ?>
