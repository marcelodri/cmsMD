<div id="wrapper">
  <div id="content">
    <div id="box">

      <div style="display:none;" class='info'></div>
      <div style="display:none;" class='error'></div>

      <h3 id="adduser"><?php echo $title ?></h3>

      <form class="registro" id="form" action="<?php echo base_url().$form_action.$uriParameters ?>" method="post" enctype="multipart/form-data">
        <fieldset id="personal">
        <h4 class="form__titulo">Datos</h4>
          <?php
          foreach($campos as $key=>$values){

              //Título de sección
              echo (isset($values['titulo']) AND $values['titulo']) ? '<h4 class="form__titulo">'.$values["titulo"].'</h4>' : '';

              //El campo
              echo '<div class="form__campo '.( isset($values["class"]) ? $values["class"] : '').'" '
              .( (isset($values["hidden"]) AND $values["hidden"]) ? 'style="height: 0; overflow: hidden; padding: 0; margin: 0; "' : '' ).'>';

                  echo ($values["label"]=='' OR $values["type"]=='form_hidden') ? '' : '<label for="'.$key.'">'.$values["label"].': </label>';

                  echo $values['html'];

                  //Comentario
                  echo (isset($values["comentario"]) AND $values['comentario']!='') ? '<p class="form__comentario">'.$values['comentario'].'</p>' : '';

              echo '</div>';

              //Después de los campos
              echo (isset($values["extra_html"]) AND $values['extra_html']!='') ? $values['extra_html'] : '';
          }
          ?>
        </fieldset>

        <div class="form__botones">
          <input id="button1" class="boton" type="submit" value="Guardar" />
          <input id="button2" class="boton" type="button" value="Cancelar" onclick="document.location.href = '<?php echo $return_url.$uriParameters ?>'"/>
          <!--<input id="button2" class="boton" type="reset" />-->
        </div>

        <input type="hidden" id="document_root" value="<?php echo base_url() ?>"/>
        <input type="hidden" id="controller" value="<?php echo $current_script ?>"/>
        <input type="hidden" id="uriParameters" value="<?php echo $uriParameters ?>"/>
        <input type="hidden" id="tmp_id" name="tmp_id" value="<?php echo (isset($tmp_id)) ? $tmp_id : '' ?>"/>
      </form>
    </div>
  </div>
</div>
