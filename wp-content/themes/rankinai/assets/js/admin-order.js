/**
 * Drag to reorder, on the admin list of a sortable model (see 'sortable' in
 * inc/model-engine.php). Drag a row by its handle, and on the drop the rows'
 * new order is saved straight away. If the save fails the page reloads, so
 * what is on screen is always what is stored. The Order box on the edit
 * screen and in Quick Edit does the same job without this script.
 */
jQuery(function ($) {
  var cfg = window.rankinaiOrder;
  var $list = $('#the-list');
  if (!cfg || !$list.length || $list.children('tr[id^="post-"]').length < 2) { return; }

  function failed() {
    window.alert(cfg.failed);
    window.location.reload();
  }

  $list.sortable({
    items: '> tr[id^="post-"]',
    handle: '.ri-drag',
    axis: 'y',
    cursor: 'move',
    distance: 4,
    placeholder: 'ri-drag-placeholder',
    // The lifted row keeps its cells' widths, which a table row loses.
    helper: function (e, $row) {
      var $cells = $row.children();
      return $row.clone().children().each(function (i) {
        $(this).width($cells.eq(i).width());
      }).end();
    },
    start: function (e, ui) {
      ui.placeholder.height(ui.item.height())
        .html('<td colspan="' + ui.item.children().length + '"></td>');
    },
    update: function () {
      var ids = $list.children('tr[id^="post-"]').map(function () {
        return this.id.replace('post-', '');
      }).get();
      $list.sortable('disable').addClass('ri-saving');
      $.post(cfg.ajax, { action: 'rankinai_order', nonce: cfg.nonce, type: cfg.type, ids: ids })
        .done(function (r) { if (!r || !r.success) { failed(); } })
        .fail(failed)
        .always(function () { $list.sortable('enable').removeClass('ri-saving'); });
    }
  });
});
