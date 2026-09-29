<?php
/**
 * Industry page template
 * =============================================================================
 * Same arrangement as the service pages: one template, one data file per
 * industry. I checked Interior design studios, Construction companies and
 * Architecture firms — all three carry the same nine sections in the same
 * order, so this is the page and each industry is content.
 *
 *   interior-design.php  ->  $INDUSTRY = [...];  require this file;
 *
 * WordPress: single-industry.php with an `industry` post type.
 *
 * REQUIRED KEYS
 *   label, h1, sub, stats[]
 *   image                          optional; the hero's second column
 *   channelsHead[4]                [title, note, left head, right head]
 *   doing[] / notDoing[]           [icon, name, text] each
 *   impactHead[2], impact[]        [was|'', now, text, source] — source required
 *   modelHead[2], model[]          [op, 'yours'|'ours', label, value, note]
 *                                  the yours/ours flag is not printed any more;
 *                                  it only mutes the illustrative rows
 *   modelResult[3]                 [label, value, note]
 *   midcta[2]
 *   leverHead[2], levers[], leverSum   optional; shelved on every page for now
 *   startHead[2], start[]          [title, note] + [priority, service, text, href]
 *   story | null                   same shape as the service template
 *   qs[], closeText
 *
 * No eyebrows on the content sections. Each one is a short heading with the
 * explanation in the note beside it, which is the pattern the service pages
 * settled on. Icon keys come from svc_icon_svg() in config.php.
 *
 *   monthHead/month, marksHead/marks, whoHead/who, stagesHead/stages,
 *   problemsHead/problems and ctaNote are gone from all nine data files.
 * =============================================================================
 *
 * WORDPRESS: ported 29 Sep 2026 from the flat build's includes/industry-template.php. The array
 * the template reads now comes from the post's ACF fields through
 * rankinai_cpt_data() (inc/content-model.php) instead of a data file. The
 * markup below is the flat build's, unchanged.
 */
global $FOOTER, $SITE, $NAV;
$INDUSTRY = rankinai_cpt_data();
if (!isset($INDUSTRY)) {
    exit('industry-template.php requires $Industry.');
}
$I = $INDUSTRY;

/* NEW SHAPE, 25 Sep 2026. The six service pages were rebuilt to Kulwant's
   copy, and the industry copy follows the same structure: an opportunity, an
   approach, a client journey, the six services, one or more argument blocks,
   how we start, a story, what we measure and the questions.

   A file on the new shape sets 'opportunity'. A file on the old shape sets
   'stats' and does not. Every section below is guarded on one or the other, so
   the eight industries still on the old shape render exactly as they did.

   NEW KEYS
     label, h1, sub, sub2
     opportunity   [eyebrow, title, quotes[], paras[], items[]]
     approach      [eyebrow, title, paras[], listLead, list[], items[], tail]
     journey       [eyebrow, title, items[]]
     services      [eyebrow, title, items[[icon, name, href, lead, text]], tail]
     blocks[]      [band, eyebrow, title, paras[], items[], listLead, list[], tail]
     movesEyebrow, movesTitle, moves[], movesTail
     reportEyebrow, reportTitle, reportNote, reportGrid, report[], reportTail
   The story and the questions are shared by both shapes. */
$new = !empty($I['opportunity']);

/* The five-across strip takes four or six phrases too. Anything else keeps the
   default five columns and wraps. */
$strip = function (array $list): string {
    $n = count($list);
    return $n === 6 ? ' needs__list--six' : ($n === 4 ? ' needs__list--four' : '');
};

/* All nine industry files are on the new shape as of 21 Sep 2026, so these
   no longer translate anything. They are kept as a single safe read of the
   head and row arrays, so a new industry file with a short head does not
   fatal on a missing index. */
$head = function (array $h): array {
    return [$h[0] ?? '', $h[1] ?? ''];
};
$row = function (array $r): array {
    return count($r) >= 3 ? $r : [null, $r[0] ?? '', $r[1] ?? ''];
};

get_header();
?>

<!-- 01 — HERO ============================================================
     The same hero as the service pages: subhead at its reading width, then
     the two calls in a row beneath it, button first.

     What it replaced: a two-column meta block whose right-hand column held a
     reassurance note. The note is gone and the quiet link to /call/ stands in
     its place, so a visitor moving between a service page and an industry
     page meets the same pattern.

     'image' is optional. Interior design and construction set one; the other
     seven render single column until their object render exists.

     Since 28 Sep 2026 the new-shape hero takes 'heroPhoto' instead, and is
     no longer the same as the service hero. See the note just below.
     ====================================================================== -->
<?php if ($new): ?>
<?php
/* THE INDUSTRY HERO, 28 Sep 2026. A page that sets 'heroPhoto' gets it: the
   headline and the intro side by side, then a wide photograph across the
   full measure, with the industry's group and its sibling pages on a card
   over the photo's lower corner. Deliberately unlike the service hero, which
   is a square object beside the copy, so the two kinds of page read as two
   kinds of page. A file without 'heroPhoto' keeps the centred hero.

   The photographs are from Unsplash, on the same terms as the blog's: places
   and materials, no people, no laptops, no meeting rooms. Hotlinked as the
   Unsplash guidelines require. The on-page credit was removed on request
   on 28 Sep 2026. The photographer is still recorded in each data file
   ('by', 'user') and in CLAIMS.md, so it can be put back.

   The group comes from $FOOTER['industries'] in config.php, so the card and
   the rest of the site cannot disagree about which industries sit together. */
