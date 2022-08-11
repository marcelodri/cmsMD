
$(document).ready(function() {

    $('.js-checkbox-ajax').click(function(){

        var id_item   = $(this).parents('tr').first().data('id');
        var key_campo = $(this).parents('td').first().data('key');
        var $fa       = $(this).find('.fa');



        $.ajax({
            type: 'get',
            url: DOCUMENT_ROOT + CONTROLLER +'/actualizarCheckbox/'+ id_item +'/'+ key_campo,
            beforeSend: function() {
                console.log('Actualizando...');
                // $('.mensaje-feedback')
                //     .html('<img src="'+DOCUMENT_ROOT+'core/images/ajax-loader.gif">&nbsp;&nbsp;Actualizando...')
                //     .addClass('info').fadeIn();
                // $("html, body").animate({ scrollTop: 0 }, "slow");
            },
            success: function(){
                console.log('Actualizado!');
                $fa.toggleClass('fa-check-square-o');
                $fa.toggleClass('fa-square-o');
                // $('.mensaje-feedback')
                //     .html('Actualizado!')
                //     .removeClass('info')
                //     .addClass('success').fadeIn();

            },
            error: function(jqXHR, textStatus, errorThrown){
                var error = jqXHR.responseText || 'Hubo un problema. Volvé a intentarlo.';
                $('.mensaje-feedback')
                    .html(error)
                    .removeClass('info')
                    .addClass('error').stop().fadeIn();
            },
            complete: function() {
                // $("html, body").animate({ scrollTop: 0 }, "slow");
                setTimeout(function(){
                    $('.mensaje-feedback').fadeOut(400, function(){
                        $(this).removeClass('success').removeClass('error');
                    });
                },3000);
            },
        });

        return false;
    });

});