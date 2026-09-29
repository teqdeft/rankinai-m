<?php
/**
 * The service page
 * -----------------------------------------------------------------------------
 * One template, six pages. Each service file fills $SERVICE and requires this.
 *
 * REBUILT 25 Sep 2026 to the structure of Kulwant's AI and search visibility
 * copy, and to the rules the home, about, pricing, how-we-work, questions and
 * contact pages were settled on: no uppercase, no em-dashes, no semicolons, no
 * <br> in a heading, one eyebrow per section head, heading and note both free
 * inside the 1024px measure, alternating light and dark bands, and the shared
 * close from includes/close.php.
 *
 * EVERY SECTION IS CONDITIONAL. The new copy adds three sections the other
 * five services have no data for yet: 'opportunity', 'approach' and 'compare'.
 * A page without them simply does not render them, so the five older files
 * keep working untouched and gain the sections as their copy arrives. Nothing
 * here prints an empty band.
 *
 * WHAT CAME OFF IN THE REBUILD
 *   · the mid-page CTA. The new copy puts its actions in the hero and again
 *     under "is this the right next step", which is where a reader decides.
 *   · the team marquee. Not in the copy. It is still on the home page and on
 *     /about/, and includes/team.php is unchanged if it should come back.
 *   · the hero object image. It came back on 28 Sep 2026 in a new split hero,
 *     'heroImage', so the service pages no longer share the centred hero with
 *     every other page. Each page sets its own image: service-search,
 *     service-content, service-paid, service-web, service-reputation,
 *     service-crm.
 *
 * THE STORY BLOCK IS UNCHANGED, including its rule: 'story' => null renders a
 * pending block rather than an invented client. Only Pine Tree Lane, Studio
 * Ubique and SweetRush are real. Do not fill these in with anything that is
 * not a signed-off customer. See CLAIMS.md.
 *
 * WordPress: becomes single-service.php with ACF fields in the same shape.
 *
 * WORDPRESS: ported 29 Sep 2026 from the flat build's includes/service-template.php. The array
 * the template reads now comes from the post's ACF fields through
 * rankinai_cpt_data() (inc/content-model.php) instead of a data file. The
 * markup below is the flat build's, unchanged.
 */
global $FOOTER, $SITE, $NAV;
$SERVICE = rankinai_cpt_data();
$S = $SERVICE;
get_header();
?>

<!-- 01 — HERO ============================================================
     Two shapes. A page that sets 'heroImage' gets the service hero: copy on
     the left, the service's object on the right, and under it the group the
     service belongs to with its two siblings, so a reader can move sideways
     between services without going back to the menu. A page without it keeps
     the centred hero every other page on the site uses.

     The group comes from $FOOTER in config.php, the same "Getting found" and
     "Getting booked" split the footer prints, so the two never disagree.
     ====================================================================== -->
<?php
$heroImg = $S['heroImage'] ?? null;
$heroGroup = null;
if ($heroImg) {
    foreach (['found' => 'Getting found', 'booked' => 'Getting booked'] as $key => $name) {
        foreach ($FOOTER[$key] as [$label, $href]) {
            if (is_current($href)) { $heroGroup = [$name, $FOOTER[$key]]; break 2; }
        }
    }
}
?>
<section class="hero <?= $heroImg ? 'hero--service' : 'hero--centred' ?>">
  <div class="hero__inner container">

<?php if ($heroImg): ?>
    <div class="svc-hero__copy">
<?php endif; ?>
    <p class="eyebrow"><span><?= $S['label'] ?></span></p>

    <h1 class="hero__title"><?= $S['h1'] ?></h1>

    <div class="hero__meta hero__meta--home">
      <p class="hero__sub"><?= $S['sub'] ?><?php if (!empty($S['sub2'])): ?><span class="hero__sub2"><?= $S['sub2'] ?></span><?php endif; ?></p>
      <div class="hero__actions">
        <a class="btn btn--primary" href="<?= url('/growth-audit/') ?>">Get your growth audit <?= btn_arrow() ?></a>
        <a class="link-quiet" href="<?= url('/call/') ?>">Book a 20-minute call</a>
      </div>
