<?php

class Sliders extends MY_Controller {

	function __construct() {

		parent::__construct();

		/////////////////////
		/// Configuración ///
		/////////////////////

		$controller_config["script"] = "sliders";


		//////////////////////////////
		/// Opciones de las vistas ///
		//////////////////////////////

		// Nombre del listado
		$controller_config["name"] = "sliders";

		// Opcionales
		$controller_config["ordenar"] = true;

		// Acciones
		$controller_config['actions_list'] = array(
			'editar'  =>	base_url() . $controller_config['script'] . '/edit/{id}/{uriParameters}',
			// 'preview' =>	base_url().'../nuevo/emprendimiento/{id}/vista-previa/',
			'delete'	=>	'javascript:eliminarAjax({id});'
		);


		///////////////////////////////////
		/// Configuración de los campos ///
		///////////////////////////////////

		$controller_config["campos_form"] = array(

			array(
				'key'	=> 'titulo',
				'label'	=> 'Titulo',
				'type'	=> 'form_input',
				'filter' => 'like',
				'list'	=> true,
				'class'	=> 'form-third label-up',
				'properties' => array(
					'name'      => 'titulo',
					'maxlength' => '45',
				)
			),

			// array(
			// 	'key'	=> 'bajada',
			// 	'label'	=> 'Bajada',
			// 	'type'	=> 'form_textarea',
			// 	'filter' => false,
			// 	'list'	=> false,
			// 	'class'	=> 'form-half clear label-up',
			// 	'properties' => array(
			// 		'name'  => 'bajada',
			// 		'id'    => 'bajada',
			// 		'maxlength' => '255',
			// 		'rows' => '4',
			// 	)
			// ),

			// array(
			// 	'key'	=> 'cuerpo',
			// 	'label'	=> 'Cuerpo',
			// 	'type'	=> 'form_textarea',
			// 	'filter' => false,
			// 	'list'	=> false,
			// 	'class'	=> 'form-half clear label-up',
			// 	'properties' => array(
			// 		'name'  => 'cuerpo',
			// 		'id'    => 'cuerpo',
			// 	)
			// ),

			array(
				'key'    => 'imagen_id',
				'label'  => 'Imagen',
				'type'   => 'jcropimage',
				'filter' => false,
				'list'   => true,
				'class'  => 'form-full clear label-up',
				'properties' => array(
					'id'       => 'imagen_id',
					'name'     => 'imagen_id',
					'quantity' => 20,
					'sizes'    => array(
						array('width' => '1300', 'height' => '600', 'method' => 'crop'),
					),
					'siempre_jpg' => true,
					'margenes'    => false,
					'controller'  => $controller_config["script"]
				)
			),

			// array(
			// 	'key'    => 'adjunto_id',
			// 	'label'  => 'Ficha técnica',
			// 	'type'   => 'form_upload',
			// 	'filter' => false,
			// 	'list'	=> false,
			// 	'class'  => 'form-half clear label-up',
			// 	'comentario' => 'Extensiones permitidas: .pdf',
			// 	'properties' => array(
			// 		'name'          => 'adjunto_id',
			// 		'allowed_types' => 'pdf',
			// 		'thumbnail' => false,
			// 	)
			// ),
		);

		$this->cargar_config($controller_config);
	}
}