$heroPhoto = $I['heroPhoto'] ?? null;
$heroGroup = null;
if ($heroPhoto) {
    foreach ($FOOTER['industries'] as [$cat, $catHref, $items]) {
        foreach ($items as [$label, $href]) {
            if (is_current($href)) { $heroGroup = [$cat, $catHref, $items]; break 2; }
        }
    }
    $heroSrc = function (int $w, int $h) use ($heroPhoto): string {
        return 'https://images.unsplash.com/' . $heroPhoto['photo'] . '?' . http_build_query([
            'ixlib' => 'rb-4.1.0', 'auto' => 'format', 'fit' => 'crop', 'w' => $w, 'h' => $h, 'q' => 70,
        ]);
    };
}
?>
<section class="hero <?= $heroPhoto ? 'hero--industry' : 'hero--centred' ?>">
  <div class="hero__inner container">

<?php if ($heroPhoto): ?>
    <div class="ind-hero__head">
      <div class="ind-hero__lede">
<?php endif; ?>
    <p class="eyebrow"><span><?= $I['label'] ?></span></p>

    <h1 class="hero__title"><?= $I['h1'] ?></h1>
<?php if ($heroPhoto): ?>
      </div>
<?php endif; ?>

    <div class="hero__meta hero__meta--home">
      <p class="hero__sub"><?= $I['sub'] ?><?php if (!empty($I['sub2'])): ?><span class="hero__sub2"><?= $I['sub2'] ?></span><?php endif; ?></p>
      <div class="hero__actions">
        <a class="btn btn--primary" href="<?= url('/growth-audit/') ?>">Get your growth audit <?= btn_arrow() ?></a>
        <a class="link-quiet" href="<?= url('/call/') ?>">Book a 20-minute call</a>
      </div>
    </div>
<?php if ($heroPhoto): ?>
    </div>

    <div class="ind-hero__visual">
      <?php /* The stage holds the photo and the card, so the card is placed
               against the photo alone. */ ?>
      <div class="ind-hero__stage">
      <figure class="ind-hero__photo">
        <img src="<?= e($heroSrc(1600, 686)) ?>"
             srcset="<?= e($heroSrc(900, 386)) ?> 900w, <?= e($heroSrc(1600, 686)) ?> 1600w, <?= e($heroSrc(2400, 1029)) ?> 2400w"
             sizes="(max-width: 1440px) 100vw, 1440px"
             alt="<?= e($heroPhoto['alt']) ?>" width="1600" height="686" fetchpriority="high" decoding="async">
      </figure>
<?php if ($heroGroup): ?>
      <nav class="ind-hero__group" aria-label="<?= e($heroGroup[0]) ?> industries">
        <a class="label label--clay" href="<?= url($heroGroup[1]) ?>"><?= $heroGroup[0] ?></a>
        <ul role="list">
<?php foreach ($heroGroup[2] as [$label, $href]): ?>
<?php if (is_current($href)): ?>
          <li class="is-current" aria-current="page"><?= $label ?></li>
<?php else: ?>
          <li><a href="<?= url($href) ?>"><?= $label ?></a></li>
<?php endif; ?>
<?php endforeach; ?>
        </ul>
      </nav>
<?php endif; ?>
      </div>
    </div>
<?php endif; ?>

  </div>
</section>


<!-- 02 — THE OPPORTUNITY ================================================= -->
<section class="band band--forest">
  <div class="container">

    <div class="why">
      <div class="why__head">
        <p class="eyebrow eyebrow--light"><span><?= $I['opportunity']['eyebrow'] ?></span></p>
        <h2 class="why__title"><?= $I['opportunity']['title'] ?></h2>
<?php if (!empty($I['opportunity']['quotes'])): ?>
        <ol class="qset" role="list">
<?php foreach ($I['opportunity']['quotes'] as $qi => $q): ?>
          <li><span class="qset__n"><?= sprintf('%02d', $qi + 1) ?></span><span class="qset__q"><?= $q ?></span></li>
<?php endforeach; ?>
        </ol>
<?php endif; ?>
      </div>
      <div class="why__body">
<?php foreach ($I['opportunity']['paras'] as $pi => $p): ?>
        <p<?= $pi === 0 ? ' class="why__lead"' : '' ?>><?= $p ?></p>
<?php endforeach; ?>
      </div>
    </div>

<?php if (!empty($I['opportunity']['items'])): ?>
    <div class="hows hows--onforest">
<?php foreach ($I['opportunity']['items'] as $ii => [$n, $t]): ?>
      <div class="how">
        <p class="label label--clay"><?= sprintf('%02d', $ii + 1) ?></p>
        <p class="how__name"><?= $n ?></p>
        <p class="how__text"><?= $t ?></p>
      </div>
<?php endforeach; ?>
    </div>
<?php endif; ?>

  </div>