<?php if (!empty($S['heroNote'])): ?>
      <p class="hero__note"><?= $S['heroNote'] ?></p>
<?php endif; ?>
    </div>
<?php if ($heroImg): ?>
    </div>

    <?php /* Since 28 Sep 2026 each service shows an Unsplash photograph
             chosen for that service ('photo' in heroImage), on the same terms
             as the industry and blog photographs: no people, no laptops, no
             meeting rooms, hotlinked as the Unsplash guidelines require. See
             CLAIMS.md. A heroImage with 'src' instead still serves a local
             file, so the object renders in assets/images can come back.

             The forest block behind it stands in for a shadow, which the
             image brief rules out. */ ?>
    <div class="svc-hero__visual">
      <figure class="svc-hero__frame">
<?php if (!empty($heroImg['photo'])):
        $u = function (int $px) use ($heroImg): string {
            return 'https://images.unsplash.com/' . $heroImg['photo'] . '?' . http_build_query([
                'ixlib' => 'rb-4.1.0', 'auto' => 'format', 'fit' => 'crop', 'w' => $px, 'h' => $px, 'q' => 70,
            ]);
        }; ?>
        <img src="<?= e($u(1000)) ?>" srcset="<?= e($u(600)) ?> 600w, <?= e($u(1000)) ?> 1000w, <?= e($u(1400)) ?> 1400w" sizes="(max-width: 900px) 480px, 540px" alt="<?= e($heroImg['alt']) ?>" width="1000" height="1000" fetchpriority="high" decoding="async">
<?php else: ?>
        <img src="<?= asset($heroImg['src']) ?>" alt="" width="<?= (int) $heroImg['w'] ?>" height="<?= (int) $heroImg['h'] ?>" fetchpriority="high">
<?php endif; ?>
      </figure>
<?php if ($heroGroup): ?>
      <nav class="svc-hero__group" aria-label="<?= e($heroGroup[0]) ?> services">
        <p class="label label--clay"><?= $heroGroup[0] ?></p>
        <ul role="list">
<?php foreach ($heroGroup[1] as [$label, $href]): ?>
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
<?php endif; ?>

  </div>
</section>


<?php if (!empty($S['opportunity'])): ?>
<!-- 02 — THE OPPORTUNITY =================================================
     The claim on the left and what follows from it on the right, the same
     shape the about page opens with. The three points underneath are the
     argument in its shortest form.
     ====================================================================== -->
<section class="band band--forest">
  <div class="container">

    <div class="why">
      <div class="why__head">
        <p class="eyebrow eyebrow--light"><span><?= $S['opportunity']['eyebrow'] ?? 'The opportunity' ?></span></p>
        <h2 class="why__title"><?= $S['opportunity']['title'] ?></h2>
<?php /* The buyer's own questions, where a service has them, set as type under
         the heading rather than run into the prose beside it. Same .qset the
         how we work page uses, which already has its forest colours. */ ?>
<?php if (!empty($S['opportunity']['quotes'])): ?>
        <ol class="qset" role="list">
<?php foreach ($S['opportunity']['quotes'] as $i => $q): ?>
          <li><span class="qset__n"><?= sprintf('%02d', $i + 1) ?></span><span class="qset__q"><?= $q ?></span></li>
<?php endforeach; ?>
        </ol>
<?php endif; ?>
      </div>
      <div class="why__body">
<?php foreach ($S['opportunity']['paras'] as $i => $p): ?>
        <p<?= $i === 0 ? ' class="why__lead"' : '' ?>><?= $p ?></p>
<?php endforeach; ?>
      </div>
    </div>

