/* ==========================================================================
   RankinAI — script.js

   Behaviour ported from the "Homepage v3" design canvas. Two deliberate
   departures, both improvements on a real page:

   1. The canvas pins the header with a JS transform on every animation
      frame, because its host scrolls by transforming a wrapper and CSS
      sticky has no scroll range there. Here the header is position:sticky,
      so that code is gone entirely — same result, no work per frame.

   2. Panels open on hover on the desktop, exactly as designed, but they
      also open on click and on keyboard focus. Hover alone locks out
      anyone using a keyboard or a touchscreen.
   ========================================================================== */

(function () {
  'use strict';

  var DESKTOP = '(min-width: 901px)';
  var CLOSE_DELAY = 140;   /* matches the canvas */

  var body        = document.body;
  var navToggle   = document.querySelector('[data-nav-toggle]');
  var nav         = document.querySelector('[data-nav]');
  var triggers    = Array.prototype.slice.call(document.querySelectorAll('[data-panel-trigger]'));
  var closeTimer  = null;

  function isDesktop() {
    return window.matchMedia(DESKTOP).matches;
  }

  function panelFor(trigger) {
    var id = trigger.getAttribute('aria-controls');
    return id ? document.getElementById(id) : null;
  }

  /* ------------------------------------------------------------------------
     Panels
     ------------------------------------------------------------------------ */
  function closeAll(except) {
    triggers.forEach(function (t) {
      if (t === except) return;
      var panel = panelFor(t);
      if (panel) panel.classList.remove('is-open');
      t.setAttribute('aria-expanded', 'false');
    });
  }

  function openPanel(trigger) {
    var panel = panelFor(trigger);
    if (!panel) return;
    closeAll(trigger);
    panel.classList.add('is-open');
    trigger.setAttribute('aria-expanded', 'true');
  }

  function closePanel(trigger) {
    var panel = panelFor(trigger);
    if (panel) panel.classList.remove('is-open');
    trigger.setAttribute('aria-expanded', 'false');
  }

  function togglePanel(trigger) {
    if (trigger.getAttribute('aria-expanded') === 'true') {
      closePanel(trigger);
    } else {
      openPanel(trigger);
    }
  }

  function arm()    { window.clearTimeout(closeTimer); }
  function disarm() {
    window.clearTimeout(closeTimer);
    closeTimer = window.setTimeout(function () { closeAll(); }, CLOSE_DELAY);
  }

  triggers.forEach(function (trigger) {
    var panel = panelFor(trigger);

    /* Desktop: hover opens, and the panel keeps itself open underneath */
    trigger.addEventListener('mouseenter', function () {
      if (!isDesktop()) return;
      arm();
      openPanel(trigger);
    });
    trigger.addEventListener('mouseleave', function () {
      if (!isDesktop()) return;
      disarm();
    });

    if (panel) {
      panel.addEventListener('mouseenter', function () { if (isDesktop()) arm(); });
      panel.addEventListener('mouseleave', function () { if (isDesktop()) disarm(); });
    }

    /* Click works everywhere — the accordion on small screens, and a
       keyboard or touch route into the panels on large ones */
    trigger.addEventListener('click', function (e) {
      e.preventDefault();
      arm();
      togglePanel(trigger);
    });

    /* Keyboard: opening on focus would be hostile while tabbing past, so
       the panel closes when focus leaves it entirely instead */
    if (panel) {
      panel.addEventListener('focusin',  function () { arm(); openPanel(trigger); });
      panel.addEventListener('focusout', function (e) {
        if (!panel.contains(e.relatedTarget) && e.relatedTarget !== trigger) {
          closePanel(trigger);
        }
      });
    }
  });

  /* Click outside, or Escape, closes everything */
  document.addEventListener('click', function (e) {
    if (!e.target.closest('[data-panel-trigger]') && !e.target.closest('[data-panel]')) {
      closeAll();
    }
  });

  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;

    var open = triggers.filter(function (t) {
      return t.getAttribute('aria-expanded') === 'true';
    });
    if (open.length) {
      closeAll();
      open[0].focus();
      return;
    }
    if (nav && nav.classList.contains('is-open')) closeSheet();
  });

  /* ------------------------------------------------------------------------
     The mobile sheet
     ------------------------------------------------------------------------ */
  function openSheet() {
    nav.classList.add('is-open');
    navToggle.setAttribute('aria-expanded', 'true');
    body.classList.add('nav-locked');
    document.dispatchEvent(new CustomEvent('rankinai:scroll-lock'));
  }

  function closeSheet() {
    nav.classList.remove('is-open');
    navToggle.setAttribute('aria-expanded', 'false');
    body.classList.remove('nav-locked');
    closeAll();
    navToggle.focus();
    document.dispatchEvent(new CustomEvent('rankinai:scroll-unlock'));
  }

  if (navToggle && nav) {
    navToggle.addEventListener('click', function () {
      if (nav.classList.contains('is-open')) {
        closeSheet();
      } else {
        openSheet();
      }
    });

    /* Following a link should not leave the sheet hanging open behind it */
    nav.addEventListener('click', function (e) {
      var link = e.target.closest('a');
      if (link && !isDesktop()) closeSheet();
    });
  }

  /* Crossing the breakpoint with the sheet open would strand it ----------- */
  var mq = window.matchMedia(DESKTOP);
  var onChange = function () {
    closeAll();
    if (nav) nav.classList.remove('is-open');
    if (navToggle) navToggle.setAttribute('aria-expanded', 'false');
    body.classList.remove('nav-locked');
  };
  if (mq.addEventListener) {
    mq.addEventListener('change', onChange);
  } else if (mq.addListener) {
    mq.addListener(onChange);
  }
})();


/* ==========================================================================
   RankinAI — section behaviour
   Ported from the design canvas, with the same three interactions:
     · the services list drives the pinned column beside it
     · the four-step method swaps its schematic and its copy
     · the questions list drives the answer beside it
   Plus the two team rows drifting in opposite directions.
   ========================================================================== */

