<?php

class Grupo_filtros extends MY_Controller
{

	function __construct()
	{

		parent::__construct();

		/////////////////////
		/// Configuración ///
		/////////////////////

		$controller_config["script"] = "grupo_filtros";


		//////////////////////////////
		/// Opciones de las vistas ///
		//////////////////////////////

		// Nombre del listado
		$controller_config["name"] = "filtros";

		// Opcionales
		$controller_config["ordenar"] = true;

		// Ordeno el listado
		if(! isset($_GET['order'])){
	    $_GET['order'] = array(
				'categoria_id' => 'ASC',
				'orden' => 'ASC',
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
				'key'	=> 'categoria_id',
				'label'	=> 'Categoría',
				'type'	=> 'form_dropdown',
				'filter' => 'match',
				'list'	=> true,
				'class'	=> 'form-full label-up',
				'properties' => array(
					'name'    => 'categoria_id',
					'options' => array("" => "Elija una categoría") + $this->getOptions('categorias_model', 'nombre')
				)
			),

			array(
				'key'	=> 'nombre',
				'label'	=> 'Grupo',
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
				'key'    => 'filtros',
				'label'  => '',
				'type'   => 'child_relation_iframe',
				'filter' => false,
				'titulo' => 'Filtros',
				'properties' => array(
					'child_controller' => 'filtros',
					'child_model'      => 'filtros_model',
					'foreign_key'      => 'grupo_id',
					'controlador_hermano' => 'grupo_filtros',
					'fk_hermano' => 'id',
					'campo_descriptor'  => 'nombre'
				)
			),

		);

		$this->cargar_config($controller_config);
	}
}
