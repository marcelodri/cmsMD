<?php

	$html_acciones = '';

	foreach( $actions as $action_key => $action ){

		// Remplazo los uri_parameters definidos
		$href = is_string($action) ? $action : $action['href'];
		$href = str_replace('{uriParameters}', $uriParameters, $href);

		// Y todos los otros valos extraidos de cada item
		preg_match_all("/{(.*?)}/", $href, $variables);
		foreach($variables[1] as $var){
			if(isset($item->$var)){
				$href = str_replace('{'.$var.'}', mb_strtolower(str_replace(' ','-',$item->$var)), $href);
			}
		}

		// Modo personalizable
		if(is_array($action)){
			$class = isset($action['class']) ? $action['class'] : '';
			$style = isset($action['style']) ? $action['style'] : '';
			$body = isset($action['body']) ? $action['body'] : str_replace('-', ' ',$action_key);
			$title = isset($action['title']) ? $action['title'] : '';
			$html_acciones =
				'<a href="'.$href.'" class="'.$class.'" style="'.$style.'" title="'.$title.'">'.$body.'</a>'.
				$html_acciones;
			continue;
		}

		// Modo plantilla
		switch( $action_key ){

			case 'delete':
				$html_acciones = '<a href="'.$href.'" title="Eliminar"><i class="fa fa-times" style="color: #a61c1c;" aria-hidden="true"></i></a>'.$html_acciones;
				break;

			case 'editar':
			case 'edit':
				$html_acciones = '<a href="'.$href.'" title="Editar"><i class="fa fa-pencil" aria-hidden="true"></i></a>'.$html_acciones;
				break;

			case 'preview':
				$html_acciones = '<a href="'.$href.'" target="_blank" title="Ver vista previa"><i class="fa fa-search" style="color: #cccccc;" aria-hidden="true"></i></a>'.$html_acciones;
				break;

			case 'clonar':
				$html_acciones = '<a href="'.$href.'" title="Clonar"><i class="fa fa-clone" aria-hidden="true"></i></a>'.$html_acciones;
				break;
			
			case 'guardar':
				$html_acciones = '<a href="'.$href.'" title="Guardar"><i class="fa fa-save" aria-hidden="true"></i></a>'.$html_acciones;
				break;

			case 'presentacion':
				if( isset($item->activa) AND $item->activa ){
					$html_acciones = '<a href="'.$href.'" title="Generar presentación" target="_blank">.<i class="fa fa-file-text-o" aria-hidden="true"></i></a>'.$html_acciones;
				}
				break;

			default:
				$html_acciones = '<a href="'.$href.'" class="boton boton--sm">'.str_replace('-', ' ',$action_key).'</a>'.$html_acciones;
				break;
		}

	}

	return $html_acciones;