<?php /* A service whose opportunity is the questions and the argument alone
         renders those and stops. */ ?>
<?php if (!empty($S['opportunity']['items'])): ?>
    <div class="hows hows--onforest">
<?php foreach ($S['opportunity']['items'] as $i => [$name, $text]): ?>
      <div class="how">
        <p class="label label--clay"><?= sprintf('%02d', $i + 1) ?></p>
        <p class="how__name"><?= $name ?></p>
        <p class="how__text"><?= $text ?></p>
      </div>
<?php endforeach; ?>
    </div>
<?php endif; ?>

  </div>
</section>
<?php endif; ?>


<?php if (!empty($S['approach'])): ?>
<!-- 03 — OUR APPROACH ====================================================
     Four stacked blocks became two rows. The heading and the thinking sit
     side by side rather than one under the other, and the closing line is the
     last paragraph of that column instead of a block of its own. Two vertical
     gaps and a whole heading block came out of the height.

     Under them, the five things we need to understand run as a strip: a clay
     rule, a mono number and a short line each. Rules rather than cards, and
     five across rather than a list down, which is the only horizontal rhythm
     on this page and the reason the section reads quickly.
     ====================================================================== -->
<section class="band band--light">
  <div class="container">

    <div class="approach">
      <div class="approach__head">
        <p class="eyebrow"><span><?= $S['approach']['eyebrow'] ?? 'Our approach' ?></span></p>
        <h2 class="approach__title"><?= $S['approach']['title'] ?></h2>
      </div>

      <div class="approach__say">
<?php foreach ($S['approach']['paras'] as $i => $p): ?>
        <p class="<?= $i === 0 ? 'approach__lead' : 'approach__text' ?>"><?= $p ?></p>
<?php endforeach; ?>
<?php if (!empty($S['approach']['tail'])): ?>
        <p class="approach__tail"><?= $S['approach']['tail'] ?></p>
<?php endif; ?>
      </div>
    </div>

<?php /* Three named moves instead of the five-across strip, where what follows
         the thinking is a set of things the approach produces rather than a
         set of things we need to know. */ ?>
<?php if (!empty($S['approach']['items'])): ?>
    <div class="hows hows--three">
<?php foreach ($S['approach']['items'] as $i => [$name, $text]): ?>
      <div class="how">
        <p class="label label--clay"><?= sprintf('%02d', $i + 1) ?></p>
        <p class="how__name"><?= $name ?></p>
        <p class="how__text"><?= $text ?></p>
      </div>
<?php endforeach; ?>
    </div>
<?php endif; ?>

<?php /* A service whose approach is prose alone renders the two rows and stops.
         Nothing prints an empty strip. */ ?>
<?php if (!empty($S['approach']['list'])): ?>
    <div class="needs">
      <p class="label label--clay"><?= $S['approach']['listLead'] ?></p>
      <ol class="needs__list" role="list">
<?php foreach ($S['approach']['list'] as $i => $item): ?>
        <li><span class="needs__n"><?= sprintf('%02d', $i + 1) ?></span><span class="needs__t"><?= $item ?></span></li>
<?php endforeach; ?>
      </ol>
    </div>
<?php endif; ?>

  </div>
</section>
<?php endif; ?>


<?php if (!empty($S['channels'])): ?>
<!-- 03b — WHERE WE ADVERTISE =============================================
     Service-specific. Only the paid advertising page has this data.

     Four channels, not four steps: they are alternatives, and the reader is
     choosing between them rather than working through them. So they carry an
     icon and no number, and they sit on the cream ground directly under the
     section that decided which enquiries are worth buying. Two light bands in
     a row is handled in style.css: the gradient carries on and the gap closes
     to a section gap, so the two read as one continuous field of setup before
     the dark band changes the subject.
     ====================================================================== -->
