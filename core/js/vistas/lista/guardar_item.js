function guardarItemLista(id) {
  var datos = {};
  var DOCUMENT_ROOT = window.DOCUMENT_ROOT;
  var CONTROLLER = window.CONTROLLER;
  $('[data-id="' + id + '"] [data-key]').each(function () {
    var $input = $(this).find('input, select').first();
    if ($input) {
      datos[$(this).data('key')] = $input.val();
    }
  });
  $.ajax({
    type: 'POST',
    url: DOCUMENT_ROOT + CONTROLLER + '/save/' + id,
    data: datos,
    dataType: 'json',
    success: function () {
      toastr.success('Guardado!');
      window.location.reload();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      var error = jqXHR.responseText || 'Hubo un problema. Volvé a intentarlo.';
      toastr.error(error, 'Error');
    },
  })
}