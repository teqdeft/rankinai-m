<?php
/**
 * Template Name: Blog page
 *
 * The blog index. The flat build's blog.php: topic filtering and paging are
 * query-string state (?topic=, ?page=, and ?q= still read), so nothing depends
 * on JavaScript and every view has its own URL. The articles are WordPress
 * posts, read through rankinai_posts() (inc/models/_blog.php); the page's own
 * words are its fields (inc/models/page-blog.php).
 */
global $SITE, $NAV, $FOOTER, $POSTS, $TOPICS;
$D = rankinai_model_data();

$active = isset($_GET['topic']) && isset($TOPICS[$_GET['topic']]) ? $_GET['topic'] : '';
$q      = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
$page   = max(1, (int) ($_GET['page'] ?? 1));

/* Four to a page, so the "load more" state is visible with seven posts. */
$PER_PAGE = 4;

$found = $active
    ? array_values(array_filter($POSTS, function ($p) use ($active) { return $p['topic'] === $active; }))
    : $POSTS;
$found = posts_search($found, $q);

$feature   = ($active === '' && $q === '' && $page === 1) ? post_featured() : null;
$listing   = $feature
    ? array_values(array_filter($found, function ($p) use ($feature) { return $p['slug'] !== $feature['slug']; }))
    : $found;

$total   = count($listing);
$pages   = max(1, (int) ceil($total / $PER_PAGE));
$page    = min($page, $pages);
$shown   = array_slice($listing, ($page - 1) * $PER_PAGE, $PER_PAGE);
$hasMore = $page < $pages;

$qs = function (array $over = []) use ($active, $q, $page) {
    $parts = array_filter([
        'topic' => $over['topic'] ?? $active,
        'q'     => $over['q']     ?? $q,
        'page'  => $over['page']  ?? null,
    ], function ($v) { return $v !== '' && $v !== null; });
    return $parts ? '?' . http_build_query($parts) : '';
};

/* The title follows the view, as on the flat build. */
if ($q !== '' || $active) {
    add_filter('pre_get_document_title', function () use ($q, $active, $TOPICS) {
        return ($q !== '' ? 'Search results' : $TOPICS[$active]) . ' | RankinAI';
    }, 20);
}

$used = array_unique(array_column((array) $POSTS, 'topic'));

get_header();
?>

<!-- 01 — HERO ============================================================ -->
<section class="hero hero--centred hero--short">
  <div class="hero__inner container">

    <p class="eyebrow"><span><?= $D['eyebrow'] ?? '' ?></span></p>

    <h1 class="hero__title"><?= $D['h1'] ?? '' ?></h1>

  </div>
</section>


<!-- 02 — FILTERS ========================================================= -->
<section class="band band--light band--topics">
  <div class="container">

    <div class="finder finder--solo">
      <nav class="browse" aria-label="Browse articles by topic">
        <p class="label label--clay"><?= $D['browse'] ?? '' ?></p>
        <div class="filters">
          <a class="filter<?= $active === '' ? ' is-on' : '' ?>" href="<?= url('/blog/') . $qs(['topic' => '']) ?>"<?= $active === '' ? ' aria-current="true"' : '' ?>><?= $D['all'] ?? '' ?></a>
<?php foreach ($TOPICS as $key => $name): ?>
<?php if (!in_array($key, $used, true)) continue; ?>
          <a class="filter<?= $active === $key ? ' is-on' : '' ?>" href="<?= url('/blog/') . $qs(['topic' => $key]) ?>"<?= $active === $key ? ' aria-current="true"' : '' ?>><?= $name ?></a>
<?php endforeach; ?>
        </div>
      </nav>
    </div>

  </div>
</section>


<?php if ($feature): ?>
<!-- 03 — THE FEATURED ARTICLE ============================================ -->
<section class="band band--light">
  <div class="container">

    <article class="feature feature--light<?= !empty($feature['img']) ? ' feature--img' : '' ?>">
<?php if (!empty($feature['img'])): ?>
      <a class="feature__img" href="<?= url('/blog/' . $feature['slug'] . '/') ?>" tabindex="-1" aria-hidden="true">
        <img src="<?= e(post_img($feature, 1000, 750)) ?>" alt="" width="1000" height="750" loading="lazy" decoding="async">
      </a>
