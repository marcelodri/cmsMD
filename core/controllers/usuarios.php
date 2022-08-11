<?php

class Usuarios extends MY_Controller {

	function __construct() {

		parent::__construct();

		/////////////////////
		/// Configuración ///
		/////////////////////

		$controller_config["script"] = "usuarios";


		//////////////////////////////
		/// Opciones de las vistas ///
		//////////////////////////////


		// Nombre del listado
		$controller_config["name"] = "usuarios";

		// Opcionales
		$controller_config["ordenar"] = false;

		// Ordeno el listado
		if(! isset($_GET['order'])){
	    $_GET['order'] = array(
				'id' => 'DESC',
			);
		}
		
		// Filtro el listado
		$_GET['perfil'] = 'cliente';

		// Acciones
		$controller_config['actions_list'] = array(
			'editar'  =>	base_url() . $controller_config['script'] . '/edit/{id}/{uriParameters}',
			'delete'	=>	'javascript:eliminarAjax({id});'
		);


		///////////////////////////////////
		/// Configuración de los campos ///
		///////////////////////////////////

    
    $campos = array();
    
    $campos[] = array(
      'key'	=> 'cuil',
      'label'	=> 'CUIL',
      'type'	=> 'form_input',
      'filter' => false,
      'list'	=> false,
      'class'	=> 'form-third label-up',
      'properties' => array(
        'name'      => 'cuil',
        'maxlength' => 20,
      )
    );
    
    foreach([
      'telefono' => 'Teléfono',
      'empresa' => 'Empresa',
      'actividad' => 'Actividad',
      'cargo' => 'Cargo',
      'direccion' => 'Dirección',
      'localidad' => 'Localidad',
      'provincia' => 'Provincia',
      'pais' => 'País',
    ] as $key => $label){
      $campos[] = array(
        'key'	=> $key,
        'label'	=> $label,
        'type'	=> 'form_input',
        'filter' => in_array($key, ['empresa']) ? 'like' : false,
        'list'	=> in_array($key, ['empresa']),
        'class'	=> 'form-third label-up',
        'properties' => array(
          'name'      => $key,
          'maxlength' => 200,
        )
      );
    }
    foreach([
      'ficha' => 'Ficha',
      'certificado' => 'Certificado',
      'plano' => 'Plano',
      'curvas' => 'Curvas',
      'archivo_3d' => '3D',
    ] as $key => $label){
      $campos[] = array(
        'key'	=> $key,
        'label'	=> $label,
        'type'	=> 'form_checkbox',
        'filter' => false,
        'list'	=> false,
        'class'	=> 'form-quarter label-up clear',
        'properties' => array(
          'name'      => $key,
          'id'      => $key,
          'value'    => '1',
        )
      );
    }
    
		$campos[] = array(
			'titulo' => 'Datos del usuario',
			'key'	=> 'activo',
			'label'	=> 'Activo',
			'type'	=> 'form_checkbox',
			'filter' => false,
			'list'	=> true,
			'class'	=> 'form-quarter label-up clear',
			'properties' => array(
				'name'     => 'activo',
				'id'       => 'activo',
				'value'    => '1',
				'checked'    => 'checked',
			)
    );
    
    $campos[] = array(
      'key'	=> 'nombre',
      'label'	=> 'Nombre',
      'type'	=> 'form_input',
      'filter' => 'like',
      'list'	=> true,
      'class'	=> 'form-third clear label-up',
      'properties' => array(
        'name'      => 'nombre',
        'maxlength' => 255,
      )
    );

    $campos[] = array(
      'key'	=> 'email',
      'label'	=> 'Email',
      'type'	=> 'form_input',
      'filter' => 'like',
      'list'	=> true,
      'class'	=> 'form-third clear label-up',
      'properties' => array(
        'name'      => 'email',
        'maxlength' => 255,
      )
    );

		$campos[] = array(
			'key'=>'password',
			'label'=>'Clave',
			'type'      =>'form_password',
			'validate'  => 'password_validate',
			'filter'=>false,
			'class'	=>'form-half label-up cl-b',
			'properties'=> array(
				'name'  => 'password',
				'id'    => 'password',
			)
		);

		$campos[] = array(
			'key'=>'password_validate',
			'label'=>'Repetir clave',
			'type'=>'form_password',
			'filter'=>false,
			'class'	=>'form-half label-up cl-b',
			'properties'=> array(
				'name' => 'password_validate',
				'id'   => 'password_validate',
			)
		);

    $controller_config["campos_form"] = $campos;

		$this->cargar_config($controller_config);
	}
}