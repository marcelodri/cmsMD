
$(document).ready(function() {

    $('.js-checkbox-CRL input').change(function(){
        var $padre      = $(this).parent();
        var id_propio   = $padre.data('id_propio');
        var id_hermano  = $padre.data('id_hermano');
        var fk_propia   = $padre.data('fk_propia');
        var fk_hermano  = $padre.data('fk_hermano');
        var controlador = $padre.data('controlador');
        var checked = $(this).prop('checked') ? 1 : 0;

        var accion = DOCUMENT_ROOT + controlador +'/actualizarRelacion/'+ id_propio +'/'+ id_hermano +'/'+ fk_propia +'/'+ fk_hermano +'/'+ checked;

        $.ajax({
            type: 'get',
            url: accion,
            beforeSend: function() {
                console.log('Actualizando...');
            },
            complete: function() {

                console.log('Actualizado!');
            },
        });
    });

    $('.js-checkbox-CRL-todos input').change(function(){

        var checked = $(this).prop('checked') ? 1 : 0;
        if(checked){
            $(this).closest('.form__campo').find('.js-checkbox-CRL input').prop('checked',true);
        }else{
            $(this).closest('.form__campo').find('.js-checkbox-CRL input').prop('checked',false);
        }
    });

});