</section>


<!-- 03 — OUR APPROACH ==================================================== -->
<?php if (!empty($I['approach'])): ?>
<section class="band band--light">
  <div class="container">

    <div class="approach">
      <div class="approach__head">
        <p class="eyebrow"><span><?= $I['approach']['eyebrow'] ?></span></p>
        <h2 class="approach__title"><?= $I['approach']['title'] ?></h2>
      </div>
      <div class="approach__say">
<?php foreach ($I['approach']['paras'] as $pi => $p): ?>
        <p class="<?= $pi === 0 ? 'approach__lead' : 'approach__text' ?>"><?= $p ?></p>
<?php endforeach; ?>
<?php if (!empty($I['approach']['tail'])): ?>
        <p class="approach__tail"><?= $I['approach']['tail'] ?></p>
<?php endif; ?>
      </div>
    </div>

<?php if (!empty($I['approach']['list'])): ?>
    <div class="needs">
      <p class="label label--clay"><?= $I['approach']['listLead'] ?></p>
      <ol class="needs__list<?= $strip($I['approach']['list']) ?>" role="list">
<?php foreach ($I['approach']['list'] as $li => $item): ?>
        <li><span class="needs__n"><?= sprintf('%02d', $li + 1) ?></span><span class="needs__t"><?= $item ?></span></li>
<?php endforeach; ?>
      </ol>
    </div>
<?php endif; ?>

  </div>
</section>
<?php endif; ?>


<!-- 04 — THE CLIENT JOURNEY ============================================== -->
<?php if (!empty($I['journey'])): ?>
<section class="band band--forest">
  <div class="container">

    <div class="stories__head">
      <div>
        <p class="eyebrow eyebrow--light"><span><?= $I['journey']['eyebrow'] ?></span></p>
        <h2 class="stories__title"><?= $I['journey']['title'] ?></h2>
      </div>
    </div>

<?php if (!empty($I['journey']['paras'])): ?>
    <div class="prose">
<?php foreach ($I['journey']['paras'] as $pi => $p): ?>
      <p<?= $pi === 0 ? ' class="prose__lead"' : '' ?>><?= $p ?></p>
<?php endforeach; ?>
    </div>
<?php endif; ?>

<?php $jc = ['two', 'two', 'three', 'four', 'five'][min(count($I['journey']['items']), 5) - 1]; ?>
    <div class="hows hows--<?= $jc ?> hows--onforest">
<?php foreach ($I['journey']['items'] as $ji => [$n, $t]): ?>
      <div class="how">
        <p class="label label--clay"><?= sprintf('%02d', $ji + 1) ?></p>
        <p class="how__name"><?= $n ?></p>
        <p class="how__text"><?= $t ?></p>
      </div>
<?php endforeach; ?>
    </div>

<?php if (!empty($I['journey']['tail'])): ?>
    <p class="aftercards aftercards--onforest"><?= $I['journey']['tail'] ?></p>
<?php endif; ?>

  </div>
</section>
<?php endif; ?>


<!-- 05 — THE SIX SERVICES ================================================
     The same paper cards the paid advertising page uses for its channels, with
     the name as the link. Six of them, so three across rather than four.
     ====================================================================== -->
<?php if (!empty($I['services'])): ?>
<section class="band band--light">
  <div class="container">

    <div class="questions__head">
      <div>
        <p class="eyebrow"><span><?= $I['services']['eyebrow'] ?></span></p>
        <h2 class="questions__title"><?= $I['services']['title'] ?></h2>
      </div>
    </div>

    <div class="chans chans--three">
<?php foreach ($I['services']['items'] as [$icon, $name, $href, $lead, $text]): ?>
      <article class="chan">
        <span class="chan__icon"><?= svc_icon_svg($icon) ?></span>
        <h3 class="chan__name"><a href="<?= url($href) ?>"><?= $name ?></a></h3>
        <p class="chan__lead"><?= $lead ?></p>
        <p class="chan__text"><?= $text ?></p>
      </article>
<?php endforeach; ?>
    </div>

<?php if (!empty($I['services']['tail'])): ?>
    <p class="aftercards"><?= $I['services']['tail'] ?></p>
<?php endif; ?>

  </div>
</section>
<?php endif; ?>


<!-- 06 — THE ARGUMENT BLOCKS =============================================
     Each block picks its own ground and prints only the parts it has: prose,
     a set of cards, a labelled strip, a closing line.

     A block that sets 'photo' (28 Sep 2026, the first block on each page)
     takes the split layout: the heading and prose on the left, a tall
     photograph on the right, and its points as a row of cards underneath.
     The photographs are from Unsplash on the same terms as the hero ones.
     Every other block renders as it always has.
     ====================================================================== -->
<?php foreach (($I['blocks'] ?? []) as $B):
  $dark = ($B['band'] ?? 'light') === 'forest';
  $bp = $B['photo'] ?? null;
  /* A prose-only block (a heading, paragraphs and a closing line, nothing
     else) takes the brief layout, 29 Sep 2026: heading left, paragraphs
     right, the closing line as a callout under them. It used to run as one
     long column at full width with the closing line loose underneath. */
  $brief = !$bp && !empty($B['paras']) && empty($B['items']) && empty($B['list'])
         && empty($B['quotes']) && empty($B['lead']); ?>