(function () {
  'use strict';

  var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ------------------------------------------------------------------------
     Services — the reading line
     A service becomes active the moment its top rule crosses a line just
     under the pinned column's verb, and stays active until the next one
     does. Same order the eye reads in.
     ------------------------------------------------------------------------ */
  var rows   = [].slice.call(document.querySelectorAll('[data-svc]'));
  var shots  = [].slice.call(document.querySelectorAll('[data-img]'));
  var verbs  = [].slice.call(document.querySelectorAll('[data-verb]'));
  var active = -1;
  var queued = false;

  function readServices() {
    if (!rows.length) return;

    /* Each row hands over to the next as soon as its own top slides under
       the header. Rows in this list are nearly a full screen tall, so that
       works out at one active service per screen of scrolling, and the row
       being read is the one that is lit.

       It used to be "the last row whose top has crossed a line 80% down the
       screen". Because a row is taller than that line is far down, row one
       stayed lit until it had entirely cleared the top of the viewport.

       So: count the rows that have left, and that count is the index of the
       one now in place. Nothing has left yet means row one, which is why it
       is lit from the start. */
    var headerH = parseFloat(
      getComputedStyle(document.documentElement).getPropertyValue('--header-h')
    ) || 0;
    var best = 0;

    rows.forEach(function (row) {
      if (row.getBoundingClientRect().top <= headerH) best += 1;
    });
    best = Math.min(best, rows.length - 1);
    if (best === active) return;
    active = best;

    rows.forEach(function (row, i)  { row.classList.toggle('is-active', i === best); });
    shots.forEach(function (shot)   {
      shot.style.opacity = Number(shot.getAttribute('data-img')) === best ? '1' : '0';
    });

    /* The verb swaps once the list crosses into "Get booked." */
    var job = best < 3 ? 0 : 1;
    verbs.forEach(function (v) {
      v.style.opacity = Number(v.getAttribute('data-verb')) === job ? '1' : '0';
    });
  }

  function onScroll() {
    if (queued) return;
    queued = true;
    window.requestAnimationFrame(function () {
      queued = false;
      readServices();
    });
  }

  if (rows.length) {
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
    readServices();
  }

  /* ------------------------------------------------------------------------
     A small tab controller — used by both the method steps and the questions
     ------------------------------------------------------------------------ */
  function tabs(triggerSel, panelSel, panelAttr, autoMs) {
    var buttons = [].slice.call(document.querySelectorAll(triggerSel));
    var panels  = [].slice.call(document.querySelectorAll(panelSel));
    if (!buttons.length) return;

    var timer = null;
    var current = 0;

    function show(n) {
      current = n;
      buttons.forEach(function (b, i) {
        b.setAttribute('aria-selected', i === n ? 'true' : 'false');
      });
      panels.forEach(function (p) {
        p.classList.toggle('is-on', Number(p.getAttribute(panelAttr)) === n);
      });
    }

    function stop() {
      if (timer) { window.clearInterval(timer); timer = null; }
    }

    buttons.forEach(function (b, i) {
      b.addEventListener('mouseenter', function () { stop(); show(i); });
      b.addEventListener('focus',      function () { stop(); show(i); });
      b.addEventListener('click',      function () { stop(); show(i); });
    });

    show(0);

    /* Auto-advance, but never against someone who asked for less motion */
    if (autoMs && !reduced) {
      timer = window.setInterval(function () {
        show((current + 1) % buttons.length);
      }, autoMs);
    }
  }

  /* 6.5s, not 4.2: each step's schematic now plays a sequence, and the
     visitor should see it finish before the next one starts. */
  tabs('[data-step]', '[data-panel]', 'data-panel', 6500);
  tabs('[data-step]', '[data-copy]',  'data-copy',  0);
  tabs('[data-fq]',   '[data-fa]',    'data-fa',    0);

  /* Keep the method copy in step with the schematic when it auto-advances */
  var stepBtns = [].slice.call(document.querySelectorAll('[data-step]'));
  var copies   = [].slice.call(document.querySelectorAll('[data-copy]'));
  if (stepBtns.length && copies.length) {
    new MutationObserver(function () {
      stepBtns.forEach(function (b, i) {
        if (b.getAttribute('aria-selected') !== 'true') return;
        copies.forEach(function (c) {
          c.classList.toggle('is-on', Number(c.getAttribute('data-copy')) === i);
        });
      });
    }).observe(stepBtns[0].parentNode, {
      subtree: true, attributes: true, attributeFilter: ['aria-selected']
    });
  }

  /* ------------------------------------------------------------------------
     Team — two rows drifting in opposite directions, paused under the cursor.
     The track holds the tile set twice, so the animation can run from one
     set-width to zero and loop without a visible seam.
     ------------------------------------------------------------------------ */
  if (!reduced) {
    [].slice.call(document.querySelectorAll('[data-marquee]')).forEach(function (row) {
      var track = row.querySelector('[data-track]');
      if (!track || !track.animate) return;

      var kids = [].slice.call(track.children);
      var gap  = 20;
      var half = kids.length / 2;
      var setW = gap * half;

      kids.slice(0, half).forEach(function (k) {
        setW += k.getBoundingClientRect().width;
      });

      var right = row.getAttribute('data-marquee') === 'right';
      var from  = right ? -setW : 0;
      var to    = right ? 0 : -setW;

      var anim = track.animate(
        [{ transform: 'translateX(' + from + 'px)' },
         { transform: 'translateX(' + to + 'px)' }],
        { duration: (setW / 42) * 1000, iterations: Infinity, easing: 'linear' }
      );

      row.addEventListener('mouseenter', function () { anim.pause(); });
      row.addEventListener('mouseleave', function () { anim.play(); });
    });
  }
})();


/* ==========================================================================
   RankinAI — motion

   Restraint is the brand, so the whole vocabulary here is three moves:
   a short rise, a fade, and a rule drawing itself. Nothing flies, nothing
   bounces, nothing scales. Distances are 12–24px and durations 0.6–1.0s.

   TWO RULES THAT KEEP THIS SAFE

   1. Never animate opacity on anything whose opacity is already state.
      .svc, .step and .fq are dimmed and lit by the sections above; the
      answers, schematic panels and service shots are cross-faded. Touching
      their opacity here would fight that and leave elements stuck. Those
      get position only.

   2. Only the first screen is pre-hidden in CSS. Everything else is
      revealed with gsap.from(), which needs no initial state — so if this
      file or the CDN fails, the page is simply static rather than blank.
   ========================================================================== */

/* ==========================================================================
   RankinAI — the leads
   Builds the lead tiles for the hero scene and, with motion off or GSAP
   missing, leaves them parked along the lanes. The motion block further
   down picks them up from here and moves them. Runs always.

   A tile is drawn 100 units wide, anchored bottom-centre at 0,0, and then
   translated and scaled into place — the scale is what does the
   perspective: a tile at the foot of the lane is bigger than one at the
   gate, by the same proportion the painted tiles were.
   ========================================================================== */
(function () {
  'use strict';

  var scene = document.querySelector('[data-scene]');
  if (!scene) return;
  var host  = scene.querySelector('[data-leads]');
  var lanes = [].slice.call(scene.querySelectorAll('[data-lane]'));
  if (!host || !lanes.length) return;

  var NS = 'http://www.w3.org/2000/svg';
  function el(name, attrs, cls) {
    var n = document.createElementNS(NS, name);
    for (var k in attrs) n.setAttribute(k, attrs[k]);
    if (cls) n.setAttribute('class', cls);
    return n;
  }

  /* Tile width, in plate pixels, as a function of where it stands. Read
     off the painted tiles: 153 wide at y=974, 119 at y=660. */
  function widthAt(y) { return 25 + 0.13 * y; }

  function makeTile() {
    var g = el('g', {}, 'lead');
    g.appendChild(el('ellipse', { cx: 6, cy: 3, rx: 48, ry: 8 }, 'lead__shadow'));

    /* Visitor: cream */
    var v = el('g', {}, 'lead__state lead__state--v');
    v.appendChild(el('rect', { x: -45, y: -95, width: 100, height: 100, rx: 17 }, 'lead__side--v'));
    v.appendChild(el('rect', { x: -50, y: -100, width: 100, height: 100, rx: 17 }, 'lead__face--v'));
    v.appendChild(el('circle', { cx: 0, cy: -63, r: 13 }, 'lead__icon--v'));
    v.appendChild(el('path', { d: 'M-25,-22 C-25,-42 25,-42 25,-22 Z' }, 'lead__icon--v'));

    /* Lead: green, with the tick */
    var q = el('g', {}, 'lead__state lead__state--q');
    q.appendChild(el('rect', { x: -45, y: -95, width: 100, height: 100, rx: 17 }, 'lead__side'));
    q.appendChild(el('rect', { x: -50, y: -100, width: 100, height: 100, rx: 17 }, 'lead__face'));
    q.appendChild(el('circle', { cx: 0, cy: -63, r: 13 }, 'lead__icon'));
    q.appendChild(el('path', { d: 'M-25,-22 C-25,-42 25,-42 25,-22 Z' }, 'lead__icon'));
    var badge = el('g', {}, 'lead__badgewrap');
    badge.appendChild(el('circle', { cx: 38, cy: -12, r: 14 }, 'lead__badge'));
    badge.appendChild(el('path', { d: 'M31,-12 l5,5 l10,-11' }, 'lead__tick'));
    q.appendChild(badge);

    g.appendChild(v); g.appendChild(q);
    host.appendChild(g);
    return { g: g, v: v, q: q, badge: badge };
  }

  /* Where a lead is at progress t along its lane, and how big. */
  function place(lead, t) {
    var lane = lanes[lead.lane];
    var len  = lane.getTotalLength();
    var pt   = lane.getPointAtLength(Math.max(0, Math.min(1, t)) * len);
    var k    = widthAt(pt.y) / 100;
    lead.g.setAttribute('transform', 'translate(' + pt.x.toFixed(1) + ',' + pt.y.toFixed(1) + ') scale(' + k.toFixed(3) + ')');
    return pt;
  }

  /* Two per lane. Progress and stagger belong to the motion block; here they
     are just parked so the scene is complete without it. */
  var QUAL = 0.42;   /* the moment a visitor becomes a lead */
  var leads = [
    { lane: 0, t: 0.16 }, { lane: 0, t: 0.58 },
    { lane: 1, t: 0.30 }, { lane: 1, t: 0.80 }
  ].map(function (spec) {
    var tile = makeTile();
    tile.lane = spec.lane; tile.t = spec.t;
    place(tile, spec.t);
    var isLead = spec.t >= QUAL;
    tile.v.style.opacity = isLead ? 0 : 1;
    tile.q.style.opacity = isLead ? 1 : 0;
    if (!isLead) tile.badge.setAttribute('transform', 'scale(0)');
    tile.badge.setAttribute('transform-origin', '38 -12');
    return tile;
  });

  /* Below 1150 the scene is a full-width band, and the gate should sit in
     the middle of it rather than at the right edge. */
  function align() {
    scene.setAttribute('preserveAspectRatio', window.innerWidth <= 1150 ? 'xMidYMid slice' : 'xMaxYMid slice');
  }
  align();
  window.addEventListener('resize', align);

  /* A sharper plate on big, dense screens. The 1920 one is in the markup so
     it starts loading before this runs. */
  var plate = scene.querySelector('[data-plate]');
  if (plate && window.innerWidth * (window.devicePixelRatio || 1) > 2100) {
    plate.setAttribute('href', plate.getAttribute('href').replace('hero-bg-1920', 'hero-bg-2560'));
  }

  scene.__leads = { leads: leads, place: place, lanes: lanes, QUAL: QUAL };
})();


