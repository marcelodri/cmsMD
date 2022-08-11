<?php include('head.php');?>

	<div id="container">
		<div class="contenedor">
			<div id="header">
				<h2><?php if( isset($nombre) AND $nombre!="" ){echo 'Bienvenido , '.$nombre.'';} ?></h2>
				<?php if( isset($nombre) AND $nombre!="" ){
					echo '<div class="salir">Log out<a href="'.base_url().'login/logout" title="Cerrar sesión"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M4.16666 18.3332C3.94565 18.3332 3.73369 18.2454 3.57741 18.0891C3.42113 17.9328 3.33333 17.7209 3.33333 17.4998V2.49984C3.33333 2.27882 3.42113 2.06686 3.57741 1.91058C3.73369 1.7543 3.94565 1.6665 4.16666 1.6665H15.8333C16.0543 1.6665 16.2663 1.7543 16.4226 1.91058C16.5789 2.06686 16.6667 2.27882 16.6667 2.49984V4.99984H15V3.33317H4.99999V16.6665H15V14.9998H16.6667V17.4998C16.6667 17.7209 16.5789 17.9328 16.4226 18.0891C16.2663 18.2454 16.0543 18.3332 15.8333 18.3332H4.16666ZM15 13.3332V10.8332H9.16666V9.1665H15V6.6665L19.1667 9.99984L15 13.3332Z" fill="#333333"/>
</svg>
</a></div>';
				}

				?>