<section class="band band--<?= $dark ? 'forest' : 'light' ?>">
  <div class="container">

<?php if ($bp): ?>
<?php /* A block that also carries a numbered list (29 Sep 2026) flips the
         split, photograph on the left, so a page with two photo blocks does
         not show the same shape twice, and its list becomes cards below. */ ?>
    <div class="split<?= !empty($B['list']) ? ' split--flip' : '' ?>">
      <div class="split__copy">
<?php endif; ?>
<?php if ($brief): ?>
    <div class="brief<?= $dark ? ' brief--onforest' : '' ?>">
      <div class="brief__head">
<?php endif; ?>
    <div class="<?= $dark ? 'stories__head' : 'questions__head' ?>">
      <div>
        <p class="eyebrow<?= $dark ? ' eyebrow--light' : '' ?>"><span><?= $B['eyebrow'] ?></span></p>
        <h2 class="<?= $dark ? 'stories__title' : 'questions__title' ?>"><?= $B['title'] ?></h2>
      </div>
    </div>
<?php if ($brief): ?>
      </div>
      <div class="brief__body">
<?php endif; ?>

<?php /* A line that has to come before the questions rather than after them,
         because it is what the questions are about. */ ?>
<?php if (!empty($B['lead'])): ?>
    <div class="prose">
      <p class="prose__lead"><?= $B['lead'] ?></p>
    </div>
<?php endif; ?>

<?php if (!empty($B['quotes'])): ?>
    <ol class="qset qset--wide" role="list">
<?php foreach ($B['quotes'] as $qi => $q): ?>
      <li><span class="qset__n"><?= sprintf('%02d', $qi + 1) ?></span><span class="qset__q"><?= $q ?></span></li>
<?php endforeach; ?>
    </ol>
<?php endif; ?>

<?php if (!empty($B['paras'])): ?>
    <div class="prose">
<?php foreach ($B['paras'] as $pi => $p): ?>
      <p<?= $pi === 0 ? ' class="prose__lead"' : '' ?>><?= $p ?></p>
<?php endforeach; ?>
    </div>
<?php endif; ?>
<?php if ($brief): ?>
<?php if (!empty($B['tail'])): ?>
        <p class="brief__note"><?= $B['tail'] ?></p>
<?php endif; ?>
      </div>
    </div>
<?php endif; ?>
<?php if ($bp):
      $bu = function (int $w, int $h) use ($bp): string {
          return 'https://images.unsplash.com/' . $bp['photo'] . '?' . http_build_query([
              'ixlib' => 'rb-4.1.0', 'auto' => 'format', 'fit' => 'crop', 'w' => $w, 'h' => $h, 'q' => 70,
          ]);
      }; ?>
      </div>
      <figure class="split__photo">
        <img src="<?= e(!empty($B['list']) ? $bu(800, 600) : $bu(800, 1000)) ?>" srcset="<?= e(!empty($B['list']) ? $bu(560, 420) . ' 560w, ' . $bu(800, 600) . ' 800w, ' . $bu(1120, 840) . ' 1120w' : $bu(560, 700) . ' 560w, ' . $bu(800, 1000) . ' 800w, ' . $bu(1120, 1400) . ' 1120w') ?>" sizes="(max-width: 900px) 100vw, 520px" alt="<?= e($bp['alt']) ?>" width="800" height="<?= !empty($B['list']) ? 600 : 1000 ?>" loading="lazy" decoding="async">
      </figure>
    </div>
<?php endif; ?>

<?php if (!empty($B['items'])):
      $bc = ['two', 'two', 'three', 'four', 'five'][min(count($B['items']), 5) - 1]; ?>
    <div class="hows hows--<?= $bc ?><?= $dark ? ' hows--onforest' : '' ?><?= $bp ? ' hows--cards' : '' ?>">
<?php foreach ($B['items'] as $bi => [$n, $t]): ?>
      <div class="how">
        <p class="label label--clay"><?= sprintf('%02d', $bi + 1) ?></p>
        <p class="how__name"><?= $n ?></p>
        <p class="how__text"><?= $t ?></p>
      </div>
<?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (!empty($B['list'])): ?>
    <div class="needs<?= $bp ? ' needs--cards' : '' ?>">
      <p class="label label--clay"><?= $B['listLead'] ?></p>
      <ol class="needs__list<?= $strip($B['list']) ?>" role="list">
<?php foreach ($B['list'] as $li => $item): ?>
        <li><span class="needs__n"><?= sprintf('%02d', $li + 1) ?></span><span class="needs__t"><?= $item ?></span></li>
<?php endforeach; ?>
      </ol>
    </div>
<?php endif; ?>

<?php if (!empty($B['tail']) && !$brief): ?>
    <p class="aftercards<?= $dark ? ' aftercards--onforest' : '' ?>"><?= $B['tail'] ?></p>
<?php endif; ?>

  </div>
</section>
<?php endforeach; ?>