(function () {
  'use strict';

  var root    = document.documentElement;
  var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* Failsafe: if GSAP has not arrived, un-hide the first screen and stop. */
  if (reduced || typeof window.gsap === 'undefined') {
    root.classList.remove('anim');
    return;
  }

  var gsap = window.gsap;
  var ST   = window.ScrollTrigger;
  if (ST) gsap.registerPlugin(ST);

  var EASE = 'power3.out';
  var RISE = 20;

  /* Reveal defaults. "top 82%" means a section starts as its top third
     enters the viewport — early enough that nothing is ever caught
     mid-animation, late enough to read as a response to scrolling. */
  /* Elements already handed to reveal(). See the guard below. */
  var revealed = (typeof WeakSet === 'function') ? new WeakSet() : null;

  function reveal(targets, opts) {
    opts = opts || {};
    if (!targets || (targets.length === 0)) return;

    /* THE GUARD, and why it exists.
       gsap.from() records the element's CURRENT state as the "from" state.
       So a second from() on the same element records opacity 0 — the state
       the first one just set — and animates 0 to 0. The element is then
       invisible for good, with no error anywhere.

       It is an easy mistake to make: a page-specific rule like
       '.hows--onforest .how' overlaps a general one like '.how', and only
       that page breaks. Registering twice is now simply ignored. */
    if (revealed) {
      targets = [].slice.call(targets).filter(function (el) {
        if (revealed.has(el)) return false;
        revealed.add(el);
        return true;
      });
      if (!targets.length) return;
    }

    var vars = {
      y: opts.y === undefined ? RISE : opts.y,
      duration: opts.duration || 0.8,
      ease: EASE,
      stagger: opts.stagger || 0,
      scrollTrigger: ST ? {
        trigger: opts.trigger || targets[0] || targets,
        start: opts.start || 'top 82%',
        once: true
      } : undefined
    };

    /* opts.fade === false for anything whose opacity is already state */
    if (opts.fade !== false) vars.opacity = 0;

    gsap.from(targets, vars);
  }

  function $(sel, ctx) { return (ctx || document).querySelector(sel); }
  function $$(sel, ctx) { return [].slice.call((ctx || document).querySelectorAll(sel)); }

  /* ------------------------------------------------------------------------
     The eyebrow rule draws itself as its section arrives
     ------------------------------------------------------------------------ */
  $$('.eyebrow').forEach(function (el, i) {
    if (!ST) { el.classList.add('is-in'); return; }
    ST.create({
      trigger: el,
      start: 'top 88%',
      once: true,
      onEnter: function () {
        gsap.delayedCall(i === 0 ? 0.35 : 0.1, function () { el.classList.add('is-in'); });
      }
    });
  });

  /* ------------------------------------------------------------------------
     Hero — the only sequence on the page that runs on load
     ------------------------------------------------------------------------ */
  var tl = gsap.timeline({ defaults: { ease: EASE, duration: 0.9 } });

  /* clearProps is not tidiness — it is load-bearing.

     GSAP leaves an inline transform on an element after a tween, even an
     identity one. Any transform makes an element the containing block for
     its position:fixed descendants, and the mobile menu is a fixed, inset:0
     child of this panel. Left alone, the menu resolved against the 375x102
     header instead of the viewport: it opened as a sliver behind the page.
     Clearing the transform when the slide finishes hands the viewport back. */
  tl.to('.site-header__panel', { opacity: 1, duration: 0.6 })
    .from('.site-header__panel', { y: -10, duration: 0.6, clearProps: 'transform' }, '<')
    .to('.hero .eyebrow', { opacity: 1, duration: 0.7 }, 0.15)
    .from('.hero .eyebrow', { y: 12, duration: 0.7 }, '<')
    .to('.hero__title', { opacity: 1 }, 0.28)
    .from('.hero__title', { y: 24 }, '<')
    .to('.hero__meta', { opacity: 1, duration: 0.8 }, 0.46)
    .from('.hero__meta', { y: 16, duration: 0.8 }, '<')
    .to('.hero__proof', { opacity: 1, duration: 0.8 }, 0.58)
    .from('.hero__proof', { y: 16, duration: 0.8 }, '<');

  /* The pre-hide in CSS is `.anim .hero__inner > *` — every direct child,
     whatever it is. The sequence above names only the three the homepage
     happens to have, so any page whose hero is built differently would be
     left at opacity 0 with no way back.

     The growth audit page is the first: its lede wraps the eyebrow and the
     title inside .audit-lede__copy, so those two are grandchildren and the
     wrapper itself is what stays hidden. Fading the grandchildren cannot
     rescue it — opacity multiplies down the tree.

     So rather than name the new blocks here and hit this again on the next
     page, anything the sequence did not reach is swept up and faded on the
     same beat as .hero__meta. */
  var namedInHero = $$('.hero .eyebrow, .hero__title, .hero__meta');
  var unnamed = $$('.hero__inner > *').filter(function (el) {
    return namedInHero.indexOf(el) === -1;
  });

  if (unnamed.length) {
    tl.to(unnamed, { opacity: 1, duration: 0.8, stagger: 0.1 }, 0.28)
      .from(unnamed, { y: 18, duration: 0.8, stagger: 0.1 }, '<');
  }

  /* The hero scene fades with the header, a touch before the copy. */
  if ($('.hero__bg')) tl.to('.hero__bg', { opacity: 1, duration: 1.0 }, 0.1);

  /* ------------------------------------------------------------------------
     The leads — homepage hero

     Four tiles, two per lane, each on its own clock. A tile enters at the
     foot of its lane as a cream visitor, travels the path, and at QUAL
     turns green and gets its tick — that is the moment it becomes a lead.
     It shrinks with the perspective as it goes, and at the top of the lane
     it passes into the gate: it fades as the gate glows. Then it is sent
     back to the start after a pause, so there is always a tile somewhere on
     the path and never a moment when they all arrive together.

     Positions come from the lane paths every frame (getPointAtLength), so
     the lanes can be re-drawn in the markup without touching this.
     ------------------------------------------------------------------------ */
  var scene = $('[data-scene]');
  if (scene && scene.__leads) {
    var L = scene.__leads;
    var glow = $('[data-glow]', scene);
    var TRIP = 11.0;                       /* seconds, foot of lane to gate */
    var GAP  = [2.0, 4.5];                 /* pause before re-entering */
    var now0 = null;

    /* Stagger the four starts so the first screen has tiles at rest on
       the path from the first frame, not four tiles queued at the foot. */
    L.leads.forEach(function (ld, i) {
      ld.start = -ld.t * TRIP;             /* already this far along */
      ld.qualified = ld.t >= L.QUAL;
      ld.gone = false;
    });

    function qualify(ld) {
      ld.qualified = true;
      gsap.to(ld.v, { opacity: 0, duration: 0.5 });
      gsap.to(ld.q, { opacity: 1, duration: 0.5 });
      gsap.fromTo(ld.badge, { scale: 0 }, { scale: 1, duration: 0.55, ease: 'back.out(2.4)', transformOrigin: '38px -12px', delay: 0.15 });
      /* a small lift as it turns, like it has noticed something */
      gsap.fromTo(ld.g, { y: 0 }, { y: -10, duration: 0.22, yoyo: true, repeat: 1, ease: 'sine.out' });
    }

    function arrive() {
      gsap.fromTo(glow, { opacity: 0.25 }, { opacity: 0.85, duration: 0.35, ease: 'power2.out' });
      gsap.to(glow, { opacity: 0.25, duration: 1.4, ease: 'power2.inOut', delay: 0.35 });
    }

    function reset(ld, time) {
      ld.qualified = false; ld.gone = false;
      ld.start = time + GAP[0] + Math.random() * (GAP[1] - GAP[0]);
      gsap.set(ld.v, { opacity: 1 }); gsap.set(ld.q, { opacity: 0 });
      gsap.set(ld.badge, { scale: 0, transformOrigin: '38px -12px' });
      gsap.set(ld.g, { opacity: 0 });
    }

    function step(time) {
      if (now0 === null) now0 = time;
      var t0 = time - now0;
      L.leads.forEach(function (ld) {
        var t = (t0 - ld.start) / TRIP;
        if (t < 0) return;                                  /* waiting to enter */
        if (t >= 1) { if (!ld.gone) { ld.gone = true; reset(ld, t0); } return; }
        /* ease the walk a little: quick off the mark, slower into the gate */
        var e = 1 - Math.pow(1 - t, 1.25);
        L.place(ld, e);
        /* in over the first 6%, out over the last 8% as it enters the gate */
        var a = t < 0.06 ? t / 0.06 : t > 0.92 ? Math.max(0, (1 - t) / 0.08) : 1;
        gsap.set(ld.g, { opacity: a });
        if (!ld.qualified && t >= L.QUAL) qualify(ld);
        if (!ld.arrived && t >= 0.90) { ld.arrived = true; arrive(); }
        if (t < 0.90) ld.arrived = false;
      });
    }

    tl.add(function () { gsap.ticker.add(step); }, 0.9);

    document.addEventListener('visibilitychange', function () {
      if (document.hidden) gsap.ticker.sleep(); else gsap.ticker.wake();
    });
  }

  /* ------------------------------------------------------------------------
     Method, step 01 — the four questions

     Each row: the question types itself in (a caret blinks while it does),
     the rule beneath draws left to right, the clay dot lands. Rows follow
     one another; when all four are in, it holds, clears, and goes again.
     The timeline plays only while its panel is the visible one — the tab
     controller toggles .is-on, and an observer here follows it — so it is
     never typing to itself behind another step, and always starts from the
     top when the step comes round.
     ------------------------------------------------------------------------ */
  var ask = $('[data-ask]');
  if (ask) {
    var rows = $$('.ask', ask);
    var seq = gsap.timeline({ paused: true, repeat: -1, repeatDelay: 1.6 });
    /* Everything winds back at time zero — otherwise a row that has not had
       its turn yet sits there fully written while the earlier ones type. */
    seq.call(function () { $$('.ask__q', ask).forEach(function (q) { q.textContent = ''; }); }, null, 0)
       .set($$('.ask__line', ask), { scaleX: 0 }, 0)
       .set($$('.ask__dot', ask),  { scale: 0, transformOrigin: 'center' }, 0);
    var at = 0.2;
    rows.forEach(function (row) {
      var q = $('.ask__q', row), line = $('.ask__line', row), dot = $('.ask__dot', row);
      var text = q.textContent;
      var typer = { n: 0 };
      var typeDur = Math.max(0.45, text.length * 0.032);
      seq.set(row, { className: 'ask is-typing' }, at)
         .to(typer, {
           n: text.length, duration: typeDur, ease: 'none',
           onUpdate: function () { q.textContent = text.slice(0, Math.round(typer.n)); }
         }, at)
         .set(row, { className: 'ask' }, at + typeDur)
         .fromTo(line, { scaleX: 0 }, { scaleX: 1, duration: 0.45, ease: 'power2.out' }, at + typeDur)
         .fromTo(dot,  { scale: 0 },  { scale: 1, duration: 0.35, ease: 'back.out(2.4)', transformOrigin: 'center' }, at + typeDur + 0.25);
      at += typeDur + 0.55;
    });
    /* clear before the repeat, so it never jumps from full to empty */
    seq.to(rows, { opacity: 0, duration: 0.35, stagger: 0.04 }, at + 1.4)
       .set(rows, { opacity: 1 }, at + 1.8)
       .call(function () { $$('.ask__q', ask).forEach(function (q) { q.textContent = ''; }); }, null, at + 1.8);

    function follow() {
      if (ask.classList.contains('is-on')) { seq.restart(); } else { seq.pause(); }
    }
    new MutationObserver(follow).observe(ask, { attributes: true, attributeFilter: ['class'] });
    /* And only once the section is on screen the first time. */
    if (ST) {
      ST.create({ trigger: ask, start: 'top 85%', once: true, onEnter: follow });
    } else { follow(); }
  }

  /* ------------------------------------------------------------------------
     Method, step 02 — the sweep

     The line starts above the grid, travels down through it, and each row
     of tiles takes its result as the line reaches it: green for fine, clay
     with a mark for a problem. Holds on the result, clears, sweeps again.
     Follows its panel's .is-on exactly as step 01 does.
     ------------------------------------------------------------------------ */
  var sweep = $('[data-sweep]');
  if (sweep) {
    var sgrid = $('.sweep__grid', sweep), sline = $('.sweep__line', sweep);
    var tiles = $$('i', sgrid);
    var COLS = 8, ROWS = Math.ceil(tiles.length / COLS);
    var sseq = gsap.timeline({ paused: true, repeat: -1, repeatDelay: 1.9 });

    function tileRow(i) { return Math.floor(i / COLS); }

    /* Geometry is read at play time, not build time, so a resize between
       loops is picked up. */
    function gridTop()    { return sgrid.offsetTop; }
    function gridHeight() { return sgrid.offsetHeight; }

    var TRAVEL = 1.7, LEAD = 0.35;

    sseq.call(function () {
          tiles.forEach(function (t) { t.className = ''; });
          gsap.set(sline, { y: gridTop() - 10, opacity: 0 });
        }, null, 0)
        .to(sline, { opacity: 1, duration: 0.25 }, LEAD - 0.2)
        .to(sline, {
          y: function () { return gridTop() + gridHeight() + 10; },
          duration: TRAVEL, ease: 'none'
        }, LEAD)
        .to(sline, { opacity: 0, duration: 0.3 }, LEAD + TRAVEL - 0.1);

    for (var r = 0; r < ROWS; r++) {
      (function (row) {
        var at = LEAD + ((row + 0.5) / ROWS) * TRAVEL;
        var rowTiles = tiles.filter(function (t, i) { return tileRow(i) === row; });
        rowTiles.forEach(function (t, c) {
          sseq.call(function () { t.className = 'is-' + t.getAttribute('data-r'); }, null, at + c * 0.02);
          if (t.getAttribute('data-r') === 'bad') {
            sseq.fromTo(t, { scale: 1 }, { scale: 1.18, duration: 0.16, yoyo: true, repeat: 1, ease: 'power2.out' }, at + c * 0.02);
          }
        });
      })(r);
    }

    function followSweep() {
      if (sweep.classList.contains('is-on')) { sseq.restart(); } else { sseq.pause(); }
    }
    new MutationObserver(followSweep).observe(sweep, { attributes: true, attributeFilter: ['class'] });
    followSweep();
  }

  /* ------------------------------------------------------------------------
     Method, step 03 — in order

     The rail draws across, then the nodes land one at a time: dot, number,
     and the block rising at each. Holds, clears, repeats. Same follow-the-
     panel rule as the other steps.
     ------------------------------------------------------------------------ */
  var order = $('[data-order]');
  if (order) {
    var rail   = $('.order__rail', order);
    var nodes  = $$('.order__node', order);
    var oseq   = gsap.timeline({ paused: true, repeat: -1, repeatDelay: 1.9 });
    oseq.set(rail, { scaleX: 0 }, 0)
        .set($$('.order__dot', order),   { scale: 0 }, 0)
        .set($$('.order__n', order),     { opacity: 0, y: 4 }, 0)
        .set($$('.order__block', order), { scaleY: 0 }, 0)
        .to(rail, { scaleX: 1, duration: 0.9, ease: 'power2.inOut' }, 0.15);
    nodes.forEach(function (n, i) {
      var at = 0.55 + i * 0.6;
      oseq.to($('.order__dot', n),   { scale: 1, duration: 0.35, ease: 'back.out(2.4)' }, at)
          .to($('.order__n', n),     { opacity: 1, y: 0, duration: 0.35 }, at + 0.05)
          .to($('.order__block', n), { scaleY: 1, duration: 0.5, ease: 'power3.out' }, at + 0.15);
    });
    var oEnd = 0.55 + (nodes.length - 1) * 0.6 + 0.7;
    /* Fade the inner group, never the panel: the panel's opacity is how the
       tab controller shows and hides it, and an inline opacity from here
       would override that and leave this step visible behind the others. */
    var oInner = $('.order', order);
    oseq.to(oInner, { opacity: 0, duration: 0.35 }, oEnd + 1.5)
        .set(oInner, { opacity: 1 }, oEnd + 1.9);

    function followOrder() {
      if (order.classList.contains('is-on')) { oseq.restart(); } else { oseq.pause(); }
    }
    new MutationObserver(followOrder).observe(order, { attributes: true, attributeFilter: ['class'] });
    followOrder();
  }

  /* ------------------------------------------------------------------------
     Method, step 04 — the monthly loop

     One number drives it: the angle. The marker's arm rotates to it and the
     arc's dash offset follows it, so they can never drift apart. Three
     segments of 120°, each a move and a dwell; at every dwell the node and
     the word for that stop light and the others go quiet. It never stops
     while its panel is showing, which is the message.
     ------------------------------------------------------------------------ */
  var loop = $('[data-loop]');
  if (loop) {
    var arm    = $('[data-arm]', loop);
    var arc    = $('.loop__arc', loop);
    var lnodes = $$('.loop__node', loop);
    var llabels= $$('.loop__label', loop);
    var C = 2 * Math.PI * 68;
    var ang = { a: 0 };

    function paint() {
      gsap.set(arm, { rotation: ang.a, svgOrigin: '100 100' });
      gsap.set(arc, { strokeDashoffset: C * (1 - (ang.a % 360) / 360) });
    }
    function light(i) {
      lnodes.forEach(function (n, k)  { n.classList.toggle('is-on', k === i); });
      llabels.forEach(function (l, k) { l.classList.toggle('is-on', k === i); });
    }

    var lseq = gsap.timeline({ paused: true, repeat: -1 });
    lseq.call(function () { ang.a = 0; paint(); light(0); }, null, 0);
    for (var k = 1; k <= 3; k++) {
      (function (k) {
        var at = 0.7 + (k - 1) * 1.7;
        lseq.to(ang, { a: k * 120, duration: 1.0, ease: 'power2.inOut', onUpdate: paint }, at)
            .call(function () { light(k % 3); }, null, at + 1.0);
      })(k);
    }
    /* At 360 the arc is full; let it be seen full for the dwell, then the
       repeat's first call zeroes it. The jump from full to empty is the
       month turning over, and reads as such. */

    function followLoop() {
      if (loop.classList.contains('is-on')) { lseq.restart(); } else { lseq.pause(); }
    }
    new MutationObserver(followLoop).observe(loop, { attributes: true, attributeFilter: ['class'] });
    followLoop();
  }

  /* ------------------------------------------------------------------------
     Success stories
     ------------------------------------------------------------------------ */
  reveal($$('.stories__title, .stories__head .link-mono'), { stagger: 0.06 });
  reveal($$('.case'), { y: 28, stagger: 0.09, trigger: $('.stories__grid') });

  /* ------------------------------------------------------------------------
     What we do
     The rows carry their own opacity, so these rise without fading.
     ------------------------------------------------------------------------ */
  reveal($$('.services__title'));
  reveal($$('.svc'), { fade: false, y: 18, stagger: 0.05, trigger: $('.services__band') });
  reveal($$('.svc-media__frame'), { y: 24, duration: 1 });

  /* ------------------------------------------------------------------------
     How we do it
     Steps rise only — their opacity is the selected state.
     ------------------------------------------------------------------------ */
  reveal($$('.method__title'));
  reveal($$('.step'), { fade: false, y: 14, stagger: 0.06, trigger: $('.steps') });
  reveal($$('.schematic'), { y: 20, duration: 0.9 });

  /* The two rules above Foundation Fix and Ongoing draw themselves. The rule
     is a background gradient rather than a border, so it can be widened
     without scaling the text underneath it. */
  if ($('.parts')) {
    var drawParts = function () {
      $$('.part').forEach(function (part, i) {
        gsap.delayedCall(i * 0.12, function () { part.classList.add('is-in'); });
      });
    };
    if (ST) {
      ST.create({ trigger: $('.parts'), start: 'top 85%', once: true, onEnter: drawParts });
    } else {
      drawParts();
    }
  }

  reveal($$('.part__name, .part__text'), { y: 12, stagger: 0.05, trigger: $('.parts') });

  /* ------------------------------------------------------------------------
     Team, questions, close, footer
     ------------------------------------------------------------------------ */
  reveal($$('.team__title, .team__lead'), { stagger: 0.08 });
  reveal($$('.marquee'), { y: 24, stagger: 0.1, duration: 1, trigger: $('.marquees') });

  reveal($$('.questions__title, .questions__head .link-quiet'), { stagger: 0.06 });
  reveal($$('.fq'), { fade: false, y: 12, stagger: 0.04, trigger: $('.fqs') });
  reveal($$('.answers'), { y: 16 });

  reveal($$('.close__tagline'), { y: 24, duration: 1 });
  reveal($$('.close__card'), { y: 24, duration: 1 });

  reveal($$('.footer__col'), { y: 16, stagger: 0.06, trigger: $('.footer__cols') });
  reveal($$('.ind'), { y: 12, stagger: 0.06, trigger: $('.footer__industries') });

  /* ------------------------------------------------------------------------
     Growth audit page

     The lede is inside .hero__inner, so the hero timeline already handles it.
     Everything below gets the same treatment as its homepage equivalent.
     ------------------------------------------------------------------------ */
  reveal($$('.ticks li'), { y: 12, stagger: 0.05, trigger: $('.ticks') });
  /* The success story process list. Named .flow, not .step: the homepage
     method stepper owns .step and holds it at opacity 0.45 until selected,
     which greyed this list out when it shared the name. */
  reveal($$('.flow__item'), { y: 14, stagger: 0.06, trigger: $('.flow') });
  reveal($$('.deliv__grid--onforest .deliv'), { y: 20, stagger: 0.08, trigger: $('.deliv__grid--onforest') });
  /* Every .how on the page used to share ONE ScrollTrigger, pinned to the
     first .hows container. On a service page .beliefs sits about a thousand
     pixels ABOVE .hows, so its seven blocks stayed at opacity 0 until the
     reader had already scrolled past them: the longest section on the page
     rendered as an empty green field. Each group now triggers on itself. */
  $$('.beliefs, .hows').forEach(function (group) {
    reveal($$('.how', group), { y: 20, stagger: 0.07, trigger: group });
  });
  /* Anything the loop did not reach (a .how outside either container)
     reveals on itself rather than being left hidden. */
  reveal($$('.how'), { y: 20, stagger: 0.08 });

  /* The rule that draws in under each step of "how it works". A class, not
     a tween: the width transition lives in CSS, so it costs nothing and
     honours reduced motion through the same media query as everything else. */
  $$('.hows .how').forEach(function (el, i) {
    if (!ST) { el.classList.add('is-in'); return; }
    ST.create({
      trigger: el, start: 'top 88%', once: true,
      onEnter: function () {
        gsap.delayedCall(i * 0.12, function () { el.classList.add('is-in'); });
      }
    });
  });
  reveal($$('.notthis'), { y: 16 });

  /* ------------------------------------------------------------------------
     Call page
     ------------------------------------------------------------------------ */
  reveal($$('.band--forest .term'), { y: 20, stagger: 0.07, trigger: $('.band--forest .terms__grid') });
  reveal($$('.who__photo'), { y: 24, duration: 1 });
  reveal($$('.who__copy > *'), { y: 16, stagger: 0.07, trigger: $('.who__copy') });

  /* ------------------------------------------------------------------------
     Success stories page
     ------------------------------------------------------------------------ */
  reveal($$('.story'), { y: 24, stagger: 0.1, duration: 0.9, trigger: $('.storylist') });
  reveal($$('.more'), { y: 16 });

  /* ------------------------------------------------------------------------
     About page
     The .how loop above already covers .beliefs and .hows--onforest —
     reveal() ignores a second registration, but naming them again would
     still be a lie about what this block does.
     ------------------------------------------------------------------------ */
  reveal($$('.vs__row'), { y: 12, stagger: 0.05, trigger: $('.vs') });
  reveal($$('.person'), { y: 20, stagger: 0.08, trigger: $('.people') });
  reveal($$('.office'), { y: 16, stagger: 0.08, trigger: $('.offices') });

  /* ------------------------------------------------------------------------
     Service pages
     '.how', '.term', '.story' and '.vs__row' are already covered by the
     rules above; reveal() ignores the repeats. Only the genuinely new
     blocks are named here.
     ------------------------------------------------------------------------ */
  reveal($$('.midcta__inner > *'), { y: 16, stagger: 0.08, trigger: $('.midcta') });
  reveal($$('.fit__col'), { y: 18, stagger: 0.1, trigger: $('.fit') });

  /* Industry and category pages — '.how', '.term', '.story', '.more' and
     '.qi' are all already covered above. */
  reveal($$('.hows--five .how'), { y: 16, stagger: 0.06, trigger: $('.hows--five') });

  /* How we work, and contact */
  reveal($$('.step4'), { y: 20, stagger: 0.08, trigger: $('.steps4') });
  reveal($$('.part2'), { y: 20, stagger: 0.1, trigger: $('.parts') });
  reveal($$('.route'), { y: 12, stagger: 0.05, trigger: $('.routes') });

  /* Legal pages */
  reveal($$('.clause'), { y: 14, stagger: 0.04, trigger: $('.legal__doc') });

  /* Story pages */
  reveal($$('.result'), { y: 18, stagger: 0.08, trigger: $('.results') });
  reveal($$('.situation__copy, .situation__found'), { y: 16, stagger: 0.1, trigger: $('.situation') });
  reveal($$('.storyshot'), { y: 20, stagger: 0.1, trigger: $('.storyshots') });
  reveal($$('.pullquote'), { y: 20, duration: 1 });
  /* No reveal for .hows--onforest here — the '.how' rule above already
     matches them. Registering the same element twice is what broke this
     section once; see the note in reveal(). */

  /* ------------------------------------------------------------------------
     The services list resizes its own trigger points as images settle
     ------------------------------------------------------------------------ */
  if (ST) {
    window.addEventListener('load', function () { ST.refresh(); });
    if (document.fonts && document.fonts.ready) {
      document.fonts.ready.then(function () { ST.refresh(); });
    }
  }
})();