<section class="band band--light">
  <div class="container">

    <div class="questions__head">
      <div>
        <p class="eyebrow"><span><?= $S['channels']['eyebrow'] ?? 'Where we advertise' ?></span></p>
        <h2 class="questions__title"><?= $S['channels']['title'] ?></h2>
      </div>
    </div>

    <div class="chans">
<?php foreach ($S['channels']['items'] as [$icon, $name, $lead, $text]): ?>
      <article class="chan">
        <span class="chan__icon"><?= svc_icon_svg($icon) ?></span>
        <h3 class="chan__name"><?= $name ?></h3>
        <p class="chan__lead"><?= $lead ?></p>
        <p class="chan__text"><?= $text ?></p>
      </article>
<?php endforeach; ?>
    </div>

<?php if (!empty($S['channels']['tail'])): ?>
    <p class="aftercards"><?= $S['channels']['tail'] ?></p>
<?php endif; ?>

  </div>
</section>
<?php endif; ?>


<?php if (!empty($S['do'])): ?>
<!-- 04 — WHAT THE SERVICE COVERS =========================================
     Numbered cards with an icon each. A row can be [icon, name, lead, text]
     or the older [name, text]; both render, so the five files that have not
     been rewritten yet keep working.
     ====================================================================== -->
<section class="band band--forest">
  <div class="container">

    <div class="stories__head">
      <div>
        <p class="eyebrow eyebrow--light"><span><?= !empty($S['doHead'][0]) ? $S['doHead'][0] : 'What the service covers' ?></span></p>
        <h2 class="stories__title"><?= $S['doHead'][1] ?></h2>
<?php if (!empty($S['doHead'][2])): ?>
        <p class="packages__note"><?= $S['doHead'][2] ?></p>
<?php endif; ?>
      </div>
    </div>

    <div class="wcards wcards--three">
<?php foreach ($S['do'] as $i => $row):
      $icon = count($row) > 2 ? $row[0] : '';
      $name = count($row) > 2 ? $row[1] : $row[0];
      $lead = count($row) > 3 ? $row[2] : '';
      $text = count($row) > 3 ? $row[3] : $row[1];
?>
      <div class="wcard">
<?php if ($icon): ?>
        <span class="wcard__icon"><?= svc_icon_svg($icon) ?></span>
<?php endif; ?>
        <span class="wcard__n"><?= sprintf('%02d', $i + 1) ?></span>
        <h3 class="wcard__name"><?= $name ?></h3>
<?php if ($lead): ?>
        <p class="wcard__lead"><?= $lead ?></p>
<?php endif; ?>
        <p class="wcard__text"><?= $text ?></p>
      </div>
<?php endforeach; ?>
    </div>

  </div>
</section>
<?php endif; ?>


<?php if (!empty($S['compare'])): ?>
<!-- 05 — TWO WAYS OF SEARCHING ==========================================
     Service-specific. Only the AI and search visibility page has this data.

     The two searches come first, before the heading. They are the evidence,
     and the heading is the conclusion drawn from them: show a reader the two
     things and they have made the point themselves before they read it.

     After that, the heading beside what we do about it, then what the
     reporting shows as a strip. Three horizontal rows rather than a column of
     prose with a list beside it.
     ====================================================================== -->
<?php $cp = $S['compare']['photo'] ?? null;
if ($cp):
  /* THE PHOTO VERSION, 29 Sep 2026. Heading and lead on the left with a
     small photograph of an old brick mill beside them (the detailed search
     asks about converting older buildings into offices). Then the two
     searches as a row, then the rest of the prose beside the reporting
     points as a numbered card. The photograph was first a full-width band
     under the heading with the searches over it, and was too large.
     The photograph is from Unsplash on the site's usual terms, see
     CLAIMS.md. Without 'photo' the section renders as it did before. */
  $cu = function (int $w, int $h) use ($cp): string {
      return 'https://images.unsplash.com/' . $cp['photo'] . '?' . http_build_query([
          'ixlib' => 'rb-4.1.0', 'auto' => 'format', 'fit' => 'crop', 'w' => $w, 'h' => $h, 'q' => 70,
      ]);
  };
  $cParas = $S['compare']['paras']; ?>
