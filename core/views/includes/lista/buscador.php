<?php

$buscador_campos_cantidad = count($campos_form);

//Construyo el buscador
$html_buscador = '<form id="form" class="filtro">';

// Mantengo todo los parámetros pasados por URL
// Restablezco el orden y página al hacer búsquedas
$GET = $_GET;
unset($GET['order']);
unset($GET['page']);
$html_buscador .= form_hidden(array_keys(array_flip($GET)));

$busqueda_activa = false;

foreach($campos_form as $key=>$field){

	// Si no esta activado el filter para este campo continuo
	if(!isset($field['filter']) OR $field['filter']==null){
		$buscador_campos_cantidad--;
		continue;
	}

	// Controlo si se hizo alguna búsqueda
	$busqueda_activa = $this->input->get($field['key'])  ? true : $busqueda_activa;

	///// Hago algunos ajustes antes de construir los filtros de búsqueda /////
	switch($field['type']){

		case 'form_dropdown':
		case 'form_dropdown_ajax':
		case 'form_multiselect_simple':
		case 'form_dropdown_nueva_opcion':
			$field['properties']['options'] =  array( ''=>'Sin especificar' ) + $field['properties']['options'];
			if ($field['type'] != 'form_dropdown_ajax') {
				$field['type'] = 'form_dropdown';
			}
			break;

		case 'form_textarea':
			$field['type']='form_input';
			break;
	}

	///// Construyo los filtros de búsqueda /////
	// Si es un campo del controlador local lo consulto
	if($field['type'] == 'date' OR $field['type']=='datetime'){

		// Evito que el buscador tenga una fecha por defecto
		// (en realidad sí la tiene, pero dentro del datepicker)
		$field['properties']['value'] = $this->input->get($field['key']);
		$html_buscador .= '<fieldset>'.
							'<label for="'.$field['key'].'">'.
								( in_array($field['filter'], array('<','>','>=','<=')) ?
								    ( in_array($field['filter'], array('<','<=')) ?
								     	'Hasta' : 'Desde').' el ' : $field['label']).
							'</label>'.
							$this->functions->make_form('date',$field['properties']).
						  '</fieldset>';

	}else{
		$html_buscador .= '<fieldset><label for="'.$field['key'].'">'.$field['label'].'</label> '.$this->functions->make_form(
			$field['type'],
			$field['properties'],
			$this->input->get($field['key']),
			0,
			'buscador'
		).'</fieldset>';
	}
}
$html_buscador .= '<button type="submit">Buscar</button>';
$html_buscador .= $busqueda_activa ?'<button style="float: left;" type="button"><a href="'.$return_url.'">Limpiar</a></button> ' : '';

// Filtro por categoría
$html_buscador .= $this->input->get('categ') ? '<input type="hidden" name="categ" value="'. $this->input->get('categ') .'">' : '';

$html_buscador .= '</form>';

return ( $buscador_campos_cantidad>0 ) ? $html_buscador : '';