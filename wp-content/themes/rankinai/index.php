<?php
/**
 * Fallback template. Every real page goes through page.php or front-page.php;
 * this only catches what WordPress routes nowhere else.
 */
get_header();
?>
<section class="band band--light"><div class="container">
  <h1 class="questions__title"><?php echo esc_html( wp_get_document_title() ); ?></h1>
</div></section>
<?php
get_footer();
