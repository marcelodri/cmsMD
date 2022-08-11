<div id="wrapper">
    <div id="content">

      <h1>Gallery manager</h2>

      <div style="margin-top:20px;">
         <a class="js-add-params boton" href="" target="_blank">Crear medidas</a>
         <input placeholder="controlador" class="js-controlador" style="float: none; height: 30px;" type="text">
         <span><input style="float: none; height: 30px;" class="js-sobreescribir" type="checkbox"> Sobreescribir</span>
         <br /><br />

         <a class="boton" href="gallery_manager/crearMedidas/marcaDeAgua/true/sobreescribir/true" target="_blank">Agregar marca de agua</a>
      </div>

      <script>
      $('.js-add-params').click(function(){
         $(this).attr('href', 'gallery_manager/crearMedidas?controlador=' + $('.js-controlador').val() );
         if( $('.js-sobreescribir').is(':checked') ){
      	  $(this).attr('href', $(this).attr('href') + '&sobreescribir=true');
         }
      });
      </script>
</div>