<?php endif; ?>
      <div class="feature__say">
      <p class="feature__flags">
        <span class="label label--clay"><?= $D['featured'] ?? '' ?></span>
        <span class="label"><?= $TOPICS[$feature['topic']] ?></span>
      </p>
      <h2 class="feature__title"><a href="<?= url('/blog/' . $feature['slug'] . '/') ?>"><?= $feature['title'] ?></a></h2>
      <p class="feature__stand"><?= $feature['stand'] ?></p>
      <p class="feature__meta"><time datetime="<?= e($feature['date']) ?>"><?= post_date($feature['date']) ?></time> &middot; <?= $feature['mins'] ?> min read</p>
      <a class="btn btn--primary" href="<?= url('/blog/' . $feature['slug'] . '/') ?>"><?= $D['read'] ?? '' ?> <?= btn_arrow() ?></a>
      </div>
    </article>

  </div>
</section>
<?php endif; ?>


<!-- 04 — THE LISTING ===================================================== -->
<section class="band band--light">
  <div class="container">

    <div class="questions__head">
      <div>
<?php if ($q !== ''): ?>
        <p class="eyebrow"><span>Search results</span></p>
        <h2 class="questions__title">Results for &ldquo;<?= e($q) ?>&rdquo;</h2>
        <p class="stories__note"><?= $total ?> <?= $total === 1 ? 'article' : 'articles' ?> found.</p>
<?php elseif ($active): ?>
        <p class="eyebrow"><span><?= $TOPICS[$active] ?></span></p>
        <h2 class="questions__title"><?= $D['listTitle'] ?? '' ?></h2>
<?php else: ?>
        <p class="eyebrow"><span><?= $D['listEyebrow'] ?? '' ?></span></p>
        <h2 class="questions__title"><?= $D['listTitle'] ?? '' ?></h2>
<?php endif; ?>
      </div>
    </div>

<?php if ($shown): ?>
    <div class="postgrid">
<?php foreach ($shown as $p): ?>
      <article class="post">
<?php if (!empty($p['img'])): ?>
        <a class="post__img" href="<?= url('/blog/' . $p['slug'] . '/') ?>" tabindex="-1" aria-hidden="true">
          <img src="<?= e(post_img($p, 800, 480)) ?>" alt="" width="800" height="480" loading="lazy" decoding="async">
        </a>
<?php endif; ?>
        <p class="post__meta">
          <span class="label label--clay"><?= $TOPICS[$p['topic']] ?></span>
          <span class="post__mins"><?= $p['mins'] ?> min read</span>
        </p>
        <h3 class="post__title"><a href="<?= url('/blog/' . $p['slug'] . '/') ?>"><?= $p['title'] ?></a></h3>
        <p class="post__stand"><?= $p['stand'] ?></p>
        <p class="post__more"><a class="link-quiet link-quiet--bold" href="<?= url('/blog/' . $p['slug'] . '/') ?>"><?= $D['read'] ?? '' ?></a></p>
      </article>
<?php endforeach; ?>
    </div>

    <?php /* Unsplash's guidelines want the photographer credited. Each article
             carries its own credit under the picture; this line covers the
             thumbnails, which are too small to hold one each. */ ?>
    <p class="aftercards"><?= $D['credit'] ?? '' ?> <a class="link-quiet" href="<?= e(unsplash_url()) ?>" rel="noopener">Unsplash</a>.</p>

<?php if ($hasMore): ?>
    <p class="loadmore">
      <a class="btn btn--outline" href="<?= url('/blog/') . $qs(['page' => $page + 1]) ?>"><?= $D['more'] ?? '' ?> <?= btn_arrow() ?></a>
    </p>
<?php elseif ($pages > 1): ?>
    <p class="loadmore">
      <a class="link-quiet link-quiet--bold" href="<?= url('/blog/') . $qs(['page' => null]) ?>"><?= $D['back'] ?? '' ?></a>
    </p>
<?php endif; ?>

<?php else: ?>
    <div class="noresults">
      <p class="noresults__t"><?= $D['noneTitle'] ?? '' ?></p>
      <p class="noresults__x"><?= $D['noneText'] ?? '' ?></p>
      <a class="btn btn--outline" href="<?= url('/blog/') ?>"><?= $D['noneButton'] ?? '' ?> <?= btn_arrow() ?></a>
    </div>
<?php endif; ?>

  </div>
</section>

<?php get_footer(); ?>