/* ==========================================================================
   RankinAI — smooth scrolling

   Lenis, driving the real window scroll. That choice matters: a smooth-scroll
   library that transforms a wrapper would break position:sticky, and two
   things on this page depend on it — the header, and the pinned column beside
   the services list.

   Off entirely for reduced motion. Off on touch, where the operating system's
   own momentum is better than anything JavaScript can imitate.
   ========================================================================== */

(function () {
  'use strict';

  var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduced || typeof window.Lenis === 'undefined') return;

  var gsap = window.gsap;
  var ST   = window.ScrollTrigger;

  var lenis = new window.Lenis({
    duration: 0.9,                 /* 1.2 is the default and feels floaty */
    easing: function (t) { return Math.min(1, 1.001 - Math.pow(2, -10 * t)); },
    smoothWheel: true,
    syncTouch: false,              /* native momentum on phones and tablets */
    wheelMultiplier: 1,
    touchMultiplier: 1.6
  });

  /* One ticker for the whole page. Running Lenis on GSAP's ticker rather than
     its own rAF keeps scroll position and ScrollTrigger in the same frame —
     without it, reveals fire a frame late and appear to lag the scroll. */
  if (gsap) {
    gsap.ticker.add(function (time) { lenis.raf(time * 1000); });
    gsap.ticker.lagSmoothing(0);
  } else {
    var raf = function (time) { lenis.raf(time); requestAnimationFrame(raf); };
    requestAnimationFrame(raf);
  }

  if (ST) {
    lenis.on('scroll', ST.update);
    ST.refresh();
  }

  /* The mobile sheet scrolls itself. Lenis stops while it is open. */
  document.addEventListener('rankinai:scroll-lock',   function () { lenis.stop(); });
  document.addEventListener('rankinai:scroll-unlock', function () { lenis.start(); });

  /* ------------------------------------------------------------------------
     In-page anchors
     Routed through Lenis so they ease rather than jump, and offset by the
     sticky header so the target is not hidden underneath it.

     The skip link is deliberately excluded — someone using it is navigating
     by keyboard and wants to arrive immediately, not watch a second of easing.
     ------------------------------------------------------------------------ */
  function headerOffset() {
    var panel = document.querySelector('.site-header__panel');
    return panel ? panel.getBoundingClientRect().height + 16 : 0;
  }

  document.addEventListener('click', function (e) {
    var link = e.target.closest('a[href^="#"]');
    if (!link || link.classList.contains('skip-link')) return;

    var id = link.getAttribute('href');
    if (!id || id === '#') return;

    var target = document.querySelector(id);
    if (!target) return;

    e.preventDefault();
    lenis.scrollTo(target, { offset: -headerOffset(), duration: 1 });
    history.pushState(null, '', id);
  });

  /* Land on the right place when arriving with a hash already in the URL */
  if (window.location.hash) {
    var initial = document.querySelector(window.location.hash);
    if (initial) {
      window.setTimeout(function () {
        lenis.scrollTo(initial, { offset: -headerOffset(), immediate: true });
      }, 0);
    }
  }

  window.addEventListener('load', function () { lenis.resize(); });
})();