<section class="band band--light">
  <div class="container">

    <div class="cmp__head">
      <div>
        <p class="eyebrow"><span><?= $S['compare']['eyebrow'] ?></span></p>
        <h2 class="says__title"><?= $S['compare']['title'] ?></h2>
        <p class="says__lead"><?= array_shift($cParas) ?></p>
      </div>
      <figure class="cmp__photo">
        <img src="<?= e($cu(720, 540)) ?>" srcset="<?= e($cu(480, 360)) ?> 480w, <?= e($cu(720, 540)) ?> 720w, <?= e($cu(960, 720)) ?> 960w" sizes="(max-width: 900px) 100vw, 420px" alt="<?= e($cp['alt']) ?>" width="720" height="540" loading="lazy" decoding="async">
      </figure>
    </div>

    <div class="cmp__stage">
      <div class="asks cmp__asks">
<?php foreach ($S['compare']['quotes'] as [$icon, $kind, $q]): ?>
        <figure class="ask">
          <figcaption class="label label--clay"><?= $kind ?></figcaption>
          <blockquote class="ask__field">
            <span class="ask__icon" aria-hidden="true"><?= svc_icon_svg($icon) ?></span>
            <span class="ask__t"><?= $q ?></span>
            <span class="ask__caret" aria-hidden="true"></span>
          </blockquote>
        </figure>
<?php endforeach; ?>
      </div>
    </div>

    <div class="cmp__body">
      <div class="cmp__text">
<?php foreach ($cParas as $p): ?>
        <p class="says__text"><?= $p ?></p>
<?php endforeach; ?>
      </div>
      <div class="cmp__report">
        <p class="label label--clay"><?= $S['compare']['listTitle'] ?></p>
        <ol class="cmp__list" role="list">
<?php foreach ($S['compare']['list'] as $i => $item): ?>
          <li><span class="needs__n"><?= sprintf('%02d', $i + 1) ?></span><span><?= $item ?></span></li>
<?php endforeach; ?>
        </ol>
<?php if (!empty($S['compare']['tail'])): ?>
        <p class="needs__tail"><?= $S['compare']['tail'] ?></p>
<?php endif; ?>
      </div>
    </div>

  </div>
</section>
<?php else: ?>
<section class="band band--light">
  <div class="container">

    <p class="eyebrow"><span><?= $S['compare']['eyebrow'] ?></span></p>

    <div class="asks">
<?php foreach ($S['compare']['quotes'] as [$icon, $kind, $q]): ?>
      <figure class="ask">
        <figcaption class="label label--clay"><?= $kind ?></figcaption>
        <blockquote class="ask__field">
          <span class="ask__icon" aria-hidden="true"><?= svc_icon_svg($icon) ?></span>
          <span class="ask__t"><?= $q ?></span>
          <span class="ask__caret" aria-hidden="true"></span>
        </blockquote>
      </figure>
<?php endforeach; ?>
    </div>

    <div class="says">
      <h2 class="says__title"><?= $S['compare']['title'] ?></h2>
      <div class="says__body">
<?php foreach ($S['compare']['paras'] as $i => $p): ?>
        <p class="<?= $i === 0 ? 'says__lead' : 'says__text' ?>"><?= $p ?></p>
<?php endforeach; ?>
      </div>
    </div>

    <div class="needs">
      <p class="label label--clay"><?= $S['compare']['listTitle'] ?></p>
      <ol class="needs__list" role="list">
<?php foreach ($S['compare']['list'] as $i => $item): ?>
        <li><span class="needs__n"><?= sprintf('%02d', $i + 1) ?></span><span class="needs__t"><?= $item ?></span></li>
<?php endforeach; ?>
      </ol>
