<?php
/**
 * Blog post — the detail page
 * =============================================================================
 * One template, one data file per post:
 *
 *   blog-what-an-assistant-reads.php  ->  $POST = [...]; require this file;
 *
 * Served at /blog/<slug>/ by the two-segment rewrite already in .htaccess.
 *
 * THE BODY IS A LIST OF BLOCKS, so a post file stays data rather than markup
 * and the same rows move into Gutenberg without rewriting:
 *
 *   ['h2',    'A heading']
 *   ['h3',    'A sub-heading']
 *   ['p',     'A paragraph.']
 *   ['note',  'A single statement, set apart.']
 *   ['list',  ['One.', 'Two.']]
 *   ['olist', ['First.', 'Second.']]
 *   ['quote', 'A line worth pulling out.']
 *   ['table', ['An optional caption', ['Head', 'Head'], [['Cell', 'Cell']]]]
 *   ['img',   ['photo-id', 'Alt text', 'Photographer', 'username', 'photo-slug']]
 *   ['take',  ['What to take from this', ['One.', 'Two.']]]
 *
 * A TABLE CAPTION IS PRINTED, NOT DECORATION. Where a table holds a worked
 * example rather than measured data, the caption is what says so, and it sits
 * above the table where it cannot be scrolled past. See CLAIMS.md.
 *
 * Anything else is ignored rather than printed, so a typo in a post file
 * cannot put a stray array on the page.
 *
 * NO BYLINE PHOTOGRAPH AND NO PERSONAL AUTHOR ON THE DUMMY POSTS. The posts
 * are attributed to RankinAI. Attributing words nobody wrote to a named person,
 * even our own, is the same mistake as an invented client testimonial, and the
 * three posts currently in the section are placeholders. Real bylines come with
 * the real articles. See CLAIMS.md.
 *
 * REQUIRED KEYS
 *   slug                         must match a row in includes/posts.php
 *   stand                        the standfirst, one sentence or two
 *   body[]                       the blocks above
 *   author                       optional; defaults to RankinAI
 * Everything else — title, topic, date, reading time — is read from the index,
 * so a post's metadata lives in exactly one place.
 * =============================================================================
 */
/* WORDPRESS: ported 29 Sep 2026 from the flat build's includes/blog-template.php.
   The body comes from the post's fields (inc/models/post.php), the index row
   from rankinai_posts() (inc/models/_blog.php). */
global $SITE, $NAV, $FOOTER, $POSTS, $TOPICS;
$POST = rankinai_model_data() + array( 'slug' => get_post_field( 'post_name' ) );
if (!isset($POST)) {
    exit('blog-template.php requires $POST.');
}

$row = post_by_slug($POST['slug']);
if ($row === null) {
    status_header(404);
    exit('No published post for slug: ' . htmlspecialchars($POST['slug']));
}

$P      = array_merge($row, $POST);
$author = !empty($P['author']) ? $P['author'] : 'RankinAI';

/* The contents list is built from the article's own h2 blocks rather than
   written by hand, so it can never drift from the piece. Each heading gets an
   id from its text; a duplicate heading gets a suffix so the anchors stay
   unique. */
$slugify = function (string $t): string {
    $t = html_entity_decode(strip_tags($t), ENT_QUOTES, 'UTF-8');
    $t = mb_strtolower(trim($t));
    $t = preg_replace('/[^a-z0-9]+/', '-', $t);
    return trim($t, '-');
};
$toc  = [];
$seen = [];
foreach ($P['body'] as $b) {
    if (($b[0] ?? '') !== 'h2') continue;
    $id = $slugify($b[1]);
    if (isset($seen[$id])) { $seen[$id]++; $id .= '-' . $seen[$id]; } else { $seen[$id] = 1; }
    $toc[] = ['id' => $id, 'text' => $b[1]];
}
/* The render loop derives each heading's id from its own text with the same
   function and the same duplicate handling, rather than walking a counter
   alongside the list. A counter has to stay in step with a loop that also
   renders nine other block types; a slug cannot fall out of step with the
   heading it came from. */
$seenR = [];
$headId = function (string $t) use ($slugify, &$seenR): string {
    $id = $slugify($t);
    if (isset($seenR[$id])) { $seenR[$id]++; $id .= '-' . $seenR[$id]; } else { $seenR[$id] = 1; }
    return $id;
};

