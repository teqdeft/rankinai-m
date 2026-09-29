<?php
/**
 * The team section: a heading, a paragraph, and the photo marquee
 * -----------------------------------------------------------------------------
 * Shared by the homepage and every service page. It lived inline in index.php
 * until the service pages wanted it too.
 *
 * Nothing here is page-specific except $team_title and $team_lead, both
 * optional. Include it where the section should appear:
 *
 *     require __DIR__ . '/team.php';
 *
 * The section is a dark forest band. On the service pages it sits between two
 * light bands, which is the reason it works there: it breaks the long cream
 * run before the questions rather than adding a third pale section to it.
 *
 * THE PICTURES ARE IN team-marquee.php. They were split out on 25 Sep 2026
 * because the about page wants them without this heading above them. If you
 * only want the rows, require that file instead of this one and give whatever
 * wraps it overflow:hidden.
 */
$args       = isset( $args ) && is_array( $args ) ? $args : array();
$team_title = $args['title'] ?? null;
$team_lead  = $args['lead'] ?? '';
?>
<!-- ==========================================================================
     OUR TEAM
     Two rows drifting in opposite directions, paused under the cursor.
     ========================================================================== -->
<section class="team">
  <div class="container team__head">
    <div>
      <?php /* $team_title and $team_lead let a page override this. The home
               page does; the service pages take the default. */ ?>
      <h2 class="team__title"><?= $team_title ?? 'Our team' ?></h2>
    </div>
    <div class="team__lead">
<?php if (!empty($team_lead)): ?>
      <?= $team_lead ?>
<?php else: ?>
      <p>Specialists in search, paid advertising, content, websites and follow-up, working as one group rather than five departments. The same people who plan your quarter are the ones who ship it, and who sit on the monthly call explaining what moved.</p>
<?php endif; ?>
    </div>
  </div>

<?php get_template_part( 'template-parts/team-marquee' ); ?>
</section>
