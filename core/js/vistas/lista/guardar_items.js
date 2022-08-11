function guardarItemsLista() {
  var DOCUMENT_ROOT = window.DOCUMENT_ROOT;
  var CONTROLLER = window.CONTROLLER;
  var items = [];
  $('[data-id]').each(function () {
    var item = {id: $(this).data('id')};
    $(this).find('[data-key]').each(function () {
      var $input = $(this).find('input, select').first();
      if ($input) {
        item[$(this).data('key')] = $input.val();
      }
    });
    items.push(item);
  });
  $.ajax({
    type: 'POST',
    url: DOCUMENT_ROOT + CONTROLLER + '/saveAll',
    data: {items: items},
    dataType: 'json',
    beforeSend: function() {
      $('.mensaje-feedback')
        .html('<img src="'+DOCUMENT_ROOT+'core/images/ajax-loader.gif">&nbsp;&nbsp;Actualizando...')
        .addClass('info').fadeIn();
      $("html, body").animate({ scrollTop: 0 }, "slow");
    },
    success: function () {
      $('.mensaje-feedback')
        .html('Guardado!')
        .removeClass('info')
        .addClass('success').fadeIn();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      var error = jqXHR.responseText || 'Hubo un problema. Volvé a intentarlo.';
      $('.mensaje-feedback')
        .html(error)
        .removeClass('info')
        .addClass('error').stop().fadeIn();
    },
    complete: function () {
      setTimeout(function(){
        $('.mensaje-feedback').fadeOut(400, function(){
          $(this).removeClass('success').removeClass('error');
       });
      },3000);
    },
  });
}