get_header();
?>

<article class="post-detail">

<!-- 01 — HERO ============================================================
     The topic, the date and the reading time on one line, then the title and
     the standfirst. No hero image: we do not have a picture library for the
     blog, and a stock photograph at the top of a piece about not using stock
     photography would be a poor start.
     ====================================================================== -->
<section class="hero hero--centred hero--post">
  <div class="hero__inner container">

    <p class="eyebrow"><span><?= $TOPICS[$P['topic']] ?> &middot; <?= post_date($P['date']) ?> &middot; <?= $P['mins'] ?> min read</span></p>

    <h1 class="hero__title"><?= $P['title'] ?></h1>

    <div class="hero__meta hero__meta--home">
      <p class="hero__sub"><?= $P['stand'] ?></p>
    </div>

  </div>

<?php /* The credit sits under the picture, where a reader meets it, with the
         referral parameters Unsplash's guidelines ask for. */ ?>
<?php if (!empty($P['img'])): ?>
  <figure class="posthero">
    <div class="container">
      <img src="<?= e(post_img($P, 1600, 800)) ?>" alt="<?= e($P['img']['alt']) ?>" width="1600" height="800" fetchpriority="high" decoding="async">
      <figcaption class="posthero__by">Photograph by <a href="<?= e(post_img_by_url($P)) ?>" rel="noopener"><?= e($P['img']['by']) ?></a> on <a href="<?= e(unsplash_url()) ?>" rel="noopener">Unsplash</a></figcaption>
    </div>
  </figure>
<?php endif; ?>
</section>


<!-- 02 — THE ARTICLE ====================================================
     One column at the reading measure. The pull quote and the takeaway box
     step outside it, which is the only thing that interrupts the column and
     the reason they read as emphasis.
     ====================================================================== -->
<section class="band band--light">
  <div class="container">

    <div class="longread">

<?php /* The rail. Sticky on a wide screen, and a plain block at the top of the
         page on a narrow one, where there is no room beside the text. */ ?>
      <aside class="rail">
<?php if (count($toc) > 1): ?>
        <nav class="toc" aria-label="On this page">
          <p class="label label--clay">On this page</p>
          <ol class="toc__list">
<?php foreach ($toc as $ti => $t): ?>
            <li><a href="#<?= e($t['id']) ?>" data-toc="<?= e($t['id']) ?>"><span class="toc__n"><?= sprintf('%02d', $ti + 1) ?></span><span class="toc__t"><?= $t['text'] ?></span></a></li>
<?php endforeach; ?>
          </ol>
        </nav>
<?php endif; ?>

        <?php /* Short on purpose. The full close is at the foot of the page;
                 this is the offer for someone who has read enough. */ ?>
        <div class="railcta">
          <p class="railcta__t">Want this looked at on your own site?</p>
          <p class="railcta__x">We&rsquo;ll review your visibility and enquiry journey and send the clearest opportunities in writing.</p>
          <a class="btn btn--primary btn--sm" href="<?= url('/growth-audit/') ?>">Get your growth audit <?= btn_arrow() ?></a>
          <a class="link-quiet link-quiet--bold" href="<?= url('/call/') ?>">Or book a 20-minute call</a>
        </div>
      </aside>

    <div class="article">
<?php foreach ($P['body'] as $block):
      $kind = $block[0] ?? '';
      $val  = $block[1] ?? '';
?>
<?php if ($kind === 'h2'): ?>
      <h2 class="article__h2" id="<?= e($headId($val)) ?>"><?= $val ?></h2>
<?php elseif ($kind === 'h3'): ?>
      <h3 class="article__h3"><?= $val ?></h3>
<?php elseif ($kind === 'p'): ?>
      <p class="article__p"><?= $val ?></p>
<?php elseif ($kind === 'note'): ?>
      <p class="article__note"><?= $val ?></p>
<?php elseif ($kind === 'list'): ?>
      <ul class="article__list" role="list">
<?php foreach ((array) $val as $li): ?>
        <li><?= $li ?></li>
<?php endforeach; ?>
      </ul>
<?php elseif ($kind === 'olist'): ?>
      <ol class="article__olist">
<?php foreach ((array) $val as $li): ?>
        <li><?= $li ?></li>
<?php endforeach; ?>
      </ol>
<?php elseif ($kind === 'table'): ?>
      <figure class="atable">
