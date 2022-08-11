$(document).ready(function() {
    $('.item').on('blur', '.js-input-ajax', function(){
        actualizarValorCampo($(this));
        return false;
    });
    $('.item').on('change', '.js-form_dropdown-ajax,.js-date-ajax', function(){
        actualizarValorCampo($(this));
        return false;
    });
    $('.item').on("keypress", '.js-input-ajax', function(e) {
        /* ENTER PRESSED*/
        if (e.keyCode == 13) {
            actualizarValorCampo($(this));
            return false;
        }
    });

});

function actualizarValorCampo($campo){
    var id_item  = $campo.parents('tr').first().data('id');
    var key_campo = $campo.parents('td').first().data('key');
    $.ajax({
        type: 'get',
        url: DOCUMENT_ROOT + CONTROLLER +'/actualizarCampo/'
            + id_item +'/'
            + key_campo+'/'
            + $campo.val(),
        beforeSend: function() {
            console.log('Actualizando...');
        },
        complete: function(jqXHR, textStatus) {
            console.log('Actualizado!');
            // $('.mensaje-feedback').show().removeClass('info').addClass('success').html('¡Actualizado!');
            //    setTimeout(function(){
            //     $('.mensaje-feedback').hide().removeClass('success');
            // },2000);
            $campo.val(jqXHR.responseText);
        },
    });
}
