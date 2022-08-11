<?php

//Obtengo los campos del listado
$campos = '';
foreach( $campos_form as $field ){


	$mostrar_campo = (isset($field['list']) && $field['list']===true);

	// No muestro el campo si está activo el filtro JavaScript para ese campo y se está filtrando
	if( isset($field['properties']['filtros']) ){
		foreach($field['properties']['filtros'] as $filtro_tipo => $filtro_valor){
 			if(isset($_GET[ $filtro_tipo ]) AND $_GET[ $filtro_tipo ]!=''){
 				$aplicar_filtro = false;
 				foreach(explode(' ',$filtro_valor) as $f_valor){
 					if($_GET[ $filtro_tipo ] == $f_valor){
 						$aplicar_filtro = true;
 						break;
 					}
 				}
 				// Solo muestro el campo si no se ocultó explícitamente con la propeidad "list" del campo
 				if(!$aplicar_filtro AND $mostrar_campo){
 					$mostrar_campo = false;
 				}
 			}
 		}
	}

	if( !$mostrar_campo)
		continue;

	$key = $field['key'];
	$ajax = isset($field['ajax']) && $field['ajax'] === true;
	$list_edit = isset($field['list_edit']) && $field['list_edit'] === true;
	switch( $field['type'] ){

		case 'email':
			$html_campo = '<a href="mailto:'.$item->$key.'">'.$item->$key.'</a>';
			break;

		case 'images':
			$ruta = $controller.'/'.$item->id.'/0_'.$field['key'].'_thumbnail.jpg';
			$html_campo = file_exists(IMAGES_RESOURCES_PATH.$ruta) ? '<img src="'.IMAGES_RESOURCES_URL.'/'.$ruta.'?v='.filemtime(IMAGES_RESOURCES_PATH.$ruta).'" style="height: 30px; width: auto;"/>' : '';
			break;

		case 'gallery':
			$galeria_id = $item->{$field['key'].'_id'};

			$CI =& get_instance();
			$CI->load->model('fotos_model');

			if($fotos_galeria = $CI->fotos_model->fotos_galeria($galeria_id)){
				$ruta = $galeria_id.'/'.$fotos_galeria[0]->filename.'_thumbnail.'.$fotos_galeria[0]->extension;
				$html_campo = file_exists(GALLERIES_RESOURCES_PATH.$ruta) ? '<img src="'.GALLERIES_RESOURCES_URL.$ruta.'?v='.filemtime(GALLERIES_RESOURCES_PATH.$ruta).'" style="height: 30px; width: auto;"/>' : '';
			}
			break;

		case 'jcropimage':
			$html_campo = '';
			if($item->{$field['key']}){
				$CI =& get_instance();
				$CI->load->model('fotos_model');
				$foto = $CI->fotos_model->get_item($item->{$field['key']});
				$ruta = $controller.'/'.$item->id.'/0_'.$field['key'].'_'.$field['properties']['sizes'][0]['height'].'x'.$field['properties']['sizes'][0]['width'].'.'.$foto->extension;
				$html_campo = file_exists(IMAGES_RESOURCES_PATH.$ruta) ? '<img src="'. IMAGES_RESOURCES_URL.$ruta .'?v='.filemtime(IMAGES_RESOURCES_PATH.$ruta).'" style="height: 30px; width: auto;"/>' : '';
			}
			break;

		case 'form_checkbox':
			if ($list_edit) {
				$html_campo = $this->functions->make_form(
					$field['type'],
					$field['properties'],
					$item->{$key}
				);
			} else{
				$html_campo =
				'<a href="#" class="js-checkbox-ajax checkbox-ajax">
					<i class="fa fa-'.($item->{$key} ? 'check-square-o' : 'square-o').'" aria-hidden="true"></i>
				</a>';
			}
			break;

		case 'form_input':
			if($ajax OR $list_edit){
				$html_campo =
				'<input
					'.($ajax ? 'class="js-input-ajax"' : '').'
					type="text"
					value="'.$item->$key.'"
					size="'.strlen($item->$key).'"
					onInput=this.size=this.value.length
					style="border: 0"
				/>';
			}else{
				$html_campo = ($item->$key!='') ? $item->$key : '-';
			}
			break;

		case 'form_upload':
			$html_campo = '';
			if($item->{$field['key']}){
				$CI =& get_instance();
				$CI->load->model('archivos_model');
				$archivo = $CI->archivos_model->get_item($item->{$field['key']});
				if($archivo->extension === 'svg'){
					$ruta = $controller.'/'.$archivo->id.'/'.$archivo->nombre.'.'.$archivo->extension;
					$html_campo = file_exists(FILES_RESOURCES_PATH.$ruta)
						? '<img src="'.FILES_RESOURCES_URL.$ruta.'" style="height: 30px; width: auto;"/>'
						: '';
				}
			}
			break;
		
		case 'date':
			if($ajax OR $list_edit){
				$field['properties']['class'] = isset($field['properties']['class'])
					? $field['properties']['class']
					: '';
				$field['properties']['class'] .= ($ajax ? ' js-date-ajax' : '');
				$field['properties']['style'] = 'width:80px';
				// Diferencio IDs para evitar que se rompa datepicker
				$field['properties']['id'] .= '-'.$item->id;
				$html_campo = $this->functions->make_form(
					$field['type'],
					$field['properties'],
					$item->$key
				);
			}else{
				$html_campo = ($item->$key!='') ? $item->$key : '-';
			}
			break;

		case 'form_dropdown':
		case 'form_dropdown_ajax':
		case 'form_dropdown_nueva_opcion':
		case 'form_dropdown_busqueda':
			if($field['type'] === 'form_dropdown_nueva_opcion'){
				$field['type'] = 'form_dropdown';
			}
			if($field['type'] === 'form_dropdown_ajax' AND $ajax){
				$field['type'] = 'form_dropdown';
			}
			if(($ajax OR $list_edit)
				AND !isset($field['properties']['disabled'])
				AND !isset($field['properties']['readonly'])
			){
				$field['properties']['class'] = isset($field['properties']['class'])
					? $field['properties']['class']
					: '';
				$field['properties']['class'] .= ($ajax ? ' js-form_dropdown-ajax': '');
				$field['properties']['style'] = 'width:120px';
				$html_campo = $this->functions->make_form(
					$field['type'],
					$field['properties'],
					$item->$key,
					$item->id,
					'lista'
				);
			}else{
				$html_campo = ($item->$key AND isset($field['properties']['options'][$item->$key])) ? $field['properties']['options'][$item->$key] : '-' ;
			}
			break;

		case 'form_multiselect_simple':
			$html_campo = '';
			if($item->$key){
				$labels = array();
				$selected_options = explode(',', $item->$key);
				foreach($selected_options as $option){
					if(isset($field['properties']['options'][$option])){
						$labels[] = $field['properties']['options'][$option];
					}
				}
				$html_campo = $labels ? implode(',', $labels) : '-';
			}
			break;

		case 'form_textarea':
			$this->load->helper('recortar_cadena');
			$html_campo = '<span title="'.$item->$key.'">'.recortarCadena(nl2br($item->$key), 50).'</span>';
			break;

		case 'child_relation_list':
		case 'child_relation_iframe':
			if($field['type'] === 'child_relation_list'){
				$key_controlador_hijo = $field['properties']['controlador_hijo'];
				$fk_propia = $field['properties']['fk_propia'];
			}elseif($field['type'] === 'child_relation_iframe'){
				$key_controlador_hijo = $field['properties']['child_controller'];
				$fk_propia = $field['properties']['foreign_key'];
			}
			$key_controlador_hermano = $field['properties']['controlador_hermano'];
    		$fk_hermano = $field['properties']['fk_hermano'];
    		$campo_descriptor = $field['properties']['campo_descriptor'];

			$CI =& get_instance();

			// Obtengo todos los items del controlador hermano
			$CI->cargar_controlador($key_controlador_hermano);
			$controlador_hermano = $CI->{$key_controlador_hermano};
			$modelo_hermano = $controlador_hermano->{$controlador_hermano->controller_config["model"]};
			$items = $modelo_hermano->get_query_list();

	    	// Obtengo las relaciones
	    	$CI->cargar_controlador($key_controlador_hijo);
	    	$controlador_hijo = $CI->{$key_controlador_hijo};
			$modelo_hijo = $controlador_hijo->{$controlador_hijo->controller_config["model"]};
	    	$relaciones = $modelo_hijo->get_query_list(array(
	    		' AND '.$controlador_hijo->getRelationFilters(
	    			$fk_propia,
	    			$item->id
	    		)
	    	));
	    	$id_relaciones = array_map(function($r) use ($fk_hermano){
	    		return $r->{$fk_hermano};
	    	}, $relaciones);

	    	// Filtro items por los relacionados
	    	$items = array_filter($items, function($item) use ($id_relaciones){
		    	return in_array($item->id, $id_relaciones);
		    });

		    // Obtengo labels de los items
	    	$items = array_map(function($item) use ($campo_descriptor){
	    		return $item->$campo_descriptor;
	    	}, $items);

			$html_campo = $items ? implode(',', $items) : '-';
			break;

		default:
			$html_campo = ($item->$key!='') ? $item->$key : '-';
			break;
	}

	if(isset($field['html_extra_list'])){
		// Reemplazo los valores de cada item en el HTML
		preg_match_all("/{(.*?)}/", $field['html_extra_list'], $variables);
		foreach($variables[1] as $var){
			if(isset($item->$var)){
				$field['html_extra_list'] = str_replace(
					'{'.$var.'}',
					$item->$var,
					$field['html_extra_list']
				);
			}
		}
		$html_campo .= $field['html_extra_list'];
	}
	$campos .= '<td data-key="'.$key.'">'.
		$html_campo.
	'</td>';

}

echo $campos;