<?php

class Categorias extends MY_Controller
{

	function __construct()
	{

		parent::__construct();

		/////////////////////
		/// Configuración ///
		/////////////////////

		$controller_config["script"] = "categorias";


		//////////////////////////////
		/// Opciones de las vistas ///
		//////////////////////////////

		// Nombre del listado
		$controller_config["name"] = "categorías";

		// Opcionales
		$controller_config["ordenar"] = true;

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
					'maxlength' => 45,
				)
			),

			array(
				'key'	=> 'color',
				'label'	=> 'Color',
				'type'	=> 'form_input',
				'filter' => false,
				'list'	=> false,
				'class'	=> 'form-half label-up',
				'properties' => array(
					'name'         => 'color',
					'id'           => 'color',
					'maxlength'    => '6',
					'class'		=> 'colorpicker_selector',
				)
			),

			array(
				'key'    => 'icono_id',
				'label'  => 'Ícono',
				'type'   => 'jcropimage',
				'filter' => false,
				'list'   => false,
				'class'  => 'form-third label-up',
				'properties' => array(
					'id'       => 'icono_id',
					'name'     => 'icono_id',
					'quantity' => 1,
					'sizes'    => array(
						array('width' => '60', 'height' => 'auto', 'method' => 'crop'),
					),
					'siempre_jpg' => false,
					'margenes'    => false,
					'controller'  => $controller_config["script"]
				)
			),
		);

		$this->cargar_config($controller_config);
	}
}
