<?php

class Filtros extends MY_Controller
{


	function __construct()
	{

		parent::__construct();

		/////////////////////
		/// Configuración ///
		/////////////////////

		$controller_config["script"] = "filtros";

		$controller_config['parents'] = array(
			'grupo_filtros' => array(
				'foreign_key' => 'grupo_id',
				'parent_model' => 'grupo_filtros_model'
			)
		);


		//////////////////////////////
		/// Opciones de las vistas ///
		//////////////////////////////

		// Nombre del listado
		$controller_config["name"] = false;

		// Opcionales
		$controller_config["ordenar"] = true;

		// Ordeno el listado
		if (!isset($_GET['order'])) {
			$_GET['order'] = array(
				'orden' => 'ASC',
				'id' => 'ASC',
			);
		}

		// Acciones
		$controller_config['actions_list'] = array(
			'editar'  =>	base_url() . $controller_config['script'] . '/edit/{id}/{uriParameters}',
			'delete'	=>	'javascript:eliminarAjax({id});'
		);


		///////////////////////////////////
		/// Configuración de los campos ///
		///////////////////////////////////

		$controller_config["campos_form"] = array(

			array(
				'key'	=> 'nombre',
				'label'	=> 'Nombre',
				'type'	=> 'form_input',
				'filter' => false,
				'list'	=> true,
				'class'	=> 'form-third label-up',
				'properties' => array(
					'name'      => 'nombre',
					'maxlength' => 50,
				)
			),

			array(
				'key'	=> 'grupo_id',
				'label'	=> 'Grupo',
				'type'	=> 'form_dropdown',
				'filter' => false,
				'list'	=> false,
				'hidden'	=> true,
				'class'	=> 'form-full label-up',
				'properties' => array(
					'name'    => 'grupo_id',
					'options' => array("" => "Elija un grupo") + $this->getOptions('grupo_filtros_model', 'nombre')
				)
			),

		);

		$this->cargar_config($controller_config);
	}
}