<!-- 07 — HOW WE START ====================================================
     A page that sets 'movesPhoto' (28 Sep 2026) gets this on a third ground,
     tan, with a tall photograph on the left and the steps as a list on the
     right. It sits between the last argument block and "proof in practice",
     and the argument blocks alternate from forest, so on a page with an odd
     number of them it was a second forest band in a row. Tan reads as its
     own ground next to either. Without 'movesPhoto' it is the forest band it
     always was.
     ====================================================================== -->
<?php if (!empty($I['moves'])):
  $mp = $I['movesPhoto'] ?? null;
  if ($mp):
    $mu = function (int $w, int $h) use ($mp): string {
        return 'https://images.unsplash.com/' . $mp['photo'] . '?' . http_build_query([
            'ixlib' => 'rb-4.1.0', 'auto' => 'format', 'fit' => 'crop', 'w' => $w, 'h' => $h, 'q' => 70,
        ]);
    }; ?>
<section class="band band--tan">
  <div class="container">
    <div class="start">
      <figure class="start__photo">
        <img src="<?= e($mu(800, 1000)) ?>" srcset="<?= e($mu(560, 700)) ?> 560w, <?= e($mu(800, 1000)) ?> 800w, <?= e($mu(1120, 1400)) ?> 1120w" sizes="(max-width: 900px) 100vw, 480px" alt="<?= e($mp['alt']) ?>" width="800" height="1000" loading="lazy" decoding="async">
      </figure>
      <div class="start__copy">
        <div class="questions__head">
          <div>
            <p class="eyebrow"><span><?= $I['movesEyebrow'] ?></span></p>
            <h2 class="questions__title"><?= $I['movesTitle'] ?></h2>
          </div>
        </div>
        <ol class="start__steps" role="list">
<?php foreach ($I['moves'] as $mi => [$n, $t]): ?>
          <li class="start__step">
            <p class="label label--clay"><?= sprintf('%02d', $mi + 1) ?></p>
            <div>
              <h3 class="start__name"><?= $n ?></h3>
              <p class="start__text"><?= $t ?></p>
            </div>
          </li>
<?php endforeach; ?>
        </ol>
<?php if (!empty($I['movesTail'])): ?>
        <p class="aftercards"><?= $I['movesTail'] ?></p>
<?php endif; ?>
      </div>
    </div>
  </div>
</section>
<?php else: ?>
<section class="band band--forest">
  <div class="container">

    <div class="stories__head">
      <div>
        <p class="eyebrow eyebrow--light"><span><?= $I['movesEyebrow'] ?></span></p>
        <h2 class="stories__title"><?= $I['movesTitle'] ?></h2>
      </div>
    </div>

    <div class="steps4">
<?php foreach ($I['moves'] as $mi => [$n, $t]): ?>
      <article class="step4">
        <p class="label label--clay"><?= sprintf('%02d', $mi + 1) ?></p>
        <h3 class="step4__name"><?= $n ?></h3>
        <p class="step4__text"><?= $t ?></p>
      </article>
<?php endforeach; ?>
    </div>

<?php if (!empty($I['movesTail'])): ?>
    <p class="aftercards aftercards--onforest"><?= $I['movesTail'] ?></p>
<?php endif; ?>

  </div>
</section>
<?php endif; ?>
<?php endif; ?>
<?php else: ?>
<section class="hero hero--page<?= !empty($I['image']) ? ' hero--object' : '' ?>">
  <div class="hero__inner container">
    <h1 class="hero__title hero__title--page"><?= $I['h1'] ?></h1>

    <div class="hero__meta hero__meta--home">
      <p class="hero__sub"><?= $I['sub'] ?></p>
      <div class="hero__actions">
        <a class="btn btn--primary" href="<?= url('/growth-audit/') ?>">Get your growth audit <?= btn_arrow() ?></a>
        <a class="link-quiet" href="<?= url('/call/') ?>">Book a 20-minute call</a>
      </div>
    </div>

<?php if (!empty($I['image'])): ?>
    <figure class="hero__object" aria-hidden="true">
      <img src="<?= rankinai_img_url($I['image']) ?>" alt="" width="1040" height="1040" fetchpriority="high" decoding="async">
    </figure>
<?php endif; ?>
  </div>

  <div class="hero__proof hero__proof--four">
<?php foreach ($I['stats'] as [$n, $k]): ?>
    <div class="stat stat--wide">
      <span class="stat__n"><?= $n ?></span>
      <span class="stat__k"><?= $k ?></span>
    </div>
<?php endforeach; ?>
  </div>
</section>


<?php endif; /* old-shape sections */ ?>


<?php /* All nine industries now set these. The guard stays so that a new
         industry file can be added copy first and still render, rather than
         fataling on a missing key. */ ?>
<?php if (!empty($I['channelsHead'])): ?>
<!-- 02 — WHAT THIS TRADE DOES, AND WHAT IT DOESN'T =======================
     The page's first real argument. Not "here are your problems" but "here
     is where your marketing effort goes, and here is the channel it is not
     going into". Two equal panels, because the left column is a list of
     things the reader is doing well and should keep doing.

     Nothing in the left column is criticised. A studio that lives on
     referrals is not doing it wrong, it is doing one thing and missing one.
     ====================================================================== -->
