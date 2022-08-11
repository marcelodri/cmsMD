 <div id="wrapper">
  <div id="content">

 		<a style="font-weight: bold; display:block; padding-bottom:15px;" href="<?=base_url()?>productos">< Volver</a>
		<form method="post" enctype="multipart/form-data">
			<div class="form__campo">
				<input class="form-control" style="width: 300px; margin-top: 5px;" type="file" name="spreadsheet"/>
				<button class="boton" type="submit">Cargar XLS</button>
			</div>
		</form>

 		<div style="width: 100%;margin-top: 20px;"></div>
		<?= $error ? '<p class="alert alert-danger">'.$error.'</p>' : ''?>
		<?= $output ? '<br><br><div style="alert">'.$output.'</div>' : ''?>

	</div>
</div>