<?php

class Marcas extends MY_Controller {

	function __construct() {

		parent::__construct();

		/////////////////////
		/// Configuración ///
		/////////////////////

		$controller_config["script"] = "marcas";


		//////////////////////////////
		/// Opciones de las vistas ///
		//////////////////////////////

		// Nombre del listado
		$controller_config["name"] = "marcas";

		// Ordeno el listado
		if(! isset($_GET['order'])){
	    $_GET['order'] = array(
				'nombre' => 'ASC',
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

			/* Input text */
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
				'key'    => 'logo_id',
				'label'  => 'Logo',
				'type'   => 'jcropimage',
				'filter' => false,
				'list'   => true,
				'class'  => 'form-full label-up',
				'properties' => array(
					'id'       => 'logo_id',
					'name'     => 'logo_id',
					'quantity' => 1,
					'sizes'    => array(
						array('width' => 'auto', 'height' => '50', 'method' => 'crop'),
					),
					'siempre_jpg' => 0,
					'margenes'    => false,
					'controller'  => $controller_config["script"]
				)
			),
		);

		$this->cargar_config($controller_config);
	}
}