
<div id="wrapper">
    <div id="content">
        <div id="box">
        		<div style="display:none;" class='info'></div>
        		<div style="display:none;" class='error'></div>

            <h3 id="adduser">Generar PDF</h3>
            <form class="registro2" action="<?=base_url()?>pdf/generar_pdf" method="post" enctype="multipart/form-data">
          	  <fieldset id="personal">

                <legend>Generar PDF</legend>

                <div style='margin:7px'>
                    <label style="width: 100%;" for="id">Categoría: </label>
                    <select name="id">
                      <option value="0">Elija una categoría</option>
                      <?= $opciones ?>
                    </select>
                  </div>

              </fieldset>

              <div align="center">
                  <input class="boton" id="button1" type="submit" value="Generar PDF" />
              </div>
              <input type="hidden" id="document_root" value="<?php echo base_url() ?>"/>
              <input type="hidden" id="controller" value="<?php echo $current_script ?>"/>
            </form>

        </div>
    </div>
</div>
