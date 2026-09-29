<?php
/**
 * The close
 * -----------------------------------------------------------------------------
 * One closing section, the same on every page. It used to be written out by
 * hand in fourteen files, with eleven different paragraphs, three different
 * primary buttons and two pages carrying an inline form instead of a card. A
 * reader moving through the site met a different ask each time.
 *
 * Settled 25 Sep 2026 as: the about page's copy, in the home page's layout,
 * with the home page's card and buttons.
 *
 *     heading    Tell us about the work you want more of.
 *     paragraph  A particular kind of client. Larger projects. …
 *     card       the growth audit, and the four things it promises
 *     primary    Get your growth audit
 *     secondary  Or book a 20-minute call
 *
 * footer.php requires this file, so a page gets it without asking and a new
 * page cannot forget it.
 *
 * THE FOUR LINES IN THE CARD are lifted word for word from /growth-audit/.
 * Nothing here is a claim of its own, and the two cannot drift apart. If that
 * page's promises change, change them here in the same commit.
 *
 * WHAT A PAGE MAY OVERRIDE. In the theme the close is printed by footer.php.
 * A page hides it with its "Hide the closing section" setting. A template
 * that needs other buttons passes them in $args:
 *
 *   get_template_part( 'template-parts/close', null, array(
 *     'btn'  => array( 'Fill in the form', '#audit-form' ),
 *     'link' => array( 'Or pick a time above', '#book' ),
 *   ) );
 *
 * Two pages use the first two, and only because the standard link would point
 * at the page the reader is already on: /growth-audit/ and /call/. That is the
 * whole list of acceptable reasons to override. A page that wants its own
 * wording does not get one.
 */
$args = isset( $args ) && is_array( $args ) ? $args : array();
$btn  = $args['btn']  ?? ['Get your growth audit',     '/growth-audit/'];
$link = $args['link'] ?? ['Or book a 20-minute call',  '/call/'];

/* An anchor stays an anchor; anything else goes through url() for the subshell
   prefix. */
$href = static fn(string $h): string => $h[0] === '#' ? e($h) : url($h);
?>
<!-- ==========================================================================
     THE CLOSE
     Shared by every page. See includes/close.php before changing anything.
     ========================================================================== -->
<section class="close">
  <div class="container close__inner close__inner--ask">

    <div class="close__left">
      <h2 class="close__tagline close__tagline--ask">Tell us about the work you want more of.</h2>
      <p class="close__text">A particular kind of client. Larger projects. A new service. A market you haven&rsquo;t reached yet. We&rsquo;ll start there, and look at how your marketing could help you get closer.</p>
    </div>

    <div class="close__card">
      <p class="label label--onclay">Your growth audit</p>
      <ul class="ticks ticks--oncard" role="list">
        <li>Free, and it stays free</li>
        <li>Back within one working day</li>
        <li>Written by a person, not generated</li>
        <li>No call booked on your behalf</li>
      </ul>
      <div class="close__actions">
        <a class="btn btn--cream btn--lg" href="<?= $href($btn[1]) ?>"><?= $btn[0] ?> <?= btn_arrow() ?></a>
        <a class="link-quiet link-quiet--onforest" href="<?= $href($link[1]) ?>"><?= $link[0] ?></a>
      </div>
    </div>

  </div>
</section>