<?php if (!empty($S['compare']['tail'])): ?>
      <p class="needs__tail"><?= $S['compare']['tail'] ?></p>
<?php endif; ?>
    </div>

  </div>
</section>
<?php endif; ?>
<?php endif; ?>


<?php if (!empty($S['substance'])):
  /* One block or several. A service with a single block writes it flat, which
     is what /content/ and /website-conversion/ do; a service with two wraps
     them in a list. Detected on 'title', because a block always has one and a
     list never does. */
  $blocks = isset($S['substance']['title']) ? [$S['substance']] : $S['substance'];
  foreach ($blocks as $B):
    $dark = ($B['band'] ?? 'light') === 'forest';
?>
<!-- 05b — THE ARGUMENT IN ITS OWN SECTION ================================
     Service-specific, and the most general of the blocks: a heading, the
     thinking under it, and three columns where the copy has three parallel
     statements. Every part except the heading is optional, so a service can
     use it for prose alone, for three cards alone, or for both.

     Each block picks its own ground, because a page with two of them wants
     them to read as two different moves rather than one long field of cream.
     ====================================================================== -->
<section class="band band--<?= $dark ? 'forest' : 'light' ?>">
  <div class="container">

    <div class="<?= $dark ? 'stories__head' : 'questions__head' ?>">
      <div>
        <p class="eyebrow<?= $dark ? ' eyebrow--light' : '' ?>"><span><?= $B['eyebrow'] ?></span></p>
        <h2 class="<?= $dark ? 'stories__title' : 'questions__title' ?>"><?= $B['title'] ?></h2>
      </div>
    </div>

<?php if (!empty($B['paras'])): ?>
    <div class="prose">
<?php foreach ($B['paras'] as $i => $p): ?>
      <p<?= $i === 0 ? ' class="prose__lead"' : '' ?>><?= $p ?></p>
<?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (!empty($B['items'])):
      /* Three or four across, from the count. Same picker the category
         template uses, so a block never has to state its own grid. */
      $cols = ['two', 'two', 'three', 'four'][min(count($B['items']), 4) - 1];
?>
    <div class="hows hows--<?= $cols ?><?= $dark ? ' hows--onforest' : '' ?>">
<?php foreach ($B['items'] as $i => [$claim, $shown]): ?>
      <div class="how">
        <p class="label label--clay"><?= sprintf('%02d', $i + 1) ?></p>
        <p class="how__name"><?= $claim ?></p>
        <p class="how__text"><?= $shown ?></p>
      </div>
<?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (!empty($B['tail'])): ?>
    <p class="aftercards<?= $dark ? ' aftercards--onforest' : '' ?>"><?= $B['tail'] ?></p>
<?php endif; ?>

  </div>
</section>
<?php endforeach; endif; ?>


<?php if (!empty($S['routes'])): ?>
<!-- 05c — WHICH ROUTE ====================================================
     Service-specific. Where a service has two honest answers rather than one,
     they go side by side as the two cards the contact page uses, so the reader
     sees a choice being offered rather than a recommendation being made.
     ====================================================================== -->
<section class="band band--forest">
  <div class="container">

    <div class="stories__head">
      <div>
        <p class="eyebrow eyebrow--light"><span><?= $S['routes']['eyebrow'] ?></span></p>
        <h2 class="stories__title"><?= $S['routes']['title'] ?></h2>
      </div>
    </div>

    <div class="routes">
<?php foreach ($S['routes']['items'] as [$name, $text]): ?>
      <article class="route">
        <h3 class="route__name"><?= $name ?></h3>
        <p class="route__text"><?= $text ?></p>
      </article>
<?php endforeach; ?>
    </div>

<?php if (!empty($S['routes']['tail'])): ?>
    <p class="aftercards aftercards--onforest"><?= $S['routes']['tail'] ?></p>
<?php endif; ?>

  </div>
</section>
<?php endif; ?>


