<?php

class Productos_filtros extends MY_Controller
{

	function __construct()
	{

		parent::__construct();

		/////////////////////
		/// Configuración ///
		/////////////////////

		$controller_config["script"] = "productos_filtros";


		//////////////////////////////
		/// Opciones de las vistas ///
		//////////////////////////////

		// Nombre del listado
		$controller_config["name"] = "filtros";

		// Opcionales
		$controller_config["ordenar"] = false;

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
				'key'	=> 'producto_id',
				'label'	=> 'Producto',
				'type'	=> 'form_dropdown',
				'filter' => false,
				'list'	=> true,
				'class'	=> 'form-full label-up',
				'properties' => array(
					'name'    => 'producto_id',
					'options' => array("" => "Elija un producto") + $this->getOptions('productos_model', 'titulo')
				)
			),

			array(
				'key'	=> 'filtro_id',
				'label'	=> 'Filtro',
				'type'	=> 'form_dropdown',
				'filter' => false,
				'list'	=> true,
				'class'	=> 'form-full label-up',
				'properties' => array(
					'name'    => 'filtro_id',
					'options' => array("" => "Elija un filtro") + $this->getOptions('filtros_model', 'nombre')
				)
			),

		);

		$this->cargar_config($controller_config);
	}
}