<section class="band band--forest">
  <div class="container">
    <div class="stories__head">
      <div>
        <h2 class="stories__title"><?= $I['channelsHead'][0] ?></h2>
      </div>
      <p class="packages__note"><?= $I['channelsHead'][1] ?></p>
    </div>

    <?php /* One compound mark per row: the chip says what the channel is, the
             badge on its corner says whether they are on it. Two separate
             marks per row read as noise; this reads in one glance. */ ?>
    <div class="chans">
<?php
$cols = [
    [true,  $I['channelsHead'][2], $I['doing']],
    [false, $I['channelsHead'][3], $I['notDoing']],
];
foreach ($cols as [$doing, $title, $items]):
?>
      <div class="chan<?= $doing ? '' : ' chan--no' ?>">
        <div class="chan__head">
          <h3 class="chan__title"><?= $title ?></h3>
          <span class="chan__count"><?= count($items) ?></span>
        </div>
        <ul class="chan__list" role="list">
<?php foreach ($items as [$icon, $name, $text]): ?>
          <li class="chan__item">
            <span class="chan__chip"><?= svc_icon_svg($icon) ?><span class="chan__badge"><?= flag_svg($doing) ?></span></span>
            <span class="chan__body">
              <span class="chan__name"><?= $name ?></span>
              <span class="chan__text"><?= $text ?></span>
            </span>
          </li>
<?php endforeach; ?>
        </ul>
      </div>
<?php endforeach; ?>
    </div>
  </div>
</section>




<!-- 03 — WHAT IT IS COSTING ==============================================
     Published figures only, each with its source printed underneath it. No
     number goes in here that cannot be attributed to a named study, and the
     source is part of the card rather than a footnote, because an agency that
     shows its working is making the argument twice.

     Two of the three figures moved year on year, and a number that moved is
     a great deal more alarming than a number that sits still. Where we have
     the earlier figure it is printed above the current one, so the card shows
     a direction rather than a fact.

     The calculation is a method, not a claim. Every slot on the right is
     deliberately empty: nothing is asserted about what the reader will earn,
     and the blanks are the argument for booking the audit that fills them in.
     See CLAIMS.md.
     ====================================================================== -->
<section class="band band--light">
  <div class="container">
    <div class="questions__head">
      <div>
        <h2 class="questions__title"><?= $I['impactHead'][0] ?></h2>
      </div>
      <p class="stories__note"><?= $I['impactHead'][1] ?></p>
    </div>

    <div class="figs">
<?php foreach ($I['impact'] as [$from, $now, $text, $source]): ?>
      <figure class="fig3">
<?php if ($from): ?>
        <p class="fig3__move"><span class="fig3__was"><?= $from ?></span><span class="fig3__arrow" aria-hidden="true">&rarr;</span><span class="fig3__vis">rising to</span></p>
<?php else: ?>
        <p class="fig3__move fig3__move--flat">today</p>
<?php endif; ?>
        <p class="fig3__n"><?= $now ?></p>
        <p class="fig3__text"><?= $text ?></p>
        <figcaption class="fig3__src"><?= $source ?></figcaption>
      </figure>
<?php endforeach; ?>
    </div>

    <div class="calc">
      <div class="calc__head">
        <h3 class="calc__title"><?= $I['modelHead'][0] ?></h3>
        <p class="calc__text"><?= $I['modelHead'][1] ?></p>
      </div>

      <?php /* Each row carries whose number it is and, where we have one, a
               real figure with its source. The two "ours" rows are sourced.
               The three "yours" rows cannot be: nobody publishes close rates
               for this trade, so they are shown as a worked example in muted
               type and labelled as one. A reader substitutes their own and
               the sum still works. See CLAIMS.md. */ ?>
      <div class="calc__sum">
<?php foreach ($I['model'] as [$op, $whose, $label, $value, $note]): ?>
        <div class="calc__row<?= $whose === 'yours' ? ' calc__row--eg' : '' ?>">
          <span class="calc__op" aria-hidden="true"><?= $op ?></span>
          <span class="calc__label">
            <?= $label ?>
            <span class="calc__note"><?= $note ?></span>
          </span>
          <span class="calc__val"><?= $value ?></span>
        </div>
<?php endforeach; ?>
        <div class="calc__row calc__row--total">
          <span class="calc__op" aria-hidden="true">=</span>
          <span class="calc__label">
            <?= $I['modelResult'][0] ?>
            <span class="calc__note"><?= $I['modelResult'][2] ?></span>
          </span>
          <span class="calc__val calc__val--total"><?= $I['modelResult'][1] ?></span>
        </div>
      </div>

      <a class="btn btn--primary calc__cta" href="<?= url('/growth-audit/') ?>">Get the audit and we&rsquo;ll fill these in <?= btn_arrow() ?></a>
    </div>
  </div>
</section>

<?php endif; ?>


