<?php
/**
 * The growth audit form
 * =============================================================================
 * Built 25 Sep 2026 to Kulwant's copy. Two fields: the website and an email
 * address. One include, so the same offer is the same offer wherever it
 * appears, and a change to the wording or the fields happens in one file.
 *
 *   get_template_part( 'template-parts/auditform', null, array( ... ) );
 *
 * Optional $args:
 *   'panel'   true to paint the whole block as a forest card, for a cream
 *             ground like the growth audit hero
 *   'light'   true for cream text on a dark ground it does not paint itself
 *   'id'      the form's id, where a page has more than one
 *
 * WHAT THIS REPLACED, AND WHAT WENT WITH IT. The /growth-audit/ page asked for
 * name, company, work email, phone, website and an optional note: six fields
 * for a free offer, four of them before the visitor has seen anything in
 * return. Kulwant's copy asks for two. The name, company and phone are no
 * longer collected at the point of enquiry and would have to come from the
 * reply or the call. That is a commercial trade, not a design one, and it is
 * recorded in CLAIMS.md rather than buried here.
 *
 * THE FORM IS CONTACT FORM 7 since 29 Sep 2026 (inc/forms.php). It sends
 * the request by email to the address set on the form under Contact.
 */
$args     = isset( $args ) && is_array( $args ) ? $args : array();
$af_panel = ! empty( $args['panel'] );
$af_light = ! empty( $args['light'] );
$af_id    = $args['id'] ?? 'audit-form';
?>
<div class="afx<?= $af_panel ? ' afx--panel' : '' ?><?= $af_light ? ' afx--light' : '' ?>">

  <p class="eyebrow<?= $af_light ? '' : ' eyebrow--light' ?>"><span>Your free growth audit</span></p>

  <h2 class="afx__title">Know what to improve next.</h2>

  <p class="afx__sub">We&rsquo;ll review your website, search visibility and enquiry journey, and send you three practical priorities to help you attract and convert more of the right clients.</p>

  <?php /* The form itself is Contact Form 7 (inc/forms.php): its fields,
           button and note are edited under Contact. */ ?>
  <?= rankinai_form( 'audit', $af_id, 'auditform afx__form' . ( $af_light ? ' auditform--light' : '' ) ) ?>

  <p class="afx__alt">Prefer to talk? <a class="link-quiet link-quiet--bold<?= $af_light ? '' : ' link-quiet--onforest' ?>" href="<?= url('/call/') ?>">Book a 20-minute call</a></p>

</div>
