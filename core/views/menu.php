	<?php

	// Utilidades de la vista
	function vistaSolapasCrearEnlace($solapa){
		return base_url().
			$solapa->controlador.
			($solapa->listar!='todos' ? '/edit/'.$solapa->listar : '').
			($solapa->categoria!='' ? ($solapa->listar=='todos' ? '/index' : '').'?categ='.$solapa->categoria : '').
			(isset($solapa->QS) ? '?'.http_build_query($solapa->QS) : '');
	}
	function vistaSolapasEstaActivaSolapa($current_script, $solapa){
		$activo = false;
		if($current_script == $solapa->controlador){
			if($solapa->categoria!=''){
				if(isset($_GET['categ']) AND $_GET['categ'] == $solapa->categoria){
					$activo = true;
				}
			}else{
				$activo = true;
			}
		}
		return $activo;
	}
	function vistaSolapasEstaActivoGrupo($current_script, $solapas_grupo){
		foreach($solapas_grupo as $solapa){
			if(vistaSolapasEstaActivaSolapa($current_script, $solapa)){
				return true;
			}
		}
		return false;
	}


	if(count($grupos) > 1){
	?>
	<div class="menu-grupos">
		<ul>
			<?php
			$solapas_grupo_activo = [];
			foreach ($grupos as $grupo) {
				$solapas_grupo = array_filter($menu, function($solapa) use ($grupo){
					return $solapa->grupo === $grupo AND $solapa->tipo != 'oculta';
				});
				$activo = vistaSolapasEstaActivoGrupo($current_script, $solapas_grupo);
				if($activo){
					$solapas_grupo_activo = $solapas_grupo;
				}
				echo '<li><a '.($activo ? 'class="is-active"': '').' href="'.vistaSolapasCrearEnlace(array_shift($solapas_grupo)).'">'.
					$grupo.
				'</a></li>'.PHP_EOL;
			}?>
		</ul>
	</div>

	<style>
		.menu-grupos {
			margin: 35px 0 -8px;
		}
		.menu-grupos ul {
		    display: flex!important;
		    float: none!important;
		    flex-flow: row nowrap;
			align-items: center;
		    justify-content: space-around;
		}
		.menu-grupos li {
			flex-grow: 1;
			display: inline-block;
			list-style-type: none;
		}
		.menu-grupos a {
			display: block;
			padding: 12px;
			margin: 0 6px;
			background: black;
			text-transform: uppercase;
			text-align: center;
			color:white;
			font-weight: bold;
			font-size: 14px;
			font-family: 'Segoe UI';
		}
		.menu-grupos a:hover,
		.menu-grupos a:focus,
		.menu-grupos a.is-active {
			color: black;
			background-color:white;
			padding-bottom: 7px;
			border-bottom: 5px solid #FCC35C;
		}
		.menu-grupos li:first-child a {
			margin-left: 0;
		}
		.menu-grupos li:last-child a {
			margin-right 0;
		}
	</style>

	<?php } ?>

	<div id="topmenu">
		<ul>
			<?php
			$solapas_grupo_activo = count($grupos)>1 ? $solapas_grupo_activo : $menu;
			foreach( $solapas_grupo_activo AS $solapa ){
				$activo = vistaSolapasEstaActivaSolapa($current_script, $solapa);
				echo '<li class="'.($activo ? 'current' : '').' '.
									($this->session->userdata('perfil')=='superadmin' ? $solapa->tipo : '').
									( ($this->session->userdata('perfil')!='superadmin' AND $solapa->tipo=='oculta') ? 'hidden': '').'">'.
									'<a href="'.vistaSolapasCrearEnlace($solapa).'">'.$solapa->nombre.'</a></li>';
			}
			?>
		</ul>
	</div>

</div>