/* The pricing page's deliverables dialog was removed on 25 Sep 2026 with
   the page rebuild. The four lists are on the page now, inside their own
   plan, so there is nothing left to open. The block that drove it lived
   here. */



/* ==========================================================================
   RankinAI — audit request forms

   Every form marked [data-auditform] gets this. There are three now — the
   growth audit page, the pricing page and the success stories page — and
   they are the same form, so they behave the same way. A page that adds
   another needs the attribute and `novalidate` and nothing else.

   These forms carry `novalidate`, so this replaces the browser's own bubbles
   with messages that sit under the field and match the page. It does not
   replace server-side validation, which is still required — this is only
   here to stop a completed form failing on a typo the visitor cannot see.

   Deliberate choices:
   · Nothing is validated until the first submit attempt. Marking a field
     invalid while someone is still typing into it is hostile.
   · After that first attempt, fields re-check on input, so an error clears
     the moment it is fixed rather than on the next submit.
   · Focus moves to the first invalid field. On a long form the error is
     often above the fold and the button is not.
   ========================================================================== */

(function () {
  'use strict';

  [].slice.call(document.querySelectorAll('[data-auditform]')).forEach(wire);

  function wire(form) {

  var fields = [].slice.call(form.querySelectorAll('.field'));
  var submitted = false;

  /* A permissive URL test. "yourcompany.com" is what people type, and
     rejecting it because it has no scheme loses real enquiries — the server
     can normalise it. This only catches something that is not a domain. */
  var RE_EMAIL = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
  var RE_SITE  = /^(https?:\/\/)?([\w-]+\.)+[a-z]{2,}(\/\S*)?$/i;
  var RE_PHONE = /^[+\d][\d\s().-]{6,}$/;

  function control(field) {
    return field.querySelector('input, textarea');
  }

  function problem(field) {
    var el = control(field);
    /* Contact Form 7 marks a required field with aria-required, not required. */
    if (!el || !(el.required || el.getAttribute('aria-required') === 'true')) return '';

    var v = (el.value || '').trim();
    if (!v) return 'Required';

    if (el.type === 'email' && !RE_EMAIL.test(v))       return 'That does not look like an email address';
    if (el.name === 'website' && !RE_SITE.test(v))      return 'Something like yourcompany.com';
    if (el.type === 'tel' && !RE_PHONE.test(v))         return 'That does not look like a phone number';
    return '';
  }

  function check(field) {
    var msg = problem(field);
    var slot = field.querySelector('[data-msg]');
    var el = control(field);

    field.classList.toggle('is-invalid', !!msg);
    if (slot) slot.textContent = msg;
    if (el) el.setAttribute('aria-invalid', msg ? 'true' : 'false');
    return !msg;
  }

  fields.forEach(function (field) {
    var el = control(field);
    if (!el) return;

    /* Only after the first submit — see the note above */
    el.addEventListener('input', function () { if (submitted) check(field); });
    el.addEventListener('blur',  function () { if (submitted) check(field); });
  });

  /* Capture phase, so an invalid form is stopped before Contact Form 7's own
     submit handler sends it (that handler does not check defaultPrevented). */
  form.addEventListener('submit', function (e) {
    submitted = true;

    var firstBad = null;
    fields.forEach(function (field) {
      if (!check(field) && !firstBad) firstBad = field;
    });

    if (firstBad) {
      e.preventDefault();
      e.stopImmediatePropagation();
      var el = control(firstBad);
      if (el) el.focus();
      return;
    }

    /* No endpoint yet. Until one exists, stop the submit rather than let the
       page reload to itself and look like the form was silently thrown away.
       DELETE THIS BLOCK the moment `action` points somewhere real. */
    if (form.getAttribute('action') === '#') {
      e.preventDefault();
      sent();
    }
  }, true);

  /* Contact Form 7 has sent it: show the same in-place confirmation. */
  form.addEventListener('wpcf7mailsent', function () { sent(); });

  /* Replaces the form's contents in place. The reader keeps their scroll
     position and the rest of the page stays where it was. */
  function sent() {
    var name = (form.querySelector('[name="name"], [name="your-name"]') || {}).value || '';
    var first = name.trim().split(/\s+/)[0];

    /* The audit form's wording is the default because it came first and three
       pages use it. A form that promises something else says so in its own
       markup: data-sent-label and data-sent-text on the <form>. */
    /* A Contact Form 7 form carries its wording on a hidden [data-sent]
       element, since the form tag itself is the plugin's. */
    var cfg   = form.querySelector('[data-sent]') || form;
    var label = cfg.getAttribute('data-sent-label') || 'Request received';
    var body  = cfg.getAttribute('data-sent-text')  ||
      'Someone here will read this today and start on your audit. It comes back by email ' +
      'within one working day, two if your site is large.|' +
      'Nothing else happens in between. No sequence, no call booked on your behalf.';

    var paras = body.split('|').map(function (p) {
      return '<p>' + p + '</p>';
    }).join('');

    form.innerHTML =
      '<div class="auditform__sent">' +
        '<p class="label">' + escapeHtml(label) + '</p>' +
        '<h2>' + (first ? 'Thanks, ' + escapeHtml(first) + '.' : 'Thank you.') + '</h2>' +
        paras +
      '</div>';

    form.setAttribute('tabindex', '-1');
    form.focus();
    if (window.ScrollTrigger) window.ScrollTrigger.refresh();
  }

  function escapeHtml(s) {
    return String(s).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  }

  } /* wire */
})();


