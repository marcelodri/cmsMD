<?php

$archivo = is_array($archivo) ? (object) $archivo : $archivo;

$url_eliminar = base_url().$controlador.'/deleteFile/'.$campo. '/'. $archivo->id. '/'. $controlador_id;
$url_ver  = FILES_RESOURCES_URL.$controlador.'/'.$archivo->id.'/'.$archivo->nombre.'.'.$archivo->extension;

?>
<div class="js-fineuploader-item" data-id="<?= $archivo->id ?>">
    <a href="<?= $url_ver ?>" target="_blank">
        <?= $archivo->nombre .'.'.$archivo->extension ?>
    </a>

    <a class="js-fineuploader-editar-titulo" href="#" style="color: #0d78b3;" title="Editar título">
        <i class="fa fa-tag fa-lg" aria-hidden="true"></i>
    </a>

    <a class="js-fineuploader-eliminar" href="<?= $url_eliminar ?>" style="color: #da0e0e;">
        <i class="fa fa-times fa-lg" aria-hidden="true"></i>
    </a>
                        
    <input type="hidden" name="<?= $campo ?>" value="<?= $archivo->id ?>">
</div>

<script>
$(".js-fineuploader-eliminar").click(function(){
    var $enlace = $(this);
    var $enlaceContenedor = $enlace.closest('.js-fineuploader-item');
    if(confirm('¿Estás seguro de eliminar este archivo?')){
        $.ajax({
          url: $enlace.attr('href'),

          beforeSend: function(){
            $enlaceContenedor.html("<img src=\"<?= base_url()?>core/images/ajax-loader.gif\"/>");
          },

          error: function(jqXHR, textStatus, errorThrown){
            alert(jqXHR.responseText || 'Se perdió la conexión con el servidor. Asegurate de estar conectado a internet');
          },

          success: function(){
            $.ajax({
              url: '<?= base_url() ?>campos/fileUploader/cargar_vista/',
              data:{
                'vista': 'cargar',
                'datos': <?= json_encode(array(
                    'controlador' => $controlador,
                    'controlador_id' => $controlador_id,
                    'modelo' => $modelo,
                    'campo' => $campo,
                    'allowed_types' => $allowed_types,
                    'max_size' => $max_size,
                    )); ?>,
                'devolver': false
              },

              beforeSend: function(){
                $enlaceContenedor.html("<img src=\"<?= base_url()?>core/images/ajax-loader.gif\"/>");
              },

              error: function(jqXHR, textStatus, errorThrown){
                alert(jqXHR.responseText || 'Se perdió la conexión con el servidor. Asegurate de estar conectado a internet');
              },
              
              success: function(html){
                $enlaceContenedor.html(html);
              }
            });

          }
        });
    }
    return false;
});

$(".js-fineuploader-editar-titulo").click(function(){
    var id = $(this).closest('.js-fineuploader-item').data('id');
    archivoCambiarTitulo(id);
    return false;
});
</script>