<?php if (!empty($S['moves'])): ?>
<!-- 06 — HOW THE WORK PROGRESSES =========================================
     Four steps. Each one names what it settles, not just what happens in it.
     ====================================================================== -->
<section class="band band--forest">
  <div class="container">

    <div class="stories__head">
      <div>
        <p class="eyebrow eyebrow--light"><span><?= $S['movesEyebrow'] ?? 'How the work progresses' ?></span></p>
        <h2 class="stories__title"><?= $S['movesTitle'] ?></h2>
      </div>
    </div>

    <div class="steps4">
<?php foreach ($S['moves'] as $i => [$name, $text]): ?>
      <article class="step4">
        <p class="label label--clay"><?= sprintf('%02d', $i + 1) ?></p>
        <h3 class="step4__name"><?= $name ?></h3>
        <p class="step4__text"><?= $text ?></p>
      </article>
<?php endforeach; ?>
    </div>

<?php if (!empty($S['movesTail'])): ?>
    <p class="aftercards aftercards--onforest"><?= $S['movesTail'] ?></p>
<?php endif; ?>

  </div>
</section>
<?php endif; ?>


<!-- 06b — WHAT ELSE THE WORK DOES — REMOVED =============================
     Built 25 Sep 2026 on the content page and removed the same day at
     Kulwant's instruction. It was prose at the shared measure arguing that a
     finished piece keeps working after publication.

     Removed with it: the 'reuse' data block in content.php. No CSS went with
     it, because it reused .questions__head, .prose and .aftercards.
     ====================================================================== -->


<!-- 07 — ONE STORY IN FULL ===============================================
     'story' => null renders a pending block instead of a case.

     Only Pine Tree Lane, Studio Ubique and SweetRush are real, published
     clients. The design canvases invented named companies with full results
     attached for four of the six services. A fabricated client is not a
     placeholder that can be quietly shipped. See CLAIMS.md.
     ====================================================================== -->
<section class="band band--light">
  <div class="container">
    <div class="questions__head">
      <div>
        <p class="eyebrow"><span>Proof in practice</span></p>
        <h2 class="questions__title"><?= $S['story']['title'] ?? 'A story for this service is with the client.' ?></h2>
<?php if (empty($S['story'])): ?>
        <p class="stories__note">We publish three stories and we&rsquo;d rather that stayed true than fill this space. When the client for this one signs off the numbers, it appears here, with the period attached, like the others.</p>
<?php endif; ?>
      </div>
    </div>

<?php if (empty($S['story'])): ?>
    <div class="storylist">
      <div class="more">
        <p class="label label--clay">Awaiting sign-off</p>
        <p class="more__title">Nothing invented in the meantime.</p>
        <p class="more__text">The three stories we can stand behind are on the success stories page, with the figure and the period for each.</p>
        <a class="link-quiet link-quiet--bold" href="<?= url('/success-stories/') ?>">Read the published stories</a>
      </div>
    </div>
<?php else: ?>

    <article class="story storylist">
      <div class="story__copy">
        <div class="story__head">
          <span class="label label--clay">01</span>
          <div>
            <h3 class="story__name"><?= $S['story']['name'] ?></h3>
            <p class="story__where"><?php foreach ($S['story']['meta'] as $m): ?><span class="label"><?= $m ?></span><?php endforeach; ?></p>
          </div>
        </div>

<?php /* A story can run before its number does. Naming a real client and
         saying what we do for them is a fact we can stand behind today; the
         figure is theirs to release. */ ?>
<?php if (!empty($S['story']['metric'])): ?>
        <div class="metric">
          <b class="metric__n"><?= $S['story']['metric'] ?></b>
          <span class="metric__k"><?= $S['story']['metricKey'] ?></span>
        </div>
<?php else: ?>
        <div class="metric metric--pending">
          <b class="metric__n">&mdash;</b>
          <span class="metric__k">Figure with the client for sign-off</span>
        </div>