/* ==========================================================================
   RankinAI — horizontal card sliders

   Two on the site now: the team on /about/ and the commitments on the home
   page. Both are native scroll-snap tracks; this only wires the buttons,
   greys them at the ends, and hides them when everything already fits.

   Written for any number of sliders on a page, and for either set of data
   attributes, because the team one shipped first with its own names.
   ========================================================================== */

(function () {
  'use strict';

  var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function wire(slider, sel) {
    var track = slider.querySelector(sel.track);
    var prev  = slider.querySelector(sel.prev);
    var next  = slider.querySelector(sel.next);
    if (!track || !prev || !next) return;

    var anim = null;

    function step() {
      var card = track.firstElementChild;
      if (!card) return track.clientWidth;
      var gap = parseFloat(getComputedStyle(track).columnGap || '0') || 0;
      return card.getBoundingClientRect().width + gap;
    }

    function sync() {
      var max = track.scrollWidth - track.clientWidth;
      /* Sub-pixel widths make scrollWidth exceed clientWidth by a hair even
         when nothing is clipped, so allow a little slack. */
      slider.toggleAttribute('data-static', max <= 2);
      prev.disabled = track.scrollLeft <= 1;
      next.disabled = track.scrollLeft >= max - 1;
    }

    /* Animated here rather than with scrollBy({behavior:'smooth'}), which is
       a no-op in some browsers and under reduced-motion settings.

       SNAP IS SWITCHED OFF FOR THE DURATION. scroll-snap-type fights a tween
       that writes scrollLeft every frame: the browser keeps pulling the track
       back towards the nearest snap point, and on a wide card that reads as a
       stutter rather than a glide. Snap is what we want when a finger or a
       trackpad is doing the scrolling, so it goes back on the moment the
       tween lands.

       THE DURATION FOLLOWS THE DISTANCE. A fixed 420ms was brisk for a small
       card and abrupt for an 1,100px one. It is now the distance at roughly
       1.6px a millisecond, held between 380 and 760. */
    function glide(delta) {
      var max  = track.scrollWidth - track.clientWidth;
      var from = track.scrollLeft;
      var to   = Math.max(0, Math.min(max, from + delta));
      if (to === from) return;

      if (anim) cancelAnimationFrame(anim);
      if (reduced) { track.scrollLeft = to; sync(); return; }

      var snap = track.style.scrollSnapType;
      track.style.scrollSnapType = 'none';

      var dist  = Math.abs(to - from);
      var dur   = Math.max(380, Math.min(760, dist * 0.62));
      var start = null, done = false;

      function land() {
        if (done) return;
        done = true;
        if (anim) { cancelAnimationFrame(anim); anim = null; }
        track.scrollLeft = to;
        track.style.scrollSnapType = snap;
        sync();
      }

      /* Ease in and out rather than out only. A move that starts at full
         speed is the other half of why this felt like a jump. */
      function ease(t) {
        return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
      }

      function frame(now) {
        if (done) return;
        if (start === null) start = now;
        var t = Math.min(1, (now - start) / dur);
        track.scrollLeft = from + (to - from) * ease(t);
        if (t < 1) { anim = requestAnimationFrame(frame); } else { land(); }
      }
      anim = requestAnimationFrame(frame);

      /* A background or throttled tab may never run a frame, and the button
         would look broken. Land it anyway once the tween should have ended. */
      setTimeout(land, dur + 140);
    }

    prev.addEventListener('click', function () { glide(-step()); });
    next.addEventListener('click', function () { glide( step()); });
    track.addEventListener('scroll', sync, { passive: true });
    window.addEventListener('resize', sync);
    window.addEventListener('load', sync);
    sync();
  }

  [].slice.call(document.querySelectorAll('[data-teamslider]')).forEach(function (el) {
    wire(el, { track: '[data-team-track]', prev: '[data-team-prev]', next: '[data-team-next]' });
  });
  [].slice.call(document.querySelectorAll('[data-slider]')).forEach(function (el) {
    wire(el, { track: '[data-slider-track]', prev: '[data-slider-prev]', next: '[data-slider-next]' });
  });
})();

