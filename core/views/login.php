
<div id="topmenu"></div>
</div>

<div id="wrapper">
	<div id="content" class="login">
        <div class="content-login">
	        <form id="form" action="<?php echo base_url() ?>login/dologin" method="post">
	        	<h1>Bienvenido</h1>
	        	<h3>Ingrese su usuario y contrase&ntilde;a</h3>
	 			<fieldset>
					<label for="user">Usuario</label> 
					<input class="form-control" type="text" name="user" id="user">
				</fieldset>
				
				<fieldset>
					<label for="password">Contraseña</label>
					<input class="form-control" type="password" name="password" id="password">
				</fieldset>

				<div align="center">
				  <button class="btn" type="submit">Ingresar</button> 
				</div>
	        </form>
		</div>
		<?php 
		if ($error) {echo '<div class="error"><span>'.$error.'</span></div>';} ?>
	</div>
</div>

<style type="text/css">
	fieldset {
		display: flex;
		flex-direction: column;
		align-items:  center;
	}
</style>