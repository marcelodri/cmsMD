<?php

$buscador = include(APPPATH.'views/includes/lista/buscador.php');

$listado_cabecera = $this->load->view(
    'includes/lista/cabecera',
    array(
        'campos_form' => $campos_form,
        'ordenar'     => true, // Lo dejo en true para que genere el <td> vacío al principio
        'controller'  => $controller
    ), true);

$listado_cuerpo = '';
foreach($items as $key => $item){
    $campos = $this->load->view('includes/lista/campos', array('campos_form' => $campos_form, 'item' => $item), true);
	$listado_cuerpo .=
    '<tr class="item" data-id="'.$item->id.'">'.
        '<td width="50">
            <a href="#" class="js-CRPL-item checkbox-ajax">
                <i class="fa fa-'.($item->relacionado ? 'check-square-o checked' : 'square-o').'" aria-hidden="true"></i>
            </a>
        </td>'.
        $campos.
        '<td>&nbsp;</td>'.
    '</tr>';
}

?>

 <div id="wrapper">
    <div id="content">

        <?= $buscador ? '<div class="border-bottom">'.$buscador.'<div>' : '' ?>
        
        <div class="list-feedback js-feedback alert" style="display: none;"><?= isset($message) ? $message : ''; ?></div>

    	<?= $name ? '<h1>Listado de '.$name.'</h2>' : '<br><br>' ?>
        <table class="list" width="100%" style="margin-bottom:30px;">
            <thead>
               <?= $listado_cabecera; ?>
            </thead>
            <tbody>
				<?= ($listado_cuerpo!='') ? $listado_cuerpo : '<td colspan="'.(count($campos_form) + 1).'">No hay datos para mostrar.</td>'; ?>
            </tbody>
        </table>

        <?= $paginator ? '<div class="pager"></div>' : '' ?>

		<div class="lista_acciones-gral">
            <button class="boton js-CRPL-guardar">Guardar</button>
        </div>
    </div>
</div>

<script>
$(function(){
    $('.js-CRPL-item').click(function(){

        var id_propia = $(this).closest('[data-id]').data('id');
        var $fa     = $(this).find('.fa');
        var checked = $fa.hasClass('checked');

        $.ajax({
            type: 'get',
            url: DOCUMENT_ROOT + '<?= $controlador_hijo ?>' + '/actualizarRelacionCRPL/'+
                '<?= $fk_propia ?>' + '/' +
                id_propia + '/' +
                '<?= $fk_hermano ?>' + '/' +
                '<?= $id_hermano ?>' + '/',
            beforeSend: function() {
                $fa.removeClass('fa-check-square-o fa-square-o').addClass('fa-refresh fa-spin');
            },
            success: function() {
                if(checked){
                    $fa.addClass('fa-square-o').removeClass('checked');
                }else{
                    $fa.addClass('fa-check-square-o').addClass('checked');
                }
            },
            error: function() {
                if(checked){
                    $fa.addClass('fa-check-square-o');
                }else{
                    $fa.addClass('fa-square-o');
                }
                $('.js-feedback').addClass('alert-danger').html('Hubo un problema').show();
                setTimeout(function(){
                    $('.js-feedback').fadeOut(function(){ 
                        $(this).removeClass('alert-danger');
                    });
                },2500);
            },
            complete: function() {
                $fa.removeClass('fa-refresh fa-spin');
            },
        });

        return false;
    });

    $('.js-CRPL-guardar').click(function(){
        parent.$.fancybox.close();
    });
});
</script>