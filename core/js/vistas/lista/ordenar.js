// http://api.jqueryui.com/sortable/
$(document).ready(function() {

    var orden       = $('#sort_order');
    var ordenInicio = $('#orden_inicio');
    var submit      = true;
    var messageBox  = $('.mensaje-feedback');
    var list        = $('.lista-ordenable tbody');

    /* create requesting function to avoid duplicate code */
    var request = function() {
        $.ajax({
            beforeSend: function() {
                //$('.mensaje-feedback').html('<img src="'+DOCUMENT_ROOT+'core/images/ajax-loader.gif">&nbsp;&nbsp;Actualizando el orden de los elementos en la base de datos...');
                //$('.mensaje-feedback').addClass('info').show();
               // $("html, body").animate({ scrollTop: 0 }, "slow");
                //console.log('Actualizando el orden de los elementos en la base de datos...');
            },
            complete: function() {
               //$('.mensaje-feedback').removeClass('info').addClass('success').html('¡Actualizado!');
               //setTimeout(function(){
                 //$('.mensaje-feedback').hide().removeClass('success');
              // },2000);
                console.log('Actualizado!');
                console.log(DOCUMENT_ROOT +CONTROLLER+'/actualizarOrden/?ordenInicio='+ ordenInicio[0].value +'&orden='+orden[0].value);
            },
            type: 'post',
            url: DOCUMENT_ROOT +CONTROLLER+'/actualizarOrden/',
            data:{
                'ordenInicio' : ordenInicio[0].value,
                'orden' : orden[0].value
            }
        });
    };

    /* worker function */
    var fnSubmit = function(save) {
        var sortOrder = [];
        list.children('tr').each(function(){
            sortOrder.push($(this).data('id'));
        });
        orden.val(sortOrder.join('-'));
        //console.log(orden.val());
        if(save) {
            request();
        }
    };

    /* sortables */
    list.sortable({
        items: "> tr",
        cursor: "move",
        opacity: 0.7,
        cursorAt: { left: 0 },
        handle: ".js-orden",
        placeholder: "sortable-placeholder",
        delay: 0,
        axis: "y",
        update: function() {
            fnSubmit(submit);
        }
    });
    //list.disableSelection();

    /* ajax form submission */
    /* Si quisieramos convertir la lista en un formulario
    y actualizarlo con un botón usaríamos esto:
    $('#dd-form').bind('submit',function(e) {
        if(e) e.preventDefault();
        fnSubmit(true);
    });
    */
});