<?php if (!empty($I['midcta'])): ?>
<!-- 04 — MID-PAGE CTA ==================================================== -->
<section class="midcta">
  <div class="container midcta__inner">
    <div class="midcta__say">
      <p class="midcta__line"><?= $I['midcta'][0] ?></p>
      <p class="midcta__text"><?= $I['midcta'][1] ?></p>
    </div>
    <?php /* Kept identical to the service template on purpose. If one changes,
             change both, or the same band reads differently on two pages a
             visitor sees in the same session. */ ?>
    <div class="midcta__act">
      <a class="btn btn--cream btn--lg" href="<?= url('/growth-audit/') ?>">Get your growth audit <?= btn_arrow() ?></a>
      <p class="midcta__note">Free, and back within one working day.</p>
      <a class="link-quiet link-quiet--onforest" href="<?= url('/call/') ?>">Or book a 20-minute call</a>
    </div>
  </div>
</section>
<?php endif; ?>


<?php if (!empty($I['startHead'])): ?>
<!-- 05 — WHAT WE DO ====================================================
     Moved above "what changes". Three sections of problem ran before the
     page said what was being sold, so a reader two screens in still did not
     know what this company does. Answer first, outcome second.
     Every package contains all six services; this is the order they matter
     in for this trade, which is a different claim and a more useful one.
     ====================================================================== -->
<section class="band band--light">
  <div class="container">
    <div class="questions__head">
      <div>
<?php [$t, $n] = $head($I['startHead']); ?>
        <h2 class="questions__title"><?= $t ?></h2>
      </div>
      <p class="stories__note"><?= $n ?></p>
    </div>

    <?php /* The object images are the same renders the service heroes use, so
             a reader who has seen one of those pages recognises the service
             here before reading its name. Lazy: six of them, all below the
             fold. Decorative, so alt is empty and the linked title does the
             announcing. */ ?>
    <div class="beliefs beliefs--light beliefs--cards">
<?php foreach ($I['start'] as [$pri, $svc, $text, $href]): ?>
      <div class="how">
        <p class="how__name"><a href="<?= url($href) ?>"><?= $svc ?></a></p>
        <p class="how__text"><?= $text ?></p>
      </div>
<?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>


<?php if (!empty($I['levers'])): ?>
<!-- 06 — WHERE IT SHOWS UP ===============================================
     This replaced "What changes", which was two columns of sentences split
     by timing. Timing is not the reader's question. Their question is what
     any of this does to the money, so the section is built on the only four
     numbers between a search and a signed job.

     They are deliberately the same four the calculation in section 03 runs
     through, and the same ones the monthly report sends. The multiplication
     chips are not decoration: the point of the section is that these
     compound, which is the honest case for buying all four rather than the
     cheapest one. A fifth on each is 1.2^4, so "roughly double" is arithmetic.

     Each row names the services that move that number and links to them, so
     the six cards above stop being a list and become a set of levers.
     ====================================================================== -->
<section class="band band--light">
  <div class="container">
    <div class="questions__head">
      <div>
        <h2 class="questions__title"><?= $I['leverHead'][0] ?></h2>
      </div>
      <p class="stories__note"><?= $I['leverHead'][1] ?></p>
    </div>

    <div class="levers">
<?php foreach ($I['levers'] as [$name, $when, $text, $svcs]): ?>
      <div class="lever">
        <div class="lever__lead">
          <h3 class="lever__name"><?= $name ?></h3>
          <p class="lever__when"><?= $when ?></p>
        </div>
        <div class="lever__body">
          <p class="lever__text"><?= $text ?></p>
          <ul class="lever__svc" role="list">
<?php foreach ($svcs as [$svcName, $svcHref]): ?>
            <li><a href="<?= url($svcHref) ?>"><?= $svcName ?></a></li>
<?php endforeach; ?>
          </ul>
        </div>
      </div>
<?php endforeach; ?>
      <p class="levers__sum"><?= $I['leverSum'] ?></p>
    </div>
  </div>
</section>


<?php endif; ?>


<!-- 07 — ONE STORY IN FULL ===============================================
     'story' => null renders the pending block. Same rule as the service
     pages: only signed-off clients are named. See CLAIMS.md.
     ====================================================================== -->
<section class="band band--light">
  <div class="container">
    <div class="questions__head">
      <div>
<?php if ($new): ?>
        <p class="eyebrow"><span>Proof in practice</span></p>
<?php endif; ?>
        <h2 class="questions__title"><?= $I['story']['title'] ?? 'A story from this trade is with the client.' ?></h2>
      </div>
    </div>

<?php if (empty($I['story'])): ?>
    <div class="storylist">
      <div class="more">
        <p class="label label--clay">Awaiting sign-off</p>
        <p class="more__title">Nothing invented in the meantime.</p>
        <p class="more__text">We publish three stories and we&rsquo;d rather that stayed true than fill this space. The ones we can stand behind are on the success stories page, each with the figure and the period it took.</p>
        <a class="link-quiet link-quiet--bold" href="<?= url('/success-stories/') ?>">Read the published stories</a>
      </div>
    </div>
<?php else: ?>
    <article class="story storylist">
      <div class="story__copy">
        <div class="story__head">
          <span class="label label--clay">01</span>
          <div>
            <h3 class="story__name"><?= $I['story']['name'] ?></h3>
            <p class="story__where"><?php foreach ($I['story']['meta'] as $m): ?><span class="label"><?= $m ?></span><?php endforeach; ?></p>
          </div>
        </div>