<?php if (!empty($val[0])): ?>
        <figcaption class="atable__cap"><?= $val[0] ?></figcaption>
<?php endif; ?>
        <div class="ctable-wrap">
          <table class="ctable">
            <thead>
              <tr>
<?php foreach ($val[1] as $hi => $h): ?>
                <th scope="col"<?= $hi ? ' class="ctable__num"' : '' ?>><?= $h ?></th>
<?php endforeach; ?>
              </tr>
            </thead>
            <tbody>
<?php foreach ($val[2] as $row): ?>
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
      </figure>
<?php elseif ($kind === 'img'): ?>
      <figure class="afig">
        <img src="<?= e('https://images.unsplash.com/' . $val[0] . '?' . http_build_query(['ixlib' => 'rb-4.1.0', 'auto' => 'format', 'fit' => 'crop', 'w' => 1400, 'h' => 700, 'q' => 70])) ?>" alt="<?= e($val[1]) ?>" width="1400" height="700" loading="lazy" decoding="async">
        <figcaption class="afig__by">Photograph by <a href="<?= e('https://unsplash.com/@' . $val[3] . '?utm_source=RankinAI&utm_medium=referral') ?>" rel="noopener"><?= e($val[2]) ?></a> on <a href="<?= e(unsplash_url()) ?>" rel="noopener">Unsplash</a></figcaption>
      </figure>
<?php elseif ($kind === 'quote'): ?>
      <blockquote class="pull"><p><?= $val ?></p></blockquote>
<?php elseif ($kind === 'take'): ?>
      <aside class="take">
        <p class="take__t"><?= $val[0] ?></p>
        <ul class="take__list" role="list">
<?php foreach ($val[1] as $li): ?>
          <li><?= $li ?></li>
<?php endforeach; ?>
        </ul>
      </aside>
<?php endif; ?>
<?php endforeach; ?>

      <p class="article__by">Written by <b><?= $author ?></b>. Published <time datetime="<?= e($P['date']) ?>"><?= post_date($P['date']) ?></time>.</p>
    </div>

    </div>
  </div>
</section>


<!-- 03 — RELATED READING ================================================= -->
<?php $related = posts_except($P['slug'], 4); ?>
<?php if ($related): ?>
<section class="band band--light">
  <div class="container">

    <div class="questions__head">
      <div>
        <p class="eyebrow"><span>Keep reading</span></p>
        <h2 class="questions__title">Four more worth your time.</h2>
      </div>
      <a class="link-quiet link-quiet--bold" href="<?= url('/blog/') ?>">Every article</a>
    </div>

    <div class="postgrid postgrid--four">
<?php foreach ($related as $r): ?>
      <article class="post">
<?php if (!empty($r['img'])): ?>
        <a class="post__img" href="<?= url('/blog/' . $r['slug'] . '/') ?>" tabindex="-1" aria-hidden="true">
          <img src="<?= e(post_img($r, 800, 480)) ?>" alt="" width="800" height="480" loading="lazy" decoding="async">
        </a>
<?php endif; ?>
        <p class="post__meta">
          <span class="label label--clay"><?= $TOPICS[$r['topic']] ?></span>
          <span class="post__mins"><?= $r['mins'] ?> min read</span>
        </p>
        <h3 class="post__title"><a href="<?= url('/blog/' . $r['slug'] . '/') ?>"><?= $r['title'] ?></a></h3>
        <p class="post__stand"><?= $r['stand'] ?></p>
      </article>
<?php endforeach; ?>
    </div>

  </div>
</section>
<?php endif; ?>

</article>

<?php
/* Structured data. Nothing here is asserted that is not on the page. */
$ld = [
    '@context'      => 'https://schema.org',
    '@type'         => 'BlogPosting',
    'headline'      => html_entity_decode(strip_tags($P['title']), ENT_QUOTES, 'UTF-8'),
    'description'   => html_entity_decode(strip_tags($P['stand']), ENT_QUOTES, 'UTF-8'),
    'datePublished' => $P['date'],
    'author'        => ['@type' => 'Organization', 'name' => html_entity_decode(strip_tags($author), ENT_QUOTES, 'UTF-8')],
    'publisher'     => ['@type' => 'Organization', 'name' => 'RankinAI'],
];
?>
<script type="application/ld+json"><?= json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>

<?php get_footer(); ?>