<?php endif; ?>

        <p class="story__text"><?= $S['story']['text'] ?></p>

        <a class="link-quiet link-quiet--bold" href="<?= url($S['story']['href']) ?>"><?= $S['story']['linkText'] ?? 'Read the full story' ?></a>
      </div>

      <div class="story__photo case__photo">
<?php if (!empty($S['story']['photoSrc'])): ?>
        <img src="<?= rankinai_img_url($S['story']['photoSrc']) ?>" alt="<?= e($S['story']['photoAlt'] ?? '') ?>" width="1200" height="580" loading="lazy" decoding="async">
<?php else: ?>
        <span><?= $S['story']['photo'] ?></span>
<?php endif; ?>
      </div>
    </article>
<?php endif; ?>
  </div>
</section>


<?php if (!empty($S['report'])): ?>
<!-- 08 — MEASURING PROGRESS ==============================================
     Four stages of one journey rather than four metrics, with a chevron
     between them to say so.
     ====================================================================== -->
<section class="band band--forest">
  <div class="container">

    <div class="stories__head">
      <div>
        <p class="eyebrow eyebrow--light"><span><?= $S['reportEyebrow'] ?? 'Measuring progress' ?></span></p>
        <h2 class="stories__title"><?= $S['reportTitle'] ?? 'What the monthly report shows.' ?></h2>
<?php if (!empty($S['reportNote'])): ?>
        <p class="packages__note"><?= $S['reportNote'] ?></p>
<?php endif; ?>
      </div>
    </div>

    <div class="wcards <?= $S['reportGrid'] ?? 'wcards--journey' ?>">
<?php foreach ($S['report'] as $i => [$name, $text]): ?>
      <div class="wcard">
        <span class="wcard__n"><?= sprintf('%02d', $i + 1) ?></span>
        <h3 class="wcard__name"><?= $name ?></h3>
        <p class="wcard__text"><?= $text ?></p>
      </div>
<?php endforeach; ?>
    </div>

<?php if (!empty($S['reportTail'])): ?>
    <p class="aftercards aftercards--onforest"><?= $S['reportTail'] ?></p>
<?php endif; ?>

  </div>
</section>
<?php endif; ?>


<!-- 08b — YOUR TEAM'S PART — REMOVED ====================================
     Built 25 Sep 2026 on the paid advertising page and removed the same day at
     Kulwant's instruction. It asked the client's sales team to record what
     each enquiry turned into, as a five-across strip of ticks.

     Removed with it: the 'feedback' data block in paid-advertising.php, and
     .needs__mark and .says--lead in style.css. Nothing here renders it, and no
     page carries the data.
     ====================================================================== -->


<!-- 09 — IS THIS THE RIGHT NEXT STEP? — REMOVED ==========================
     Removed 25 Sep 2026, at Kulwant's instruction. It carried the "this is
     for you if" list, the "it is not, if" column where a service had one, and
     a repeat of the two hero actions.

     The data is gone from all six service files as of 25 Sep 2026. Nothing
     renders it and nothing carries it. The .fit rules in style.css are now
     unused by the service pages, though .fit is still referenced elsewhere,
     so they were left alone.
     ====================================================================== -->


<?php if (!empty($S['qs'])): ?>
<!-- 10 — QUESTIONS =======================================================
     The same <details> accordion as the questions page, so the site has one
     way of asking and answering rather than two.
     ====================================================================== -->
<section class="band band--light qbody">
  <div class="container">

    <div class="qgroup">
      <div class="qgroup__head">
        <p class="eyebrow"><span>Frequently asked questions</span></p>
        <h2 class="qgroup__title"><?= $S['qsTitle'] ?? 'Before you decide.' ?></h2>
      </div>

      <div class="qa">
<?php foreach ($S['qs'] as $i => [$q, $a]): ?>
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

  </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