<?php /* Same rule as the service template: a metric only appears when the
                 client has signed it off. Everything else shows the pending
                 state rather than a number nobody can stand behind. */ ?>
<?php if (!empty($I['story']['metric'])): ?>
        <div class="metric">
          <b class="metric__n"><?= $I['story']['metric'] ?></b>
          <span class="metric__k"><?= $I['story']['metricKey'] ?></span>
        </div>
<?php else: ?>
        <div class="metric metric--pending">
          <b class="metric__n">&mdash;</b>
          <span class="metric__k">Figure with the client for sign-off</span>
        </div>
<?php endif; ?>
        <p class="story__text"><?= $I['story']['text'] ?></p>
        <?php /* Named link where the published story is about different work
                 from the section it sits under, so the label promises the
                 client's story rather than this page's figures. */ ?>
        <a class="link-quiet link-quiet--bold" href="<?= url($I['story']['href']) ?>"><?= $I['story']['linkText'] ?? 'Read the full story' ?></a>
      </div>
      <div class="story__photo case__photo">
<?php if (!empty($I['story']['photoSrc'])): ?>
        <img src="<?= rankinai_img_url($I['story']['photoSrc']) ?>" alt="<?= e($I['story']['photoAlt'] ?? '') ?>" width="1200" height="580" loading="lazy" decoding="async">
<?php else: ?>
        <span><?= $I['story']['photo'] ?></span>
<?php endif; ?>
      </div>
    </article>
<?php endif; ?>
  </div>
</section>


<?php if (!empty($I['report'])): ?>
<!-- 07b — MEASURING PROGRESS ============================================
     New shape only. Same four or five cards the service pages use, and the
     same rule: the last one is the commercial outcome, hedged to what the
     client's own records can actually show.
     ====================================================================== -->
<section class="band band--forest">
  <div class="container">

    <div class="stories__head">
      <div>
        <p class="eyebrow eyebrow--light"><span><?= $I['reportEyebrow'] ?></span></p>
        <h2 class="stories__title"><?= $I['reportTitle'] ?></h2>
<?php if (!empty($I['reportNote'])): ?>
        <p class="packages__note"><?= $I['reportNote'] ?></p>
<?php endif; ?>
      </div>
    </div>

    <div class="wcards <?= $I['reportGrid'] ?? 'wcards--journey' ?>">
<?php foreach ($I['report'] as $ri => [$n, $t]): ?>
      <div class="wcard">
        <span class="wcard__n"><?= sprintf('%02d', $ri + 1) ?></span>
        <h3 class="wcard__name"><?= $n ?></h3>
        <p class="wcard__text"><?= $t ?></p>
      </div>
<?php endforeach; ?>
    </div>

<?php if (!empty($I['reportTail'])): ?>
    <p class="aftercards aftercards--onforest"><?= $I['reportTail'] ?></p>
<?php endif; ?>

  </div>
</section>
<?php endif; ?>


<!-- 08 — OUR TEAM ========================================================
     Same section as the homepage and the service pages, from includes/team.php.
     It sits after the story: the story says the work landed for a named
     client, this says who would be doing it for you.
     ====================================================================== -->
<?php if (!$new) { get_template_part( 'template-parts/team' ); } ?>


<!-- 09 — QUESTIONS =======================================================
     Full top padding, not qbody--tight. The tight variant exists for a light
     FAQ sitting straight under another light band, where two sets of padding
     read as a gap. Here the dark team band is directly above, so the padding
     is what separates them.
     ====================================================================== -->
<section class="band band--light">
  <div class="container">
    <div class="questions__head">
      <div>
<?php if ($new): ?>
        <p class="eyebrow"><span>Frequently asked questions</span></p>
        <h2 class="questions__title">Before you decide.</h2>
<?php else: ?>
        <h2 class="questions__title">Frequently asked questions</h2>
<?php endif; ?>
      </div>
      <a class="link-quiet link-quiet--bold" href="<?= url('/questions/') ?>">Every question, answered in full</a>
    </div>

    <div class="qa qa--wide">
<?php foreach ($I['qs'] as $i => [$q, $a]): ?>
      <details class="qi" name="faq">
        <summary class="qi__q">
          <span class="qi__n"><?= sprintf('%02d', $i + 1) ?></span>
          <span class="qi__text"><?= $q ?></span>
          <span class="qi__mark" aria-hidden="true"></span>
        </summary>
        <div class="qi__a">
<?php foreach ((array) $a as $para): ?>
          <p><?= $para ?></p>
<?php endforeach; ?>
        </div>
      </details>
<?php endforeach; ?>
    </div>
  </div>
</section>


<!-- 10 — THE CLOSE ======================================================= -->

<?php
$faq = [];
foreach ($I['qs'] as [$q, $a]) {
    $faq[] = [
        '@type' => 'Question',
        'name'  => html_entity_decode(strip_tags($q), ENT_QUOTES, 'UTF-8'),
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => html_entity_decode(strip_tags(implode(' ', (array) $a)), ENT_QUOTES, 'UTF-8')],
    ];
}
?>
<script type="application/ld+json">
<?= json_encode(['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $faq],
    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>

<?php get_footer(); ?>
