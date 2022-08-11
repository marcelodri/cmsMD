<div id="wrapper">
    <div id="content">
        <div id="box">
			<div style="display:none;" class='info'></div>
			<div style="display:none;" class='error'></div>
            <h3 id="adduser" style="margin-bottom:20px;">PDF</h3>

			<!-- Botones -->
			<button class="boton" style="float: right; margin-bottom: 20px;">
				<a target="_blank" href="<?php echo $url ?>">Ver en el navegador</a>
			</button>

			<button class="boton" style="float: right; margin-bottom: 20px; margin-right: 20px;">
				<a download target="_blank" href="<?php echo $url ?>" download>Descargar</a>
			</button>

			<!-- Visualización del archivo generdado-->
			<iframe style="width:100%; height:600px;" src="<?= $url ?>"></iframe>

			<!-- Pie -->
			<a href="<?php echo $url ?>" target="_blank" style="display:block; margin-top: 50px; visibility: hidden"><?php echo $url ?></a>

        </div>
    </div>

</div>