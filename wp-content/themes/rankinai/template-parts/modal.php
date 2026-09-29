<?php
/**
 * The growth audit modal
 * =============================================================================
 * Built 25 Sep 2026. Every "get your growth audit" link on the site opens this
 * instead of loading a page: seventeen of them across the header, the shared
 * close, every hero and most service and industry pages.
 *
 * WHY THE PAGE STILL EXISTS. The links still point at /growth-audit/ and the
 * page still answers. The script intercepts the click and opens this dialog
 * instead; if the script has not run, has failed, or is blocked, the link does
 * what a link does and the visitor gets the page. Nothing about the offer
 * depends on JavaScript, which is how search, filtering and paging work on
 * this site too.
 *
 * That also means no link had to change, no bookmark breaks, and the URL is
 * still there to share and to rank.
 *
 * Rendered once per page, from includes/footer.php, before the scripts.
 */
?>
<dialog class="amodal" id="audit-modal" aria-label="Your free growth audit">
  <div class="amodal__inner">

    <button class="amodal__close" type="button" data-audit-close aria-label="Close">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true" focusable="false">
        <path d="M6 6l12 12M18 6L6 18"/>
      </svg>
    </button>

<?php get_template_part( 'template-parts/auditform', null, array( 'panel' => true, 'id' => 'audit-modal-form' ) ); ?>

  </div>
</dialog>
