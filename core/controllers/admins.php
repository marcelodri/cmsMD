<?php

Class Admins extends MY_Controller {

    function __construct(){

        parent::__construct();


	     /////////////////////
	    /// Configuración ///
	   /////////////////////

        $controller_config["script"] 	= "admins";


	     //////////////////////////////
	    /// Opciones de las vistas ///
	   //////////////////////////////

        // Nombre del listado
        $controller_config["name"] = "usuarios";

        // Acciones
        $controller_config['actions_list'] = array(
        	'editar'	=>	base_url().$controller_config['script'].'/edit/{id}/{uriParameters}',
        	'delete'	=>	'javascript:eliminarAjax({id});'
        );

        // Campos que no se muestran sus valores al editar
        $controller_config['hide_value_fields'] = array('password','password_validate');



	 	 /////////////////////////////////////////
	    ////// Configuración de los campos //////
	   /////////////////////////////////////////

			// Muestro el perfil superadmin solo a los superadmins
			$perfiles = array('admin' => 'Admin');
	  	if( $this->session->userdata('perfil')=='superadmin' ){
	  		$perfiles['superadmin'] = 'Superadmin';
	  	}


        $controller_config["campos_form"] = array(

			array(
				'key'=>'nombre',
				'label'=>'Nombre completo',
				'type'=>'form_input',
				'filter'=>'like',
				'list'=>true,
				'class'	=>'form-third label-up cl-b',
				'properties'=> array(
					'name'        => 'nombre',
					'id'          => 'nombre',
					'maxlength'   => '255',
					)
				),

			array(
				'key'=>'username',
				'label'=>'Nombre de usuario',
				'type'=>'form_input',
				'filter'=>'like',
				'list'=>true,
				'class'	=>'form-third label-up cl-b',
				'properties'=> array(
					'name'        => 'username',
					'id'          => 'username',
					'maxlength'   => '255',
				)
			),

			array(
				'key'=>'email',
				'label'=>'E-mail',
				'type'=>'form_input',
				'filter'=>'like',
				'list'=>false,
				'class'	=>'form-third label-up cl-b',
				'properties'=> array(
					'name'        => 'email',
					'id'          => 'email',
					'maxlength'   => '100',
					'size'        => '25'
				)
			),

			array(
				'key'=>'password',
				'label'   =>'Clave',
				'type'      =>'form_password',
				'validate'  => 'password_validate',
				'filter'=>null,
				'class'	=>'form-half label-up cl-b',
				'properties'=> array(
					'name'        => 'password',
					'id'          => 'password',
					'maxlength'   => '255',
					'size'        => '25'
				)
			),

			array(
				'key'=>'password_validate',
				'label'=>'Repetir clave',
				'type'=>'form_password',
				'filter'=>null,
				'class'	=>'form-half label-up cl-b',
				'properties'=> array(
					'name'        => 'password_validate',
					'id'          => 'password_validate',
					'maxlength'   => '255',
					'size'        => '25'
				)
			),

			array(
					'key'=>'perfil',
					'label'=>'Perfil',
					'type'=>'form_dropdown',
					'filter'=>null,
					'class'	=>'form-half label-up cl-b',
					'list'=>true,
					'hidden'=>false,
					'properties'=> array(
					  'name'        => 'perfil',
					  'id'          => 'perfil',
					  'options'   => $perfiles
				)
			),

        );

        $this->cargar_config( $controller_config );
    }
}