/* ---------------------------------------------------------------------------
   The article contents list
   ---------------------------------------------------------------------------
   Marks the section a reader is currently in. Progressive: without this the
   list is still a set of working anchors, which is most of its value.

   MEASURED WITH offsetTop, NOT getBoundingClientRect. The reveal animation on
   this site transforms elements as they come into view, and a transform moves
   the rect a browser reports without moving the element in the document. An
   IntersectionObserver reads the same polluted geometry, which is why the
   first version of this sat on the opening section the whole way down the
   page. An offsetTop chain is the document position and a transform cannot
   touch it. The same fix is why several measurements in this file walk
   offsetParent rather than asking for a rect.
   --------------------------------------------------------------------------- */
(function () {
  var links = [].slice.call(document.querySelectorAll('.toc__list a[data-toc]'));
  if (!links.length) return;

  var heads = [];
  links.forEach(function (a) {
    var h = document.getElementById(a.getAttribute('data-toc'));
    if (h) heads.push({ link: a, el: h });
  });
  if (!heads.length) return;

  /* The distance from the top of the document, walked rather than measured. */
  function docTop(el) {
    var y = 0;
    while (el) { y += el.offsetTop; el = el.offsetParent; }
    return y;
  }

  var tops = [];
  function measure() {
    tops = heads.map(function (h) { return docTop(h.el); });
  }

  var current = null;
  function paint() {
    /* The watch line sits below the sticky header, so the section marked is
       the one whose heading has passed under it. */
    var line = (window.scrollY || window.pageYOffset) + 140;
    var i = 0;
    for (var n = 0; n < tops.length; n++) {
      if (tops[n] <= line) i = n;
    }
    if (i === current) return;
    current = i;
    heads.forEach(function (h, n) { h.link.classList.toggle('is-here', n === i); });
  }

  var ticking = false;
  function onScroll() {
    if (ticking) return;
    ticking = true;
    requestAnimationFrame(function () { paint(); ticking = false; });
  }

  measure();
  paint();
  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', function () { measure(); current = null; paint(); });
  window.addEventListener('load', function () { measure(); current = null; paint(); });
})();

