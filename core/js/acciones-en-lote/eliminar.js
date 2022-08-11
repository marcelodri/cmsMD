function accionEnLote_eliminar() {
  var eliminar = confirm("¿Estás seguro de eliminar todos estos elementos?");
  if (eliminar) {
    var quedan_elementos = false;
    var elementos = [];

    // Quito los elementos del listado
    $.each($(".js-AEL-item"), function (i, item) {
      if ($(item).attr("checked")) {
        elementos.push($(item).val());
        $(item)
          .closest("tr")
          .animate({ opacity: 0 }, 700, function () {
            $(this).remove();
          });
      } else {
        quedan_elementos = true;
      }
    });

    // Actualizo la base de datos
    $.ajax({
      url: CONTROLLER + "/eliminarEnLote/" + elementos.join(","),
      beforeSend: function () {
        $(".mensaje-feedback")
          .html(
            '<img src="' +
              DOCUMENT_ROOT +
              'core/images/ajax-loader.gif">&nbsp;&nbsp;Procesando...'
          )
          .addClass("info")
          .fadeIn();
        $("html, body").animate({ scrollTop: 0 }, "slow");
      },
      success: function () {
        $(".mensaje-feedback")
          .html("Se eliminaron todos los elementos seleccionados.")
          .removeClass("info")
          .addClass("success")
          .fadeIn();
      },
      error: function () {
        $(".mensaje-feedback")
          .html("Hubo un problema. Volvé a intentarlo.")
          .removeClass("info")
          .addClass("error")
          .fadeIn();
      },
      complete: function () {
        $("html, body").animate({ scrollTop: 0 }, "slow");
        setTimeout(function () {
          $(".mensaje-feedback").fadeOut(400, function () {
            $(this).removeClass("success").removeClass("error");
          });
        }, 3000);
      },
    });

    // Muestro un mensaje si ya no quedan elementos
    if (!quedan_elementos) {
      $(".js-AEL-contenedor tbody").html(
        "<tr><td>No quedan elementos.</td></tr>"
      );
    }
  }
}
