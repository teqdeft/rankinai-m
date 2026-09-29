<?php
/**
 * The team photo marquee, on its own
 * -----------------------------------------------------------------------------
 * Two rows of photographs drifting in opposite directions, paused under the
 * cursor. Split out of team.php on 25 Sep 2026 because the about page wants
 * the pictures without the heading and the paragraph that sit above them on
 * the home page and the service pages. team.php requires this file, so there
 * is still one copy of the tile list and one place a photo changes.
 *
 *     require __DIR__ . '/team-marquee.php';
 *
 * It renders the rows and nothing else: no section, no band, no padding. The
 * page decides the ground it sits on. Whatever wraps it needs overflow:hidden,
 * or the rows will widen the document.
 *
 * Ten photographs from the team's own camera rolls, two of the whole group and
 * the rest of two or three people or the office at work, because ten line-ups
 * in a row read as a school photo. Graded to one look in assets/images (warm,
 * a little desaturated, blacks lifted) so phone photos from five different
 * evenings read as one set. Each row is the same five tiles twice, which is
 * what lets the drift loop seamlessly.
 *
 * The third column is a caption, kept as a record of what each photo shows. It
 * is not rendered: the chips were taken off the tiles because the photographs
 * read better without them. The alt text still carries the description for
 * anyone who cannot see the image, and only the first pass carries it, or a
 * screen reader announces all ten twice.
 *
 * Not lazy-loaded, on purpose: the rows drift sideways, so tiles enter the
 * viewport from the edges continuously, and a lazy image arriving late shows
 * as a blank tile sliding past. Ten files, about 800 KB in total.
 *
 * The drift itself is in script.js, bound to [data-marquee], and it does not
 * run at all under prefers-reduced-motion.
 */
$TEAM_ROWS = [
  'left' => [
    ['office',    560, 'The office, mid-afternoon',  'The office: rows of desks, people at their screens'],
    ['desks',     500, 'Heads down',                 'Colleagues working at desks in the office'],
    ['arcade',    400, 'Night out, arcade',          'Three colleagues with a costumed mascot at an arcade'],
    ['holi',      520, 'Holi',                       'The team outdoors, faces and clothes covered in Holi colour'],
    ['desk',      420, 'Quiet corner, one screen',   'A colleague at a laptop in front of a world map'],
  ],
  'right' => [
    ['group',     560, 'Most of the team',           'The team standing together in the office'],
    ['ropes',     380, 'Team day out, high up',      'Two colleagues in helmets crossing a high rope course'],
    ['longtable', 480, 'Dinner, the long table',     'The team along a long restaurant table'],
    ['talking',   400, 'Mid-conversation',           'Two colleagues talking in the office, colour on their shirts from Holi'],
    ['nightout',  440, 'Night out',                  'Four colleagues outside a shop at night'],
  ],
];
?>
  <div class="marquees">
<?php foreach ($TEAM_ROWS as $dir => $tiles): ?>
    <div class="marquee" data-marquee="<?= $dir ?>">
      <div class="marquee__track" data-track>
<?php for ($pass = 0; $pass < 2; $pass++): foreach ($tiles as [$file, $w, $cap, $alt]): ?>
          <div class="tile tile--photo" style="--tile-w:<?= $w ?>px">
            <img src="<?= asset("images/team-$file.webp") ?>" alt="<?= $pass ? '' : e($alt) ?>" width="<?= $w * 2 ?>" height="600" decoding="async"<?= $pass ? ' aria-hidden="true"' : '' ?>>
          </div>
<?php endforeach; endfor; ?>
      </div>
    </div>
<?php endforeach; ?>
  </div>
