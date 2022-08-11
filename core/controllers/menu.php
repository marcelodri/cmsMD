<?php

Class Menu extends MY_Controller {

    function __construct(){

        parent::__construct();


	     /////////////////////
	    /// Configuración ///
	   /////////////////////

        $controller_config["script"] 			= "menu";
        $controller_config["model"] 	= $controller_config["script"]."_model";

        $controller_config["ordenar"] = true;


	     //////////////////////////////
	    /// Opciones de las vistas ///
	   //////////////////////////////

        // Nombre del listado
        $controller_config["name"] = "pestañas";

        // Acciones
        $controller_config['actions_list'] = array(
        	'editar'	=>	base_url().$controller_config['script'].'/edit/{id}/{uriParameters}',
        	'delete'	=>	'javascript:eliminarAjax({id});'
        );


	 	 ///////////////////////////////////
	    /// Configuración de los campos ///
	   ///////////////////////////////////

		$controller_config["campos_form"] = array(

			array(
				'key'	=>'nombre',
				'label'	=>'Nombre',
				'type'	=>'form_input',
				'filter'=>null,
				'list'	=>true,
				'class'	=>'form-full label-up',
				'properties'=> array(
					'name'         => 'nombre',
					'id'           => 'nombre',
					'maxlength'    => '255'
				)
			),

			array(
				'key'	=>'controlador',
				'label'	=>'Controlador',
				'type'	=>'form_input',
				'filter'=>null,
				'list'	=>true,
				'class'	=>'form-full label-up',
				'properties'=> array(
					'name'         => 'controlador',
					'id'           => 'controlador',
					'maxlength'    => '255'
				)
			),

			array(
				'key'	=>'categoria',
				'label'	=>'Categoría',
				'type'	=>'form_input',
				'filter'=>null,
				'list'	=>true,
				'class'	=>'form-full label-up',
				'properties'=> array(
					'name'         => 'categoria',
					'id'           => 'categoria',
					'maxlength'    => '255'
				)
			),

			array(
				'key'	=>'tipo',
				'label'	=>'Tipo',
				'type'	=>'form_dropdown',
				'filter'=>null,
				'list'	=>true,
				'class'	=>'form-full label-up',
				'properties'=> array(
					'name'         => 'tipo',
					'id'           => 'tipo',
					'options'    => array(
						'normal' => 'normal',
						'modificada' => 'modificada',
						'oculta' => 'oculta'
					)
				)
			),

			array(
				'key'	=>'grupo',
				'label'	=>'Grupo',
				'type'	=>'form_dropdown',
				'filter'=>null,
				'list'	=>true,
				'class'	=>'form-full label-up',
				'properties'=> array(
					'name'         => 'grupo',
					'id'           => 'grupo',
					'options'    => array(
						'general' => 'General',
					)
				)
			),

			array(
				'key'	=>'perfil',
				'label'	=>'Perfil',
				'type'	=>'form_dropdown',
				'filter'=>null,
				'list'	=>true,
				'class'	=>'form-full label-up',
				'properties'=> array(
					'name'         => 'perfil',
					'id'           => 'perfil',
					'options'    => array(
						'2' => 'admin',
						'1' => 'superadmin',
						'3' => 'usuario'
					)
				)
			),

			array(
				'key'	=>'listar',
				'label'	=>'Listar',
				'type'	=>'form_input',
				'filter'=>null,
				'list'	=>true,
				'class'	=>'form-half label-up',
				'properties'=> array(
					'name'         => 'listar',
					'id'           => 'listar',
					'maxlength'    => '255',
					'default' => 'todos'
				)
			)
		);

        $this->cargar_config( $controller_config );
    }
}