/* ---------------------------------------------------------------------------
   The growth audit modal
   ---------------------------------------------------------------------------
   Every link to /growth-audit/ opens the dialog instead of loading the page.
   There are seventeen of them and not one had to change: the hook is the href
   they already had.

   PROGRESSIVE, NOT DEPENDENT. If this script does not run, or <dialog> is not
   supported, nothing is intercepted and the link does what a link does. The
   page is still there and still answers. The same principle as the blog's
   search, filtering and paging.

   What it handles: opening on any audit link, Escape and the backdrop and the
   close button to dismiss, focus into the first field on open and back to the
   link that opened it on close, and the scroll lock the mobile sheet already
   uses so Lenis stops while the dialog is up.
   --------------------------------------------------------------------------- */
(function () {
  var dlg = document.getElementById('audit-modal');
  if (!dlg || typeof dlg.showModal !== 'function') return;

  var opener = null;

  function isAuditLink(a) {
    if (!a || !a.getAttribute) return false;
    if (a.hasAttribute('data-no-modal')) return false;
    var href = a.getAttribute('href') || '';
    /* Match the path, not the whole href, so it works whether the site is
       served at / or in a subfolder, and ignore a bare hash link. */
    if (href.charAt(0) === '#') return false;
    return /\/growth-audit\/?$/.test(href.split('?')[0].split('#')[0]);
  }

  function open(from) {
    opener = from || null;
    dlg.showModal();
    document.dispatchEvent(new CustomEvent('rankinai:scroll-lock'));
    /* Focus the first field rather than the close button: someone who opened
       this wants to type, and the close button is one shift-tab away. */
    var first = dlg.querySelector('input, textarea, select, button');
    var field = dlg.querySelector('input');
    (field || first || dlg).focus({ preventScroll: true });
  }

  function close() {
    if (dlg.open) dlg.close();
  }

  document.addEventListener('click', function (e) {
    /* Let the browser have modified clicks: a new tab is a deliberate choice
       and the page is a real page. */
    if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
    var a = e.target.closest ? e.target.closest('a[href]') : null;
    if (!isAuditLink(a)) return;
    e.preventDefault();
    open(a);
  });

  /* The close button, and anything else that asks. */
  document.addEventListener('click', function (e) {
    if (e.target.closest && e.target.closest('[data-audit-close]')) close();
  });

  /* The backdrop. A click that lands on the dialog element itself rather than
     on the panel inside it is a click outside the content. */
  dlg.addEventListener('click', function (e) {
    if (e.target === dlg) close();
  });

  /* Escape fires the dialog's own cancel, and both routes end at close. */
  dlg.addEventListener('close', function () {
    document.dispatchEvent(new CustomEvent('rankinai:scroll-unlock'));

    /* Focus goes back to the link that opened the dialog, on the next frame:
       immediately after close() the dialog is still tearing down and the call
       is dropped. Some triggers cannot take focus back — a duplicate of the
       header button inside the closed mobile sheet, for one — so this tries
       and accepts failure rather than hunting for somewhere else to put it.
       Leaving focus at the top of the document is a worse outcome than
       leaving it where the browser put it. */
    var back = opener;
    opener = null;
    if (!back || !document.contains(back)) return;
    requestAnimationFrame(function () {
      try { back.focus({ preventScroll: true }); } catch (err) {}
    });
  });
})();

/* ==========================================================================
   One FAQ answer open at a time, 28 Sep 2026

   The markup does this on its own: every .qi in a group shares a name
   attribute, and a browser that supports exclusive <details> closes the
   others when one opens, with no script at all. This is only the fallback
   for a browser that ignores the name. It closes any other open details
   with the same name when one opens, and does nothing where the browser
   has already done it. Without script, and without support, answers simply
   stay open, which is the old behaviour and still works.
   ========================================================================== */
(function () {
  'use strict';
  document.addEventListener('toggle', function (e) {
    var d = e.target;
    if (!d || d.tagName !== 'DETAILS' || !d.open) return;
    var name = d.getAttribute('name');
    if (!name) return;
    var all = document.querySelectorAll('details[name]');
    for (var i = 0; i < all.length; i++) {
      if (all[i] !== d && all[i].open && all[i].getAttribute('name') === name) {
        all[i].open = false;
      }
    }
  }, true);
})();